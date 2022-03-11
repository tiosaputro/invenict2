"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[552],{30552:(e,t,a)=>{a.r(t),a.d(t,{default:()=>w});var o=a(5166),i={class:"p-grid crud-demo"},r={class:"p-col-12"},n={class:"card"},s=(0,o.createElementVNode)("h4",null,"ICT Request (Verifikasi) ",-1),l={class:"table-header p-d-flex p-flex-column p-flex-md-row p-jc-md-between"},c={style:{width:"110px"}},d={class:"p-input-icon-left"},u=(0,o.createElementVNode)("i",{class:"pi pi-search"},null,-1),p=(0,o.createTextVNode)(" Not Found "),m=(0,o.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),h={class:"p-grid p-dir-col"},f={class:"p-col"},v={class:"box"},g={class:"p-fluid"},k={class:"p-field p-grid"},b=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"100px"}},"Alasan",-1),C={class:"p-col-3 p-md-6"},N={key:0,class:"p-error"};var V=a(6358);const x={data:function(){return{loading:!0,dialogReject:!1,submitted:!1,verif:[],kode:[],reason:{ket:null},filters:{global:{value:null,matchMode:V.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Approval Atasan")||e.checkto.includes("/ict-request-divisi1")?(e.getIctDetail(),e.getNoreq()):(e.$toast.add({severity:"error",summary:"403",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3))}))},Approve:function(){var e=this;this.$confirm.require({message:"Approval Permohonan Dilanjutkan?",header:"ICT Request    ",icon:"pi pi-info-circle",acceptClass:"p-button",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){e.$toast.add({severity:"info",summary:"Confirmed",detail:"Permohonan Dilanjutkan"}),e.axios.get("/api/updateStatusPermohonan/"+e.$route.params.code,{headers:{Authorization:"Bearer "+e.token}}),setTimeout((function(){return e.$router.push("/ict-request-divisi1")}),1e3)},reject:function(){}})},updateReject:function(){var e=this;this.submitted=!0,null!=this.reason.ket&&this.axios.put("/api/updateStatusReject/"+this.$route.params.code,this.reason,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.dialogReject=!1,e.$toast.add({severity:"info",summary:"Confirmed",detail:"Berhasil Direject"}),setTimeout((function(){return e.$router.push("/ict-request-divisi1")}),1e3)}))},cancelReject:function(){this.dialogReject=!1,this.reason.ket=null,this.submitted=!1},getIctDetail:function(){var e=this;this.axios.get("/api/get-verif/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.verif=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))},DeleteIct:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("/api/delete-ict-detail/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},CetakPdf:function(){window.open("/api/report-ict-detail-pdf/"+this.code)},CetakExcel:function(){window.open("/api/report-ict-detail-excel/"+this.code)}},render:function(e,t,a,V,x,w){var R=this,T=(0,o.resolveComponent)("Toast"),y=(0,o.resolveComponent)("ConfirmDialog"),j=(0,o.resolveComponent)("Toolbar"),E=(0,o.resolveComponent)("InputText"),q=(0,o.resolveComponent)("Column"),D=(0,o.resolveComponent)("Button"),P=(0,o.resolveComponent)("DataTable"),A=(0,o.resolveComponent)("Textarea"),$=(0,o.resolveComponent)("Dialog");return(0,o.openBlock)(),(0,o.createElementBlock)("div",i,[(0,o.createElementVNode)("div",r,[(0,o.createElementVNode)("div",n,[(0,o.createVNode)(T),(0,o.createVNode)(y),(0,o.createVNode)(j,{class:"p-mb-4"},{left:(0,o.withCtx)((function(){return[s]})),_:1}),(0,o.createVNode)(P,{value:x.verif,paginator:!0,rows:25,loading:x.loading,filters:x.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Verifikasi)",responsiveLayout:"scroll"},{header:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",l,[(0,o.createElementVNode)("label",c,"No. Request: "+(0,o.toDisplayString)(x.kode.noreq),1),(0,o.createElementVNode)("span",d,[u,(0,o.createVNode)(E,{modelValue:x.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return x.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,o.withCtx)((function(){return[p]})),loading:(0,o.withCtx)((function(){return[m]})),footer:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",h,[(0,o.createElementVNode)("div",f,[(0,o.createElementVNode)("div",v,[(0,o.createVNode)(D,{label:"Kembali",class:"p-button-raised p-button p-mr-2 p-mb-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Divisi 1"})})}),(0,o.createVNode)(D,{label:"Approve",class:"p-button-raised p-button-success p-mr-2 p-mb-2",icon:"pi pi-check-square",onClick:t[2]||(t[2]=function(e){return w.Approve()})}),(0,o.createVNode)(D,{label:"Reject",class:"p-button-raised p-button-danger p-mr-2 p-mb-2",icon:"pi pi-times-circle",onClick:t[3]||(t[3]=function(e){return R.dialogReject=!0})})])])])]})),default:(0,o.withCtx)((function(){return[(0,o.createVNode)(q,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"12rem"}}),(0,o.createVNode)(q,{field:"invent_code",header:"Kode",sortable:!0,style:{"min-width":"12rem"}}),(0,o.createVNode)(q,{field:"invent_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"12rem"}})]})),_:1},8,["value","loading","filters"]),(0,o.createVNode)($,{visible:x.dialogReject,"onUpdate:visible":t[7]||(t[7]=function(e){return x.dialogReject=e}),style:{width:"400px"},header:"ICT Request",modal:!0,class:"p-fluid"},{footer:(0,o.withCtx)((function(){return[(0,o.createVNode)(D,{label:"Yes",onClick:t[5]||(t[5]=function(e){return w.updateReject()}),class:"p-button",autofocus:""}),(0,o.createVNode)(D,{label:"No",onClick:t[6]||(t[6]=function(e){return w.cancelReject()}),class:"p-button-text"})]})),default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",g,[(0,o.createElementVNode)("div",k,[b,(0,o.createElementVNode)("div",C,[(0,o.createVNode)(A,{autoResize:!0,type:"text",modelValue:x.reason.ket,"onUpdate:modelValue":t[4]||(t[4]=function(e){return x.reason.ket=e}),rows:"5",cols:"30",placeholder:"Masukan Alasan",class:(0,o.normalizeClass)({"p-invalid":x.submitted&&!x.reason.ket})},null,8,["modelValue","class"]),x.submitted&&!x.reason.ket?((0,o.openBlock)(),(0,o.createElementBlock)("small",N," Alasan Harus Diisi ")):(0,o.createCommentVNode)("",!0)])])])]})),_:1},8,["visible"])])])])}},w=x}}]);