"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6281],{36281:(e,t,o)=>{o.r(t),o.d(t,{default:()=>i});var a=o(70821);const n={data:function(){return{displayDialog:!1,token:localStorage.getItem("token"),checkname:[],checkto:[],id:localStorage.getItem("id")}},created:function(){this.cekUser()},methods:{cekUser:function(){var e=this;this.id?this.axios.get("api/cek-user/"+this.id,{headers:{Authorization:"Bearer "+this.token}}).then((function(t){e.checkto=t.data.map((function(e){return e.to})),e.checkname=t.data.map((function(e){return e.name})),e.checkname.includes("Master Peripheral")||e.checkto.includes("/master-peripheral")?e.displayDialog=!0:(e.$toast.add({severity:"error",summary:"403",detail:"Cannot Access This Page"}),setTimeout((function(){return e.$router.push("/dashboard")}),2e3))})):this.$router.push("/login")},onDecode:function(e){this.displayDialog=!1,localStorage.setItem("barcode",e),window.close()}},render:function(e,t,o,n,i,r){var c=(0,a.resolveComponent)("StreamBarcodeReader"),s=(0,a.resolveComponent)("Dialog");return(0,a.openBlock)(),(0,a.createElementBlock)("div",null,[(0,a.createVNode)(s,{visible:i.displayDialog,"onUpdate:visible":t[0]||(t[0]=function(e){return i.displayDialog=e}),style:{width:"500px"},header:"Scan QR-Code",modal:!0,class:"p-fluid"},{default:(0,a.withCtx)((function(){return[(0,a.createVNode)(c,{onDecode:r.onDecode},null,8,["onDecode"])]})),_:1},8,["visible"])])}},i=n}}]);