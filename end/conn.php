<?php

  class Link{

    private $test_host = 'localhost';
    private $test_user = "root";
    private $test_password = "root";
    private $test_db = "db_aqschool";

    // private $pdt_host = "bj-cdb-f9waakdl.sql.tencentcdb.com";
    // private $pdt_port = '63790';
    // private $pdt_user = "root";
    // private $pdt_password = "root123456";
    // private $pdt_db = "schoolSystem";

    private $tb;
    private $pdo;

    public function __construct($tb)
    {
      $this->tb = $tb;
      try{
        // 测试
        $this->pdo = new PDO("mysql:host=".$this->test_host.";dbname=".$this->test_db,$this->test_user,$this->test_password);
        // 生产
        // $this->pdo = new PDO("mysql:host=".$this->pdt_host.";port=".$this->pdt_port.";dbname=".$this->pdt_db,$this->pdt_user,$this->pdt_password);
        // $this->pdo->query("SET NAMES UTF8");
      } catch (Exception $th) {
        $arr = ['连接数据库有误！',$th->getMessage()];
        echo json_encode($arr);
        // echo("<script>console.log(".$th->getMessage().")</script>");
        exit();
      }
    }


    public function select($col="*",$sel=null,$val=null){
      if(empty($val) && empty($sel)){
        return $this->pdo->query("SELECT $col FROM $this->tb")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        return $this->pdo->query("SELECT $col FROM $this->tb WHERE $sel = '$val'")->fetchAll(PDO::FETCH_ASSOC);
      }
    }

    public function select_more($col="*",$selobj=null){
      if(empty($selobj)){
        return $this->pdo->query("SELECT $col FROM $this->tb ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $str = $this->objtosql($selobj);
        $sql = "SELECT $col FROM $this->tb WHERE $str ORDER BY id DESC";
        // $res = $this->pdo->query($sql);
        $res = $this->pdo->prepare($sql);
        foreach($selobj as $key => $val){
          $res->bindParam(":".$key,$selobj[$key]);
        }
        $res->execute();
        return $res->fetchAll(PDO::FETCH_ASSOC);
        // return $res;
      }
    }

    public function delete($sel=null,$val=null){
      if(!empty($sel) && !empty($val)){
        $res = $this->pdo->prepare("DELETE FROM $this->tb WHERE $sel = :val");
        $res->bindParam(":val",$val);
        return $res->execute();
        // return $this->pdo->query("DELETE FROM $this->tb WHERE $sel = '$val'");
      }else{
        return false;
      }
    }

    public function delete_more($sel=null,$arr=null){
      if(!empty($sel) && count($arr)){
        $str = $this->arrtostr($arr);
        $sql = "DELETE FROM $this->tb WHERE $sel in (".$str.");";
        $res = $this->pdo->prepare($sql);
        for($i=0;$i<count($arr);$i++){
          $res->bindParam((":".$i),$arr[$i]);
        }
        return $res->execute();
      }else{
        return false;
      }
    }

    public function update($obj=null,$sel=null,$val=null){
      if(!empty($sel) && !empty($val) && !empty($obj)){
        $str = $this->objtostr($obj);
        $arr = $this->objtoarr($obj);
        $res = $this->pdo->prepare("UPDATE $this->tb SET $str WHERE $sel = '$val'");
        for($i=0;$i<count($arr);$i++){
          $res->bindParam(":".$i,$arr[$i]);
        }
        return $res->execute();
      }else{
        return false;
      }
    }

    public function update_more($obj=null,$sel=null,$arr=null){
      if(!empty($sel) && !empty($obj) && !empty($arr)){
        $str = $this->objtostr($obj);
        $arr1 = $this->objtoarr($obj);
        $str2 = join($arr,"','");
        $str2 = "'".$str2."'";
        $sql = "UPDATE $this->tb SET $str WHERE $sel in (".$str2.")";
        $res = $this->pdo->prepare($sql);
        for($i=0;$i<count($arr1);$i++){
          $res->bindParam(":".$i,$arr1[$i]);
        }
        return $res->execute();
      }else{
        return false;
      }
    }

    public function insert($arr=null){
      if(!empty($arr)){
        $str = $this->arrtostr($arr);
        $sql = "INSERT INTO $this->tb VALUES(null,$str)";
        $res = $this->pdo->prepare($sql);
        for($i=0;$i<count($arr);$i++){
          $res->bindParam((":".$i),$arr[$i]);
        }
        // return $sql;
        return $res->execute();
      }else{
        return false;
      }
    }

    public function insert_more($col=null,$arr=null){
      if(!empty($arr)){
        $colstr = join($col,"','");
        $colstr = "'".$colstr."'";
        $arr = [];
        for($i = 0;$i<count($arr);$i++){
          $row1 = $arr[$i];
          $arr2 = [];
          foreach($row1 as $key => $value){
            array_push($arr2,$i."-".$key);
          }
          $str1 = join($arr2,',');
          $str1 = "(".$str1.")";
          array_push($arr,$str1);
        }
        $sqlstr = join($arr,",");

        $sql = "INSERT INTO $this->tb ($colstr) VALUES $sqlstr";
        $res = $this->pdo->prepare($sql);

        for($i = 0;$i<count($arr);$i++){
          $row1 = $arr[$i];
          foreach($row1 as $key => $value){
            $res->bindParam(($i."-".$key),$row1[$key]);
          }
        }

        return $res->execute();

      }
    }

    public function limit($col="*",$start=0,$num=5,$sel=null,$val=null){
      if(!empty($sel) && !empty($val)){
        return $this->pdo->query("SELECT $col FROM $this->tb WHERE $sel = '$val' LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
      }else{
        return $this->pdo->query("SELECT $col FROM $this->tb LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
      }
    }

    public function limit_more($col="*",$start=0,$num=5,$selobj=null){
      if(empty($selobj)){
        return $this->pdo->query("SELECT $col FROM $this->tb LIMIT $start,$num")->fetchAll(PDO::FETCH_ASSOC);
        // return 123;
      }else{
        $str = $this->objtosql($selobj);
        $sql = "SELECT $col FROM $this->tb WHERE $str LIMIT $start,$num";
        // $res = $this->pdo->query($sql);
        $res = $this->pdo->prepare($sql);
        foreach($selobj as $key => $val){
          $res->bindParam(":".$key,$selobj[$key]);
        }
        $res->execute();
        return $res->fetchAll(PDO::FETCH_ASSOC);
        // return $res;
      }
    }

    public function query($sql){
      return $this->pdo->query($sql);
    }

    public function prepare($sql){
      return $this->pdo->prepare($sql);
    }

    private function objtoarr($obj){
      $newarr = [];
      foreach($obj as $key=>$val){
        array_push($newarr,$val);
      }
      return $newarr;
    }

    private function objtostr($obj){
      $arr = [];
      foreach($obj as $key => $val){
        array_push($arr,($key."= :".count($arr)." "));
      }
      $text = join($arr,",");
      return $text;
    }

    private function arrtostr($arr){
      $newarr = [];
      for($i=0;$i<count($arr);$i++){
        array_push($newarr,":".$i);
      }
      return join($newarr,",");
    }

    private function objtosql($obj){
      $newarr = [];
      foreach($obj as $key => $val){
        $child = $key.' = :'.$key;
        // $child = $key." = '".$val."'";
        array_push($newarr,$child);
      }
      return join($newarr," AND ");
    }

    public function getDate($time){
      $date = date("Y-m-d",$time);
      return $date;
    }

    public function set_token($user_name)
    {
        $information ['state'] = false;

        $time = time();
        $header = array(
            'typ' => 'JWT'
        );
        $array = array(
            'iss' => 'wanted', // 权限验证作者
            'iat' => $time, // 时间戳
            'exp' => 7200, // token有效期，1小时
            'sub' => 'demo', // 案例
            'user_name' => $user_name
        ) // 用户名
        ;
        $str = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($array)); // 数组转成字符
        $str = urlencode($str); // 通过url转码
        $information ['token'] = $str;
        $information ['username'] = $user_name; // 返回用户名
        $information ['state'] = true;

        return $information;
    }

    public static function setToken($userid){
      $admin = $userid;
      $time = time();
      $end_time =time()+43200;
      $info = $admin.".".$time.".".$end_time;
      $signature = hash_hmac('md5',$info,'wanted');
      $token =$info.".".$signature;
      return base64_encode($token);
    }


    public function check_token($token)
    {
      $token = base64_decode($token);
      /**** api传来的token ****/
      if(!isset($token) || empty($token))
      {
          $msg['code']='400';
          $msg['msg']='非法请求';
          return json_encode($msg,JSON_UNESCAPED_UNICODE);
      }
      else{
        //对比token
        $explode = explode('.',$token);//以.分割token为数组
        if(!empty($explode[0]) && !empty($explode[1]) && !empty($explode[2]) && !empty($explode[3]) )
        {
            $info = $explode[0].'.'.$explode[1].'.'.$explode[2];//信息部分
            $true_signature = hash_hmac('md5',$info,'wanted');//正确的签名
            if(time() > $explode[2])
            {
                $msg['code']='401';
                $msg['msg']='Token已过期,请重新登录';
                return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }
            if ($true_signature == $explode[3])
            {
                $msg['code']='200';
                $msg['msg']='Token合法';
                $newarr = explode(".",$info);
                $msg['info']=$newarr;
                return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }
            else
            {
                $msg['code']='400';
                $msg['msg']='Token不合法';
                return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            $msg['code']='400';
            $msg['msg']='Token不合法';
            return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
      }

    }

  }

  $conn = new Link("nav");



?>