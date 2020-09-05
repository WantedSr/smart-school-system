<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link("commonly_week_head_teacher");


  // function std_class_object_to_array($stdclassobject){
  //   $array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
  //   foreach ($array as $key => $value) {
  //     $value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
  //     $array[$key] = $value;
  //   }
  //   return $array;
  // }


  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){
    if($type == 'sel_headTeacher'){
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $res = $conn->select_more($col,$selobj);
      foreach($res as $key => $row){
        $class = $row['class'];
        $campus = $row['campus'];
        $teacher = $row['teacher'];
        $res[$key]['class_name'] =  $conn->query("SELECT * FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['teacher_name'] = $conn->query("SELECT * FROM teacher_info WHERE userid = '$teacher' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
      }
    }
    else if($type == 'add_headTeacher'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $week = $_POST['week'] ? $_POST['week'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $obj = [
        "week"=>$week,
        "semester"=>$semester,
        "class"=>$class,
        'campus'=>$campus,
      ];
      $res1 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
      if($res1 > 0){
        $res = [true,false];
      }else{
        $res2 = $conn->insert($arr);
        $res = [false,$res2];
      }
    }
    else if($type == 'upa_headTeacher'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_headTeacher'){
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->delete($sel,$val);
    }
    else if($type == 'office_check'){
      $week = $_POST['week'] ? $_POST['week'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $sql = "UPDATE commonly_week_head_teacher 
        SET state='1',dep_state='1',office_state='1',leader_state='0' 
        WHERE 
          week = :week 
          AND department = :department 
          AND campus = :campus 
          AND semester = :semester 
          AND state = '1' 
          AND dep_state = '1'";
      $res = $conn->prepare($sql);
      $res->bindParam(":week",$week);
      $res->bindParam(":department",$department);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":semester",$semester);
      $res = $res->execute();
    }
    else if($type == 'office_back'){
      $week = $_POST['week'] ? $_POST['week'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $sql = "UPDATE commonly_week_head_teacher 
        SET state='1',dep_state='2',office_state='0',leader_state='0'  
        WHERE 
          week = :week 
          AND department = :department 
          AND campus = :campus 
          AND semester = :semester 
          AND state = '1' 
          AND dep_state = '1'";
      $res = $conn->prepare($sql);
      $res->bindParam(":week",$week);
      $res->bindParam(":department",$department);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":semester",$semester);
      $res = $res->execute();
    }
    else if($type == 'leader_check'){
      $week = $_POST['week'] ? $_POST['week'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $sql = "UPDATE commonly_week_head_teacher 
        SET state='1',dep_state='1',office_state='1',leader_state='1' 
        WHERE 
          week = :week 
          AND department = :department 
          AND campus = :campus 
          AND semester = :semester 
          AND state = '1' 
          AND dep_state = '1'
          AND office_state = '1'";
      $res = $conn->prepare($sql);
      $res->bindParam(":week",$week);
      $res->bindParam(":department",$department);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":semester",$semester);
      $res = $res->execute();
    }
    else if($type == 'leader_back'){
      $week = $_POST['week'] ? $_POST['week'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $sql = "UPDATE commonly_week_head_teacher 
        SET state='1',dep_state='1',office_state='2',leader_state='0' 
        WHERE 
          week = :week 
          AND department = :department 
          AND campus = :campus 
          AND semester = :semester 
          AND state = '1' 
          AND dep_state = '1'
          AND office_state = '1'";
      $res = $conn->prepare($sql);
      $res->bindParam(":week",$week);
      $res->bindParam(":department",$department);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":semester",$semester);
      $res = $res->execute();
    }
  }




  echo json_encode($res);
  





?>