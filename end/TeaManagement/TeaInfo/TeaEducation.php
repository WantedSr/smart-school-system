<?php

  
  header('Access-Control-Allow-Origin: *');
  require_once("../../conn.php");
  // require_once("../../chinaPosition.php");

  function selLimitTea($col,$start,$num,$selobj){
    $conn = new Link("teacher_education");

    $res = $conn->limit_more($col,$start,$num,$selobj);

    foreach($res as $key => $row){
      $teacher = $row['teacher_name'];
      // $campus = $row['campus'];
      $u = $row['created_user'];
      $res[$key]['username'] = $conn->query("SELECT * FROM teacher_info WHERE userid = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
      // $res[$key]['campus'] = $conn->query("SELECT * FROM base_campus WHERE campus_id = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
      $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
    }

    return $res;
  }
  function selTea($col,$selobj) {
    $conn = new Link("teacher_education");
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addTea($arr){
    $conn = new Link('teacher_education');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaTea($obj,$sel,$val){
    $conn = new Link("teacher_education");
    $res = $conn->update($obj,$sel,$val);
  }
  function delTea(){

  }

  $type = $_GET['type'] ? $_GET['type'] : null;
  if(!empty($type)){
    if($type == 'sel_limit_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      
      $res = selLimitTea($col,$start,$num,$selobj);
    }else if($type == 'sel_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = selTea($col,$selobj);
    }else if($type == 'upa_tea'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaTea($obj,$sel,$val);
    }else if($type == 'add_tea'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addTea($arr);
    }
    echo json_encode($res);
  }





?>