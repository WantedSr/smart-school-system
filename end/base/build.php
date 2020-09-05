<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./department.php");
  require_once("./campus.php");

  function getLimitBuild($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_build');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getBuild($col="*",$sel=null,$val=null){
    $conn = new Link('base_build');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addBuild($arr=null){
    $conn = new Link('base_build');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaBuild($obj=null,$sel=null,$val=null){
    $conn = new Link('base_build');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delBuild($arr=null){
    $conn = new Link('base_build');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_build'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $res = getLimitBuild($col,$start,$num,$sel,$val);
      foreach($res as $key => $row){
        $department = $row['build_department'];
        $campus = $row['build_campus'];
        $department = getDepartment("*","department_id",$department)[0]['department_name'];
        $campus = getCampus("*","campus_id",$campus)[0]['campus_name'];
        $res[$key]['build_department'] = $department;
        $res[$key]['build_campus'] = $campus;
      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_build'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getBuild($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_build'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addBuild($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_build'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaBuild($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_build'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delBuild($arr);
      echo $res;
    }
  }




?>