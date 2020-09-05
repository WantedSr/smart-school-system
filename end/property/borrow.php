<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("property_borrow");
  $conn2 = new Link("property_asset");
  $type = $_GET['type'] ? $_GET['type'] : null;

  if(!empty($type)){
    if($type == 'sel_limit_borrow'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $assetId = $row['asset_id'];
        $dep = $row['department'];
        $asset = $conn->query("SELECT * FROM property_asset WHERE asset_id = '$assetId'")->fetchAll(PDO::FETCH_ASSOC)[0];
        $res[$key]['asset_name'] =$asset['asset_name'];
        $res[$key]['unit'] =$asset['unit'];
        $res[$key]['department'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$dep'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_borrow'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_borrow'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $bornum = $_GET['num'] ? $_GET['num'] : null;
      $asset = $_GET['asset'] ? $_GET['asset'] : null;
      $res = $conn->insert($arr);
      $num = $conn->query("SELECT * FROM property_asset WHERE asset_id = '$asset'")->fetchAll(PDO::FETCH_ASSOC)[0]['borrow_num'];
      $obj = [
        "borrow_num"=>$num + $bornum,
      ];
      $conn2->update($obj,'asset_id',$asset);
    }
    else if($type == 'upa_borrow'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $old = $_GET['old'] ? $_GET['old'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
      $newasset = $obj['asset_id'];
      $oldasset = $old['asset_id'];
      $newnum = $obj['borrow_num'];
      $oldnum = $old['borrow_num'];
      $oldassnum = $conn2->select("*",'asset_id',$oldasset)[0]['borrow_num'];
      $oldobj = [
        "borrow_num"=> $oldassnum - $oldnum,
      ];
      $conn2->update($oldobj,'asset_id',$oldasset);
      $newassnum = $conn2->select("*",'asset_id',$newasset)[0]['borrow_num'];
      $newobj = [
        "borrow_num"=> $newassnum + $newnum,
      ];
      $conn2->update($newobj,'asset_id',$newasset);
    }
    else if($type == 'del_borrow'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    echo json_encode($res);
  }





?>