<?php
  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("student_reward_type");

  $type = $_GET['type'];

  if(!empty($type)){
    $col = $_GET['col'] ? $_GET['col'] : "*";
    if($type == 'sel_limit_reward'){
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      
      foreach($res as $key => $row){
        $u = $row['created_user'];
        $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }

    }else if($type == 'sel_reward'){
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }else if($type == 'add_reward'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }else if($type == 'upa_reward'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }else if($type == 'del_reward'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }else if($type == 'get_reward_id'){
      $res = $conn->query("SELECT * FROM student_reward_type ORDER BY id DESC LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
    }
    echo json_encode($res);
  }


?>