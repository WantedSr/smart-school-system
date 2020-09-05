<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('system_log');
  $type = $_GET['type'] ? $_GET['type'] : null;

  if(!empty($type)){
    $col = $_GET['col'] ? $_GET['col'] : "*";
    if($type == 'sel_limit_log'){
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 0;
      $res = $conn->limit_more($col,$start,$num,$selobj);
    }
    else if($type == 'sel_log'){
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_log'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
      // $res = $arr;
    }

    echo json_encode($res);
  }





?>