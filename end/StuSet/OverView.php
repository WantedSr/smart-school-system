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

    if($_GET['type'] == 'sel_option'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $model = $_GET['model'] ? $_GET['model'] : null;
      $obj1 = [
        'campus'=>$campus,
        'model'=>$model,
      ];
      $res = $conn->select_more($col,$obj1);
    }

    else if($_GET['type'] == 'get_num'){
      
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $model = $_GET['model'] ? $_GET['model'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;

      if($model == 'course'){
        $m = '课堂登记';
      }
      else if($model == 'early'){
        $m = '早段登记';
      }
      else if($model == 'between'){
        $m = '课间操登记';
      }

      // $model = '课堂登记';
      $obj1 = [
        'campus'=>$campus,
        'model'=>$m,
      ];

      $optionres = $conn->select_more($col,$obj1);
      $newarr = [];
      foreach($optionres as $key => $row){
 
        $optionId = $row['option_id'];
        $optionType = $row['type'];
        
        $date = $_GET['date'] ? $_GET['date'] : null;

        if($model == 'course'){
          $tb = 'commonly_attendance_course';
        }
        else if($model == 'early'){
          $tb = 'commonly_attendance_early';
        }
        else if($model == 'between'){
          $tb = 'commonly_attendance_between';
        }

        if(isset($_GET['department'])){
          $department = $_GET['department'] ? $_GET['department'] : null;
          if($optionType == 'attendance'){
            $sql = "SELECT class,attendance,discipline,count(attendance) FROM $tb WHERE semester = '$semester' AND date = '$date' AND department = '$department' AND attendance like '%$optionId%' GROUP BY department";
            $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            // $res = $date;
          }else if($optionType == 'discipline'){
            $sql = "SELECT class,attendance,discipline,count(attendance) FROM $tb WHERE semester = '$semester' AND date = '$date' AND department = '$department' AND discipline like '%$optionId%' GROUP BY department";
            $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            // $res = $date;
          }
          $newarr[$optionId] = $res;
        }
      }
      
      $res = $newarr;

    }

    else if($_GET['type'] == 'get_course_situation'){

      // $class = $_GET['class'] ? $_GET['class'] : null;
      $col = $_GET['col'] ? $_GET['col'] : null;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn1->select_more($col,$selobj);

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

    else if($_GET['type'] == 'get_course_num'){

      // $class = $_GET['class'] ? $_GET['class'] : null;
      $col = $_GET['col'] ? $_GET['col'] : null;
      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;

      $model = '课堂登记';

      $obj1 = [
        'campus'=>$campus,
        'model'=>$model,
      ];

      $optionres = $conn->select_more($col,$obj1);

      $newarr = [];

      foreach($optionres as $key => $row){
 
        $optionId = $row['option_id'];
        $optionType = $row['type'];
        
        if($seltype == 'day'){
          $date = $_GET['date'] ? $_GET['date'] : null;
          $session = $_GET['session'] ? $_GET['session'] : null;

          if(isset($_GET['department'])){
            $department = $_GET['department'];
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date = '$date' AND session = '$session' AND department = '$department' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date = '$date' AND session = '$session' AND department = '$department' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }else{
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date = '$date' AND session = '$session' AND campus = '$campus' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date = '$date' AND session = '$session' AND campus = '$campus' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }
          
        }

        else if($seltype == 'month'){
          
          $datestart = $_GET['datestart'] ? $_GET['datestart'] : null;
          $dateend = $_GET['dateend'] ? $_GET['dateend'] : null;
          

          if(isset($_GET['department'])){
            $department = $_GET['department'];
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date >= $datestart AND date <= $dateend AND department = '$department' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date >= $datestart AND date <= $dateend AND department = '$department' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }else{
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date >= $datestart AND date <= $dateend AND campus = '$campus' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE date >= $datestart AND date <= $dateend AND campus = '$campus' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }
          
        }
        
        else if($seltype == 'semester'){

          $semester = $_GET['semester'] ? $_GET['semester'] : null;

          if(isset($_GET['department'])){
            $department = $_GET['department'];
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE semester = '$semester' AND department = '$department' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE semester = '$semester' AND department = '$department' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }else{
            if($optionType == 'attendance'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE semester = '$semester' AND campus = '$campus' AND attendance like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }else if($optionType == 'discipline'){
              $sql = "SELECT class,attendance,discipline,count(attendance) FROM commonly_attendance_course WHERE semester = '$semester' AND campus = '$campus' AND discipline like '%$optionId%' GROUP BY class";
              $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            $newarr[$optionId] = $res;
          }
          
        }
        
      
      }
      
      $res = $newarr;
    
    }

    else if($_GET['type'] == 'sel_attendance_good'){

      $monthstart = $_GET['month'] ? $_GET['month'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      
      $monthend = $monthstart + 2592000000;

      $t = substr($monthstart,0,-3);

      $y = date('Y',$t);
      $m = date('m',$t);
      
      $day = getDaysInMonth($y,$m);
      if(isset($_GET['department'])){
        $department = $_GET['department'];
        $sql = "SELECT student,class,COUNT(*) FROM `commonly_attendance_course` 
          WHERE 
            attendance = 'T1' 
            AND discipline = '[]' 
            AND semester = '$semester' 
            AND date >= $monthstart 
            AND date <= $monthend 
            AND department = '$department' 
          GROUP BY student";
      }
      else if(isset($_GET['campus'])){
        $campus = $_GET['campus'];
        $sql = "SELECT student,class,COUNT(*) FROM `commonly_attendance_course` 
          WHERE 
            attendance = 'T1' 
            AND discipline = '[]' 
            AND semester = '$semester' 
            AND date >= $monthstart 
            AND date <= $monthend 
            AND campus = '$campus' 
          GROUP BY student";
      }

      $res = $conn1->query($sql)->fetchAll(PDO::FETCH_ASSOC);

      $arr = [];

      foreach($res as $key => $row){
        if(!isset($arr[$row['class']])){
          $arr[$row['class']] = [];
        }
        $student = $row['student'];      
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$student'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        if($row['COUNT(*)'] >= $day){
          array_push($arr[$row['class']],$res[$key]['student_name']);
        }
      }

      $res = $arr;

    }
      
      echo json_encode($res);
  }




?>