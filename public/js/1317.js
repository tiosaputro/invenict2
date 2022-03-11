"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1317],{13407:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(23645),a=r.n(o)()((function(e){return e[1]}));a.push([e.id,".ict-image[data-v-29d4c680]{box-shadow:0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);width:485px}",""]);const s=a},71317:(e,t,r)=>{r.r(t),r.d(t,{default:()=>Q});var o=r(5166);(0,o.pushScopeId)("data-v-29d4c680");var a={class:"card"},s=(0,o.createElementVNode)("div",{class:"p-grid p-dir-col"},[(0,o.createElementVNode)("div",{class:"p-col"},[(0,o.createElementVNode)("h4",null,"ICT Request (Detail)")])],-1),l={class:"row"},i={class:"col-sm-6"},n={class:"p-fluid"},d={class:"p-field p-grid"},c=(0,o.createElementVNode)("label",{for:"tipe",class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"No. Request",-1),p={class:"p-col-2 p-md-6"},m={class:"p-fluid"},u={class:"p-field p-grid"},h=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"Tipe Request",-1),k={class:"p-col-2 p-md-6"},V={key:0,class:"p-error"},v={key:1,class:"p-error"},f={class:"p-fluid"},N={class:"p-field p-grid"},g=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"Nama Peripheral",-1),y={class:"p-col-2 p-md-6"},b={key:0,class:"p-error"},E={key:1,class:"p-error"},q={class:"p-fluid"},C={class:"p-field p-grid"},x=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"Deskripsi",-1),B={class:"p-col-2 p-md-6"},D={key:0,class:"p-error"},S={class:"p-fluid"},w={class:"p-field p-grid"},T=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"Qty",-1),I={class:"p-col-2 p-md-6"},z={key:0,class:"p-error"},$={class:"p-fluid"},A={class:"p-field p-grid"},R=(0,o.createElementVNode)("label",{class:"p-col-12 p-mb-2 p-md-2 p-mb-md-0",style:{width:"120px"}},"Keterangan",-1),U={class:"p-col-2 p-md-6"},_={key:0,class:"p-error"},P={class:"form-group"},M={class:"col-sm-6"},j=["src"];(0,o.popScopeId)();const K={data:function(){return{errors:[],error:[],detail:[],photo:[],kode:null,desk:"",qty:null,ket:"",tipereq:null,kodeperi:[],type:[],bu:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{saveclick:function(){var e=this;if(this.errors=[],this.error=[],null!=this.kode&&null!=this.tipereq){var t=new FormData;t.append("invent_code",this.kode),t.append("desk",this.desk),t.append("qty",this.qty),t.append("ket",this.ket),t.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,t,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create",life:500}),setTimeout((function(){return e.kode=null}),e.desk=null,e.qty=null,e.ket=null,1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.kode&&(this.error.kode="Nama Peripheral Wajib Diisi"),null==this.tipereq&&(this.error.tipereq="Tipe Request Wajib Diisi")},cekUser:function(){var e=this;this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Request")||e.checkto.includes("/ict-request")?e.getNoreq():(e.$toast.add({severity:"error",summary:"403",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3))}))},getImage:function(){var e=this;this.kode&&this.axios.get("/api/getImage/"+this.kode,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.photo=t.data}))},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.getKode(),e.getType()})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getType:function(){var e=this;this.axios.get("/api/getType",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.type=t.data}))},getKode:function(){var e=this;this.axios.get("/api/get-kode",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.kodeperi=t.data}))},CreateIctDetail:function(){var e=this;if(this.errors=[],this.error=[],null!=this.kode&&null!=this.tipereq){var t=new FormData;t.append("invent_code",this.kode),t.append("desk",this.desk),t.append("qty",this.qty),t.append("ket",this.ket),t.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,t,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),setTimeout((function(){return e.$router.push("/ict-request-detail/"+e.$route.params.code)}),1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.kode&&(this.error.kode="Nama Peripheral Wajib Diisi"),null==this.tipereq&&(this.error.tipereq="Tipe Request Wajib Diisi")}}};var W=r(93379),L=r.n(W),Z=r(13407),F={insert:"head",singleton:!1};L()(Z.Z,F);Z.Z.locals;K.render=function(e,t,r,K,W,L){var Z=this,F=(0,o.resolveComponent)("Toast"),Q=(0,o.resolveComponent)("Toolbar"),G=(0,o.resolveComponent)("InputText"),H=(0,o.resolveComponent)("Dropdown"),J=(0,o.resolveComponent)("InputNumber"),O=(0,o.resolveComponent)("Textarea"),X=(0,o.resolveComponent)("Button");return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createVNode)(F),(0,o.createElementVNode)("div",a,[(0,o.createVNode)(Q,{class:"p-mb-4"},{left:(0,o.withCtx)((function(){return[s]})),_:1}),(0,o.createElementVNode)("div",l,[(0,o.createElementVNode)("div",i,[(0,o.createElementVNode)("form",{onSubmit:t[8]||(t[8]=(0,o.withModifiers)((function(){return L.CreateIctDetail&&L.CreateIctDetail.apply(L,arguments)}),["prevent"]))},[(0,o.createElementVNode)("div",n,[(0,o.createElementVNode)("div",d,[c,(0,o.createElementVNode)("div",p,[(0,o.createVNode)(G,{type:"text",modelValue:W.detail.noreq,"onUpdate:modelValue":t[0]||(t[0]=function(e){return W.detail.noreq=e}),disabled:""},null,8,["modelValue"])])])]),(0,o.createElementVNode)("div",m,[(0,o.createElementVNode)("div",u,[h,(0,o.createElementVNode)("div",k,[(0,o.createVNode)(H,{modelValue:W.tipereq,"onUpdate:modelValue":t[1]||(t[1]=function(e){return W.tipereq=e}),options:W.type,optionLabel:"name",optionValue:"code",placeholder:"Pilih Tipe Request",showClear:!0,class:(0,o.normalizeClass)({"p-invalid":W.errors.tipereq})},null,8,["modelValue","options","class"]),W.errors.tipereq?((0,o.openBlock)(),(0,o.createElementBlock)("small",V,(0,o.toDisplayString)(W.errors.tipereq[0]),1)):(0,o.createCommentVNode)("",!0),W.error.tipereq?((0,o.openBlock)(),(0,o.createElementBlock)("small",v,(0,o.toDisplayString)(W.error.tipereq),1)):(0,o.createCommentVNode)("",!0)])])]),(0,o.createElementVNode)("div",f,[(0,o.createElementVNode)("div",N,[g,(0,o.createElementVNode)("div",y,[(0,o.createVNode)(H,{modelValue:W.kode,"onUpdate:modelValue":t[2]||(t[2]=function(e){return W.kode=e}),options:W.kodeperi,optionLabel:"name",optionValue:"code",placeholder:"Pilih Nama Peripheral ",onChange:t[3]||(t[3]=function(e){return L.getImage()}),showClear:!0,class:(0,o.normalizeClass)({"p-invalid":W.errors.invent_code})},null,8,["modelValue","options","class"]),W.errors.invent_code?((0,o.openBlock)(),(0,o.createElementBlock)("small",b,(0,o.toDisplayString)(W.errors.kodeper[0]),1)):(0,o.createCommentVNode)("",!0),W.error.kode?((0,o.openBlock)(),(0,o.createElementBlock)("small",E,(0,o.toDisplayString)(W.error.kode),1)):(0,o.createCommentVNode)("",!0)])])]),(0,o.createElementVNode)("div",q,[(0,o.createElementVNode)("div",C,[x,(0,o.createElementVNode)("div",B,[(0,o.createVNode)(G,{type:"text",modelValue:W.desk,"onUpdate:modelValue":t[4]||(t[4]=function(e){return W.desk=e}),placeholder:"Masukan Deskripsi",class:(0,o.normalizeClass)({"p-invalid":W.errors.desk})},null,8,["modelValue","class"]),W.errors.desk?((0,o.openBlock)(),(0,o.createElementBlock)("small",D,(0,o.toDisplayString)(W.errors.desk[0]),1)):(0,o.createCommentVNode)("",!0)])])]),(0,o.createElementVNode)("div",S,[(0,o.createElementVNode)("div",w,[T,(0,o.createElementVNode)("div",I,[(0,o.createVNode)(J,{modelValue:W.qty,"onUpdate:modelValue":t[5]||(t[5]=function(e){return W.qty=e}),placeholder:"Masukan Qty",class:(0,o.normalizeClass)({"p-invalid":W.errors.qty})},null,8,["modelValue","class"]),W.errors.qty?((0,o.openBlock)(),(0,o.createElementBlock)("small",z,(0,o.toDisplayString)(W.errors.qty[0]),1)):(0,o.createCommentVNode)("",!0)])])]),(0,o.createElementVNode)("div",$,[(0,o.createElementVNode)("div",A,[R,(0,o.createElementVNode)("div",U,[(0,o.createVNode)(O,{autoResize:!0,type:"text",modelValue:W.ket,"onUpdate:modelValue":t[6]||(t[6]=function(e){return W.ket=e}),rows:"5",cols:"30",placeholder:"Masukan Keterangan",class:(0,o.normalizeClass)({"p-invalid":W.errors.ket})},null,8,["modelValue","class"]),W.errors.ket?((0,o.openBlock)(),(0,o.createElementBlock)("small",_,(0,o.toDisplayString)(W.errors.ket[0]),1)):(0,o.createCommentVNode)("",!0)])])]),(0,o.createElementVNode)("div",P,[(0,o.createVNode)(X,{class:"p-button-rounded p-button-primary p-mr-2 p-mb-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,o.createVNode)(X,{class:"p-button-rounded p-button-success p-mr-2 p-mb-2",icon:"pi pi-check",label:"Simpan & Add",onClick:L.saveclick},null,8,["onClick"]),(0,o.createVNode)(X,{label:"Cancel",class:"p-button-rounded p-button-secondary p-mr-2 p-mb-2",icon:"pi pi-times",onClick:t[7]||(t[7]=function(t){return e.$router.push({name:"Ict Request Detail",params:{code:Z.$route.params.code}})})})])],32)]),(0,o.createElementVNode)("div",M,[this.kode?((0,o.openBlock)(),(0,o.createElementBlock)("img",{key:0,src:"/master_peripheral/"+W.photo.photo,class:"ict-image"},null,8,j)):(0,o.createCommentVNode)("",!0)])])])])},K.__scopeId="data-v-29d4c680";const Q=K}}]);