<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('stuset_attendance_option');
  $conn1 = new Link('commonly_attendance_course');
  $conn2 = new Link('commonly_attendance_early');
  $conn3 = new Link('commonly_attendance_between');

  if(isset($_GET['type']) || isset($_POST['type'])){

    if($_GET['type'] == 'sel_option'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $model = $_GET['model'] ? $_GET['model'] : null;
      $obj1 = [
        'campus'=>$campus,
        'model'=>$model,
        'type'=>'attendance',
        'status'=>"1",
      ];
      $obj2 = [
        'campus'=>$campus,
        'model'=>$model,
        'type'=>'discipline',
        'status'=>"1",
      ];
      $res1 = $conn->select_more($col,$obj1);
      $res2 = $conn->select_more($col,$obj2);
      $res = [$res1,$res2];
    }
    else if($_POST['type'] == 'add_attendance_course'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $session = $_POST['session'] ? $_POST['session'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $time = time().'000';
      
      $num = $conn1->select_more("COUNT(*)",[
        'campus'=>$campus,
        'class'=>$class,
        'semester'=>$semester,
        'session'=>$session,
        'date'=>$date,
      ])[0]['COUNT(*)'];

      if($num > 0){
        $res = [true,false];
      }else{
        $newarr = [];
        for($i=0;$i<count($arr);$i++){
          $row = $arr[$i];
          $obj = [
            $row['userid'],
            $row['class'],
            $session,
            $date,
            $semester,
            $row['attendance'],
            $row['discipline'],
            $row['school'],
            $campus,
            $department,
            $user,
            $time
          ];
          $res2 = $conn1->insert($obj);
          array_push($newarr,$res2);
        };
        $res = [false,$newarr];
      }
      
    }
    else if($_POST['type'] == 'add_attendance_early'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $time = time().'000';
      
      $num = $conn2->select_more("COUNT(*)",[
        'campus'=>$campus,
        'class'=>$class,
        'semester'=>$semester,
        'date'=>$date,
      ])[0]['COUNT(*)'];

      if($num > 0){
        $res = [true,false];
      }else{
        $newarr = [];
        for($i=0;$i<count($arr);$i++){
          $row = $arr[$i];
          $obj = [
            $row['userid'],
            $row['class'],
            $date,
            $semester,
            $row['attendance'],
            $row['discipline'],
            $row['school'],
            $campus,
            $department,
            $user,
            $time
          ];
          $res2 = $conn2->insert($obj);
          array_push($newarr,$res2);
        };
        $res = [false,$newarr];
      }
      
    }
    else if($_POST['type'] == 'add_attendance_between'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $time = time().'000';
      
      $num = $conn3->select_more("COUNT(*)",[
        'campus'=>$campus,
        'class'=>$class,
        'semester'=>$semester,
        'date'=>$date,
      ])[0]['COUNT(*)'];

      if($num > 0){
        $res = [true,false];
      }else{
        $newarr = [];
        for($i=0;$i<count($arr);$i++){
          $row = $arr[$i];
          $obj = [
            $row['userid'],
            $row['class'],
            $date,
            $semester,
            $row['attendance'],
            $row['discipline'],
            $row['school'],
            $campus,
            $department,
            $user,
            $time
          ];
          $res2 = $conn3->insert($obj);
          array_push($newarr,$res2);
        };
        $res = [false,$newarr];
      }
      
    }
    echo json_encode($res);
  }




?>