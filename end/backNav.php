<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("./conn.php");
  $conn = new Link('system_nav');
  $conn2 = new Link("system_authority");
  $conn3 = new Link("tb_login");

  $token = $_GET['token'];

  $msg = $conn->check_token($token);
  $msg = json_decode($msg,true);
  
  if($msg['code'] != 200){
    echo json_encode($msg);
  }else{
    $userId = $msg['info'][0];
    $res = $conn3->select("*",'user_id',$userId)[0];
    $group = $res['user_group'];

    $arr = [];
    $alone = $conn2->select("*","authority_id",$group)[0]['authority_range'];
    // echo $alone;
    $alone = json_decode($alone);
  
    $arr = [];
    
  
    foreach($alone as $key => $row){
      $id = $row->id;
      $sql1 = "SELECT * FROM system_nav WHERE nav_lever = 1 AND nav_state = 1 AND nav_id = '$id' ORDER BY nav_group DESC";
      $res1 = $conn->query($sql1)->fetchAll(PDO::FETCH_ASSOC);
      if(count($res1)){
        $res1 = $conn->query($sql1)->fetchAll(PDO::FETCH_ASSOC)[0];
        $obj = [
          "nav_name"=>$res1['nav_name'],
          "nav_id"=>$res1['nav_id'],
        ];
        $arr2 = [];
        foreach($row->child as $row2){
          $id2 = $row2;
          $sql2 = "SELECT * FROM system_nav WHERE nav_lever = 2 AND nav_state = 1 AND nav_id = '$id2' ORDER BY nav_id ASC";
          $res2 = $conn->query($sql2)->fetchAll(PDO::FETCH_ASSOC)[0];
          $obj2 = [
            "nav_name"=>$res2['nav_name'],
            "nav_id"=>$res2['nav_id'],
            "href"=>$res2["nav_url"],
            'icon'=>$res2['icon'],
          ];
          array_push($arr2,$obj2);
        }
        $obj['children'] = $arr2;
        array_push($arr,$obj);
      }else{
        continue;
      }
    }
    echo json_encode($arr);
  }


?>