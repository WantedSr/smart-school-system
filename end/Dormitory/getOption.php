<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('dormitory_attendance_option');
  $conn1 = new Link("dormitory_attendance");

  function getOption($arr,$campus,$type){
    $conn2 = new Link("dormitory_attendance_option");
    $conn3 = new Link("student_info");
    $newarr = [];
    if($type == 'attendance'){
      foreach($arr as $key2 => $row2){
        $att = $row2['attendance'];
        $student = $row2['student'];
        $obj = [];
        $obj['attendance'] = $conn2->select_more("option_name",[
          'status'=>'1',
          'campus'=>$campus,
          "option_id"=>$att,
        ])[0]['option_name'];
  
        $obj['student'] = $conn3->select_more("username",[
          'job'=>'S1',
          'campus'=>$campus,
          "userid"=>$student,
        ])[0]['username'];
        array_push($newarr,$obj);
      }
    }
    else if($type == 'discipline'){
      foreach($arr as $key2 => $row2){
        $dis = json_decode($row2['discipline']);
        $student = $row2['student'];
        $obj = [];
        foreach($dis as $key3 => $row3){
          $dis[$key3] = $conn2->select_more("option_name",[
            'status'=>'1',
            'campus'=>$campus,
            "option_id"=>$row3,
          ])[0]['option_name'];
        }
        $obj['discipline'] = $dis;
  
        $obj['student'] = $conn3->select_more("username",[
          'job'=>'S1',
          'campus'=>$campus,
          "userid"=>$student,
        ])[0]['username'];
        array_push($newarr,$obj);
      }
    }
    return $newarr;
  }
  
  //调用这个函数，将其幻化为数组，然后取出对应值
  function objarray_to_array($obj) {
    $ret = [];
    foreach ($obj as $key => $value) {
      if (gettype($value) == "array" || gettype($value) == "object"){
        $ret[$key] = objarray_to_array($value);
      }else{
        array_push($ret,$value);
      }
    }
    return $ret;
  }

  if(isset($_GET['type']) || isset($_POST['type'])){

    if($_GET['type'] == 'sel_option'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $obj1 = [
        'campus'=>$campus,
        'type'=>'attendance',
        'status'=>'1',
        'state'=>'1'
      ];
      $obj2 = [
        'campus'=>$campus,
        'type'=>'discipline',
        'status'=>'1',
        'state'=>'1'
      ];
      $res1 = $conn->select_more($col,$obj1);
      $res2 = $conn->select_more($col,$obj2);
      $res = [$res1,$res2];
    }
    else if($_POST['type'] == 'add_attendance'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $hour = $_POST['hour'] ? $_POST['hour'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $dormroom = $_POST['dormroom'] ? $_POST['dormroom'] : null;
      $time = time().'000';
      
      $num = $conn1->select_more("COUNT(*)",[
        'campus'=>$campus,
        'hour'=>$hour,
        'semester'=>$semester,
        'reg_date'=>$date,
        'department'=>$department,
        'dormroom'=>$dormroom,
      ])[0]['COUNT(*)'];

      if($num > 0){
        $res = [true,false];
      }else{
        $newarr = [];
        for($i=0;$i<count($arr);$i++){
          $row = $arr[$i];
          $res2 = $conn1->insert($row);
          array_push($newarr,$res2);
        };
        $res = [false,$newarr];
      }

      // $res = $num;
      
    }
    else if($_POST['type'] == 'add_attendance_report'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $hour = $_POST['hour'] ? $_POST['hour'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $dormroom = $_POST['dormroom'] ? $_POST['dormroom'] : null;
      $time = time().'000';

      $newarr = [];

      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];

        $obj = [
          "student"=>$row['student'],
          "dormroom"=>$row['dormroom'],
          "hour"=>$row['hour'],
          "reg_date"=>$row['date'],
          "semester"=>$row['semester'],
          "campus"=>$row['campus'],
        ];

        $num = $conn1->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];

        if($num > 0){

          $sql = "UPDATE dormitory_attendance SET 
          attendance = '".$row['attendance']."', 
          discipline = '".$row['discipline']."' 
            WHERE student = '".$row['student']."' 
            AND dormroom = '".$row['dormroom']."' 
            AND hour = '".$row['hour']."' 
            AND semester = '".$row['semester']."' 
            AND campus = '".$row['campus']."' 
            AND reg_date = '".$row['date']."'";
          
          $res2 = $conn1->prepare($sql);
          $res2 = $res2->execute();

        }else{
          $arr2 = objarray_to_array($row);
          $res2 = $conn1->insert($arr2);
        }

        array_push($newarr,$res2);

      };
      $res = [false,$newarr];
      
    }
    else if($_GET['type'] == 'sel_day_attendance'){
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;
      $dormroom = $_GET['dormroom'] ? $_GET['dormroom'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $date = $_GET['date'] ? $_GET['date'] : null;

      $sql1 = "SELECT * FROM dormitory_attendance 
      WHERE campus = '$campus' 
        AND department = '$department' 
        AND dormroom = '$dormroom' 
        AND reg_date = '$date' 
        AND semester = '$semester' 
      GROUP BY hour";
      $res = $conn->query($sql1)->fetchAll(PDO::FETCH_ASSOC);

      foreach($res as $key => $row){
        $hour = $row['hour'];
        $dormroom = $row['dormroom'];
        $dormitory = $row['dormitory'];
        $campus = $row['campus'];
        $res[$key]['dormroom_name'] = $conn->query("SELECT * FROM dormitory_dormroom WHERE dormroom_id = '$dormroom' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['dormitory_name'] = $conn->query("SELECT * FROM teacher_info WHERE userid = '$dormitory' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['attendance'] = $conn->query("SELECT COUNT(*) FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND department = '$department' 
            AND dormroom = '$dormroom' 
            AND attendance != 'T1' 
            AND reg_date = '$date' 
            AND hour = '$hour' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        
        $res[$key]['discipline'] = $conn->query("SELECT COUNT(*) FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND department = '$department' 
            AND dormroom = '$dormroom' 
            AND discipline != '[]' 
            AND reg_date = '$date' 
            AND hour = '$hour' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
      }
    }
    else if($_GET['type'] == 'sel_attendance'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : "*";
      $res = $conn1->select_more($col,$selobj);
    }
    else if($_GET['type'] == 'sel_attendance_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : "*";
      $res = $conn1->select_more($col,$selobj);
      foreach($res as $key => $row){
        $campus = $row['campus'];
        $student = $row['student'];
        $dormroom = $row['dormroom'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info 
        WHERE campus = '$campus' 
        AND userid = '$student' 
        AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['dormroom_name'] = $conn->query("SELECT * FROM dormitory_dormroom 
        WHERE dormroom_id = '$dormroom' 
        AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
      }
    }
    else if($_GET['type'] == 'get_more_attendance'){
      $col = $_GET['col'] ? $_GET['col'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;
      $date = $_GET['date'] ? $_GET['date'] : null;
      $sql = "SELECT $col FROM dormitory_attendance 
      WHERE reg_date = :reg_date 
        AND semester = :semester 
        AND department = :department 
        AND campus = :campus 
      GROUP BY dormroom";
      $res = $conn1->prepare($sql);
      $res->bindParam(":reg_date",$date);
      $res->bindParam(":semester",$semester);
      $res->bindParam(":department",$department);
      $res->bindParam(":campus",$campus);
      $res->execute();
      $res = $res->fetchAll(PDO::FETCH_ASSOC);
      
      foreach($res as $key => $row){
        $dormroom = $row['dormroom'];
        $dormitory = $row['dormitory'];
        $res[$key]['dormroom_name'] = $conn->query("SELECT * FROM dormitory_dormroom WHERE dormroom_id = '$dormroom' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['dormroom_name'];
        $res[$key]['dormitory_name'] = $conn->query("SELECT * FROM teacher_info WHERE userid = '$dormitory' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        
        $res[$key]['morning'] = [];

        $morningAtt = $conn->query("SELECT student,attendance FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '早上' 
            AND attendance != 'T1' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $morningDis = $conn->query("SELECT student,discipline FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '早上' 
            AND discipline != '[]' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $res[$key]['morning']['attendance'] = getOption($morningAtt,$campus,"attendance");
        $res[$key]['morning']['discipline'] = getOption($morningDis,$campus,"discipline");
        
        $res[$key]['noon'] = [];

        $noonAtt = $conn->query("SELECT student,attendance FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '中午' 
            AND attendance != 'T1' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $noonDis = $conn->query("SELECT student,discipline FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '中午' 
            AND discipline != '[]' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $res[$key]['noon']['attendance'] = getOption($noonAtt,$campus,"attendance");
        $res[$key]['noon']['discipline'] = getOption($noonDis,$campus,"discipline");

        
        $res[$key]['afternoon'] = [];
        
        $afternoonAtt = $conn->query("SELECT student,attendance FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '下午' 
            AND attendance != 'T1' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $afternoonDis = $conn->query("SELECT student,discipline FROM dormitory_attendance 
          WHERE campus = '$campus' 
            AND dormroom = '$dormroom' 
            AND hour = '下午' 
            AND discipline != '[]' 
            AND reg_date = '$date' 
            AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);

        $res[$key]['afternoon']['attendance'] = getOption($afternoonAtt,$campus,"attendance");
        $res[$key]['afternoon']['discipline'] = getOption($afternoonDis,$campus,"discipline");

      }
    }
    else if($_GET['type'] == 'sel_more_attendance'){

      $where = $_GET['where'] ? $_GET['where'] : null;
      $sel_type = $_GET['sel_type'] ? $_GET['sel_type'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;

      if($sel_type == 'class'){
        if($where == 'day'){
          $time = $_GET['time'] ? $_GET['time'] : null;

          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $obj = [
              'semester'=>$semester,
              'student'=>$student,
              'campus'=>$campus,
              'reg_date'=>$time,
            ];
            $res = $conn1->select_more("*",$obj);
          }
          else if(isset($_GET['class'])){
            $class = $_GET['class'] ? $_GET['class'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester' 
                AND reg_date = '$time' 
                AND student in (
                  SELECT userid FROM student_info 
                    WHERE campus = '$campus'
                      AND job = 'S1' 
                      AND class = '$class'
                )")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $obj = [
              'semester'=>$semester,
              'department'=>$department,
              'campus'=>$campus,
              'reg_date'=>$time,
            ];
            $res = $conn1->select_more("*",$obj);
          }
        }
        else if($where == 'month'){
          $month_start = $_GET['month'] ? $_GET['month'] : null;
          $month_end = $month_start + 2592000000;
          
          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $res = $conn->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester' 
                AND student = '$student' 
                AND reg_date >= $month_start 
                AND reg_date < $month_end")->fetchAll(PDO::FETCH_ASSOC);
          }
          else if(isset($_GET['class'])){
            $class = $_GET['class'] ? $_GET['class'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND reg_date >= $month_start 
                AND reg_date < $month_end 
                AND student in (
                  SELECT userid FROM student_info 
                    WHERE campus = '$campus'
                      AND job = 'S1' 
                      AND class = '$class'
                )")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND reg_date >= $month_start 
                AND reg_date < $month_end 
                AND department = '$department'")->fetchAll(PDO::FETCH_ASSOC);
          }
        }
        else if($where == 'semester'){

          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $obj = [
              'semester'=>$semester,
              'student'=>$student,
              'campus'=>$campus,
            ];
            $res = $conn1->select_more("*",$obj);
          }
          else if(isset($_GET['class'])){
            $class = $_GET['class'] ? $_GET['class'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND student in (
                  SELECT userid FROM student_info 
                    WHERE campus = '$campus'
                      AND job = 'S1' 
                      AND class = '$class'
                )")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $obj = [
              'semester'=>$semester,
              'department'=>$department,
              'campus'=>$campus,
            ];
            $res = $conn1->select_more("*",$obj);
          }
        }
      }
      else if($sel_type == 'dormroom'){
        if($where == 'day'){
          $time = $_GET['time'] ? $_GET['time'] : null;

          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $obj = [
              'semester'=>$semester,
              'student'=>$student,
              'campus'=>$campus,
              'reg_date'=>$time,
            ];
            $res = $conn1->select_more("*",$obj);
          }
          else if(isset($_GET['dormroom'])){
            $dormroom = $_GET['dormroom'] ? $_GET['dormroom'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester' 
                AND reg_date = '$time' 
                AND dormroom = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $obj = [
              'semester'=>$semester,
              'department'=>$department,
              'campus'=>$campus,
              'reg_date'=>$time,
            ];
            $res = $conn1->select_more("*",$obj);
          }

        }
        else if($where == 'month'){
          $month_start = $_GET['month'] ? $_GET['month'] : null;
          $month_end = $month_start + 2592000000;
          
          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $res = $conn->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester' 
                AND student = '$student' 
                AND reg_date >= $month_start 
                AND reg_date < $month_end")->fetchAll(PDO::FETCH_ASSOC);
          }
          else if(isset($_GET['dormroom'])){
            $dormroom = $_GET['dormroom'] ? $_GET['dormroom'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND reg_date >= $month_start 
                AND reg_date < $month_end 
                AND dormroom = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND reg_date >= $month_start 
                AND reg_date < $month_end 
                AND department = '$department'")->fetchAll(PDO::FETCH_ASSOC);
          }
        }
        else if($where == 'semester'){

          if(isset($_GET['student'])){
            $student = $_GET['student'] ? $_GET['student'] : null;
            $obj = [
              'semester'=>$semester,
              'student'=>$student,
              'campus'=>$campus,
            ];
            $res = $conn1->select_more("*",$obj);
          }
          else if(isset($_GET['dormroom'])){
            $dormroom = $_GET['dormroom'] ? $_GET['dormroom'] : null;
            $res = $conn1->query("SELECT * FROM dormitory_attendance 
              WHERE campus = '$campus' 
                AND semester = '$semester'
                AND dormroom = '$dormroom'")->fetchAll(PDO::FETCH_ASSOC);
          }
          else{
            $obj = [
              'semester'=>$semester,
              'department'=>$department,
              'campus'=>$campus,
            ];
            $res = $conn1->select_more("*",$obj);
          }
        }
      }

      foreach($res as $key => $row){

        $student = $row['student'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info 
        WHERE campus = '$campus' 
        AND userid = '$student' 
        AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

        $res[$key]['class'] = $conn->query("SELECT class FROM student_info 
        WHERE campus = '$campus' 
        AND userid = '$student' 
        AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class'];
        $att = $row['attendance'];
        
        $dis = json_decode($row['discipline']);
        $down_1 = $conn->select_more("score",[
          'option_id'=>$att,
          'campus'=>$campus,
          'status'=>'1',
        ])[0]['score'];

        $down_2 = 0;
        for($i=0;$i<count($dis);$i++){
          $thatscore = $dis[$i];
          $score = $conn->select_more("score",[
            'option_id'=>$thatscore,
            'campus'=>$campus,
            'status'=>'1',
          ])[0]['score'];
          
          $down_2 += (int)$score;
        }

        $res[$key]['score'] = (int)$down_1 + (int)$down_2;

        $res[$key]['discipline'] = $dis;
      
      } 

    }
    echo json_encode($res);
  }




?>