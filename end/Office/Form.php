<?php

  
  header('Access-Control-Allow-Origin: *');

  require_once("../conn.php");

  $conn = new Link("oa_form");
  $conn2 = new Link("oa_form_process");
  $conn3 = new Link("base_department");
  
  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }

  
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

  if(!empty($type)){

    if($type == 'sel_form'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->select_more($col,$selobj);
    }
    else if($type == 'sel_limit_form'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 15;
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $res = $conn->limit_more($col,$start,$num,$selobj);
      foreach($res as $key => $row){
        $arr = $row['process'];
        $arr = json_decode($arr);
        if(!is_array($arr)){
          $arr = [];
        }
        $res[$key]['form_num'] = count($arr);
      }
    }
    else if($type == 'add_form'){
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $num = $conn->query("SELECT id FROM oa_form 
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
      $arr[0] = $campus.'_F'.$num;
      $res = $conn->insert($arr);
      // $res = $arr;
    }
    else if($type == 'upa_form'){
      $obj = $_POST['obj'] ? $_POST['obj'] : null;
      $sel = $_POST['sel'] ? $_POST['sel'] : null;
      $val = $_POST['val'] ? $_POST['val'] : null;
      $res = $conn->update($obj,$sel,$val);
    }
    else if($type == 'del_approval'){
      $sel = $_POST['sel'] ? $_POST['sel'] : "id";
      $arr = $_POST['arr'] ? $_POST['arr'] : [];
      $res = $conn->delete_more($sel,$arr);
    }
    else if($type == 'sel_process'){
      $campus = $_GET['campus'] ? $_GET['campus'] : null;
      $id = $_GET['id'] ? $_GET['id'] : null;
      $setArr = $conn->select_more("process",[
        'campus'=>$campus,
        'form_id'=>$id,
      ])[0]['process'];
      $setArr = json_decode($setArr);
      if(!is_array($setArr)){
        $setArr = [];
      }
      $res = $conn->prepare("SELECT approval_id,approval_name FROM oa_approval_process 
        WHERE campus = :campus 
          AND approval_type != '分类设置'");
      $res->bindParam(":campus",$campus);
      $res->execute();
      $arr = $res->fetchAll(PDO::FETCH_ASSOC);
      $proArr = [];
      foreach($arr as $key => $row){
        $obj = [
          'key'=>$row['approval_id'],
          'label'=>$row['approval_name'],
        ];
        array_push($proArr,$obj);
      }
      $res = [$proArr,$setArr];
      // $res = $setArr;
    }
    else if($type == 'set_process'){
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $id = $_POST['id'] ? $_POST['id'] : null;
      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $res = $conn->prepare("UPDATE oa_form SET process = :arr 
        WHERE campus = :campus 
          AND form_id = :id");
      $res->bindParam(":arr",$arr);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":id",$id);
      $res = $res->execute();
    }
    else if($type == 'sel_approval'){
      $form = $_GET['form'] ? $_GET['form'] : null;
      $campus = $_GET['campus'] ? $_GET['campus'] : null;

      $process = $conn->select_more('process',[
        'campus'=>$campus,
        'form_id'=>$form,
      ])[0]['process'];
      $process = json_decode($process);
      $strArr = [];
      for($i=0;$i<count($process);$i++){
        $str1 = "approval_id = :".$i;
        array_push($strArr,$str1);
      }
      $str = join(" OR ",$strArr);

      $res = $conn->prepare("SELECT id,approval_id,approval_name,approval_type,approvers,campus FROM oa_approval_process 
        WHERE campus = '$campus' 
          AND (".$str.")");
      for($i=0;$i<count($process);$i++){
        $res->bindParam((":".$i),$process[$i]);
      }
      $res->execute();
      $res = $res->fetchAll(PDO::FETCH_ASSOC);

      foreach($res as $key => $row){

        $approval = $row['approval_id'];
        $approvalType = $row['approval_type'];
        if($approvalType == '统一设置'){

          $approvers = $row['approvers'];
          $approvers = json_decode($approvers);
          if(!is_array($approval)){
            $approvers = [];
          }
          
          $strArr = [];
          for($i=0;$i<count($approvers);$i++){
            $str1 = "userid = '".$approvers[$i]."'";
            array_push($strArr,$str1);
          }
          $str = join(" OR ",$strArr);
          if(count($approvers) > 0){
            $res[$key]['approvers'] = $conn->query("SELECT username FROM teacher_info 
              WHERE campus = '$campus' 
              AND (".$str.")")->fetchAll(PDO::FETCH_ASSOC);
          }else{
            $res[$key]['approvers'] = [];
          }

        }
        else if($approvalType == '分部设置'){
          $depData = $conn3->select_more("department_id,department_name",[
            'campus'=>$campus,
            'department_state'=>'1',
          ]);
          $newarr = [];
          for($r=0;$r<count($depData);$r++){
            $dep = $depData[$r]['department_id'];
            $responsible = $conn->query("SELECT department_responsible FROM oa_organization 
              WHERE campus = '$campus' 
                AND department = '$dep'")->fetchAll(PDO::FETCH_ASSOC);
            $responsible = json_decode($responsible[0]['department_responsible']);
            if(!is_array($responsible)){
              $responsible = [];
            }
            $arr = [];
            for($i=0;$i<count($responsible);$i++){
              array_push($arr,$responsible[$i]);
            }
            $arr = array_unique($arr);
            $teaArr = [];
            for($i=0;$i<count($arr);$i++){
              $teaId = $arr[$i];
              $username = $conn->query("SELECT username FROM teacher_info 
                WHERE campus = '$campus' 
                  AND department = '$dep'
                  AND userid = '$teaId'")->fetchAll(PDO::FETCH_ASSOC)[0]['username'];
              array_push($teaArr,$username);
            }
            $obj = [
              'dep_name'=>$depData[$r]['department_name'],
              'dep_teacher'=>$teaArr,
            ];
            array_push($newarr,$obj);
          };
          

          $res[$key]['approvers'] = $newarr;
          // $res[$key]['approvers'] = $depData;
        }
        
        $res2 = $conn2->select_more("*",[
          'campus'=>$campus,
          "form_id"=>$form,
          "approval_id"=>$approval,
        ]);

        if(count($res2) > 0){
          $res2 = $res2[0];
          $res[$key]['queue'] = $res2['queue'];
          $res[$key]["end"] = $res2["end"];
          $res[$key]['money'] = $res2['money'];
        }
        else{
          $res[$key]['queue'] = '0';
          $res[$key]['end'] = '1';
          $res[$key]['money'] = '0';
        }
        
      }
    }
    else if($type == 'set_approval'){
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $form = $_POST['form'] ? $_POST['form'] : null;
      $arr = $_POST['arr'] ? $_POST['arr'] : null;

      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];
        $num = $conn2->select_more("COUNT(*)",[
          'form_id'=>$row['form_id'],
          'approval_id'=>$row['approval_id'],
          'campus'=>$campus,
        ])[0]['COUNT(*)'];
        if($num > 0){
          $res = $conn2->prepare("UPDATE oa_form_process SET 
            queue = :queue,
            money = :money,
            end = :end 
            WHERE 
              form_id = :form 
              AND approval_id = :approval 
              AND campus = :campus");
          $res->bindParam(":queue",$row['queue']);
          $res->bindParam(":money",$row['money']);
          $res->bindParam(":end",$row['end']);
          $res->bindParam(":form",$row['form']);
          $res->bindParam(":approval",$row['approval_id']);
          $res->bindParam(":campus",$campus);
          $res = $res->execute();
          array_push($newarr,$res);
        }
        else{
          $itemArr = objarray_to_array($row);
          $res = $conn2->insert($itemArr);
          array_push($newarr,$res);
        }
      }
      $res = $newarr;

    }
    
    echo json_encode($res);
  }




?>