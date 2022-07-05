<template>
    <Button
        v-if="this.displayDialog == false"
        label="Scan Qr-Code"
        class="p-button-raised"
        icon="pi pi-qrcode"
        @click="this.displayDialog = true"
    />
    <Dialog
        v-model:visible="displayDialog"
        :style="{ width: '500px' }"
        header="Scan QR-Code"
        :modal="true"
        class="p-fluid"
    >
        <qr-stream @decode="onDecode">
        <div style="color: red;"></div>
        </qr-stream>
    </Dialog>
</template>
<script>
import { QrStream } from 'vue3-qr-reader';
export default {
  components: {
    QrStream,
  },
  data() {
    return {
        displayDialog: false,
    };
  },
  created() {
    this.checkLogin();
  },
  methods: {
    checkLogin(){
        var loggedIn = localStorage.getItem('loggedIn');
        if(loggedIn){
            this.cekUser();
        }
        else{
            this.$router.push('/login')
        }
    },
    cekUser(){
    var id = localStorage.getItem('id');
    var token = localStorage.getItem('token');
      this.axios.get('/api/cek-user/'+ id, {headers: {'Authorization': 'Bearer '+token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Scan Legality") || this.checkto.includes("/scan-qr-code")){
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    onDecode(data){
        this.displayDialog = false;
        window.location = data;
    }
  },
};
</script>