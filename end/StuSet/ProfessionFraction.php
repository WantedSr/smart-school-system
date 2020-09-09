<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn = new Link('stuset_reward');
        $conn_stu = new Link('student_info');
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
        $id = $_POST['id'] ? $_POST['id'] : null;
        $request = $_POST['request'] ? (array)json_decode($_POST['request']) : null;
        $conn->update($request, 'id', $id);
    }
    
    /**
     * id int （stuset_reward 的唯一自增值）: 1 必须
     */
    function delData(){
        global $conn;
        $id = $_POST['id'] ? $_POST['id'] : null;
        $conn->delete('id', $id);
    }

    /**
     * student string （通过student做筛选）: 2017217041 自选
     * 默认返回所有更改
     */
    function getData(){
        global $conn;
        global $conn_stu;
        global $data;
        $student = $_POST['student'] ? $_POST['student'] : null;

        if ($student){
            $data = $conn->select_more("*",[
                'student'=>$student
            ]);
        } else {
            $data = $conn->select_more();
        }
        foreach ($data as $val){
            $data['stu'] = _getStuByUid($val['student'])[0];
        }
    }

    function _getStuByUid($userid){
        global $conn_stu;
        return $conn_stu->select_more("*", [
            "userid"=>$userid
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
        global $conn_stu;

        $student = $_POST['student'] ? $_POST['student'] : null;
        $type = $_POST['type'] ? (int)$_POST['type'] : null;
        $reward = $_POST['reward'] ? $_POST['reward'] : null;
        $score = $_POST['score'] ? (int)$_POST['score'] : null;
        $create_user = $_POST['create_user'] ? $_POST['create_user'] : null;
    
        $number = $conn_stu->select_more("*",[
            'userid'=>$student
        ]);
        $number = $number[0];
    
        $department = $number['department'];
        $campus = $number['campus'];
        $school = $number['school'];
        $class = $number['class'];
        $time = time().'000';
        $obj = [
            $student,
            $type,
            $reward,
            $score,
            $class,
            $department,
            $campus,
            $school,
            $create_user,
            $time
        ];
    
        $conn->insert($obj);
    }
?>