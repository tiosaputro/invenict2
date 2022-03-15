"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[730],{10730:(e,t,o)=>{o.r(t),o.d(t,{default:()=>p});var n=o(5166),a={class:"grid"},r={class:"col-12"},i={class:"card"},u=(0,n.createElementVNode)("h4",null,"Laporan Request Divisi Requestor Per Tahun",-1),s=(0,n.createTextVNode)(" Loading data. Please wait. "),l={class:"table-header p-text-left"},c={class:"p-grid p-dir-col"},d={class:"p-col"},h={class:"box"};const m={data:function(){var e=this;return{bulanRequestor:null,tahunRequestor:null,tahunn:[],loading:!1,req:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),items:[{label:"Pdf",icon:"bi bi-file-earmark-pdf text-danger",command:function(){window.open("api/req-div-req-per-tahun-pdf/"+e.tahunRequestor)}},{label:"Excel",icon:"bi bi-file-earmark-excel text-success",command:function(){window.open("api/req-div-req-per-tahun-excel/"+e.tahunRequestor)}}]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Divisi Requestor Per Tahun")||e.checkto.includes("/report-div-req-per-tahun")?e.getTahun():e.$router.push("/access")})):this.$router.push("/login")},getPerDivisiRequestorTahun:function(){var e=this;null!=this.tahunRequestor&&(this.loading=!0,this.axios.get("api/count-per-divreq-tahun/"+this.tahunRequestor,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.req=t.data,e.loading=!1})))},getTahun:function(){var e=this;this.axios.get("api/get-tahun",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.tahunn=t.data.grafik})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))}},render:function(e,t,o,m,p,f){var v=(0,n.resolveComponent)("Toast"),g=(0,n.resolveComponent)("ConfirmDialog"),k=(0,n.resolveComponent)("Toolbar"),q=(0,n.resolveComponent)("Dropdown"),C=(0,n.resolveComponent)("Column"),V=(0,n.resolveComponent)("SplitButton"),w=(0,n.resolveComponent)("DataTable");return(0,n.openBlock)(),(0,n.createElementBlock)("div",a,[(0,n.createElementVNode)("div",r,[(0,n.createElementVNode)("div",i,[(0,n.createVNode)(v),(0,n.createVNode)(g),(0,n.createVNode)(k,{class:"mb-4"},{start:(0,n.withCtx)((function(){return[u]})),_:1}),(0,n.createVNode)(w,{value:p.req,rows:25,rowHover:!0,responsiveLayout:"scroll",loading:p.loading,stripedRows:""},(0,n.createSlots)({loading:(0,n.withCtx)((function(){return[s]})),header:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("div",l,[(0,n.createVNode)(q,{onChange:t[0]||(t[0]=function(e){return f.getPerDivisiRequestorTahun()}),showClear:!0,modelValue:p.tahunRequestor,"onUpdate:modelValue":t[1]||(t[1]=function(e){return p.tahunRequestor=e}),options:p.tahunn,optionValue:"tahun",optionLabel:"tahun",placeholder:"Pilih Tahun"},null,8,["modelValue","options"])])]})),default:(0,n.withCtx)((function(){return[p.tahunRequestor?((0,n.openBlock)(),(0,n.createBlock)(C,{key:0,field:"div_name",header:"Divisi Requestor",style:{"min-width":"10rem"}})):(0,n.createCommentVNode)("",!0),p.tahunRequestor?((0,n.openBlock)(),(0,n.createBlock)(C,{key:1,field:"jumlah",header:"Jumlah Request",style:{"min-width":"10rem"}})):(0,n.createCommentVNode)("",!0)]})),_:2},[p.tahunRequestor?{name:"footer",fn:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("div",c,[(0,n.createElementVNode)("div",d,[(0,n.createElementVNode)("div",h,[(0,n.createVNode)(V,{label:"Print",model:p.items},null,8,["model"])])])])]}))}:void 0]),1032,["value","loading"])])])])}},p=m}}]);