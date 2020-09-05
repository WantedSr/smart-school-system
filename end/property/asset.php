<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("property_asset");
  $type = $_GET['type'] ? $_GET['type'] : null;

  if(!empty($type)){
    if($type == 'sel_limit_asset'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $ast = $row['asset_type'];
        $user = $row['created_user'];
        $housemaster = $row['housemaster'];
        $res[$key]['asset_type'] = $conn->query("SELECT type_name FROM property_asset_type WHERE type_id = '$ast'")->fetchAll(PDO::FETCH_ASSOC)[0]['type_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_asset'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_asset'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_asset'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_asset'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    echo json_encode($res);
  }





?>