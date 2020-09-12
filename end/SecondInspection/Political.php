<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn = new Link('commonly_second_political');
        $conn_dep = new Link('base_department');
        $conn_user = new Link('tb_login');
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
     * arr 数组对象 督查记录信息 必须
     */
    function upData(){
      global $conn;
      global $data;

      $arr = $_POST['arr'] ? (array)json_decode($_POST['arr'],true) : null;
      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        $row = $arr[$i];
        $obj = [
          "health"=>$row['health'],
          "health_plus"=>(int)$row['health_plus'],
          "health_minus"=>(int)$row['health_minus'],
          "discipline"=>$row['discipline'],
          "discipline_plus"=>(int)$row['discipline_plus'],
          "discipline_minus"=>(int)$row['discipline_minus'],
          "exercises"=>$row['exercises'],
          "exercises_plus"=>(int)$row['exercises_plus'],
          "exercises_minus"=>(int)$row['exercises_minus'],
          "inspection"=>$row['inspection'],
          "inspection_plus"=>(int)$row['inspection_plus'],
          "inspection_minus"=>(int)$row['inspection_minus'],
          "other"=>$row['other'],
          "other_plus"=>(int)$row['other_plus'],
          "other_minus"=>(int)$row['other_minus'],
        ];

        $res = $conn->update($obj,"id",$row['id']);
        array_push($newarr,$res);
        // array_push($newarr,$obj);
        // array_push($newarr,$row['id']);
      }

      $data = $newarr;
    }
    
    /**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     */
    function delData(){
        global $conn;
        global $data;
        $date = $_POST['date'] ? $_POST['date'] : null;
        $campus = $_POST['campus'] ? $_POST['campus'] : null;
        $sql = "DELETE FROM commonly_second_political 
          WHERE addDate = :addDate 
            AND campus = :campus";
        $res = $conn->prepare($sql);
        $res->bindParam(":addDate",$date);
        $res->bindParam(":campus",$campus);
        $res = $res->execute();
        $data = $res; 
    }

    /**
     * request array 数组对象 进行赛选内容 自选
     * col string 字符串 搜索的值 自选
     * 默认返回所有数据
     */
    function getData(){
        global $conn;
        global $data;
        $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
        $col = $_POST['col'] ? $_POST['col'] : "*";
        
        if ($request){
          $data = $conn->select_more($col, $request);
        } else {
          $data = $conn->select_more($col);
        }

        foreach($data as $key => $val){
          $data[$key]['department_name'] = _getDepartmentName($val['department'],$val['campus'])['department_name'];
          // $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['username'];
        }
    }

    function _getDepartmentName($dep,$cam){
      global $conn_dep;
      return $conn_dep->select_more("department_name",[
        "department_id"=>$dep,
        "campus"=>$cam,
      ])[0];
    }

    function _getUser($id,$cam){
      global $conn_user;
      return $conn_user->select_more("user_name",[
        "user_id"=>$id,
        "user_campus"=>$cam,
      ])[0];
    }

    /**
     * arr array 数组对象 包含各个部门的政教处督查的信息 必须
     */
    function addData(){
      global $conn;
      global $data;

      $arr = $_POST['arr'] ? (array)json_decode($_POST['arr']) : null;
      $newarr = [];
      $addTime = time()."000";
      for($i=count($arr)-1;$i>=0;$i--){
        $row = $arr[$i];
        $obj = [
          $row->department_id,
          $row->health,
          (int)$row->health_plus,
          (int)$row->health_minus,
          $row->discipline,
          (int)$row->discipline_plus,
          (int)$row->discipline_minus,
          $row->exercises,
          (int)$row->exercises_plus,
          (int)$row->exercises_minus,
          $row->inspection,
          (int)$row->inspection_plus,
          (int)$row->inspection_minus,
          $row->other,
          (int)$row->other_plus,
          (int)$row->other_minus,
          $row->addDate,
          $row->semester,
          $row->campus,
          $row->school,
          $row->created_user,
          $addTime
        ];

        $res = $conn->insert($obj);
        array_push($newarr,$res);
        // array_push($newarr,$obj);
      }

      $data = $newarr;
      // $data = $arr;
    }

    function groupData(){
      global $conn;
      global $data;

      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;

      $sql = "SELECT COUNT(department),addDate,semester,created_user,addTime FROM commonly_second_political 
        WHERE campus = :campus 
          AND semester = :semester
        GROUP BY addDate
        ORDER BY addDate DESC";
      $res = $conn->prepare($sql);
      $res->bindParam(":campus",$campus);
      $res->bindParam(":semester",$semester);
      $res->execute();
      $data = $res->fetchAll(PDO::FETCH_ASSOC);

      foreach($data as $key => $val){
        $data[$key]['created_user'] = _getUser($val['created_user'],$campus)['user_name'];
      }
    }



?>