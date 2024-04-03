<template>
    <div ref="qrcode" />
    <Button @click="downloadQRCode">Download QR Code</Button>
</template>
<script>
    import * as QRCode from 'easyqrcodejs'

    export default {
        data() {
            return {
                judul: "", 
                nameQr:""
            };
        },
        mounted() {
            var invent_code_dtl = localStorage.getItem('code')
            this.axios.get('/api/detail-peripherall/' + invent_code_dtl).then((response) => {
                this.judul = response.data.invent_bu + " - ICT";
                var value = process.env.MIX_APP_URL + '/detPeripheral/' + +invent_code_dtl;
                var title = this.judul;
                this.nameQr = response.data.name + response.data.invent_sn;
                var subtitle = response.data.invent_tgl_perolehan;
                var options = {
                    width: 256,
                    height: 256,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.L,
                    text: value,

                    title: title,
                    titleFont: "normal normal bold 16px Arial", //font. default is "bold 16px Arial"
                    titleColor: "#000", // color. default is "#000"
                    titleBackgroundColor: "#fff", // background color. default is "#fff"
                    titleHeight: 40, // height, including subTitle. default is 0
                    titleTop: 15, // draws y coordinates. default is 30
                    // logo: '/assets/layout/images/logo-emp.png', 
                    // logoWidth: 100, // fixed logo width. default is `width/3.5`
                    // logoHeight: 100, // fixed logo height. default is `heigth/3.5`
                    // logoBackgroundTransparent:true,

                    backgroundImage: '/assets/logo-emp.png', // Background Image
                    backgroundImageAlpha: 1, // Background image transparency, value between 0 and 1. default is 1. 
                    // autoColor: false, // Automatic color adjustment(for data block)
                    // autoColorDark: "rgba(0, 0, 0, .6)", // Automatic color: dark CSS color
                    // autoColorLight: "rgba(255, 255, 255, .7)", // Automatic color: light CSS color

                    subTitle: subtitle, // content
                    subTitleFont: "normal normal normal 13px Arial", // font. default is "14px Arial"
                    subTitleColor: "#000", // color. default is "4F4F4F"
                    subTitleTop: 35, // draws y coordinates. default is 0

                    quietZone: 14,
                    quietZoneColor: "rgba(0,0,0,0)",

                    dotScaleTiming_H: 1, // For horizontal timing block, must be greater than 0, less than or equal to 1. default is 1
                    dotScaleTiming_V: 1, // For vertical timing block, must be greater than 0, less than or equal to 1. default is 1

                    dotScaleA: 1, // Dafault for alignment block, must be greater than 0, less than or equal to 1. default is 1
                    dotScaleAO: 1, // For alignment outer block, must be greater than 0, less than or equal to 1. default is 1
                    dotScaleAI: 1, // For alignment inner block, must be greater than 0, less than or equal to 1. default is 1alignment inner block, must be greater than 0, less than or equal to 1. default is 1
                }
                // Create new QRCode Object
                this.qrcode = new QRCode(this.$refs.qrcode, options);
            });
        },
        methods: {
          downloadQRCode() {
            this.qrcode.download("QR Code "+this.nameQr);
          }
        }
    }
</script>
