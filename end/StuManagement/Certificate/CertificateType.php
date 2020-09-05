<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("student_certificate_type");

  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }

  if(!empty($type)){
    if($_GET['type'] == 'sel_limit_type'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn->limit_more($col,$start,$num,$selobj);
    }
    else if($_GET['type'] == 'sel_type'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_type'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_type'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_type'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $sel = $_POST['sel'] ? $_POST['sel'] : [];
      $res = $conn->delete_more($sel,$arr);
    }
    echo json_encode($res);
  }




?>