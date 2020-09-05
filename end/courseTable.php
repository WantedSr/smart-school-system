<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("./conn.php");

  $conn = new Link('teach_class_schedule');
  $conn2 = new Link("teach_alter_class");

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

    if($_GET['type'] == 'sel_more_course_table'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      
      $department = $_GET['department'] ? $_GET['department'] : null;

      $time = $_GET['time'] ? $_GET['time'] : null;

      $res1 = $conn2->select_more("*",[
        'department'=>$department,
        'date'=>$time,
        ]);
        
      $res = $conn->select_more($col,$selobj);
        
      $time = $time != null ? substr($time,0,-3) : null;

      $day = date('w',$time);

      switch($day){
        case '0':
          $courseKey = "";
          $buildKey = "";
          $roomKey = "";
          break;
        case '1':
          $courseId = "mon_course";
          $courseKey = "mon_course_name";
          $buildKey = "mon_build";
          $roomKey = "mon_room";
          $teacherKey = "mon_teacher_name";
          break;
        case '2':
          $courseId = "tue_course";
          $courseKey = "tue_course_name";
          $buildKey = "tue_build";
          $roomKey = "tue_room";
          $teacherKey = "tue_teacher_name";
          break;
        case '3':
          $courseId = "wed_course";
          $courseKey = "wed_course_name";
          $buildKey = "wed_build";
          $roomKey = "wed_room";
          $teacherKey = "wed_teacher_name";
          break;
        case '4':
          $courseId = "thu_course";
          $courseKey = "thu_course_name";
          $buildKey = "thu_build";
          $roomKey = "thu_room";
          $teacherKey = "thu_teacher_name";
          break;
        case '5':
          $courseId = "fri_course";
          $courseKey = "fri_course_name";
          $buildKey = "fri_build";
          $roomKey = "fri_room";
          $teacherKey = "fri_teacher_name";
          break;
        case '6':
          $courseKey = "";
          $buildKey = "";
          $roomKey = "";
          break;
      }

      if(count($res1) > 0){
        for($i=0;$i<count($res);$i++){
          $row = $res[$i];
          for($r=0;$r<count($res1);$r++){
            $row1 = $res1[$r];
            if($row1['session'] == $row['session']){
              $res[$i][$courseKey] = $row1['course'];
              $res[$i][$buildKey] = $row1['build'];
              $res[$i][$roomKey] = $row1['room'];
              $res[$i][$teacherKey] = $row1['teacher'];
            }
          }
        }

      }

      foreach($res as $key => $row){
        
        $MonCourseId = $row['mon_course'];
        $TueCourseId = $row['tue_course'];
        $WedCourseId = $row['wed_course'];
        $ThuCourseId = $row['thu_course'];
        $FriCourseId = $row['fri_course'];
        
        $MonBuild = $row['mon_build'];
        $TueBuild = $row['tue_build'];
        $WedBuild = $row['wed_build'];
        $ThuBuild = $row['thu_build'];
        $FriBuild = $row['fri_build'];

        $MonRoom = $row['mon_room'];
        $TueRoom = $row['tue_room'];
        $WedRoom = $row['wed_room'];
        $ThuRoom = $row['thu_room'];
        $FriRoom = $row['fri_room'];

        
        if($row[$courseKey] != '' || $row[$courseKey] != null){
          $res[$key][$courseKey] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$row[$courseKey]'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        }else{
          $res[$key]['mon_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['tue_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['wed_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['thu_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['fri_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        }
        
        
        if($row[$teacherKey] != '' || $row[$teacherKey] != null){
          $res[$key][$teacherKey] = $conn->query("SELECT username FROM teacher_info WHERE userid = '$row[$teacherKey]'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        }else{
          $res[$key]['mon_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['tue_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['wed_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['thu_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['fri_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        }

        $res[$key]['mon_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$MonBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['tue_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$TueBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['wed_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$WedBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['thu_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$ThuBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['fri_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$FriBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
       
        $res[$key]['mon_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$MonRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['tue_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$TueRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['wed_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$WedRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['thu_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$ThuRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['fri_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$FriRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
      }

    }
    else if($_GET['type'] == 'sel_teacher_schedule_name'){

      $col = $_GET['col'] ? $_GET['col'] : "*";
      $semester = $_GET['semester'] ? $_GET['semester'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      
      $start = $_GET['start'] ? $_GET['start'] : null;
      $end = $_GET['end'] ? $_GET['end'] : null;

      $dateArr = [
        $start,
        $start+86400000,
        $start+2*(86400000),
        $start+3*(86400000),
        $start+4*(86400000),
        $start+5*(86400000),
        $end,
      ];

      $department = $_GET['department'] ? $_GET['department'] : null;

      $teacher = $_GET['teacher'] ? $_GET['teacher'] : null;

      $res1 = $conn2->query("SELECT * FROM teach_alter_class 
        WHERE department = '$department' 
          AND teacher = '$teacher' 
          AND date > $start 
          AND date < $end")->fetchAll(PDO::FETCH_ASSOC);

      $res = $conn->query("SELECT $col FROM teach_class_schedule 
        WHERE semester = '$semester' 
          AND campus = '$campus' 
          AND mon_course in (SELECT id FROM teach_teacher_schedule 
            WHERE teacher = '$teacher' 
              AND semester = '$semester' 
              AND campus = '$campus')
          OR tue_course in (SELECT id FROM teach_teacher_schedule 
            WHERE teacher = '$teacher' 
              AND semester = '$semester' 
              AND campus = '$campus')
          OR wed_course in (SELECT id FROM teach_teacher_schedule 
            WHERE teacher = '$teacher' 
              AND semester = '$semester' 
              AND campus = '$campus')
          OR thu_course in (SELECT id FROM teach_teacher_schedule 
            WHERE teacher = '$teacher' 
              AND semester = '$semester' 
              AND campus = '$campus')
          OR fri_course in (SELECT id FROM teach_teacher_schedule 
            WHERE teacher = '$teacher' 
              AND semester = '$semester' 
              AND campus = '$campus');
      )")->fetchAll(PDO::FETCH_ASSOC);

      
      $courseId = "";
      $courseKey = "";
      $buildKey = "";
      $roomKey = "";
      $teacherKey = "";
      $teacherId = "";
      
      if(count($res1) > 0 && count($res) > 0){
        for($i=0;$i<count($res);$i++){
          $row = $res[$i];
          for($r=0;$r<count($res1);$r++){

            $row1 = $res1[$r];
          
            $time = $row1['date'];
            $time = $time != null ? substr($time,0,-3) : null;
            $day = date('w',$time);
            switch($day){
              case '0':
                $courseKey = "";
                $buildKey = "";
                $roomKey = "";
                break;
              case '1':
                $courseId = "mon_course";
                $courseKey = "mon_course_name";
                $buildKey = "mon_build";
                $roomKey = "mon_room";
                $teacherKey = "mon_teacher_name";
                $teacherId = "mon_teacher";
                break;
              case '2':
                $courseId = "tue_course";
                $courseKey = "tue_course_name";
                $buildKey = "tue_build";
                $roomKey = "tue_room";
                $teacherKey = "tue_teacher_name";
                $teacherId = "tue_teacher";
                break;
              case '3':
                $courseId = "wed_course";
                $courseKey = "wed_course_name";
                $buildKey = "wed_build";
                $roomKey = "wed_room";
                $teacherKey = "wed_teacher_name";
                $teacherId = "wed_teacher";
                break;
              case '4':
                $courseId = "thu_course";
                $courseKey = "thu_course_name";
                $buildKey = "thu_build";
                $roomKey = "thu_room";
                $teacherKey = "thu_teacher_name";
                $teacherId = "thu_teacher";
                break;
              case '5':
                $courseId = "fri_course";
                $courseKey = "fri_course_name";
                $buildKey = "fri_build";
                $roomKey = "fri_room";
                $teacherKey = "fri_teacher_name";
                $teacherId = "fri_teacher";
                break;
              case '6':
                $courseKey = "";
                $buildKey = "";
                $roomKey = "";
                break;
            }
            if($row1['session'] == $row['session']){
              $res[$i][$courseKey] = $row1['course'];
              $res[$i][$buildKey] = $row1['build'];
              $res[$i][$roomKey] = $row1['room'];
              $res[$i][$teacherKey] = $row1['teacher'];
              $res[$i][$teacherId] = $row1['teacher'];
            }
          }
        }

        // $res = $res1;

      }else if(count($res1) > 0 && count($res) == 0){
        for($r=0;$r<count($res1);$r++){
          $row1 = $res1[$r];
          $time = array_search($row1['date'],$dateArr);
          $time = $time != null ? substr($time,0,-3) : null;
          $day = date('w',$time);
          switch($day){
            case '0':
              $courseKey = "";
              $buildKey = "";
              $roomKey = "";
              break;
            case '1':
              $courseId = "mon_course";
              $courseKey = "mon_course_name";
              $buildKey = "mon_build";
              $roomKey = "mon_room";
              $teacherKey = "mon_teacher_name";
              $teacherId = "mon_teacher";
              break;
            case '2':
              $courseId = "tue_course";
              $courseKey = "tue_course_name";
              $buildKey = "tue_build";
              $roomKey = "tue_room";
              $teacherKey = "tue_teacher_name";
              $teacherId = "tue_teacher";
              break;
            case '3':
              $courseId = "wed_course";
              $courseKey = "wed_course_name";
              $buildKey = "wed_build";
              $roomKey = "wed_room";
              $teacherKey = "wed_teacher_name";
              $teacherId = "wed_teacher";
              break;
            case '4':
              $courseId = "thu_course";
              $courseKey = "thu_course_name";
              $buildKey = "thu_build";
              $roomKey = "thu_room";
              $teacherKey = "thu_teacher_name";
              $teacherId = "thu_teacher";
              break;
            case '5':
              $courseId = "fri_course";
              $courseKey = "fri_course_name";
              $buildKey = "fri_build";
              $roomKey = "fri_room";
              $teacherKey = "fri_teacher_name";
              $teacherId = "fri_teacher";
              break;
            case '6':
              $courseKey = "";
              $buildKey = "";
              $roomKey = "";
              break;
          }
          $obj = [];
          $obj[$courseKey] = $row1['course'];
          $obj[$buildKey] = $row1['build'];
          $obj[$roomKey] = $row1['room'];
          $obj[$teacherId] = $row1['teacher'];
          $obj[$teacherKey] = $row1['teacher'];
          $obj['session'] = $row1['session'];
          $obj['department'] = $row1['department'];
          array_push($res,$obj);
        }
      }

      foreach($res as $key => $row){
        

        $MonCourseId = $row['mon_course'];
        $TueCourseId = $row['tue_course'];
        $WedCourseId = $row['wed_course'];
        $ThuCourseId = $row['thu_course'];
        $FriCourseId = $row['fri_course'];
        
        $MonBuild = $row['mon_build'];
        $TueBuild = $row['tue_build'];
        $WedBuild = $row['wed_build'];
        $ThuBuild = $row['thu_build'];
        $FriBuild = $row['fri_build'];

        $MonRoom = $row['mon_room'];
        $TueRoom = $row['tue_room'];
        $WedRoom = $row['wed_room'];
        $ThuRoom = $row['thu_room'];
        $FriRoom = $row['fri_room'];

        if($row[$courseKey] != '' || $row[$courseKey] != null){
          $res[$key][$courseKey] = $conn->query("SELECT course_name FROM base_course WHERE course_id = '$row[$courseKey]'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        }else{
          $res[$key]['mon_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['tue_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['wed_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['thu_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
          $res[$key]['fri_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        }
        
        if($row[$teacherKey] != '' || $row[$teacherKey] != null){
          $teacherInfo = $conn->query("SELECT userid,username FROM teacher_info WHERE userid = '$row[$teacherKey]'")->fetchAll(PDO::FETCH_ASSOC)[0];
          $res[$key][$teacherKey] = $teacherInfo['username'];
          // $res[$key][$teacherId] = $teacherInfo['userid'];
        }else{
          $res[$key]['mon_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['tue_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['wed_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['thu_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
          $res[$key]['fri_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        }
        
        if($row[$teacherId] == '' || $row[$teacherId] == null){
          $res[$key]['mon_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
          $res[$key]['tue_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
          $res[$key]['wed_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
          $res[$key]['thu_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
          $res[$key]['fri_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        }
        

        $res[$key]['mon_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$MonBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['tue_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$TueBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['wed_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$WedBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['thu_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$ThuBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['fri_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$FriBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
       
        $res[$key]['mon_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$MonRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['tue_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$TueRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['wed_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$WedRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['thu_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$ThuRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['fri_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$FriRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
      }

      // $res = count($res1);

    }
    else if($_POST['type'] == 'set_schedule'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = [];
      for($i=0;$i<count($arr);$i++){
        $obj = $arr[$i];
        $id = $obj['id'];
        if(count($conn->select("id","id",$id)) > 0){
          $row = $conn->update($obj,'id',$id);
        }else{
          $arr2 = objarray_to_array($obj);
          array_push($arr2,(time()."000"));
          $row = $conn->insert($arr2);
          // array_push($res,$arr2);  
        }
        array_push($res,$row);
      };
    }
    else if($_POST['type'] == 'del_schedule'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }

    echo json_encode($res);
  }




?>