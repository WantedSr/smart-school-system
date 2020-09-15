<?php 

    header('Access-Control-Allow-Origin: *');

    try{
      require_once("../conn.php");
      $conn = new Link('stuset_student_score');
      $conn_user = new Link('tb_login');
      $conn_imp = new Link('stuset_imp_score');
      $conn_stu = new Link("student_info");
    } catch (PDOException $e){
      echo json_encode([
        'msg'=> '服务器错误',
        'error'=> '1000'
      ]);
    }

    $data = [];

    try{
      $action = $_POST['action'] ? $_POST['action'] : null;
      switch ($action){
        case 'add': addData(); break;
        case 'getImp': getImpData(); break;
        case 'get': getData(); break;
        case 'getStu': getStuData(); break;
        case 'del': delData(); break;
        case 'imp': impData(); break;
      }
    } catch (PDOException $e) {
      echo json_encode([
        'msg'=> 'error',
        'error'=> $e->getMessage()
      ]);
    }
    
    echo json_encode([
        'msg'=> '成功',
        'error'=> 'null',
        'data'=> $data
    ]);

/**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     * request object （需要更新的数据）: {"p": "捡到一分钱"} 必须
     */
    function upData(){
      global $conn;
      global $data;
      $id = $_POST['id'] ? $_POST['id'] : null;
      $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
      $data = $conn->update($request, 'id', $id);
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
   * student string （自定义筛选）: {"student": 2017217041} 自选
   * 默认返回所有数据
   */
  function getImpData(){
    global $conn;
    global $conn_imp;
    global $data;
    $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
    $col = $_POST['col'] ? $_POST['col'] : "*";
    
    try{
        $date = $request['addTime'];
        unset($request['addTime']);
    } catch (Exception $e){};
    
    if ($request){
      $data = $conn_imp->select_more($col, $request);
    } else {
      $data = $conn_imp->select_more($col);
    }
    
    foreach($data as $key => $val){
      $campus = $val['campus'];
      $dep = $val['department'];
      $id = $val['id'];
      $data[$key]['num'] = $conn->select_more("COUNT(*)",[
        "campus"=>$campus,
        "department"=>$dep,
        'imp_id'=>$id
      ])[0]['COUNT(*)'];
      $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['user_name'];
    }
  }
  


  function getData(){
    global $conn;
    global $conn_stu;
    global $data;

    $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
    $col = $_POST['col'] ? $_POST['col'] : "*";
    
    try{
        $date = $request['addTime'];
        unset($request['addTime']);
    } catch (Exception $e){};
    
    if ($request){
      $data = $conn->select_more($col, $request);
    } else {
      $data = $conn->select_more($col);
    }
    
    foreach($data as $key => $val){
      $campus = $val['campus'];
      $semester = $val['semester'];
      // $id = $val['id'];
      $data[$key]['semester'] = $conn->query("SELECT * FROM base_semester 
          WHERE semesterId = '$semester' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
      $user = $conn_stu->select_more("userid,username,class",[
        'userid'=>$val['student'],
        'class'=>$val['class'],
        'campus'=>$campus,
      ])[0];
      $data[$key]['student_name'] = $user['username'];
      $data[$key]['course_name'] = $conn->query("SELECT course_id,course_name FROM base_course 
      WHERE course_campus = '$campus' 
        AND course_id = '".$val['course']."'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['user_name'];
    }
  }



  function getStuData(){
    global $conn;
    global $conn_stu;
    global $data;

    $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
    $col = $_POST['col'] ? $_POST['col'] : "*";
    
    try{
        $date = $request['addTime'];
        unset($request['addTime']);
    } catch (Exception $e){};
    
    if ($request){
      $data = $conn->select_more($col, $request);
    } else {
      $data = $conn->select_more($col);
    }
    
    foreach($data as $key => $val){
      $campus = $val['campus'];
      $semester = $val['semester'];
      // $id = $val['id'];
      $data[$key]['semester'] = $conn->query("SELECT * FROM base_semester 
          WHERE semesterId = '$semester' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
      $user = $conn_stu->select_more("userid,username,class",[
        'userid'=>$val['student'],
        'class'=>$val['class'],
        'campus'=>$campus,
      ])[0];
      $data[$key]['student_name'] = $user['username'];
      $data[$key]['course_name'] = $conn->query("SELECT course_id,course_name FROM base_course 
      WHERE course_campus = '$campus' 
        AND course_id = '".$val['course']."'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['user_name'];
    }
  }



    
    /**
     * id int 导入文件的id  必须
     */
    function delData(){
        global $conn;
        global $conn_imp;
        global $data;
        $id = $_POST['id'] ? $_POST['id'] : null;
        $res1 = $conn_imp->delete('id', $id);
        if($res1){
          $res2 = $conn->delete("imp_id",$id);
        }else{
          $data = false;
        }
        $data = $res2;
    }

    /**
     * arr json 要存储的学生成绩信息 必须
     * form json 要存储的文件信息 必须
     */
    function impData(){
      global $conn;
      global $conn_imp;
      global $conn_stu;
      global $data;
      global $conn_reward;

      $arr = $_POST['arr'] ? json_decode($_POST['arr'],true) : null;
      $form = $_POST['form'] ? json_decode($_POST['form'],true) : null;
      $impId = "";
      $newarr = []; 

      if($form != null){
        $res = $conn_imp->insert($form);
        if($res){
          $impId = $conn_imp->query("SELECT max(id) FROM stuset_imp_score")->fetchAll(PDO::FETCH_ASSOC)[0]['max(id)'];
        }
        else{
          $data = "文件上传失败";
          return;
        }
      }

      if($arr != null){
        for($i=0;$i<count($arr);$i++){
  
          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";

          $campus = $row['campus'];

          $userid = $row['userid'];
          $username = $row['username'];
          $class = $row['class'];
          $semester = $row['semester'];

          $semester = $conn->query("SELECT * FROM base_semester 
          WHERE semester = '$semester' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC);
          
          if(count($semester) > 0){
            $arr[$i]['semester'] = $semester = $semester[0]['semesterId'];
          }else{
            $arr[$i]['semester'] = $semester = "";
          }

          $class = $conn->query("SELECT * FROM base_class 
          WHERE class_campus = '$campus' 
            AND class_name = '$class'")->fetchAll(PDO::FETCH_ASSOC);

          if(count($class) > 0){
            $arr[$i]['class'] = $class = $class[0]['class_id'];
          }else{
            $arr[$i]['class'] = $class = "";
          }

          $user = $conn_stu->select_more("userid,username,class",[
            'username'=>$username,
            'userid'=>$userid,
            'class'=>$class,
            'campus'=>$campus,
          ]);

          if(count($user) > 0){
            $arr[$i]['student'] = $userid = $user[0]['userid'];
          }else{
            $arr[$i]['student'] = $userid = "";
          }

          
          $course = $row['course'];
          $course = $conn->query("SELECT * FROM base_course 
          WHERE course_campus = '$campus' 
            AND course_name = '$course'")->fetchAll(PDO::FETCH_ASSOC);
          if(count($course) > 0){
            $arr[$i]['course'] = $course = $course[0]['course_id'];
          }
          else{
            $arr[$i]['course'] = $course = "";
          }
          
          $arr[$i]['imp_id'] = $impId;

          unset($arr[$i]['userid']);
          unset($arr[$i]['username']);
  
          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = '`'.join($colArr,"`,`").'`';
          $value = join($pareArr,",");
  
  
          $sql = "INSERT INTO stuset_student_score($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute(); 
          // $res = true;
          array_push($newarr,$res);
          // array_push($newarr,$sql); 
        }
        $data = $newarr;
      }else{
        $data = false;
      }
    }


?>