<?php

  header('Access-Control-Allow-Origin: *');
  require_once("./conn.php");
  $id = $_GET['id'];
  $conn = new Link('tb_login');

  $res = $conn->select("*",'user_id',$id);
  echo json_encode($res);
  // echo $_GET['id'];


?>