<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitCourseType($col="*",$start,$num,$selobj=null){
    $conn = new Link('base_course_type');
    $selobj['status'] = '1';
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function getCourseType($col="*",$selobj=null){
    $conn = new Link('base_course_type');
    $selobj['status'] = '1';
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addCourseType($arr=null){
    $conn = new Link('base_course_type');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaCourseType($obj=null,$sel=null,$val=null){
    $conn = new Link('base_course_type');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delCourseType($arr=null){
    $conn = new Link('base_course_type');
    if(count($arr)){
      $obj = [
        "status"=>'0',
      ];
      $res = $conn->update_more($obj,'id',$arr);
      return $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_course_credit'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $res = getLimitCourseType($col,$start,$num,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_course_credit'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getCourseType($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_course_credit'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addCourseType($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_course_credit'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaCourseType($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_course_credit'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delCourseType($arr);
      echo $res;
    }
    else if($_GET['type'] == 'get_course_credit_id'){
      $conn = new Link('base_course_type');
      $res = $conn->query("SELECT * FROM base_course_type ORDER BY id DESC LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC);
      echo substr($res[0]['course_id'],1);
    }
  }




?>