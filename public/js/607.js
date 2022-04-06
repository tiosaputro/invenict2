"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[607],{30607:(e,t,n)=>{n.r(t),n.d(t,{default:()=>f});var a=n(70821),o={class:"grid"},r={class:"col-12"},i={class:"card"},l=(0,a.createElementVNode)("h4",null,"Laporan Request Divisi User Per Bulan",-1),s=(0,a.createTextVNode)(" Loading data. Please wait. "),u=(0,a.createTextVNode)(" Not Found "),c={class:"table-header p-text-left"},d={class:"p-grid p-dir-col"},h={class:"p-col"},m={class:"box"};const p={data:function(){var e=this;return{bulanUser:null,tahunnUser:null,bulan:[],tahunn:[],loading:!1,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-div-user-per-bulan-pdf/"+e.tahunnUser+"/"+e.bulanUser)}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-div-user-per-bulan-excel/"+e.tahunnUser+"/"+e.bulanUser)}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Divisi User Per Bulan")||e.checkto.includes("/report-div-user-per-bulan")?e.getBulan():e.$router.push("/access")})):this.$router.push("/login")},getBulan:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.bulan=t.data.grafik2})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getTahunUser:function(){var e=this;this.tahunnUser=null,null!=this.bulanUser&&this.axios.get("api/get-tahun-user/"+this.bulanUser,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.tahunn=t.data}))},getPerDivisiUserBulan:function(){var e=this;null!=this.tahunnUser&&null!=this.bulanUser&&(this.loading=!0,this.axios.get("api/count-per-divuser-bulan/"+this.tahunnUser+"/"+this.bulanUser,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data,e.loading=!1})).catch((function(e){return console.log(e.response)})))}},render:function(e,t,n,p,f,g){var v=this,b=(0,a.resolveComponent)("Toast"),k=(0,a.resolveComponent)("ConfirmDialog"),U=(0,a.resolveComponent)("Toolbar"),V=(0,a.resolveComponent)("Dropdown"),C=(0,a.resolveComponent)("Column"),w=(0,a.resolveComponent)("SplitButton"),x=(0,a.resolveComponent)("DataTable");return(0,a.openBlock)(),(0,a.createElementBlock)("div",o,[(0,a.createElementVNode)("div",r,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(b),(0,a.createVNode)(k),(0,a.createVNode)(U,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[l]})),_:1}),(0,a.createVNode)(x,{value:f.req,rows:25,rowHover:!0,responsiveLayout:"scroll",loading:f.loading,stripedRows:""},(0,a.createSlots)({loading:(0,a.withCtx)((function(){return[s]})),empty:(0,a.withCtx)((function(){return[u]})),header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",c,[(0,a.createVNode)(V,{onChange:t[0]||(t[0]=function(e){return g.getTahunUser()}),showClear:!0,modelValue:f.bulanUser,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.bulanUser=e}),options:f.bulan,optionValue:"code",optionLabel:"name",placeholder:"Pilih Bulan",class:"mr-2"},null,8,["modelValue","options"]),v.bulanUser?((0,a.openBlock)(),(0,a.createBlock)(V,{key:0,onChange:t[2]||(t[2]=function(e){return g.getPerDivisiUserBulan()}),showClear:!0,modelValue:f.tahunnUser,"onUpdate:modelValue":t[3]||(t[3]=function(e){return f.tahunnUser=e}),options:f.tahunn,optionValue:"tahun",optionLabel:"tahun",placeholder:"Pilih Tahun",class:"mr-2"},null,8,["modelValue","options"])):(0,a.createCommentVNode)("",!0)])]})),default:(0,a.withCtx)((function(){return[f.tahunnUser?((0,a.openBlock)(),(0,a.createBlock)(C,{key:0,field:"div_name",header:"Divisi User",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0),f.tahunnUser?((0,a.openBlock)(),(0,a.createBlock)(C,{key:1,field:"jumlah",header:"Jumlah Request",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0)]})),_:2},[f.tahunnUser?{name:"footer",fn:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",d,[(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("div",m,[(0,a.createVNode)(w,{label:"Print",model:f.items},null,8,["model"])])])])]}))}:void 0]),1032,["value","loading"])])])])}},f=p}}]);