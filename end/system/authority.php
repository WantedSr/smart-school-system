<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitAuthority($col,$start,$num,$selobj){
    $conn = new Link("system_authority");
    $selobj['status'] = '1';
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function getAuthority($col,$selobj){
    $conn = new Link("system_authority");
    $selobj['status'] = '1';
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addAuthority($arr){
    $conn = new Link("system_authority");
    $res = $conn->insert($arr);
    return $res;
  }
  function upaAuthority($obj,$sel,$val){
    $conn = new Link("system_authority");
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delAuthority($sel,$arr){
    $conn = new Link("system_authority");
    $res = $conn->delete_more($sel,$arr);
    return $res;
  }

  $type;
  if($_GET['type']){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }else{
    $type == null;
  }

  if(!empty($type)){
    if($type == 'sel_limit_authority'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getLimitAuthority($col,$start,$num,$selobj);
      $conn = new Link('tb_login');
      foreach($res as $key => $row){
        $userid = $row['created_user'];
        $username = $conn->select("*","user_id",$userid)[0]['user_name'];
        $res[$key]['created_user'] = $username; 
      }
    }
    else if($type == 'sel_authority'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getAuthority($col,$selobj);
    }
    else if($type == 'add_authority'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = addAuthority($arr);
      // $res = 1234;
    }
    else if($type == 'upa_authority'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaAuthority($obj,$sel,$val);
      // $res = 123;
    }
    echo json_encode($res);
  }





?>