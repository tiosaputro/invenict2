"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6418],{6418:(e,t,r)=>{r.r(t),r.d(t,{default:()=>P});var i=r(5166),o={class:"card"},a=(0,i.createElementVNode)("h4",null,"ICT Request",-1),s={class:"card-body"},l={class:"field grid"},n=(0,i.createElementVNode)("label",{style:{width:"120px"}},"No. Request",-1),c={class:"col-3"},u={class:"field grid"},d=(0,i.createElementVNode)("label",{style:{width:"120px"}},"Tgl. Request",-1),m={class:"col-3"},p={class:"flex items-center"},h=["value","onClick"],V={key:0,class:"p-error"},k={class:"field grid"},v=(0,i.createElementVNode)("label",{style:{width:"120px"}},"Tipe Request",-1),g={class:"col-3"},q={key:0,class:"p-error"},_={key:1,class:"p-error"},f={class:"field grid"},y=(0,i.createElementVNode)("label",{style:{width:"120px"}},"Pengguna",-1),b={class:"col-3"},N={key:0,class:"p-error"},E={class:"field grid"},B=(0,i.createElementVNode)("label",{style:{width:"120px"}},"Divisi Pengguna",-1),C={class:"col-3"},x={key:0,class:"p-error"},D={class:"field grid"},w=(0,i.createElementVNode)("label",{style:{width:"120px"}},"Bisnis Unit",-1),S={class:"col-3"},U={key:0,class:"p-error"},T={key:1,class:"p-error"},z={class:"form-group"};const I={data:function(){return{errors:[],error:[],mutasi:[],type:[],divisi:[],bu:[],mask:{input:"DD MMM YYYY"},token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Request")||e.checkto.includes("/ict-request")?(e.getIct(),e.getDivisi()):e.$router.push("/access")})):this.$router.push("/login")},getIct:function(){var e=this;this.axios.get("/api/edit-ict/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.mutasi=t.data,e.getBisnis(),e.getReq()})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getDivisi:function(){var e=this;this.axios.get("/api/get-divisi",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.divisi=t.data}))},getReq:function(){var e=this;this.axios.get("/api/getType",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.type=t.data}))},getBisnis:function(){var e=this;this.axios.get("/api/get-bisnis",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.bu=t.data}))},UpdateIct:function(){var e=this;this.errors=[],this.error=[],null!=this.mutasi.ireq_bu&&null!=this.mutasi.ireq_type&&null!=this.mutasi.ireq_divisi_user?this.axios.put("/api/update-ict/"+this.$route.params.code,this.mutasi,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Update"}),setTimeout((function(){return e.$router.push("/ict-request")}),1e3)})).catch((function(t){e.errors=t.response.data.errors})):(null==this.mutasi.ireq_type&&(this.error.ireq_type="Tipe Request Wajib Diisi"),null==this.mutasi.ireq_bu&&(this.error.ireq_bu="Bisnis Unit Wajib Diisi"),null==this.mutasi.ireq_divisi_user&&(this.error.ireq_divisi_user="Divisi User Wajib Diisi"))}},render:function(e,t,r,I,P,R){var $=(0,i.resolveComponent)("Toast"),A=(0,i.resolveComponent)("Toolbar"),M=(0,i.resolveComponent)("InputText"),L=(0,i.resolveComponent)("Button"),Y=(0,i.resolveComponent)("DatePicker"),j=(0,i.resolveComponent)("Dropdown");return(0,i.openBlock)(),(0,i.createElementBlock)("div",null,[(0,i.createVNode)($),(0,i.createElementVNode)("div",o,[(0,i.createVNode)(A,{class:"mb-4"},{start:(0,i.withCtx)((function(){return[a]})),_:1}),(0,i.createElementVNode)("div",s,[(0,i.createElementVNode)("form",{onSubmit:t[8]||(t[8]=(0,i.withModifiers)((function(){return R.UpdateIct&&R.UpdateIct.apply(R,arguments)}),["prevent"]))},[(0,i.createElementVNode)("div",l,[n,(0,i.createElementVNode)("div",c,[(0,i.createVNode)(M,{type:"text",modelValue:P.mutasi.ireq_no,"onUpdate:modelValue":t[0]||(t[0]=function(e){return P.mutasi.ireq_no=e}),disabled:""},null,8,["modelValue"])])]),(0,i.createElementVNode)("div",u,[d,(0,i.createElementVNode)("div",m,[(0,i.createVNode)(Y,{modelValue:P.mutasi.ireq_date,"onUpdate:modelValue":t[2]||(t[2]=function(e){return P.mutasi.ireq_date=e}),masks:P.mask},{default:(0,i.withCtx)((function(e){var r=e.inputValue,o=e.togglePopover;return[(0,i.createElementVNode)("div",p,[(0,i.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:r,onClick:o,placeholder:"Pilih Tgl. Request",readonly:""},null,8,h),P.mutasi.ireq_date?((0,i.openBlock)(),(0,i.createBlock)(L,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:t[1]||(t[1]=function(e){return P.mutasi.ireq_date=""})})):((0,i.openBlock)(),(0,i.createBlock)(L,{key:0,icon:"pi pi-calendar",onClick:o},null,8,["onClick"]))])]})),_:1},8,["modelValue","masks"]),P.errors.ireq_date?((0,i.openBlock)(),(0,i.createElementBlock)("small",V,(0,i.toDisplayString)(P.errors.ireq_date[0]),1)):(0,i.createCommentVNode)("",!0)])]),(0,i.createElementVNode)("div",k,[v,(0,i.createElementVNode)("div",g,[(0,i.createVNode)(j,{modelValue:P.mutasi.ireq_type,"onUpdate:modelValue":t[3]||(t[3]=function(e){return P.mutasi.ireq_type=e}),options:P.type,optionLabel:"name",optionValue:"code",placeholder:"Pilih Tipe Request",showClear:!0,class:(0,i.normalizeClass)({"p-invalid":P.errors.ireq_type})},null,8,["modelValue","options","class"]),P.errors.ireq_type?((0,i.openBlock)(),(0,i.createElementBlock)("small",q,(0,i.toDisplayString)(P.errors.ireq_type[0]),1)):(0,i.createCommentVNode)("",!0),P.error.ireq_type?((0,i.openBlock)(),(0,i.createElementBlock)("small",_,(0,i.toDisplayString)(P.error.ireq_type),1)):(0,i.createCommentVNode)("",!0)])]),(0,i.createElementVNode)("div",f,[y,(0,i.createElementVNode)("div",b,[(0,i.createVNode)(M,{type:"text",modelValue:P.mutasi.ireq_user,"onUpdate:modelValue":t[4]||(t[4]=function(e){return P.mutasi.ireq_user=e}),placeholder:"Masukan Pengguna",class:(0,i.normalizeClass)({"p-invalid":P.errors.ireq_user})},null,8,["modelValue","class"]),P.errors.ireq_user?((0,i.openBlock)(),(0,i.createElementBlock)("small",N,(0,i.toDisplayString)(P.errors.ireq_user[0]),1)):(0,i.createCommentVNode)("",!0)])]),(0,i.createElementVNode)("div",E,[B,(0,i.createElementVNode)("div",C,[(0,i.createVNode)(j,{modelValue:P.mutasi.ireq_divisi_user,"onUpdate:modelValue":t[5]||(t[5]=function(e){return P.mutasi.ireq_divisi_user=e}),options:P.divisi,optionLabel:"name",optionValue:"code",placeholder:"Pilih Divisi Pengguna",showClear:!0,filter:!0,class:(0,i.normalizeClass)({"p-invalid":P.error.ireq_divisi_user})},null,8,["modelValue","options","class"]),P.error.ireq_divisi_user?((0,i.openBlock)(),(0,i.createElementBlock)("small",x,(0,i.toDisplayString)(P.error.ireq_divisi_user),1)):(0,i.createCommentVNode)("",!0)])]),(0,i.createElementVNode)("div",D,[w,(0,i.createElementVNode)("div",S,[(0,i.createVNode)(j,{modelValue:P.mutasi.ireq_bu,"onUpdate:modelValue":t[6]||(t[6]=function(e){return P.mutasi.ireq_bu=e}),options:P.bu,optionLabel:"name",optionValue:"code",placeholder:"Pilih Bisnis Unit",showClear:!0,filter:!0,class:(0,i.normalizeClass)({"p-invalid":P.errors.ireq_bu})},null,8,["modelValue","options","class"]),P.errors.ireq_bu?((0,i.openBlock)(),(0,i.createElementBlock)("small",U,(0,i.toDisplayString)(P.errors.ireq_bu[0]),1)):(0,i.createCommentVNode)("",!0),P.error.ireq_bu?((0,i.openBlock)(),(0,i.createElementBlock)("small",T,(0,i.toDisplayString)(P.error.ireq_bu),1)):(0,i.createCommentVNode)("",!0)])]),(0,i.createElementVNode)("div",z,[(0,i.createVNode)(L,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,i.createVNode)(L,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:t[7]||(t[7]=function(t){return e.$router.push("/ict-request")})})])],32)])])])}},P=I}}]);