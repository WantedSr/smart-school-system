<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('stuset_attendance_option');
  $conn1 = new Link('commonly_attendance_course');
  $conn2 = new Link('commonly_attendance_early');
  $conn3 = new Link('commonly_attendance_between');

  function getDaysInMonth($year = '', $month = ''){
    if (empty($year)) $year = date('Y');
    if (empty($month)) $month = date('m');
    if (in_array($month, array(1, 3, 5, 7, 8, '01', '03', '05', '07', '08', 10, 12))) {
      $text = '31';//月大
    } elseif ($month == 2 || $month == '02') {
      if (($year % 400 == 0) || (($year % 4 == 0) && ($year % 100 !== 0))) {//判断是否是闰年
        $text = '29';//闰年2月
      } else {
        $text = '28';//平年2月
      }
    } else {
      $text = '30';//月小
    }
    return $text;
  }

  if(isset($_GET['type']) || isset($_POST['type'])){
    
    if($_POST['type'] == 'get_course_situation'){

      $col = $_POST['col'] ? $_POST['col'] : "*";
      
      $user = $_POST['user'] ? $_POST['user'] : null;
      $date = $_POST['date'] ? $_POST['date'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $session = $_POST['session'] ? $_POST['session'] : null;

      
      $selobj = [
        "semester"=>$semester,
        'campus'=>$campus,
        'department'=>$department,
        'class'=>$class,
        'date'=>$date,
        'session'=>$session,
      ];

      $res = $conn1->select_more($col,$selobj);
      // $res = $selobj;

      foreach($res as $key => $row){
        $stu = $row['student'];
        $class = $row['class'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$stu'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $attendance = $row['attendance'];
        $res[$key]['attendance'] = $conn->select_more('option_name',["option_id"=>$attendance])[0]['option_name'];
        $discipline = json_decode($row['discipline']);

        for($i=0;$i<count($discipline);$i++){
          $row2 = $discipline[$i];
          $discipline[$i] = $conn->select_more('option_name',["option_id"=>$row2])[0]['option_name'];
        }
        $res[$key]['discipline'] = $discipline;
      }

    }

    else if($_GET['type'] == 'get_early_situation'){

      // $class = $_GET['class'] ? $_GET['class'] : null;
      $col = $_GET['col'] ? $_GET['col'] : null;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn2->select_more($col,$selobj);
      // $res = $conn2->select_more("*");

      foreach($res as $key => $row){
        $stu = $row['student'];
        $class = $row['class'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$stu'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $attendance = $row['attendance'];
        $res[$key]['attendance'] = $conn->select_more('option_name',["option_id"=>$attendance])[0]['option_name'];
        $discipline = json_decode($row['discipline']);

        for($i=0;$i<count($discipline);$i++){
          $row2 = $discipline[$i];
          $discipline[$i] = $conn->select_more('option_name',["option_id"=>$row2])[0]['option_name'];
        }
        $res[$key]['discipline'] = $discipline;
      }

      // $res = $col;

    }
    
    else if($_GET['type'] == 'get_between_situation'){

      // $class = $_GET['class'] ? $_GET['class'] : null;
      $col = $_GET['col'] ? $_GET['col'] : null;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn3->select_more($col,$selobj);
      // $res = $conn2->select_more("*");

      foreach($res as $key => $row){
        $stu = $row['student'];
        $class = $row['class'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$stu'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $attendance = $row['attendance'];
        $res[$key]['attendance'] = $conn->select_more('option_name',["option_id"=>$attendance])[0]['option_name'];
        $discipline = json_decode($row['discipline']);

        for($i=0;$i<count($discipline);$i++){
          $row2 = $discipline[$i];
          $discipline[$i] = $conn->select_more('option_name',["option_id"=>$row2])[0]['option_name'];
        }
        $res[$key]['discipline'] = $discipline;
      }

      // $res = $col;

    }

    else if($_POST['type'] == 'del_course'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn1->delete_more('id',$arr);
    }

    echo json_encode($res);
  }




?>