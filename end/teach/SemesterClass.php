<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_semester_class');

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
  function objtosql($obj){
    $newarr = [];
    foreach($obj as $key => $val){
      $child = $key.' = :'.$key;
      // $child = $key." = '".$val."'";
      array_push($newarr,$child);
    }
    return join($newarr," AND ");
  }


  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_class'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;      
      $res = $conn->query("SELECT $col FROM teach_semester_class WHERE semester = '$semester' AND department = '$department' AND class in (SELECT class_id FROM base_class WHERE class_department = '$department' AND status = '1');")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    else if($_GET['type'] == 'sel_class_name'){

      $col = $_GET['col'] ? $_GET['col'] : "*";

      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      if(empty($selobj)){
        $res = $conn->prepare("SELECT $col FROM teach_semester_class WHERE class in (SELECT class_id FROM base_class WHERE status = '1');");
      }else{
        $str = objtosql($selobj);
  
        $res = $conn->prepare("SELECT $col FROM teach_semester_class WHERE $str AND class in (SELECT class_id FROM base_class WHERE status = '1');");
    
        foreach($selobj as $key => $val){
          $res->bindParam(":".$key,$selobj[$key]);
        }
  
        $res->execute();
  
        $res = $res->fetchAll(PDO::FETCH_ASSOC);
  
        foreach($res as $key => $row){
          $classId = $row['class'];
          $campus = $row['campus'];
          $department = $row['department'];
          $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$classId' AND class_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
          $res[$key]['department_name'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department' AND campus ='$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
          $res[$key]['class_num'] = $conn->query("SELECT COUNT(*) FROM student_info WHERE class = '$classId' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }

        // $res = $str;
      }

    }
    else if($_POST['type'] == 'set_class'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = [];
      for($i=0;$i<count($arr);$i++){
        $obj = $arr[$i];
        $class = $obj['class'];
        $semester = $obj['semester'];
        if(count($conn->select_more("class",[
          'class'=>$class,
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