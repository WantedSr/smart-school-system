<?php 

    header('Access-Control-Allow-Origin: *');

    try{
        require_once("./conn.php");
        $conn_user = new Link('tb_login');
        $conn = new Link('commonly_affairs');
    } catch (PDOException $e){
      echo json_encode([
        'msg'=> '服务器错误',
        'error'=> '1000'
      ]);
    }

    $data = [];

    try{
      /**
       * type 获取人是学生还是老师 必须
       * action 操作类型 必须
       */
      $type = $_POST['type'] ? $_POST['type'] : null;
      $action = $_POST['action'] ? $_POST['action'] : null;
      switch ($action){
        case 'day': getDayToDo(); break;
        case 'month': getMonth(); break;
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
     * col string 查询列 默认全部 自选
     * campus string 所在校区 必填
     * userid string 用户id 必填
     * start string 某月开始的时间戳 必填
     * end string 某月结束的时间戳 必填
     * cls string 用户所在班级 类型为 stu 时必填
     * dep string 用户所在部门 类型为 stu 时必填
     * 默认返回所有数据
     */
    function getMonth(){
      global $conn;
      global $data;
      global $type;

      $campus = $_POST['campus'] ? $_POST['campus'] : null;
      $userid = $_POST['userid'] ? $_POST['userid'] : null;
      $start = $_POST['start'] ? $_POST['start'] : null;
      $end = $_POST['end'] ? $_POST['end'] : null;
      $col = $_POST['col'] ? $_POST['col'] : "*";
      if($type == 'tea'){
        $sql = "SELECT addDate FROM commonly_affairs 
        WHERE type = '0' 
          AND campus = '".$campus."'
          AND object = '".$userid."' 
          AND addDate >= '".$start."' 
          AND addDate <= '".$end."' 
        GROUP BY addDate 
        ORDER BY addDate";
        $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
        foreach($res as $key => $val){
          $date = $val['addDate'];
          $sql2 = "SELECT $col FROM commonly_affairs 
          WHERE type = '0' 
            AND campus = '$campus' 
            AND object = '$userid' 
            AND addDate = '$date'";
          $res2 = $conn->query($sql2)->fetchAll(PDO::FETCH_ASSOC);
          $res[$key]['addDate'] = date('Y-m-d',substr($val['addDate'],0,10));
          $res[$key]['child'] = $res2;
        }
      }
      else if($type == 'stu'){
        $cls = $_POST['cls'] ? $_POST['cls'] : null;
        $dep = $_POST['dep'] ? $_POST['dep'] : null;
        $sql = "SELECT addDate FROM commonly_affairs 
        WHERE 
          (
            (scope = '0' AND (object = 'all' OR object = '$cls'))
            OR
            (scope = '1' AND object = '$userid')
          )
          AND type = '1' 
          AND addDate >= $start 
          AND addDate <= $end 
          AND department = '$dep' 
          AND campus = '$campus'
        GROUP BY addDate 
        ORDER BY addDate";

        $res = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach($res as $key => $val){
          $date = $val['addDate'];
          $sql2 = "SELECT $col FROM commonly_affairs 
          WHERE 
            (
              (scope = '0' AND (object = 'all' OR object = '$cls'))
              OR
              (scope = '1' AND object = '$userid')
            )
            AND type = '1' 
            AND addDate >= '$date' 
            AND department = '$dep' 
            AND campus = '$campus'";
          $res2 = $conn->query($sql2)->fetchAll(PDO::FETCH_ASSOC);
          $res[$key]['addDate'] = date('Y-m-d',substr($val['addDate'],0,10));
          $res[$key]['child'] = $res2;
        }
      }
      $data = $res;
    }

    /**
     * userid string （通过用户id做筛选）: 2017217041 必须
     * campus string 所在校区: 0 必须
     * date string 获取哪一日的时间戳 必须
     * col string 获取的列，默认所有 必须
     * cls string 学生的班级 必须
     * dep string 学生的部门 必须
     */
    function getDayToDo(){
        global $conn;
        global $data;
        global $type;
      
        $campus = $_POST['campus'] ? $_POST['campus'] : null;
        $userid = $_POST['userid'] ? $_POST['userid'] : null;
        $date = $_POST['date'] ? $_POST['date'] : null;
        $col = $_POST['col'] ? $_POST['col'] : "*";
        if($type == 'tea'){
          $obj = [
            'type'=>'0',
            'addDate'=>$date,
            'object'=>$userid,
            'campus'=>$campus,
          ];
          $data = $conn->select_more($col,$obj);
        }
        else if($type == 'stu'){
          $cls = $_POST['cls'] ? $_POST['cls'] : null;
          $dep = $_POST['dep'] ? $_POST['dep'] : null;
          $sql = "SELECT $col FROM commonly_affairs 
          WHERE 
            (
              (scope = '0' AND (object = 'all' OR object = :cls))
              OR
              (scope = '1' AND object = :userid)
            )
            AND type = '1' 
            AND department = :dep 
            AND addDate = :addDate
            AND campus = :campus";
          $res = $conn->prepare($sql);
          $res->bindParam(":cls",$cls);
          $res->bindParam(":userid",$userid);
          $res->bindParam(":dep",$dep);
          $res->bindParam(":addDate",$date);
          $res->bindParam(":campus",$campus);
          $res->execute();
          $data = $res->fetchAll(PDO::FETCH_ASSOC);
        }
    }



?>