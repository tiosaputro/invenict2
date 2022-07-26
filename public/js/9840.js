"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9840],{89840:(e,t,o)=>{o.r(t),o.d(t,{default:()=>h});var r=o(70821),a={class:"grid"},n={class:"col-12"},i={class:"card"},l=(0,r.createElementVNode)("h4",null,"Referensi - Kategori",-1),s={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},c={class:"block mt-2 md:mt-0 p-input-icon-left"},d=(0,r.createElementVNode)("i",{class:"pi pi-search"},null,-1),u=(0,r.createTextVNode)(" Not Found "),p=(0,r.createTextVNode)(" Loading Lookups data. Please wait. ");var f=o(6358);const m={data:function(){return{loading:!0,token:localStorage.getItem("token"),ref:[],filters:{global:{value:null,matchMode:f.a6.CONTAINS}},id:localStorage.getItem("id"),checkname:[],checkto:[]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkto.includes("/referensi-kategori")?e.getRef():e.$router.push("/access")}))},getRef:function(){var e=this;this.axios.get("api/ref-lookup-kategori",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.ref=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},DeleteRef:function(e,t){var o=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){o.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),o.axios.delete("api/delete-ref/"+e+"/"+t,{headers:{Authorization:"Bearer "+o.token}}),o.getRef()},reject:function(){}})}}};const h=(0,o(83744).Z)(m,[["render",function(e,t,o,f,m,h){var g=(0,r.resolveComponent)("Toast"),k=(0,r.resolveComponent)("ConfirmDialog"),v=(0,r.resolveComponent)("Toolbar"),b=(0,r.resolveComponent)("Button"),C=(0,r.resolveComponent)("InputText"),N=(0,r.resolveComponent)("Column"),w=(0,r.resolveComponent)("DataTable"),V=(0,r.resolveDirective)("tooltip");return(0,r.openBlock)(),(0,r.createElementBlock)("div",a,[(0,r.createElementVNode)("div",n,[(0,r.createElementVNode)("div",i,[(0,r.createVNode)(g),(0,r.createVNode)(k),(0,r.createVNode)(v,{class:"mb-4"},{start:(0,r.withCtx)((function(){return[l]})),_:1}),(0,r.createVNode)(w,{value:m.ref,paginator:!0,rows:10,filters:m.filters,"onUpdate:filters":t[2]||(t[2]=function(e){return m.filters=e}),loading:m.loading,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,25],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} Referensi  Kategori",responsiveLayout:"scroll"},{header:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",s,[(0,r.createVNode)(b,{label:"Add",class:"p-button-raised",icon:"bi bi-file-earmark-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push("/Add-referensi-kategori")})}),(0,r.createElementVNode)("span",c,[d,(0,r.createVNode)(C,{modelValue:m.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return m.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,r.withCtx)((function(){return[u]})),loading:(0,r.withCtx)((function(){return[p]})),default:(0,r.withCtx)((function(){return[(0,r.createVNode)(N,{field:"lookup_code",header:"Kode",sortable:!0,style:{"min-width":"10rem"}}),(0,r.createVNode)(N,{field:"lookup_desc",header:"Deskripsi",sortable:!0,style:{"min-width":"10rem"}}),(0,r.createVNode)(N,{field:"lookup_status",header:"Status",sortable:!0,style:{"min-width":"10rem"}}),(0,r.createVNode)(N,{headerStyle:"min-width:10rem"},{body:(0,r.withCtx)((function(t){return[(0,r.withDirectives)((0,r.createVNode)(b,{class:"p-button-rounded p-button-info mr-2",icon:"pi pi-pencil",onClick:function(o){return e.$router.push({name:"Edit Referensi Kategori",params:{code:t.data.lookup_code,type:t.data.lookup_type}})}},null,8,["onClick"]),[[V,"Edit",void 0,{left:!0}]]),(0,r.withDirectives)((0,r.createVNode)(b,{icon:"pi pi-trash",class:"p-button-rounded p-button-danger mr-2",onClick:function(e){return h.DeleteRef(t.data.lookup_code,t.data.lookup_type)}},null,8,["onClick"]),[[V,"Delete",void 0,{Right:!0}]])]})),_:1})]})),_:1},8,["value","filters","loading"])])])])}]])}}]);