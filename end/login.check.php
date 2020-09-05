<?php

header('Access-Control-Allow-Origin: *');

require_once('conn.php');
$conn = new Link('tb_login');
$feedback = "账户密码错误";

// $userPhone = '15625226090';
// $username = '2017217042';
// $password = md5('123456');
$userPhone = $_POST['userPhone'];
if(isset($_POST['userid'])){
  $userid = $_POST['userid'];
  $password = md5($_POST['password']);
  
  $res = $conn->query("SELECT * FROM tb_login WHERE user_id = '$userid' AND password = '$password' AND user_phone = '$userPhone'")->fetchAll(PDO::FETCH_ASSOC);
  
  if(count($res) == 0){
    echo null;
  }
  else{
    $userid = $res[0]['user_id'];
    $mes = $conn->setToken($userid);  
    array_push($res,$mes);
    echo json_encode($res);
  }
}else{
  $res = $conn->select("*","user_phone",$userPhone);
  echo json_encode($res);
}



?>