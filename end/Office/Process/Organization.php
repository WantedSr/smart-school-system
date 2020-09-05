<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("oa_organization");
  
  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }


  if(!empty($type)){

    if($type == 'sel_organization'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'sel_limit_organization'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $dep = $row['department'];
        $campus = $row['campus'];
        $res[$key]['department_name'] = $conn->query("SELECT department_name FROM base_department 
          WHERE department_id = '$dep' 
          AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
      };
    }
    else if($type == 'add_organization'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $num = $conn->query("SELECT id FROM oa_organization 
        WHERE campus = '$campus' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
      if(count($num) > 0){
        $num = $num[0]['id'];
        $num = $num >= 0 ? $num + 1 : 1;
      }else{
        $num = 1;
      }
      if($num < 10){
        $num = '000'.$num;
      }
      else if($num < 100){
        $num = '00'.$num;
      }
      else if($num < 1000){
        $num = '000'.$num;
      }
      $arr[0] = $campus.'_G'.$num;
      // $res = $conn->insert($arr);
      $res = $num;
    }
    else if($type == 'upa_organization'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_organization'){
      $sel = $_POST['sel'] ? $_POST['sel'] : "id";
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'sel_teacher_organization'){
      $dep = $_GET['department'] ? $_GET['department'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $id = $_GET['id'] ? $_GET['id'] : null;
      $form = $_GET['form'] ? $_GET['form'] : null;
      if($form == 'sponsor'){
        $setArr = $conn->select_more("sponsor",[
          'campus'=>$campus,
          'department'=>$dep,
          'id'=>$id,
        ])[0]['sponsor'];
      }
      else if($form == 'department'){
        $setArr = $conn->select_more("department_responsible",[
          'campus'=>$campus,
          'department'=>$dep,
          'id'=>$id,
        ])[0]['department_responsible'];
      }
      else if($form == 'course'){
        $setArr = $conn->select_more("course_responsible",[
          'campus'=>$campus,
          'department'=>$dep,
          'id'=>$id,
        ])[0]['course_responsible'];
      }
      $setArr = json_decode($setArr);
      if(!is_array($setArr)){
        $setArr = [];
      }
      $res = $conn->prepare("SELECT userid,username FROM teacher_info 
        WHERE department = :dep 
          AND campus = :campus 
          AND teacher_job like '%T%' 
          AND type = '1'");
      $res->bindParam(":dep",$dep);
      $res->bindParam(":campus",$campus);
      $res->execute();
      $arr = $res->fetchAll(PDO::FETCH_ASSOC);
      $teaArr = [];
      foreach($arr as $key => $row){
        $obj = [
          'key'=>$row['userid'],
          'label'=>$row['username'],
        ];
        array_push($teaArr,$obj);
      }
      $res = [$teaArr,$setArr];
    }
    else if($type == 'set_responsible'){
      $dep = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $id = $_POST['id'] ? $_POST['id'] : null;
      $form = $_POST['form'] ? $_POST['form'] : null;
      $arr = $_POST['arr'] ? $_POST['arr'] : null;

      if($form == 'sponsor'){
        $res = $conn->prepare("UPDATE oa_organization SET sponsor = :arr 
          WHERE department = :dep 
            AND campus = :campus 
            AND id = :id");
        $res->bindParam(":arr",$arr);
        $res->bindParam(":dep",$dep);
        $res->bindParam(":campus",$campus);
        $res->bindParam(":id",$id);
        $res = $res->execute();
      }
      else if($form == 'department'){
        $res = $conn->prepare("UPDATE oa_organization SET department_responsible = :arr 
          WHERE department = :dep 
            AND campus = :campus 
            AND id = :id");
        $res->bindParam(":arr",$arr);
        $res->bindParam(":dep",$dep);
        $res->bindParam(":campus",$campus);
        $res->bindParam(":id",$id);
        $res = $res->execute();
      }
      else if($form == 'course'){
        $res = $conn->prepare("UPDATE oa_organization SET course_responsible = :arr 
          WHERE department = :dep 
            AND campus = :campus 
            AND id = :id");
        $res->bindParam(":arr",$arr);
        $res->bindParam(":dep",$dep);
        $res->bindParam(":campus",$campus);
        $res->bindParam(":id",$id);
        $res = $res->execute();
      }

    }
    
    echo json_encode($res);
  }




?>