<template>
<div>
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
</div>
</template>
<script>
import { QrStream } from 'vue3-qr-reader';
export default {
  components: {
    QrStream,
  },
    data(){
      return{
        displayDialog:true,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
      }
    },
    created() {
      this.cekUser();
    },
    methods: {
      cekUser(){
      if(this.id){
        this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.checkto = response.data.map((x)=> x.to)
          this.checkname = response.data.map((x)=> x.name)
          if(this.checkto.includes("/scan")){
            this.displayDialog = true;
          }
          else {
            this.$toast.add({
              severity:'error', summary: '403', detail:'Cannot Access This Page'
            });
            setTimeout( () => this.$router.push('/dashboard'),2000);
          }
        });
      } else {
        this.$router.push('/login');
       }
      },
      onDecode(data){
            this.displayDialog = false;
            window.location = data;
          }
      },
    }
</script>