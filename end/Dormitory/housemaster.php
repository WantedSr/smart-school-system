<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("dormitory_housemaster");
  $conn2 = new Link("dormitory_build");
  $conn3 = new Link("dormitory_dormroom");

  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){
    if($type == 'sel_limit_housemaster'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $reqType = $_GET['reqType'] ? $_GET['reqType'] : null;
      if($reqType == 'department'){
        $department = $selobj['department'];
        $res1 = $conn->query("SELECT build_id,department FROM dormitory_build WHERE department = '$department'")->fetchAll(PDO::FETCH_ASSOC);
        $arr = [];
        foreach($res1 as $key => $row){
          array_push($arr,"dormroom_id = '".$row."'");
        }
        $str = join($arr," OR ");
        $res = $conn->query("SELECT $col FROM dormitory_housemaster WHERE $str LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $res = $conn->limit_more($col,$start,$num,$selobj);
      }
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $build = $row['build'];
        $dormroom = $row['dormroom_id'];
        $user = $row['created_user'];
        $housemaster = $row['housemaster'];
        $res[$key]['campus'] = $conn->query("SELECT campus_name FROM base_campus WHERE campus_id = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
        $res[$key]['dormroom_id'] = $conn->query("SELECT dormroom_name FROM dormitory_dormroom WHERE dormroom_id = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['build'] = $conn->query("SELECT build_name FROM dormitory_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        $res[$key]['housemaster'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$housemaster'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_housemaster_count'){
      $col = "COUNT(*)";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $reqType = $_GET['reqType'] ? $_GET['reqType'] : null;
      if($reqType == 'department'){
        $department = $selobj['department'];
        $res1 = $conn->query("SELECT build_id,department FROM dormitory_build WHERE department = '$department'")->fetchAll(PDO::FETCH_ASSOC);
        $arr = [];
        foreach($res1 as $key => $row){
          array_push($arr,"dormroom_id = '".$row."'");
        }
        $str = join($arr," OR ");
        $res = $conn->query("SELECT $col FROM dormitory_housemaster WHERE $str")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $res = $conn->select_more($col,$selobj);
      }
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $build = $row['build'];
        $dormroom = $row['dormroom_id'];
        $user = $row['created_user'];
        $res[$key]['campus'] = $conn->query("SELECT campus_name FROM base_campus WHERE campus_id = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
        $res[$key]['dormroom_id'] = $conn->query("SELECT dormroom_name FROM dormitory_dormroom WHERE dormroom_id = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['build'] = $conn->query("SELECT build_name FROM dormitory_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_housemaster'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_housemaster'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_housemaster'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_housemaster'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'imp_housemaster'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){
          
          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";

          $campus = $row['campus'];
          $arr[$i]['build'] = $row['build'] = $conn2->select_more("build_id",[
            "campus"=>$campus,
            'build_name'=>$row['build']
          ])[0]['build_id'];

          $build = $row['build'];
          $arr[$i]['dormroom_id'] = $row['dormroom_id'] = $conn3->select_more("dormroom_id",[
            'campus'=>$campus,
            "dormroom_name"=>$row['dormroom_id'],
            'build'=>$build,
          ])[0]['dormroom_id'];

          $arr[$i]['semester'] = $row['semester'] = $conn->query("SELECT * FROM base_semester 
          WHERE semester = '".$row["semester"]."' 
          AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semesterId'];

          $housemaster = $row['housemaster'];

          $arr[$i]['housemaster'] = $row['housemaster'] = $conn->query("SELECT * FROM teacher_info 
          WHERE campus = '$campus' 
          AND username = '$housemaster'  
          AND teacher_job = 'T3'")->fetchAll(PDO::FETCH_ASSOC)[0]['userid']; 

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");
  
          $sql = "INSERT INTO dormitory_housemaster($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();

          array_push($newarr,$res);
          // array_push($newarr,$row);
        }
        $res = $newarr;
      }
    }
    echo json_encode($res);
  }





?>