<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");
  require_once("./department.php");
  require_once("./campus.php");

  function getProfession($col="*",$campus=null,$dep=null){
    $conn = new Link('base_profession');
    if($campus != 'all'){
      if($dep != 'all'){
        $res = $conn->query("SELECT * FROM base_profession WHERE skill_campus = '$campus' AND skill_department = '$dep'")->fetchAll(PDO::FETCH_ASSOC);
      }
      else{
        $res = $conn->query("SELECT * FROM base_profession WHERE skill_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC);
      }
    }else{
      if($dep != 'all'){
        $res = $conn->query("SELECT * FROM base_profession WHERE skill_department = '$dep'")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $res = $conn->select();
      }
    };
    return $res;
  }
  function getOriProfession($col="*",$sel=null,$val=null){
    $conn = new Link('base_profession');
    $res = $conn->select($col,$sel,$val);
    return $res;
  }
  function addProfession($arr=null){
    $conn = new Link('base_profession');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaProfession($obj=null,$sel=null,$val=null){
    $conn = new Link('base_profession');
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delProfession($arr=null){
    $conn = new Link('base_profession');
    if(count($arr)){
      $res = $conn->delete_more('id',$arr);
      echo $res;
    }
  }


  if(isset($_GET['type'])){
    if($_GET['type'] == 'sel_limit_profession'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $department = $_GET['department'] ? $_GET['department'] : null;
      $res = getProfession($col,$campus,$department);
      foreach($res as $key => $row){
        $department = $row['skill_department'];
        $campus = $row['skill_campus'];
        $department = getDepartment("*","department_id",$department)[0]['department_name'];
        $campus = getCampus("*","campus_id",$campus)[0]['campus_name'];
        $res[$key]['skill_department'] = $department;
        $res[$key]['skill_campus'] = $campus;
      };
      echo json_encode($res);
    }
    else if($_GET['type'] == 'sel_profession'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = getOriProfession($col,$sel,$val);
      echo json_encode($res);
    }
    else if($_GET['type'] == 'add_profession'){
      $arr = $_GET['arr'] ? $_GET['arr'] : null;
      $res = addProfession($arr);
      echo $res;
    }
    else if($_GET['type'] == 'upa_profession'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaProfession($obj,$sel,$val);
      echo $res;
    }
    else if($_GET['type'] == 'del_profession'){
      $arr = $_GET['arr'] ? $_GET['arr'] : [];
      $res = delProfession($arr);
      echo $res;
    }
  }




?>