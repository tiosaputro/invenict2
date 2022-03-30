"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6834],{16834:(e,t,o)=>{o.r(t),o.d(t,{default:()=>V});var a=o(5166),i={class:"grid"},n={class:"col-12"},r={class:"card"},l=(0,a.createElementVNode)("div",{class:"my-2"},[(0,a.createElementVNode)("h4",null,"ICT Request (Detail) ")],-1),c={style:{width:"130px"}},s={key:0,class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},d={class:"block mt-2 md:mt-0 p-input-icon-left"},u=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),p={key:1,class:"table-header text-right"},m={class:"p-input-icon-left"},h=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),f=(0,a.createTextVNode)(" Not Found "),k=(0,a.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),g={class:"grid dir-col"},C={class:"col"},b={class:"box"};var N=o(6358);const v={data:function(){return{loading:!0,detail:[],status:"",kode:"",filters:{global:{value:null,matchMode:N.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),tes:[],ireq:[]}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Request")||e.checkto.includes("/ict-request")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.tes=t.data.map((function(e){return e.ireq_assigned_to})),e.tes.length>0&&null!=e.tes[0]&&(e.ireq=e.tes),e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data.noreq,e.status=t.data.ireq_status}))},DeleteIct:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("/api/delete-ict-detail/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},CetakPdf:function(){window.open("/api/report-ict-detail-pdf/"+this.code)},CetakExcel:function(){window.open("/api/report-ict-detail-excel/"+this.code)},CetakPdfReject:function(){window.open("/api/report-ict-detail-pdf-reject/"+this.code)},CetakExcelReject:function(){window.open("/api/report-ict-detail-excel-reject/"+this.code)}},render:function(e,t,o,N,v,V){var w=this,x=(0,a.resolveComponent)("Toast"),q=(0,a.resolveComponent)("ConfirmDialog"),y=(0,a.resolveComponent)("Toolbar"),E=(0,a.resolveComponent)("Button"),B=(0,a.resolveComponent)("InputText"),R=(0,a.resolveComponent)("Column"),D=(0,a.resolveComponent)("DataTable"),P=(0,a.resolveDirective)("tooltip");return(0,a.openBlock)(),(0,a.createElementBlock)("div",i,[(0,a.createElementVNode)("div",n,[(0,a.createElementVNode)("div",r,[(0,a.createVNode)(x),(0,a.createVNode)(q),(0,a.createVNode)(y,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[l]})),end:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("label",c,"No. Request: "+(0,a.toDisplayString)(w.kode),1)]})),_:1}),(0,a.createVNode)(D,{value:v.detail,paginator:!0,rows:10,loading:v.loading,filters:v.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,a.withCtx)((function(){return[null==w.status?((0,a.openBlock)(),(0,a.createElementBlock)("div",s,[(0,a.createVNode)(E,{label:"Add",class:"p-button-raised",icon:"bi bi-file-earmark-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push({name:"Add Ict Request Detail",params:{code:v.code}})})}),(0,a.createElementVNode)("span",d,[u,(0,a.createVNode)(B,{modelValue:v.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return v.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])):null!=w.status?((0,a.openBlock)(),(0,a.createElementBlock)("div",p,[(0,a.createElementVNode)("span",m,[h,(0,a.createVNode)(B,{modelValue:v.filters.global.value,"onUpdate:modelValue":t[2]||(t[2]=function(e){return v.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])):(0,a.createCommentVNode)("",!0)]})),empty:(0,a.withCtx)((function(){return[f]})),loading:(0,a.withCtx)((function(){return[k]})),footer:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",g,[(0,a.createElementVNode)("div",C,[(0,a.createElementVNode)("div",b,[(0,a.createVNode)(E,{label:"Kembali",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[3]||(t[3]=function(t){return e.$router.push({name:"Ict Request"})})}),"Reject"==w.status?((0,a.openBlock)(),(0,a.createBlock)(E,{key:0,label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[4]||(t[4]=function(e){return V.CetakPdfReject()})})):(0,a.createCommentVNode)("",!0),"Reject"==w.status?((0,a.openBlock)(),(0,a.createBlock)(E,{key:1,label:"Excel",class:"p-button-raised p-button-success mt-2",icon:"pi pi-print",onClick:t[5]||(t[5]=function(e){return V.CetakExcelReject()})})):(0,a.createCommentVNode)("",!0),"Reject"!=w.status?((0,a.openBlock)(),(0,a.createBlock)(E,{key:2,label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[6]||(t[6]=function(e){return V.CetakPdf()})})):(0,a.createCommentVNode)("",!0),"Reject"!=w.status?((0,a.openBlock)(),(0,a.createBlock)(E,{key:3,label:"Excel",class:"p-button-raised p-button-success mt-2",icon:"pi pi-print",onClick:t[7]||(t[7]=function(e){return V.CetakExcel()})})):(0,a.createCommentVNode)("",!0)])])])]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(R,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(R,{field:"name",header:"Nama Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(R,{field:"ireq_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(R,{field:"ireq_remark",header:"Keterangan",sortable:!0,style:{"min-width":"12rem"}}),w.ireq.length?((0,a.openBlock)(),(0,a.createBlock)(R,{key:0,field:"ireq_assigned_to",header:"Petugas ICT",sortable:!0,style:{"min-width":"12rem"}})):(0,a.createCommentVNode)("",!0),(0,a.createVNode)(R,{field:"ireq_status",header:"Status",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(R,{style:{"min-width":"12rem"}},{body:(0,a.withCtx)((function(t){return[null==t.data.ireq_status?(0,a.withDirectives)(((0,a.openBlock)(),(0,a.createBlock)(E,{key:0,class:"p-button-rounded p-button-info mr-2",icon:"pi pi-pencil",onClick:function(o){return e.$router.push({name:"Edit Ict Request Detail",params:{ireq:t.data.ireqd_id}})}},null,8,["onClick"])),[[P,"Edit",void 0,{left:!0}]]):(0,a.createCommentVNode)("",!0),null==t.data.ireq_status?(0,a.withDirectives)(((0,a.openBlock)(),(0,a.createBlock)(E,{key:1,icon:"pi pi-trash",class:"p-button-rounded p-button-danger mr-2",onClick:function(e){return V.DeleteIct(t.data.ireqd_id)}},null,8,["onClick"])),[[P,"Delete",void 0,{right:!0}]]):(0,a.createCommentVNode)("",!0)]})),_:1})]})),_:1},8,["value","loading","filters"])])])])}},V=v}}]);