<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_class_course');

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
      $res = $conn->query("SELECT $col FROM teach_class_course WHERE class = '$class' AND semester = '$semester' AND course in (SELECT course FROM `teach_semester_course` WHERE status = '1' AND skill = '$skill' AND semester = '$semester' AND course in (SELECT course_id FROM base_course WHERE state = '1' AND course_profession = '$skill'))")->fetchAll(PDO::FETCH_ASSOC);
    }
    else if($_GET['type'] == 'sel_course_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $class = $_GET['class'] ? $_GET['class'] : null;
      $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $res = $conn->query("SELECT $col FROM teach_class_course WHERE class = '$class' AND semester = '$semester' AND status = '1' AND course in (SELECT course FROM `teach_semester_course` WHERE status = '1' AND skill = '$skill' AND semester = '$semester' AND course in (SELECT course_id FROM base_course WHERE state = '1' AND course_profession = '$skill'))")->fetchAll(PDO::FETCH_ASSOC);
      foreach($res as $key => $row){
        $courseId = $row['course'];
        $res[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$courseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      }
    }
    else if($_POST['type'] == 'set_course'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = [];
      for($i=0;$i<count($arr);$i++){
        $obj = $arr[$i];
        $courseId = $obj['course'];
        $selobj = [
          'course'=>$obj['course'],
          'semester'=>$obj['semester'],
          'campus'=>$obj['campus'],
          'class'=>$obj['class'],
        ];
        if(count($conn->select_more("id",$selobj)) > 0){
          $row = $conn->update($obj,'course',$courseId);
        }else{
          $arr2 = objarray_to_array($obj);
          array_push($arr2,(time()."000"));
          $row = $conn->insert($arr2);
          // array_push($res,$arr2);
        }
        array_push($res,$row);
      };
      
    }
    else if($_GET['type'] == 'sel_more_course'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $department = $_GET['department'] ? $_GET['department'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;

      if($department != "all"){
        $res = $conn->query("SELECT $col FROM  teach_class_course 
          WHERE semester = '$semester' 
          AND campus = '$campus' 
          AND status = '1' 
          AND class in (
            SELECT class_id FROM base_class 
              WHERE class_department = '$department' 
              AND class_campus = '$campus' 
              AND status = '1' 
          )")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $res = $conn->query("SELECT $col FROM teach_class_course 
          WHERE semester = '$semester' 
          AND campus = '$campus' 
          AND status = '1' 
          AND class in (
            SELECT class_id FROM base_class 
              WHERE class_campus = '$campus' 
              AND status = '1' 
          );")->fetchAll(PDO::FETCH_ASSOC);
      }

      foreach($res as $key => $row){
        $courseId = $row['course'];
        $res[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$courseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      }

    }

    echo json_encode($res);
  }




?>