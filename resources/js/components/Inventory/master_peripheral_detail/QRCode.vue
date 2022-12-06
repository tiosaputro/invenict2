<template>
    <div ref="qrcode"/>
</template>
<script>
import * as QRCode from 'easyqrcodejs'   
 
export default {
  data() {
    return {
      judul:"",
      token: localStorage.getItem('token'),
    };
  },
  mounted(){
    var invent_code_dtl = localStorage.getItem('code')
      this.axios.get('/api/detail-peripherall/' +invent_code_dtl, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.judul = response.data.invent_bu+" - ICT";
        var value = process.env.MIX_APP_URL+'/detPeripheral/'+ +invent_code_dtl;
        var title = this.judul;
        var options = {
          width: 256,
          height: 256,
          colorDark : "#000000",
          colorLight : "#ffffff",
          correctLevel : QRCode.CorrectLevel.L,
          text: value,

          title: title,
          titleFont: "normal normal bold 18px Arial", //font. default is "bold 16px Arial"
          titleColor: "#000", // color. default is "#000"
          titleBackgroundColor: "#fff", // background color. default is "#fff"
          titleHeight: 40, // height, including subTitle. default is 0
          titleTop: 20, // draws y coordinates. default is 30
          logo: '/assets/layout/images/logo_emp_new.png', 
          logoWidth: 100, // fixed logo width. default is `width/3.5`
          logoHeight: 100, // fixed logo height. default is `heigth/3.5`
          logoBackgroundTransparent:true,

          quietZone: 10,
          quietZoneColor: "rgba(0,0,0,0)",

          dotScaleTiming_H: 1, // For horizontal timing block, must be greater than 0, less than or equal to 1. default is 1
          dotScaleTiming_V: 1, // For vertical timing block, must be greater than 0, less than or equal to 1. default is 1
          
          dotScaleA: 1, // Dafault for alignment block, must be greater than 0, less than or equal to 1. default is 1
          dotScaleAO: 1, // For alignment outer block, must be greater than 0, less than or equal to 1. default is 1
          dotScaleAI: 1, // For alignment inner block, must be greater than 0, less than or equal to 1. default is 1alignment inner block, must be greater than 0, less than or equal to 1. default is 1
        }
    // Create new QRCode Object
    new QRCode(this.$refs.qrcode, options);
  });
   },
  }
</script>