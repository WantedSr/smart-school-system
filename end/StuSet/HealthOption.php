<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link('stuset_health_option');
  $conn2 = new Link('stuset_health_option_list');

  if(isset($_GET['type']) || isset($_POST['type'])){
    if($_GET['type'] == 'sel_limit_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $model = $row['model'];
        $opId = $row['option_id'];
        $campus = $row['campus'];
        $res[$key]['model'] = $conn->query("SELECT * FROM stuset_health_model WHERE health_id = '$model' AND campus = '$campus' LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['health_name'];
        $res[$key]['option_num']= $conn->query("SELECT COUNT(*) FROM stuset_health_option_list WHERE option_id = '$opId' AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
      }
    }
    else if($_GET['type'] == 'sel_option'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }

    else if($_POST['type'] == 'add_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->insert($arr);
    }

    else if($_POST['type'] == 'upa_option'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }

    else if($_POST['type'] == 'del_option'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($arr);
    }

    else if($_GET['type'] == 'sel_limit_option_list'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn2->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $optionId = $row['option_id'];
        $campus = $row['campus'];
        $res[$key]["option_id"] = $conn->query("SELECT * FROM stuset_health_option WHERE option_id = '$optionId' AND campus = '$campus' LIMIT 0,1")->fetchAll(PDO::FETCH_ASSOC)[0]['option_name'];
      }
    }
    
    else if($_GET['type'] == 'sel_option_list'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn2->select_more($col,$selobj);
    }

    else if($_POST['type'] == 'add_option_list'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn2->insert($arr);
    }

    else if($_POST['type'] == 'upa_option_list'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn2->update($obj,$sel,$val);
    }

    else if($_POST['type'] == 'del_option_list'){
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn2->delete_more($arr);
    }

    else if($_GET['type'] == 'get_option_id'){
      $conn = new Link('stuset_health_option');
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $model = $_GET['model'] ? $_GET['model'] : null;
      if($campus != null){
        $sql = "SELECT * FROM stuset_health_option WHERE campus = '$campus' AND model = '$model' ORDER BY id DESC LIMIT 0,1";
        $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        // $arr = explode('_',$res);
        $res = $model."_O".($res+1);
      }else{
        $res = 0;
      }
    }

    echo json_encode($res);
  }




?>