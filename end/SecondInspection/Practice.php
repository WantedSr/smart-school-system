<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn = new Link('commonly_second_practice');
        $conn_dep = new Link('base_department');
        $conn_place = new Link('commonly_second_practice_place');
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
      for($i=count($arr)-1;$i>=0;$i--){
        $row = $arr[$i];
        $d = [
          "department_id"=>$row['department_id'],
          "department_name"=>$row['department_name'],
          "child"=>[],
        ];
        $child = $row['child'];
        for($r=count($child)-1;$r>=0;$r--){
          $row2 = $child[$r];
          $obj = [
            "class"=>$row2['class'],
            "teacher"=>$row2['teacher'],
            "reason"=>$row2['reason'],
            "attendance"=>(int)$row2['attendance'],
            "orderly"=>(int)$row2['orderly'],
            "phone"=>(int)$row2['phone'],
            "health"=>(int)$row2['health'],
            "toilet"=>(int)$row2['toilet'],
            "inspection"=>(int)$row2['inspection'],
            "other"=>(int)$row2['other'],
          ];
  
          $res = $conn->update($obj,'id',$row2['id']);
          array_push($d['child'],$res);
          // array_push($d['child'],$obj);
        }
        array_push($newarr,$d);
      }

      $data = $newarr;
    }
    
    /**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     * date timestamp 删除的某天的时间戳 必须
     */
    function delData(){
        global $conn;
        global $data;
        $date = $_POST['date'] ? $_POST['date'] : null;
        $campus = $_POST['campus'] ? $_POST['campus'] : null;
        $sql = "DELETE FROM commonly_second_practice 
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
          $data[$key]['place_name'] = _getPlaceName($val['place'],$val['campus'])['place_name'];
        }
    }

    function _getDepartmentName($dep,$cam){
      global $conn_dep;
      return $conn_dep->select_more("department_name",[
        "department_id"=>$dep,
        "campus"=>$cam,
      ])[0];
    }
    
    function _getPlaceName($place,$cam){
      global $conn_place;
      return $conn_place->select_more("place_name",[
        "place_id"=>$place,
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

      $arr = $_POST['arr'] ? (array)json_decode($_POST['arr'],true) : null;
      $newarr = [];
      $addTime = time()."000";
      for($i=count($arr)-1;$i>=0;$i--){
        $row = $arr[$i];
        $d = [
          "department_id"=>$row['department_id'],
          "department_name"=>$row['department_name'],
          "child"=>[],
        ];
        $child = $row['child'];
        for($r=count($child)-1;$r>=0;$r--){
          $row2 = $child[$r];
          $obj = [
            $row['department_id'],
            $row2['place_id'],
            $row2['class'],
            $row2['teacher'],
            $row2['reason'],
            (int)$row2['attendance'],
            (int)$row2['orderly'],
            (int)$row2['phone'],
            (int)$row2['health'],
            (int)$row2['toilet'],
            (int)$row2['inspection'],
            (int)$row2['other'],
            $row2['addDate'],
            $row2['semester'],
            $row2['campus'],
            $row2['school'],
            $row2['created_user'],
            $addTime
          ];
  
          $res = $conn->insert($obj);
          array_push($d['child'],$res);
          // array_push($d['child'],$obj);
        }
        array_push($newarr,$d);
      }

      $data = $newarr;
    }

    function groupData(){
      global $conn;
      global $data;

      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $semester = $_POST['semester'] ? $_POST['semester'] : null;

      $sql = "SELECT COUNT(department),addDate,semester,created_user,addTime FROM commonly_second_practice 
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