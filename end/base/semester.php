<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitSemester($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_semester');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getSemester($col,$selobj){
    $conn = new Link('base_semester');
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addSemester($arr=null){
    $conn = new Link('base_semester');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaSemester($obj=null,$sel=null,$val=null){
    $conn = new Link('base_semester');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_semester'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getLimitSemester($col,$start,$num,$sel,$val);
      echo json_encode($res);
    }
    if($_GET['type'] == 'sel_semester'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getSemester($col,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_semester'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addSemester($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_semester'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaSemester($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'set_semester'){
      $conn = new Link('base_semester');
      $res = $conn->update(["state"=>'0'],'state','1');
      if($res){
        $id = $_GET['id'] ? $_GET['id'] : null;
        $res = $conn->update(['state'=>'1'],'semesterId',$id);
        echo $res;
      }
    }
    else if($_GET['type'] == 'del_semester'){
      $conn = new Link('base_semester');
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      if(count($arr)){
        $res = $conn->delete_more('id',$arr);
        echo $res;
      }
    }
  }




?>