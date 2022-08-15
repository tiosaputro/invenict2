"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6254],{6254:(e,t,a)=>{a.r(t),a.d(t,{default:()=>C});var o=a(70821),i={class:"grid"},r={class:"col-12"},n={class:"card"},s=(0,o.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),l={style:{width:"140px"}},c={class:"table-header text-right"},d={class:"p-input-icon-left"},u=(0,o.createElementVNode)("i",{class:"pi pi-search"},null,-1),p=(0,o.createTextVNode)(" Not Found "),h=(0,o.createTextVNode)(" Loading data. Please wait. "),m={class:"p-grid p-dir-col"},f={class:"p-col"},g={class:"box"};var k=a(6358);const v={data:function(){return{loading:!0,detail:[],kode:[],filters:{global:{value:null,matchMode:k.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),tes:[],ireq:[],status:""}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Approval Atasan")||e.checkto.includes("/ict-request-divisi1")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.tes=t.data.map((function(e){return e.ireq_assigned_to})),e.tes.length>0&&null!=e.tes[0]&&(e.ireq=e.tes),e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data,e.status=t.data.cekstatus}))},DeleteIct:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("/api/delete-ict-detail/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},CetakPdf:function(){var e=this;this.loading=!0,this.axios.get("/api/print-out-ict-request/"+this.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){var a=t.data;window.open("","response","resizable=yes").document.write(a),e.loading=!1}))},CetakExcel:function(){window.open("/api/report-ict-detail-excel/"+this.code)},CetakPdfReject:function(){window.open("/api/report-ict-detail-pdf-tab-reject/"+this.code)},CetakExcelReject:function(){window.open("/api/report-ict-detail-excel-tab-reject/"+this.code)},CetakPdfSedangDikerjakan:function(){window.open("/api/report-ict-detail-pdf-tab-sedang-dikerjakan/"+this.code)},CetakExcelSedangDikerjakan:function(){window.open("/api/report-ict-detail-excel-tab-sedang-dikerjakan/"+this.code)}}};const C=(0,a(83744).Z)(v,[["render",function(e,t,a,k,v,C){var w=this,b=(0,o.resolveComponent)("Toast"),N=(0,o.resolveComponent)("ConfirmDialog"),x=(0,o.resolveComponent)("Toolbar"),V=(0,o.resolveComponent)("InputText"),q=(0,o.resolveComponent)("Column"),P=(0,o.resolveComponent)("Button"),y=(0,o.resolveComponent)("DataTable"),D=(0,o.resolveDirective)("tooltip");return(0,o.openBlock)(),(0,o.createElementBlock)("div",i,[(0,o.createElementVNode)("div",r,[(0,o.createElementVNode)("div",n,[(0,o.createVNode)(b),(0,o.createVNode)(N),(0,o.createVNode)(x,{class:"mb-4"},{start:(0,o.withCtx)((function(){return[s]})),end:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("label",l,"No. Request: "+(0,o.toDisplayString)(v.kode.noreq),1)]})),_:1}),(0,o.createVNode)(y,{value:v.detail,paginator:!0,rows:25,loading:v.loading,filters:v.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",c,[(0,o.createElementVNode)("span",d,[u,(0,o.createVNode)(V,{modelValue:v.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return v.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,o.withCtx)((function(){return[p]})),loading:(0,o.withCtx)((function(){return[h]})),footer:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",m,[(0,o.createElementVNode)("div",f,[(0,o.createElementVNode)("div",g,[(0,o.createVNode)(P,{label:"Back",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Divisi 1"})})}),(0,o.withDirectives)((0,o.createVNode)(P,{label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return C.CetakPdf()})},null,512),[[D,"Click to print out (PDF)",void 0,{bottom:!0}]])])])])]})),default:(0,o.withCtx)((function(){return[(0,o.createVNode)(q,{field:"ireq_type",header:"Request Type",sortable:!0,style:{"min-width":"12rem"}}),(0,o.createVNode)(q,{field:"name",header:"Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,o.createVNode)(q,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,o.createVNode)(q,{field:"ireq_remark",header:"Remark",sortable:!0,style:{"min-width":"12rem"}}),w.ireq.length?((0,o.openBlock)(),(0,o.createBlock)(q,{key:0,field:"ireq_assigned_to",header:"Personnel (ICT)",sortable:!0,style:{"min-width":"12rem"}})):(0,o.createCommentVNode)("",!0)]})),_:1},8,["value","loading","filters"])])])])}]])}}]);