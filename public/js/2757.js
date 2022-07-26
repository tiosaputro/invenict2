"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2757],{82757:(e,t,o)=>{o.r(t),o.d(t,{default:()=>G});var a=o(70821),l={class:"card"},n=(0,a.createElementVNode)("h4",null,"Management Menu",-1),r={class:"card-body"},s={class:"field grid"},d=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Menu Name",-1),c={class:"col-3 md-6"},i={key:0,class:"p-error"},m={class:"field grid"},u=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Menu Deskripsi",-1),p={class:"col-3 md-6"},V={key:0,class:"p-error"},h={class:"field grid"},N=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Menu Display",-1),k={class:"col-3 md-6"},_={key:0,class:"p-error"},y={class:"field grid"},f=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Controller",-1),v={class:"col-3 md-6"},E={key:0,class:"p-error"},g={class:"field grid"},C=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Action",-1),b={class:"col-3 md-6"},M={class:"field grid"},B=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Menu Status",-1),S={class:"col-3 md-6"},w={key:0,class:"p-error"},x={class:"field grid"},D=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Menu Type",-1),z={class:"col-3 md-6"},A={key:0,class:"p-error"},U={class:"field grid"},T=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Module Name",-1),L={class:"col-3 md-6"},$={key:0,class:"p-error"},I={class:"field grid"},P=(0,a.createElementVNode)("label",{style:{width:"120px"}},"Parent ID",-1),F={class:"col-3 md-6"},Z={key:0,class:"p-error"},j={class:"form-group"};const q={data:function(){return{errors:[],mod_id:"",menu_name:"",menu_desc:"",menu_display:"",menu_type:"",menu_stat:"",controller:"",action:"",parent_id:"",modul:[],parent:[],checkname:[],checkto:[],id:localStorage.getItem("id"),stat:[{nama:"Aktif",code:"T"},{nama:"Tidak Aktif",code:"F"}],type:[{nama:"Node",code:"N"},{nama:"Leaf",code:"L"}],token:localStorage.getItem("token")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Menu")||e.checkto.includes("/mng-menu")?(e.getModul(),e.getParent()):e.$router.push("/access")})):this.$router.push("/login")},getModul:function(){var e=this;this.axios.get("api/get-module",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.modul=t.data})).catch((function(t){401==t.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},getParent:function(){var e=this;this.axios.get("api/get-parent",{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.parent=t.data}))},CreateMenu:function(){var e=this;this.errors=[];var t=new FormData;t.append("mod_id",this.mod_id),t.append("menu_name",this.menu_name),t.append("menu_desc",this.menu_desc),t.append("menu_display",this.menu_display),t.append("menu_type",this.menu_type),t.append("menu_stat",this.menu_stat),t.append("controller",this.controller),t.append("action",this.action),t.append("parent_id",this.parent_id),this.axios.post("api/save-menu",t,{headers:{Authorization:"Bearer "+this.token}}).then((function(){e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Create"}),setTimeout((function(){return e.$router.push("/mng-menu")}),1e3)})).catch((function(t){e.errors=t.response.data.errors}))}}};const G=(0,o(83744).Z)(q,[["render",function(e,t,o,q,G,H){var J=(0,a.resolveComponent)("Toast"),K=(0,a.resolveComponent)("Toolbar"),O=(0,a.resolveComponent)("InputText"),Q=(0,a.resolveComponent)("Dropdown"),R=(0,a.resolveComponent)("Button");return(0,a.openBlock)(),(0,a.createElementBlock)("div",null,[(0,a.createVNode)(J),(0,a.createElementVNode)("div",l,[(0,a.createVNode)(K,{class:"mb-4"},{start:(0,a.withCtx)((function(){return[n]})),_:1}),(0,a.createElementVNode)("div",r,[(0,a.createElementVNode)("form",{onSubmit:t[10]||(t[10]=(0,a.withModifiers)((function(){return H.CreateMenu&&H.CreateMenu.apply(H,arguments)}),["prevent"]))},[(0,a.createElementVNode)("div",s,[d,(0,a.createElementVNode)("div",c,[(0,a.createVNode)(O,{modelValue:G.menu_name,"onUpdate:modelValue":t[0]||(t[0]=function(e){return G.menu_name=e}),placeholder:"Masukan Menu Name",class:(0,a.normalizeClass)({"p-invalid":G.errors.menu_name}),autofocus:""},null,8,["modelValue","class"]),G.errors.menu_name?((0,a.openBlock)(),(0,a.createElementBlock)("small",i,(0,a.toDisplayString)(G.errors.menu_name[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",m,[u,(0,a.createElementVNode)("div",p,[(0,a.createVNode)(O,{modelValue:G.menu_desc,"onUpdate:modelValue":t[1]||(t[1]=function(e){return G.menu_desc=e}),placeholder:"Masukan Menu Deskripsi",class:(0,a.normalizeClass)({"p-invalid":G.errors.menu_desc})},null,8,["modelValue","class"]),G.errors.menu_desc?((0,a.openBlock)(),(0,a.createElementBlock)("small",V,(0,a.toDisplayString)(G.errors.menu_desc[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",h,[N,(0,a.createElementVNode)("div",k,[(0,a.createVNode)(O,{modelValue:G.menu_display,"onUpdate:modelValue":t[2]||(t[2]=function(e){return G.menu_display=e}),placeholder:"Masukan Menu Display",class:(0,a.normalizeClass)({"p-invalid":G.errors.menu_display})},null,8,["modelValue","class"]),G.errors.menu_display?((0,a.openBlock)(),(0,a.createElementBlock)("small",_,(0,a.toDisplayString)(G.errors.menu_display[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",y,[f,(0,a.createElementVNode)("div",v,[(0,a.createVNode)(O,{modelValue:G.controller,"onUpdate:modelValue":t[3]||(t[3]=function(e){return G.controller=e}),placeholder:"Masukan Controller",class:(0,a.normalizeClass)({"p-invalid":G.errors.controller})},null,8,["modelValue","class"]),G.errors.controller?((0,a.openBlock)(),(0,a.createElementBlock)("small",E,(0,a.toDisplayString)(G.errors.controller[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",g,[C,(0,a.createElementVNode)("div",b,[(0,a.createVNode)(O,{modelValue:G.action,"onUpdate:modelValue":t[4]||(t[4]=function(e){return G.action=e}),placeholder:"Masukan Action"},null,8,["modelValue"])])]),(0,a.createElementVNode)("div",M,[B,(0,a.createElementVNode)("div",S,[(0,a.createVNode)(Q,{modelValue:G.menu_stat,"onUpdate:modelValue":t[5]||(t[5]=function(e){return G.menu_stat=e}),options:G.stat,showClear:!0,optionLabel:"nama",optionValue:"code",placeholder:"Select A Status",class:(0,a.normalizeClass)({"p-invalid":G.errors.menu_stat})},null,8,["modelValue","options","class"]),G.errors.menu_stat?((0,a.openBlock)(),(0,a.createElementBlock)("small",w,(0,a.toDisplayString)(G.errors.menu_stat[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",x,[D,(0,a.createElementVNode)("div",z,[(0,a.createVNode)(Q,{modelValue:G.menu_type,"onUpdate:modelValue":t[6]||(t[6]=function(e){return G.menu_type=e}),options:G.type,showClear:!0,optionLabel:"nama",optionValue:"code",placeholder:"Select A Menu Type",class:(0,a.normalizeClass)({"p-invalid":G.errors.menu_type})},null,8,["modelValue","options","class"]),G.errors.menu_type?((0,a.openBlock)(),(0,a.createElementBlock)("small",A,(0,a.toDisplayString)(G.errors.menu_type[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",U,[T,(0,a.createElementVNode)("div",L,[(0,a.createVNode)(Q,{modelValue:G.mod_id,"onUpdate:modelValue":t[7]||(t[7]=function(e){return G.mod_id=e}),options:G.modul,showClear:!0,optionLabel:"name",optionValue:"code",placeholder:"Select A Module",class:(0,a.normalizeClass)({"p-invalid":G.errors.mod_id})},null,8,["modelValue","options","class"]),G.errors.mod_id?((0,a.openBlock)(),(0,a.createElementBlock)("small",$,(0,a.toDisplayString)(G.errors.mod_id[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",I,[P,(0,a.createElementVNode)("div",F,[(0,a.createVNode)(Q,{modelValue:G.parent_id,"onUpdate:modelValue":t[8]||(t[8]=function(e){return G.parent_id=e}),options:G.parent,showClear:!0,optionLabel:"name",optionValue:"code",placeholder:"Select A Parent",class:(0,a.normalizeClass)({"p-invalid":G.errors.parent_id})},null,8,["modelValue","options","class"]),G.errors.parent_id?((0,a.openBlock)(),(0,a.createElementBlock)("small",Z,(0,a.toDisplayString)(G.errors.parent_id[0]),1)):(0,a.createCommentVNode)("",!0)])]),(0,a.createElementVNode)("div",j,[(0,a.createVNode)(R,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,a.createVNode)(R,{label:"Cancel",class:"p-button-rounded p-button-secondary mr-2",icon:"pi pi-times",onClick:t[9]||(t[9]=function(t){return e.$router.push("/mng-menu")})})])],32)])])])}]])}}]);