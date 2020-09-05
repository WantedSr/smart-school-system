<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_alter_class');

  //调用这个函数，将其幻化为数组，然后取出对应值
  function objarray_to_array($obj) {
    $ret = [];
    foreach ($obj as $key => $value) {
      if (gettype($value) == "array" || gettype($value) == "object"){
        $ret[$key] = objarray_to_array($value);
      }else{
        array_push($ret,$value);
      }
    }
    return $ret;
  }


  if(isset($_GET['type']) || isset($_POST['type'])){
    
    if($_GET['type'] == 'sel_course'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $class = $_GET['class'] ? $_GET['class'] : null;
      $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $res = $conn->query("SELECT $col FROM teach_teacher_schedule WHERE class = '$class' AND semester = '$semester' AND course in (SELECT course FROM teach_class_course WHERE class = '$class' AND semester = '$semester' AND status = '1' AND course in (SELECT course FROM `teach_semester_course` WHERE status = '1' AND semester = '$semester' AND skill = '$skill' AND course in (SELECT course_id FROM base_course WHERE state = '1' AND course_profession = '$skill')))")->fetchAll(PDO::FETCH_ASSOC);
    }
    else if($_GET['type'] == 'sel_course_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $class = $_GET['class'] ? $_GET['class'] : null;
      // $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $res = $conn->query("SELECT $col FROM teach_teacher_schedule WHERE class = '$class' AND semester = '$semester' AND course in (SELECT course FROM teach_class_course WHERE class = '$class' AND semester = '$semester' AND status = '1' AND course in (SELECT course FROM `teach_semester_course` WHERE status = '1' AND course in (SELECT course_id FROM base_course WHERE state = '1')))")->fetchAll(PDO::FETCH_ASSOC);
      foreach($res as $key => $row){
        $courseId = $row['course'];
        $teacherId = $row['teacher'];
        $campus = $row['campus'];
        $res[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$courseId' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacherId' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
      }
    }
    else if($_POST['type'] == 'add_alter_class'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
      // $res = '123';
    }
    else if($_POST['type'] == 'del_course'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }

    echo json_encode($res);
  }




?>