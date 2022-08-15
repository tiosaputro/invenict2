"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7513],{3107:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(23645),i=r.n(o)()((function(e){return e[1]}));i.push([e.id,".ict-image[data-v-ba67bd02]{box-shadow:0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);width:450px}",""]);const s=i},37513:(e,t,r)=>{r.r(t),r.d(t,{default:()=>z});var o=r(70821),i=function(e){return(0,o.pushScopeId)("data-v-ba67bd02"),e=e(),(0,o.popScopeId)(),e},s={class:"card"},a=i((function(){return(0,o.createElementVNode)("h4",null,"ICT Request (Detail)",-1)})),n={class:"card-body"},l={class:"field grid"},c=i((function(){return(0,o.createElementVNode)("label",{class:"col-fixed w-9rem"},"No. Request",-1)})),d={class:"col-fixed w-9rem"},p={class:"field grid"},u=i((function(){return(0,o.createElementVNode)("label",{class:"col-fixed w-9rem"},"Request Type",-1)})),h={class:"col-fixed w-9rem"},m={key:0,class:"p-error"},k={key:1,class:"p-error"},q={key:0,class:"field grid"},f=i((function(){return(0,o.createElementVNode)("label",{class:"col-fixed w-9rem"}," Peripheral",-1)})),v={class:"col-fixed w-9rem"},y={key:0,class:"p-error"},V={key:1,class:"p-error"},g={key:1,class:"field grid"},C=i((function(){return(0,o.createElementVNode)("label",{class:"col-fixed w-9rem"},"Qty",-1)})),N={class:"col-fixed w-9rem"},b={key:0,class:"p-error"},E={key:2,class:"field grid"},x=i((function(){return(0,o.createElementVNode)("label",{class:"col-fixed w-9rem"},"Remark",-1)})),T={class:"col-fixed w-9rem"},w={key:0,class:"p-error"},B={class:"form-group"};const S={data:function(){return{errors:[],error:[],detail:[],kode:"",desk:"",qty:null,ket:"",tipereq:"",kodeperi:[],type:[],bu:[],token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),cekTipeReq:""}},mounted:function(){this.cekUser()},methods:{getIreq:function(e){this.cekTipeReq=e,"S"==this.cekTipeReq&&(this.qty=null,this.kode=""),this.errors=[],this.error=[]},saveclick:function(){var e=this;if(this.errors=[],this.error=[],"P"==this.tipereq)if(null!=this.kode&&null!=this.tipereq&&"null"!=this.tipereq){var t=new FormData;t.append("invent_code",this.kode),t.append("desk",this.desk),t.append("qty",this.qty),t.append("ket",this.ket),t.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,t,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create",life:500}),setTimeout((function(){return e.kode=null}),e.desk="",e.qty=null,e.ket="",1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.kode&&(this.error.kode="Peripheral not filled"),null==this.tipereq&&(this.error.tipereq="Request Type not filled"),"null"==this.tipereq&&(this.error.tipereq="Request Type not filled");else if(null!=this.tipereq&&"null"!=this.tipereq){var r=new FormData;r.append("desk",this.desk),r.append("ket",this.ket),r.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,r,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create",life:500}),setTimeout((function(){return e.desk=""}),e.ket="",1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.tipereq&&(this.error.tipereq="Request Type not filled"),"null"==this.tipereq&&(this.error.tipereq="Request Type not filled")},cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Request")||e.checkto.includes("/ict-request")?e.getNoreq():e.$router.push("/access")})):this.$router.push("/login")},getNoreq:function(){var e=this;this.axios.get("/api/get-noreq/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.detail=t.data,e.tipereq=e.detail.ireq_type,e.cekTipeReq=e.detail.ireq_type,e.getType()})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getType:function(){var e=this;this.axios.get("/api/getAddDetail",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.type=t.data.ref,e.kodeperi=t.data.kode}))},CreateIctDetail:function(){var e=this;if(this.errors=[],this.error=[],"P"==this.tipereq)if(null!=this.kode&&null!=this.tipereq&&"null"!=this.tipereq){var t=new FormData;t.append("invent_code",this.kode),t.append("desk",this.desk),t.append("qty",this.qty),t.append("ket",this.ket),t.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,t,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),setTimeout((function(){return e.$router.push("/ict-request-detail/"+e.$route.params.code)}),1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.kode&&(this.error.kode="Peripheral not filled"),null==this.tipereq&&(this.error.tipereq="Request Type not filled"),"null"==this.tipereq&&(this.error.tipereq="Request Type not filled");else if(null!=this.tipereq&&"null"!=this.tipereq){var r=new FormData;r.append("desk",this.desk),r.append("ket",this.ket),r.append("tipereq",this.tipereq),this.axios.post("/api/add-ict-detail/"+this.$route.params.code,r,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),setTimeout((function(){return e.$router.push("/ict-request-detail/"+e.$route.params.code)}),1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.tipereq&&(this.error.tipereq="Request Type not filled"),"null"==this.tipereq&&(this.error.tipereq="Request Type not filled")}}};var R=r(93379),D=r.n(R),$=r(3107),I={insert:"head",singleton:!1};D()($.Z,I);$.Z.locals;const z=(0,r(83744).Z)(S,[["render",function(e,t,r,i,S,R){var D=this,$=(0,o.resolveComponent)("Toast"),I=(0,o.resolveComponent)("Toolbar"),z=(0,o.resolveComponent)("InputText"),A=(0,o.resolveComponent)("Dropdown"),_=(0,o.resolveComponent)("InputNumber"),P=(0,o.resolveComponent)("Textarea"),U=(0,o.resolveComponent)("Button"),M=(0,o.resolveDirective)("tooltip");return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createVNode)($),(0,o.createElementVNode)("div",s,[(0,o.createVNode)(I,{class:"mb-4"},{start:(0,o.withCtx)((function(){return[a]})),_:1}),(0,o.createElementVNode)("div",n,[(0,o.createElementVNode)("form",{onSubmit:t[7]||(t[7]=(0,o.withModifiers)((function(){return R.CreateIctDetail&&R.CreateIctDetail.apply(R,arguments)}),["prevent"]))},[(0,o.createElementVNode)("div",l,[c,(0,o.createElementVNode)("div",d,[(0,o.createVNode)(z,{type:"text",modelValue:S.detail.noreq,"onUpdate:modelValue":t[0]||(t[0]=function(e){return S.detail.noreq=e}),disabled:""},null,8,["modelValue"])])]),(0,o.createElementVNode)("div",p,[u,(0,o.createElementVNode)("div",h,[(0,o.createVNode)(A,{modelValue:S.tipereq,"onUpdate:modelValue":t[1]||(t[1]=function(e){return S.tipereq=e}),options:S.type,optionLabel:"name",optionValue:"code",placeholder:"Select One",onChange:t[2]||(t[2]=function(e){return R.getIreq(S.tipereq)}),showClear:!0,class:(0,o.normalizeClass)({"p-invalid":S.error.tipereq})},null,8,["modelValue","options","class"]),S.errors.tipereq?((0,o.openBlock)(),(0,o.createElementBlock)("small",m,(0,o.toDisplayString)(S.errors.tipereq[0]),1)):(0,o.createCommentVNode)("",!0),S.error.tipereq?((0,o.openBlock)(),(0,o.createElementBlock)("small",k,(0,o.toDisplayString)(S.error.tipereq),1)):(0,o.createCommentVNode)("",!0)])]),"P"==this.cekTipeReq?((0,o.openBlock)(),(0,o.createElementBlock)("div",q,[f,(0,o.createElementVNode)("div",v,[(0,o.createVNode)(A,{modelValue:S.kode,"onUpdate:modelValue":t[3]||(t[3]=function(e){return S.kode=e}),options:S.kodeperi,optionLabel:"name",optionValue:"code",placeholder:"Select One ",showClear:!0,filter:!0,class:(0,o.normalizeClass)({"p-invalid":S.error.kode})},null,8,["modelValue","options","class"]),S.errors.invent_code?((0,o.openBlock)(),(0,o.createElementBlock)("small",y,(0,o.toDisplayString)(S.errors.invent_code[0]),1)):(0,o.createCommentVNode)("",!0),S.error.kode?((0,o.openBlock)(),(0,o.createElementBlock)("small",V,(0,o.toDisplayString)(S.error.kode),1)):(0,o.createCommentVNode)("",!0)])])):(0,o.createCommentVNode)("",!0),"P"==this.cekTipeReq?((0,o.openBlock)(),(0,o.createElementBlock)("div",g,[C,(0,o.createElementVNode)("div",N,[(0,o.createVNode)(_,{modelValue:S.qty,"onUpdate:modelValue":t[4]||(t[4]=function(e){return S.qty=e}),placeholder:"Enter Qty",class:(0,o.normalizeClass)({"p-invalid":S.errors.qty})},null,8,["modelValue","class"]),S.errors.qty?((0,o.openBlock)(),(0,o.createElementBlock)("small",b,(0,o.toDisplayString)(S.errors.qty[0]),1)):(0,o.createCommentVNode)("",!0)])])):(0,o.createCommentVNode)("",!0),"P"==this.cekTipeReq||"S"==this.cekTipeReq?((0,o.openBlock)(),(0,o.createElementBlock)("div",E,[x,(0,o.createElementVNode)("div",T,[(0,o.createVNode)(P,{autoResize:!0,type:"text",modelValue:S.ket,"onUpdate:modelValue":t[5]||(t[5]=function(e){return S.ket=e}),rows:"6",cols:"40",placeholder:"Enter Remark",class:(0,o.normalizeClass)([{"p-invalid":S.errors.ket},"inputfield"])},null,8,["modelValue","class"]),S.errors.ket?((0,o.openBlock)(),(0,o.createElementBlock)("small",w,(0,o.toDisplayString)(S.errors.ket[0]),1)):(0,o.createCommentVNode)("",!0)])])):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("div",B,[(0,o.withDirectives)((0,o.createVNode)(U,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Save",type:"submit"},null,512),[[M,"Click to save detail",void 0,{bottom:!0}]]),(0,o.withDirectives)((0,o.createVNode)(U,{class:"p-button-rounded p-button-success mr-2 mt-2",icon:"pi pi-plus",label:"Add Request",onClick:R.saveclick},null,8,["onClick"]),[[M,"Click to save & add new detail",void 0,{bottom:!0}]]),(0,o.withDirectives)((0,o.createVNode)(U,{label:"Cancel",class:"p-button-rounded p-button-secondary mt-2",icon:"pi pi-times",onClick:t[6]||(t[6]=function(t){return e.$router.push({name:"Ict Request Detail",params:{code:D.$route.params.code}})})},null,512),[[M,"Click to cancel create data",void 0,{bottom:!0}]])])],32)])])])}],["__scopeId","data-v-ba67bd02"]])}}]);