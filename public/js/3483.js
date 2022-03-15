"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3483],{5306:(e,t,r)=>{r.d(t,{Z:()=>n});var a=r(23645),o=r.n(a)()((function(e){return e[1]}));o.push([e.id,".profile-image[data-v-0a202dc2]{box-shadow:0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);width:50px}",""]);const n=o},53483:(e,t,r)=>{r.r(t),r.d(t,{default:()=>b});var a=r(5166);(0,a.pushScopeId)("data-v-0a202dc2");var o={class:"grid"},n={class:"col-18"},i={class:"card"},s=(0,a.createElementVNode)("h4",null,"Management User",-1),l={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},d={class:"p-input-icon-left"},c=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),u=(0,a.createTextVNode)(" Not Found "),p=(0,a.createTextVNode)(" Loading data. Please wait. "),m=["src"];(0,a.popScopeId)();var h=r(6358);const f={data:function(){return{loading:!0,token:localStorage.getItem("token"),user:[],filters:{global:{value:null,matchMode:h.a6.CONTAINS}},checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("User")||e.checkto.includes("/mng-user")?e.getUser():e.$router.push("/access")})):this.$router.push("/login")},getUser:function(){var e=this;this.axios.get("api/get-user",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.user=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},DeleteUser:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("api/delete-user/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getUser()},reject:function(){}})},CetakPdf:function(){window.open("api/report-lookups-pdf")},CetakExcel:function(){window.open("api/report-lookups-excel")}}};var g=r(93379),v=r.n(g),k=r(5306),w={insert:"head",singleton:!1};v()(k.Z,w);k.Z.locals;f.render=function(e,t,r,h,f,g){var v=(0,a.resolveComponent)("Toast"),k=(0,a.resolveComponent)("ConfirmDialog"),w=(0,a.resolveComponent)("Toolbar"),b=(0,a.resolveComponent)("Button"),C=(0,a.resolveComponent)("InputText"),N=(0,a.resolveComponent)("Column"),V=(0,a.resolveComponent)("DataTable"),x=(0,a.resolveDirective)("tooltip");return(0,a.openBlock)(),(0,a.createElementBlock)("div",o,[(0,a.createElementVNode)("div",n,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(v),(0,a.createVNode)(k),(0,a.createVNode)(w,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[s]})),_:1}),(0,a.createVNode)(V,{value:f.user,paginator:!0,rows:10,filters:f.filters,"onUpdate:filters":t[2]||(t[2]=function(e){return f.filters=e}),loading:f.loading,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,25],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} Management User"},{header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",l,[(0,a.createVNode)(b,{label:"Add",class:"p-button-raised",icon:"bi bi-file-earmark-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push("/Add-mng-user")})}),(0,a.createElementVNode)("span",d,[c,(0,a.createVNode)(C,{modelValue:f.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,a.withCtx)((function(){return[u]})),loading:(0,a.withCtx)((function(){return[p]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(N,{field:"usr_id",header:"User ID",sortable:!0,style:{"min-width":"8 rem"}}),(0,a.createVNode)(N,{field:"usr_fullname",header:"User Fullname",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{field:"usr_name",header:"User Name",sortable:!0,style:{"min-width":"8rem"}}),(0,a.createVNode)(N,{field:"usr_email",header:"User Email",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{field:"div_name",header:"User Divisi",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{header:"User Photo",style:{"min-width":"8rem"}},{body:(0,a.withCtx)((function(e){return[(0,a.createElementVNode)("img",{src:"/profile/"+e.data.usr_foto,class:"profile-image"},null,8,m)]})),_:1}),(0,a.createVNode)(N,{field:"usr_stat",header:"User Status",sortable:!0,style:{"min-width":"8rem"}}),(0,a.createVNode)(N,{style:{"min-width":"8rem"}},{body:(0,a.withCtx)((function(t){return[(0,a.withDirectives)((0,a.createVNode)(b,{class:"p-button-rounded p-button-info mr-2",icon:"pi pi-pencil",onClick:function(r){return e.$router.push({name:"Edit Management User",params:{code:t.data.usr_id}})}},null,8,["onClick"]),[[x,"Edit",void 0,{left:!0}]]),(0,a.withDirectives)((0,a.createVNode)(b,{icon:"pi pi-trash",class:"p-button-rounded p-button-danger mr-2",onClick:function(e){return g.DeleteUser(t.data.usr_id)}},null,8,["onClick"]),[[x,"Delete",void 0,{right:!0}]])]})),_:1})]})),_:1},8,["value","filters","loading"])])])])},f.__scopeId="data-v-0a202dc2";const b=f}}]);