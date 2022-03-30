"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3562],{3562:(e,t,a)=>{a.r(t),a.d(t,{default:()=>v});var r=a(5166),o={class:"grid"},i={class:"col-12"},n={class:"card"},l=(0,r.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),s={style:{width:"140px"}},c={class:"table-header text-right"},d={class:"p-input-icon-left"},u=(0,r.createElementVNode)("i",{class:"pi pi-search"},null,-1),h=(0,r.createTextVNode)(" Not Found "),m=(0,r.createTextVNode)(" Loading data. Please wait. "),p={class:"p-grid p-dir-col"},g={class:"p-col"},f={class:"box"};var N=a(6358);const k={data:function(){return{loading:!0,detail:[],kode:[],filters:{global:{value:null,matchMode:N.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Reviewer")||e.checkname.includes("Request")||e.checkname.includes("Approval Manager")||e.checkto.includes("/ict-request-reviewer")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail-penugasan/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))}},render:function(e,t,a,N,k,v){var V=(0,r.resolveComponent)("Toast"),w=(0,r.resolveComponent)("ConfirmDialog"),C=(0,r.resolveComponent)("Toolbar"),b=(0,r.resolveComponent)("InputText"),x=(0,r.resolveComponent)("Column"),q=(0,r.resolveComponent)("Button"),P=(0,r.resolveComponent)("DataTable");return(0,r.openBlock)(),(0,r.createElementBlock)("div",o,[(0,r.createElementVNode)("div",i,[(0,r.createElementVNode)("div",n,[(0,r.createVNode)(V),(0,r.createVNode)(w),(0,r.createVNode)(C,{class:"mb-4"},{start:(0,r.withCtx)((function(){return[l]})),end:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("label",s,"No. Request: "+(0,r.toDisplayString)(k.kode.noreq),1)]})),_:1}),(0,r.createVNode)(P,{value:k.detail,paginator:!0,rows:25,loading:k.loading,filters:k.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",c,[(0,r.createElementVNode)("span",d,[u,(0,r.createVNode)(b,{modelValue:k.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return k.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,r.withCtx)((function(){return[h]})),loading:(0,r.withCtx)((function(){return[m]})),footer:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",p,[(0,r.createElementVNode)("div",g,[(0,r.createElementVNode)("div",f,[(0,r.createVNode)(q,{label:"Kembali",class:"p-button-raised p-button p-mr-2 p-mb-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Desc"})})})])])])]})),default:(0,r.withCtx)((function(){return[(0,r.createVNode)(x,{field:"ireqd_id",header:"No. Detail",sortable:!0,style:{"min-width":"4rem"}}),(0,r.createVNode)(x,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(x,{field:"name",header:"Nama Peripheral",sortable:!0,style:{"min-width":"4rem"}}),(0,r.createVNode)(x,{field:"ireq_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"4rem"}}),(0,r.createVNode)(x,{field:"ireq_remark",header:"Keterangan",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(x,{field:"ireq_assigned_to",header:"Petugas(ICT)",sortable:!0,style:{"min-width":"4rem"}}),(0,r.createVNode)(x,{field:"ireq_status",header:"Status",sortable:!0,style:{"min-width":"4rem"}})]})),_:1},8,["value","loading","filters"])])])])}},v=k}}]);