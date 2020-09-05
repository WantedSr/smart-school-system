<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("property_asset_type");
  $type = $_GET['type'] ? $_GET['type'] : null;

  if(!empty($type)){
    if($type == 'sel_limit_asset_type'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $user = $row['created_user'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_asset_type'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_asset_type'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_asset_type'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_asset_type'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'get_asset_type_id'){
      $res = $conn->query("SELECT * FROM property_asset_type ORDER BY id DESC LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['type_id'];
      $num = substr($res,3,3) + 1;
      if($num < 10){
        $num = '00'.$num;
      }else if($num < 100){
        $num = '0'.$num;
      }
      $res = "AS_".$num;
    }
    echo json_encode($res);
  }





?>