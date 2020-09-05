<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_evaluation_report');
  $conn2 = new Link("teacher_info");
  $conn3 = new Link("teach_evaluation");

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
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['session'] = $conn->query("SELECT schedule_name FROM base_schedule WHERE schedule_id = '$session'")->fetchAll(PDO::FETCH_ASSOC)[0]['schedule_name'];
        $res[$key]['teacher'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['organizer'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$organizer'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['course'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$course'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }

      // $res = $start;

    }
    else if($_GET['type'] == 'sel_evaluation'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_GET['type'] == 'sel_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn2->select_more($col,$selobj);
      $arr = [];
      foreach($res as $key => $row){
        $department = $row['department'];
        $department = $department != '0' && $department != '' ? $conn->query("SELECT * FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'] : "无部门";
        $obj = [
          'label'=>$department.'-'.$row['username'],
          "key"=>$row['userid'],
        ];
        array_push($arr,$obj);
      }
      $res = $arr;
    }
    else if($_GET['type'] == 'sel_evaluation_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj)[0];
      // $res = $res != '' || count($res) > 0 ? $res : [];
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
    else if($_POST['type'] == 'set_evaluation'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $teacher = $_POST['id'] ? $_POST['teacher'] : null;
      $wanna = $_POST['wanna'] ? $_POST['wanna'] : null;
      $light = $_POST['light'] ? $_POST['light'] : null;
      $img = $_POST['img'] ? $_POST['img'] : null;
      $school = $_POST['school'] ? $_POST['school'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $informant = $_POST['informant'] ? $_POST['informant'] : null;
      
      $teacher = json_encode($teacher);
      $arr = [$id,$teacher,$wanna,$light,$img,$school,$campus];
      $type = "";
      $obj = [
        "evaluation"=>$id,
        'teacher'=>$teacher,
        'content'=>$wanna,
        "highlight"=>$light,
        'img'=>$img,
        'school'=>$school,
        'campus'=>$campus
      ];

      if(count($conn->select("evaluation",'evaluation',$id)) > 0){
        $type = false;
        $res = $conn->update($obj,'evaluation',$id);
        // $res = 1;
      }else{
        $type = true;
        $res = $conn->insert($arr);
        // $res = $arr;
      }

      $conn3->update(['informant'=>$informant],'id',$id);
      $res = [$res,$type];


    }

    echo json_encode($res);
  }




?>