"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6617],{6617:(e,t,o)=>{o.r(t),o.d(t,{default:()=>f});var a=o(70821),r={class:"grid"},s={class:"col-12"},n={class:"card"},i=(0,a.createElementVNode)("h4",null,"Laporan Request Divisi User Per Status",-1),l=(0,a.createTextVNode)(" Loading data. Please wait. "),u=(0,a.createTextVNode)(" Not Found "),c={class:"table-header p-text-left"},d={class:"p-grid p-dir-col"},h={class:"p-col"},m={class:"box"};const p={data:function(){var e=this;return{statusUser:null,status:[],loading:!1,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-div-user-per-status-pdf/"+e.statusUser)}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-div-user-per-status-excel/"+e.statusUser)}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Divisi User Per Status")||e.checkto.includes("/report-div-user-per-status")?e.getStatus():e.$router.push("/access")})):this.$router.push("/login")},getStatus:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.status=t.data.grafik1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getStatusDivisiUser:function(){var e=this;null!=this.statusUser&&(this.loading=!0,this.axios.get("api/count-per-divuser-status/"+this.statusUser,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data,e.loading=!1})))}}};const f=(0,o(83744).Z)(p,[["render",function(e,t,o,p,f,v){var g=(0,a.resolveComponent)("Toast"),k=(0,a.resolveComponent)("ConfirmDialog"),C=(0,a.resolveComponent)("Toolbar"),V=(0,a.resolveComponent)("Dropdown"),w=(0,a.resolveComponent)("Column"),x=(0,a.resolveComponent)("SplitButton"),N=(0,a.resolveComponent)("DataTable");return(0,a.openBlock)(),(0,a.createElementBlock)("div",r,[(0,a.createElementVNode)("div",s,[(0,a.createElementVNode)("div",n,[(0,a.createVNode)(g),(0,a.createVNode)(k),(0,a.createVNode)(C,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[i]})),_:1}),(0,a.createVNode)(N,{value:f.req,rows:25,rowHover:!0,responsiveLayout:"scroll",loading:f.loading,stripedRows:""},(0,a.createSlots)({loading:(0,a.withCtx)((function(){return[l]})),empty:(0,a.withCtx)((function(){return[u]})),header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",c,[(0,a.createVNode)(V,{onChange:t[0]||(t[0]=function(e){return v.getStatusDivisiUser()}),showClear:!0,modelValue:f.statusUser,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.statusUser=e}),options:f.status,optionValue:"code",optionLabel:"name",placeholder:"Pilih Status"},null,8,["modelValue","options"])])]})),default:(0,a.withCtx)((function(){return[f.statusUser?((0,a.openBlock)(),(0,a.createBlock)(w,{key:0,field:"div_name",header:"Divisi User",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0),f.statusUser?((0,a.openBlock)(),(0,a.createBlock)(w,{key:1,field:"jumlah",header:"Jumlah Request",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0)]})),_:2},[f.statusUser?{name:"footer",fn:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",d,[(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("div",m,[(0,a.createVNode)(x,{label:"Print",model:f.items},null,8,["model"])])])])]}))}:void 0]),1032,["value","loading"])])])])}]])}}]);