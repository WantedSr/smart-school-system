<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn_user = new Link('tb_login');
        $conn = new Link('commonly_second_skill');
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
            case 'getId': _getId(); break;
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
     * 返回查询用户
     */
    function _getUser($id,$cam){
      global $conn_user;
      return $conn_user->select_more("user_name",[
        "user_id"=>$id,
        "user_campus"=>$cam,
      ])[0];
    }


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
     * id array 存放要删除的记录的id 必须
     */
    function delData(){
        global $conn;
        global $data;
        $id = $_POST['id'] ? $_POST['id'] : null;
        $data = $conn->delete_more('id', $id);
    }

    /**
     * col string 查询列 默认全部 自选
     * request 数组对象 （筛选查询）: {"student": 2017217041} 自选
     * 默认返回所有数据
     */
    function getData(){
        global $conn;
        global $data;
        $col = $_POST['col'] ? $_POST['col'] : "*";
        $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
        
        try{
            $date = $request['addTime'];
            unset($request['addTime']);
        } catch (Exception $e){};
        
        if ($request){
            $data = $conn->select_more($col, $request);
        } else {
            $data = $conn->select_more($col);
        }
        
        if($_POST['created_user']){
          foreach($data as $key => $val){
            $data[$key]['created_user'] = _getUser($val['created_user'],$val['campus'])['user_name'];
          }
        }
    }
    function _getSemesterById($semesterid){
      $conn_semester = new Link('base_semester');
      return $conn_semester->select_more("*", [
          "semesterid"=>$semesterid
      ]);
    }

    function _getId(){
      global $conn;
      global $data;

      $campus = $_POST['campus'] ? $_POST['campus'] : null;

      $sql = "SELECT id FROM commonly_second_skill 
      WHERE campus = :campus 
      ORDER BY id DESC 
      LIMIT 0,1";
      $res = $conn->prepare($sql); $res->bindParam(":campus",$campus);
      $res->execute();
      $data = $res->fetchAll(PDO::FETCH_ASSOC);
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

        $arr = $_POST['arr'] ? (array)json_decode($_POST['arr'],true) : null;
        $data = $conn->insert($arr);
    }



?>