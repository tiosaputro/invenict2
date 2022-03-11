"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7104],{85042:(e,t,a)=>{a.d(t,{Z:()=>l});var r=a(23645),o=a.n(r)()((function(e){return e[1]}));o.push([e.id,".pegawai-image[data-v-3d850ba3]{box-shadow:0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);width:100px}",""]);const l=o},87104:(e,t,a)=>{a.r(t),a.d(t,{default:()=>q});var r=a(5166);(0,r.pushScopeId)("data-v-3d850ba3");var o={class:"container py-4"},l={class:"col-md-10"},c={class:"card"},s=(0,r.createElementVNode)("div",{class:"p-grid p-dir-col"},[(0,r.createElementVNode)("div",{class:"p-col"},[(0,r.createElementVNode)("h4",null,"Pembelian Peripheral")])],-1),n={class:"card-body"},i={class:"p-fluid"},p={class:"p-field p-grid"},d=(0,r.createElementVNode)("label",{for:"tipe",class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Suplier",-1),u={class:"p-col-4"},m={key:0,class:"p-error"},h={class:"p-fluid"},b={class:"p-field p-grid"},V=(0,r.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Tgl. Pembelian",-1),k={class:"p-col-10 p-md-6"},g={class:"p-inputgroup"},v=["value","onClick"],f={key:0,class:"p-error"},N={class:"p-fluid"},y={class:"p-field p-grid"},_=(0,r.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Cara Pembayaran",-1),E={class:"p-col-4"},C={key:0,class:"p-error"},P={class:"p-fluid"},x={class:"p-field p-grid"},B=(0,r.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Petugas",-1),w={class:"p-col-4"},M={key:0,class:"p-error"},S={class:"p-fluid"},T={class:"p-field p-grid"},U=(0,r.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Mata Uang",-1),z={class:"p-col-3"},A={key:0,class:"p-error"},D={class:"p-fluid"},$={class:"p-field p-grid"},I=(0,r.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"150px"}},"Keterangan",-1),j={class:"p-col-4"},W={key:0,class:"p-error"},L={class:"form-group"};(0,r.popScopeId)();const Y={data:function(){return{purch:[],suplier:[],code_money:[],methode_pay:[],errors:[],submitted:!1,checkname:[],checkto:[],id:localStorage.getItem("id"),stat:[{nama:"Aktif",code:"T"},{nama:"Tidak Aktif",code:"F"}],mask:{input:"DD MMM YYYY"},token:localStorage.getItem("token")}},created:function(){this.cekUser(),this.getPurch()},methods:{cekUser:function(){var e=this;this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Pembelian Peripheral")||e.checkto.includes("/pembelian-peripheral")?e.getPurch():(e.$toast.add({severity:"error",summary:"403",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3))}))},getPurch:function(){var e=this;this.axios.get("/api/edit-pem/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.purch=t.data,e.getSupplier(),e.getCodeMoney(),e.getMethodePurchase()})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getCodeMoney:function(){var e=this;this.axios.get("/api/getMataUang",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.code_money=t.data}))},getSupplier:function(){var e=this;this.axios.get("/api/get-supp",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.suplier=t.data}))},getMethodePurchase:function(){var e=this;this.axios.get("/api/getMethodePurch",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.methode_pay=t.data}))},CreatePurch:function(){var e=this;this.submitted=!0,null!=this.purch.suplier_code&&null!=this.purch.purchase_date&&null!=this.purch.purchase_pay_methode&&null!=this.purch.purchase_petugas&&null!=this.purch.valuta_code&&null!=this.purch.purchase_remark&&this.axios.put("/api/update-pem/"+this.$route.params.code,this.purch,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){setTimeout((function(){return e.$router.push("/pembelian-peripheral")}),1e3),e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Update"})})).catch((function(t){e.errors=t.response.data.errors,e.submitted=!1}))}}};var K=a(93379),Z=a.n(K),F=a(85042),R={insert:"head",singleton:!1};Z()(F.Z,R);F.Z.locals;Y.render=function(e,t,a,Y,K,Z){var F=(0,r.resolveComponent)("Toast"),R=(0,r.resolveComponent)("Toolbar"),q=(0,r.resolveComponent)("Dropdown"),G=(0,r.resolveComponent)("Button"),H=(0,r.resolveComponent)("DatePicker"),J=(0,r.resolveComponent)("InputText"),O=(0,r.resolveComponent)("Textarea");return(0,r.openBlock)(),(0,r.createElementBlock)("div",o,[(0,r.createElementVNode)("div",l,[(0,r.createVNode)(F),(0,r.createElementVNode)("div",c,[(0,r.createVNode)(R,{class:"p-mb-4"},{left:(0,r.withCtx)((function(){return[s]})),_:1}),(0,r.createElementVNode)("div",n,[(0,r.createElementVNode)("form",{onSubmit:t[8]||(t[8]=(0,r.withModifiers)((function(){return Z.CreatePurch&&Z.CreatePurch.apply(Z,arguments)}),["prevent"]))},[(0,r.createElementVNode)("div",i,[(0,r.createElementVNode)("div",p,[d,(0,r.createElementVNode)("div",u,[(0,r.createVNode)(q,{options:K.suplier,optionLabel:"name",optionValue:"code",showClear:!0,filter:!0,modelValue:K.purch.suplier_code,"onUpdate:modelValue":t[0]||(t[0]=function(e){return K.purch.suplier_code=e}),placeholder:"Pilih Suplier",class:(0,r.normalizeClass)({"p-invalid":K.submitted&&!K.purch.suplier_code})},null,8,["options","modelValue","class"]),K.submitted&&!K.purch.suplier_code?((0,r.openBlock)(),(0,r.createElementBlock)("small",m)):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",b,[V,(0,r.createElementVNode)("div",k,[(0,r.createElementVNode)("div",g,[(0,r.createVNode)(H,{modelValue:K.purch.purchase_date,"onUpdate:modelValue":t[2]||(t[2]=function(e){return K.purch.purchase_date=e}),masks:K.mask},{default:(0,r.withCtx)((function(e){var a=e.inputValue,o=e.togglePopover;return[(0,r.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:a,onClick:o,readonly:"",placeholder:"Pilih Tanggal Pembelian"},null,8,v),K.purch.purchase_date?((0,r.openBlock)(),(0,r.createBlock)(G,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:t[1]||(t[1]=function(e){return K.purch.purchase_date=null})})):((0,r.openBlock)(),(0,r.createBlock)(G,{key:0,icon:"pi pi-calendar",onClick:o},null,8,["onClick"]))]})),_:1},8,["modelValue","masks"])]),K.submitted&&!K.purch.purchase_date?((0,r.openBlock)(),(0,r.createElementBlock)("small",f,"Tgl. Pembelian Wajib Diisi. ")):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",N,[(0,r.createElementVNode)("div",y,[_,(0,r.createElementVNode)("div",E,[(0,r.createVNode)(q,{modelValue:K.purch.purchase_pay_methode,"onUpdate:modelValue":t[3]||(t[3]=function(e){return K.purch.purchase_pay_methode=e}),options:K.methode_pay,optionLabel:"name",optionValue:"code",showClear:!0,filter:!0,placeholder:"Pilih Cara Pembayaran",class:(0,r.normalizeClass)({"p-invalid":K.submitted&&!K.purch.purchase_pay_methode})},null,8,["modelValue","options","class"]),K.submitted&&!K.purch.purchase_pay_methode?((0,r.openBlock)(),(0,r.createElementBlock)("small",C,"Cara Pembayaran Wajib Diisi. ")):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",P,[(0,r.createElementVNode)("div",x,[B,(0,r.createElementVNode)("div",w,[(0,r.createVNode)(J,{type:"text",modelValue:K.purch.purchase_petugas,"onUpdate:modelValue":t[4]||(t[4]=function(e){return K.purch.purchase_petugas=e}),placeholder:"Masukan Petugas",class:(0,r.normalizeClass)({"p-invalid":K.submitted&&!K.purch.purchase_petugas})},null,8,["modelValue","class"]),K.submitted&&!K.purch.purchase_petugas?((0,r.openBlock)(),(0,r.createElementBlock)("small",M,"Petugas Wajib Diisi. ")):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",S,[(0,r.createElementVNode)("div",T,[U,(0,r.createElementVNode)("div",z,[(0,r.createVNode)(q,{modelValue:K.purch.valuta_code,"onUpdate:modelValue":t[5]||(t[5]=function(e){return K.purch.valuta_code=e}),options:K.code_money,showClear:!0,optionLabel:"name",optionValue:"code",filter:!0,placeholder:"Pilih Mata Uang",class:(0,r.normalizeClass)({"p-invalid":K.submitted&&!K.purch.valuta_code})},null,8,["modelValue","options","class"]),K.submitted&&!K.purch.valuta_code?((0,r.openBlock)(),(0,r.createElementBlock)("small",A,"Mata Uang Wajib Diisi. ")):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",D,[(0,r.createElementVNode)("div",$,[I,(0,r.createElementVNode)("div",j,[(0,r.createVNode)(O,{type:"text",modelValue:K.purch.purchase_remark,"onUpdate:modelValue":t[6]||(t[6]=function(e){return K.purch.purchase_remark=e}),autoResize:!0,rows:"5",cols:"30",placeholder:"Masukan Keterangan",class:(0,r.normalizeClass)({"p-invalid":K.submitted&&!K.purch.purchase_remark})},null,8,["modelValue","class"]),K.submitted&&!K.purch.purchase_remark?((0,r.openBlock)(),(0,r.createElementBlock)("small",W,"Keterangan Wajib Diisi. ")):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",L,[(0,r.createVNode)(G,{class:"p-button-rounded p-button-primary p-mr-2 p-mb-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,r.createVNode)(G,{label:"Cancel",class:"p-button-rounded p-button-secondary p-mr-2 p-mb-2",icon:"pi pi-times",onClick:t[7]||(t[7]=function(t){return e.$router.push("/pembelian-peripheral")})})])],32)])])])])},Y.__scopeId="data-v-3d850ba3";const q=Y}}]);