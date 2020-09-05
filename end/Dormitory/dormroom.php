<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("dormitory_dormroom");
  $conn2 = new Link("dormitory_build");
  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  function getDormroomId($build){
    $conn = new Link("dormitory_dormroom");
    $res = $conn->query("SELECT COUNT(*) FROM dormitory_dormroom WHERE build = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
    $res ++;
    if(strlen($res) == 1){
      $res = '00'.$res;
    }else if(strlen($res) == 2){
      $res = '0'.$res;
    }
    return $res = $build."_".$res;
  }


  if(!empty($type)){
    if($type == 'sel_limit_dormroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $build = $row['build'];
        $user = $row['created_user'];
        $res[$key]['campus'] = $conn->query("SELECT campus_name FROM base_campus WHERE campus_id = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_name'];
        $res[$key]['build'] = $conn->query("SELECT build_name FROM dormitory_build WHERE build_id = '$build'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($type == 'sel_dormroom'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_dormroom'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = $conn->insert($arr);
    }
    else if($type == 'upa_dormroom'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'imp_dormroom'){
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

          $arr[$i]['dormroom_id'] = $row['dormroom_id'] = getDormroomId($row['build']);

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");
  
          $sql = "INSERT INTO dormitory_dormroom($newcol) VALUES($value)";
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
    else if($type == 'del_dormroom'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'get_dormroom_id'){
      // $arr = ['A','B','C','D','E','F','G','H','I,','J',',K,','L',',M,','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
      $build = $_GET['build'] ? $_GET['build'] : null;
      $res = getDormroomId($build);
    }
    else if($type == 'sel_more_dormroom'){
      $department = $_GET['department'] ? $_GET['department'] : null;
      $buildtype = $_GET['buildtype'] ? $_GET['buildtype'] : "学生宿舍";
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $col = $_GET['col'] ? $_GET['col'] : "*";
      if($department == 'all'){
        $obj1 = [
          'campus'=>$campus,
          'type'=>$buildtype,
          'status'=>'1',    
        ];
      }else{
        $obj1 = [
          'department'=>$department,
          'campus'=>$campus,
          'type'=>$buildtype,
          'status'=>'1',    
        ];
      }
      $res1 = $conn2->select_more("*",$obj1);
      $newarr = [];
      // $res = $res1;
      foreach($res1 as $key => $row){
        $buildId = $row['build_id'];
        $obj2 = [
          'build'=>$buildId,
          'campus'=>$campus,
          'status'=>'1',
        ];
        $res2 = $conn->select_more($col,$obj2);
        foreach($res2 as $key2 => $row2){
          array_push($newarr,$row2);
        }
      }
      $res = $newarr;

      foreach($res as $key => $row){
        $dormroom = $row['dormroom_id'];
        // $res[$key]['dormitory'] = $conn->query("SELECT housemaster FROM dormitory_housemaster 
        //   WHERE semester = '$semester' 
        //     AND campus = '$campus' 
        //     AND dormroom_id = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC)[0]['housemaster'];
        $res[$key]['people_num'] = $conn->query("SELECT COUNT(*) FROM dormitory_stu_arrangement 
          WHERE semester = '$semester' 
            AND dormroom = '$dormroom' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
      }


    }
    echo json_encode($res);
  }





?>