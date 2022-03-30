"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2414],{52414:(e,t,o)=>{o.r(t),o.d(t,{default:()=>k});var a=o(5166),r={class:"p-grid crud-demo"},n={class:"col-12"},i={class:"card"},l=(0,a.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),s={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},c={style:{width:"110px"}},d={class:"p-input-icon-left"},u=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),m=(0,a.createTextVNode)(" Not Found "),h=(0,a.createTextVNode)(" Loading data. Please wait. "),p={class:"grid dir-col"},f={class:"col"},g={class:"box"};var v=o(6358);const N={data:function(){return{loading:!0,detail:[],kode:[],filters:{global:{value:null,matchMode:v.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token")}},mounted:function(){this.getIctDetail(),this.getNoreq()},methods:{cekUser:function(){var e=this;this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Closing Request")||e.checkto.includes("/ict-request-divisi4")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")}))},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))}},render:function(e,t,o,v,N,k){var V=(0,a.resolveComponent)("Toast"),C=(0,a.resolveComponent)("ConfirmDialog"),w=(0,a.resolveComponent)("Toolbar"),x=(0,a.resolveComponent)("InputText"),b=(0,a.resolveComponent)("Column"),E=(0,a.resolveComponent)("Button"),P=(0,a.resolveComponent)("DataTable");return(0,a.openBlock)(),(0,a.createElementBlock)("div",r,[(0,a.createElementVNode)("div",n,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(V),(0,a.createVNode)(C),(0,a.createVNode)(w,{class:"mb-4"},{left:(0,a.withCtx)((function(){return[l]})),_:1}),(0,a.createVNode)(P,{value:N.detail,paginator:!0,rows:25,loading:N.loading,filters:N.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",s,[(0,a.createElementVNode)("label",c,"No. Request: "+(0,a.toDisplayString)(N.kode.noreq),1),(0,a.createElementVNode)("span",d,[u,(0,a.createVNode)(x,{modelValue:N.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return N.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,a.withCtx)((function(){return[m]})),loading:(0,a.withCtx)((function(){return[h]})),footer:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",p,[(0,a.createElementVNode)("div",f,[(0,a.createElementVNode)("div",g,[(0,a.createVNode)(E,{label:"Kembali",class:"p-button-raised p-button p-mr-2 p-mb-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Desc"})})})])])])]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(b,{field:"name",header:"Nama Peripheral",sortable:!0,style:{"min-width":"4rem"}}),(0,a.createVNode)(b,{field:"ireq_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"4rem"}}),(0,a.createVNode)(b,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"6rem"}})]})),_:1},8,["value","loading","filters"])])])])}},k=N}}]);