"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2749],{62749:(e,t,a)=>{a.r(t),a.d(t,{default:()=>v});var i=a(70821),o={class:"grid"},r={class:"col-12"},n={class:"card"},l=(0,i.createElementVNode)("h4",null,"Mutasi Peripheral ICT",-1),c={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},s={class:"block mt-2 md:mt-0 p-input-icon-left"},d=(0,i.createElementVNode)("i",{class:"pi pi-search"},null,-1),u=(0,i.createTextVNode)(" Not Found "),p=(0,i.createTextVNode)(" Loading Mutasi Peripheral data. Please wait. "),m={class:"p-grid p-dir-col"},h={class:"p-col"},f={class:"box"};var g=a(6358);const k={data:function(){return{loading:!0,token:localStorage.getItem("token"),mutasi:[],filters:{global:{value:null,matchMode:g.a6.CONTAINS}},checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Mutasi Peripheral")||e.checkto.includes("/mutasi-peripheral")?e.getMutasi():e.$router.push("/access")})):this.$router.push("/login")},getMutasi:function(){var e=this;this.axios.get("api/mut",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.mutasi=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},DeleteMut:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("api/delete-mut/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getMutasi()},reject:function(){}})},CetakPdf:function(){window.open("api/report-mutasi-pdf")},CetakExcel:function(){window.open("api/report-mutasi-excel",{headers:{Authorization:"Bearer "+this.token}})}},render:function(e,t,a,g,k,v){var C=(0,i.resolveComponent)("Toast"),b=(0,i.resolveComponent)("ConfirmDialog"),N=(0,i.resolveComponent)("Toolbar"),V=(0,i.resolveComponent)("Button"),x=(0,i.resolveComponent)("InputText"),w=(0,i.resolveComponent)("Column"),P=(0,i.resolveComponent)("DataTable"),E=(0,i.resolveDirective)("tooltip");return(0,i.openBlock)(),(0,i.createElementBlock)("div",o,[(0,i.createElementVNode)("div",r,[(0,i.createElementVNode)("div",n,[(0,i.createVNode)(C),(0,i.createVNode)(b),(0,i.createVNode)(N,{class:"mb-4"},{start:(0,i.withCtx)((function(){return[l]})),_:1}),(0,i.createVNode)(P,{value:k.mutasi,paginator:!0,rows:10,loading:k.loading,filters:k.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} Mutasi Peripheral ICT",responsiveLayout:"scroll"},{header:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",c,[(0,i.createVNode)(V,{label:"Add",class:"p-button-raised",icon:"bi bi-file-earmark-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push("/Add-mutasi-peripheral")})}),(0,i.createElementVNode)("span",s,[d,(0,i.createVNode)(x,{modelValue:k.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return k.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,i.withCtx)((function(){return[u]})),loading:(0,i.withCtx)((function(){return[p]})),footer:(0,i.withCtx)((function(){return[(0,i.createElementVNode)("div",m,[(0,i.createElementVNode)("div",h,[(0,i.createElementVNode)("div",f,[(0,i.createVNode)(V,{label:"Pdf",class:"p-button-raised p-button-danger mr-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return v.CetakPdf()})}),(0,i.createVNode)(V,{label:"Excel",class:"p-button-raised p-button-success mr-2",icon:"pi pi-print",onClick:t[3]||(t[3]=function(e){return v.CetakExcel()})})])])])]})),default:(0,i.withCtx)((function(){return[(0,i.createVNode)(w,{field:"invent_code",header:"Kode",sortable:!0},{body:(0,i.withCtx)((function(e){return[(0,i.createTextVNode)((0,i.toDisplayString)(e.data.invent_code),1)]})),_:1}),(0,i.createVNode)(w,{field:"invent_desc",header:"Nama",sortable:!0},{body:(0,i.withCtx)((function(e){return[(0,i.createTextVNode)((0,i.toDisplayString)(e.data.invent_desc),1)]})),_:1}),(0,i.createVNode)(w,{field:"imutasi_pengguna",header:"Pengguna",sortable:!0},{body:(0,i.withCtx)((function(e){return[(0,i.createTextVNode)((0,i.toDisplayString)(e.data.imutasi_pengguna),1)]})),_:1}),(0,i.createVNode)(w,{field:"imutasi_lokasi",header:"Lokasi",sortable:!0},{body:(0,i.withCtx)((function(e){return[(0,i.createTextVNode)((0,i.toDisplayString)(e.data.imutasi_lokasi),1)]})),_:1}),(0,i.createVNode)(w,{headerStyle:"min-width:12rem"},{body:(0,i.withCtx)((function(t){return[(0,i.withDirectives)((0,i.createVNode)(V,{class:"p-button-rounded p-button-info mr-2",icon:"pi pi-pencil",onClick:function(a){return e.$router.push({name:"Edit Mutasi Peripheral",params:{code:t.data.imutasi_id}})}},null,8,["onClick"]),[[E,"Edit",void 0,{left:!0}]]),(0,i.withDirectives)((0,i.createVNode)(V,{icon:"pi pi-trash",class:"p-button-rounded p-button-danger mr-2",onClick:function(e){return v.DeleteMut(t.data.imutasi_id)}},null,8,["onClick"]),[[E,"Delete",void 0,{Right:!0}]])]})),_:1})]})),_:1},8,["value","loading","filters"])])])])}},v=k}}]);