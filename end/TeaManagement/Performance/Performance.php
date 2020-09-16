<?php 

    header('Access-Control-Allow-Origin: *');

    try{
      require_once("../../conn.php");
      $conn = new Link('teacher_performance');
      $conn_user = new Link('tb_login');
      $conn_imp = new Link('teacher_performance_imp');
      $conn_tea = new Link("teacher_info");
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
        case 'getTea': getTeaData(); break;
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


  
  function mFristAndLast($y = "", $m = ""){
    if ($y == "") $y = date("Y");
    if ($m == "") $m = date("m");
    $m = sprintf("%02d", intval($m));
    $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

    $m>12 || $m<1 ? $m=1 : $m=$m;
    $firstday = strtotime($y . $m . "01000000");
    $firstdaystr = date("Y-m-01", $firstday);
    $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));

    return array(
        "firstday" => $firstday,
        "lastday" => $lastday
    );
  }



  function getTeaData(){
    global $conn;
    global $conn_tea;
    global $data;

    $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
    $col = $_POST['col'] ? $_POST['col'] : "*";
    $campus = $_POST['campus'] ? $_POST['campus'] : null;
    
    $timestamp =  substr($request['month'],0,strlen($request['month'])-3);

    $year = date("Y",$timestamp);
    $month = date("m",$timestamp);

    $time = mFristAndLast($year,$month);
    $start = $time['firstday'].'000';
    $end = $time['lastday'].'000';

    $res = $conn->prepare("SELECT $col FROM teacher_performance 
    WHERE semester = :semester 
      AND campus = :campus 
      AND department = :department 
      AND teacher = :teacher 
      AND addTime >= :start 
      AND addTime <= :end");
    $res->bindParam(":semester",$request['semester']);
    $res->bindParam(":department",$request['department']);
    $res->bindParam(":campus",$campus);
    $res->bindParam(":teacher",$request['teacher']);
    $res->bindParam(":start",$start);
    $res->bindParam(":end",$end);
    $res->execute();
    $data = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $key => $val){
      $campus = $val['campus'];
      $semester = $val['semester'];
      $data[$key]['semester'] = $conn->query("SELECT * FROM base_semester 
          WHERE semesterId = '$semester' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
      $user = $conn_tea->select_more("userid,username",[
        'userid'=>$val['teacher'],
        'department'=>$val['department'],
        'campus'=>$campus,
      ])[0];
      $data[$key]['teacher_name'] = $user['username'];
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
      global $conn_tea;
      global $data;

      $arr = $_POST['arr'] ? json_decode($_POST['arr'],true) : null;
      $form = $_POST['form'] ? json_decode($_POST['form'],true) : null;
      $impId = "";
      $newarr = []; 

      if($form != null){
        $res = $conn_imp->insert($form);
        if($res){
          $impId = $conn_imp->query("SELECT max(id) FROM teacher_performance_imp")->fetchAll(PDO::FETCH_ASSOC)[0]['max(id)'];
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
          $semester = $row['semester'];

          $semester = $conn->query("SELECT * FROM base_semester 
          WHERE semester = '$semester' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC);
          
          if(count($semester) > 0){
            $arr[$i]['semester'] = $semester = $semester[0]['semesterId'];
          }else{
            $arr[$i]['semester'] = $semester = "";
          }

          $user = $conn_tea->select_more("userid,username,department",[
            'username'=>$username,
            'userid'=>$userid,
            'campus'=>$campus,
          ]);

          if(count($user) > 0){
            $arr[$i]['teacher'] = $userid = $user[0]['userid'];
            $arr[$i]['department'] = $user[0]['department'];
          }else{
            $arr[$i]['teacher'] = $userid = "";
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
  
  
          $sql = "INSERT INTO teacher_performance($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute(); 
          // $res = true;
          array_push($newarr,$res);
          // array_push($newarr,$arr[$i]); 
        }
        $data = $newarr;
      }else{
        $data = false;
      }
    }


?>