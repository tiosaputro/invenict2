"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1028],{81028:(e,t,a)=>{a.r(t),a.d(t,{default:()=>k});var o=a(5166),r={class:"p-grid crud-demo"},n={class:"p-col-12"},i={class:"card"},l=(0,o.createElementVNode)("div",{class:"p-grid p-dir-col"},[(0,o.createElementVNode)("div",{class:"p-col"},[(0,o.createElementVNode)("h4",null,"Mutasi Peripheral ICT")])],-1),c={class:"table-header p-d-flex p-flex-column p-flex-md-row p-jc-md-between"},s={class:"p-input-icon-left"},d=(0,o.createElementVNode)("i",{class:"pi pi-search"},null,-1),u=(0,o.createTextVNode)(" Not Found "),p=(0,o.createTextVNode)(" Loading Mutasi Peripheral data. Please wait. "),m={class:"p-grid p-dir-col"},h={class:"p-col"},f={class:"box"};var g=a(6358);const b={data:function(){return{loading:!0,token:localStorage.getItem("token"),mutasi:[],filters:{global:{value:null,matchMode:g.a6.CONTAINS}},checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Mutasi Peripheral")||e.checkto.includes("/mutasi-peripheral")?e.getMutasi():(e.$toast.add({severity:"error",summary:"403",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3))}))},getMutasi:function(){var e=this;this.axios.get("api/mut",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.mutasi=t.data,e.loading=!1})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},DeleteMut:function(e){var t=this;this.$confirm.require({message:"Data ini benar-benar akan dihapus?",header:"Delete Confirmation",icon:"pi pi-info-circle",acceptClass:"p-button-danger",acceptLabel:"Ya",rejectLabel:"Tidak",accept:function(){t.$toast.add({severity:"info",summary:"Confirmed",detail:"Record deleted",life:3e3}),t.axios.delete("api/delete-mut/"+e,{headers:{Authorization:"Bearer "+t.token}}),t.getMutasi()},reject:function(){}})},CetakPdf:function(){window.open("api/report-mutasi-pdf")},CetakExcel:function(){window.open("api/report-mutasi-excel",{headers:{Authorization:"Bearer "+this.token}})}},render:function(e,t,a,g,b,k){var C=(0,o.resolveComponent)("Toast"),v=(0,o.resolveComponent)("ConfirmDialog"),N=(0,o.resolveComponent)("Toolbar"),V=(0,o.resolveComponent)("Button"),x=(0,o.resolveComponent)("InputText"),w=(0,o.resolveComponent)("Column"),P=(0,o.resolveComponent)("DataTable");return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",n,[(0,o.createElementVNode)("div",i,[(0,o.createVNode)(C),(0,o.createVNode)(v),(0,o.createVNode)(N,{class:"p-mb-4"},{left:(0,o.withCtx)((function(){return[l]})),_:1}),(0,o.createVNode)(P,{value:b.mutasi,paginator:!0,rows:10,loading:b.loading,filters:b.filters,rowHover:!0,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,15,20,25,30,35,40,45,50],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} Mutasi Peripheral ICT",responsiveLayout:"scroll"},{header:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",c,[(0,o.createVNode)(V,{label:"Add",class:"p-button-raised",icon:"pi pi-plus",onClick:t[0]||(t[0]=function(t){return e.$router.push("/Add-mutasi-peripheral")})}),(0,o.createElementVNode)("span",s,[d,(0,o.createVNode)(x,{modelValue:b.filters.global.value,"onUpdate:modelValue":t[1]||(t[1]=function(e){return b.filters.global.value=e}),placeholder:"Search. . ."},null,8,["modelValue"])])])]})),empty:(0,o.withCtx)((function(){return[u]})),loading:(0,o.withCtx)((function(){return[p]})),footer:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",m,[(0,o.createElementVNode)("div",h,[(0,o.createElementVNode)("div",f,[(0,o.createVNode)(V,{label:"Pdf",class:"p-button-raised p-button-danger p-mr-2 p-mb-2",icon:"pi pi-file-pdf",onClick:t[2]||(t[2]=function(e){return k.CetakPdf()})}),(0,o.createVNode)(V,{label:"Excel",class:"p-button-raised p-button-success p-mr-2 p-mb-2",icon:"pi pi-print",onClick:t[3]||(t[3]=function(e){return k.CetakExcel()})})])])])]})),default:(0,o.withCtx)((function(){return[(0,o.createVNode)(w,{field:"invent_code",header:"Kode",sortable:!0},{body:(0,o.withCtx)((function(e){return[(0,o.createTextVNode)((0,o.toDisplayString)(e.data.invent_code),1)]})),_:1}),(0,o.createVNode)(w,{field:"invent_desc",header:"Nama",sortable:!0},{body:(0,o.withCtx)((function(e){return[(0,o.createTextVNode)((0,o.toDisplayString)(e.data.invent_desc),1)]})),_:1}),(0,o.createVNode)(w,{field:"imutasi_pengguna",header:"Pengguna",sortable:!0},{body:(0,o.withCtx)((function(e){return[(0,o.createTextVNode)((0,o.toDisplayString)(e.data.imutasi_pengguna),1)]})),_:1}),(0,o.createVNode)(w,{field:"imutasi_lokasi",header:"Lokasi",sortable:!0},{body:(0,o.withCtx)((function(e){return[(0,o.createTextVNode)((0,o.toDisplayString)(e.data.imutasi_lokasi),1)]})),_:1}),(0,o.createVNode)(w,{header:""},{body:(0,o.withCtx)((function(t){return[(0,o.createVNode)(V,{class:"p-button-rounded p-button-info p-mr-2 p-mb-2",icon:"pi pi-pencil",onClick:function(a){return e.$router.push({name:"Edit Mutasi Peripheral",params:{code:t.data.imutasi_id}})}},null,8,["onClick"]),(0,o.createVNode)(V,{icon:"pi pi-trash",class:"p-button-rounded p-button-danger p-mr-2 p-mb-2",onClick:function(e){return k.DeleteMut(t.data.imutasi_id)}},null,8,["onClick"])]})),_:1})]})),_:1},8,["value","loading","filters"])])])])}},k=b}}]);