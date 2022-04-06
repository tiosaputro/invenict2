"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4814],{34814:(e,t,a)=>{a.r(t),a.d(t,{default:()=>I});var l=a(70821),o={class:"container py-4"},n={class:"col-md-10"},r={class:"card"},s=(0,l.createElementVNode)("h4",null,"Pembelian Peripheral",-1),i={class:"card-body"},c={class:"field grid"},u=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Suplier",-1),d={class:"col-3"},p={key:0,class:"p-error"},m={class:"field grid"},h=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Tgl. Pembelian",-1),k={class:"col-12 md:col-4"},V={class:"flex items-center"},g=["value","onClick"],y={key:0,class:"p-error"},f={class:"field grid"},b=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Cara Pembayaran",-1),v={class:"col-4"},N={key:0,class:"p-error"},C={class:"field grid"},E=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Petugas",-1),B={class:"col-4"},P={key:0,class:"p-error"},x={class:"field grid"},_=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Mata Uang",-1),w={class:"col-3"},M={key:0,class:"p-error"},S={class:"field grid"},U=(0,l.createElementVNode)("label",{style:{width:"150px"}},"Keterangan",-1),z={class:"col-4"},D={key:0,class:"p-error"},T={class:"form-group"};function A(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}const $={data:function(){var e;return A(e={checkname:[],checkto:[],id:localStorage.getItem("id"),errors:[],suplier:[],code_money:[],methode_pay:[],purch_date:null,status:null,money:null,petugas:null,purchase_total:null,pay:null,supp:null,ket:null,submitted:!1,stat:[{nama:"Aktif",code:"T"},{nama:"Tidak Aktif",code:"F"}],mask:{input:"DD MMM YYYY"},token:localStorage.getItem("token")},"checkname",[]),A(e,"ceckto",[]),A(e,"id",localStorage.getItem("id")),e},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?(this.petugas=localStorage.getItem("usr_name"),this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Pembelian Peripheral")||e.checkto.includes("/pembelian-peripheral")?(e.getSupplier(),e.getCodeMoney(),e.getMethodePurchase()):e.$router.push("/access")}))):this.$router.push("/login")},getCodeMoney:function(){var e=this;this.axios.get("api/getMataUang",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.code_money=t.data})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getSupplier:function(){var e=this;this.axios.get("api/get-supp",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.suplier=t.data}))},getMethodePurchase:function(){var e=this;this.axios.get("api/getMethodePurch",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.methode_pay=t.data}))},CreatePurch:function(){var e=this;if(this.submitted=!0,null!=this.supp&&null!=this.purch_date&&null!=this.pay&&null!=this.petugas&&null!=this.money&&null!=this.ket){var t=new FormData;t.append("supp",this.supp),t.append("purch_date",this.purch_date),t.append("pay",this.pay),t.append("ket",this.ket),t.append("petugas",this.petugas),t.append("money",this.money),t.append("purchase_total",this.purchase_total),t.append("status",this.status),this.axios.post("api/add-pem",t,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){setTimeout((function(){return e.$router.push("/pembelian-peripheral")}),1e3),e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"})})).catch((function(t){e.submitted=!1,e.errors=t.response.data.errors}))}}},render:function(e,t,a,A,$,I){var L=(0,l.resolveComponent)("Toast"),Y=(0,l.resolveComponent)("Toolbar"),K=(0,l.resolveComponent)("Dropdown"),F=(0,l.resolveComponent)("Button"),j=(0,l.resolveComponent)("DatePicker"),O=(0,l.resolveComponent)("InputText"),R=(0,l.resolveComponent)("Textarea");return(0,l.openBlock)(),(0,l.createElementBlock)("div",o,[(0,l.createElementVNode)("div",n,[(0,l.createVNode)(L),(0,l.createElementVNode)("div",r,[(0,l.createVNode)(Y,{class:"mb-4"},{start:(0,l.withCtx)((function(){return[s]})),_:1}),(0,l.createElementVNode)("div",i,[(0,l.createElementVNode)("form",{onSubmit:t[8]||(t[8]=(0,l.withModifiers)((function(){return I.CreatePurch&&I.CreatePurch.apply(I,arguments)}),["prevent"]))},[(0,l.createElementVNode)("div",c,[u,(0,l.createElementVNode)("div",d,[(0,l.createVNode)(K,{options:$.suplier,optionLabel:"name",optionValue:"code",showClear:!0,filter:!0,modelValue:$.supp,"onUpdate:modelValue":t[0]||(t[0]=function(e){return $.supp=e}),placeholder:"Pilih Suplier",class:(0,l.normalizeClass)({"p-invalid":$.submitted&&!$.supp})},null,8,["options","modelValue","class"]),$.submitted&&!$.supp?((0,l.openBlock)(),(0,l.createElementBlock)("small",p,"Suplier Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",m,[h,(0,l.createElementVNode)("div",k,[(0,l.createVNode)(j,{modelValue:$.purch_date,"onUpdate:modelValue":t[2]||(t[2]=function(e){return $.purch_date=e}),masks:$.mask},{default:(0,l.withCtx)((function(e){var a=e.inputValue,o=e.togglePopover;return[(0,l.createElementVNode)("div",V,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:a,onClick:o,readonly:"",placeholder:"Pilih Tanggal Pembelian"},null,8,g),$.purch_date?((0,l.openBlock)(),(0,l.createBlock)(F,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:t[1]||(t[1]=function(e){return $.purch_date=null})})):((0,l.openBlock)(),(0,l.createBlock)(F,{key:0,icon:"pi pi-calendar",onClick:o},null,8,["onClick"]))])]})),_:1},8,["modelValue","masks"]),$.submitted&&!$.purch_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",y,"Tgl. Pembelian Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",f,[b,(0,l.createElementVNode)("div",v,[(0,l.createVNode)(K,{modelValue:$.pay,"onUpdate:modelValue":t[3]||(t[3]=function(e){return $.pay=e}),options:$.methode_pay,optionLabel:"name",optionValue:"code",showClear:!0,filter:!0,placeholder:"Pilih Cara Pembayaran",class:(0,l.normalizeClass)({"p-invalid":$.submitted&&!$.pay})},null,8,["modelValue","options","class"]),$.submitted&&!$.pay?((0,l.openBlock)(),(0,l.createElementBlock)("small",N,"Cara Pembayaran Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",C,[E,(0,l.createElementVNode)("div",B,[(0,l.createVNode)(O,{type:"text",modelValue:$.petugas,"onUpdate:modelValue":t[4]||(t[4]=function(e){return $.petugas=e}),placeholder:"Masukan Petugas",class:(0,l.normalizeClass)({"p-invalid":$.submitted&&!$.petugas})},null,8,["modelValue","class"]),$.submitted&&!$.petugas?((0,l.openBlock)(),(0,l.createElementBlock)("small",P,"Petugas Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",x,[_,(0,l.createElementVNode)("div",w,[(0,l.createVNode)(K,{modelValue:$.money,"onUpdate:modelValue":t[5]||(t[5]=function(e){return $.money=e}),options:$.code_money,showClear:!0,optionLabel:"name",optionValue:"code",filter:!0,placeholder:"Pilih Mata Uang",class:(0,l.normalizeClass)({"p-invalid":$.submitted&&!$.money})},null,8,["modelValue","options","class"]),$.submitted&&!$.money?((0,l.openBlock)(),(0,l.createElementBlock)("small",M,"Mata Uang Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",S,[U,(0,l.createElementVNode)("div",z,[(0,l.createVNode)(R,{type:"text",modelValue:$.ket,"onUpdate:modelValue":t[6]||(t[6]=function(e){return $.ket=e}),autoResize:!0,rows:"5",cols:"30",placeholder:"Masukan Keterangan",class:(0,l.normalizeClass)({"p-invalid":$.submitted&&!$.ket})},null,8,["modelValue","class"]),$.submitted&&!$.ket?((0,l.openBlock)(),(0,l.createElementBlock)("small",D,"Keterangan Belum Diisi. ")):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",T,[(0,l.createVNode)(F,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,l.createVNode)(F,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:t[7]||(t[7]=function(t){return e.$router.push("/pembelian-peripheral")})})])],32)])])])])}},I=$}}]);