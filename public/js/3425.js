"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3425],{33425:(e,l,r)=>{r.r(l),r.d(l,{default:()=>Y});var t=r(70821),s={class:"card"},a=(0,t.createElementVNode)("h4",null,"Referensi - Suplier",-1),o={class:"card-body"},i={class:"formgroup-inline"},n={class:"field grid"},p=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Kode",-1),c={class:"field grid"},d=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Nama",-1),u={class:"col-5"},m={key:0,class:"p-error"},V={class:"field grid"},N=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Contact Person",-1),h={class:"col-3"},f={key:0,class:"p-error"},v={class:"field grid"},y=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Alamat 1",-1),E={class:"col-3"},k={key:0,class:"p-error"},_={class:"field grid"},g=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Alamat 2",-1),x={class:"col-4"},C={class:"formgroup-inline"},b={class:"field grid"},S=(0,t.createElementVNode)("label",{style:{width:"115px"}},"Kota",-1),B={class:"col-3"},U={key:0,class:"p-error"},w={class:"field grid"},z=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Provinsi",-1),D={class:"col-4"},T={key:0,class:"p-error"},$={class:"field grid"},A=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Email",-1),I={class:"col-3"},M={key:0,class:"p-error"},R={class:"field grid"},K=(0,t.createElementVNode)("label",{style:{width:"120px"}},"Fax",-1),O={class:"col-3"},P={key:0,class:"p-error"},F={class:"field grid"},L=(0,t.createElementVNode)("label",{style:{width:"120px"}},"No.Tlp-1",-1),j={class:"col-3"},q={key:0,class:"p-error"},G={class:"field grid"},H=(0,t.createElementVNode)("label",{style:{width:"120px"}},"No.Tlp-2",-1),J={class:"col-3"},Q={key:0,class:"p-error"},W={class:"form-group"};const X={data:function(){return{errors:[],supp:[],token:localStorage.getItem("token"),id:localStorage.getItem("id"),checkname:[],checkto:[]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(l){e.checkto=l.data.map((function(e){return e.to})),e.checkname=l.data.map((function(e){return e.name})),e.checkname.includes("Suplier")||e.checkto.includes("/referensi-supplier")?e.getSupp():e.$router.push("/access")})):this.$router.push("/login")},getSupp:function(){var e=this;this.axios.get("/api/edit-supp/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(l){e.supp=l.data})).catch((function(l){401==l.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},UpdateSupplier:function(){var e=this;this.errors=[],this.axios.put("/api/update-supp/"+this.$route.params.code,this.supp,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Update"}),setTimeout((function(){return e.$router.push("/referensi-supplier")}),1e3)})).catch((function(l){e.errors=l.response.data.errors}))}},render:function(e,l,r,X,Y,Z){var ee=(0,t.resolveComponent)("Toast"),le=(0,t.resolveComponent)("Toolbar"),re=(0,t.resolveComponent)("InputText"),te=(0,t.resolveComponent)("Textarea"),se=(0,t.resolveComponent)("Button");return(0,t.openBlock)(),(0,t.createElementBlock)("div",null,[(0,t.createVNode)(ee),(0,t.createElementVNode)("div",s,[(0,t.createVNode)(le,{class:"mb-4"},{start:(0,t.withCtx)((function(){return[a]})),_:1}),(0,t.createElementVNode)("div",o,[(0,t.createElementVNode)("form",{onSubmit:l[12]||(l[12]=(0,t.withModifiers)((function(){return Z.UpdateSupplier&&Z.UpdateSupplier.apply(Z,arguments)}),["prevent"]))},[(0,t.createElementVNode)("div",i,[(0,t.createElementVNode)("div",n,[p,(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_code,"onUpdate:modelValue":l[0]||(l[0]=function(e){return Y.supp.suplier_code=e}),disabled:""},null,8,["modelValue"])]),(0,t.createElementVNode)("div",c,[d,(0,t.createElementVNode)("div",u,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_name,"onUpdate:modelValue":l[1]||(l[1]=function(e){return Y.supp.suplier_name=e}),autofocus:"",class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_name})},null,8,["modelValue","class"]),Y.errors.suplier_name?((0,t.openBlock)(),(0,t.createElementBlock)("small",m,(0,t.toDisplayString)(Y.errors.suplier_name[0]),1)):(0,t.createCommentVNode)("",!0)])])]),(0,t.createElementVNode)("div",V,[N,(0,t.createElementVNode)("div",h,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_contact,"onUpdate:modelValue":l[2]||(l[2]=function(e){return Y.supp.suplier_contact=e}),class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_contact})},null,8,["modelValue","class"]),Y.errors.suplier_contact?((0,t.openBlock)(),(0,t.createElementBlock)("small",f,(0,t.toDisplayString)(Y.errors.suplier_contact[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",v,[y,(0,t.createElementVNode)("div",E,[(0,t.createVNode)(te,{type:"text",modelValue:Y.supp.suplier_address1,"onUpdate:modelValue":l[3]||(l[3]=function(e){return Y.supp.suplier_address1=e}),autoResize:!0,class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_address1})},null,8,["modelValue","class"]),Y.errors.suplier_address1?((0,t.openBlock)(),(0,t.createElementBlock)("small",k,(0,t.toDisplayString)(Y.errors.suplier_address1[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",_,[g,(0,t.createElementVNode)("div",x,[(0,t.createVNode)(te,{type:"text",modelValue:Y.supp.suplier_address2,"onUpdate:modelValue":l[4]||(l[4]=function(e){return Y.supp.suplier_address2=e}),autoResize:!0,placeholder:"(Optional)"},null,8,["modelValue"])])]),(0,t.createElementVNode)("div",C,[(0,t.createElementVNode)("div",b,[S,(0,t.createElementVNode)("div",B,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_city,"onUpdate:modelValue":l[5]||(l[5]=function(e){return Y.supp.suplier_city=e}),class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_city})},null,8,["modelValue","class"]),Y.errors.suplier_city?((0,t.openBlock)(),(0,t.createElementBlock)("small",U,(0,t.toDisplayString)(Y.errors.suplier_city[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",w,[z,(0,t.createElementVNode)("div",D,[(0,t.createVNode)(re,{modelValue:Y.supp.suplier_prov,"onUpdate:modelValue":l[6]||(l[6]=function(e){return Y.supp.suplier_prov=e}),type:"text",class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_prov})},null,8,["modelValue","class"]),Y.errors.suplier_prov?((0,t.openBlock)(),(0,t.createElementBlock)("small",T,(0,t.toDisplayString)(Y.errors.suplier_prov[0]),1)):(0,t.createCommentVNode)("",!0)])])]),(0,t.createElementVNode)("div",$,[A,(0,t.createElementVNode)("div",I,[(0,t.createVNode)(re,{type:"Email",modelValue:Y.supp.suplier_email,"onUpdate:modelValue":l[7]||(l[7]=function(e){return Y.supp.suplier_email=e}),class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_email})},null,8,["modelValue","class"]),Y.errors.suplier_email?((0,t.openBlock)(),(0,t.createElementBlock)("small",M,(0,t.toDisplayString)(Y.errors.suplier_email[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",R,[K,(0,t.createElementVNode)("div",O,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_fax,"onUpdate:modelValue":l[8]||(l[8]=function(e){return Y.supp.suplier_fax=e}),class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_fax})},null,8,["modelValue","class"]),Y.errors.suplier_fax?((0,t.openBlock)(),(0,t.createElementBlock)("small",P,(0,t.toDisplayString)(Y.errors.suplier_fax[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",F,[L,(0,t.createElementVNode)("div",j,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_tlp1,"onUpdate:modelValue":l[9]||(l[9]=function(e){return Y.supp.suplier_tlp1=e}),placeholder:"Masukan No.Tlp 1. . .",class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_tlp1})},null,8,["modelValue","class"]),Y.errors.suplier_tlp1?((0,t.openBlock)(),(0,t.createElementBlock)("small",q,(0,t.toDisplayString)(Y.errors.suplier_tlp1[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",G,[H,(0,t.createElementVNode)("div",J,[(0,t.createVNode)(re,{type:"text",modelValue:Y.supp.suplier_tlp2,"onUpdate:modelValue":l[10]||(l[10]=function(e){return Y.supp.suplier_tlp2=e}),placeholder:"(Optional)",class:(0,t.normalizeClass)({"p-invalid":Y.errors.suplier_tlp2})},null,8,["modelValue","class"]),Y.errors.suplier_tlp2?((0,t.openBlock)(),(0,t.createElementBlock)("small",Q,(0,t.toDisplayString)(Y.errors.suplier_tlp2[0]),1)):(0,t.createCommentVNode)("",!0)])]),(0,t.createElementVNode)("div",W,[(0,t.createVNode)(se,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,t.createVNode)(se,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:l[11]||(l[11]=function(l){return e.$router.push("/referensi-supplier")})})])],32)])])])}},Y=X}}]);