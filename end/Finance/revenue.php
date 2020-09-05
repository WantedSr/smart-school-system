<?php

  
  header('Access-Control-Allow-Origin: *');
  require_once("../conn.php");

  $conn = new Link("finance_upload");
  $conn2 = new Link('finance_revenue');

  $type = '';
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }else if($_POST['type']){
    $type = $_POST['type'];
  }

  if(!empty($type)){
    if($type == 'imp_wage'){

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $file = $_POST['file'] ? $_POST['file'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $school = $_POST['school'] ? $_POST['school'] : null;
      $user = $_POST['user'] ? $_POST['user'] : null;
      $newarr = [];
      if($arr != null){
        
        $month = strtotime(date('Y-m-01')).'000';
        $selobj = [
          'campus'=>$campus,
          'department'=>$department,
          'semester'=>$semester,
          'reg_date'=>$month,
        ];
        $res1 = $conn->select_more("COUNT(*)",$selobj)[0]['COUNT(*)'];
        if($res1 > 0){
          $res1 = true;
        }else{
          $arr1 = [
            $file,
            count($arr),
            $month,
            $semester,
            '0',
            $department,
            $campus,
            $school,
            $user,
            time().'000',
          ];
          $res1 = $conn->insert($arr1);
        }

        if($res1){
          $selobj = [
            'campus'=>$campus,
            'department'=>$department,
            'semester'=>$semester,
            'reg_date'=>$month,
          ];
          $fileId = $conn->select_more("id",$selobj)[0]['id'];

          for($i=0;$i<count($arr);$i++){

            $row = $arr[$i];
            $colArr = [];
            $pareArr = [];
            $newcol = "";
            
            $teacher = $row['teacher_name'];
            $department = $row['department'];
            
            $arr[$i]['department'] = $row['department'] = $conn->query("SELECT * FROM base_department 
            WHERE campus = '$campus' 
              AND department_name ='$department'")->fetchAll(PDO::FETCH_ASSOC)[0]['department_id'];
            
            $arr[$i]['teacher'] = $row['teacher'] = $conn->query("SELECT * FROM teacher_info 
            WHERE campus = '$campus' 
              AND teacher_sfz = '".$row["user_id"]."' 
              AND username = '$teacher'")->fetchAll(PDO::FETCH_ASSOC)[0]['userid']; 

            $arr[$i]['semester'] = $row['semester'] = $conn->query("SELECT * FROM base_semester 
            WHERE semester = '".$row["semester"]."' 
              AND campus = '$campus'")->fetchAll(PDO::FETCH_ASSOC)[0]['semesterId'];

            $arr[$i]['file'] = $row['file'] = $fileId;

            unset($arr[$i]['teacher_name']);
            foreach($arr[$i] as $key => $value){
              array_push($colArr,$key);
              array_push($pareArr,":".$key);
            }
            $newcol = join($colArr,",");
            $value = join($pareArr,",");


            $selobj2 = [
              'file'=>$fileId,
              'campus'=>$campus,
              'department'=>$department,
              'semeter'=>$semester,
              'teacher'=>$row['teacher']
            ];
            $res3 = $conn2->select_more("COUNT(*)",$selobj2)[0]['COUNT(*)'];
            if($res3 > 0){
              $res4 = $conn2->prepare("UPDATE finance_revenue SET 
                job_wage = '".$row['job_wage']."',
                salary_wage = '".$row['salary_wage']."',
                special_salary_wage = '".$row['special_salary_wage']."',
                base_performance_wage = '".$row['base_performance_wage']."',
                teacher_age_wage = '".$row['teacher_age_wage']."',
                house_wage = '".$row['house_wage']."',
                only_child_wage = '".$row['only_child_wage']."',
                one_day_donation = '".$row['one_day_donation']."',
                pension_insurance = '".$row['pension_insurance']."',
                occupational_pension = '".$row['occupational_pension']."',
                house_fund = '".$row['house_fund']."',
                unemployment_insurance = '".$row['unemployment_insurance']."',
                medical_insurance = '".$row['medical_insurance']."',
                personal_income_tax = '".$row['personal_income_tax']."',
                reward_performance_deduction = '".$row['reward_performance_deduction']."',
                should_sum = '".$row['should_sum']."',
                should_deduct = '".$row['should_deduct']."',
                actual_sum = '".$row['actual_sum']."',
                bank_card = '".$row['bank_card']."' 
                WHERE file = '$fileId' 
                  AND campus = '$campus' 
                  AND department = '$department' 
                  AND semester = '$semester' 
                  AND teacher = '".$row['teacher']."'");
              $res = $res4->execute();
            }
            else{
              $sql = "INSERT INTO finance_revenue($newcol) VALUES($value)";
              $res = $conn->prepare($sql);
              foreach($arr[$i] as $key => $val){
                $res->bindParam(":".$key,$arr[$i][$key]);
              }
              $res = $res->execute();
            }
            array_push($newarr,$sql);
            // array_push($newarr,$res);
          }

        }

        $res = [$res1,$newarr];
      }

    }
    else if($type == 'sel_limit_wage'){
      $col = $_GET['col'] ? $_GET['col'] : "*";
      $selobj = $_GET['selobj'] ? $_GET['selobj'] : null;
      $start = $_GET['start'] ? $_GET['start'] : 0;
      $num = $_GET['num'] ? $_GET['num'] : 25;
      $res = $conn->limit_more($col,$start,$num,$selobj);



      // $res = 123;
    }
    echo json_encode($res);
  }





?>