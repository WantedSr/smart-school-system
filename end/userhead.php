<?php 

    header('Access-Control-Allow-Origin: *');

    
    
    //定义错误编码与错误信息的对应关系
    $errorInfo=[
      '1001'=>'文件超过php.ini限',
      '1002'=>'文件超过html限制',
      '1003'=>'文件上传不完整',
      '1004'=>'没有选择文件',
      '1005'=>'文件上传成功',
      '1006'=>'服务器内部错误',
      '1007'=>'服务器内部错误',
      '1008'=>'文件太大',
      '1009'=>'文件类型不合法',
      '1010'=>'文件移动失败'
    ];

    $maxsize = 5 * 1024 * 1024;      // 文件上传最大内存
    $mime = ["image/jpeg","","image/png","image/gif"];     // 允许文件mime类型
    $alone_ext = ['jpg','jpeg','png','gif'];      // 允许的文件后缀名
    $path = "./userhead";      // 文件存放路径




    try{
      require_once("./conn.php");
      $conn = new Link("tb_userhead");
    } catch (PDOException $e){
      echo json_encode([
        'msg'=> '服务器错误',
        'error'=> '1000'
      ]);
    }

    $data = [];

    try{
      $action = $_POST['action'] ? $_POST['action'] : null;
      // var_dump();
      switch ($action){
        case 'setUserHead': setUserHead(); break;
        case 'getUserHead': getUserHead(); break;
      }
    } catch (PDOException $e) {
      echo json_encode([
        'msg'=> 'error',
        'error'=> $e->getMessage()
      ]);
    }


    /**
     * 检验文件
     * $file array 文件数组
     * mime string 文件类型
     * maxsize number 文件最大内存
     * path 存储路径
     */
    function uploads($file,$campus,$user){
      global $errorInfo;
      global $maxsize;
      global $mime;
      global $path;
      global $alone_ext;

      $state = '';
      $msg = '';
      $data = '';

      //判断文件上传错误
      switch($file['error']){
        case 1:
          $state = '1001';//'文件超过php.ini限制';
          break;
        case 2:
          $statet = '1002';//'文件超过html限制';
          break;
        case 3:
          $state = '1003';//'文件上传不完整';
          break;
        case 4:
          $state = '1004';//'没有选择文件';
          break;
        case 6:
          $state = '1006';//'服务器内部错误';
          break;
        case 7:
          $state = '1007';//'服务器内部错误';
          break;   
      }
      
      $tmp = $file['tmp_name'];     //获取文件的扩展名
      $ext = pathinfo($file['name'],PATHINFO_EXTENSION);    // 获取文件后缀名
     
      if($file['size'] > $maxsize){
        $state = '1008';                          //文件太大;
      }
     
      //判断用户上传的文件类型是否合法
      if(!in_array($file['type'],$mime)){
        $state = '1009';                        //文件类型不合法;
      }

      if(!in_array($ext,$alone_ext)){
        $state = '1009';
      }

      $fileName = 'smartSchool-'. $campus. '-' . $user;      // 获取随机文件名
      //拼接文件名
      $basename = $fileName.'.'.$ext;
      //拼接路径
      $dest = $path.'/'. $basename;

      //将临时文件夹中的文件，移动到目标位置
      if(move_uploaded_file($tmp,$dest)){
        $data = $basename;
        $state = '1005';
      }else{
        $state = '1010';
      }

      $res = [
        'state'=>$state,
        'msg'=>$errorInfo[$state],
        'data'=>$data,
      ];
      return $res;
    }

    /**
     * 获取所有消息列表
     * $request json 请求内容 required
     */
    function getUserHead(){
      global $conn;
      global $data;
      $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
      
      $data = $conn->select_more("*",[
        'user'=>$request['userid'],
        'campus'=>$request['campus'],
      ]);
    }

    /**
     * 获取所有消息列表
     * $request json 请求内容 required
     */
    function setUserHead(){
      global $conn;
      global $data;

      $request = $_POST['request'] ? (array)json_decode($_POST['request'],true) : null;
      $file = $_FILES['img'];
      $upload = uploads($file,$request['campus'],$request['userid']);
      
      if($upload['data'] != ''){
        $filename = $upload['data'];
        $num = $conn->select_more("COUNT(*)",[
          'user'=>$request['userid'],
          'campus'=>$request['campus'],
        ])[0]['COUNT(*)'];
        if($num > 0){
          $res = $conn->prepare("UPDATE tb_userhead SET 
            user_head = :filename 
            WHERE user = :user 
              AND campus = :campus");
          $res->bindParam(":filename",$filename);
          $res->bindParam(":user",$request['userid']);
          $res->bindParam(":campus",$request['campus']);
          $res = $res->execute();
          if($res){
            $data = [
              'type'=>"success",
              'info'=>$filename,
            ];
          }else{
            $data = [
              'type'=>'fail',
              'info'=>'头像保存失败',
            ];
          }
        }else{
          $head_arr = [
            $request['userid'],
            $filename,
            $request['campus'],
            $request['school'],
            time().'000'
          ];
          $res = $conn->insert($head_arr);
          if($res){
            $data = [
              'type'=>"success",
              'info'=>$filename,
            ];
          }else{
            $data = [
              'type'=>'fail',
              'info'=>'头像保存失败',
            ];
          }
        }
      }else{
        $data = [
          "type"=>"fail",
          'info'=>"头像上传失败"
        ];
        return false;
      }
    }



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


    echo json_encode([
      'msg'=> '成功',
      'error'=> 'null',
      'data'=> $data
    ]);

    

?>