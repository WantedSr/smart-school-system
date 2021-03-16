<!--
 * @Author: your name
 * @Date: 2020-09-05 20:33:10
 * @LastEditTime: 2021-03-16 14:29:45
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \shandong\README.md
-->

![s3.png](https://i.loli.net/2021/03/16/MA2lOFqSex9RuKY.png)  
# <center><font color=#2c3e50>"smart-school-system"</font></center>
#### <center><font color=#34495e>智慧校园管理系统</font></center>
-------------------------
这个是本人在去年（高三）的时候，帮个人写的系统，虽然最后没成（应该是我写的太烂了）  
放着吃灰也是浪费，不如开源可能有的大佬们可以改改用到呢？  
  
我用到的技术栈有：  
**前端**
+ `vue2.x`, `vuex`, `vue-router` 全家桶
+ `jquery`(可惜没早点搞懂`axios`，不然就用上了)，中的`ajax`
+ `ELementUI`
+ `xlsx`
+ `wangeditor`
+ `particles.js`(挺好看的粒子特效)
  
**后端**
+ `php`原生（当时没摄入太多后端知识）
+ `mysql`

功能就是将校园内的各种事务集合于一个系统中，例如对学生的管理，教师的管理，课程，考勤等等  
可惜技术栈和vue用的不成熟，很多地方都造成了资源浪费导致运行速度缓慢（也有因为稚嫩的我当时只一心求快，cv比封装快多了呜呜）  

如果大佬们有使用修改升华一下他的话，可以联系一下我 **2426756650@qq.com** 去学习学习，顺便来个star

运行环境需求：
+ `node`
+ `php`
+ `mysql`

1. 首先现将数据库进行导入：*database/db_aqschool.sql*
```mysql
create database db_aqschool
use db_aqschool
set names utf-8
source /database/db_aqschool.sql
```

2. 安装所需依赖
```node
npm i -S
```

3. 运行

启动服务
```node
npm run serve
```
生成dist
```node
npm run build
```


如果大佬们喜欢，也希望能够给个 **<font color=red>star</font>** 多多照顾一下谢谢  

--------------------------------

#### <center><font color=#34495e>smart-school-system</font></center>