<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./campus.php");

  function getLimitDepartment($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_department');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getDepartment($col="*",$sel=null,$val=null){
    $conn = new Link('base_department');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addDepartment($arr=null){
    $conn = new Link('base_department');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaDepartment($obj=null,$sel=null,$val=null){
    $conn = new Link('base_department');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delDepartment($arr=null){
    $conn = new Link('base_department');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_department'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $res = getLimitDepartment($col,$start,$num,$sel,$val); 
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $campus = getCampus("*","campus_id",$campus)[0]['campus_name'];
        $res[$key]['campus'] = $campus;
      };
      echo json_encode($res);
    }
    if($_GET['type'] == 'sel_department'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getDepartment($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'set_department'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getDepartment($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_department'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addDepartment($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_department'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaDepartment($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_department'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delDepartment($arr);
      echo $res;
    }
  }




?>