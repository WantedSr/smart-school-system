<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../../conn.php");

  $conn = new Link("oa_contain_option");
  
  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }


  if(!empty($type)){

    if($type == 'sel_contain_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'add_contain_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $num = $conn->query("SELECT id FROM oa_contain_option 
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
      $arr[0] = $campus.'_O'.$num;
      $res = $conn->insert($arr);
      // $res = $num;
    }
    else if($type == 'upa_contain_option'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_contain_option'){
      $sel = $_POST['sel'] ? $_POST['sel'] : "id";
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($sel,$arr);
    }
    
    echo json_encode($res);
  }




?>