<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");
  $conn = new Link("teacher_info");
  $conn2 = new Link("tb_login");

  function std_class_object_to_array($stdclassobject){
    $array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
    foreach ($array as $key => $value) {
      $value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
      $array[$key] = $value;
    }
    return $array;
  }



  $arr = $_GET['arr'] ? $_GET['arr'] : null;
  $arr2 = $_GET['arr2'] ? $_GET['arr2'] : null;

  if(empty($arr) || empty($arr2)){
    $res = false;
  }else{
    $arr2[2] = md5(123456);
    $res1 = $conn->insert($arr);
    $res2 = $conn2->insert($arr2);
    $res = [$res1,$res2];
  }





  echo json_encode($res);
  





?>