<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('teach_patrol_option');
  $conn2 = new Link('teach_patrol_option_list');

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


  if(isset($_GET['type']) || isset($_POST['type'])){
    
    if($_GET['type'] == 'sel_limit_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn->limit_more($col,$start,$num,$selobj);

      foreach($res as $key => $row){
        $user = $row['created_user'];
        $id = $row['option_id'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
        $res[$key]['num'] = $conn2->select_more("COUNT(*)",['option_id'=>$id])[0]['COUNT(*)'];
      }
    }
    else if($_GET['type'] == 'sel_limit_option_list'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn2->limit_more($col,$start,$num,$selobj);
      
      foreach($res as $key => $row){
        $user = $row['created_user'];
        $id = $row['option_id'];
        $res[$key]['option'] = $conn->select_more("option_name",['option_id'=>$id])[0]['option_name'];
        $res[$key]['created_user'] = $conn->query("SELECT user_name FROM tb_login WHERE user_id = '$user'")->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];
      }
    }
    else if($_GET['type'] == 'sel_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($_GET['type'] == 'sel_option_list'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn2->select_more($col,$selobj);
    }
    else if($_POST['type'] == 'add_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
      // $res = '123';
    }
    else if($_POST['type'] == 'add_option_list'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn2->insert($arr);
      // $res = '123';
    }
    else if($_POST['type'] == 'upa_option'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
      // $res = '123';
    }
    else if($_POST['type'] == 'upa_option_list'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn2->update($obj,$sel,$val);
      // $res = '123';
    }
    else if($_POST['type'] == 'del_course'){
      $id = $_POST['id'] ? $_POST['id'] : null;
      $res = $conn->delete('id',$id);
    }
    else if($_GET['type'] == 'get_option_id'){
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $res = $conn->query("SELECT * FROM teach_patrol_option WHERE campus = '$campus' ORDER BY id DESC LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['option_id'];
      $res = substr($res,-1,3);
      $res++;
      if($res < 10){
        $res = '00'.$res;
      }else if($res < 100){
        $res = '0'.$res;
      }
      $res = $campus."_O".$res;
    }
    else if($_GET['type'] == 'get_option_list'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      // $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $selobj = ['campus'=>$campus];
      $res = $conn->select_more($col,$selobj);
      foreach($res as $key => $row){
        $optionID = $row['option_id'];
        $res[$key]['children'] = $conn2->select("*","option_id",$optionID);
      }
    }

    echo json_encode($res);
  }




?>