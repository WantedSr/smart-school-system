<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn_message = new Link("commonly_message");
        $conn_tb_message = new Link("tb_message");
    } catch (PDOException $e){
        echo json_encode([
            'msg'=> '服务器错误',
            'error'=> '1000'
        ]);
    }

    $data = [];

    try{
        $action = $_POST['action'] ? $_POST['action'] : null;
        // var_dump();
        switch ($action){
            case 'setMessage': setMessage(); break;
            case 'getMessage': getMessage(); break;
            case 'upMessage': upMessage(); break;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'msg'=> 'error',
            'error'=> $e->getMessage()
        ]);
    }
    
    /**
     * 发布消息
     * @param entity array （用户和信息数据）
     * -> 例：{"title": "dsf;ewqr", "type": "1", "target": "department", "content": "ewrjerl", "aims": "S0001_A_01", "mes_type": "1", "semester": "202101", "department": "S0001_A_01", "campus": "S0001_A", "school": "S0001", "userid": "2017217038"} 
     * -> 必须
     * 
     * type: 1、所有人 2、学生 3、老师
     */
    function setMessage(){
        global $conn_message;
        global $conn_tb_message;
        global $data;
        $entity = $_POST['entity'] ? (array)json_decode($_POST['entity']) : null;
        
        $commomd_message_obj = _parseEntity($entity);
        $now = time();
        array_push($commomd_message_obj, $now);
        // 保存到公共信息
        $state = $conn_message->insert($commomd_message_obj);

        if (!$state){
            return false;
        }

        $message_data = [_getMessageIdByTime($now)];
        $message_data = array_merge($message_data, _parseMessageObject($entity));
        array_push($message_data, time());
        $status = $conn_tb_message->insert($message_data);
        $data = [
            "state" => $status
        ];
    }

    /**
     * 查阅消息
     * @param userid string （用户id）：2017217042 必须
     * 
     * D 类用户，返回所有信息
     * S 类用户和 T 类用户返回指定信息
     */
    function getMessage(){
        global $conn_message;
        global $data;
        $userid = $_POST['userid'] ? $_POST['userid'] : null;
        $group = _checkUser($userid);
        if ($group == 1){
            // 不指定消息类型，直接返回所有消息
            $data = $conn_message->select_more();
        }
        $user = _selectTable($group, $userid);
        if ($user == null){
            return false;
        }
        // 获取自己相关对
        $res = $conn_message->select_more('*', [
            "type"=> $group
        ]);
        // 获取所有人的通知
        $msg_data = $conn_message->select_more('*', [
            "type"=> 1
        ]);
        foreach($res as $item){
            if ($user[$item['target']] == $item['aims']){
                array_push($msg_data, $item);
            }
        }
        $data = $msg_data;
    }

    /**
     * 更新消息
     * @param id number (消息id): 1 必须
     * @param uppost array (需要更改的地方): {"aims": "S0001_A_02"} 必须
     */
    function upMessage(){
        global $data;
        global $conn_message;
        $message_id = $_POST['id'] ? (int)$_POST['id'] : null;
        if ($message_id == null){
            return false;
        }
        $uppost = $_POST['uppost'] ? (array)json_decode($_POST['uppost']) : null;
        $uppost = _parseEntity($uppost, true);
        $state = $conn_message->update($uppost, 'id', $message_id);
        $data = [
            "state" => $state
        ];
    }

    echo json_encode([
        'msg'=> '成功',
        'error'=> 'null',
        'data'=> $data
    ]);

    function _selectTable($group, $userid){
        if ($group == 2){
            $conn_student = new Link("student_info");
            return $conn_student->select_more('*', [
                "userid" => $userid
            ])[0];
        } else if ($group == 3){
            $conn_teacher = new Link("teacher_info");
            return $conn_teacher->select_more('*', [
                "userid" => $userid
            ])[0];
        }
        return null;
    }
    
    function _checkUser($userid){
        $conn_user = new Link("tb_login");
        $group = $conn_user->select_more('*', [
            "user_id"=>$userid
        ])[0]['user_group'];
        // D all S 2 T 3
        $group = substr($group, 0, 1);
        $rule = 1;
        switch($group){
            case 'S': $rule = 2;break;
            case 'T': $rule = 3;break;
        }
        return $rule;
    }

    function _getMessageIdByTime($time){
        global $conn_message;
        $res = $conn_message->select_more("*", [
            "addTime"=>$time
        ])[0]['id'];
        return $res;
    }

    function _parseObject($need_field, $obj, $export_obj=false){
        $field = [];
        foreach($need_field as $key){
            if (array_key_exists($key, $obj)){
                if (export_obj){
                    $field[$key] = $obj[$key];
                } else {
                    array_push($field, $obj[$key]);
                }
            }
        }
        return $field;
    }

    // 只获取需要的字段
    function _parseEntity($entity, $export_obj=false){
        $need_field = [
            'semester',
            'title',
            'type',
            'target',
            'aims',
            'content',
            'department',
            'campus',
            'school',
            'userid'
        ];
        return _parseObject($need_field, $entity, $export_obj);
    }
?>