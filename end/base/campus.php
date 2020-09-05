<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  function getLimitCampus($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_campus');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getCampus($col="*",$sel=null,$val=null){
    $conn = new Link('base_campus');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addCampus($arr=null){
    $conn = new Link('base_campus');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaCampus($obj=null,$sel=null,$val=null){
    $conn = new Link('base_campus');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delCampus($arr=null){
    $conn = new Link('base_campus');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_campus'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitCampus($col,$start,$num,$sel,$val);
      $china = new Link('china_provinces');
      foreach($res as $key => $row){

        $position = $row['campus_position'];
        $arr = [];
        $province = substr($position,0,2).'0000';
        $city = substr($position,0,4).'00';
        $area = $position;
        array_push($arr,$china->select("*",'provinceid',$province)[0]['province']);
        array_push($arr,$china->query("SELECT * FROM china_cities WHERE cityid = '$city'")->fetchAll(PDO::FETCH_ASSOC)[0]['city']);
        array_push($arr,$china->query("SELECT * FROM china_areas WHERE areaid = '$area'")->fetchAll(PDO::FETCH_ASSOC)[0]['area']);
        $str = join($arr,'-');
        $res[$key]['campus_position'] = $str;

        $school = $row['campus_school'];
        $school = $conn->query("SELECT * FROM base_school WHERE school_id = '$school'")->fetchAll(PDO::FETCH_ASSOC)[0]['school_name'];
        $res[$key]['campus_school'] = $school;

      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_campus'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getCampus($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_campus'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addCampus($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_campus'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaCampus($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_campus'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delCampus($arr);
      echo $res;
    }
  }




?>