<?php
  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("student_school_status");

  $type = $_GET['type'];

  if(!empty($type)){
    $col = $_GET['col'] ? $_GET['col'] : "*";
    if($type == 'sel_limit_status'){

      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 0;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : 0;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $u = $row['created_user'];
        $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    
    }else if($type == 'sel_status'){

      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);

    }else if($type == 'add_status'){

      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
      
    }else if($type == 'upa_status'){
      
    }else if($type == 'del_status'){

    }else if($type == 'get_reward_id'){
      $res = $conn->query("SELECT * FROM student_school_status ORDER BY id DESC LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
    }
    echo json_encode($res);
  }


?>