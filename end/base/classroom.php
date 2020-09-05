<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./campus.php");
  require_once("./department.php");
  require_once("./build.php");

  function getLimitClassRoom($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_room');
    $res = $conn->limit($col,$start,$num,$sel,$val);
    return $res;
  }
  function getClassRoom($col="*",$sel=null,$val=null){
    $conn = new Link('base_room');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addClassRoom($arr=null){
    $conn = new Link('base_room');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaClassRoom($obj=null,$sel=null,$val=null){
    $conn = new Link('base_room');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delClassRoom($arr=null){
    $conn = new Link('base_room');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }

  function getClassRoomId($build){
    $conn = new Link('base_room');
    if($build != null){
      $sql = "SELECT * FROM base_room WHERE room_build = '$build' ORDER BY id DESC LIMIT 0,1";
      $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['room_id'];
      $arr = explode('_',$res);
      return $arr[count($arr)-1];
    }else{
      return 0;
    }
  }


  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_classroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitClassRoom($col,$start,$num,$sel,$val);
      foreach($res as $key => $row){
        $cam = $row['room_campus'];
        $dep = $row['room_department'];
        $build = $row['room_build'];
        $cam = getCampus("*","campus_id",$cam)[0]['campus_name'];
        $dep = getDepartment("*","department_id",$dep)[0]['department_name'];
        $build = getBuild("*","build_id",$build)[0]['build_name'];
        $res[$key]['room_campus'] = $cam;
        $res[$key]['room_department'] = $dep;
        $res[$key]['room_build'] = $build;
      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_classroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getClassRoom($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_classroom'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addClassRoom($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_classroom'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaClassRoom($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_classroom'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delClassRoom($arr);
      echo $res;
    }
    else if($_POST['type'] == 'imp_classroom'){
      $conn = new Link("base_room");
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){

          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";
          
          $school = $row['room_school'];
          $campus = $row['room_campus'];
          $build = $row['room_build'];
          $department = $row['room_department'];
          
          $arr[$i]['room_campus'] = $row['room_campus'] = $conn->query("SELECT * FROM base_campus WHERE campus_name = '$campus' AND campus_school = '$school'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_id'];
          $campus = $arr[$i]['room_campus'];
          $arr[$i]['room_department'] = $row['room_department'] = $conn->query("SELECT * FROM base_department WHERE department_name = '$department' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $arr[$i]['room_build'] = $row['room_build'] = $conn->query("SELECT * FROM base_build WHERE build_name = '$build' AND build_department = '".$row['room_department']."' AND build_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_id'];
          
          $build = $arr[$i]['room_build'];
          
          $roomId = getClassRoomId($build);

          if($roomId == 0 || $roomId == null || $roomId == ''){
            $roomId = $build."_001";
          }else{
            $roomId = (int)$roomId + 1;
            if($roomId<10){
              $roomId = '00'.$roomId;
            }else if($roomId<100){
              $roomId = '0'.$roomId;
            }
            $roomId = $build."_".$roomId;
          }
          

          $arr[$i]['room_id'] = $roomId;

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");

          $sql = "INSERT INTO base_room($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();
          array_push($newarr,$res);
          // array_push($newarr,$arr[$i]);
        }

        echo json_encode($newarr);
      }
    }
    else if($_GET['type'] == 'get_classroom_id'){
      $build = $_GET['build'] ? $_GET['build'] : null;
      $res = getClassRoomId($build);
      echo $res;
    }
  }




?>