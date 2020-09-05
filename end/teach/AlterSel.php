<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("teach_alter_class");

  $type = $_GET['type'] ? $_GET['type'] : null;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }

  if(!empty($type)){
    if($type == 'sel_limit_alter'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;

      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;

      if($seltype == 'day'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->limit_more($col,$start,$num,$selobj);
      }else if($seltype == 'month'){
        $datestart = $_GET['datestart'] ? $_GET['datestart'] : null;
        $dateend = $_GET['dateend'] ? $_GET['dateend'] : null;
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE class = '$class' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE department = '$department' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $campus = $_GET['campus'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE campus = '$campus' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }
      }else if($seltype == 'semester'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->limit_more($col,$start,$num,$selobj);
      }

      foreach($res as $key => $row){
        $teacher = $row['teacher'];
        $class = $row['class'];
        $build = $row['build'];
        $course = $row['course'];
        $session = $row['session'];
        $semester = $row['semester'];
        $department = $row['department'];
        $user = $row['created_user'];
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['build'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['teacher'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['course'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$course'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['session'] = $conn->query("SELECT schedule_name FROM base_schedule WHERE schedule_id = '$session'")->fetchAll(PDO::FETCH_ASSOC)[0]['schedule_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }

    }
    else if($type == 'sel_alter'){
      $col = $_GET['col'] ? $_GET['col'] : "*";

      $col = $_GET['col'] ? $_GET['col'] : "*";

      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;

      if($seltype == 'day'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->select_more($col,$selobj);
      }else if($seltype == 'month'){
        $datestart = $_GET['datestart'] ? $_GET['datestart'] : null;
        $dateend = $_GET['dateend'] ? $_GET['dateend'] : null;
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE class = '$class' AND date > $datestart AND date < $dateend;")->fetchAll(PDO::FETCH_ASSOC);
        }else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE department = '$department' AND date > $datestart AND date < $dateend;")->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $campus = $_GET['campus'];
          $res = $conn->query("SELECT $col FROM teach_alter_class WHERE campus = '$campus' AND date > $datestart AND date < $dateend;")->fetchAll(PDO::FETCH_ASSOC);
        }
      }else if($seltype == 'semester'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->select_more($col,$selobj);
      }
      // $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_dormroom'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($_POST['type'] == 'del_alter'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    echo json_encode($res);
  }





?>