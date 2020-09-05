<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitSchool($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_school');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getSchool($col="*",$sel=null,$val=null){
    $conn = new Link('base_school');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addSchool($arr=null){
    $conn = new Link('base_school');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaSchool($obj=null,$sel=null,$val=null){
    $conn = new Link('base_school');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delSchool($arr=null){
    $conn = new Link('base_school');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_school'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitSchool($col,$start,$num,$sel,$val);
      $china = new Link('tb_china_id');
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_school'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getSchool($col,$sel,$val);
      $china = new Link('tb_china_id');
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_school'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addSchool($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_school'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaSchool($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_school'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delSchool($arr);
      echo $res;
    }
  }




?>