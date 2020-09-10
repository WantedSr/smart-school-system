<?php

  require_once("./conn.php");
  $conn = new Link('china_provinces');
  // phpinfo();
  // echo "Hello World"
  $res = $conn->select("*");
  foreach($res as $key => $row){
    echo $row['province'];
    echo ",";
  }

  echo strtotime("2020-09-10");

?>