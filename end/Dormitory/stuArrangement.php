<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("dormitory_stu_arrangement");
  $conn2 = new Link("dormitory_build");
  $conn3 = new Link("dormitory_dormroom");

  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){
    if($type == 'sel_limit_dormroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $dormroom = $row['dormroom'];
        $class = $row['class'];
        $build = $row['build'];
        $student = $row['student'];
        $semester = $row['semester'];
        $department = $row['department'];
        $user = $row['created_user'];
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['dormroom'] = $conn->query("SELECT dormroom_name FROM dormitory_dormroom WHERE dormroom_id = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['build'] = $conn->query("SELECT build_name FROM dormitory_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$student'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_dormroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'sel_dormroom_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
      
      foreach($res as $key => $row){
        $dormroom = $row['dormroom'];
        $class = $row['class'];
        $build = $row['build'];
        $student = $row['student'];
        $semester = $row['semester'];
        $department = $row['department'];
        $user = $row['created_user'];
        $campus = $row['campus'];
        $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['dormroom_name'] = $conn->query("SELECT dormroom_name FROM dormitory_dormroom WHERE dormroom_id = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['build_name'] = $conn->query("SELECT build_name FROM dormitory_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$student' AND job = 'S1' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester_name'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department_name'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }

    }
    else if($type == 'add_dormroom'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_dormroom'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_dormroom'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'imp_dormroom'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){
          
          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";

          $campus = $row['campus'];
          $arr[$i]['build'] = $row['build'] = $conn2->select_more("build_id",[
            "campus"=>$campus,
            'build_name'=>$row['build']
          ])[0]['build_id'];

          $build = $row['build'];
          $arr[$i]['dormroom'] = $row['dormroom'] = $conn3->select_more("dormroom_id",[
            'campus'=>$campus,
            "dormroom_name"=>$row['dormroom'],
            'build'=>$build,
          ])[0]['dormroom_id'];

          $arr[$i]['semester'] = $row['semester'] = $conn->query("SELECT * FROM base_semester 
          WHERE semester = '".$row["semester"]."' 
          AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semesterId'];

          $arr[$i]['department'] = $row['department'] = $conn->query("SELECT * FROM base_department WHERE campus = '$campus' AND department_name ='".$row['department']."'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $department = $row['department'];

          $arr[$i]['class'] = $row['class'] = $conn->query("SELECT * FROM base_class 
          WHERE class_department = '$department' 
          AND class_name = '".$row['class']."' 
          AND class_campus = '$campus' 
          AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_id'];
          $class = $row['class'];

          $arr[$i]['student'] = $row['student'] = $conn->query("SELECT * FROM student_info 
          WHERE campus = '$campus' 
          AND class = '$class' 
          AND username = '".$row['student']."'  
          AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['userid']; 

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");
  
          $sql = "INSERT INTO dormitory_stu_arrangement($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();

          array_push($newarr,$res);
          // array_push($newarr,$row);
        }
        $res = $newarr;
      }
    }
    echo json_encode($res);
  }





?>