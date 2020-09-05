<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./campus.php");
  require_once("./department.php");
  require_once("./profession.php");
  require_once("./courseCredit.php");

  function getLimitCourse($col="*",$start,$num,$sel=null,$val=null){
    $conn = new Link('base_course');
    $res = $conn->query("SELECT $col FROM base_course WHERE $sel = '$val' AND state = '1' ORDER BY course_department ASC LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  function getCourse($col="*",$sel=null,$val=null){
    $conn = new Link('base_Course');
    $res = $conn->query("SELECT $col FROM base_course WHERE $sel = '$val' AND state = '1' ORDER BY course_department ASC ")->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  function addCourse($arr=null){
    $conn = new Link('base_course');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaCourse($obj=null,$sel=null,$val=null){
    $conn = new Link('base_course');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delCourse($arr=null){
    $conn = new Link('base_course');
    if(count($arr)){
      $obj = [
        "state"=>'0',
      ];
      $res = $conn->update_more($obj,'id',$arr);
      return $res;
    }
  }

  function getCourseId($profession){
    $conn = new Link('base_course');
    if($profession != null){
      $sql = "SELECT * FROM base_course WHERE course_profession = '$profession' ORDER BY id DESC LIMIT 0,1";
      $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['course_id'];
      $arr = explode('_',$res);
      return $arr[count($arr)-1];
    }else{
      return 0;
    }
  }


  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_course'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $res = getLimitCourse($col,$start,$num,$sel,$val);
      foreach($res as $key => $row){
        $cam = $row['course_campus'];
        $dep = $row['course_department'];
        $pro = $row['course_profession'];
        $courseType = $row['course_type'];
        $cam = getCampus("*","campus_id",$cam)[0]['campus_name'];
        $dep = getDepartment("*","department_id",$dep)[0]['department_name'];
        $pro = getOriProfession("*","skill_id",$pro)[0]['skill_name'];
        $courseType = getCourseType("*",["course_id"=>$courseType])[0]['course_name'];
        $res[$key]['course_campus'] = $cam;
        $res[$key]['course_department'] = $dep;
        $res[$key]['course_profession'] = $pro;
        $res[$key]['course_type'] = $courseType;
      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_course'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getCourse($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_course'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addCourse($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_course'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaCourse($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_course'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delCourse($arr);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'get_course_id'){
      $profession = $_GET['profession'] ? $_GET['profession'] : null;
      $res = getCourseId($profession);
      echo $res;
    }
    else if($_POST['type'] == 'imp_course'){
      $conn = new Link("base_course");
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){

          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";
          
          $school = $row['course_school'];
          // $profession = $row['course_profession'];
          
          $arr[$i]['course_campus'] = $row['course_campus'] = $conn->query("SELECT * FROM base_campus WHERE campus_name = '".$row['course_campus']."' AND campus_school = '$school'")->fetchAll(PDO::FETCH_ASSOC)[0]['campus_id'];
          $campus = $arr[$i]['course_campus'];
          $arr[$i]['course_department'] = $row['course_department'] = $conn->query("SELECT * FROM base_department WHERE department_name = '".$row['course_department']."' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $department = $row['course_department'];
          $arr[$i]['course_profession'] = $row['course_profession'] = $conn->query("SELECT * FROM base_profession WHERE skill_name = '".$row['course_profession']."' AND skill_department = '$department' AND skill_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_id'];
          $arr[$i]['course_type'] = $row['course_type'] = $conn->query("SELECT * FROM base_course_type WHERE course_name = '".$row['course_type']."' AND campus = '$campus' AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['course_id'];
          
          $profession = $arr[$i]['course_profession'];
          
          $courseId = getCourseId($profession);

          
          if($courseId == '0' || $courseId == null || $courseId == ''){
            $courseId = $profession."_C001";
          }else{
            $num = (int)(substr($courseId,1,3)) + 1;
            if($num < 10){
              $num = '00'.$num;
            }else if($num < 100){
              $num = '0'.$num;
            }
            $courseId = $profession."_C".$num;
          }
          

          $arr[$i]['course_id'] = $courseId;

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");

          $sql = "INSERT INTO base_course($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();
          array_push($newarr,$res);
          // array_push($newarr,$arr[$i]); 
        }
        echo json_encode($newarr);
      }
    }
  }




?>