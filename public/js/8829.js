"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8829],{28829:(e,t,a)=>{a.r(t),a.d(t,{default:()=>b});var o=a(70821),r={class:"grid"},i={class:"col-12"},n={class:"card"},s=(0,o.createElementVNode)("div",{class:"my-2"},[(0,o.createElementVNode)("h4",null,"ICT Request (Detail) ")],-1),l={style:{width:"200px"}},c={class:"table-header text-right"},d={class:"p-input-icon-left"},u=(0,o.createElementVNode)("i",{class:"pi pi-search"},null,-1),h=(0,o.createTextVNode)(" Not Found "),m=(0,o.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),p={class:"grid dir-col"},g={class:"col"},f={class:"box"};var v=a(6358);const k={data:function(){return e={submitted:!1,dialogAssign:!1,assign:[],petugas:[],kode:"",status:"",loading:!0,detail:[],filters:{global:{value:null,matchMode:v.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")},a=null,(t="code")in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e;var e,t,a},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Reviewer")||e.checkto.includes("/ict-request/reviewer")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data.noreq,e.status=t.data.cekstatus}))},CetakPdf:function(){var e=this;this.loading=!0,this.axios.get("/api/print-out-ict-request/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){var a=t.data;window.open("","response","resizable=yes").document.write(a),e.loading=!1}))}}};const b=(0,a(83744).Z)(k,[["render",function(e,t,a,v,k,b){var w=this,N=(0,o.resolveComponent)("Toast"),C=(0,o.resolveComponent)("ConfirmDialog"),V=(0,o.resolveComponent)("Toolbar"),x=(0,o.resolveComponent)("InputText"),y=(0,o.resolveComponent)("Column"),P=(0,o.resolveComponent)("Button"),q=(0,o.resolveComponent)("DataTable"),E=(0,o.resolveDirective)("tooltip");return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",i,[(0,o.createElementVNode)("div",n,[(0,o.createVNode)(N),(0,o.createVNode)(C),(0,o.createVNode)(V,{class:"mb-4"},{start:(0,o.withCtx)((function(){return[s]})),end:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("label",l,"No. Request: "+(0,o.toDisplayString)(w.kode),1)]})),_:1}),(0,o.createVNode)(q,{value:k.detail,paginator:!0,rows:10,loading:k.loading,filters:k.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",c,[(0,o.createElementVNode)("span",d,[u,(0,o.createVNode)(x,{modelValue:k.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return k.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,o.withCtx)((function(){return[h]})),loading:(0,o.withCtx)((function(){return[m]})),footer:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",p,[(0,o.createElementVNode)("div",g,[(0,o.createElementVNode)("div",f,[(0,o.withDirectives)((0,o.createVNode)(P,{label:"Back",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Reviewer"})})},null,512),[[E,"Click to back",void 0,{bottom:!0}]]),(0,o.withDirectives)((0,o.createVNode)(P,{label:"Pdf",class:"p-button-raised p-button-danger mt-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return b.CetakPdf()})},null,512),[[E,"Click to print out (PDF)",void 0,{bottom:!0}]])])])])]})),default:(0,o.withCtx)((function(){return[(0,o.createVNode)(y,{field:"ireq_type",header:"Request Type",sortable:!0,style:{"min-width":"11rem"}}),(0,o.createVNode)(y,{field:"name",header:"Peripheral",sortable:!0,style:{"min-width":"11rem"}}),(0,o.createVNode)(y,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,o.createVNode)(y,{field:"ireq_remark",header:"Remark",sortable:!0,style:{"min-width":"11rem"}}),(0,o.createVNode)(y,{field:"ireq_status",header:"Status",sortable:!0,style:{"min-width":"11rem"}},{body:(0,o.withCtx)((function(e){return[(0,o.createElementVNode)("span",{class:(0,o.normalizeClass)("status-bagde status-"+e.data.status.toLowerCase())},(0,o.toDisplayString)(e.data.ireq_status),3)]})),_:1})]})),_:1},8,["value","loading","filters"])])])])}]])}}]);