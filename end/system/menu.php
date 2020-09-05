<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("system_nav");

  $reqType = $_GET['reqType'];

  if($reqType == 'sel_menu'){
    $col = $_GET['col'] ? $_GET['col'] : "*";
    $lever = $_GET['lever'];
    $upnav = $_GET['upnav'] ? $_GET['upnav'] : null;
    $type = $_GET['type'] ? $_GET['type'] : 'data';
    $start = $_GET['start'] ? $_GET['start'] : 0;
    $num = $_GET['num'] ? $_GET['num'] : 15;
    if(empty($upnav)){
      $sel = "nav_lever";
      if($type == 'data'){
        $res = $conn->select($col,$sel,$lever);
      }else if($type == 'list'){
        $res = $conn->limit($col,$start,$num,$sel,$lever);  
      }
    }else{
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : [
        "nav_lever"=>$lever,
        "nav_up_id"=>$upnav,
      ];
      if($type == 'data'){
        
        $newarr = [];
        foreach($selobj as $key => $val){
          $child = $key." = '".$val."'";
          // $child = $key." = '".$val."'";
          array_push($newarr,$child);
        }
        $str = join($newarr," AND ");

        $res = $conn->query("SELECT $col FROM system_nav WHERE $str ORDER BY nav_group ASC")->fetchAll(PDO::FETCH_ASSOC);
      }else if($type == 'list'){
        $res = $conn->limit_more($col,$start,$num,$selobj);
      }
    }
    echo json_encode($res);
  }
  else if($reqType == "sel_more_menu"){
    $col = $_GET['col'] ? $_GET['col'] : "*";
    $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
    $res = $conn->select_more($col,$selobj);
    echo json_encode($res);
  }
  else if($reqType == 'add_menu'){
    $arr = $_GET['arr'] ? $_GET['arr'] : null;
    $res = $conn->insert($arr);
    echo $res;
  }
  else if($reqType == 'get_menu_id'){
    $upnav = $_GET['upnav'] ? $_GET['upnav'] : '0';
    $res = $conn->select("COUNT(*)","nav_up_id",$upnav);
    echo json_encode($res);
  }
  else if($reqType == 'upa_menu'){
    $obj = $_GET['obj'] ? $_GET['obj'] : null;
    $sel = $_GET['sel'] ? $_GET['sel'] : null;
    $val = $_GET['val'] ? $_GET['val'] : null;
    $res = $conn->update($obj,$sel,$val);
    echo $res;
  }



?>