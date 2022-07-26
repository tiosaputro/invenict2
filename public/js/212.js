"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[212],{60212:(e,t,o)=>{o.r(t),o.d(t,{default:()=>x});var a=o(70821),r={class:"grid crud-demo"},n={class:"col-12"},i={class:"card"},s=(0,a.createElementVNode)("h4",null,"ICT Request (Verifikasi) ",-1),l={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},c={style:{width:"110px"}},u={class:"p-input-icon-left"},d=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),p=(0,a.createTextVNode)(" Not Found "),m=(0,a.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),h={class:"p-grid p-dir-col"},f={class:"p-col"},g={class:"box"},v={class:"field grid"},k=(0,a.createElementVNode)("label",{style:{width:"100px"}},"Alasan",-1),b={class:"col-3 md-6"},N={key:0,class:"p-error"};var V=o(6358);const C={data:function(){return{loading:!0,dialogReject:!1,submitted:!1,verif:[],kode:[],reason:{ket:null},filters:{global:{value:null,matchMode:V.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token")}},mounted:function(){this.getIctDetail(),this.getNoreq()},methods:{Approve:function(){var e=this;this.$confirm.require({message:"Approval Permohonan Dilanjutkan?",header:"ICT Request    ",icon:"pi pi-info-circle",acceptClass:"p-button",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){e.$toast.add({severity:"info",summary:"Confirmed",detail:"Permohonan Dilanjutkan"}),e.axios.get("/api/updateStatusPermohonan/"+e.$route.params.code,{headers:{Authorization:"Bearer "+e.token}}),setTimeout((function(){return e.$router.push("/ict-request-desc")}),1e3)},reject:function(){}})},updateReject:function(){var e=this;this.submitted=!0,null!=this.reason.ket&&this.axios.put("/api/updateStatusReject/"+this.$route.params.code,this.reason,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.dialogReject=!1,e.$toast.add({severity:"info",summary:"Confirmed",detail:"Berhasil Direject"}),setTimeout((function(){return e.$router.push("/ict-request-desc")}),1e3)}))},cancelReject:function(){this.dialogReject=!1,this.reason.ket=null,this.submitted=!1},getIctDetail:function(){var e=this;this.axios.get("/api/get-verif/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.verif=t.data,e.loading=!1})).catch((function(t){403==t.response.status?e.$router.push("/access"):401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))}}};const x=(0,o(83744).Z)(C,[["render",function(e,t,o,V,C,x){var w=this,R=(0,a.resolveComponent)("Toast"),y=(0,a.resolveComponent)("ConfirmDialog"),T=(0,a.resolveComponent)("Toolbar"),q=(0,a.resolveComponent)("InputText"),j=(0,a.resolveComponent)("Column"),E=(0,a.resolveComponent)("Button"),P=(0,a.resolveComponent)("DataTable"),$=(0,a.resolveComponent)("Textarea"),D=(0,a.resolveComponent)("Dialog");return(0,a.openBlock)(),(0,a.createElementBlock)("div",r,[(0,a.createElementVNode)("div",n,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(R),(0,a.createVNode)(y),(0,a.createVNode)(T,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[s]})),_:1}),(0,a.createVNode)(P,{value:C.verif,paginator:!0,rows:25,loading:C.loading,filters:C.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Verifikasi)",responsiveLayout:"scroll"},{header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",l,[(0,a.createElementVNode)("label",c,"No. Request: "+(0,a.toDisplayString)(C.kode.noreq),1),(0,a.createElementVNode)("span",u,[d,(0,a.createVNode)(q,{modelValue:C.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return C.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,a.withCtx)((function(){return[p]})),loading:(0,a.withCtx)((function(){return[m]})),footer:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("div",f,[(0,a.createElementVNode)("div",g,[(0,a.createVNode)(E,{label:"Kembali",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Desc"})})}),(0,a.createVNode)(E,{label:"Approve",class:"p-button-raised p-button-success mr-2",icon:"pi pi-check-square",onClick:t[2]||(t[2]=function(e){return x.Approve()})}),(0,a.createVNode)(E,{label:"Reject",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-times-circle",onClick:t[3]||(t[3]=function(e){return w.dialogReject=!0})})])])])]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(j,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(j,{field:"name",header:"Nama Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,a.createVNode)(j,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,a.createVNode)(j,{field:"ireq_remark",header:"keterangan",sortable:!0,style:{"min-width":"12rem"}})]})),_:1},8,["value","loading","filters"]),(0,a.createVNode)(D,{visible:C.dialogReject,"onUpdate:visible":t[7]||(t[7]=function(e){return C.dialogReject=e}),style:{width:"400px"},header:"ICT Request",modal:!0,class:"field grid"},{footer:(0,a.withCtx)((function(){return[(0,a.createVNode)(E,{label:"Yes",onClick:t[5]||(t[5]=function(e){return x.updateReject()}),class:"p-button",autofocus:""}),(0,a.createVNode)(E,{label:"No",onClick:t[6]||(t[6]=function(e){return x.cancelReject()}),class:"p-button-text"})]})),default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",v,[k,(0,a.createElementVNode)("div",b,[(0,a.createVNode)($,{autoResize:!0,type:"text",modelValue:C.reason.ket,"onUpdate:modelValue":t[4]||(t[4]=function(e){return C.reason.ket=e}),rows:"5",cols:"30",placeholder:"Masukan Alasan",class:(0,a.normalizeClass)({"p-invalid":C.submitted&&!C.reason.ket})},null,8,["modelValue","class"]),C.submitted&&!C.reason.ket?((0,a.openBlock)(),(0,a.createElementBlock)("small",N," Alasan Harus Diisi ")):(0,a.createCommentVNode)("",!0)])])]})),_:1},8,["visible"])])])])}]])}}]);