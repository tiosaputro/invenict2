"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2488],{12488:(e,t,o)=>{o.r(t),o.d(t,{default:()=>p});var r=o(5166),a={class:"grid"},n={class:"col-12"},i={class:"card"},s=(0,r.createElementVNode)("h4",null,"Laporan Request Per Status",-1),c=(0,r.createTextVNode)(" Not Found "),l=(0,r.createTextVNode)(" Loading. Please wait. "),d={class:"p-grid p-dir-col"},u={class:"p-col"},m={class:"box"};const h={data:function(){return{loading:!0,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-per-status-pdf")}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-per-status-excel")}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Per Status")||e.checkto.includes("/report-per-status")?e.getReq():e.$router.push("/access")})):this.$router.push("/login")},getReq:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data.grafik3,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))}},render:function(e,t,o,h,p,f){var g=(0,r.resolveComponent)("Toast"),k=(0,r.resolveComponent)("ConfirmDialog"),v=(0,r.resolveComponent)("Toolbar"),w=(0,r.resolveComponent)("Column"),x=(0,r.resolveComponent)("SplitButton"),C=(0,r.resolveComponent)("DataTable");return(0,r.openBlock)(),(0,r.createElementBlock)("div",a,[(0,r.createElementVNode)("div",n,[(0,r.createElementVNode)("div",i,[(0,r.createVNode)(g),(0,r.createVNode)(k),(0,r.createVNode)(v,{class:"mb-4"},{start:(0,r.withCtx)((function(){return[s]})),_:1}),(0,r.createVNode)(C,{value:p.req,rows:25,loading:p.loading,rowHover:!0,responsiveLayout:"scroll",stripedRows:""},{empty:(0,r.withCtx)((function(){return[c]})),loading:(0,r.withCtx)((function(){return[l]})),footer:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",d,[(0,r.createElementVNode)("div",u,[(0,r.createElementVNode)("div",m,[(0,r.createVNode)(x,{label:"Print",model:p.items},null,8,["model"])])])])]})),default:(0,r.withCtx)((function(){return[(0,r.createVNode)(w,{field:"ireq_status",header:"Status ICT Request",style:{"min-width":"10rem"}}),(0,r.createVNode)(w,{field:"jumlah",header:"Jumlah Request",style:{"min-width":"10rem"}})]})),_:1},8,["value","loading"])])])])}},p=h}}]);