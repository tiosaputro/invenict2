"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5508],{75508:(e,t,n)=>{n.r(t),n.d(t,{default:()=>p});var a=n(5166),o={class:"grid"},r={class:"col-12"},i={class:"card"},l=(0,a.createElementVNode)("h4",null,"Laporan Request Divisi User Per Bulan",-1),s=(0,a.createTextVNode)(" Loading data. Please wait. "),u={class:"table-header p-text-left"},c={class:"p-grid p-dir-col"},d={class:"p-col"},h={class:"box"};const m={data:function(){var e=this;return{bulanUser:null,tahunnUser:null,bulan:[],tahunn:[],loading:!1,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-div-user-per-bulan-pdf/"+e.tahunnUser+"/"+e.bulanUser)}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-div-user-per-bulan-excel/"+e.tahunnUser+"/"+e.bulanUser)}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Divisi User Per Bulan")||e.checkto.includes("/report-div-user-per-bulan")?e.getBulan():e.$router.push("/access")})):this.$router.push("/login")},getBulan:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.bulan=t.data.grafik2})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getTahunUser:function(){var e=this;this.tahunnUser=null,null!=this.bulanUser&&this.axios.get("api/get-tahun-user/"+this.bulanUser,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.tahunn=t.data}))},getPerDivisiUserBulan:function(){var e=this;null!=this.tahunnUser&&null!=this.bulanUser&&(this.loading=!0,this.axios.get("api/count-per-divuser-bulan/"+this.tahunnUser+"/"+this.bulanUser,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data,e.loading=!1})).catch((function(e){return console.log(e.response)})))}},render:function(e,t,n,m,p,f){var g=this,v=(0,a.resolveComponent)("Toast"),b=(0,a.resolveComponent)("ConfirmDialog"),k=(0,a.resolveComponent)("Toolbar"),U=(0,a.resolveComponent)("Dropdown"),V=(0,a.resolveComponent)("Column"),C=(0,a.resolveComponent)("SplitButton"),w=(0,a.resolveComponent)("DataTable");return(0,a.openBlock)(),(0,a.createElementBlock)("div",o,[(0,a.createElementVNode)("div",r,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(v),(0,a.createVNode)(b),(0,a.createVNode)(k,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[l]})),_:1}),(0,a.createVNode)(w,{value:p.req,rows:25,rowHover:!0,responsiveLayout:"scroll",loading:p.loading,stripedRows:""},(0,a.createSlots)({loading:(0,a.withCtx)((function(){return[s]})),header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",u,[(0,a.createVNode)(U,{onChange:t[0]||(t[0]=function(e){return f.getTahunUser()}),showClear:!0,modelValue:p.bulanUser,"onUpdate:modelValue":t[1]||(t[1]=function(e){return p.bulanUser=e}),options:p.bulan,optionValue:"code",optionLabel:"name",placeholder:"Pilih Bulan",class:"mr-2"},null,8,["modelValue","options"]),g.bulanUser?((0,a.openBlock)(),(0,a.createBlock)(U,{key:0,onChange:t[2]||(t[2]=function(e){return f.getPerDivisiUserBulan()}),showClear:!0,modelValue:p.tahunnUser,"onUpdate:modelValue":t[3]||(t[3]=function(e){return p.tahunnUser=e}),options:p.tahunn,optionValue:"tahun",optionLabel:"tahun",placeholder:"Pilih Tahun",class:"mr-2"},null,8,["modelValue","options"])):(0,a.createCommentVNode)("",!0)])]})),default:(0,a.withCtx)((function(){return[p.tahunnUser?((0,a.openBlock)(),(0,a.createBlock)(V,{key:0,field:"div_name",header:"Divisi User",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0),p.tahunnUser?((0,a.openBlock)(),(0,a.createBlock)(V,{key:1,field:"jumlah",header:"Jumlah Request",style:{"min-width":"10rem"}})):(0,a.createCommentVNode)("",!0)]})),_:2},[p.tahunnUser?{name:"footer",fn:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",c,[(0,a.createElementVNode)("div",d,[(0,a.createElementVNode)("div",h,[(0,a.createVNode)(C,{label:"Print",model:p.items},null,8,["model"])])])])]}))}:void 0]),1032,["value","loading"])])])])}},p=m}}]);