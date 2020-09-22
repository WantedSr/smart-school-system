<?php 

    header('Access-Control-Allow-Origin: *');


    
    //定义错误编码与错误信息的对应关系
    $errorInfo=[
      '1001'=>'文件超过php.ini限',
      '1002'=>'文件超过html限制',
      '1003'=>'文件上传不完整',
      '1004'=>'没有选择文件',
      '1005'=>'文件上传成功',
      '1006'=>'服务器内部错误',
      '1007'=>'服务器内部错误',
      '1008'=>'文件太大',
      '1009'=>'文件类型不合法',
      '1010'=>'文件移动失败'
    ];

    $maxsize = 10 * 1024 * 1024;      // 文件上传最大内存
    $mime = ["application/zip","application/x-zip-compressed","application/octet-stream","application/x-rar","application/x-7z-compressed"];     // 允许文件mime类型
    $alone_ext = ['zip','rar','7z'];      // 允许的文件后缀名
    $path = "./annex";      // 文件存放路径

    try{
        require_once("../conn.php");
        $conn_message = new Link("commonly_message");
        $conn_message_annex = new Link("commonly_message_annex");
        $conn_tb_message = new Link("tb_message");
        $conn_tb_message_read = new Link("tb_message_read");
        $conn_user = new Link('tb_login');
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
            case 'getMessageList': getMessageList(); break;
            case 'upMessage': upMessage(); break;
            case 'readMessage': readMessage(); break;
            case 'delMessage': delMessage(); break;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'msg'=> 'error',
            'error'=> $e->getMessage()
        ]);
    }

    /**
     * 检验文件
     * $file array 文件数组
     * mime string 文件类型
     * maxsize number 文件最大内存
     * path 存储路径
     */
    function uploads($file){
      global $errorInfo;
      global $maxsize;
      global $mime;
      global $path;
      global $alone_ext;

      $state = '';
      $msg = '';
      $data = '';

      //判断文件上传错误
      switch($file['error']){
        case 1:
          $state = '1001';//'文件超过php.ini限制';
          break;
        case 2:
          $statet = '1002';//'文件超过html限制';
          break;
        case 3:
          $state = '1003';//'文件上传不完整';
          break;
        case 4:
          $state = '1004';//'没有选择文件';
          break;
        case 6:
          $state = '1006';//'服务器内部错误';
          break;
        case 7:
          $state = '1007';//'服务器内部错误';
          break;   
      }
      
      $tmp = $file['tmp_name'];     //获取文件的扩展名
      $ext = pathinfo($file['name'],PATHINFO_EXTENSION);    // 获取文件后缀名
     
      if($file['size'] > $maxsize){
        $state = '1008';                          //文件太大;
      }
     
      //判断用户上传的文件类型是否合法
      if(!in_array($file['type'],$mime)){
        $state = '1009';                        //文件类型不合法;
      }

      if(!in_array($ext,$alone_ext)){
        $state = '1009';
      }

      $fileName = create_guid("smartSchool");      // 获取随机文件名
      //拼接文件名
      $basename = $fileName.'.'.$ext;
      //拼接路径
      $dest = $path.'/'. $basename;

      //将临时文件夹中的文件，移动到目标位置
      if(move_uploaded_file($tmp,$dest)){
        $data = $basename;
        $state = '1005';
      }else{
        $state = '1010';
      }

      $res = [
        'state'=>$state,
        'msg'=>$errorInfo[$state],
        'data'=>$data,
      ];
      return $res;
    }
    
    /**
     * 获取唯一id
     * namespace string 唯一id开头标识
     */
    function create_guid($namespace = '') {  
      $date = date('YmdHis');
      static $guid = '';
      $uid = uniqid("", true);
      $data = $namespace;
      $data .= $_SERVER['REQUEST_TIME'];
      $data .= $_SERVER['HTTP_USER_AGENT'];
      $data .= $_SERVER['LOCAL_ADDR'];
      $data .= $_SERVER['LOCAL_PORT'];
      $data .= $_SERVER['REMOTE_ADDR'];
      $data .= $_SERVER['REMOTE_PORT'];
      $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
      $guid =  
          substr($hash, 0, 8) .
          '-' .
          substr($hash, 8, 4) .
          '-' .
          substr($hash, 12, 4) .
          '-' .
          substr($hash, 16, 4) .
          '-' .
          substr($hash, 20, 12);
      return $namespace . '-' . $guid . '-' . $date;
    }

    
     /**
     * 发布消息
     * formdata 
     * @param entity array （用户和信息数据）
     * -> 例：{"title": "dsf;ewqr", "type": "1", "target": "department", "content": "ewrjerl", "aims": "S0001_A_01", "mes_type": "1", "semester": "202101", "department": "S0001_A_01", "campus": "S0001_A", "school": "S0001", "userid": "2017217038"} 
     * -> 必须
     * @param annex file （附件）不必须
     * 
     * type: 1、所有人 2、学生 3、老师
     * tb tpye: 自定义也是数值
     */
    function setMessage(){
      global $conn_message;
      global $conn_tb_message;
      global $conn_message_annex;
      global $data;

      $form = $_POST['entity'] ? (array)json_decode($_POST['entity'],true) : null;
      $file = $_FILES['annex'];
      if(empty($file)){
        $res1 = true;
        $annexId = 0;
      }else{
        $upload = uploads($file);
        if($upload['data'] != ''){
          $filename = $upload['data'];
          $arr_annex = [
            $filename,
            $form['campus'],
            $form['school'],
            $form['created_user'],
            $form['addTime'],
          ];
          $res1 = $conn_message_annex->insert($arr_annex);
          if($res1){
            $annexId = $conn_message_annex->query("SELECT max(id) FROM commonly_message_annex")->fetchAll(PDO::FETCH_ASSOC)[0]['max(id)'];
          }else{
            $annexId = 0;
            $data = [
              "type"=>"fail",
              'info'=>"附件信息添加失败"
            ];
            return false;
          }
        }else{
          $data = [
            "type"=>"fail",
            'info'=>"文件上传失败"
          ];
          return false;
        }
      }
      if($res1){
        $arr_form = [
          $form['semester'],
          $form['title'],
          $form['type'],
          $form['target'],
          $form['aims'],
          $form['content'],
          $annexId,
          $form['department'],
          $form['campus'],
          $form['school'],
          $form['created_user'],
          $form['addTime'],
        ];
        $res2 = $conn_message->insert($arr_form);
        if($res2){
          $msgId = $conn_message->query("SELECT max(id) FROM commonly_message")->fetchAll(PDO::FETCH_ASSOC)[0]['max(id)'];
          $msg_arr = [
            $msgId,
            "message",
            $form['campus'],
            $form['school'],
            $form['created_user'],
            $form['addTime'],
          ];
          $res3 = $conn_tb_message->insert($msg_arr);
          if($res3){
            $data = [
              "type"=>"success",
              'info'=>"通知添加成功"
            ];
            return false;
          }else{
            $data = [
              "type"=>"fail",
              'info'=>"通知添加失败"
            ];
            return false;
          }
        }else{
          $data = [
            "type"=>"fail",
            'info'=>"通知添加失败"
          ];
          return false;
        }
      }
    }
    

    /**
     * 获取所有消息列表
     * $request json 请求内容 required
     * $col string 设置查询列 un-required
     */
    function getMessage(){
      global $conn_message;
      global $conn_tb_message;
      global $data;

      $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
      $col = $_POST['col'] ? $_POST['col'] : "*";

      $data = $conn_message->select_more($col,$request);

      foreach($data as $key => $val){
        $target = $val['target'];
        $aims = $val['aims'];
        $campus = $val['campus'];
        switch($target){
          case "class":
            $data[$key]['aims'] = $conn_message->query("SELECT class_id,class_name FROM base_class 
              WHERE class_id = '$aims' 
                AND class_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0];
            break;
          case 'grade':
            $data[$key]['aims'] = $conn_message->query("SELECT grade_id,grade_name FROM base_grade 
              WHERE grade_id = '$aims' 
                AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0];
            break;
          case 'department':
            $data[$key]['aims'] = $conn_message->query("SELECT department_id,department_name FROM base_department 
              WHERE department_id = '$aims' 
                AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0];
            break;
          case 'campus':
            $data[$key]['aims'] = "本校区";
            break;
        }
        $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['user_name'];
        $data[$key]['show'] = _getShow($val['id'],$val['campus'])['COUNT(*)'];
      }

    }


    /**
     * 返回是否载tb_message种被删除
     */
    function _getShow($id,$cam){
      global $conn_tb_message;
      return $conn_tb_message->select_more("COUNT(*)",[
        'message_id'=>$id,
        "type"=>"message",
        'campus'=>$cam
      ])[0];
    }



    /**
     * 返回查询用户
     */
    function _getUser($id,$cam){
      global $conn_user;
      return $conn_user->select_more("user_name",[
        "user_id"=>$id,
        "user_campus"=>$cam,
      ])[0];
    }

    /**
     * 删除消息
     * @param id int （tb message 表的 id）：6 必须
     * @param auth array (验证字段) : {"campus": "S0001_A", "school": "S0001"} 必须
     * 
     * 只是删除 tb message 里面的数据
     */
    function delMessage(){
        global $conn_tb_message;
        global $data;
        $message_id = $_POST['id'] ? $_POST['id'] : null;
        $campus = $_POST['campus'] ? $_POST['campus'] : null;
        if ($message_id == null){
            $data = false;
            return false;
        }
        $sql = "DELETE FROM tb_message 
          WHERE message_id = :id 
            AND type = 'message' 
            AND campus = :campus";
        $res = $conn_tb_message->prepare($sql);
        $res->bindParam(":id",$message_id);
        $res->bindParam(":campus",$campus);
        $res = $res->execute();
        $data = $res;
    }

    echo json_encode([
        'msg'=> '成功',
        'error'=> 'null',
        'data'=> $data
    ]);

?>