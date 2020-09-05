<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('commonly_homework');
  $conn2 = new Link("commonly_homework_register");
  $conn3 = new Link("tb_message");

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


  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){

    if($type == 'sel_limit_homework'){

      // $class = $_GET['class'] ? $_GET['class'] : null;
      $col = $_GET['col'] ? $_GET['col'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;


      if($seltype == 'day'){
        $date = $_GET['date'] ? $_GET['date'] : null;
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $obj = [
            'class'=>$class,
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $obj = [
            'department'=>$department,
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else{
          $obj = [
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
      }

      else if($seltype == 'month'){
        $month_start = $_GET['date_start'] ? $_GET['date_start'] : 0;
        $month_end = $_GET['date_end'] ? $_GET['date_end'] : 2592000000;
        
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE class = '$class' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE class = '$class' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE department = '$department' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE department = '$department' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
        else{
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
       
      }
      
      else if($seltype == 'semester'){

        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $obj = [
            'class'=>$class,
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $obj = [
            'department'=>$department,
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
          // $res = '123';
        }
        else{
          $obj = [
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        
      }
        

      foreach($res1 as $key => $row){
        $course = $row['course'];
        $campus = $row['campus'];
        $teacher = $row['created_user'];
        $class = $row['class'];
        $res1[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course 
        WHERE course_id = '$course' 
        AND course_campus = '$campus' 
        AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res1[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class 
        WHERE class_id = '$class'
        AND class_campus = '$campus'
        AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res1[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info 
        WHERE userid = '$teacher' 
        AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

      };

      $res = [$res1,$res2];
    
    }
    else if($type == 'sel_limit_homework_register'){

      $col = $_GET['col'] ? $_GET['col'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $seltype = $_GET['seltype'] ? $_GET['seltype'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $semester = $_GET['semester'] ? $_GET['semester'] : null;


      if($seltype == 'day'){
        $date = $_GET['date'] ? $_GET['date'] : null;
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $obj = [
            'class'=>$class,
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $obj = [
            'department'=>$department,
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else{
          $obj = [
            'campus'=>$campus,
            'semester'=>$semester,
            'end_date'=>$date,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
      }

      else if($seltype == 'month'){
        $month_start = $_GET['date_start'] ? $_GET['date_start'] : 0;
        $month_end = $_GET['date_end'] ? $_GET['date_end'] : 2592000000;
        
        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE class = '$class' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE class = '$class' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE department = '$department' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE department = '$department' 
            AND semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
        else{
          $res1 = $conn->query("SELECT $col FROM commonly_homework 
            WHERE semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end 
            LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
          $res2 = $conn->query("SELECT COUNT(*) FROM commonly_homework 
            WHERE semester = '$semester' 
            AND campus = '$campus' 
            AND end_date >= $month_start 
            AND end_date <= $month_end")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        }
       
      }
      
      else if($seltype == 'semester'){

        if(isset($_GET['class'])){
          $class = $_GET['class'];
          $obj = [
            'class'=>$class,
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        else if(isset($_GET['department'])){
          $department = $_GET['department'];
          $obj = [
            'department'=>$department,
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
          // $res = '123';
        }
        else{
          $obj = [
            'campus'=>$campus,
            'semester'=>$semester,
          ];
          $res1 = $conn->limit_more($col,$start,$num,$obj);
          $res2 = $conn->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        }
        
      }
        

      foreach($res1 as $key => $row){
        $homework = $row['id'];
        $course = $row['course'];
        $campus = $row['campus'];
        $teacher = $row['created_user'];
        $student = $row['student'];
        $class = $row['class'];

        $res1[$key]['student_name'] = $conn->query("SELECT username FROM student_info 
          WHERE class = '$class' 
          AND campus = '$campus' 
          AND userid = '$student' 
          AND job = 'S1' 
          AND type = 'T1'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

        $res1[$key]['student_num'] = $conn->query("SELECT COUNT(*) FROM student_info 
          WHERE class = '$class' 
          AND campus = '$campus' 
          AND job = 'S1' 
          AND type = 'T1'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        
        $obj1 = [
          "homework"=>$homework,
          'state'=>'1',
          'class'=>$class,
          'campus'=>$campus,
        ];
        $obj2 = [
          "homework"=>$homework,
          'state'=>'2',
          'class'=>$class,
          'campus'=>$campus,
        ];
        $obj3 = [
          "homework"=>$homework,
          'state'=>'3',
          'class'=>$class,
          'campus'=>$campus,
        ];
        $res1[$key]["register_num"] = $conn2->select_more("COUNT(*)",$obj1)[0]['COUNT(*)'];
        $res1[$key]["noregister_num"] = $conn2->select_more("COUNT(*)",$obj2)[0]['COUNT(*)'];
        $res1[$key]["report_num"] = $conn2->select_more("COUNT(*)",$obj3)[0]['COUNT(*)'];

        $res1[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course 
        WHERE course_id = '$course' 
        AND course_campus = '$campus' 
        AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];

        $res1[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class 
        WHERE class_id = '$class'
        AND class_campus = '$campus'
        AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];

        $res1[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info 
        WHERE userid = '$teacher' 
        AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

      };

      $res = [$res1,$res2];
    
    }
    else if($type == 'sel_limit_homework_student'){
      
      $col = $_GET['col'] ? $_GET['col'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res1 = $conn2->limit_more($col,$start,$num,$selobj);
      $res2 = $conn2->select_more("COUNT(*)",$selobj);

      foreach($res1 as $key => $row){
        $homework = $row['id'];
        $course = $row['course'];
        $campus = $row['campus'];
        $teacher = $row['created_user'];
        $student = $row['student'];
        $class = $row['class'];
        $department = $row['department'];

        $semester = $row['semester'];

        $res1[$key]['student_name'] = $conn->query("SELECT username FROM student_info 
          WHERE class = '$class' 
          AND campus = '$campus' 
          AND userid = '$student' 
          AND job = 'S1' 
          AND type = 'T1'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

        $res1[$key]['student_num'] = $conn->query("SELECT COUNT(*) FROM student_info 
          WHERE class = '$class' 
          AND campus = '$campus' 
          AND job = 'S1' 
          AND type = 'T1'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        
        $obj1 = [
          'student'=>$student,
          'state'=>'1',
          'class'=>$class,
          'campus'=>$campus,
        ];
        $obj2 = [
          'student'=>$student,
          'state'=>'2',
          'class'=>$class,
          'campus'=>$campus,
        ];
        $obj3 = [
          'student'=>$student,
          'state'=>'3',
          'class'=>$class,
          'campus'=>$campus,
        ];

        $obj4 = [
          'class'=>$class,
          'campus'=>$campus,
          'department'=>$department
        ];

        $res1[$key]["register_num"] = $conn2->select_more("COUNT(*)",$obj1)[0]['COUNT(*)'];
        $res1[$key]["noregister_num"] = $conn2->select_more("COUNT(*)",$obj2)[0]['COUNT(*)'];
        $res1[$key]["report_num"] = $conn2->select_more("COUNT(*)",$obj3)[0]['COUNT(*)'];
        $res1[$key]['homework_num'] = $conn->select_more("COUNT(*)",$obj4)[0]['COUNT(*)'];
        
        $res1[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course 
        WHERE course_id = '$course' 
        AND course_campus = '$campus' 
        AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];

        $res1[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class 
        WHERE class_id = '$class'
        AND class_campus = '$campus'
        AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];

        $res1[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info 
        WHERE userid = '$teacher' 
        AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

      };

      $res = [$res1,$res2];
    }
    else if($type == 'sel_limit_homework_teacher'){
      
      $col = $_GET['col'] ? $_GET['col'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res1 = $conn->limit_more($col,$start,$num,$selobj);
      $res2 = $conn->select_more("COUNT(*)",$selobj)[0]['COUNT(*)'];

      
      foreach($res1 as $key => $row){
        $course = $row['course'];
        $campus = $row['campus'];
        $teacher = $row['created_user'];
        $class = $row['class'];
        $res1[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course 
        WHERE course_id = '$course' 
        AND course_campus = '$campus' 
        AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
        $res1[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class 
        WHERE class_id = '$class'
        AND class_campus = '$campus'
        AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res1[$key]['teacher_name'] = $conn->query("SELECT username FROM teacher_info 
        WHERE userid = '$teacher' 
        AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

      };

      $res = [$res1,$res2];
    }
    else if($type == 'sel_homework'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn->select_more($col,$selobj);
      // $res = 123;
    }
    else if($type == 'sel_homework_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn->select_more($col,$selobj);

      foreach($res as $key => $row){
        $course = $row['course'];
        $campus = $row['campus'];
        $res[$key]['course_name'] = $conn->query("SELECT course_name FROM base_course 
        WHERE course_id = '$course' 
        AND course_campus = '$campus' 
        AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_name'];
      };

    }
    else if($type == 'sel_homework_register'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;

      $res = $conn2->select_more($col,$selobj);

      foreach($res as $key => $row){
        $student = $row['student'];
        $campus = $row['campus'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info 
        WHERE campus = '$campus' 
        AND userid = '$student' 
        AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
      };

    }
    else if($type == 'add_homework'){

      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $arr = objarray_to_array($obj);
      $res = $conn->insert($arr);
      if($res){
        $hw = $conn->select_more("id",$obj)[0]['id'];
        $arr = [
          $hw,
          "homework",
          $obj['campus'],
          $obj['school'],
          $obj['created_user'],
          $obj['addTime']
        ];
        $conn3->insert($arr);
      }
      // $res = $arr;
      
    }
    else if($type == 'register_homework'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $school = $_POST['school'] ? $_POST['school'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $homework = $_POST['homework'] ? $_POST['homework'] : null;
      $time = time().'000';
      
      if(!empty($arr)){
        $obj1 = [
          "campus"=>$campus,
          "class"=>$class,
          "semester"=>$semester,
          "department"=>$department,
        ];


          $res1 = $conn2->select_more("COUNT(*)",$obj1)[0]['COUNT(*)'];
          if($res1 > 0){
            $res = [true,false];
          }else{

            $newarr = [];
            for($i=0;$i<count($arr);$i++){
              $row = $arr[$i];
              $student = $row['userid'];
              $state = $row['state'];

              $arr1 = [
                $homework,
                $student,
                $state,
                $semester,
                $class,
                $department,
                $campus,
                $school,
                $user,
                $time
              ];
              $res2 = $conn2->insert($arr1);
              // array_push($newarr,$arr1);
              array_push($newarr,$res2);
            }
            $res = [false,$newarr];

          }
      }
      
    }
    else if($type == 'add_register_homework'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $school = $_POST['school'] ? $_POST['school'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $class = $_POST['class'] ? $_POST['class'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $homework = $_POST['homework'] ? $_POST['homework'] : null;
      $time = time().'000';
    
      $newarr = [];
      for($i=0;$i<count($arr);$i++){

        $row = $arr[$i];
        $student = $row['userid'];
        $state = $row['state'];

        $obj = [
          'homework'=>$homework,
          'student'=>$student,
          'semester'=>$semester,
          "class"=>$class,
          'campus'=>$campus,
          'department'=>$department,
        ];

        $res1 = $conn2->select_more("COUNT(*)",$obj)[0]['COUNT(*)'];
        if($res1 > 0){
          $res2 = $conn2->prepare("UPDATE commonly_homework_register SET state = :state 
            WHERE student = :student 
            AND class = :class 
            AND semester = :semester 
            AND homework = :homework 
            AND campus = :campus 
            AND department = :department
          ");
          $res2->bindParam(":state",$state);
          $res2->bindParam(":class",$class);
          $res2->bindParam(":semester",$semester);
          $res2->bindParam(":homework",$homework);
          $res2->bindParam(":campus",$campus);
          $res2->bindParam(":department",$department);
          $res2->bindParam(":student",$student);
          $res2 = $res2->execute();
        }else{
          $arr1 = [
            $homework,
            $student,
            $state,
            $semester,
            $class,
            $department,
            $campus,
            $school,
            $user,
            $time
          ];
          $res2 = $conn2->insert($arr1);
        }
        // array_push($newarr,$arr1);
        array_push($newarr,$res2);
      }
      $res = $newarr;

      
    }
    else if($type == 'del_homework'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      
      if(count($arr) > 0){
        $res = $conn->delete_more('id',$arr);
        if($res){
          for($i=0;$i<count($arr);$i++){
            $id = $arr[$i];
            $res1 = $conn->prepare("DELETE FROM tb_message 
              WHERE message_id = :id 
              AND type = 'homework' 
              AND campus = :campus");
            $res1->bindParam(":id",$id);
            $res1->bindParam(":campus",$campus);
            $res1->execute();
          }
        }
      }
    }
    else if($type == 'upa_homework'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    echo json_encode($res);
  }




?>