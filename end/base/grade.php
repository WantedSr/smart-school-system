<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitGrade($col="*",$start,$num,$selobj=null){
    $conn = new Link('base_grade');
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function getGrade($col="*",$selobj=null){
    $conn = new Link('base_grade');
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addGrade($arr=null){
    $conn = new Link('base_grade');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaGrade($obj=null,$sel=null,$val=null){
    $conn = new Link('base_grade');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delGrade($arr=null){
    $conn = new Link('base_grade');
    if(count($arr)){
      $obj = [
        "state"=>'0',
      ];
      $res = $conn->update_more($obj,'id',$arr);
      return $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_grade'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitGrade($col,$start,$num,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_grade'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getGrade($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_grade'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addGrade($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_grade'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaGrade($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_grade'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delGrade($arr);
      echo $res;
    }
  }




?>