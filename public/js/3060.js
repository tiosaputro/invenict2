"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3060],{43060:(e,t,a)=>{a.r(t),a.d(t,{default:()=>q});var i=a(5166),o={class:"grid crud-demo"},n={class:"col-12"},s={class:"card"},l=(0,i.createElementVNode)("h4",null,"ICT Request (Detail) ",-1),r={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},d={style:{width:"110px"}},c={class:"p-input-icon-left"},u=(0,i.createElementVNode)("i",{class:"pi pi-search"},null,-1),g=(0,i.createTextVNode)(" Not Found "),m=(0,i.createTextVNode)(" Loading data. Please wait. "),h={class:"grid dir-col"},p={class:"col"},f={class:"box"},V={class:"field grid"},b=(0,i.createElementVNode)("label",{style:{width:"100px"}},"No Request",-1),N={class:"col-3 md-6"},v={class:"field grid"},C=(0,i.createElementVNode)("label",{style:{width:"100px"}},"Kode",-1),k={class:"col-3 md-6"},w={class:"field grid"},x=(0,i.createElementVNode)("label",{style:{width:"100px"}},"Petugas (ICT)",-1),A={class:"col-3 md-6"},E={key:0,class:"p-error"};var P,y=a(6358);function _(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}const D={data:function(){return{submitted:!1,loading:!0,dialogAssign:!1,detail:[],petugas:[],assign:[],kode:[],filters:{global:{value:null,matchMode:y.a6.CONTAINS}},code:this.$route.params.code,token:localStorage.getItem("token")}},mounted:function(){this.getIctDetail(),this.getNoreq()},methods:(P={cancelAssign:function(){this.assign=[],this.petugas=[],this.dialogAssign=!1},AssignPerDetail:function(e){var t=this;this.axios.get("/api/detail/"+e,{headers:{Authorization:"Bearer "+this.token}}).then((function(e){t.assign=e.data})),this.axios.get("/api/get-pekerja",{headers:{Authorization:"Bearer "+this.token}}).then((function(e){t.petugas=e.data})),this.dialogAssign=!0}},_(P,"cancelAssign",(function(){this.submitted=!1,this.assign=[],this.dialogAssign=!1})),_(P,"updateAssign",(function(){var e=this;this.submitted=!0,null!=this.assign.name&&this.axios.put("/api/updateAssignPerDetail/"+this.$route.params.code,this.assign,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.assign=[],e.dialogAssign=!1,e.submitted=!1,e.$toast.add({severity:"info",summary:"Confirmed",detail:"Berhasil Assign",life:3e3}),e.getIctDetail()}))})),_(P,"getIctDetail",(function(){var e=this;this.axios.get("/api/ict-detail/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.loading=!1})).catch((function(t){403==t.response.status?e.$router.push("/access"):401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))})),_(P,"getNoreq",(function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kode=t.data}))})),P),render:function(e,t,a,P,y,_){var D=(0,i.resolveComponent)("Toast"),q=(0,i.resolveComponent)("ConfirmDialog"),T=(0,i.resolveComponent)("Toolbar"),I=(0,i.resolveComponent)("InputText"),B=(0,i.resolveComponent)("Column"),L=(0,i.resolveComponent)("Button"),R=(0,i.resolveComponent)("DataTable"),S=(0,i.resolveComponent)("Dropdown"),$=(0,i.resolveComponent)("Dialog");return(0,i.openBlock)(),(0,i.createElementBlock)("div",o,[(0,i.createElementVNode)("div",n,[(0,i.createElementVNode)("div",s,[(0,i.createVNode)(D),(0,i.createVNode)(q),(0,i.createVNode)(T,{class:"mb-4"},{start:(0,i.withCtx)((function(){return[l]})),_:1}),(0,i.createVNode)(R,{value:y.detail,paginator:!0,rows:25,loading:y.loading,filters:y.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} ICT Request (Detail)",responsiveLayout:"scroll"},{header:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",r,[(0,i.createElementVNode)("label",d,"No. Request: "+(0,i.toDisplayString)(y.kode.noreq),1),(0,i.createElementVNode)("span",c,[u,(0,i.createVNode)(I,{modelValue:y.filters.global.value,"onUpdate:modelValue":t[0]||(t[0]=function(e){return y.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,i.withCtx)((function(){return[g]})),loading:(0,i.withCtx)((function(){return[m]})),footer:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",h,[(0,i.createElementVNode)("div",p,[(0,i.createElementVNode)("div",f,[(0,i.createVNode)(L,{label:"Kembali",class:"p-button-raised p-button mr-2",icon:"bi bi-skip-backward-fill",onClick:t[1]||(t[1]=function(t){return e.$router.push({name:"Desc"})})})])])])]})),default:(0,i.withCtx)((function(){return[(0,i.createVNode)(B,{field:"invent_code",header:"Kode",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(B,{field:"invent_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(B,{field:"ireq_assigned_to",header:"Petugas (ICT)",sortable:!0,style:{"min-width":"12rem"}}),(0,i.createVNode)(B,{style:{"min-width":"12rem"}},{body:(0,i.withCtx)((function(e){return[(0,i.createVNode)(L,{class:"p-button-raised p-button-text mr-2",label:"Assign",onClick:function(t){return _.AssignPerDetail(e.data.ireqd_id)}},null,8,["onClick"])]})),_:1})]})),_:1},8,["value","loading","filters"]),(0,i.createVNode)($,{visible:y.dialogAssign,"onUpdate:visible":t[7]||(t[7]=function(e){return y.dialogAssign=e}),style:{width:"400px"},header:"Assign Per-Detail Request",modal:!0,closable:!1,class:"field grid"},{footer:(0,i.withCtx)((function(){return[(0,i.createVNode)(L,{label:"Simpan",onClick:t[5]||(t[5]=function(e){return _.updateAssign()}),class:"p-button",autofocus:""}),(0,i.createVNode)(L,{label:"Cancel",onClick:t[6]||(t[6]=function(e){return _.cancelAssign()}),class:"p-button-text"})]})),default:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",V,[b,(0,i.createElementVNode)("div",N,[(0,i.createVNode)(I,{modelValue:y.assign.ireq_no,"onUpdate:modelValue":t[2]||(t[2]=function(e){return y.assign.ireq_no=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",v,[C,(0,i.createElementVNode)("div",k,[(0,i.createVNode)(I,{modelValue:y.assign.name,"onUpdate:modelValue":t[3]||(t[3]=function(e){return y.assign.name=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",w,[x,(0,i.createElementVNode)("div",A,[(0,i.createVNode)(S,{modelValue:y.assign.ireq_assigned_to,"onUpdate:modelValue":t[4]||(t[4]=function(e){return y.assign.ireq_assigned_to=e}),options:y.petugas,optionValue:"name",optionLabel:"name",placeholder:"Pilih Petugas (ICT)",class:(0,i.normalizeClass)({"p-invalid":y.submitted&&!y.assign.ireq_assigned_to})},null,8,["modelValue","options","class"]),y.submitted&&!y.assign.ireq_assigned_to?((0,i.openBlock)(),(0,i.createElementBlock)("small",E," Petugas(ICT) Harus Diisi ")):(0,i.createCommentVNode)("",!0)])])]})),_:1},8,["visible"])])])])}},q=D}}]);