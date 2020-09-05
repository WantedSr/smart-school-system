<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('stuset_health_option');
  $conn1 = new Link('stuset_health_option_list');

  // $conn2 = new Link('commonly_health_classroom');
  // $conn3 = new Link('commonly_health_blackborad');

  if(isset($_GET['type']) || isset($_POST['type'])){

    if($_GET['type'] == 'sel_option'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $model = $_GET['model'] ? $_GET['model'] : null;
      $date = $_GET['date'] ? $_GET['date'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;

      if($model == 'classroom'){
        $model = 'S0001_A_H1';
      }
      else if($model == 'blackboard'){
        $model = "S0001_A_H2";
      }
      else if($model == 'stuhealth'){
        $model = "S0001_A_H3";
      }
      
      $selobj = [
        'model'=>$model,
        'campus'=>$campus,
      ];

      $res = $conn->select_more($col,$selobj);
      foreach($res as $key => $row){
        $opId = $row['option_id'];
        $obj2 = [
          'campus'=>$campus,
          'option_id'=>$opId,
        ];
        $res2 = $conn1->select_more("id,name,score",$obj2);
        $res[$key]['children'] = $res2;
      }

    }
    else if($_GET['type'] == 'sel_health'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $model = $_GET['model'] ? $_GET['model'] : null;
      $date = $_GET['date'] ? $_GET['date'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;

      if($model == 'classroom'){
        $model = 'S0001_A_H1';
        $conn2 = new Link('commonly_health_classroom');
      }
      else if($model == 'blackboard'){
        $model = "S0001_A_H2";
        $conn2 = new Link('commonly_health_blackboard');
      }
      else if($model == 'stuhealth'){
        $model = "S0001_A_H3";
        $conn2 = new Link('commonly_health_stuhealth');
      }
      $selobj = [
        // 'model'=>$model,
        'campus'=>$campus,
        'date'=>$date,
      ];

      $res = $conn2->select_more($col,$selobj);

      // foreach($res as $key => $row){
      //   $res[$key]['state'] = $conn2->select_more("COUNT(*)",[
      //     'campus'=>$campus,
      //     'date'=>$date,
      //   ])[0]['COUNT(*)'];
      // };

    }
    else if($_POST['type'] == 'add_health'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $model = $_POST['model'] ? $_POST['model'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      
      $num = $conn1->select_more("COUNT(*)",[
        'campus'=>$campus,
        'semester'=>$semester,
        'class'=>$class,
        'date'=>$date,
      ])[0]['COUNT(*)'];

      if($num > 0){
        $res = [true,false];
      }else{
        // $newarr = [];

        if($model == 'classroom'){
          $conn2 = new Link('commonly_health_classroom');
        }
        else if($model == 'blackboard'){
          $conn2 = new Link('commonly_health_blackboard');
        }
        else if($model == 'stuhealth'){
          $conn2 = new Link('commonly_health_stuhealth');
        }

        $res2 = $conn2->insert($arr);

        $res = [false,$res2];
      }
      
    }
    else if($_POST['type'] == 'add_attendance_early'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
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
            $user,
            $time
          ];
          $res2 = $conn3->insert($obj);
          array_push($newarr,$res2);
        };
        $res = [false,$newarr];
      }
      
    }
    else if($_POST['type'] == 'add_attendance_stuhealth'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $time = time().'000';
      
      $num = $conn4->select_more("COUNT(*)",[
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