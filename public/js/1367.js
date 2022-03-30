"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1367],{61367:(e,t,i)=>{i.r(t),i.d(t,{default:()=>C});var s=i(5166),r={class:"card"},n=(0,s.createElementVNode)("h4",null,"ICT Request",-1),o={class:"card-body"},a=(0,s.createElementVNode)("div",{class:"field grid"},[(0,s.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"120px"}},"No. Request")],-1),l={class:"field grid"},c=(0,s.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"120px"}},"Tgl. Request",-1),u={class:"col-10 md:col-3"},d={class:"flex items-center"},p=["value","onClick"],h={key:0,class:"p-error"},m={class:"field grid"},g=(0,s.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"120px"}},"Tipe Request",-1),f={class:"field col-12 md:col-4"},k={key:0,class:"p-error"},v={class:"field grid"},b=(0,s.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"120px"}},"Bisnis Unit",-1),V={class:"field col-12 md:col-4"},N={class:"form-group"};const y={data:function(){return{errors:[],error:[],tgl:new Date,tipereq:null,usr_name:null,usr_divisi:null,divisi:[],bisnis:null,type:[],bu:[],mask:{input:"DD MMM YYYY"},token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id"),code:null,user:[]}},mounted:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Request")||e.checkto.includes("/ict-request")?(e.getBisnis(),e.getDivisi(),e.getUser(),e.getType()):e.$router.push("/access")})):this.$router.push("/login")},getUser:function(){var e=this;this.axios.get("api/user",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.user=t.data,e.usr_name=e.user.usr_name,e.bisnis=e.user.usr_bu,e.usr_divisi=e.user.div_id}))},getDivisi:function(){var e=this;this.axios.get("api/get-divisi",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.divisi=t.data}))},getBisnis:function(){var e=this;this.axios.get("api/get-bisnis",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.bu=t.data}))},getType:function(){var e=this;this.axios.get("api/getType",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.type=t.data}))},CreateIct:function(){var e=this;if(this.errors=[],this.error=[],null!=this.tipereq&&null!=this.bisnis&&null!=this.usr_name&&null!=this.usr_divisi){var t=new FormData;t.append("tgl",this.tgl),t.append("tipereq",this.tipereq),t.append("bisnis",this.bisnis),t.append("user_name",this.usr_name),t.append("user_divisi",this.usr_divisi),this.axios.post("api/add-ict",t,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),e.code=t.data.ireq_id,setTimeout((function(){return e.$router.push({name:"Add Ict Request Detail",params:{code:e.code}})}),1e3)})).catch((function(t){e.errors=t.response.data.errors}))}else null==this.tipereq&&(this.error.tipereq="Tipe Request Wajib Diisi"),null==this.bisnis&&(this.error.bisnis="Bisnis Unit Wajib Diisi"),null==this.usr_name&&(this.error.usr_name="Pengguna Wajib Diisi"),null==this.usr_divisi&&(this.error.usr_divisi="Divisi Pengguna Wajib Diisi")}},render:function(e,t,i,y,C,q){var B=(0,s.resolveComponent)("Toast"),E=(0,s.resolveComponent)("Toolbar"),_=(0,s.resolveComponent)("Button"),w=(0,s.resolveComponent)("DatePicker"),x=(0,s.resolveComponent)("Dropdown");return(0,s.openBlock)(),(0,s.createElementBlock)("div",null,[(0,s.createVNode)(B),(0,s.createElementVNode)("div",r,[(0,s.createVNode)(E,{class:"mb-4"},{start:(0,s.withCtx)((function(){return[n]})),_:1}),(0,s.createElementVNode)("div",o,[(0,s.createElementVNode)("form",{onSubmit:t[5]||(t[5]=(0,s.withModifiers)((function(){return q.CreateIct&&q.CreateIct.apply(q,arguments)}),["prevent"]))},[a,(0,s.createElementVNode)("div",l,[c,(0,s.createElementVNode)("div",u,[(0,s.createVNode)(w,{modelValue:C.tgl,"onUpdate:modelValue":t[1]||(t[1]=function(e){return C.tgl=e}),masks:C.mask},{default:(0,s.withCtx)((function(e){var i=e.inputValue,r=e.togglePopover;return[(0,s.createElementVNode)("div",d,[(0,s.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:i,onClick:r,placeholder:"Pilih Tgl. Request",readonly:""},null,8,p),C.tgl?((0,s.openBlock)(),(0,s.createBlock)(_,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:t[0]||(t[0]=function(e){return C.tgl=""})})):((0,s.openBlock)(),(0,s.createBlock)(_,{key:0,icon:"pi pi-calendar",onClick:r},null,8,["onClick"]))])]})),_:1},8,["modelValue","masks"]),C.errors.tgl?((0,s.openBlock)(),(0,s.createElementBlock)("small",h,(0,s.toDisplayString)(C.errors.tgl[0]),1)):(0,s.createCommentVNode)("",!0)])]),(0,s.createElementVNode)("div",m,[g,(0,s.createElementVNode)("div",f,[(0,s.createVNode)(x,{modelValue:C.tipereq,"onUpdate:modelValue":t[2]||(t[2]=function(e){return C.tipereq=e}),options:C.type,optionLabel:"name",optionValue:"code",placeholder:"Pilih Tipe Request",showClear:!0,class:(0,s.normalizeClass)({"p-invalid":C.error.tipereq})},null,8,["modelValue","options","class"]),C.error.tipereq?((0,s.openBlock)(),(0,s.createElementBlock)("small",k,(0,s.toDisplayString)(C.error.tipereq),1)):(0,s.createCommentVNode)("",!0)])]),(0,s.createElementVNode)("div",v,[b,(0,s.createElementVNode)("div",V,[(0,s.createVNode)(x,{modelValue:C.bisnis,"onUpdate:modelValue":t[3]||(t[3]=function(e){return C.bisnis=e}),options:C.bu,optionLabel:"name",optionValue:"code",disabled:""},null,8,["modelValue","options"])])]),(0,s.createElementVNode)("div",N,[(0,s.createVNode)(_,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,s.createVNode)(_,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:t[4]||(t[4]=function(t){return e.$router.push("/ict-request")})})])],32)])])])}},C=y}}]);