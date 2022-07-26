"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4904],{54904:(e,t,o)=>{o.r(t),o.d(t,{default:()=>q});var r=o(70821),a={class:"grid crud-demo"},i={class:"col-12"},n={class:"card"},l=(0,r.createElementVNode)("h4",null,"ICT Request (Verifikasi) ",-1),s={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},c={style:{width:"150px"}},d={class:"p-input-icon-left"},u=(0,r.createElementVNode)("i",{class:"pi pi-search"},null,-1),p=(0,r.createTextVNode)(" Not Found "),m=(0,r.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),h={class:"p-grid p-dir-col"},f={class:"p-col"},g={class:"box"},v={class:"field grid"},k=(0,r.createElementVNode)("label",{style:{width:"100px"}},"Reason",-1),V={class:"col-3 md-6"},N={key:0,class:"p-error"},b={class:"field"},C={class:"field grid"},x=(0,r.createElementVNode)("label",{class:"col-fixed w-9rem"},"Remark",-1),w={class:"co-fixed w-9rem"};var R=o(6358);const y={data:function(){return{loading:!0,dialogReject:!1,dialogApprove:!1,submitted:!1,verif:[],kode:[],reason:{ket:null,remark:null},filters:{global:{value:null,matchMode:R.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),id:localStorage.getItem("id"),status:null}},mounted:function(){this.getNoreq()},methods:{cek:function(){this.status=this.$route.params.status,"approve"==this.status&&(this.dialogApprove=!0),"reject"==this.status&&(this.dialogReject=!0)},Approve:function(){var e=this;this.$toast.add({severity:"info",summary:"Success Message",detail:"Successfully approved the request"}),this.axios.put("/api/abm/"+this.code,this.reason,{headers:{Authorization:"Bearer "+this.token}}),setTimeout((function(){return e.$router.push("/ict-request-manager")}),1e3)},updateReject:function(){var e=this;this.submitted=!0,null!=this.reason.ket&&this.axios.put("/api/rbm/"+this.code,this.reason,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.dialogReject=!1,e.$toast.add({severity:"info",summary:"Success Message",detail:"Successfully rejected the request"}),setTimeout((function(){return e.$router.push("/ict-request-manager")}),1e3)}))},cancelReject:function(){this.dialogReject=!1,this.reason.ket=null,this.submitted=!1},cancelApprove:function(){this.dialogApprove=!1,this.reason.remark=null},getIctDetail:function(){var e=this;this.axios.get("/api/get-verif/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.verif=t.data,e.loading=!1,e.cek()})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data,e.getIctDetail()}))}}};const q=(0,o(83744).Z)(y,[["render",function(e,t,o,R,y,q){var E=this,T=(0,r.resolveComponent)("Toast"),j=(0,r.resolveComponent)("ConfirmDialog"),P=(0,r.resolveComponent)("Toolbar"),A=(0,r.resolveComponent)("InputText"),I=(0,r.resolveComponent)("Column"),B=(0,r.resolveComponent)("Button"),S=(0,r.resolveComponent)("DataTable"),D=(0,r.resolveComponent)("Textarea"),$=(0,r.resolveComponent)("Dialog");return(0,r.openBlock)(),(0,r.createElementBlock)("div",a,[(0,r.createElementVNode)("div",i,[(0,r.createElementVNode)("div",n,[(0,r.createVNode)(T),(0,r.createVNode)(j,{group:"positionDialog"}),(0,r.createVNode)(P,{class:"p-mb-4"},{start:(0,r.withCtx)((function(){return[l]})),_:1}),(0,r.createVNode)(S,{value:y.verif,paginator:!0,rows:25,loading:y.loading,filters:y.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Verifikasi)",responsiveLayout:"scroll"},{header:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",s,[(0,r.createElementVNode)("label",c,"No. Request: "+(0,r.toDisplayString)(y.kode.noreq),1),(0,r.createElementVNode)("span",d,[u,(0,r.createVNode)(A,{modelValue:y.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return y.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,r.withCtx)((function(){return[p]})),loading:(0,r.withCtx)((function(){return[m]})),footer:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",f,[(0,r.createElementVNode)("div",g,[(0,r.createVNode)(B,{label:"Kembali",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Divisi 1"})})}),"Permohonan"==E.kode.ireq_status?((0,r.openBlock)(),(0,r.createBlock)(B,{key:0,label:"Approve",class:"p-button-raised p-button-success mr-2",icon:"pi pi-check-square",onClick:t[2]||(t[2]=function(e){return q.Approve()})})):(0,r.createCommentVNode)("",!0),"Permohonan"==E.kode.ireq_status?((0,r.openBlock)(),(0,r.createBlock)(B,{key:1,label:"Reject",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-times-circle",onClick:t[3]||(t[3]=function(e){return E.dialogReject=!0})})):(0,r.createCommentVNode)("",!0)])])])]})),default:(0,r.withCtx)((function(){return[(0,r.createVNode)(I,{field:"ireqd_id",header:"No. Detail",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(I,{field:"ireq_type",header:"Request Type",sortable:!0,style:{"min-width":"12rem"}}),(0,r.createVNode)(I,{field:"invent_desc",header:"Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,r.createVNode)(I,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,r.createVNode)(I,{field:"ireq_remark",header:"Remark",sortable:!0,style:{"min-width":"12rem"}})]})),_:1},8,["value","loading","filters"]),(0,r.createVNode)($,{visible:y.dialogReject,"onUpdate:visible":t[7]||(t[7]=function(e){return y.dialogReject=e}),style:{width:"400px"},header:"ICT Request Form Dialog Reject",modal:!0,position:"top",class:"field grid"},{footer:(0,r.withCtx)((function(){return[(0,r.createVNode)(B,{label:"Yes",onClick:t[5]||(t[5]=function(e){return q.updateReject()}),class:"p-button",autofocus:""}),(0,r.createVNode)(B,{label:"No",onClick:t[6]||(t[6]=function(e){return q.cancelReject()}),class:"p-button-text"})]})),default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",v,[k,(0,r.createElementVNode)("div",V,[(0,r.createVNode)(D,{autoResize:!0,type:"text",rows:"5",cols:"20",modelValue:y.reason.ket,"onUpdate:modelValue":t[4]||(t[4]=function(e){return y.reason.ket=e}),placeholder:"Give a reason",class:(0,r.normalizeClass)({"p-invalid":y.submitted&&!y.reason.ket})},null,8,["modelValue","class"]),y.submitted&&!y.reason.ket?((0,r.openBlock)(),(0,r.createElementBlock)("small",N," Reason not filled ")):(0,r.createCommentVNode)("",!0)])])]})),_:1},8,["visible"]),(0,r.createVNode)($,{visible:y.dialogApprove,"onUpdate:visible":t[11]||(t[11]=function(e){return y.dialogApprove=e}),style:{width:"400px"},header:"ICT Request",modal:!0,class:"field"},{footer:(0,r.withCtx)((function(){return[(0,r.createVNode)(B,{label:"Yes",onClick:t[9]||(t[9]=function(t){return e.approve()}),class:"p-button",autofocus:""}),(0,r.createVNode)(B,{label:"No",onClick:t[10]||(t[10]=function(e){return q.cancelApprove()}),class:"p-button-text"})]})),default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",b,[(0,r.createElementVNode)("div",C,[x,(0,r.createElementVNode)("div",w,[(0,r.createVNode)(D,{autoResize:!0,type:"text",modelValue:y.reason.remark,"onUpdate:modelValue":t[8]||(t[8]=function(e){return y.reason.remark=e}),rows:"5",placeholder:"IF Required"},null,8,["modelValue"])])])])]})),_:1},8,["visible"])])])])}]])}}]);