<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_evaluation');
  $conn2 = new Link('teach_evaluation_report');

  if(isset($_GET['type']) || isset($_POST['type'])){
    
    if($_GET['type'] == 'sel_limit_evaluation'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      // $res = $conn->select_more($col,$selobj);

      foreach($res as $key => $row){
        $organizer = $row['organizer'];
        $class = $row['class'];
        $session = $row['session'];
        $teacher = $row['teacher'];
        $semester = $row['semester'];
        $department = $row['department'];
        $course = $row['course'];
        $user = $row['created_user'];
        $informant = $row['informant'];
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['session'] = $conn->query("SELECT schedule_name FROM base_schedule WHERE schedule_id = '$session'")->fetchAll(PDO::FETCH_ASSOC)[0]['schedule_name'];
        $res[$key]['teacher'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['organizer'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$organizer'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['course'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$course'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        $res[$key]['informant'] = $informant != '' && $informant != null ? $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'] : '';
      }

      // $res = $start;

    }
    else if($_GET['type'] == 'sel_evaluation'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_POST['type'] == 'upa_evaluation'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
      // $res=$obj;
    }
    else if($_GET['type'] == 'sel_evaluation_more'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
      $id = $res[0]['id'];
      $res1 = $conn2->select($col,'evaluation',$id)[0];

      foreach($res as $key => $row){
        $organizer = $row['organizer'];
        $class = $row['class'];
        $session = $row['session'];
        $teacher = $row['teacher'];
        $semester = $row['semester'];
        $department = $row['department'];
        $course = $row['course'];
        $user = $row['created_user'];
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['session'] = $conn->query("SELECT schedule_name FROM base_schedule WHERE schedule_id = '$session'")->fetchAll(PDO::FETCH_ASSOC)[0]['schedule_name'];
        $res[$key]['teacher'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['organizer'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$organizer'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['course'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$course'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
      $teacher = json_decode($res1['teacher']);
      $arr = [];
      foreach($teacher as $row){
        $teaName = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$row'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        array_push($arr,$teaName);
      }
      $res1['teacher'] = join($arr,'-');

      $res = [$res[0],$res1];
      // $res = $id;
    }
    else if($_POST['type'] == 'add_evaluation'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
      // $res = $arr;
    }
    else if($_POST['type'] == 'del_course'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }

    echo json_encode($res);
  }




?>