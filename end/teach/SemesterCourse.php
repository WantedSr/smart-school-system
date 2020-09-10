<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_semester_course');

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
      $skill = $_GET['skill'] ? $_GET['skill'] : null;      
      $res = $conn->query("SELECT $col FROM teach_semester_course WHERE semester = '$semester' AND skill = '$skill' AND course in (SELECT course_id FROM base_course WHERE course_profession = '$skill' AND state = '1');")->fetchAll(PDO::FETCH_ASSOC);
    }
    else if($_GET['type'] == 'sel_more_course'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
      foreach($res as $key => $row){
        $courseId = $row['course'];
        $res[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$courseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      }
    }
    else if($_GET['type'] == 'sel_course_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $skill = $_GET['skill'] ? $_GET['skill'] : null;      
      $res = $conn->query("SELECT $col FROM teach_semester_course WHERE semester = '$semester' AND status = '1' AND skill = '$skill' AND course in (SELECT course_id FROM base_course WHERE course_profession = '$skill' AND state = '1');")->fetchAll(PDO::FETCH_ASSOC);
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
        $semester = $obj['semester'];
        if(count($conn->select_more("course",[
          "course"=>$courseId,
          'semester'=>$semester
        ])) > 0){
          $row = $conn->update($obj,'id',$obj['id']);
        }else{
          $arr2 = objarray_to_array($obj);
          array_push($arr2,(time()."000"));
          $row = $conn->insert($arr2);
          // array_push($res,$arr2);
        }
        array_push($res,$row);
      };
      
    }

    echo json_encode($res);
  }




?>