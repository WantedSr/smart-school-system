<?php

  
  header('Access-Control-Allow-Origin: *');

  // require_once("../conn.php");

  // $conn = new Link('commonly_homework');
  // $conn2 = new Link("commonly_homework_register");
  // $conn3 = new Link("tb_message");

  // //调用这个函数，将其幻化为数组，然后取出对应值
  // function objarray_to_array($obj) {
  //   $ret = [];
  //   foreach ($obj as $key => $value) {
  //     if (gettype($value) == "array" || gettype($value) == "object"){
  //       $ret[$key] = objarray_to_array($value);
  //     }else{
  //       array_push($ret,$value);
  //     }
  //   }
  //   return $ret;
  // }


  // $type;
  // if(isset($_GET['type'])){
  //   $type = $_GET['type'];
  // }else if(isset($_POST['type'])){
  //   $type = $_POST['type'];
  // }else{
  //   $type = null;
  // }

  // if(!empty($type)){

  //   // if($type == 'add_annex'){

  //   //   // $myfile=$_FILES["file"];
  //   //   // $describe=$_POST["describe"];
      
      
  //   //   // $fileTypeArray=array(
  //   //   //   'application/vnd.ms-excel',
  //   //   //   'text/plain',
  //   //   //   'application/msword',
  //   //   //   'application/zip',
  //   //   //   'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  //   //   //   'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
  //   //   //   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  //   //   //   'application/octet-stream'
  //   //   // );
  //   //   // $filename=$myfile["name"];
  //   //   // $filetype=$myfile["type"];
  //   //   // $filesize=$myfile["size"];
  //   //   // $fileerror=$myfile["error"];
  //   //   // if(in_array($filetype,$fileTypeArray)){
  //   //   //   //限制大小
  //   //   //   if($filesize<1024*1024*1024){
  //   //   //     if($fileerror>0){
  //   //   //       echo json_encode($myfile);
  //   //   //     }
  //   //   //     else{
  //   //   //       if(file_exists(iconv("UTF-8", "gb2312","../file/".$filename))){//防止中文乱码
          
  //   //   //         $myfile["myallowerror"]=$filename."已经存在!无需上传";
  //   //   //         $url="../file/".$filename;
  //   //   //         $myfile["url"]=$url;
  //   //   //       }
  //   //   //       else{
  //   //   //         move_uploaded_file($myfile["tmp_name"],iconv("UTF-8", "gb2312","../file/".$filename));
  //   //   //         $url="../file/".$filename;
  //   //   //       }
  //   //   //     }
  //   //   //   }
  //   //   // }
  //   //   // else{
  //   //   //   $myfile["myerror"]="不符合的文件类型";
  //   //   // }
  //   //   $res = 1234;
  //   // }
    $res = 123;
    echo json_encode($res);
  // }



?>