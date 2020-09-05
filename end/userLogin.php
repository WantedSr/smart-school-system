<?php

  header('Access-Control-Allow-Origin: *');
  
  require_once("./conn.php");

  session_start();

  if(isset($_SESSION['login'])){ 
    if($_SESSION['type'] == "teacher" || $_SESSION['type'] == 'admin'){
      $conn = new Link('teacher_info');
    }else if($_SESSION['type'] == 'student'){
      $conn = new Link("student_info");
    }
    $userid = $_SESSION['userid'];
    $res = $conn->select("*","userid",$userid);
    echo $res;
  }else{
    echo null;
  }

?>