<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitSchedule($col="*",$start=0,$num=15,$selobj=null){
    $conn = new Link('base_schedule');
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function getSchedule($col="*",$selobj=null){
    $conn = new Link('base_schedule');
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addSchedule($arr=null){
    $conn = new Link('base_schedule');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaSchedule($obj=null,$sel=null,$val=null){
    $conn = new Link('base_schedule');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delSchedule($arr=null){
    $conn = new Link('base_schedule');
    $obj = [
      'status'=>'0',
    ];
    if(count($arr)){
      $res = $conn->update_more($obj,'id',$arr);
      return $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_schedule'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitSchedule($col,$start,$num,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_schedule'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getSchedule($col,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_schedule'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addSchedule($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_schedule'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaSchedule($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_schedule'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delSchedule($arr);
      echo $res;
    }
    else if($_GET['type'] == 'get_schedule_id'){
      $conn = new Link('base_schedule');
      $sql = "SELECT * FROM base_schedule ORDER BY id DESC LIMIT 0,1";
      $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
      echo $res;
    }
  }




?>