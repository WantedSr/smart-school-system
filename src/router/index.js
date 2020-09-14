import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import {requestAjax} from "network/request_ajax";

Vue.use(VueRouter);

const Login = ()=>import("views/Login/main");

// ----------------------------student--------------------------------
const Student = ()=>import("views/Student/main");
const StudentHome = ()=>import("views/Student/Student_Home/main");
const StudentSetting = ()=>import("views/Student/Student_Setting/main");
const StudentMessage = ()=>import("views/Student/Student_Message/main");
const StuMessContent = ()=>import("views/Student/Student_Message/MessContent.vue");
const StudentReward = ()=>import("views/Student/Student_Reward/main");
const StudentGrowing = ()=>import("views/Student/Student_Growing/main");
const StudentGraduation = ()=>import("views/Student/Student_Graduation/main");
const StudentCertificate = ()=>import("views/Student/Student_Certificate/main");
const StudentToday = ()=>import("views/Student/Student_Today/main");

// ---------------------------------------management---------------------------
const Management = ()=>import("views/Management/main");
const ManagementBackStage = ()=>import("views/Management/Backstage/main");

// ---------------------------------------teacher---------------------------
const Teacher = ()=>import("views/Teacher/main");
const TeacherHome = ()=>import("views/Teacher/Teacher_Home/main");
const TeacherMessage = ()=>import("views/Teacher/Teacher_Message/main");
const TeacherSetting = ()=>import("views/Teacher/Teacher_Setting/main"); 
const TeaMessContent = ()=>import("views/Teacher/Teacher_Message/MessContent");
const TeacherClassTable = ()=>import('views/Teacher/Teacher_ClassTable/main');
const TeacherToday = ()=>import("views/Teacher/Teacher_Today/main");

  // ---------------------------------------Features------------------------------
  const Commonly_Attendance = ()=>import("views/Management/Features/Commonly/Attendance/main");
      const Commonly_Attendance_Register = ()=>import("views/Management/Features/Commonly/Attendance/Register");
      const Commonly_Attendance_EarlyRegister = ()=>import("views/Management/Features/Commonly/Attendance/EarlyRegister");
      const Commonly_Attendance_BetweenRegister = ()=>import("views/Management/Features/Commonly/Attendance/BetweenRegister");
      const Commonly_Attendance_ClassroomHealth = ()=>import("views/Management/Features/Commonly/Attendance/ClassroomHealth");
      const Commonly_Attendance_BlackBoard = ()=>import("views/Management/Features/Commonly/Attendance/BlackBoard");
      const Commonly_Attendance_StuHealth = ()=>import("views/Management/Features/Commonly/Attendance/StuHealth");

  const Commonly_ClassTable = ()=>import("views/Management/Features/Commonly/ClassTable/main");
      const Commonly_ClassTable_TeacherClass = ()=>import("views/Management/Features/Commonly/ClassTable/TeacherClass");
      const Commonly_ClassTable_BuildClass = ()=>import("views/Management/Features/Commonly/ClassTable/BuildClass");
      const Commonly_ClassTable_MoreClass = ()=>import("views/Management/Features/Commonly/ClassTable/MoreClass");
      const Commonly_ClassTable_ClassroomClass = ()=>import("views/Management/Features/Commonly/ClassTable/ClassroomClass");
      const Commonly_ClassTable_WorkRest = ()=>import("views/Management/Features/Commonly/ClassTable/WorkRest");

  const Commonly_Dormroom = ()=>import("views/Management/Features/Commonly/Dormroom/main");
      const Commonly_Dormroom_Recording = ()=>import("views/Management/Features/Commonly/Dormroom/Recording");

  const Commonly_HomeWork = ()=>import("views/Management/Features/Commonly/HomeWork/main");
      const Commonly_HomeWork_Layout = ()=>import("views/Management/Features/Commonly/HomeWork/Layout");
      const Commonly_HomeWork_Register = ()=>import("views/Management/Features/Commonly/HomeWork/Register");
      const Commonly_HomeWork_SelHW = ()=>import("views/Management/Features/Commonly/HomeWork/SelHW");
      const Commonly_HomeWork_AddRegister = ()=>import("views/Management/Features/Commonly/HomeWork/AddRegister");
      const Commonly_HomeWork_SelLayout = ()=>import("views/Management/Features/Commonly/HomeWork/SelLayout/main");
      const Commonly_HomeWork_SelSubmit = ()=>import("views/Management/Features/Commonly/HomeWork/SelSubmit");
      const Commonly_HomeWork_SelNoSubmit = ()=>import("views/Management/Features/Commonly/HomeWork/SelNoSubmit");
      const Commonly_HomeWork_SelStuHW = ()=>import("views/Management/Features/Commonly/HomeWork/SelStuHW");
      const Commonly_HomeWork_SelTeaHW = ()=>import("views/Management/Features/Commonly/HomeWork/SelTeaHW");

  const Commonly_WeekSchool = ()=>import("views/Management/Features/Commonly/WeekSchool/main");
      const Commonly_WeekSchool_HeadTeacher = ()=>import("views/Management/Features/Commonly/WeekSchool/HeadTeacher/main");
      const Commonly_WeekSchool_Dormitory = ()=>import("views/Management/Features/Commonly/WeekSchool/Dormitory/main");
      const Commonly_WeekSchool_DepartmentCheck = ()=>import("views/Management/Features/Commonly/WeekSchool/DepartmentCheck/main");
      const Commonly_WeekSchool_OfficeCheck = ()=>import("views/Management/Features/Commonly/WeekSchool/OfficeCheck/main");
      const Commonly_WeekSchool_LeaderCheck = ()=>import("views/Management/Features/Commonly/WeekSchool/LeaderCheck/main");
      const Commonly_WeekSchool_SelReport = ()=>import("views/Management/Features/Commonly/WeekSchool/SelReport/main");

  const Commonly_Message = ()=>import("views/Management/Features/Commonly/Message/main");
      const Commonly_Message_SendMessage = ()=>import("views/Management/Features/Commonly/Message/SendMessage/main");
      const Commonly_Message_SelMessage = ()=>import("views/Management/Features/Commonly/Message/SelMessage/main");
  
  const Commonly_Affairs = ()=>import("views/Management/Features/Commonly/Affairs/main");
      const Commonly_Affairs_Register = ()=>import("views/Management/Features/Commonly/Affairs/Register/main");
      const Commonly_Affairs_SelAffair = ()=>import("views/Management/Features/Commonly/Affairs/SelAffair/main");

  const Commonly_SecondInspection = ()=>import("views/Management/Features/Commonly/SecondInspection/main");
      const Commonly_SecondInspection_Political = ()=>import("views/Management/Features/Commonly/SecondInspection/Political/main");
      const Commonly_SecondInspection_Education = ()=>import("views/Management/Features/Commonly/SecondInspection/Education/main");
      const Commonly_SecondInspection_Practice = ()=>import("views/Management/Features/Commonly/SecondInspection/Practice/main");
      const Commonly_SecondInspection_PracticePlace = ()=>import("views/Management/Features/Commonly/SecondInspection/PracticePlace/main");
      const Commonly_SecondInspection_Skill = ()=>import("views/Management/Features/Commonly/SecondInspection/Skill/main");
      const Commonly_SecondInspection_Contest = ()=>import("views/Management/Features/Commonly/SecondInspection/Contest/main");
      const Commonly_SecondInspection_Record = ()=>import("views/Management/Features/Commonly/SecondInspection/Record/main");


  const Teach_ClassSchedule = ()=>import("views/Management/Features/TeachSet/ClassSchedule/main");
  const Teach_TeacherSchedule = ()=>import("views/Management/Features/TeachSet/TeacherSchedule/main");

  const Teach_Leave = ()=>import("views/Management/Features/TeachSet/Leave/main");
      const Teach_Leave_AlterSel = ()=>import("views/Management/Features/TeachSet/Leave/AlterSel");
      const Teach_Leave_AlterClass = ()=>import("views/Management/Features/TeachSet/Leave/AlterClass");

  const Teach_Patrol = ()=>import("views/Management/Features/TeachSet/Patrol/main");
      const Teach_Patrol_RegisterPatrol = ()=>import("views/Management/Features/TeachSet/Patrol/RegisterPatrol/main");
      const Teach_Patrol_SelPatrol = ()=>import("views/Management/Features/TeachSet/Patrol/SelPatrol/main");
      const Teach_Patrol_ModificationPatrol = ()=>import("views/Management/Features/TeachSet/Patrol/ModificationPatrol/main");
      const Teach_Patrol_OptionSet = ()=>import("views/Management/Features/TeachSet/Patrol/OptionSet/main");

  const Teach_Evaluation = ()=>import("views/Management/Features/TeachSet/Evaluation/main");
      const Teach_Evaluation_SelEvaluation = ()=>import("views/Management/Features/TeachSet/Evaluation/SelEvaluation");
      const Teach_Evaluation_AddEvaluation = ()=>import("views/Management/Features/TeachSet/Evaluation/AddEvaluation");
      const Teach_Evaluation_ReportEvaluation = ()=>import("views/Management/Features/TeachSet/Evaluation/ReportEvaluation");
      const Teach_Evaluation_AttendClass = ()=>import("views/Management/Features/TeachSet/Evaluation/AttendClass");
  
  const Teach_ClassCourse = ()=>import('views/Management/Features/TeachSet/ClassCourse/main');
  const Teach_SemesterCourse = ()=>import('views/Management/Features/TeachSet/SemesterCourse/main');
  const Teach_SemesterClass = ()=>import("views/Management/Features/TeachSet/SemesterClass/main");
  const Teach_HeadTeacher = ()=>import("views/Management/Features/TeachSet/HeadTeacher/main");

  const Dormitory_Build = ()=>import("views/Management/Features/Dormitory/Build/main");

  const Dormitory_DormAgm = ()=>import("views/Management/Features/Dormitory/DormAgm/main");
      const Dormitory_DormAgm_HousemasterArrangement = ()=>import("views/Management/Features/Dormitory/DormAgm/HousemasterArrangement/main");
      const Dormitory_DormAgm_StuArrangement = ()=>import("views/Management/Features/Dormitory/DormAgm/StuArrangement/main");

  const Dormitory_DormMgm = ()=>import("views/Management/Features/Dormitory/DormMgm/main");

  const Dormitory_Patrol = ()=>import("views/Management/Features/Dormitory/Patrol/main");
      const Dormitory_Patrol_AddRecording = ()=>import("views/Management/Features/Dormitory/Patrol/AddRecording");
      const Dormitory_Patrol_Happening = ()=>import("views/Management/Features/Dormitory/Patrol/Happening");
      const Dormitory_Patrol_SelDormroom = ()=>import("views/Management/Features/Dormitory/Patrol/SelDormroom");
      const Dormitory_Patrol_AlterRecording = ()=>import("views/Management/Features/Dormitory/Patrol/AlterRecording/main");
      const Dormitory_Patrol_BreachDiscipline = ()=>import('views/Management/Features/Dormitory/Patrol/BreachDiscipline/main');

  const Finance_Wage = ()=>import("views/Management/Features/Finance/Wage/main");
  const Finance_Revenue = ()=>import("views/Management/Features/Finance/Revenue/main");

  const Property_Borrow = ()=>import('views/Management/Features/Property/Borrow/main');
  const Property_Purchase = ()=>import('views/Management/Features/Property/Purchase/main');
  const Property_Scrapped = ()=>import('views/Management/Features/Property/Scrapped/main');
  const Property_Asset_Type = ()=>import('views/Management/Features/Property/AssetType/main');
  const Property_Scrapped_Type = ()=>import('views/Management/Features/Property/ScrappedType/main');

  const Office_Initiated = ()=>import("views/Management/Features/Office/Initiated/main");
  const Office_Form = ()=>import("views/Management/Features/Office/Form/main");
  const Office_Approval = ()=>import("views/Management/Features/Office/Approval/main");
      const Office_Approval_Annex = ()=>import("views/Management/Features/Office/Approval/Annex/main");
      const Office_Approval_Monitoring = ()=>import("views/Management/Features/Office/Approval/Monitoring/main");
      const Office_Approval_MyApproval = ()=>import("views/Management/Features/Office/Approval/MyApproval/main");
      const Office_Approval_MyWork = ()=>import("views/Management/Features/Office/Approval/MyWork/main");
      const Office_Approval_MyInitiate = ()=>import("views/Management/Features/Office/Approval/MyInitiate/main");

  const Office_Process = ()=>import("views/Management/Features/Office/Process/main");
      const Office_Process_ApprovalProcess = ()=>import("views/Management/Features/Office/Process/ApprovalProcess/main");
      const Office_Process_ContainOption = ()=>import("views/Management/Features/Office/Process/ContainOption/main");
      const Office_Process_ExpenditureType = ()=>import("views/Management/Features/Office/Process/ExpenditureType/main");
      const Office_Process_Organization = ()=>import("views/Management/Features/Office/Process/Organization/main");


  const TeaSet_OnBoarding = ()=>import("views/Management/Features/TeaSet/OnBoarding/main");
  const TeaSet_PerformanceReport = ()=>import("views/Management/Features/TeaSet/PerformanceReport/main");
  const TeaSet_PerformanceSummary = ()=>import("views/Management/Features/TeaSet/PerformanceSummary/main");
  const TeaSet_Mine = ()=>import("views/Management/Features/TeaSet/Mine/main");
      const TeaSet_Mine_SelEducation = ()=>import("views/Management/Features/TeaSet/Mine/SelEducation");
      const TeaSet_Mine_AddEducation = ()=>import("views/Management/Features/TeaSet/Mine/AddEducation");
      const TeaSet_Mine_SelQualification = ()=>import("views/Management/Features/TeaSet/Mine/SelQualification");
      const TeaSet_Mine_AddQualification = ()=>import("views/Management/Features/TeaSet/Mine/AddQualification");
  
  const TeaSet_TeacherInfo = ()=>import("views/Management/Features/TeaSet/TeacherInfo/main");
      const TeaSet_TeacherInfo_TeacherLibrary = ()=>import("views/Management/Features/TeaSet/TeacherInfo/TeacherLibrary/main");
      const TeaSet_TeacherInfo_TeacherEducation = ()=>import("views/Management/Features/TeaSet/TeacherInfo/TeacherEducation/main");
      const TeaSet_TeacherInfo_TeacherQualifications = ()=>import("views/Management/Features/TeaSet/TeacherInfo/TeacherQualifications/main");
      const TeaSet_TeacherInfo_ArrangementDepartment = ()=>import("views/Management/Features/TeaSet/TeacherInfo/ArrangementDepartment/main");

  const StuSet_Attendance = ()=>import("views/Management/Features/StuSet/Attendance/main");  
      const StuSet_Attendance_OverView = ()=>import("views/Management/Features/StuSet/Attendance/OverView");
      const StuSet_Attendance_Today = ()=>import("views/Management/Features/StuSet/Attendance/Today");
      const StuSet_Attendance_ClassToday = ()=>import("views/Management/Features/StuSet/Attendance/ClassToday");
      const StuSet_Attendance_Report = ()=>import("views/Management/Features/StuSet/Attendance/Report");
      const StuSet_Attendance_SelWork = ()=>import("views/Management/Features/StuSet/Attendance/SelWork");
      const StuSet_Attendance_FullAttendance = ()=>import("views/Management/Features/StuSet/Attendance/FullAttendance");
      const StuSet_Attendance_AddRegister = ()=>import("views/Management/Features/StuSet/Attendance/AddRegister");
      const StuSet_Attendance_Alter = ()=>import("views/Management/Features/StuSet/Attendance/Alter");
      const StuSet_Attendance_AlterClass = ()=>import("views/Management/Features/StuSet/Attendance/AlterClass");
      const StuSet_Attendance_AttendanceOption = ()=>import("views/Management/Features/StuSet/Attendance/AttendanceOption/AttendanceOption");
      const StuSet_Attendance_HealthModel = ()=>import("views/Management/Features/StuSet/Attendance/HealthModel/HealthModel");
      const StuSet_Attendance_HealthOption = ()=>import("views/Management/Features/StuSet/Attendance/HealthOption/HealthOption");

  const StuSet_Placement = ()=>import("views/Management/Features/StuSet/Placement/main");

  const StuSet_ScoreReport = ()=>import("views/Management/Features/StuSet/ScoreReport/main");
      const StuSet_ScoreReport_Report = ()=>import("views/Management/Features/StuSet/ScoreReport/Report");
      const StuSet_ScoreReport_SelReport = ()=>import("views/Management/Features/StuSet/ScoreReport/SelReport");

  const StuSet_ScoreSummary = ()=>import("views/Management/Features/StuSet/ScoreSummary/main");
      const StuSet_ScoreSummary_SelScore = ()=>import("views/Management/Features/StuSet/ScoreSummary/SelScore");
      const StuSet_ScoreSummary_SelStudent = ()=>import("views/Management/Features/StuSet/ScoreSummary/SelStudent");

  const StuSet_StuInfo = ()=>import("views/Management/Features/StuSet/StuInfo/main");
      const StuSet_StuInfo_Library = ()=>import("views/Management/Features/StuSet/StuInfo/StuLibrary/main");
      const StuSet_StuInfo_Reward = ()=>import("views/Management/Features/StuSet/StuInfo/StuReward/main");
      const StuSet_StuInfo_Status = ()=>import("views/Management/Features/StuSet/StuInfo/StuStatus/main");
      const StuSet_StuInfo_Score = ()=>import("views/Management/Features/StuSet/StuInfo/StuScore/main");

  const StuSet_Certificate = ()=>import("views/Management/Features/StuSet/Certificate/main");
      const StuSet_Certificate_StuCertificate = ()=>import("views/Management/Features/StuSet/Certificate/StuCertificate/main");
      const StuSet_Certificate_CertificateType = ()=>import("views/Management/Features/StuSet/Certificate/CertificateType/main");
      
  const StuSet_Rewards = ()=>import("views/Management/Features/StuSet/Rewards/main");
      const StuSet_Rewards_List = ()=>import("views/Management/Features/StuSet/Rewards/List/main");
      const StuSet_Rewards_Score = ()=>import("views/Management/Features/StuSet/Rewards/Score/main");


  const Base_Build = ()=>import("views/Management/Features/Base/Build/main");
  const Base_Class = ()=>import("views/Management/Features/Base/Class/main");
  const Base_ClassRoom = ()=>import("views/Management/Features/Base/ClassRoom/main");
  const Base_Course = ()=>import("views/Management/Features/Base/Course/main");
  const Base_Department = ()=>import("views/Management/Features/Base/Department/main");
  const Base_Grade = ()=>import("views/Management/Features/Base/Grade/main");
  const Base_School = ()=>import("views/Management/Features/Base/School/main");
  const Base_Semester = ()=>import("views/Management/Features/Base/Semester/main");
  const Base_Skill = ()=>import("views/Management/Features/Base/Skill/main");
  const Base_Schedule = ()=>import("views/Management/Features/Base/Schedule/main");
  const Base_CourseCredit = ()=>import("views/Management/Features/Base/CourseCredit/main");
  const Base_Campus = ()=>import("views/Management/Features/Base/Campus/main");

  const System_Authority = ()=>import("views/Management/Features/System/Authority/main");
  const System_Menu = ()=>import("views/Management/Features/System/Menu/main");
  const System_User = ()=>import("views/Management/Features/System/User/main");
  const System_SystemLog = ()=>import("views/Management/Features/System/SystemLog/main");

  const Other_DataCenter = ()=>import("views/Other/DataCenter/main");
      const Other_DataCenter_ClassSummary = ()=>import("views/Other/DataCenter/ClassSummary/main");
      const Other_DataCenter_DormitorySummary = ()=>import("views/Other/DataCenter/DormitorySummary/main");
      const Other_DataCenter_HomeworkSummary = ()=>import("views/Other/DataCenter/HomeworkSummary/main");
      const Other_DataCenter_SystemSummary = ()=>import("views/Other/DataCenter/SystemSummary/main");

  const Other_Help = ()=>import("views/Other/Help/main");

const routes = [
  {
    path: "/",
    redirect: "/login",
  },
  {
    path: "/login",
    component: Login,
    name: "Login",
  },
  {
    path: "/student",
    component: Student,
    name: "Student",
    children:[
      {
        path: "/student",
        redirect: "/student/home",
      },
      {
        path: "home",
        component: StudentHome,
      },
      {
        path: "setting",
        component: StudentSetting,
      },
      {
        path: "message",
        component: StudentMessage,
      },
      {
        path: "today",
        component: StudentToday,
      },
      {
        path: "reward",
        component: StudentReward,
      },
      {
        path: "growing",
        component: StudentGrowing,
      },
      {
        path: "graduation",
        component: StudentGraduation,
      },
      {
        path: "certificate",
        component: StudentCertificate,
      },
      {
        path: "message/:messId",
        component: StuMessContent,
      },
    ]
  },

    // -----------------------------teacher---------------------

  {
    path: "/teacher",
    component: Teacher,
    name: "Teacher",
    children: [
      {
        path: "/teacher",
        redirect: "/teacher/home"
      },
      {
        path: "message",
        component: TeacherMessage,
        name: "TeacherMessage",
      },
      {
        path: "message/:id",
        component: TeaMessContent,
      },
      {
        path: "setting",
        component: TeacherSetting,
        name: "TeacherSetting",
      },
      {
        path: "today",
        component: TeacherToday,
        name: "TeacherToday",
      },
      {
        path: "classtable",
        component: TeacherClassTable,
      },
      {
        path: "home",
        component: TeacherHome,
        name: "TeacherHome"
      },
    ],
  },

  {
    path: "/management",
    component: Management,
    name: "Management",
    
    children:[
      {
        path: "/management",
        redirect: "/management/backstage",
      },
      {
        path: "backstage",
        name: "ManagementBackStage",
        component: ManagementBackStage,
      },


      // ---------------------------commonly---------------------

      {
        path: "/management/commonly",
        redirect: "/management/backstage",
      },
          {
            path: "commonly/attendance",
            component: Commonly_Attendance,
            meta: {
              roles: "10002"
            },
            name: "Commonly_Attendance",
            children: [
              {
                path: "/management/commonly/attendance",
                redirect: "/management/commonly/attendance/register",
              },
              {
                path: "register",
                name: "Commonly_Attendance_Register",
                component: Commonly_Attendance_Register,
              },
              {
                path: "early_register",
                name: "Commonly_Attendance_EarlyRegister",
                component: Commonly_Attendance_EarlyRegister,
              },
              {
                path: "between_register",
                name: "BetweenRegister",
                component: Commonly_Attendance_BetweenRegister,
              },
              {
                path: "classroom_health",
                name: "ClassroomHealth",
                component: Commonly_Attendance_ClassroomHealth,
              },
              {
                path: "black_board",
                name: "BlackBoard",
                component: Commonly_Attendance_BlackBoard,
              },
              {
                path: "stu_health",
                name: "StuHealth",
                component: Commonly_Attendance_StuHealth,
              },
            ],
          },
          {
            path: "commonly/classtable",
            component: Commonly_ClassTable,
            name: "Commonly_ClassTable",
            meta: {
              roles: "10001",
            },
            children: [
              {
                path: "/management/commonly/classtable",
                redirect: "/management/commonly/classtable/moreclass",
              },
              {
                path: "moreclass",
                component: Commonly_ClassTable_MoreClass,
                name: "Commonly_ClassTable_MoreClass",
              },
              {
                path: "classroomclass",
                component: Commonly_ClassTable_ClassroomClass,
                name: "Commonly_ClassTable_ClassroomClass",
              },
              {
                path: "teacherclass",
                component: Commonly_ClassTable_TeacherClass,
                name: "Commonly_ClassTable_TeacherClass",
              },
              {
                path: "buildclass",
                component: Commonly_ClassTable_BuildClass,
                name: "Commonly_ClassTable_BuildClass",
              },
              {
                path: "workrest",
                component: Commonly_ClassTable_WorkRest,
                name: "Commonly_ClassTable_WorkRest",
              }
            ]
          },
          {
            path: "commonly/dormroom",
            name: "Commonly_Dormroom",
            component: Commonly_Dormroom,
            meta: {
              roles: "10004",
            },
            children: [
              {
                path: "/management/commonly/dormroom",
                redirect: "/management/commonly/dormroom/recording",
              },
              {
                path: "recording",
                name: "Commonly_Dormroom_Recording",
                component: Commonly_Dormroom_Recording,
              },
            ]
          },
          {
            path: "commonly/homework",
            component: Commonly_HomeWork,
            name: "Commonly_HomeWork",
            meta: {
              roles: "10003",
            },
            children: [
              {
                path: "/management/commonly/homework",
                redirect: "/management/commonly/homework/layout",
              },
              {
                path: "layout",
                component: Commonly_HomeWork_Layout,
                name: "Commonly_HomeWork_Layout",
              },
              {
                path: "register",
                component: Commonly_HomeWork_Register,
                name: "Commonly_HomeWork_Register",
              },
              {
                path: "selhw",
                component: Commonly_HomeWork_SelHW,
                name: "Commonly_HomeWork_SelHW",
              },
              {
                path: "add_register",
                component: Commonly_HomeWork_AddRegister,
                name: "Commonly_HomeWork_AddRegister",
              },
              {
                path: "sel_layout",
                component: Commonly_HomeWork_SelLayout,
                name: "Commonly_HomeWork_SelLayout",
              },
              {
                path: "sel_submit",
                component: Commonly_HomeWork_SelSubmit,
                name: "Commonly_HomeWork_SelSubmit",
              },
              {
                path: "sel_nosubmit",
                component: Commonly_HomeWork_SelNoSubmit,
                name: "Commonly_HomeWork_SelNoSubmit",
              },
              {
                path: "sel_stuhw",
                component: Commonly_HomeWork_SelStuHW,
                name: "Commonly_HomeWork_SelStuHW",
              },
              {
                path: "sel_teahw",
                component: Commonly_HomeWork_SelTeaHW,
                name: "Commonly_HomeWork_SelTeaHW",
              },
            ]
          },
          {
            path: "commonly/week_school",
            name: "WeekSchool",
            component: Commonly_WeekSchool,
            meta:{
              roles: "10004",
            },
            children: [
              {
                path: "/management/commonly/week_school",
                redirect: "/management/commonly/week_school/sel_report",
              },
              {
                path: "head_teacher",
                component: Commonly_WeekSchool_HeadTeacher,
                name: "Commonly_WeekSchooo_HeadTeacher",
              },
              {
                path: "dormitory",
                component: Commonly_WeekSchool_Dormitory,
                name: "Commonly_WeekSchooo_Dormitory",
              },
              {
                path: "department_check",
                component: Commonly_WeekSchool_DepartmentCheck,
                name: "Commonly_WeekSchooo_DepartmentCheck",
              },
              {
                path: "office_check",
                component: Commonly_WeekSchool_OfficeCheck,
                name: "Commonly_WeekSchooo_OfficeCheck",
              },
              {
                path: "leader_check",
                component: Commonly_WeekSchool_LeaderCheck,
                name: "Commonly_WeekSchooo_LeaderCheck",
              },
              {
                path: "sel_report",
                component: Commonly_WeekSchool_SelReport,
                name: "Commonly_WeekSchooo_SelReport",
              },
            ],
          },
          {
            path: "commonly/message",
            name: "Message",
            component: Commonly_Message,
            meta:{
              roles: "10006",
            },
            children: [
              {
                path: "/management/commonly/message",
                redirect: "/management/commonly/message/send_message",
              },
              {
                path: "send_message",
                component: Commonly_Message_SendMessage,
                name: "Commonly_Message_SendMessage",
              },
              {
                path: "sel_message",
                component: Commonly_Message_SelMessage,
                name: "Commonly_Message_SelMessage",
              },
            ],
          },
          {
            path: "commonly/affairs",
            name: "Affairs",
            component: Commonly_Affairs,
            meta:{
              roles: "10007",
            },
            children: [
              {
                path: "/management/commonly/affairs",
                redirect: "/management/commonly/affairs/reg_affair",
              },
              {
                path: "reg_affair",
                component: Commonly_Affairs_Register,
                name: "Commonly_Affairs_Register",
              },
              {
                path: "sel_affair",
                component: Commonly_Affairs_SelAffair,
                name: "Commonly_Affairs_SelAffair",
              },
            ]
          },
          {
            path: "commonly/second_inspection",
            name: "Second_inspection",
            component: Commonly_SecondInspection,
            meta:{
              roles: '10008',
            },
            children: [
              {
                path: "/management/commonly/second_inspection",
                redirect: "/management/commonly/second_inspection/political"
              },
              {
                path: "political",
                component: Commonly_SecondInspection_Political,
                name: "Commonly_SecondInspection_Political"
              },
              {
                path: "education",
                component: Commonly_SecondInspection_Education,
                name: "Commonly_SecondInspection_Education"
              },
              {
                path: "practice",
                component: Commonly_SecondInspection_Practice,
                name: "Commonly_SecondInspection_Practice"
              },
              {
                path: "practice_place",
                component: Commonly_SecondInspection_PracticePlace,
                name: "Commonly_SecondInspection_PracticePlace"
              },
              {
                path: "skill",
                component: Commonly_SecondInspection_Skill,
                name: "Commonly_SecondInspection_Skill"
              },
              {
                path: "contest",
                component: Commonly_SecondInspection_Contest,
                name: "Commonly_SecondInspection_Contest"
              },
              {
                path: "record",
                component: Commonly_SecondInspection_Record,
                name: "Commonly_SecondInspection_Record"
              },
            ],
          },


      // ---------------------------teach---------------------

      {
        path: "/management/teach",
        redirect: "/management/backstage",
      },
          {
            path: "teach/class_schedule",
            component: Teach_ClassSchedule,
            name: "Class_Schedule",
            meta: {
              roles: "10011",
            },
          },
          {
            path: "teach/teacher_schedule",
            component: Teach_TeacherSchedule,
            name: "Teacher_Schedule",
            meta: {
              roles: "10012",
            },
          },
          {
            path: "teach/leave",
            component: Teach_Leave,
            name: "Leave",
            meta: {
              roles: "10013",
            },
            children: [
              {
                path: "/management/teach/leave",
                redirect: "/management/teach/leave/altersel",
              },
              {
                path: "altersel",
                component: Teach_Leave_AlterSel,
                name: "Teach_Leave_AlterSel",
              },
              {
                path: "alterclass",
                component: Teach_Leave_AlterClass,
                name: "Teach_Leave_AlterClass",
              },
            ]
          },
          {
            path: "teach/patrol",
            component: Teach_Patrol,
            name: "Patrol",
            meta: {
              roles: "10014",
            },
            children:[
              {
                path: "/management/teach/patrol",
                redirect: "/management/teach/patrol/register_patrol"
              },
              {
                path: "register_patrol",
                name: "Teach_Patrol_RegisterPatrol",
                component: Teach_Patrol_RegisterPatrol,
              },
              {
                path: "sel_patrol",
                name: "Teach_Patrol_SelPatrol",
                component: Teach_Patrol_SelPatrol,
              },
              {
                path: "option_set",
                name: "Teach_Patrol_OptionSet",
                component: Teach_Patrol_OptionSet,
              },
            ]
          },
          { 
            path: "teach/evaluation",
            component: Teach_Evaluation,
            name: "Evaluation",
            meta: {
              roles: "10015",
            },
            children:[
              {
                path: "/management/teach/evaluation",
                redirect: "/management/teach/evaluation/sel_evaluation"
              },
              {
                path: "sel_evaluation",
                name: "Teach_Evaluation_SelEvaluation",
                component: Teach_Evaluation_SelEvaluation,
              },
              {
                path: "add_evaluation",
                name: "Teach_Evaluation_AddEvaluation",
                component: Teach_Evaluation_AddEvaluation,
              },
              {
                path: "report_evaluation",
                name: "Teach_Evaluation_ReportEvaluation",
                component: Teach_Evaluation_ReportEvaluation,
              },
              {
                path: "attend_class",
                name: "Teach_Evaluation_AttendClass",
                component: Teach_Evaluation_AttendClass,
              },
            ]
          },
          { 
            path: "teach/classCourse",
            component: Teach_ClassCourse,
            name: "ClassCourse",
            meta: {
              roles: "10017",
            },
          },
          { 
            path: "teach/semesterCourse",
            component: Teach_SemesterCourse,
            name: "SemesterCourse",
            meta: {
              roles: "10016",
            },
          },
          { 
            path: "teach/semesterClass",
            component: Teach_SemesterClass,
            name: "SemesterClass",
            meta: {
              roles: "10018",
            },
          },
          {
            path: "teach/head_teacher",
            component: Teach_HeadTeacher,
            name: "HeadTeacher",
            meta:{
              roles: "10019"
            },
          },

        // ---------------------------dormitory---------------------

      {
        path: "/management/dormitory",
        redirect: "/management/home",
      },
          {
            path: "dormitory/dormAgm",
            component: Dormitory_DormAgm,
            name: "dormAgm",
            meta: {
              roles: "10021",
            },
            children: [
              {
                path: '/management/dormitory/dormAgm',
                redirect: "/management/dormitory/dormAgm/housemaster_arrangement",
              },
              {
                path: "housemaster_arrangement",
                component: Dormitory_DormAgm_HousemasterArrangement,
                name: "Dormitory_DormAgm_HousemasterArrangement",
              },
              {
                path: "stu_arrangement",
                component: Dormitory_DormAgm_StuArrangement,
                name: "Dormitory_DormAgm_StuArrangement",
              },
            ]
          },
          {
            path: "dormitory/patrol",
            component: Dormitory_Patrol,
            name: "Patrol",
            meta: {
              roles: "10022",
            },
            children: [
              {
                path: "/management/dormitory/patrol",
                redirect: "/management/dormitory/patrol/happening",
              },
              {
                path: "happening",
                name: "Dormitory_Patrol_Happening",
                component: Dormitory_Patrol_Happening,
              },
              {
                path: "sel_dormroom",
                name: "Dormitory_Patrol_SelDormroom",
                component: Dormitory_Patrol_SelDormroom,
              },
              {
                path: "alter_recording",
                name: "Dormitory_Patrol_AlterRecording",
                component: Dormitory_Patrol_AlterRecording,
              },
              {
                path: "add_recording",
                name: "Dormitory_Patrol_AddRecording",
                component: Dormitory_Patrol_AddRecording,
              },
              {
                path: "breach_discipline",
                name: "Dormitory_Patrol_BreachDiscipline",
                component: Dormitory_Patrol_BreachDiscipline,
              }
            ]
          },
          {
            path: "dormitory/build",
            component: Dormitory_Build,
            name: "Build",
            meta: {
              roles: "10023",
            },
          },
          {
            path: "dormitory/dormMgm",
            component: Dormitory_DormMgm,
            name: "dormMgm",
            meta: {
              roles: "10024",
            },
          },

      // ---------------------------finance---------------------

      {
        path: "/management/finance",
        redirect: "/management/backstage",
      },
          {
            path: "finance/wage",
            name: "Wage",
            component: Finance_Wage,
            meta: {
              roles: "10031",
            },
          },
          {
            path: "finance/revenue",
            name: "Revenue",
            component: Finance_Revenue,
            meta: {
              roles: "10032",
            },
          },

      // ---------------------------property---------------------

      {
        path: "/management/property",
        redirect: "/management/backstage",
      },
          {
            path: "property/purchase",
            name: "Purchase",
            component: Property_Purchase,
            meta: {
              roles: "10041",
            },
          },
          {
            path: "property/borrow",
            name: "Borrow",
            component: Property_Borrow,
            meta: {
              roles: "10042",
            },
          },
          {
            path: "property/scrapped",
            name: "Scrapped",
            component: Property_Scrapped,
            meta: {
              roles: "10043",
            },
          },
          {
            path: "property/asset_type",
            name: "AssetType",
            component: Property_Asset_Type,
            meta: {
              roles: "10044",
            },
          },
          {
            path: "property/scrapped_type",
            name: "ScrappedType",
            component: Property_Scrapped_Type,
            meta: {
              roles: "10045",
            },
          },

      // ---------------------------office---------------------

      {
        path: "/management/office",
        redirect: "/management/backstage",
      },
          {
            path: "office/form",
            name: "From",
            component: Office_Form,
            meta: {
              roles: "10051",
            },
          },
          {
            path: "office/process",
            name: "Process",
            component: Office_Process,
            meta: {
              roles: "10053",
            },
            children:[
              {
                path: "/management/office/process",
                redirect: "/management/office/process/expenditure_type",
              },
              {
                path: "expenditure_type",
                name: "ExpenditureType",
                component: Office_Process_ExpenditureType,
              },
              {
                path: "contain_option",
                name: "ContainOption",
                component: Office_Process_ContainOption,
              },
              {
                path: "organization",
                name: "Organization",
                component: Office_Process_Organization,
              },
              {
                path: "approval_process",
                name: "ApprovalProcess",
                component: Office_Process_ApprovalProcess,
              },
            ]
          },
          {
            path: "office/oa_approval",
            name: "Approval",
            component: Office_Approval,
            meta: {
              roles: "10054",
            },
            children: [
              {
                path: "/management/office/oa_approval",
                redirect: "/management/office/oa_approval/my_work",
              },
              {
                path: "my_work",
                name: "MyWork",
                component: Office_Approval_MyWork,
              },
              {
                path: "my_initiate",
                name: "MyInitiate",
                component: Office_Approval_MyInitiate,
              },
              {
                path: "my_approval",
                name: "MyApproval",
                component: Office_Approval_MyApproval,
              },
              {
                path: "annex",
                name: "Annex",
                component: Office_Approval_Annex,
              },
              {
                path: "monitoring",
                name: "Monitoring",
                component: Office_Approval_Monitoring,
              },
            ]
          },
          {
            path: "office/oa_initiated",
            name: "Initiated",
            component: Office_Initiated,
            meta: {
              roles: "10055",
            },
          },

      // ---------------------------teaset---------------------

      {
        path: "/management/teaset",
        redirect: "/management/backstage",
      },
          {
            path: "teaset/onboarding",
            name: "OnBoarding",
            component: TeaSet_OnBoarding,
            meta: {
              roles: "10061",
            },
          },
          {
            path: "teaset/teacherinfo",
            name: "TeacherInfo",
            component: TeaSet_TeacherInfo,
            meta: {
              roles: "10062",
            },
            children:[
              {
                path: "/management/teaset/teacherinfo",
                redirect: "/management/teaset/teacherinfo/teacher_library",
              },
              {
                path: "teacher_library",
                name: "TeacherLibrary",
                component: TeaSet_TeacherInfo_TeacherLibrary,
              },
              {
                path: "teacher_education",
                name: "TeacherEducation",
                component: TeaSet_TeacherInfo_TeacherEducation,
              },
              {
                path: "teacher_qualifications",
                name: "TeacherQualifications",
                component: TeaSet_TeacherInfo_TeacherQualifications,
              },
              {
                path: "arrangement_department",
                name: "ArrangementDepartment",
                component: TeaSet_TeacherInfo_ArrangementDepartment,
              },
            ]
          },
          {
            path: "teaset/performance_report",
            name: "PerformanceReport",
            component: TeaSet_PerformanceReport,
            meta: {
              roles: "10063",
            },
          },
          {
            path: "teaset/performance_summary",
            name: "PerformanceSummary",
            component: TeaSet_PerformanceSummary,
            meta: {
              roles: "10064",
            },
          },
          {
            path: "teaset/mine",
            name: "Mine",
            component: TeaSet_Mine,
            meta: {
              roles: "10065",
            },
            children:[
              {
                path: "/management/teaset/mine",
                redirect: "/management/teaset/mine/sel_education",
              },
              {
                path: "sel_education",
                name: "SelEducation",
                component: TeaSet_Mine_SelEducation,
              },
              {
                path: "add_education",
                name: "AelEducation",
                component: TeaSet_Mine_AddEducation,
              },
              {
                path: "sel_qualification",
                name: "SelQualification",
                component: TeaSet_Mine_SelQualification,
              },
              {
                path: "add_qualification",
                name: "AddQualification",
                component: TeaSet_Mine_AddQualification,
              },
            ]
          },
    
      // ---------------------------stuset---------------------

      {
        path: "/management/stuset",
        redirect: "/management/backstage",
      },
          {
            path: "stuset/placement",
            name: "Placement",
            component: StuSet_Placement,
            meta: {
              roles: "10071",
            },
          },
          {
            path: "stuset/stuinfo",
            name: "StuInfo",
            component: StuSet_StuInfo,
            meta: {
              roles: "10072",
            },
            children:[
              {
                path: '/management/stuset/stuinfo',
                redirect: "/management/stuset/stuinfo/stu_library",
              },
              {
                path: "stu_library",
                name: "StuLibrary",
                component: StuSet_StuInfo_Library,
              },
              {
                path: "stu_reward",
                name: "StuReward",
                component: StuSet_StuInfo_Reward,
              },
              {
                path: "stu_status",
                name: "StuStatus",
                component: StuSet_StuInfo_Status,
              },
              {
                path: "stu_score",
                name: "StuScore",
                component: StuSet_StuInfo_Score,
              },
            ]
          },
          {
            path: 'stuset/attendance',
            name: "Attendance",
            component: StuSet_Attendance,
            meta: {
              roles: "10073",
            },
            children:[
              {
                path: "/management/stuset/attendance",
                redirect: "/management/stuset/attendance/today",
              },
              {
                path: "today",
                name: "StuSet_Attendance_Today",
                component: StuSet_Attendance_Today,
              },
              {
                path: "class_today",
                name: "StuSet_Attendance_ClassToday",
                component: StuSet_Attendance_ClassToday,
              },
              {
                path: "overview",
                name: "StuSet_Attendance_OverView",
                component: StuSet_Attendance_OverView,
              },
              {
                path: "report",
                name: "StuSet_Attendance_Report",
                component: StuSet_Attendance_Report,
              },
              {
                path: "sel_work",
                name: "StuSet_Attendance_SelWork",
                component: StuSet_Attendance_SelWork,
              },
              {
                path: "full_attendance",
                name: "StuSet_Attendance_FullAttendance",
                component: StuSet_Attendance_FullAttendance,
              },
              {
                path: "add_register",
                name: "StuSet_Attendance_AddRegister",
                component: StuSet_Attendance_AddRegister,
              },
              {
                path: "alter",
                name: "StuSet_Attendance_Alter",
                component: StuSet_Attendance_Alter,
              },
              {
                path: "alter_class",
                name: "StuSet_Attendance_AlterClass",
                component: StuSet_Attendance_AlterClass,
              },
              {
                path: "attendance_option",
                name: "AttendanceOption",
                component: StuSet_Attendance_AttendanceOption,
              },
              {
                path: "health_model",
                name: "HealthModel",
                component: StuSet_Attendance_HealthModel,
              },
              {
                path: "health_option",
                name: "HealthOption",
                component: StuSet_Attendance_HealthOption,
              },
            ]
          },
          {
            path: "stuset/score_report",
            name: "ScoreReport",
            component: StuSet_ScoreReport,
            meta: {
              roles: "10074",
            },
            children:[
              {
                path: "/management/stuset/score_report",
                redirect: "/management/stuset/score_report/report"
              },
              {
                path: "report",
                component: StuSet_ScoreReport_Report,
                name: "StuSet_ScoreReport_Report",
              },
              {
                path: "sel_report",
                component: StuSet_ScoreReport_SelReport,
                name: "StuSet_ScoreReport_SelReport"
              },
            ]
          },
          {
            path: "stuset/score_summary",
            name: "ScoreSummary",
            component: StuSet_ScoreSummary,
            meta: {
              roles: "10075",
            },
            children:[
              {
                path: "/management/stuset/score_summary",
                redirect: "/management/stuset/score_summary/sel_score"
              },
              {
                path: "sel_score",
                component: StuSet_ScoreSummary_SelScore,
                name: "StuSet_ScoreSummary_SelScore",
              },
              {
                path: "sel_student",
                component: StuSet_ScoreSummary_SelStudent,
                name: "StuSet_ScoreSummary_SelStudent"
              },
            ]
          },
          {
            path: "stuset/rewards",
            name: "Rewards",
            component: StuSet_Rewards,
            meta: {
              roles: "10077",
            },
            children:[
              {
                path: "/management/stuset/rewards",
                redirect: "/management/stuset/rewards/list",
              },
              {
                path: "list",
                component: StuSet_Rewards_List,
                name: "StuSet_Rewards_List",
              },
              {
                path: "score",
                component: StuSet_Rewards_Score,
                name: "StuSet_Rewards_Score",
              },
            ]
          },
          {
            path: "stuset/certificate",
            name: "Certificate",
            component: StuSet_Certificate,
            meta: {
              roles: "10076",
            },
            children:[
              {
                path: "/management/stuset/certificate",
                redirect: "/management/stuset/certificate/stu_certificate",
              },
              {
                path: "stu_certificate",
                component: StuSet_Certificate_StuCertificate,
                name: "StuSet_Certificate_StuCertificate",
              },
              {
                path: "certificate_type",
                component: StuSet_Certificate_CertificateType,
                name: "StuSet_Certificate_CertificateType",
              },
            ]
          },

      // ---------------------------base---------------------

      {
        path: "/management/base",
        redirect: "/management/backstage",
      },
          {
            path: "base/semester",
            name: "Semester",
            component: Base_Semester,
            meta: {
              roles: "10081",
            },
          },
          {
            path: "base/grade",
            name: "Grade",
            component: Base_Grade,
            meta: {
              roles: "10082",
            },
          },
          {
            path: "base/skill",
            name: "Skill",
            component: Base_Skill,
            meta: {
              roles: "10083",
            },
          },
          {
            path: "base/course",
            name: "Course",
            component: Base_Course,
            meta: {
              roles: "10084",
            },
          },
          {
            path: "base/build",
            name: "Build",
            component: Base_Build,
            meta: {
              roles: "10085",
            },
          },
          {
            path: "base/classroom",
            name: "ClassRoom",
            component: Base_ClassRoom,
            meta: {
              roles: "10086",
            },
          },
          {
            path: "base/class",
            name: "Class",
            component: Base_Class,
            meta: {
              roles: "10087",
            },
          },
          {
            path: "base/school",
            name: "School",
            component: Base_School,
            meta: {
              roles: "10088",
            },
          },
          {
            path: "base/department",
            name: "Department",
            component: Base_Department,
            meta: {
              roles: "10089",
            },
          },
          {
            path: "base/schedule",
            name: "Schedule",
            component: Base_Schedule,
            meta: {
              roles: "100810",
            },
          },
          {
            path: "base/course_credit",
            name: "CourseCredit",
            component: Base_CourseCredit,
            meta: {
              roles: "100811",
            },
          },
          {
            path: "base/campus",
            name: "Campus",
            component: Base_Campus,
            meta: {
              roles: "100812",
            },
          },

      // ---------------------------system---------------------
          
      {
        path: "/management/system",
        redirect: "/management/backstage",
      },
          {
            path: "system/menu",
            name: "Menu",
            component: System_Menu,
            meta: {
              roles: "10091",
            },
          },
          {
            path: "system/user",
            name: "User",
            component: System_User,
            meta: {
              roles: "10092",
            },
          },
          {
            path: "system/authority",
            name: "Authority",
            component: System_Authority,
            meta: {
              roles: "10093",
            },
          },
          {
            path: "system/system_log",
            name: "SystemLog",
            component: System_SystemLog,
            meta: {
              roles: "10094",
            },
          },
          {
            path: "/teacher/other",
            redirect: "/teacher/backstage",  
          },
    ],
  },



      // ---------------------------other---------------------

  {
    path: "/other",
    redirect: "/management /backstage",
  },
    {
      path: "/other/data_center",
      name: "DataCenter",
      component: Other_DataCenter,
      meta: {
        roles: "10101",
      },
      children:[
        {
          path: "class_summary",
          name: "ClassSummary",
          component: Other_DataCenter_ClassSummary,
        },
        {
          path: "dormitory_summary",
          name: "DormitorySummary",
          component: Other_DataCenter_DormitorySummary,
        },
        {
          path: "homework_summary",
          name: "HomeWorkSummary",
          component: Other_DataCenter_HomeworkSummary,
        },
        {
          path: "system_summary",
          name: "SystemSummary",
          component: Other_DataCenter_SystemSummary,
        },
      ]
    },
    {
      path: "/other/help",
      name: "Help",
      component: Other_Help,
      meta: {
        roles: "10102",
      },
    },
]

// requestAjax({
//   type: "get",
//   url: "/system/menu.php",
//   async: false,
//   data:{
//     reqType: "sel_more_menu",
//     col: "*",
//     sel: 'nav_lever',
//     val: '2',
//   },
//   success:(res)=>{
//     res = JSON.parse(res);
//     let arr = [];
//     $.each(res, (i, v)=>{ 
//       let obj = {
//         path: v.nav_url,

//       }
//     });
//     console.log(res);
//   },
//   error:(err)=>{
//     console.log(err);
//   }
// })

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to,from,next)=>{
  // console.log(to);
  let token = localStorage.getItem("Authorization");
  let login = store.state.isLogin;
  if(login == null || login == "" || login == false){
    if(to.path == "/login"){
      next();
    }else{
      router.replace('/login');
    }
  }else{
    requestAjax({
      type: 'get',
      url: "/userStatus.php",
      data:{
        token: token,
      },
      async: false,
      success:(res)=>{
        res = JSON.parse(res);
        // console.log(res);
        if(res['code'] != 200){
          console.log(res);
          // console.log(store.state.token);
          store.commit("logout");
          router.replace('/login');
          return;
        }
        let usergroup = res['info']['user_group'];
        if(usergroup.indexOf('S') != -1){
          if(to.path.substr(1,7) == 'teacher' || to.path.substr(1,5) == 'other' || to.path.substr(1,5) == "login" || to.path.substr(1,10) == 'management'){
            router.replace("/student");
          }
        }else{
          if(usergroup.indexOf('D') != -1){
            if(to.path.substr(1,7) == 'teacher' || to.path.substr(1,5) == "login" || to.path.substr(1,7) == 'student'){
              router.replace("/management");
            }
          }
          else{
            if(to.path.substr(1,7) == 'student' || to.path.substr(1,5) ==  "login" || to.path.substr(1,5) == 'other'){
              router.replace("/teacher");
            }
          }

          let roles;
          if(to.meta.roles){
            roles = to.meta.roles;
          }else{
            if(to.matched && to.matched[1] && to.matched[1].meta.roles){
              roles = to.matched[1].meta.roles
            }else{
              roles = null;
            }
          }
          if(roles != undefined && roles != null){
            store.commit("getUserAuthority");
            let parent_roles = roles.substr(0,4);
            // console.log(store.state.userAuthority);
            let alone = store.state.userAuthority.filter((item)=>{
              return item.id == parent_roles;
            })
            // if(alone.length == 0){
            //   alert("您没有权限访问!");
            //   router.replace('/teacher');
            //   console.log(1)
            // }
            // if(!alone[0].child.includes(roles)){
            //   alert("您没有权限访问!");
            //   router.replace('/teacher');
            //   console.log(2)
            //   console.log(alone[0])
            // }
          }

        }

        requestAjax({
          type: "get",
          url: "/system/menu.php",
          data:{
            reqType: "sel_more_menu",
            col:"nav_state",
            selobj:{
              nav_url: to.path,
            },
          },
          async: false,
          success:(res2)=>{
            res2 = JSON.parse(res2).length > 0 ? JSON.parse(res2)[0]['nav_state'] : '1';
            // console.log(res2);
            if(res2 == 0){
              alert("该路由已被禁用！");
              router.replace("/login");
            }else{
              return;
            }
          },
          error:(err2)=>{
            console.log(err2);
            alert("服务器有误！");
          }
        })
        

        next();
  
      },
      error:(err)=>{
        console.log(err);
        store.commit('logout');
        router.replace('/login');
      }
    })
  }
  // console.log(to);
  // console.log(from);
});
router.afterEach((to,from)=>{
  // console.log(to);
  // console.log(to.path);
})




export default router
