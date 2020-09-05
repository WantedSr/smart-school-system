<?php

    header('Access-Control-Allow-Origin: *');

    require_once("../../conn.php");  

    $conn = new Link("teacher_info");
    $conn2 = new Link('tb_login');
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

        $job = $row['teacher_job'];

        $arr[$i]['address_province'] = $row['address_province'] = $conn->query("SELECT * FROM china_provinces WHERE province = '$add_province'")->fetchAll(PDO::FETCH_ASSOC)[0]['provinceid'];
        $arr[$i]['address_city'] = $row['address_city'] = $conn->query("SELECT * FROM china_cities WHERE city = '$add_city'")->fetchAll(PDO::FETCH_ASSOC)[0]['cityid'];
        $arr[$i]['address_area'] = $row['address_area'] = $conn->query("SELECT * FROM china_areas WHERE area = '$add_area'")->fetchAll(PDO::FETCH_ASSOC)[0]['areaid'];

        $arr[$i]['department'] = $row['department'] = $conn->query("SELECT * FROM base_department WHERE campus = '$campus' AND department_name ='$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
        $arr[$i]['department'] = $row['department'] = $row['department'] == '' ? '0' : $row['department'];
        $arr[$i]['teacher_job'] = $row['teacher_job'] = $conn->query("SELECT * FROM system_authority WHERE campus = '$campus' AND authority_name ='$job' AND status = '1'")->fetchAll(PDO::FETCH_ASSOC)[0]['authority_id'];

        $arr[$i]['house_register_provinces'] = $row['house_register_provinces'] = $conn->query("SELECT * FROM china_provinces WHERE province = '$hou_province'")->fetchAll(PDO::FETCH_ASSOC)[0]['provinceid'];
        $arr[$i]['house_register_city'] = $row['house_register_city'] = $conn->query("SELECT * FROM china_cities WHERE city = '$hou_city'")->fetchAll(PDO::FETCH_ASSOC)[0]['cityid'];
        $arr[$i]['house_register_area'] = $row['house_register_area'] = $conn->query("SELECT * FROM china_areas WHERE area = '$hou_area'")->fetchAll(PDO::FETCH_ASSOC)[0]['areaid'];
        
        

        foreach($arr[$i] as $key => $value){
          array_push($colArr,$key);
          array_push($pareArr,":".$key);
        }
        
        $newcol = join($colArr,",");
        $value = join($pareArr,",");


        $sql = "INSERT INTO teacher_info($newcol) VALUES($value)";
        $res = $conn->prepare($sql);
        foreach($arr[$i] as $key => $val){
          $res->bindParam(":".$key,$arr[$i][$key]);
        }
        $res = $res->execute();
        // $res = true;
        if($res){
          $logarr = [
            $row['userid'],
            $row['username'],
            md5(123456),
            $row['teacher_phone'],
            $row['teacher_job'],
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
        // array_push($newarr,$sql); 
      }
      $res = $newarr;
    }else{
      $res = false;
    }

    echo json_encode($res);


?>