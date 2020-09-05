<?php

  
  header('Access-Control-Allow-Origin: *');
  require_once("../../conn.php");

  function arrToStr($arr){
    $newarr = [];
    for($i=0;$i<count($arr);$i++){
      array_push($newarr,"userid = :".$i);
    }
    $str = join($newarr," OR ");
    return $str;
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
  function delTea(){

  }

  $type = $_GET['type'] ? $_GET['type'] : null;
  if(!empty($type)){

    if($type == 'sel_limit_tea'){

      $conn = new Link("teacher_info");

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $department = $_GET['department'] ? $_GET['department'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;

      if(!empty($department)){
        if($department != 'all'){
          $res = $conn->limit($col,$start,$num,'department',$department);
        }else{
          $res = $conn->query("SELECT $col FROM teacher_info WHERE campus = '$campus' AND department != '0' ORDER BY department DESC LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        }
      }

      foreach($res as $key => $row){
        $dep = $row['department'];
        $res[$key]['department'] = $conn->query("SELECT * FROM `base_department` WHERE department_id = '$dep'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
      }
      
    }else if($type == 'sel_tea'){

      $conn = new Link("teacher_info");
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $dep = $_GET['department'] ? $_GET['department'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $sql = "SELECT * FROM teacher_info WHERE campus = '$campus' AND department = '0' OR department = '' OR department = '$dep'";
      $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
      $arr = [];
      $defaultArr = [];
      foreach($res as $key => $row){
        $obj = [
          'label'=>$row['username'],
          "key"=>$row['userid'],
        ];
        if($row['department'] != '0' && $row['department'] != ''){
          array_push($defaultArr,$row['userid']);
        }
        array_push($arr,$obj);
      }
      $newarr = [$arr,$defaultArr];
      $res = $newarr;

    }else if($type == 'upa_tea'){

      $conn = new Link("teacher_info");

      $dep = $_GET['department'] ? $_GET['department'] : null;
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $str = arrtoStr($arr);
      $set = $_GET['set'] ? $_GET['set'] : null;
      if($set == 'set'){
        $res = $conn->prepare("UPDATE teacher_info SET department = '$dep' WHERE $str");
      }else if($set = 'unset'){
        $res = $conn->prepare("UPDATE teacher_info SET department = '0' WHERE $str");
      }
      for($i=0;$i<count($arr);$i++){
        $res->bindParam(":".$i,$arr[$i]);
      }
      $res->execute();
      // $res = $str;
    }else if($type == 'add_tea'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addTea($arr);
    }
    echo json_encode($res);
  }





?>