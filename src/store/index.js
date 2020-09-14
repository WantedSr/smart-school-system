import Vue from 'vue'
import Vuex from 'vuex'
import router from '../router';
import {requestAjax} from "network/request_ajax";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: localStorage.getItem('Authorization') ? localStorage.getItem('Authorization') : '',
    username: localStorage.getItem("username") ? localStorage.getItem("username") : "",
    isLogin: localStorage.getItem('Authorization') ? true : false,
    userGroup: "",
    userId: "",
    userSchool: "",
    userCampus: "",
    userDepartment: "",
    userAuthority: "",
    userClass: "",

    semester: "",
    semester_start: "",
    school_name: "",
    campus_name: "",

    semester: "",
    week: "",

    endUrl: "http://localhost:9092",
    // endUrl: "http://192.144.220.15:9092",
  },
  mutations: {
    setToken(state,msg){
      state.isLogin = true;
      localStorage.setItem('Authorization',msg);
      state.token = msg;
    },
    logout(state){      
      state.isLogin = false;
      localStorage.clear();
    },
    handleUsername(state,name){
      localStorage.setItem("username",name);
      state.username = name;
    },
    getUser(state){
      if(state.token != "" || state.token != null){
        if(state.userId == ''){
          requestAjax({
            type: 'get',
            url: "/userStatus.php",
            data:{
              token: this.state.token,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res['code'] != 200){
                console.log(res);
                store.commit("logout");
                router.replace('/login');
                return;
              }
              let usergroup = res['info']['user_group'];
              state.userGroup = usergroup;
              state.userSchool = res['info']['user_school'];
              state.userCampus = res['info']['user_campus'];
              state.userDepartment = res['info']['user_department'];
              state.userId = res['info']['user_id'];
              state.semester = res['info']['semester'];
              state.semester_start = res['info']['semester_start'];
              state.school_name = res['info']['school_name'];
              state.campus_name = res['info']['campus_name'];
              state.userAuthority = JSON.parse(res['info']['authority'] ? res['info']['authority'] : null);
              state.userClass = res['info']['class'];
              // console.log(res['info']);
              let $old = state.semester_start;
              let $new = new Date().getTime();
              let $zhou = parseInt(($new-$old)/1000/60/60/24/7 + 1);

              state.week = $zhou;
            },
            error:(err)=>{
              store.commit('logout');
              router.replace('/login');
              console.log(err);
            }
          })
        }
        else{
          return;
        }
      }
    },
    getUserAuthority(state,group){
      requestAjax({
        type: "get",
        url: "/system/authority.php",
        async: false,
        data:{
          type: 'sel_authority',
          col: "*",
          selobj: {
            authority_id: group,
          },
        },
        success:(res)=>{
          res = JSON.parse(res)[0];
          // console.log(res);
          state.userAuthority = JSON.parse(res['authority_range']);
        },
        error:(err)=>{
          store.commit('logout');
          router.replace('/login');
          console.log(err);
        }
      })
    },
    writeLog(state,arr){
      requestAjax({
        type: "get",
        url: "/system/systemLog.php",
        data:{
          type: 'add_log',
          arr: arr,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          if(res){
            return;
          }else{
            console.log("日志填写失败！");
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器出错，请稍后再试！',
            duration: 0,
          });
        }
      });
    },
  },
  getters:{
  },
  actions: {
  },
  modules: {
  }
})
