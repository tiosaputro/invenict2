"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6108],{6108:(e,t,o)=>{o.r(t),o.d(t,{default:()=>w});var a=o(70821),i={class:"grid crud-demo"},r={class:"col-12"},n={class:"card"},s=(0,a.createElementVNode)("h4",null,"ICT Request (Verification) ",-1),c={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},l={style:{width:"140px"}},d={class:"p-input-icon-left"},u=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),p=(0,a.createTextVNode)(" Not Found "),m=(0,a.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),h={class:"grid p-dir-col"},f={class:"col"},g={class:"box"},v={class:"field"},k={class:"field grid"},b=(0,a.createElementVNode)("label",{class:"col-fixed w-9rem"},"Reason",-1),C={class:"col-fixed w-9rem"},N={key:0,class:"p-error"};var V=o(6358);const x={data:function(){return{loading:!0,dialogReject:!1,submitted:!1,verif:[],kode:[],reason:{ket:null},filters:{global:{value:null,matchMode:V.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Atasan Requestor Divisi")||e.checkto.includes("/ict-request-divisi1")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},Approve:function(){var e=this;this.$confirm.require({message:"Are you sure you agree to this request?",header:"Confirmation Approval",icon:"pi pi-info-circle",acceptClass:"p-button",acceptLabel:"Yes",rejectLabel:"No",accept:function(){e.$toast.add({severity:"info",summary:"Success Message",detail:"Successfully approved this request"}),e.axios.get("/api/updateStatusPermohonan/"+e.$route.params.code,{headers:{Authorization:"Bearer "+e.token}}),setTimeout((function(){return e.$router.push("/ict-request-divisi1")}),1e3)},reject:function(){}})},updateReject:function(){var e=this;this.submitted=!0,null!=this.reason.ket&&this.axios.put("/api/updateStatusReject/"+this.$route.params.code,this.reason,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.dialogReject=!1,e.$toast.add({severity:"info",summary:"Success Message",detail:"Successfully rejected this request"}),setTimeout((function(){return e.$router.push("/ict-request-divisi1")}),1e3)}))},cancelReject:function(){this.dialogReject=!1,this.reason.ket=null,this.submitted=!1},getIctDetail:function(){var e=this;this.axios.get("/api/get-verif/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.verif=t.data})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data,e.loading=!1}))},DeleteIct:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("/api/delete-ict-detail/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},CetakPdf:function(){window.open("/api/report-ict-detail-pdf/"+this.code)},CetakExcel:function(){window.open("/api/report-ict-detail-excel/"+this.code)}}};const w=(0,o(83744).Z)(x,[["render",function(e,t,o,V,x,w){var R=this,y=(0,a.resolveComponent)("Toast"),q=(0,a.resolveComponent)("ConfirmDialog"),E=(0,a.resolveComponent)("Toolbar"),T=(0,a.resolveComponent)("InputText"),j=(0,a.resolveComponent)("Column"),$=(0,a.resolveComponent)("Button"),P=(0,a.resolveComponent)("DataTable"),S=(0,a.resolveComponent)("Textarea"),D=(0,a.resolveComponent)("Dialog");return(0,a.openBlock)(),(0,a.createElementBlock)("div",i,[(0,a.createElementVNode)("div",r,[(0,a.createElementVNode)("div",n,[(0,a.createVNode)(y),(0,a.createVNode)(q),(0,a.createVNode)(E,{class:"p-mb-4"},{start:(0,a.withCtx)((function(){return[s]})),_:1}),(0,a.createVNode)(P,{value:x.verif,paginator:!0,rows:25,loading:x.loading,filters:x.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Verification)",responsiveLayout:"scroll"},{header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",c,[(0,a.createElementVNode)("label",l,"No. Request: "+(0,a.toDisplayString)(x.kode.noreq),1),(0,a.createElementVNode)("span",d,[u,(0,a.createVNode)(T,{modelValue:x.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return x.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,a.withCtx)((function(){return[p]})),loading:(0,a.withCtx)((function(){return[m]})),footer:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("div",f,[(0,a.createElementVNode)("div",g,[(0,a.createVNode)($,{label:"Back",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Divisi 1"})})}),(0,a.createVNode)($,{label:"Approve",class:"p-button-raised p-button-success mr-2",icon:"pi pi-check-square",onClick:t[2]||(t[2]=function(e){return w.Approve()})}),(0,a.createVNode)($,{label:"Reject",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-times-circle",onClick:t[3]||(t[3]=function(e){return R.dialogReject=!0})})])])])]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(j,{field:"ireq_type",header:"Request Type",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(j,{field:"name",header:"Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(j,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,a.createVNode)(j,{field:"ireq_remark",header:"Remark",sortable:!0,style:{"min-width":"12rem"}})]})),_:1},8,["value","loading","filters"]),(0,a.createVNode)(D,{visible:x.dialogReject,"onUpdate:visible":t[7]||(t[7]=function(e){return x.dialogReject=e}),style:{width:"400px"},header:"ICT Request",modal:!0,class:"field grid"},{footer:(0,a.withCtx)((function(){return[(0,a.createVNode)($,{label:"Save",onClick:t[5]||(t[5]=function(e){return w.updateReject()}),class:"p-button",autofocus:""}),(0,a.createVNode)($,{label:"Cancel",onClick:t[6]||(t[6]=function(e){return w.cancelReject()}),class:"p-button-text"})]})),default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",v,[(0,a.createElementVNode)("div",k,[b,(0,a.createElementVNode)("div",C,[(0,a.createVNode)(S,{autoResize:!0,type:"text",modelValue:x.reason.ket,"onUpdate:modelValue":t[4]||(t[4]=function(e){return x.reason.ket=e}),rows:"5",placeholder:"Give a reason",class:(0,a.normalizeClass)({"p-invalid":x.submitted&&!x.reason.ket})},null,8,["modelValue","class"]),x.submitted&&!x.reason.ket?((0,a.openBlock)(),(0,a.createElementBlock)("small",N," Reason not filled ")):(0,a.createCommentVNode)("",!0)])])])]})),_:1},8,["visible"])])])])}]])}}]);