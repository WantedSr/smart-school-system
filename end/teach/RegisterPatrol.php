<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_patrol');

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
    
    
    if($_GET['type'] == 'sel_limit_patrol'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;

      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;

      if($seltype == 'day'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->limit_more($col,$start,$num,$selobj);
        // $res = $selobj;
      }else if($seltype == 'month'){

        $datestart = $_GET['datestart'] ? $_GET['datestart'] : null;
        $dateend = $_GET['dateend'] ? $_GET['dateend'] : null;

        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $res = $conn->query("SELECT $col FROM teach_patrol WHERE class = '$class' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $res = $conn->query("SELECT $col FROM teach_patrol WHERE department = '$department' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $campus = $_GET['campus'];
          $res = $conn->query("SELECT $col FROM teach_patrol WHERE campus = '$campus' AND date >= $datestart AND date <= $dateend LIMIT $start,$num;")->fetchAll(PDO::FETCH_ASSOC);
        }
      }else if($seltype == 'semester'){
        $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
        $res = $conn->limit_more($col,$start,$num,$selobj);
      }

      foreach($res as $key => $row){
        $class = $row['class'];
        $session = $row['session'];
        $semester = $row['semester'];
        $department = $row['department'];
        $user = $row['created_user'];
        $res[$key]['class'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['semester'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['session'] = $conn->query("SELECT schedule_name FROM base_schedule WHERE schedule_id = '$session'")->fetchAll(PDO::FETCH_ASSOC)[0]['schedule_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }

    }
    else if($_GET['type'] == 'sel_patrol'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_POST['type'] == 'add_patrol'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $session = $_POST['session'] ? $_POST['session'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $selobj = [
        'session'=>$session,
        'class'=>$class,
        'date'=>$date,
      ];
      if(count($conn->select_more("*",$selobj)) > 0){
        $res = [true,false];
      }else{
        $res1 = $conn->insert($arr);
        $res = [false,$res1];
      }
      // $res = count($conn->select_more("*",$selobj));
      // $res = '123';
    }
    else if($_POST['type'] == 'upa_patrol'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
      // $res = '123';
    }
    else if($_POST['type'] == 'del_patrol'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }
    echo json_encode($res);
  }




?>