<?php

  header('Access-Control-Allow-Origin: *');
  require_once("./conn.php");

  function getProvinces($id=null){
    $conn = new Link('china_provinces');
    if($id){
      $res = $conn->select("*",'provinceid',$id);
    }else{
      $res = $conn->select("*");
    }
    return $res;
  }
  function getCity($id){
    if(!$id) return false;
    $conn = new Link('china_cities');
    $res = $conn->select("*",'provinceid',$id);
    return $res;
  }
  function getArea($id){
    if(!$id) return false;
    $conn = new Link('china_areas');
    $res = $conn->select("*",'cityid',$id);
    return $res;
  }

  if(isset($_GET['type'])){
    $type = $_GET['type'];
    switch($type){
      case "get_provinces":
        $res = getProvinces();
        echo json_encode($res);
        break;
      case "get_cities":
        $provinceId = $_GET['provinceId'] ? $_GET['provinceId'] : null;
        $res = getCity($provinceId);
        echo json_encode($res);
        break;
      case "get_areas":
        $cityId = $_GET['cityId'] ? $_GET['cityId'] : null;
        $res = getArea($cityId);
        echo json_encode($res);
        break;
    }
  }



?>