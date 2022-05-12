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
            Memverifikasi Link
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
        loading : true,
        loggedIn: localStorage.getItem('loggedIn')
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
             this.$router.push({ name: 'error', params: { stat: 'notvalid' } }) }
          else{
            if (this.loggedIn){
                this.$router.push({name:'Ict Request Reviewer'})
            } 
            else {
              localStorage.clear();
              this.$router.push('/login')
            }
           }
        });
      },
  },
};
</script>