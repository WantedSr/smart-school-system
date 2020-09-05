<?php

  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  $conn = new Link("student_placement");

  $type;
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if(isset($_POST['type'])){
    $type = $_POST['type'];
  }else{
    $type = null;
  }

  if(!empty($type)){
    if($type == 'sel_limit_placement'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $class = $row['class'];
        $student = $row['student'];
        $semester = $row['semester'];
        $department = $row['department'];
        $res[$key]['class_name'] = $conn->query("SELECT class_name FROM base_class WHERE class_id = '$class'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res[$key]['student_name'] = $conn->query("SELECT username FROM student_info WHERE userid = '$student'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
        $res[$key]['semester_name'] = $conn->query("SELECT semester FROM base_semester WHERE semesterId = '$semester'")->fetchAll(PDO::FETCH_ASSOC)[0]['semester'];
        $res[$key]['department_name'] = $conn->query("SELECT department_name FROM base_department WHERE department_id = '$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
      }
    }
    else if($type == 'sel_placement'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_placement'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $student = $arr[0];
      $semester = $arr[2];
      $campus = $arr[4];
      $res1 = $conn->select_more("COUNT(*)",[
        'student'=>$student,
        'semester'=>$semester,
        'campus'=>$campus,
      ])[0]['COUNT(*)'];
      if($res1 > 0){
        $res = [true,false];
      }else{
        $res2 = $conn->insert($arr);
        $res = [false,$res2];
      }
    }
    else if($type == 'upa_placement'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $obj2 = [
        'student'=>$obj['student'],
        'campus'=>$obj['campus'],
        'semester'=>$obj['semester'],
      ];
      $res1 = $conn->select_more("id",$obj2)[0]['id'];
      $res2 = $conn->select_more("COUNT(*)",$obj2)[0]['COUNT(*)'];
      if($res1 != $val){
        if($res2 > 0){
          $res = [true,false];
        }else{
          $res3 = $conn->update($obj,$sel,$val);
          $res = [false,$res3];
        }
      }else{
        $res3 = $conn->update($obj,$sel,$val);
        $res = [false,$res3];
      }
    }
    else if($type == 'del_placement'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'imp_placement'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){
          
          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";

          $campus = $row['campus'];

          $arr[$i]['semester'] = $row['semester'] = $conn->query("SELECT * FROM base_semester 
            WHERE semester = '".$row["semester"]."' 
            AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semesterId'];
          $semester = $row['semester'];

          $arr[$i]['department'] = $row['department'] = $conn->query("SELECT * FROM base_department 
            WHERE campus = '$campus' 
            AND department_name ='".$row['department']."'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $department = $row['department'];

          $arr[$i]['class'] = $row['class'] = $conn->query("SELECT * FROM base_class 
            WHERE class_department = '$department' 
            AND class_name = '".$row['class']."' 
            AND class_campus = '$campus' 
            AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_id'];
          $class = $row['class'];

          $arr[$i]['student'] = $row['student'] = $conn->query("SELECT * FROM student_info 
            WHERE campus = '$campus' 
            AND username = '".$row['student_name']."'
            AND userid = '".$row['student']."' 
            AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0]['userid']; 
          $student = $row['student'];

          unset($arr[$i]['student_name']);
          unset($row['student_name']);

          $obj1 = [
            'campus'=>$campus,
            'student'=>$student,
            'semester'=>$semester,
          ];
          
          $res1 = $conn->select_more("COUNT(*)",$obj1)[0]['COUNT(*)'];
          if($res1 > 0){
            $res = true;
          }else{
            
            foreach($arr[$i] as $key => $value){
              array_push($colArr,$key);
              array_push($pareArr,":".$key);
            }
            
            $newcol = join($colArr,",");
            $value = join($pareArr,",");
    
            $sql = "INSERT INTO student_placement($newcol) VALUES($value)";
            $res = $conn->prepare($sql);
            foreach($arr[$i] as $key => $val){
              $res->bindParam(":".$key,$arr[$i][$key]);
            }
            $res = $res->execute();
            
            if($res){
              $conn->query("UPDATE student_info SET 
                class = '$class'
                WHERE userid = '$student' 
                AND campus = '$campus' 
                AND department = '$department'");
            }
            // array_push($newarr,$row);
          }
          array_push($newarr,$res);

        }
        $res = $newarr;
      }
    }
    echo json_encode($res);
  }





?>