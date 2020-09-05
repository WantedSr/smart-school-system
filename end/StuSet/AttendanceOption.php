<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitOption($col="*",$start,$num,$selobj=null){
    $conn = new Link('stuset_attendance_option');
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function getOption($col="*",$selobj=null){
    $conn = new Link('stuset_attendance_option');
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addOption($arr=null){
    $conn = new Link('stuset_attendance_option');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaOption($obj=null,$sel=null,$val=null){
    $conn = new Link('stuset_attendance_option');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delOption($arr=null){
    $conn = new Link('stuset_attendance_option');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitOption($col,$start,$num,$selobj);
    }
    else if($_GET['type'] == 'sel_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getOption($col,$selobj);
    }
    else if($_POST['type'] == 'add_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = addOption($arr);
    }
    else if($_POST['type'] == 'upa_option'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = upaOption($obj,$sel,$val);
    }
    else if($_POST['type'] == 'del_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = delOption($arr);
    }
    else if($_GET['type'] == 'get_option_id'){
      $conn = new Link('stuset_attendance_option');
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      if($campus != null){
        $sql = "SELECT * FROM stuset_attendance_option WHERE campus = '$campus' ORDER BY id DESC LIMIT 0,1";
        $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        // $arr = explode('_',$res);
        $res = 'T'.($res+1);
      }else{
        $res = "T1";
      }
    }
    echo json_encode($res);
  }




?>