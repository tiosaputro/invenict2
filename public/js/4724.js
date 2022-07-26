"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4724],{4724:(e,o,t)=>{t.r(o),t.d(o,{default:()=>w});var r=t(70821),l={class:"card"},a=(0,r.createElementVNode)("h4",null,"Divisi Refs",-1),s={class:"row"},n={class:"col-sm-6"},c={class:"card-body"},i={class:"field grid"},d=(0,r.createElementVNode)("label",{style:{width:"120px"}},"Role Name",-1),m={class:"col-3 md-6"},u={key:0,class:"p-error"},p={class:"field grid"},h=(0,r.createElementVNode)("label",{style:{width:"120px"}},"Role Deskripsi",-1),V={class:"col-3 md-6"},k={key:0,class:"p-error"},v={class:"field grid"},f=(0,r.createElementVNode)("label",{style:{width:"120px"}},"Role Status",-1),N={class:"col-3 md-6"},E={key:0,class:"p-error"},g={class:"card",style:{width:"33rem"}},C={class:"field grid"},y=(0,r.createElementVNode)("label",{style:{width:"120px"}},"Menu",-1),b={class:"col-3 md-6"},_={key:0,class:"p-error"},B={class:"form-group"};const S={data:function(){return{errors:[],role:{rol_name:"",rol_desc:"",rol_stat:"",menu:""},menus:[],stat:[{nama:"Aktif",code:"T"},{nama:"Tidak Aktif",code:"F"}],token:localStorage.getItem("token"),id:localStorage.getItem("id"),checkname:[],checkto:[]}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(o){e.checkto=o.data.map((function(e){return e.to})),e.checkname=o.data.map((function(e){return e.name})),e.checkname.includes("Divisi")||e.checkto.includes("/divisi-refs")?e.getMenu():e.$router.push("/access")})):this.$router.push("/login")},getMenu:function(){var e=this;this.axios.get("api/get-menu",{headers:{Authorization:"Bearer "+this.token}}).then((function(o){e.menus=o.data}))},CreateRole:function(){var e=this;this.errors=[],this.axios.post("api/save-role",this.role,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.axios.post("api/save-role-menu",e.role,{headers:{Authorization:"Bearer "+e.token}}),e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),setTimeout((function(){return e.$router.push("/mng-role")}),1e3)})).catch((function(o){e.errors=o.response.data.errors}))}}};const w=(0,t(83744).Z)(S,[["render",function(e,o,t,S,w,D){var x=(0,r.resolveComponent)("Toast"),M=(0,r.resolveComponent)("Toolbar"),R=(0,r.resolveComponent)("InputText"),z=(0,r.resolveComponent)("Dropdown"),A=(0,r.resolveComponent)("MultiSelect"),T=(0,r.resolveComponent)("Button");return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[(0,r.createVNode)(x),(0,r.createElementVNode)("div",l,[(0,r.createVNode)(M,{class:"mb-4"},{start:(0,r.withCtx)((function(){return[a]})),_:1}),(0,r.createElementVNode)("div",s,[(0,r.createElementVNode)("div",n,[(0,r.createElementVNode)("form",{onSubmit:o[5]||(o[5]=(0,r.withModifiers)((function(){return e.DivisiRefs&&e.DivisiRefs.apply(e,arguments)}),["prevent"]))},[(0,r.createElementVNode)("div",c,[(0,r.createElementVNode)("div",i,[d,(0,r.createElementVNode)("div",m,[(0,r.createVNode)(R,{modelValue:w.role.rol_name,"onUpdate:modelValue":o[0]||(o[0]=function(e){return w.role.rol_name=e}),placeholder:"Masukan Role Name",class:(0,r.normalizeClass)({"p-invalid":w.errors.rol_name})},null,8,["modelValue","class"]),w.errors.rol_name?((0,r.openBlock)(),(0,r.createElementBlock)("small",u,(0,r.toDisplayString)(w.errors.rol_name[0]),1)):(0,r.createCommentVNode)("",!0)])]),(0,r.createElementVNode)("div",p,[h,(0,r.createElementVNode)("div",V,[(0,r.createVNode)(R,{modelValue:w.role.rol_desc,"onUpdate:modelValue":o[1]||(o[1]=function(e){return w.role.rol_desc=e}),placeholder:"Masukan Role Deskripsi",class:(0,r.normalizeClass)({"p-invalid":w.errors.rol_desc})},null,8,["modelValue","class"]),w.errors.rol_desc?((0,r.openBlock)(),(0,r.createElementBlock)("small",k,(0,r.toDisplayString)(w.errors.rol_desc[0]),1)):(0,r.createCommentVNode)("",!0)])]),(0,r.createElementVNode)("div",v,[f,(0,r.createElementVNode)("div",N,[(0,r.createVNode)(z,{modelValue:w.role.rol_stat,"onUpdate:modelValue":o[2]||(o[2]=function(e){return w.role.rol_stat=e}),options:w.stat,showClear:!0,optionLabel:"nama",optionValue:"code",placeholder:"Select A Status",class:(0,r.normalizeClass)({"p-invalid":w.errors.rol_stat})},null,8,["modelValue","options","class"]),w.errors.rol_stat?((0,r.openBlock)(),(0,r.createElementBlock)("small",E,(0,r.toDisplayString)(w.errors.rol_stat[0]),1)):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",g,[(0,r.createElementVNode)("div",C,[y,(0,r.createElementVNode)("div",b,[(0,r.createVNode)(A,{modelValue:w.role.menu,"onUpdate:modelValue":o[3]||(o[3]=function(e){return w.role.menu=e}),options:w.menus,optionValue:"code",optionLabel:"name",display:"chip",placeholder:"Select Menu",class:(0,r.normalizeClass)({"p-invalid":w.errors.menu})},null,8,["modelValue","options","class"]),w.errors.menu?((0,r.openBlock)(),(0,r.createElementBlock)("small",_,(0,r.toDisplayString)(w.errors.menu[0]),1)):(0,r.createCommentVNode)("",!0)])])]),(0,r.createElementVNode)("div",B,[(0,r.createVNode)(T,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,r.createVNode)(T,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:o[4]||(o[4]=function(o){return e.$router.push("/mng-role")})})])],32)])])])])}]])}}]);