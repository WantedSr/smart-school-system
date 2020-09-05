<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("dormitory_build");
  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){
    if($type == 'sel_limit_build'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $department = $row['department'];
        $user = $row['created_user'];
        $res[$key]['campus'] = $conn->query("SELECT campus_name FROM base_campus WHERE campus_id = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_build'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_build'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_build'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_build'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'get_build_id'){
      $arr = ['A','B','C','D','E','F','G','H','I,','J',',K,','L',',M,','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $res = $conn->query("SELECT COUNT(*) FROM dormitory_build WHERE campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
      $res = $campus."_".$arr[$res];
    }
    echo json_encode($res);
  }





?>