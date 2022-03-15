"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9838],{47236:(e,t,o)=>{o.d(t,{Z:()=>n});var a=o(23645),r=o.n(a)()((function(e){return e[1]}));r.push([e.id,".table-header[data-v-08da1435]{align-items:center;display:flex;justify-content:space-between}@media screen and (max-width:960px){.table-header[data-v-08da1435]{align-items:start}}",""]);const n=r},79838:(e,t,o)=>{o.r(t),o.d(t,{default:()=>C});var a=o(5166);(0,a.pushScopeId)("data-v-08da1435");var r={class:"grid"},n={class:"col-12"},i={class:"card"},l=(0,a.createElementVNode)("h4",null,"Management Module",-1),d={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},s={class:"p-input-icon-left"},c=(0,a.createElementVNode)("i",{class:"pi pi-search"},null,-1),u=(0,a.createTextVNode)(" Not Found "),m=(0,a.createTextVNode)(" Loading data. Please wait. ");(0,a.popScopeId)();var p=o(6358);const f={data:function(){return{loading:!0,token:localStorage.getItem("token"),modul:[],filters:{global:{value:null,matchMode:p.a6.CONTAINS}}}},created:function(){this.getModule()},methods:{getModule:function(){var e=this;this.axios.get("api/module",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.modul=t.data,e.loading=!1})).catch((function(t){403==t.response.status?(e.$toast.add({severity:"error",summary:"Error",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3)):401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},DeleteModule:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("api/delete-module/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getModule()},reject:function(){}})}}};var h=o(93379),g=o.n(h),v=o(47236),b={insert:"head",singleton:!1};g()(v.Z,b);v.Z.locals;f.render=function(e,t,o,p,f,h){var g=(0,a.resolveComponent)("Toast"),v=(0,a.resolveComponent)("ConfirmDialog"),b=(0,a.resolveComponent)("Toolbar"),C=(0,a.resolveComponent)("Button"),w=(0,a.resolveComponent)("InputText"),N=(0,a.resolveComponent)("Column"),V=(0,a.resolveComponent)("DataTable"),k=(0,a.resolveDirective)("tooltip");return(0,a.openBlock)(),(0,a.createElementBlock)("div",r,[(0,a.createElementVNode)("div",n,[(0,a.createElementVNode)("div",i,[(0,a.createVNode)(g),(0,a.createVNode)(v),(0,a.createVNode)(b,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[l]})),_:1}),(0,a.createVNode)(V,{value:f.modul,paginator:!0,rows:10,filters:f.filters,"onUpdate:filters":t[2]||(t[2]=function(e){return f.filters=e}),loading:f.loading,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,25],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} Management Module"},{header:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("div",d,[(0,a.createVNode)(C,{label:"Add",class:"p-button-raised",icon:"bi bi-file-earmark-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push("/Add-mng-module")})}),(0,a.createElementVNode)("span",s,[c,(0,a.createVNode)(w,{modelValue:f.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,a.withCtx)((function(){return[u]})),loading:(0,a.withCtx)((function(){return[m]})),default:(0,a.withCtx)((function(){return[(0,a.createVNode)(N,{field:"mod_id",header:"Module ID",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{field:"mod_name",header:"Module Name",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{field:"mod_desc",header:"Module Description",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{field:"mod_stat",header:"Module Status",sortable:!0,style:{"min-width":"10rem"}}),(0,a.createVNode)(N,{style:{"min-width":"10rem"}},{body:(0,a.withCtx)((function(t){return[(0,a.withDirectives)((0,a.createVNode)(C,{class:"p-button-rounded p-button-info mr-2",icon:"pi pi-pencil",onClick:function(o){return e.$router.push({name:"Edit Management Module",params:{code:t.data.mod_id}})}},null,8,["onClick"]),[[k,"Edit",void 0,{left:!0}]]),(0,a.withDirectives)((0,a.createVNode)(C,{icon:"pi pi-trash",class:"p-button-rounded p-button-danger mr-2",onClick:function(e){return h.DeleteModule(t.data.mod_id)}},null,8,["onClick"]),[[k,"Delete",void 0,{right:!0}]])]})),_:1})]})),_:1},8,["value","filters","loading"])])])])},f.__scopeId="data-v-08da1435";const C=f}}]);