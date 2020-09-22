<?php 

    header('Access-Control-Allow-Origin: *');

    try{
      require_once("./conn.php");
      $conn_message = new Link("commonly_message");
      $conn_message_annex = new Link("commonly_message_annex");
      $conn_homework = new Link("commonly_homework");
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
        case 'getMessage': getMessage(); break;
        case 'getMessageList': getMessageList(); break;
        case 'readMessage': readMessage(); break;
      }
    } catch (PDOException $e) {
      echo json_encode([
        'msg'=> 'error',
        'error'=> $e->getMessage()
      ]);
    }

    /**
     * 获取所有消息列表
     * $request json 请求内容 required
     * $type string 用户类别 required
     * $msgType string 通知类别 required
     * $msgId string 通知id required
     */
    function getMessage(){
      global $conn_message;
      global $conn_homework;
      global $conn_tb_message_read;
      global $conn_message_annex;
      global $data;

      $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
      $type = $_POST['type'] ? $_POST['type'] : null;
      $msgType = $_POST['msgType'] ? $_POST['msgType'] : null;
      $msgId = $_POST['msgId'] ? $_POST['msgId'] : null;

      if($type == 'teacher'){
        $sql = "SELECT * FROM commonly_message 
        WHERE semester = :semester 
          AND id = :msgId 
          AND campus = :campus 
          AND (type = 'teacher' OR type = 'all') 
          AND (
            (target = 'department' AND aims = :department) OR
            (target = 'campus' AND aims = :campus)
          )";
        $res = $conn_message->prepare($sql);
        $res->bindParam(":semester",$request['semester']);
        $res->bindParam(":campus",$request['campus']);
        $res->bindParam(":department",$request['department']);
        $res->bindParam(":msgId",$msgId);
        $res->execute();
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
      }
      else if($type == 'student'){
        if($msgType == 'message'){
          $sql = "SELECT * FROM commonly_message 
          WHERE semester = :semester 
            AND id = :msgId 
            AND campus = :campus 
            AND (type = 'student' OR type = 'all') 
            AND (
              (target = 'class' AND aims = :class) OR 
              (target = 'grade' AND aims = :grade) OR 
              (target = 'department' AND aims = :department) OR
              (target = 'campus' AND aims = :campus)
            )";
          $res = $conn_message->prepare($sql);
          $res->bindParam(":semester",$request['semester']);
          $res->bindParam(":msgId",$msgId);
          $res->bindParam(":campus",$request['campus']);
          $res->bindParam(":department",$request['department']);
          $res->bindParam(":grade",$request['grade']);
          $res->bindParam(":class",$request['class']);
          $res->execute();
          $data = $res->fetchAll(PDO::FETCH_ASSOC);
          // $data = $msgId;
        }
        else if($msgType == 'homework'){
          $data = $conn_homework->select_more("*",[
            'id'=>$msgId,
            "class"=>$request['class'],
            'campus'=>$request['campus'],
            'semester'=>$request['semester'],
          ]);
          // $data = $msgId;
        }
      }
      foreach($data as $key => $val){
        $annexId = $val['annex'];
        $campus = $val['campus'];
        $data[$key]['annex'] = $conn_message_annex->select_more("filename,campus",[
          'id'=>$annexId,
          'campus'=>$campus,
        ])[0];
        $data[$key]['read_num'] = $conn_tb_message_read->select_more("COUNT(*)",[
          'message'=>$msgId,
          'campus'=>$request['campus'],
        ])[0]['COUNT(*)'];
        if($msgType == 'homework'){
          $course = $val['course'];
          $data[$key]['course'] = $conn_homework->query("SELECT course_id,course_name FROM base_course WHERE course_id = '$course' AND course_campus = '$campus' AND state = 1")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        }
      }
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
     * 返回当前用于所允许获得的通知列表
     * 
     * limit number 获取的通知数量 un-required
     * request json 获取通知的用户信息({campus:123,department:123,...}) required
     * type string 获取通知的用户类型 （student/teacher） required
     */
    function getMessageList(){
      global $conn_tb_message;
      global $conn_message;
      global $conn_homework;
      global $conn_tb_message_read;
      global $data;

      $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
      $type = $_POST['type'] ? $_POST['type'] : null;
      $limit = $_POST['limit'] ? $_POST['limit'] : 0;
      $sql = "";
      if($type == 'teacher'){
        $sql = "SELECT * FROM tb_message 
        WHERE campus = :campus 
          AND type = 'message' 
          AND message_id in (
            SELECT id FROM commonly_message 
            WHERE campus = :campus 
              AND (type = 'teacher' OR type ='all')
              AND semester = :semester 
              AND (
                (target = 'department' AND aims = :department) OR
                (target = 'campus' AND aims = :campus)
              )
            )
        ORDER BY addTime DESC";
        if(!empty($limit)){
          $sql .= " limit 0,$limit";
        }
        $res = $conn_tb_message->prepare($sql);
        $res->bindParam(":campus",$request['campus']);
        $res->bindParam(":semester",$request['semester']);
        $res->bindParam(":department",$request['department']);
        $res->execute();
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
      }
      else if($type == 'student'){
        $sql = "SELECT * FROM tb_message 
        WHERE campus = :campus 
          AND (
            (
              type = 'message' 
              AND message_id in (
                SELECT id FROM commonly_message 
                WHERE campus = :campus 
                  AND (type = 'student' OR type = 'all') 
                  AND semester = :semester 
                  AND (
                    (target = 'class' AND aims = :class) OR 
                    (target = 'grade' AND aims = :grade) OR 
                    (target = 'department' AND aims = :department) OR
                    (target = 'campus' AND aims = :campus)
                  )
              )
            )
            OR
            (
              type = 'homework'
              AND message_id in (
                SELECT id FROM commonly_homework 
                WHERE campus = :campus 
                  AND semester = :semester 
                  AND class = :class
              )
            )
          )
        ORDER BY addTime DESC";
        if(!empty($limit)){
          $sql .= " limit 0,$limit";
        }
        $res = $conn_tb_message->prepare($sql);
        $res->bindParam(":campus",$request['campus']);
        $res->bindParam(":semester",$request['semester']);
        $res->bindParam(":department",$request['department']);
        $res->bindParam(":grade",$request['grade']);
        $res->bindParam(":class",$request['class']);
        $res->execute();
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
      }

      foreach($data as $key => $val){
        $msgId = $val['message_id'];
        $type = $val['type'];
        $campus = $val['campus'];
        if($type == 'message'){
          $msg = $conn_message->select_more("*",[
            "id"=>$msgId,
          ])[0];
          $data[$key]['title'] = $msg['title'];
        }else if($type == 'homework'){
          $hw = $conn_homework->select_more("*",[
            'id'=>$msgId,
          ])[0];
          $data[$key]['title'] = $hw['title'];
        }
        $data[$key]['read'] = $conn_tb_message_read->select_more("COUNT(*)",[
          'message'=>$msgId,
          'user'=>$request['userid'],
        ])[0]['COUNT(*)'];
      }

    }


    /**
     * 用于记录当前用户已读消息
     * arr json 添加已读记录的数组 required
     */
    function readMessage(){
      global $conn_tb_message_read;
      global $data;

      $arr = $_POST['arr'] ? (array)json_decode($_POST['arr']) : null;
      $msgId = $arr[0];
      $userId = $arr[1];
      $campus = $arr[2];
      $num = $conn_tb_message_read->select_more("COUNT(*)",[
        'message'=>$msgId,
        'user'=>$userId,
        'campus'=>$campus,
      ])[0]['COUNT(*)'];
      if($num > 0){
        $data = 'repeat';
      }else{
        $data = $conn_tb_message_read->insert($arr);
      }
    }


    echo json_encode([
      'msg'=> '成功',
      'error'=> 'null',
      'data'=> $data
    ]);

    

?>