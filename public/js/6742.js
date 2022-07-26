"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6742],{36742:(e,t,a)=>{a.r(t),a.d(t,{default:()=>Q});var i=a(70821),s={class:"grid"},o={class:"col-12"},n={class:"card"},l=(0,i.createElementVNode)("div",{class:"my-2"},[(0,i.createElementVNode)("h4",null,"ICT Request (Detail) ")],-1),r={style:{width:"130px"}},d={class:"table-header text-right"},c={class:"p-input-icon-left"},u=(0,i.createElementVNode)("i",{class:"pi pi-search"},null,-1),m=(0,i.createTextVNode)(" Not Found "),g=(0,i.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. "),p={class:"grid dir-col"},h={class:"col"},f={class:"box"},V={class:"field grid"},b=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"No Request",-1),k={class:"col-fixed"},N={class:"field grid"},v=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"No Detail",-1),C={class:"col-fixed"},_={class:"field grid"},q=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Request Type",-1),w={class:"col-fixed"},x={class:"field grid"},y=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Peripheral",-1),E={class:"col-fixed"},A={class:"field grid"},B=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Qty",-1),P={class:"col-fixed"},T={class:"field grid"},R=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Remark",-1),D={class:"col-fixed"},I={key:0,class:"field grid"},S=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Reason",-1),$={class:"col-fixed"},z={key:0,class:"p-error"},L={class:"field grid"},U=(0,i.createElementVNode)("label",{class:"col-fixed w-9rem"},"Petugas (ICT)",-1),j={class:"col-fixed"},F={key:0,class:"p-error"};var O=a(6358);const M={data:function(){return e={submitted:!1,dialogAssign:!1,assign:[],petugas:[],kode:"",show:!1,status:"",loading:!0,detail:[],filters:{global:{value:null,matchMode:O.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")},a=null,(t="code")in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e;var e,t,a},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Reviewer")||e.checkto.includes("/ict-request/reviewer")?(e.getIctDetail(),e.getNoreq()):e.$router.push("/access")})):this.$router.push("/login")},AssignPerDetail:function(e){var t=this;this.axios.get("/api/detail/"+e+"/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(e){t.assign=e.data})),this.axios.get("/api/get-pekerja",{headers:{Authorization:"Bearer "+this.token}}).then((function(e){t.petugas=e.data})),this.dialogAssign=!0},updateAssign:function(){var e=this;this.submitted=!0,this.code=this.assign.ireqd_id,"RT"==this.assign.status?null!=this.assign.ireq_assigned_to1&&this.axios.put("/api/updateAssignPerDetailFromReject/"+this.code,this.assign,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.assign=[],e.dialogAssign=!1,e.submitted=!1,e.$toast.add({severity:"info",summary:"Confirmed",detail:"Berhasil Assign",life:3e3}),e.getIctDetail()})):null!=this.assign.ireq_assigned_to1&&null!=this.assign.ireq_assigned_to1_reason&&this.axios.put("/api/updateAssignPerDetailFromReject/"+this.code,this.assign,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.assign=[],e.dialogAssign=!1,e.submitted=!1,e.$toast.add({severity:"info",summary:"Confirmed",detail:"Berhasil Assign",life:3e3}),e.getIctDetail()}))},Submit:function(e){var t=this;this.$confirm.require({message:"Are you sure you want to submit this request?",header:"ICT Request    ",icon:"pi pi-info-circle",acceptClass:"p-button",acceptLabel:"Yes",rejectLabel:"No",accept:function(){t.$toast.add({severity:"info",summary:"Success Message",detail:"Success Submit",life:1e3}),t.axios.get("/api/appd/"+e+"/"+t.$route.params.code,{headers:{Authorization:"Bearer "+t.token}}),t.getIctDetail()},reject:function(){}})},cancelAssign:function(){this.submitted=!1,this.assign=[],this.dialogAssign=!1},getIctDetail:function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data.noreq,e.status=t.data.cekstatus,"NT"!=e.status&&"RT"!=e.status||(e.show=!0)}))},CetakPdf:function(){window.open("/api/print-out-ict-request/"+this.$route.params.code)}}};const Q=(0,a(83744).Z)(M,[["render",function(e,t,a,O,M,Q){var H=this,K=(0,i.resolveComponent)("Toast"),Y=(0,i.resolveComponent)("ConfirmDialog"),Z=(0,i.resolveComponent)("Toolbar"),G=(0,i.resolveComponent)("InputText"),J=(0,i.resolveComponent)("Column"),W=(0,i.resolveComponent)("Button"),X=(0,i.resolveComponent)("DataTable"),ee=(0,i.resolveComponent)("Textarea"),te=(0,i.resolveComponent)("Dropdown"),ae=(0,i.resolveComponent)("Dialog"),ie=(0,i.resolveDirective)("tooltip");return(0,i.openBlock)(),(0,i.createElementBlock)("div",s,[(0,i.createElementVNode)("div",o,[(0,i.createElementVNode)("div",n,[(0,i.createVNode)(K),(0,i.createVNode)(Y),(0,i.createVNode)(Z,{class:"mb-4"},{start:(0,i.withCtx)((function(){return[l]})),end:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("label",r,"No. Request: "+(0,i.toDisplayString)(H.kode),1)]})),_:1}),(0,i.createVNode)(X,{value:M.detail,paginator:!0,rows:10,loading:M.loading,filters:M.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",d,[(0,i.createElementVNode)("span",c,[u,(0,i.createVNode)(G,{modelValue:M.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return M.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,i.withCtx)((function(){return[m]})),loading:(0,i.withCtx)((function(){return[g]})),footer:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",p,[(0,i.createElementVNode)("div",h,[(0,i.createElementVNode)("div",f,[(0,i.withDirectives)((0,i.createVNode)(W,{label:"Back",class:"p-button-raised p-button mr-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Ict Request Reviewer"})})},null,512),[[ie,"Click to back",void 0,{bottom:!0}]]),(0,i.withDirectives)((0,i.createVNode)(W,{label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return Q.CetakPdf()})},null,512),[[ie,"Click to print out (PDF)",void 0,{bottom:!0}]])])])])]})),default:(0,i.withCtx)((function(){return[(0,i.createVNode)(J,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"8rem"}}),(0,i.createVNode)(J,{field:"name",header:"Peripheral",sortable:!0,style:{"min-width":"8rem"}}),(0,i.createVNode)(J,{field:"ireq_qty",header:"Qty",sortable:!0,style:{"min-width":"6rem"}}),(0,i.createVNode)(J,{field:"ireq_remark",header:"Keterangan",sortable:!0,style:{"min-width":"8rem"}}),(0,i.createVNode)(J,{field:"ireq_assigned_to1",header:"Personnel ICT",sortable:!0,style:{"min-width":"8rem"}}),1==H.show?((0,i.openBlock)(),(0,i.createBlock)(J,{key:0,field:"ireq_assigned_to1_reason",header:"Alasan",sortable:!0,style:{"min-width":"8rem"}})):(0,i.createCommentVNode)("",!0),1==H.show?((0,i.openBlock)(),(0,i.createBlock)(J,{key:1,field:"ireq_assigned_to2",header:"Personnel ICT (2)",sortable:!0,style:{"min-width":"8rem"}})):(0,i.createCommentVNode)("",!0),(0,i.createVNode)(J,{field:"ireq_status",header:"Status",sortable:!0,style:{"min-width":"8rem"}},{body:(0,i.withCtx)((function(e){return[(0,i.createElementVNode)("span",{class:(0,i.normalizeClass)("status-bagde status-"+e.data.status.toLowerCase())},(0,i.toDisplayString)(e.data.ireq_status),3)]})),_:1}),(0,i.createVNode)(J,{style:{"min-width":"17rem"}},{body:(0,i.withCtx)((function(e){return["RT"==e.data.cekstatus?((0,i.openBlock)(),(0,i.createBlock)(W,{key:0,class:"p-button-raised p-button-text mr-2 mt-2",label:"Assign",icon:"pi pi-user-edit",onClick:function(t){return Q.AssignPerDetail(e.data.ireqd_id)}},null,8,["onClick"])):(0,i.createCommentVNode)("",!0),"NT"==e.data.cekstatus?((0,i.openBlock)(),(0,i.createBlock)(W,{key:1,class:"p-button-raised p-button-text mr-2 mt-2",icon:"pi pi-user-edit",label:"Assign",onClick:function(t){return Q.AssignPerDetail(e.data.ireqd_id)}},null,8,["onClick"])):(0,i.createCommentVNode)("",!0),e.data.ireq_assigned_to2&&"RT"==e.data.cekstatus||e.data.ireq_assigned_to2&&"NT"==e.data.cekstatus?((0,i.openBlock)(),(0,i.createBlock)(W,{key:2,class:"p-button-raised p-button-success p-button-text mr-2 mt-2",icon:"pi pi-check",label:"Submit",onClick:function(t){return Q.Submit(e.data.ireqd_id)}},null,8,["onClick"])):(0,i.createCommentVNode)("",!0)]})),_:1})]})),_:1},8,["value","loading","filters"]),(0,i.createVNode)(ae,{visible:M.dialogAssign,"onUpdate:visible":t[13]||(t[13]=function(e){return M.dialogAssign=e}),style:{width:"500px"},header:"Assign Per-Request",modal:!0,closable:!1,class:"fluid"},{footer:(0,i.withCtx)((function(){return[(0,i.createVNode)(W,{label:"Save",onClick:t[11]||(t[11]=function(e){return Q.updateAssign()}),class:"p-button",autofocus:""}),(0,i.createVNode)(W,{label:"Cancel",onClick:t[12]||(t[12]=function(e){return Q.cancelAssign()}),class:"p-button-text"})]})),default:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",V,[b,(0,i.createElementVNode)("div",k,[(0,i.createVNode)(G,{modelValue:M.assign.ireq_no,"onUpdate:modelValue":t[3]||(t[3]=function(e){return M.assign.ireq_no=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",N,[v,(0,i.createElementVNode)("div",C,[(0,i.createVNode)(G,{modelValue:M.assign.ireqd_id,"onUpdate:modelValue":t[4]||(t[4]=function(e){return M.assign.ireqd_id=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",_,[q,(0,i.createElementVNode)("div",w,[(0,i.createVNode)(G,{modelValue:M.assign.ireq_type,"onUpdate:modelValue":t[5]||(t[5]=function(e){return M.assign.ireq_type=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",x,[y,(0,i.createElementVNode)("div",E,[(0,i.createVNode)(G,{modelValue:M.assign.name,"onUpdate:modelValue":t[6]||(t[6]=function(e){return M.assign.name=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",A,[B,(0,i.createElementVNode)("div",P,[(0,i.createVNode)(G,{modelValue:M.assign.ireq_qty,"onUpdate:modelValue":t[7]||(t[7]=function(e){return M.assign.ireq_qty=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",T,[R,(0,i.createElementVNode)("div",D,[(0,i.createVNode)(ee,{modelValue:M.assign.ireq_remark,"onUpdate:modelValue":t[8]||(t[8]=function(e){return M.assign.ireq_remark=e}),disabled:"",autoResize:""},null,8,["modelValue"])])]),"NT"==M.assign.status?((0,i.openBlock)(),(0,i.createElementBlock)("div",I,[S,(0,i.createElementVNode)("div",$,[(0,i.createVNode)(ee,{modelValue:M.assign.ireq_assigned_to1_reason,"onUpdate:modelValue":t[9]||(t[9]=function(e){return M.assign.ireq_assigned_to1_reason=e}),autoResize:!0,rows:"5",cols:"20",placeholder:"Enter Reason",class:(0,i.normalizeClass)({"p-invalid":M.submitted&&!M.assign.ireq_assigned_to1_reason})},null,8,["modelValue","class"])]),M.submitted&&!M.assign.ireq_assigned_to1_reason?((0,i.openBlock)(),(0,i.createElementBlock)("small",z," Reason not filled ")):(0,i.createCommentVNode)("",!0)])):(0,i.createCommentVNode)("",!0),(0,i.createElementVNode)("div",L,[U,(0,i.createElementVNode)("div",j,[(0,i.createVNode)(te,{modelValue:M.assign.ireq_assigned_to1,"onUpdate:modelValue":t[10]||(t[10]=function(e){return M.assign.ireq_assigned_to1=e}),options:M.petugas,optionValue:"name",optionLabel:"name",placeholder:"Select One",class:(0,i.normalizeClass)({"p-invalid":M.submitted&&!M.assign.ireq_assigned_to1})},null,8,["modelValue","options","class"]),M.submitted&&!M.assign.ireq_assigned_to1?((0,i.openBlock)(),(0,i.createElementBlock)("small",F," Petugas(ICT) not filled ")):(0,i.createCommentVNode)("",!0)])])]})),_:1},8,["visible"])])])])}]])}}]);