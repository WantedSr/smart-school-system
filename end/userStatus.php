<?php
    
header('Access-Control-Allow-Origin: *');

  require_once("./conn.php");
  $conn = new Link("tb_login");

  $token = $_GET['token'];

  $msg = $conn->check_token($token);
  $msg = json_decode($msg,true);

  if(isset($_GET['token'])){
    if(isset($_GET['type'])){
      $tb = $_GET['type'] == "student" ? "student_info" : "teacher_info";
      $conn2 = new Link($tb);
      if($msg['code'] != 200){
        echo json_encode($msg);
      }else{
        $userId = $msg['info'][0];
        $res = $conn2->select("*","userid",$userId)[0];
        $school = $res['school'];
        $dp = $res['department'];
        $class = $res['class'];
        $userCla = $conn2->query("SELECT * FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $userDep = $conn2->query("SELECT * FROM base_department WHERE department_id = '$dp'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $userSch = $conn2->query("SELECT * FROM base_school WHERE school_id = '$school'")->fetchAll(PDO::FETCH_ASSOC)[0]['school_name'];
        $res['department'] = $userDep;
        $res['class'] = $userCla;
        $res['class_id'] = $class;
        $res['school'] = $userSch;
        $msg['info'] = $res;
        echo json_encode($msg);
      }
    }else{
      if($msg['code'] != 200){
        echo json_encode($msg);
      }else{
        $userId = $msg['info'][0];
        $res = $conn->select("*",'user_id',$userId)[0];
        $school = $res['user_school'];
        $school_name = $conn->query("SELECT school_name FROM base_school WHERE school_id = '$school';")->fetchAll(PDO::FETCH_ASSOC)[0]['school_name'];
        $campus = $res['user_campus'];
        $department = $conn->query("SELECT * FROM teacher_info WHERE userid = '$userId' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department'];
        if($department == null || $department == ""){
          $department = $conn->query("SELECT * FROM student_info WHERE userid = '$userId'")->fetchAll(PDO::FETCH_ASSOC)[0]['department'];
        }
        $semester = $conn->query("SELECT * FROM base_semester WHERE state = '1' AND campus = '$campus';")->fetchAll(PDO::FETCH_ASSOC)[0];
        $campus_name = $conn->query("SELECT campus_name FROM base_campus WHERE campus_school = '$school' AND campus_id = '$campus';")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
        $authority = $conn->query("SELECT * FROM system_authority WHERE authority_id = '".$res['user_group']."';")->fetchAll(PDO::FETCH_ASSOC)[0]['authority_range'];
        $res['semester'] = $semester['semesterId'];
        $res['semester_start'] = $semester['teach_start'];
        $res['user_department'] = $department;
        $res['authority'] = $authority;
        $res['school_name'] = $school_name;
        $res['campus_name'] = $campus_name;
        $msg['info'] = $res;
        echo json_encode($msg);
      }
    }
  }



?>