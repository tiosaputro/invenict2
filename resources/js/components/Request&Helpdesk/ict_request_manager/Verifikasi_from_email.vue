<template>
 <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <DataTable
          :rows="25"
          :loading="loading"
        >
          <template #loading>
            Link Verification
          </template>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
        verif:[],
        ireq_id:null,
        todayyy : null,
        loading : true
    };
  },
  mounted() {
    this.verifId();
  },
  methods: {
    verifId(){
      localStorage.setItem("loggedIn", "true");
        this.axios.get('/api/cek-verif-id/'+this.$route.params.code).then((res)=>{
            this.verif = res.data;
          if(res.data == null) {
            localStorage.clear();
             this.$router.push({ name: 'error', params: { stat: 'notvalid' } }) }
          else{
            this.ireq_id = res.data.ireq_id;
            this.todayyy = moment(new Date()).format('YYYY-MM-DD HH:mm:s')

            if (this.verif.expired_at >= this.todayyy){
              this.loginUser();
            }
            else if(this.verif.expired_at <= this.todayyy){
              this.axios.get('/sanctum/csrf-cookie').then(() => {
              this.axios.post('/api/login-approval',this.verif).then((res)=>{
                localStorage.clear();
                localStorage.setItem("loggedIn", "true");
                localStorage.setItem("token", res.data.token);
                localStorage.setItem("id", res.data.id);
                localStorage.setItem("usr_name", res.data.usr_name);
                this.loading = false;
                this.$router.push({ name: 'error', params: { stat: 'expired' } }) 
              });
             });
            }
           }
        });
      },
    loginUser(){
      this.axios.get('/sanctum/csrf-cookie').then(() => {
        this.axios.post('/api/login-approval',this.verif).then((res)=>{
          localStorage.clear();
          localStorage.setItem("loggedIn", "true");
          localStorage.setItem("token", res.data.token);
          localStorage.setItem("id", res.data.id);
          localStorage.setItem("usr_name", res.data.usr_name);
          this.$router.push({ name: 'Ict Request Verifikasi From Email ICT Manager', params: { code: this.ireq_id, status: this.$route.params.status } })
          
          this.loading = false;
        });
      });
    },
  },
};
</script>