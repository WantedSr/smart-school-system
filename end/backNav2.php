<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("./conn.php");
  $conn = new Link('nav');

  $arr= [];
  $navid = $_GET['navid'];

  $res1 = $conn->query("SELECT * FROM system_nav WHERE nav_up_id = '$navid' AND nav_lever = 3 AND nav_state = 1 ORDER BY nav_id ASC")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($res1 as $row){
    $childId = $row['nav_id'];
    $obj=[
      "nav_id"=>$childId,
      "nav_name"=>$row['nav_name'],
      "href"=>$row['nav_url'],
    ];
    $children = [];
    $res2 = $conn->query("SELECT * FROM system_nav WHERE nav_up_id = '$childId' AND nav_lever = 4 AND nav_state = 1 ORDER BY nav_id ASC")->fetchAll(PDO::FETCH_ASSOC);
    if(count($res2) > 0){
      foreach($res2 as $row2){
        $obj2 = [
          "nav_id"=>$row2['nav_id'],
          "nav_name"=>$row2['nav_name'],
          "href"=>$row2['nav_url'],
        ];
        array_push($children,$obj2);
      }
    }
    $obj['children'] = $children;
    
    array_push($arr,$obj);
  }

  echo json_encode($arr);

?>