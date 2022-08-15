"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[428],{20428:(e,t,n)=>{n.r(t),n.d(t,{default:()=>f});var o=n(70821),r={class:"grid"},a={class:"col-12"},i={class:"card"},l=(0,o.createElementVNode)("h4",null,"Laporan Request Per Status Per ICT-Personnel",-1),s=(0,o.createTextVNode)(" Loading data. Please wait. "),c=(0,o.createTextVNode)(" Not Found "),u={class:"table-header p-text-left"},d={class:"p-grid p-dir-col"},p={class:"p-col"},h={class:"box"};const m={data:function(){var e=this;return{ictPersonnel:null,personnel:[],loading:!1,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-per-status-per-personnel-pdf/"+e.ictPersonnel)}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-per-status-per-personnel-excel/"+e.ictPersonnel)}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Per Status Per Pesonnel")||e.checkto.includes("/report-per-status-per-personnel")?e.getPersonnel():e.$router.push("/access")})):this.$router.push("/login")},getPerStatusIct:function(){var e=this;null!=this.ictPersonnel&&(this.loading=!0,this.axios.get("api/count-per-status-ict/"+this.ictPersonnel,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data,e.loading=!1})))},getPersonnel:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.personnel=t.data.personnell})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))}}};const f=(0,n(83744).Z)(m,[["render",function(e,t,n,m,f,g){var k=(0,o.resolveComponent)("Toast"),v=(0,o.resolveComponent)("ConfirmDialog"),P=(0,o.resolveComponent)("Toolbar"),C=(0,o.resolveComponent)("Dropdown"),V=(0,o.resolveComponent)("Column"),w=(0,o.resolveComponent)("SplitButton"),x=(0,o.resolveComponent)("DataTable");return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",a,[(0,o.createElementVNode)("div",i,[(0,o.createVNode)(k),(0,o.createVNode)(v),(0,o.createVNode)(P,{class:"mb-4"},{start:(0,o.withCtx)((function(){return[l]})),_:1}),(0,o.createVNode)(x,{value:f.req,rows:25,loading:f.loading,rowHover:!0,responsiveLayout:"scroll",stripedRows:""},(0,o.createSlots)({loading:(0,o.withCtx)((function(){return[s]})),empty:(0,o.withCtx)((function(){return[c]})),header:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",u,[(0,o.createVNode)(C,{onChange:t[0]||(t[0]=function(e){return g.getPerStatusIct()}),showClear:!0,modelValue:f.ictPersonnel,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.ictPersonnel=e}),options:f.personnel,optionValue:"name",optionLabel:"name",placeholder:"Pilih Personnel"},null,8,["modelValue","options"])])]})),default:(0,o.withCtx)((function(){return[f.ictPersonnel?((0,o.openBlock)(),(0,o.createBlock)(V,{key:0,field:"status",header:"Status Request",style:{"min-width":"8rem"}})):(0,o.createCommentVNode)("",!0),f.ictPersonnel?((0,o.openBlock)(),(0,o.createBlock)(V,{key:1,field:"jumlah",header:"Jumlah Request",style:{"min-width":"8rem"}})):(0,o.createCommentVNode)("",!0)]})),_:2},[f.ictPersonnel?{name:"footer",fn:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",d,[(0,o.createElementVNode)("div",p,[(0,o.createElementVNode)("div",h,[(0,o.createVNode)(w,{label:"Print",model:f.items},null,8,["model"])])])])]}))}:void 0]),1032,["value","loading"])])])])}]])}}]);