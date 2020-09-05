<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./campus.php");
  require_once("./department.php");
  require_once("./profession.php");

  function getLimitClass($col="*",$cam,$dep=null,$pro=null,$grade=null,$start,$num){
    $conn = new Link('base_class');
    if($pro != 'all'){
      if($grade != 'all'){
        $res = $conn->query("SELECT $col FROM base_class WHERE class_grade = '$grade' AND class_skill = '$pro' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $res = $conn->query("SELECT $col FROM base_class WHERE class_skill = '$pro' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
      }
    }else{
      if($dep != 'all'){
        if($grade != 'all'){
          $res = $conn->query("SELECT $col FROM base_class WHERE class_grade = '$grade' AND class_department = '$dep' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $res = $conn->query("SELECT $col FROM base_class WHERE class_department = '$dep' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        }
      }else{
        if($grade != 'all'){
          $res = $conn->query("SELECT $col FROM base_class WHERE class_campus = '$cam' AND class_grade = '$grade' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
          $res = $conn->query("SELECT $col FROM base_class WHERE class_campus = '$cam' AND status = '1' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        }
      }
    }
    return $res;
  }
  function getClass($col="*",$selobj){
    $conn = new Link('base_class');
    $selobj['status'] = '1';
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addClass($arr=null){
    $conn = new Link('base_class');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaClass($obj=null,$sel=null,$val=null){
    $conn = new Link('base_class');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delClass($arr=null){
    $conn = new Link('base_class');
    $obj = [
      'status'=>'0',
    ];
    if(count($arr)){
      $res = $conn->update_more($obj,'id',$arr);
      return $res;
    }
  }

  function getClassId($profession=null,$grade=null,$campus=null){
    $conn = new Link('base_class');
    if($profession != null){
      $getIdSql = "SELECT * FROM base_class WHERE class_skill = '$profession' AND class_grade = '$grade' AND class_campus = '$campus' ORDER BY id DESC LIMIT 0,1";
      $getres = $conn->query($getIdSql)->fetchAll(PDO::FETCH_ASSOC)[0]['class_id'];
      $arrid = explode('_',$getres);
      return $arrid[count($arrid)-1];
    }else{
      return 0;
    }
  }

  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_class'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;
      $profession = $_GET['profession'] ? $_GET['profession'] : null;
      $grade = $_GET['grade'] ? $_GET['grade'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = getLimitClass($col,$campus,$department,$profession,$grade,$start,$num);
      foreach($res as $key => $row){
        $cam = $row['class_campus'];
        $dep = $row['class_department'];
        $pro = $row['class_skill'];
        $cam = getCampus("*","campus_id",$cam)[0]['campus_name'];
        $dep = getDepartment("*","department_id",$dep)[0]['department_name'];
        $pro = getOriProfession("*","skill_id",$pro)[0]['skill_name'];
        $res[$key]['class_campus'] = $cam;
        $res[$key]['class_department'] = $dep;
        $res[$key]['class_skill'] = $pro;
      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_class'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = getClass($col,$selobj);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_class'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addClass($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_class'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaClass($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_class'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delClass($arr);
      echo $res;
    }
    else if($_GET['type'] == 'get_class_id'){
      $profession = $_GET['profession'] ? $_GET['profession'] : null;
      $grade = $_GET['grade'] ? $_GET['grade'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      echo getClassId($profession,$grade,$campus);

    }else if($_POST['type'] == 'imp_class'){
      $conn = new Link("base_class");
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){

          $row = $arr[$i];
          $colArr = [];
          // $valArr = [];
          $pareArr = [];
          $newcol = "";
          
          $campus = $row['class_campus'];
          $grade = $row['class_grade'];
          $department = $row['class_department'];
          
          $arr[$i]['class_department'] = $row['class_department'] = $conn->query("SELECT * FROM base_department WHERE department_name = '$department' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $arr[$i]['class_skill'] = $row['class_skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_name = '".$row['class_skill']."' AND skill_department = '".$row['class_department']."' AND skill_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_id'];
          $arr[$i]['class_grade'] = $row['class_grade'] = $conn->query("SELECT * FROM base_grade WHERE grade_name = '$grade' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['grade_id'];
          
          $profession = $arr[$i]['class_skill'];
          $grade = $arr[$i]['class_grade'];
          
          $classId = getClassId($profession,$grade,$campus);
          
          if($classId == 0 || $classId == null || $classId == ''){
            $grade = substr($grade,2,2);
            $classId = $profession."_".$grade."01";
          }else{
            $id = (int)(substr($classId,2,2)) + 1;
            $id = $id < 10 ? '0'.$id : $id;
            $grade = substr($grade,2,2);
            $classId = $profession."_".$grade.$id;
          }

          $arr[$i]['class_id'] = $classId;

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }

          // array_push($colArr,'class_id');
          // array_push($pareArr, ":class_id");
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");

          $sql = "INSERT INTO base_class($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();
          array_push($newarr,$res);
        }
        
        // $valsql = join($newarr,",");
        // $sql = "INSERT INTO base_class ($newcol) VALUES $valsql";
        // $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode($res);
        echo json_encode($newarr);
      }
    }
  }




?>