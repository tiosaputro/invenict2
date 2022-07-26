"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9365],{19365:(e,t,a)=>{a.r(t),a.d(t,{default:()=>N});var r=a(70821),o={class:"grid"},i={class:"col-12"},n={class:"card"},l=(0,r.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),s={style:{width:"140px"}},c={class:"text-header text-right"},d={class:"p-input-icon-left"},u=(0,r.createElementVNode)("i",{class:"pi pi-search"},null,-1),h=(0,r.createTextVNode)(" Not Found "),p=(0,r.createTextVNode)(" Loading data. Please wait. "),m={class:"p-grid p-dir-col"},f={class:"p-col"},g={class:"box"};var k=a(6358);const v={data:function(){return{loading:!0,detail:[],kode:[],filters:{global:{value:null,matchMode:k.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),status:""}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Approval Manager")||e.checkto.includes("/ict-request-manager")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data,e.status=t.data.cekstatus}))},DeleteIct:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("/api/delete-ict-detail/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},CetakPdf:function(){window.open("/api/print-out-ict-request/"+this.code)}}};const N=(0,a(83744).Z)(v,[["render",function(e,t,a,k,v,N){var C=(0,r.resolveComponent)("Toast"),V=(0,r.resolveComponent)("ConfirmDialog"),b=(0,r.resolveComponent)("Toolbar"),w=(0,r.resolveComponent)("InputText"),x=(0,r.resolveComponent)("Column"),q=(0,r.resolveComponent)("Button"),y=(0,r.resolveComponent)("DataTable");return(0,r.openBlock)(),(0,r.createElementBlock)("div",o,[(0,r.createElementVNode)("div",i,[(0,r.createElementVNode)("div",n,[(0,r.createVNode)(C),(0,r.createVNode)(V),(0,r.createVNode)(b,{class:"mb-4"},{start:(0,r.withCtx)((function(){return[l]})),end:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("label",s,"No. Request: "+(0,r.toDisplayString)(v.kode.noreq),1)]})),_:1}),(0,r.createVNode)(y,{value:v.detail,paginator:!0,rows:25,loading:v.loading,filters:v.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",c,[(0,r.createElementVNode)("span",d,[u,(0,r.createVNode)(w,{modelValue:v.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return v.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,r.withCtx)((function(){return[h]})),loading:(0,r.withCtx)((function(){return[p]})),footer:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",m,[(0,r.createElementVNode)("div",f,[(0,r.createElementVNode)("div",g,[(0,r.createVNode)(q,{label:"Back",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Manager"})})}),(0,r.createVNode)(q,{label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return N.CetakPdf()})})])])])]})),default:(0,r.withCtx)((function(){return[(0,r.createVNode)(x,{field:"ireqd_id",header:"No.Detail",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(x,{field:"ireq_type",header:"Request Type",sortable:!0,style:{"min-width":"12rem"}}),(0,r.createVNode)(x,{field:"name",header:"Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,r.createVNode)(x,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(x,{field:"ireq_remark",header:"Remark",sortable:!0,style:{"min-width":"12rem"}})]})),_:1},8,["value","loading","filters"])])])])}]])}}]);