<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("../conn.php");
        $conn_stu = new Link('student_info');
    } catch (PDOException $e){
        echo json_encode([
            'msg'=> '服务器错误',
            'error'=> '1000'
        ]);
    }

    $data = [];
    $date = '';

    try{
        $action = $_POST['action'] ? $_POST['action'] : null;
        switch ($action){
            case 'returnScoreALLEven': returnScoreALLEven(); break;
            case 'getSumScore': getSumScore(); break;
            case 'getALLStuScore': getALLStuScore(); break;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'msg'=> 'error',
            'error'=> $e->getMessage()
        ]);
    }

    /**
     * 返回宿舍、考勤、作业、奖惩所有数据的分数汇总
     * student string （学生uid）: 2017217041 必须
     * field array （过滤字段）: {"semester":  192002} 不必须
     */
    function getSumScore(){
        global $data;
        $student = $_POST['student'] ? $_POST['student'] : null;
        $enve = _getScoreALLEven($student);
        $data = [
            "sum"=>_SumALL($enve)
        ];
    }

    /**
     * 返回宿舍、考勤、作业、奖惩所有数据，每一个都有sum作为计分
     * student string （学生uid）: 2017217041 必须
     * field array （过滤字段）: {"semester":  192002} 不必须
     */
    function returnScoreALLEven(){
        global $data;
        $student = $_POST['student'] ? $_POST['student'] : null;
        if ($student){
            $data = _getScoreALLEven($student);
        }
    }

    /**
     * 返回每个学生的职业素养分
     * field array （过滤数据字段）: {"semester":  192002} 不必须 
     * filter array （过滤学生字段）: {"department":  "S0001_A_02"} 不必须
     */
    function getALLStuScore(){
        global $data;
        global $conn_stu;
        $filter = [];
        if (array_key_exists('filter', $_POST)){
            $filter = (array)json_decode($_POST['filter']);
        }
        $stu = $conn_stu->select_more('*', $filter);
        $list = [];
        // var_dump($stu);
        foreach($stu as $item){
            $enve = _getScoreALLEven($item['userid']);
            array_push($list, [
                "username"=>$item['username'],
                "userid"=>$item['userid'],
                "sum"=> _SumALL($enve)
            ]);
        }
        $data = $list;
    }


    echo json_encode([
        'msg'=> '成功',
        'error'=> 'null',
        'data'=> $data
    ]);

    function _getScoreALLEven($student){
        if ($student and _hasStu($student)){
            return [
                _CalculationDormitory($student), 
                _CalculationSure($student),
                _CalculationHomework($student),
                _CalculationReward($stuset)
            ];
        }
    }

    function _CalculationDormitory($student){
        // 宿舍
        $conn_dom = new Link('dormitory_attendance');
        $conn_dom_s = new Link('dormitory_attendance_option'); // score
        // attendance 考勤 string
        // discipline 违规 array
        return _CollectTScore($student, $conn_dom, $conn_dom_s);
    }

    // 过滤
    function _selectFilter($dbms, $student){
        $field = [
            "student"=>$student
        ];
        if(array_key_exists('field', $_POST)){
            $filter = (array)json_decode($_POST['field']);
            foreach($filter as $key => $val){
                $field[$key] = $val;
            }
        }
        $data = $dbms->select_more('*', $field);
        return $data;
    }

    function _CollectTScore($student, $in_dbms, $dbms){
        $res = _selectFilter($in_dbms, $student);
        $data = _SumTScore($res, $dbms);
        return $data;
    }

    function _SumTScore($data, $dbms){
        $sum = 0;
        for($i = 0;$i < count($data); $i++){
            $count = 0;
            $attendance = _getTOption($data[$i]['attendance'], $dbms);
            $discipline = _getTOption((array)json_decode($data[$i]['discipline']), $dbms);
            
            $data[$i]['dis'] = $discipline['list'];
            $data[$i]['att'] = $attendance['list'];
            $count = $attendance['sum'] + $discipline['sum'];
            $sum+=$count;
        }
        $data['sum'] = $sum;
        return $data;
    }

    function _getTOption($id, $dbms){
        $data = [];
        $list = [];
        if (is_array($id)){
            foreach($id as $item){
                $result = $dbms->select_more("*", [
                    "option_id"=> $item
                ])[0];
                array_push($list, $result);
            }
        } else if(is_string($id)){
            $result = $dbms->select_more("*", [
                "option_id"=>$id
            ])[0];
            array_push($list, $result);
        }

        if ($list){
            $data['list'] = $list;
            $data['sum'] = _CountDISScore($list);
        }

        return $data;
    }

    function _CountDISScore($dis){
        $count = 0;
        foreach($dis as $item){
            $count += $item['score'];
        }
        return $count;
    }

    function _CalculationSure($student, $type=''){
        $data = [];
        $sum = 0;
        // 考勤
        $conn_between = new Link('commonly_attendance_between');
        $conn_course = new Link('commonly_attendance_course');
        $conn_early = new Link('commonly_attendance_early');
        $conn_sure_s = new Link('stuset_attendance_option'); // score
        
        if ($type){
            switch($type){
                case 'between': array_push($data, _CollectTScore($student,$conn_between,$conn_sure_s));break;
                case 'course': array_push($data, _CollectTScore($student,$conn_course,$conn_sure_s));break;
                case 'early': array_push($data, _CollectTScore($student,$conn_early,$conn_sure_s));break;
            }
        } else {
            array_push($data, _CollectTScore($student,$conn_early,$conn_sure_s));
            array_push($data, _CollectTScore($student,$conn_course,$conn_sure_s));
            array_push($data, _CollectTScore($student,$conn_early,$conn_sure_s));
        }
        $data['sum'] = _SumALL($data);
        return $data;
    }

    function _SumALL($data){
        $sum = 0;
        foreach($data as $item){
            $sum += $item['sum'];
        }
        return $sum;
    }

    function _CalculationHomework($student){
        // 作业
        $conn_homework = new Link('commonly_homework_register');
        // status
        // 1 准时 无
        // 2 缺交 -1
        // 3 补交 +1
        // 统一操作都是一分
        $res = _selectFilter($conn_homework, $student);
        $sum = 0;
        foreach($res as $item){
            switch($item['state']){
                case 2: $sum-=1;break;
                case 3: $sum+=1;break;
            }
        }
        $res['sum'] = $sum;
        return $res;
    }

    function _CalculationReward($student){
        global $needField;
        $conn_reward = new Link('stuset_reward');
        $conn_reward_s = new Link('student_reward_type');

        $res = _selectFilter($conn_reward, $student);
        $ret = [];
        $sum = 0;
        for($i = 0; $i<count($res);$i++){
            $result = $conn_reward_s->select_more("*", [
                "reward_id"=>$res[$i]['reward']
            ]);
            $res[$i]['reward_type'] = $result;
            $res[$i]['type_reward_sum'] = _countReward($result);
            $sum+=$res[$i]['type_reward_sum'];
        }
        $res['sum'] = $sum;
        return $res;
    }

    function _countReward($reward_type){
        $sum=0;
        foreach($reward_type as $item){
            $sum+=$item['reward_score'];
        }
        return $sum;
    }

    function _hasStu($student){
        global $conn_stu;
        $res = $conn_stu->select_more("COUNT(*)", [
            'userid'=>$student
        ])[0]["COUNT(*)"];
        return $res ? true : false;
    }
?>