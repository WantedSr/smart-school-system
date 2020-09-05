<?php

  
  header('Access-Control-Allow-Origin: *');
  require_once("../../conn.php");
  // require_once("../../chinaPosition.php");

  $conn2 = new Link("tb_login");

  function selLimitTea($col,$start,$num,$selobj){
    $conn = new Link("teacher_info");

    $res = $conn->limit_more($col,$start,$num,$selobj);

    foreach($res as $key => $row){
      $province = $row['house_register_provinces'];
      $city = $row['house_register_city'];
      $area = $row['house_register_area'];
      $u = $row['created_user'];
      $job = $row['teacher_job'];
      $t = $row['type'];
      switch($t){
        case "1":
          $t = "在职";
          break;
        case "2":
          $t = "离职";
          break;
        case "3":
          $t = "调休";
          break;
        case "4":
          $t = "退休";
          break;
        default: 
          $t = '其他';
          break;
      }
      $res[$key]['house_register_provinces'] = $conn->query("SELECT * FROM china_provinces WHERE provinceid = '$province'")->fetchAll(PDO::FETCH_ASSOC)[0]['province'];
      $res[$key]['house_register_city'] = $conn->query("SELECT * FROM china_cities WHERE cityid = '$city'")->fetchAll(PDO::FETCH_ASSOC)[0]['city'];
      $res[$key]['house_register_area'] = $conn->query("SELECT * FROM china_areas WHERE areaid = '$area'")->fetchAll(PDO::FETCH_ASSOC)[0]['area'];;
      $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      $res[$key]['teacher_job'] = $conn->query("SELECT * FROM system_authority WHERE authority_id = '$job'")->fetchAll(PDO::FETCH_ASSOC)[0]['authority_name'];
      $res[$key]['type'] = $t;
    }

    return $res;
  }
  function selTea($col,$selobj) {
    $conn = new Link("teacher_info");
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addTea($arr){
    $conn = new Link('teacher_info');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaTea($obj,$sel,$val){
    $conn = new Link("teacher_info");
    $res = $conn->update($obj,$sel,$val);
  }
  function delTea(){

  }

  $type = $_GET['type'] ? $_GET['type'] : null;
  if(!empty($type)){
    if($type == 'sel_limit_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      
      $res = selLimitTea($col,$start,$num,$selobj);
    }else if($type == 'sel_tea'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = selTea($col,$selobj);
    }else if($type == 'upa_tea'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaTea($obj,$sel,$val);

      $obj2 = [
        "user_id"=>$obj['userid'],
        'user_name'=>$obj["username"],
        "user_phone"=>$obj["teacher_phone"],
      ];

      $conn2->update($obj2,'user_id',$obj['userid']);

    }else if($type == 'add_tea'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addTea($arr);
    }
    echo json_encode($res);
  }





?>