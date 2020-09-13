<?php

  
  header('Access-Control-Allow-Origin: *');
  require_once("../../conn.php");

  $conn = new Link("student_info");
  $conn2 = new Link('tb_login');

  function selLimitStu($col,$start,$num,$dep,$skill,$grade,$class,$campus){
    $conn = new Link("student_info");
    if($class != 'all'){
      $res = $conn->limit($col,$start,$num,'class',$class);
      $num = $conn->select("COUNT(*)",'class',$class);
    }else{
      if($skill != 'all'){
        $res = $grade != 'all' ? $conn->limit_more($col,$start,$num,[
          "skill"=>$skill,
          "grade"=>$grade,
        ]) : $conn->limit($col,$start,$num,"skill",$skill);
        $num = $grade != 'all' ? $conn->select_more("COUNT(*)",[
          "skill"=>$skill,
          "grade"=>$grade,
        ]) : $conn->select("COUNT(*)","skill",$skill);
      }else{
        if($dep != 'all'){
          $res = $grade != 'all' ? $conn->limit_more($col,$start,$num,[
            'department'=>$dep,
            'grade'=>$grade,
          ]) : $conn->limit($col,$start,$num,"department",$dep);
          $num = $grade != 'all' ? $conn->select_more("COUNT(*)",[
            'department'=>$dep,
            'grade'=>$grade,
          ]) : $conn->select("COUNT(*)","department",$dep);
        }else{
          $res = $grade != 'all' ? $conn->limit_more($col,$start,$num,[
            'campus'=>$campus,
            'grade'=>$grade,
          ]) : $conn->limit($col,$start,$num,"campus",$campus);
          $num = $grade != 'all' ? $conn->select_more("COUNT(*)",[
            'campus'=>$campus,
            'grade'=>$grade,
          ]) : $conn->select("COUNT(*)","campus",$campus);
        }
      }
    }

    foreach($res as $key => $row){
      $c = $row['class'];
      $d = $row['department'];
      $s = $row['skill'];
      $u = $row['created_user'];
      $t = $row['type'];
      $res[$key]['class'] = $conn->query("SELECT * FROM base_class WHERE class_id = '$c'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
      $res[$key]['department'] = $conn->query("SELECT * FROM base_department WHERE department_id = '$d'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
      $res[$key]['skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_id = '$s'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_name'];
      $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      $res[$key]['type'] = $conn->query("SELECT * FROM student_school_status WHERE status_id = '$t'")->fetchAll(PDO::FETCH_ASSOC)[0]['status_name'];
    }

    $num = $num[0]["COUNT(*)"];
    $arr = [$res,$num];

    return $arr;
  }
  function selStuName($col,$dep,$skill,$grade,$class,$campus){
    $conn = new Link("student_info");
    if($class != 'all'){
      $res = $conn->select_more($col,[
        'class'=>$class,
        'campus'=>$campus,
      ]);
    }else{
      if($skill != 'all'){
        $res = $grade != 'all' ? $conn->select_more($col,[
          "skill"=>$skill,
          "grade"=>$grade,
          "campus"=>$campus,
        ]) : $conn->select_more($col,[
          "skill"=>$skill,
          "campus"=>$campus,
        ]);
      }else{
        if($dep != 'all'){
          $res = $grade != 'all' ? $conn->select_more($col,[
            'department'=>$dep,
            'grade'=>$grade,
            "campus"=>$campus,
          ]) : $conn->select_more($col,[
            "department",$dep,
            "campus"=>$campus,
          ]);
        }else{
          $res = $grade != 'all' ? $conn->select_more($col,[
            'campus'=>$campus,
            'grade'=>$grade,
          ]) : $conn->select_more($col,["campus"=>$campus]);
        }
      }
    }

    foreach($res as $key => $row){
      $c = $row['class'];
      $d = $row['department'];
      $s = $row['skill'];
      $u = $row['created_user'];
      $t = $row['type'];
      $res[$key]['class'] = $conn->query("SELECT * FROM base_class WHERE class_id = '$c'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
      $res[$key]['department'] = $conn->query("SELECT * FROM base_department WHERE department_id = '$d'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
      $res[$key]['skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_id = '$s'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_name'];
      $res[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      $res[$key]['type'] = $conn->query("SELECT * FROM student_school_status WHERE status_id = '$t'")->fetchAll(PDO::FETCH_ASSOC)[0]['status_name'];
    }

    return $res;
  }
  function selStu($col,$selobj) {
    $conn = new Link("student_info");
    $res = $conn->select_more($col,$selobj);
    return $res;
  }
  function addStu($arr){
    $conn = new Link('student_info');
    $res = $conn->insert($arr);
    return $res;
  }
  function upaStu($obj,$sel,$val){
    $conn = new Link("student_info");
    $res = $conn->update($obj,$sel,$val);
    return $res;
  }
  function delStu(){

  }

  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }

  if(!empty($type)){
    if($type == 'sel_limit_stu'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $dep = $_GET['department'] ? $_GET['department'] : null;
      $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $grade = $_GET['grade'] ? $_GET['grade'] : null;
      $class = $_GET['class'] ? $_GET['class'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      
      $res = selLimitStu($col,$start,$num,$dep,$skill,$grade,$class,$campus);
    }else if($type == 'sel_stu_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $dep = $_GET['department'] ? $_GET['department'] : null;
      $skill = $_GET['skill'] ? $_GET['skill'] : null;
      $grade = $_GET['grade'] ? $_GET['grade'] : null;
      $class = $_GET['class'] ? $_GET['class'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      
      $res = selStuName($col,$dep,$skill,$grade,$class,$campus);
    }else if($type == 'sel_stu'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = selStu($col,$selobj);
    }else if($type == 'upa_stu'){
      $obj = $_GET['obj'] ? $_GET['obj'] : null;
      $sel = $_GET['sel'] ? $_GET['sel'] : null;
      $val = $_GET['val'] ? $_GET['val'] : null;
      $res = upaStu($obj,$sel,$val);
    }else if($type == 'add_stu'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $arr2 = $_POST['arr2'] ? $_POST['arr2'] : null;
      $arr2[2] = md5(123456);
      $conn2->insert($arr2);
      $res = addStu($arr);
    }else if($type == 'imp_stu'){

      $conn = new Link("student_info");
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $newarr = [];
      if($arr != null){
        for($i=0;$i<count($arr);$i++){

          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";
          
          $campus = $row['campus'];

          $add_province = $row['address_province'];
          $add_city = $row['address_city'];
          $add_area = $row['address_area'];

          $hou_province = $row['house_register_provinces'];
          $hou_city = $row['house_register_city'];
          $hou_area = $row['house_register_area'];

          $class = $row['class'];
          $department = $row['department'];
          $grade = $row['grade'];

          $arr[$i]['address_province'] = $row['address_province'] = $conn->query("SELECT * FROM china_provinces WHERE province = '$add_province'")->fetchAll(PDO::FETCH_ASSOC)[0]['provinceid'];
          $arr[$i]['address_city'] = $row['address_city'] = $conn->query("SELECT * FROM china_cities WHERE city = '$add_city'")->fetchAll(PDO::FETCH_ASSOC)[0]['cityid'];
          $arr[$i]['address_area'] = $row['address_area'] = $conn->query("SELECT * FROM china_areas WHERE area = '$add_area'")->fetchAll(PDO::FETCH_ASSOC)[0]['areaid'];

          $arr[$i]['china_member'] = $row['china_member'] = $row['china_member'] == '是' ? '1' : '0';

          $arr[$i]['class'] = $row['class'] = $conn->query("SELECT * FROM base_class WHERE class_campus = '$campus' AND class_name ='$class' AND state = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_id'];
          $arr[$i]['department'] = $row['department'] = $conn->query("SELECT * FROM base_department WHERE campus = '$campus' AND department_name ='$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
          $arr[$i]['grade'] = $row['grade'] = $conn->query("SELECT * FROM base_grade WHERE campus = '$campus' AND grade_name ='$grade' AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['grade_id'];

          $arr[$i]['house_register_provinces'] = $row['house_register_provinces'] = $conn->query("SELECT * FROM china_provinces WHERE province = '$hou_province'")->fetchAll(PDO::FETCH_ASSOC)[0]['provinceid'];
          $arr[$i]['house_register_city'] = $row['house_register_city'] = $conn->query("SELECT * FROM china_cities WHERE city = '$hou_city'")->fetchAll(PDO::FETCH_ASSOC)[0]['cityid'];
          $arr[$i]['house_register_area'] = $row['house_register_area'] = $conn->query("SELECT * FROM china_areas WHERE area = '$hou_area'")->fetchAll(PDO::FETCH_ASSOC)[0]['areaid'];
          
          $arr[$i]['skill'] = $row['skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_name = '".$row['skill']."' AND skill_department = '".$row['department']."' AND skill_campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_id'];
          

          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");


          $sql = "INSERT INTO student_info($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute();
          if($res){
            $logarr = [
              $row['userid'],
              $row['username'],
              md5(123456),
              $row['phone_me'],
              "S1",
              $row['school'],
              $row['campus'],
              "",
              "",
              $row['created_user'],
              time().'000'
            ];

            $conn2->insert($logarr);
          }
          array_push($newarr,$res);
          // array_push($newarr,$arr[$i]); 
        }
        $res = $newarr;
      }

    }else if($type == 'sel_limit_stu_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res1 = $conn->limit_more($col,$start,$num,$selobj);
      $res2 = $conn->select_more('COUNT(*)',$selobj)[0]['COUNT(*)'];
      foreach($res1 as $key => $row){
        $c = $row['class'];
        $d = $row['department'];
        $s = $row['skill'];
        $u = $row['created_user'];
        $t = $row['type'];
        $res1[$key]['class'] = $conn->query("SELECT * FROM base_class WHERE class_id = '$c'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res1[$key]['department'] = $conn->query("SELECT * FROM base_department WHERE department_id = '$d'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res1[$key]['skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_id = '$s'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_name'];
        $res1[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        $res1[$key]['type'] = $conn->query("SELECT * FROM student_school_status WHERE status_id = '$t'")->fetchAll(PDO::FETCH_ASSOC)[0]['status_name'];
      }
      $res = [$res1,$res2];
    }else if($type == 'sel_stu_name'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : null;
      $num = $_GET['num'] ? $_GET['num'] : null;
      $res1 = $conn->select_more($col,$selobj);
      $res2 = $conn->select_more('COUNT(*)',$selobj)[0]['COUNT(*)'];
      foreach($res1 as $key => $row){
        $c = $row['class'];
        $d = $row['department'];
        $s = $row['skill'];
        $u = $row['created_user'];
        $t = $row['type'];
        $res1[$key]['class'] = $conn->query("SELECT * FROM base_class WHERE class_id = '$c'")->fetchAll(PDO::FETCH_ASSOC)[0]['class_name'];
        $res1[$key]['department'] = $conn->query("SELECT * FROM base_department WHERE department_id = '$d'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_name'];
        $res1[$key]['skill'] = $conn->query("SELECT * FROM base_profession WHERE skill_id = '$s'")->fetchAll(PDO::FETCH_ASSOC)[0]['skill_name'];
        $res1[$key]['created_user'] = $conn->query("SELECT * FROM tb_login WHERE user_id = '$u'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        $res1[$key]['type'] = $conn->query("SELECT * FROM student_school_status WHERE status_id = '$t'")->fetchAll(PDO::FETCH_ASSOC)[0]['status_name'];
      }
      $res = [$res1,$res2];
    }
    echo json_encode($res);
  }





?>