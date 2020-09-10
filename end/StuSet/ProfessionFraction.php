<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn = new Link('stuset_reward');
        $conn_stu = new Link('student_info');
        $conn_reward = new Link('student_reward_type');
        $conn_class = new Link('base_class');
    } catch (PDOException $e){
        echo json_encode([
            'msg'=> '服务器错误',
            'error'=> '1000'
        ]);
    }

    $data = [];

    try{
        $action = $_POST['action'] ? $_POST['action'] : null;
        switch ($action){
            case 'add': addData(); break;
            case 'get': getData(); break;
            case 'del': delData(); break;
            case 'up': upData(); break;
            case 'group': groupData(); break;
            case 'imp': impData(); break;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'msg'=> 'error',
            'error'=> $e->getMessage()
        ]);
    }
    
    echo json_encode([
        'msg'=> '成功',
        'error'=> 'null',
        'data'=> $data
    ]);

    /**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     * request object （需要更新的数据）: {"p": "捡到一分钱"} 必须
     */
    function upData(){
        global $conn;
        global $data;
        $id = $_POST['id'] ? $_POST['id'] : null;
        $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
        $data = $conn->update($request, 'id', $id);
    }
    
    /**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     */
    function delData(){
        global $conn;
        global $data;
        $id = $_POST['id'] ? $_POST['id'] : null;
        $data = $conn->delete_more('id', $id);
    }

    /**
     * student string （自定义筛选）: {"student": 2017217041} 自选
     * 默认返回所有数据
     */
    function getData(){
        global $conn;
        global $conn_stu;
        global $data;
        $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
        if ($request){
            $data = $conn->select_more("*", $request);
        } else {
            $data = $conn->select_more();
        }
        // if($_POST['student']){
          for($i=0;$i<count($data);$i++){
            $val = $data[$i];
            $data[$i]['stu'] = _getStuByUid($val['student'])[0];
            $data[$i]['class'] = _getClass($val['class'])[0];
            $data[$i]['reward'] = _getReward($val['reward'])[0];
          }
        // }
    }

    function _getStuByUid($userid){
        global $conn_stu;
        return $conn_stu->select_more("id,userid,username,class", [
            "userid"=>$userid,
        ]);
    }
    function _getClass($class){
      global $conn_class;
      return $conn_class->select_more("class_id,class_name",[
        'class_id'=>$class,
      ]);
    }
    function _getReward($reward){
      global $conn_reward;
      return $conn_reward->select_more("reward_id,reward_name,type,reward_score",[
        'reward_id'=>$reward,
      ]);
    }

    /**
     * student string （通过student做筛选）: 2017217041 必须
     * type int （加分减分 0 1）: 0 必须
     * reward string （说明）: 捡到一分钱 必须
     * score int （加减多少分）: -1 必须
     * create_user string（增加记录的用户ID）: 2017217041 必须
     */
    function addData(){
        global $conn;
        global $data;
        // global $conn_stu;

        // $student = $_POST['student'] ? $_POST['student'] : null;
        // $type = $_POST['type'] ? (int)$_POST['type'] : null;
        // $reward = $_POST['reward'] ? $_POST['reward'] : null;
        // $score = $_POST['score'] ? (int)$_POST['score'] : null;
        // $create_user = $_POST['create_user'] ? $_POST['create_user'] : null;
    
        // $number = $conn_stu->select_more("*",[
        //   'userid'=>$student
        // ]);
        // $number = $number[0];
    
        // $department = $number['department'];
        // $campus = $number['campus'];
        // $school = $number['school'];
        // $class = $number['class'];
        // $time = time().'000';
        // $obj = [
        //     $student,
        //     $type,
        //     $reward,
        //     $score,
        //     $semester,
        //     $class,
        //     $department,
        //     $campus,
        //     $school,
        //     $create_user,
        //     $time
        // ];

        $arr = $_POST['arr'] ? (array)json_decode($_POST['arr']) : null;
        $data = $conn->insert($arr);
        // $data = [
        //   'status'=>$res,
        // ];
        // $conn->insert($obj);
    }

    function groupData(){
      global $conn;
      global $data;

      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $department = $_POST['department'] ? $_POST['department'] : null;
      $sql = "SELECT * FROM stuset_reward WHERE semester = :semester AND department = :department group by student";
      
      $res = $conn->prepare($sql);
      $res->bindParam(":semester",$semester);
      $res->bindParam(":department",$department);
      $res->execute();
      $res = $res->fetchAll(PDO::FETCH_ASSOC);
      foreach($res as $key => $val){
        $stu = $val['student'];
        $sql2 = "SELECT reward,SUM(score) FROM stuset_reward WHERE semester = :semester AND department = :department AND student = :student group by reward";
        $res2 = $conn->prepare($sql2);
        $res2->bindParam(":semester",$semester);
        $res2->bindParam(":department",$department);
        $res2->bindParam(":student",$stu);
        $res2->execute();
        $res2 = $res2->fetchAll(PDO::FETCH_ASSOC);
        $res[$key]['total_score'] = $res2;
        $res[$key]['stu'] = _getStuByUid($val['student'])[0];
        $res[$key]['class'] = _getClass($val['class'])[0];
        $res[$key]['reward'] = _getReward($val['reward'])[0];
      }
      $data = $res;
    }

    function impData(){
      global $conn;
      global $data;
      global $conn_reward;

      $arr = $_POST['arr'] ? $_POST['arr'] : null;
      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;
      $created_user = $_POST['created_user'] ? $_POST['created_user'] : null;
      $newarr = [];

      if($arr != null){
        for($i=0;$i<count($arr);$i++){
  
          $row = $arr[$i];
          $colArr = [];
          $pareArr = [];
          $newcol = "";


          $student = $conn->query("SELECT userid,username,class,department,campus,school FROM student_info 
          WHERE campus = '$campus' 
          AND userid = '".$row['userid']."' 
          AND username = '".$row['student']."'  
          AND job = 'S1'")->fetchAll(PDO::FETCH_ASSOC)[0];

          $arr[$i]['student'] = $student['userid'];
          $arr[$i]['class'] = $student['class'];
          $arr[$i]['department'] = $student['department'];
          $arr[$i]['type'] = $row['type'] == '奖励' ? '0' : '1';
          $arr[$i]['campus'] = $campus;
          $arr[$i]['school'] = $student['school'];
          $arr[$i]['semester'] = $semester;
          $arr[$i]['created_user'] = $created_user;
          $arr[$i]['addTime'] = strtotime($row['addTime']).'000';

          $reward = $conn_reward->select_more("reward_id,reward_name,reward_score",[
            'reward_name'=>$arr[$i]['reward'],
            'type'=>$arr[$i]['type'],
          ])[0];

          $arr[$i]['reward'] = $reward['reward_id'];
          $arr[$i]['score'] = $reward['reward_score'];

          unset($arr[$i]['userid']);
  
          foreach($arr[$i] as $key => $value){
            array_push($colArr,$key);
            array_push($pareArr,":".$key);
          }
          
          $newcol = join($colArr,",");
          $value = join($pareArr,",");
  
  
          $sql = "INSERT INTO stuset_reward($newcol) VALUES($value)";
          $res = $conn->prepare($sql);
          foreach($arr[$i] as $key => $val){
            $res->bindParam(":".$key,$arr[$i][$key]);
          }
          $res = $res->execute(); 
          // $res = true;
          array_push($newarr,$res);
          // array_push($newarr,$arr[$i]); 
          // array_push($newarr,$sql); 
        }
        $data = $newarr;
      }else{
        $data = false;
      }
    }


?>