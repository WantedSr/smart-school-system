<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_head_teacher');

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
    
    if($_GET['type'] == 'sel_headteacher'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_GET['type'] == 'sel_headteacher_name'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);

      foreach($res as $key => $row){
        $teacher = $row['teacher'];
        $class = $row['class'];
        $campus = $row['campus'];
        $res[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$teacher' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
      }
    }
    else if($_POST['type'] == 'add_headteacher'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $teacher = $_POST['teacher'] ? $_POST['teacher'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;

      $obj = [
        "class"=>$class,
        "semester"=>$semester,
        "department"=>$department,
        "campus"=>$campus
      ];

      $res1 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
      if($res1 > 0){
        $res = $conn->prepare("UPDATE teach_head_teacher SET teacher = :teacher WHERE class=:class AND semester=:semester AND campus=:campus");
        $res->bindParam(":teacher",$teacher);
        $res->bindParam(":class",$class);
        $res->bindParam(":semester",$semester);
        $res->bindParam(":campus",$campus);
        $res = $res->execute();
        // $res = $res1;
      }else{
        $res = $conn->insert($arr);
      }
      // $res = '123';
    }
    else if($_POST['type'] == 'del_course'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }

    echo json_encode($res);
  }




?>