<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('stuset_attendance_option');
  $conn1 = new Link('commonly_attendance_course');
  $conn2 = new Link('commonly_attendance_early');
  $conn3 = new Link('commonly_attendance_between');

  if(isset($_GET['type']) || isset($_POST['type'])){

    if($_POST['type'] == 'add_attendance_course'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $session = $_POST['session'] ? $_POST['session'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $time = time().'000';
      
      
      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];

        $student = $row['userid'];
        $class = $row['class'];

        $attendance = $row['attendance'];
        $discipline = $row['discipline'];

        $num = $conn1->select_more("COUNT(*)",[
          'student'=>$student,
          'session'=>$session,
          'class'=>$class,
          'date'=>$date,
          'semester'=>$semester,
          'campus'=>$campus,
        ])[0]['COUNT(*)'];

        if($num > 0){
          
          $sql = "UPDATE commonly_attendance_course 
            SET attendance = :attendance , discipline = :discipline 
            WHERE student = :student 
              AND session = :session 
              AND date = :date
              AND semester = :semester 
              AND campus = :campus";

          $res2 = $conn->prepare($sql);
          $res2->bindParam(":attendance",$attendance);
          $res2->bindParam(":discipline",$discipline);
          $res2->bindParam(":student",$student);
          $res2->bindParam(":session",$session);
          $res2->bindParam(":date",$date);
          $res2->bindParam(":semester",$semester);
          $res2->bindParam(":campus",$campus);

          $res2 = $res2->execute();

        }else{

          $obj = [
            $student,
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

        }
        array_push($newarr,$res2);
      };

      $res = $newarr;
      
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
      
      
      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];

        $student = $row['userid'];
        $class = $row['class'];

        $attendance = $row['attendance'];
        $discipline = $row['discipline'];

        $num = $conn2->select_more("COUNT(*)",[
          'student'=>$student,
          'class'=>$class,
          'date'=>$date,
          'semester'=>$semester,
          'campus'=>$campus,
        ])[0]['COUNT(*)'];

        if($num > 0){
          
          $sql = "UPDATE commonly_attendance_course 
            SET attendance = :attendance , discipline = :discipline 
            WHERE student = :student 
              AND date = :date
              AND semester = :semester 
              AND campus = :campus";

          $res2 = $conn->prepare($sql);
          $res2->bindParam(":attendance",$attendance);
          $res2->bindParam(":discipline",$discipline);
          $res2->bindParam(":student",$student);
          $res2->bindParam(":date",$date);
          $res2->bindParam(":semester",$semester);
          $res2->bindParam(":campus",$campus);

          $res2 = $res2->execute();

        }else{

          $obj = [
            $student,
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

        }
        array_push($newarr,$res2);
      };

      $res = $newarr;
      
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
      
      
      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];

        $student = $row['userid'];
        $class = $row['class'];

        $attendance = $row['attendance'];
        $discipline = $row['discipline'];

        $num = $conn2->select_more("COUNT(*)",[
          'student'=>$student,
          'class'=>$class,
          'date'=>$date,
          'semester'=>$semester,
          'campus'=>$campus,
        ])[0]['COUNT(*)'];

        if($num > 0){
          
          $sql = "UPDATE commonly_attendance_course 
            SET attendance = :attendance , discipline = :discipline 
            WHERE student = :student 
              AND date = :date
              AND semester = :semester 
              AND campus = :campus";

          $res2 = $conn->prepare($sql);
          $res2->bindParam(":attendance",$attendance);
          $res2->bindParam(":discipline",$discipline);
          $res2->bindParam(":student",$student);
          $res2->bindParam(":date",$date);
          $res2->bindParam(":semester",$semester);
          $res2->bindParam(":campus",$campus);

          $res2 = $res2->execute();

        }else{

          $obj = [
            $student,
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

        }
        array_push($newarr,$res2);
      };

      $res = $newarr;
      
    }
    echo json_encode($res);
  }




?>