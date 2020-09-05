<?php

  header('Access-Control-Allow-Origin: *');
  require_once("./conn.php");
  $conn = new Link('teacher_info');
  $conn2 = new Link('tb_login');

  $token = $_POST['token'];
  $msg = json_decode($conn->check_token($token),true);
  if($msg['code'] != 200){
    echo 'error';
  }
  else{
    $userid = $msg['info'][0];

    $type = $_POST['type'];
    switch($type){
      case "upaPwd":
        $oldPwd = md5($_POST['oldpwd']);
        $newPwd = md5($_POST['newpwd']);
        $pwd = $conn2->select("*","user_id",$userid)[0]['password'];
        if($oldPwd != $pwd){
          echo "really";
        }else{
          $obj=[
            'password'=>$newPwd,
          ];
          $res = $conn2->update($obj,"user_id",$userid);
          echo $res;
        }
        break;
      case "setMessage":
        $str = $_POST['msg'];
        $obj=[
          'msgSet'=>$str,
        ];
        $res=$conn->update($obj,"userid",$userid);
        echo $res;
        break;
      case "upaTea":
        $obj = $_POST['obj'];
        $res = $conn->update($obj,'userid',$userid);
        if($res){
          echo $res;
        }else{
          echo "error";
        }
        break;
    }
  }


?>