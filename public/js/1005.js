"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1005],{31005:(e,a,t)=>{t.r(a),t.d(a,{default:()=>_e});var l=t(70821),c={class:"card"},o=(0,l.createElementVNode)("h4",null,"Cash Advance",-1),r={class:"card"},n={class:"p-fluid formgrid grid"},d={class:"field grid col"},i=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"No. Request",-1),s={class:"col"},u={class:"field grid col"},m=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"No. Detail",-1),p={class:"col"},h={class:"p-fluid formgrid grid"},k={class:"field grid col"},V=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"Requestor",-1),_={class:"col"},f={class:"field grid col"},v=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"Pengguna",-1),g={class:"col"},N={class:"p-fluid formgrid grid"},y={class:"field grid col"},E=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"Tgl. Request",-1),C={class:"col"},b={class:"field grid col"},x=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"160px"}},"Bisnis Unit",-1),B={class:"col"},w={class:"card-body"},T={class:"field grid"},U=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Jumlah",-1),P={class:"col"},S={key:0,class:"p-error"},D={class:"field grid"},q=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Submit",-1),$={class:"col-12 md:col-3"},I={class:"flex items-center"},M=["value","onClick"],A={key:0,class:"p-error"},z={class:"field grid"},R=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Terima Cash",-1),Y={class:"col-12 md:col-3"},J={class:"flex items-center"},K=["value","onClick"],L={key:0,class:"p-error"},Z={class:"field grid"},j=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Pembelian",-1),F={class:"col-12 md:col-3"},G={class:"flex items-center"},H=["value","onClick"],O={key:0,class:"p-error"},Q={class:"field grid"},W=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Terima Barang",-1),X={class:"col-12 md:col-3"},ee={class:"flex items-center"},ae=["value","onClick"],te={key:0,class:"p-error"},le={class:"field grid"},ce=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Penyerahan Ke User",-1),oe={class:"col-12 md:col-3"},re={class:"flex items-center"},ne=["value","onClick"],de={key:0,class:"p-error"},ie={class:"field grid"},se=(0,l.createElementVNode)("label",{class:"col-fixed w-9rem",style:{width:"180px"}},"Tgl. Closing",-1),ue={class:"col-12 md:col-3"},me={class:"flex items-center"},pe=["value","onClick"],he={key:0,class:"p-error"},ke={class:"form-group"};const Ve={data:function(){return{errors:[],ca:[],mask:{input:"DD MMM YYYY"},token:localStorage.getItem("token"),id:localStorage.getItem("id"),checkname:[],checkto:[]}},mounted:function(){this.getCash()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("/api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(a){e.checkto=a.data.map((function(e){return e.to})),e.checkname=a.data.map((function(e){return e.name})),e.checkname.includes("Cash Advance")||e.checkto.includes("/cash-advance")?e.getCash():e.$router.push("/access")})):this.$router.push("/login")},getCash:function(){var e=this;this.axios.get("/api/edit-cash/"+this.$route.params.code,{headers:{Authorization:"Bearer "+this.token}}).then((function(a){e.ca=a.data})).catch((function(a){401==a.response.status&&(e.$toast.add({severity:"error",summary:"Error",detail:"Sesi Login Expired"}),localStorage.clear(),localStorage.setItem("Expired","true"),setTimeout((function(){return e.$router.push("/login")}),2e3))}))},UpdateCash:function(){var e=this;this.errors=[],this.axios.put("/api/update-cash/"+this.$route.params.code,this.ca,{headers:{Authorization:"Bearer "+this.token}}).then((function(a){setTimeout((function(){return e.$router.push("/cash-advance")}),1e3),e.$toast.add({severity:"success",summary:"Success Message",detail:"Success Update"})})).catch((function(a){e.errors=a.response.data.errors}))}}};const _e=(0,t(83744).Z)(Ve,[["render",function(e,a,t,Ve,_e,fe){var ve=this,ge=(0,l.resolveComponent)("Toast"),Ne=(0,l.resolveComponent)("Toolbar"),ye=(0,l.resolveComponent)("InputText"),Ee=(0,l.resolveComponent)("InputNumber"),Ce=(0,l.resolveComponent)("Button"),be=(0,l.resolveComponent)("DatePicker");return(0,l.openBlock)(),(0,l.createElementBlock)("div",null,[(0,l.createVNode)(ge),(0,l.createElementVNode)("div",c,[(0,l.createVNode)(Ne,{class:"mb-4"},{start:(0,l.withCtx)((function(){return[o]})),_:1}),this.ca?((0,l.openBlock)(),(0,l.createElementBlock)("form",{key:0,onSubmit:a[20]||(a[20]=(0,l.withModifiers)((function(){return fe.UpdateCash&&fe.UpdateCash.apply(fe,arguments)}),["prevent"]))},[(0,l.createElementVNode)("div",r,[(0,l.createElementVNode)("div",n,[(0,l.createElementVNode)("div",d,[i,(0,l.createElementVNode)("div",s,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.ca_idd,"onUpdate:modelValue":a[0]||(a[0]=function(e){return _e.ca.ca_idd=e}),disabled:""},null,8,["modelValue"])])]),(0,l.createElementVNode)("div",u,[m,(0,l.createElementVNode)("div",p,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.ireqd_id,"onUpdate:modelValue":a[1]||(a[1]=function(e){return _e.ca.ireqd_id=e}),disabled:""},null,8,["modelValue"])])])]),(0,l.createElementVNode)("div",h,[(0,l.createElementVNode)("div",k,[V,(0,l.createElementVNode)("div",_,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.req,"onUpdate:modelValue":a[2]||(a[2]=function(e){return _e.ca.req=e}),disabled:""},null,8,["modelValue"])])]),(0,l.createElementVNode)("div",f,[v,(0,l.createElementVNode)("div",g,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.ireq_user,"onUpdate:modelValue":a[3]||(a[3]=function(e){return _e.ca.ireq_user=e}),disabled:""},null,8,["modelValue"])])])]),(0,l.createElementVNode)("div",N,[(0,l.createElementVNode)("div",y,[E,(0,l.createElementVNode)("div",C,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.ireq_date,"onUpdate:modelValue":a[4]||(a[4]=function(e){return _e.ca.ireq_date=e}),disabled:""},null,8,["modelValue"])])]),(0,l.createElementVNode)("div",b,[x,(0,l.createElementVNode)("div",B,[(0,l.createVNode)(ye,{type:"text",modelValue:_e.ca.bu,"onUpdate:modelValue":a[5]||(a[5]=function(e){return _e.ca.bu=e}),disabled:""},null,8,["modelValue"])])])])]),(0,l.createElementVNode)("div",w,[(0,l.createElementVNode)("div",T,[U,(0,l.createElementVNode)("div",P,[(0,l.createVNode)(Ee,{mode:"currency",currency:"IDR",locale:"id",modelValue:_e.ca.ca_pic_name,"onUpdate:modelValue":a[6]||(a[6]=function(e){return _e.ca.ca_pic_name=e}),placeholder:"Masukan Jumlah",class:(0,l.normalizeClass)({"p-invalid":_e.errors.ca_pic_name})},null,8,["modelValue","class"]),_e.errors.ca_pic_name?((0,l.openBlock)(),(0,l.createElementBlock)("small",S,(0,l.toDisplayString)(_e.errors.ca_pic_name[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",D,[q,(0,l.createElementVNode)("div",$,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_submit_date,"onUpdate:modelValue":a[8]||(a[8]=function(e){return _e.ca.ca_submit_date=e}),masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",I,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl. Submit"},null,8,M),_e.ca.ca_submit_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[7]||(a[7]=function(e){return _e.ca.ca_submit_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","masks"]),_e.errors.ca_submit_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",A,(0,l.toDisplayString)(_e.errors.ca_submit_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",z,[R,(0,l.createElementVNode)("div",Y,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_recv_cash_date,"onUpdate:modelValue":a[10]||(a[10]=function(e){return _e.ca.ca_recv_cash_date=e}),"min-date":this.ca.ca_submit_date,masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",J,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl. Terima Cash"},null,8,K),_e.ca.ca_recv_cash_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[9]||(a[9]=function(e){return _e.ca.ca_recv_cash_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","min-date","masks"]),_e.errors.ca_recv_cash_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",L,(0,l.toDisplayString)(_e.errors.ca_recv_cash_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",Z,[j,(0,l.createElementVNode)("div",F,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_purchase_date,"onUpdate:modelValue":a[12]||(a[12]=function(e){return _e.ca.ca_purchase_date=e}),"min-date":this.ca.ca_recv_cash_date,masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",G,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl. Pembelian"},null,8,H),_e.ca.ca_purchase_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[11]||(a[11]=function(e){return _e.ca.ca_purchase_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","min-date","masks"]),_e.errors.ca_purchase_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",O,(0,l.toDisplayString)(_e.errors.ca_purchase_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",Q,[W,(0,l.createElementVNode)("div",X,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_recv_item_date,"onUpdate:modelValue":a[14]||(a[14]=function(e){return _e.ca.ca_recv_item_date=e}),"min-date":this.ca.ca_purchase_date,masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",ee,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl Terima Barang"},null,8,ae),_e.ca.ca_recv_item_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[13]||(a[13]=function(e){return _e.ca.ca_recv_item_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","min-date","masks"]),_e.errors.ca_recv_item_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",te,(0,l.toDisplayString)(_e.errors.ca_recv_item_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",le,[ce,(0,l.createElementVNode)("div",oe,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_hand_over_date,"onUpdate:modelValue":a[16]||(a[16]=function(e){return _e.ca.ca_hand_over_date=e}),"min-date":this.ca.ca_recv_item_date,masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",re,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl Penyerahan"},null,8,ne),_e.ca.ca_hand_over_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[15]||(a[15]=function(e){return ve.ca.ca_hand_over_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","min-date","masks"]),_e.errors.ca_hand_over_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",de,(0,l.toDisplayString)(_e.errors.ca_hand_over_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",ie,[se,(0,l.createElementVNode)("div",ue,[(0,l.createVNode)(be,{modelValue:_e.ca.ca_settlement_date,"onUpdate:modelValue":a[18]||(a[18]=function(e){return _e.ca.ca_settlement_date=e}),"min-date":this.ca.ca_recv_item_date,masks:_e.mask},{default:(0,l.withCtx)((function(e){var t=e.inputValue,c=e.togglePopover;return[(0,l.createElementVNode)("div",me,[(0,l.createElementVNode)("input",{class:"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none",value:t,onClick:c,readonly:"",placeholder:"Pilih Tgl Terima Barang"},null,8,pe),_e.ca.ca_settlement_date?((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:1,icon:"pi pi-trash",class:"p-button-danger",onClick:a[17]||(a[17]=function(e){return _e.ca.ca_settlement_date=""})})):((0,l.openBlock)(),(0,l.createBlock)(Ce,{key:0,icon:"pi pi-calendar",onClick:c},null,8,["onClick"]))])]})),_:1},8,["modelValue","min-date","masks"]),_e.errors.ca_settlement_date?((0,l.openBlock)(),(0,l.createElementBlock)("small",he,(0,l.toDisplayString)(_e.errors.ca_settlement_date[0]),1)):(0,l.createCommentVNode)("",!0)])]),(0,l.createElementVNode)("div",ke,[(0,l.createVNode)(Ce,{class:"p-button-rounded p-button-primary mr-2",icon:"pi pi-check",label:"Simpan",type:"submit"}),(0,l.createVNode)(Ce,{label:"Cancel",class:"p-button-rounded p-button-secondary mt-2",icon:"pi pi-times",onClick:a[19]||(a[19]=function(a){return e.$router.push("/cash-advance")})})])])],32)):(0,l.createCommentVNode)("",!0)])])}]])}}]);