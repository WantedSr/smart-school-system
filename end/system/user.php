<?php
    
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn2 = new Link("teacher_info");
  $conn3 = new Link("student_info");

  function selLimitUser($col,$start,$num,$selobj){
    $conn = new Link('tb_login');
    $res = $conn->limit_more($col,$start,$num,$selobj);
    return $res;
  }
  function selUser($col,$selobj){
    $conn = new Link('tb_login');
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addUser($arr){
    $conn = new Link("tb_login");
    $res = $conn->insert($arr);
    return $res;
  }
  function upaUser($obj,$sel,$val){
    $conn = new Link("tb_login");
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delUser($sel,$arr){
    $conn = new Link("tb_login");
    $res = $conn->delete_more($sel,$arr);
    return $res;
  }




  $type = $_GET['type'] ? $_GET['type'] : null;

  if(!empty($type)){
    if($type == 'sel_limit_user'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = selLimitUser($col,$start,$num,$selobj);
      $conn = new Link('tb_login');
      foreach($res as $key => $row){
        $userid = $row['created_user'];
        $usergroup = $row['user_group'];
        $username = $conn->select("*","user_id",$userid)[0]['user_name'];
        $res[$key]['created_user'] = $username; 
        $res[$key]['user_group'] = $conn->query("SELECT * FROM system_authority WHERE authority_id = '$usergroup'")->fetchAll(PDO::FETCH_ASSOC)[0]['authority_name']; 
      }
    }
    else if($type == 'sel_user'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = selUser($col,$selobj);
    }
    else if($type == 'add_user'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $arr[2] = md5('123456');
      $res = addUser($arr);
      // $res = $arr;
    }
    else if($type == 'upa_user'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $userid = $obj['user_id'];
      $res = upaUser($obj,$sel,$val);
      if(count($conn2->select("*",'userid',$userid)) != 0){

        $obj2 = [
          "userid"=>$obj['user_id'],
          "username"=>$obj["user_name"],
          "teacher_phone"=>$obj['user_phone'],
          "teacher_job"=>$obj["user_group"],
          "school"=>$obj["user_school"],
          "campus"=>$obj["user_campus"]
        ];
        $conn2->update($obj2,'userid',$userid);
      }else if(count($conn3->select("*",'userid',$userid)) != 0){
        
        $obj2 = [
          "userid"=>$obj['user_id'],
          "username"=>$obj["user_name"],
          "phone_me"=>$obj['user_phone'],
          "job"=>$obj["user_group"],
          "school"=>$obj["user_school"],
          "campus"=>$obj["user_campus"]
        ];
        $conn3->update($obj2,"userid",$userid);
      }
    }
    else if($type == 'upa_user_pass'){
      $obj = [
        "password"=>md5('123456'),
      ];
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaUser($obj,$sel,$val);
    }
    echo json_encode($res);
  }



?>