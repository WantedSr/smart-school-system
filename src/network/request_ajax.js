import $ from 'jquery';
import "network/ajaxfileupload";
let baseUrl = 'http://localhost:9092';
// let baseUrl = "http://192.144.220.15:9092";
function requestAjax(obj){
  // let url =  + obj.url;
  let url = baseUrl+obj.url;
  $.ajax({
    type: obj.type,
    url: url,
    data: obj.data,
    success(e){
      obj.success(e);
    },
    error(err){
      if(obj.error){
        obj.error(err);
      }else{
        console.log(err);
        alert("服务器有误，请稍后再试！");
      }
    },
    async: obj.async == undefined ? true : obj.async,
    // cache: obj.cache ? obj.cache : true,
    // processData: obj.processData ? obj.processData : true,
    // contentType: obj.contentType ? obj.contentType : "application/x-www-form-urlencoded",
    // dataType: obj.dataType,
  })
}

function fileUpload(obj){
  let url = baseUrl+obj.url;
  console.log(url);
  $.ajaxFileUpload({
    url: url,                             //单引号间填写上传处理文件的路径。
    fileElementId: obj.fileElementId,     //单引号间填写上传文件的id。
    secureuri: obj.secureuri,             //单引号间填写的内容表示是否安全提交，默认填写false。
    dataType: obj.dataType,               //单引号间填写经服务器处理之后返回的数据类型，这里有xml,script,json,html,text。
    data: obj.data,                       //单引号间填写填写除文件信息外的其它信息，用json数据格式。
    type: obj.type    ,                   //单引号间填写提交信息是的方法，常用的有post和get。
    success(data,status){
      obj.success(data,status);
    },      
    //参数data为后台处理文件返回的数据，status是返回的状态，这里有error、success、timeout。{}之间是对返回结果的处理。
    error(err,status,e){
      if(obj.error){
        obj.error(err,status,e);
      }else{
        console.log(err);
        console.log(e);
        alert("服务器有误，请稍后再试！");
      }
    }, 
    //data和error参数同上，e是异常信息。
  })
}

export {requestAjax,fileUpload};