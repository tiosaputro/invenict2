"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6476],{76476:(e,t,a)=>{a.r(t),a.d(t,{default:()=>P});var i=a(5166),o={class:"grid crud-demo"},r={class:"col-12"},l={class:"card"},n=(0,i.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),s={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},d={style:{width:"110px"}},u={class:"p-input-icon-left"},c=(0,i.createElementVNode)("i",{class:"pi pi-search"},null,-1),m=(0,i.createTextVNode)(" Not Found "),h=(0,i.createTextVNode)(" Loading data. Please wait. "),p={class:"p-grid p-dir-col"},f={class:"col"},g={class:"box"},V={class:"field grid"},N=(0,i.createElementVNode)("label",{style:{width:"100px"}},"No. Request",-1),v={class:"col-3 md-6"},b={class:"field grid"},C=(0,i.createElementVNode)("label",{style:{width:"100px"}},"Nama Peripheral",-1),k={class:"col-3 md-6"},w={class:"field grid"},x=(0,i.createElementVNode)("label",{style:{width:"100px"}},"Status",-1),E={class:"col-3 md-6"},D={key:0,class:"p-error"};var y=a(6358);const q={data:function(){return{submitted:!1,loading:!0,dialogEdit:!1,detail:[],status:[],editDetail:[],kode:[],filters:{global:{value:null,matchMode:y.a6.CONTAINS}},token:localStorage.getItem("token"),user:[]}},created:function(){this.getUser()},methods:{getUser:function(){var e=this;this.axios.get("/api/user",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.user=t.data,e.create()}))},create:function(){this.getIctDetail(),this.getNoreq()},edit:function(e){var t=this;this.dialogEdit=!0,this.axios.get("/api/detail/"+e,{headers:{Authorization:"Bearer "+this.token}}).then((function(e){t.editDetail=e.data})),this.getStatus()},getStatus:function(){var e=this;this.axios.get("/api/getStatusIct",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.status=t.data}))},cancel:function(){this.dialogEdit=!1,this.status=[],this.editDetail=[],this.submitted=!1},submit:function(){var e=this;this.submitted=!0,null!=this.editDetail.status&&this.axios.put("/api/update-status-done/"+this.$route.params.code,this.editDetail,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success",detail:"Status Berhasil Dirubah",life:3e3}),e.cancel(),e.create()}))},getIctDetail:function(){var e=this;this.axios.get("/api/get-detail-done/"+this.$route.params.code+"/"+this.user.usr_fullname,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){403==t.response.status?e.$router.push("/access"):401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))}},render:function(e,t,a,y,q,P){var S=(0,i.resolveComponent)("Toast"),T=(0,i.resolveComponent)("ConfirmDialog"),B=(0,i.resolveComponent)("Toolbar"),_=(0,i.resolveComponent)("InputText"),I=(0,i.resolveComponent)("Column"),L=(0,i.resolveComponent)("Button"),R=(0,i.resolveComponent)("DataTable"),$=(0,i.resolveComponent)("Dropdown"),z=(0,i.resolveComponent)("Dialog"),A=(0,i.resolveDirective)("tooltip");return(0,i.openBlock)(),(0,i.createElementBlock)("div",o,[(0,i.createElementVNode)("div",r,[(0,i.createElementVNode)("div",l,[(0,i.createVNode)(S),(0,i.createVNode)(T),(0,i.createVNode)(B,{class:"mb-4"},{start:(0,i.withCtx)((function(){return[n]})),_:1}),(0,i.createVNode)(R,{value:q.detail,paginator:!0,rows:25,loading:q.loading,filters:q.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",s,[(0,i.createElementVNode)("label",d,"No. Request: "+(0,i.toDisplayString)(q.kode.noreq),1),(0,i.createElementVNode)("span",u,[c,(0,i.createVNode)(_,{modelValue:q.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return q.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,i.withCtx)((function(){return[m]})),loading:(0,i.withCtx)((function(){return[h]})),footer:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",p,[(0,i.createElementVNode)("div",f,[(0,i.createElementVNode)("div",g,[(0,i.createVNode)(L,{label:"Kembali",class:"p-button-raised p-button p-mr-2 p-mb-2",icon:"pi pi-chevron-left",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Desc"})})})])])])]})),default:(0,i.withCtx)((function(){return[(0,i.createVNode)(I,{field:"name",header:"Nama Peripheral",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(I,{field:"ireq_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(I,{field:"ireq_type",header:"Tipe Request",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(I,{field:"ireq_assigned_to",header:"Petugas (ICT)",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(I,{field:"ireq_status",header:"Status",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(I,{style:{"min-width":"12rem"}},{body:(0,i.withCtx)((function(e){return["Penugasan"==e.data.ireq_status?(0,i.withDirectives)(((0,i.openBlock)(),(0,i.createBlock)(L,{key:0,class:"p-button-rounded p-button-info p-mr-2 p-mb-2",icon:"pi pi-pencil",onClick:function(t){return P.edit(e.data.ireqd_id)}},null,8,["onClick"])),[[A,"Edit",void 0,{left:!0}]]):(0,i.createCommentVNode)("",!0)]})),_:1})]})),_:1},8,["value","loading","filters"]),(0,i.createVNode)(z,{visible:q.dialogEdit,"onUpdate:visible":t[7]||(t[7]=function(e){return q.dialogEdit=e}),style:{width:"400px"},header:"ICT Request",modal:!0,class:"field grid"},{footer:(0,i.withCtx)((function(){return[(0,i.createVNode)(L,{label:"Yes",onClick:t[5]||(t[5]=function(e){return P.submit()}),class:"p-button",autofocus:""}),(0,i.createVNode)(L,{label:"No",onClick:t[6]||(t[6]=function(e){return P.cancel()}),class:"p-button-text"})]})),default:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",V,[N,(0,i.createElementVNode)("div",v,[(0,i.createVNode)(_,{modelValue:q.editDetail.ireq_no,"onUpdate:modelValue":t[2]||(t[2]=function(e){return q.editDetail.ireq_no=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",b,[C,(0,i.createElementVNode)("div",k,[(0,i.createVNode)(_,{modelValue:q.editDetail.name,"onUpdate:modelValue":t[3]||(t[3]=function(e){return q.editDetail.name=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",w,[x,(0,i.createElementVNode)("div",E,[(0,i.createVNode)($,{modelValue:q.editDetail.status,"onUpdate:modelValue":t[4]||(t[4]=function(e){return q.editDetail.status=e}),options:q.status,optionLabel:"name",optionValue:"code",showClear:!0,class:(0,i.normalizeClass)({"p-invalid":q.submitted&&!q.editDetail.status})},null,8,["modelValue","options","class"]),q.submitted&&!q.editDetail.status?((0,i.openBlock)(),(0,i.createElementBlock)("small",D,"Status Wajib Diisi. ")):(0,i.createCommentVNode)("",!0)])])]})),_:1},8,["visible"])])])])}},P=q}}]);