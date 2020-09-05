<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("oa_allow");
  $conn2 = new Link("teacher_info");
  

  // function arrToStr($arr,$campus){
  //   $newarr = [];
  //   for($i=0;$i<count($arr);$i++){
  //     array_push($newarr,"userid = :".$i);
  //   }
  //   $str = join($newarr," AND campus = '$campus' OR ");
  //   return $str;
  // }



  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }


  if(!empty($type)){

    if($type == 'sel_allow'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $department = $_GET['department'] ? $_GET['department'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $selobj1 = [
        'campus'=>$campus,
        "department"=>$department,
        'state'=>'1',
      ];
      $newarr1 = [];
      $res1 = $conn->select_more($col,$selobj1);
      foreach($res1 as $key1 => $row1){
        array_push($newarr1,$row1['teacher']);
      }
      $res2 = $conn2->select_more('userid,username',[
        'campus'=>$campus,
        'department'=>$department,
        'type'=>'1',
      ]);
      $newarr2 = [];
      foreach($res2 as $key2 => $row2){
        $obj = [
          "key"=>$row2['userid'],
          "label"=>$row2['username'],
        ];
        array_push($newarr2,$obj);
      };
      $res = [$newarr2,$newarr1];
    }
    else if($type == 'upa_allow'){

      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $school = $_POST['school'] ? $_POST['school'] : null;
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      // $str = arrtoStr($arr);
      $userId = $_POST['userId'] ? $_POST['userId'] : null;
      $set = $_POST['set'] ? $_POST['set'] : null;

      $newarr = [];

      if($set == 'set'){
        for($i=0;$i<count($arr);$i++){

          $row = $arr[$i];

          $selobj = [
            'campus'=>$campus,
            'department'=>$department,
            'teacher'=>$row,
          ];

          $num = $conn->select_more("COUNT(*)",$selobj)[0]['COUNT(*)'];

          if($num > 0){
            $res = $conn->prepare("UPDATE oa_allow SET state = '1' 
              WHERE department = :department 
                AND campus = :campus 
                AND teacher = :teacher");
            $res->bindParam(":department",$department);
            $res->bindParam(":campus",$campus);
            $res->bindParam(":teacher",$row);
            $res = $res->execute();
          }
          else{
            $insarr = [
              $row,
              '1',
              $department,
              $campus,
              $school,
              $userId,
              time().'000',
            ];
            $res = $conn->insert($insarr);
          }

          array_push($newarr,$res);

        };
        
        $res = $newarr;

      }
      else if($set = 'unset'){
        for($i=0;$i<count($arr);$i++){
          $row = $arr[$i];
          
          $res = $conn->prepare("UPDATE oa_allow SET state = '0' 
            WHERE department = :department 
              AND campus = :campus 
              AND teacher = :teacher");
          $res->bindParam(":department",$department);
          $res->bindParam(":campus",$campus);
          $res->bindParam(":teacher",$row);
          $res = $res->execute();
          array_push($newarr,$res);
        }
        $res = $newarr;
      }

    }
    else if($type == 'del_contain_option'){
      $sel = $_POST['sel'] ? $_POST['sel'] : "id";
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($sel,$arr);
    }
    
    echo json_encode($res);
  }




?>