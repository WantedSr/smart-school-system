<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_class_schedule');

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
    if($_GET['type'] == 'sel_schedule'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      // $semester = $_GET['semester'] ? $_GET['semester'] : null;
      // $class = $_GET['class'] ? $_GET['class'] : null;
      // $res = $conn->query("SELECT $col FROM teach_class_schedule WHERE class = '$class' AND semester = '$semester'")->fetchAll(PDO::FETCH_ASSOC);
      $res = $conn->select_more($col,$selobj);
    }
    else if($_GET['type'] == 'sel_schedule_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      // $semester = $_GET['semester'] ? $_GET['semester'] : null;
      // $class = $_GET['class'] ? $_GET['class'] : null;
      // $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn->select_more($col,$selobj);

      foreach($res as $key => $row){
        
        $session = $row['session'];

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

        $res[$key]['mon_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['tue_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['wed_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['thu_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['fri_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        
        $res[$key]['mon_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['tue_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['wed_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['thu_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['fri_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        
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
      // $class = $_GET['class'] ? $_GET['class'] : null;
      $teacher = $_GET['teacher'] ? $_GET['teacher'] : null;
      // $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

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

      foreach($res as $key => $row){
        
        $session = $row['session'];

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

        $res[$key]['mon_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['tue_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['wed_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['thu_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['fri_teacher_name'] = $conn->query("SELECT username FROM teacher_info WHERE userid in (SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

        $res[$key]['mon_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$MonCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        $res[$key]['tue_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$TueCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        $res[$key]['wed_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$WedCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        $res[$key]['thu_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$ThuCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        $res[$key]['fri_teacher'] = $conn->query("SELECT teacher FROM teach_teacher_schedule WHERE id = '$FriCourseId'")->fetchAll(PDO::FETCH_ASSOC)[0]['teacher'];
        
        $res[$key]['mon_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$MonCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['tue_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$TueCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['wed_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$WedCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['thu_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$ThuCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res[$key]['wed_course_name'] = $conn->query("SELECT course_name FROM base_course WHERE course_id in (SELECT course FROM teach_teacher_schedule WHERE id = '$FriCourseId')")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        
        $res[$key]['mon_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$MonBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['tue_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$TueBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['wed_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$WedBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['thu_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$ThuBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
        $res[$key]['wed_build_name'] = $conn->query("SELECT build_name FROM base_build WHERE build_id = '$FriBuild'")->fetchAll(PDO::FETCH_ASSOC)[0]['build_name'];
       
        $res[$key]['mon_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$MonRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['tue_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$TueRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['wed_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$WedRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['thu_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$ThuRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
        $res[$key]['wed_room_name'] = $conn->query("SELECT room_name FROM base_room WHERE room_id = '$FriRoom'")->fetchAll(PDO::FETCH_ASSOC)[0]['room_name'];
      }

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