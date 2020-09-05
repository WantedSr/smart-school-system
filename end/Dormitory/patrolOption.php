<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  
  $conn = new Link('dormitory_attendance_option');

  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn->limit_more($col,$start,$num,$selobj);
    }
    else if($_GET['type'] == 'sel_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_POST['type'] == 'add_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($_POST['type'] == 'upa_option'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($_POST['type'] == 'del_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($arr);
    }
    else if($_GET['type'] == 'get_option_id'){
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      if($campus != null){
        $sql = "SELECT * FROM dormitory_attendance_option WHERE campus = '$campus' ORDER BY id DESC LIMIT 0,1";
        $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        // $arr = explode('_',$res);
        $res = 'T'.($res+1);
      }else{
        $res = 'T1';
      }
    }
    echo json_encode($res);
  }




?>