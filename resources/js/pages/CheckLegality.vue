<template>
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, rgba(53, 142, 235, 0.8) 10%, rgba(247, 149, 48, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                    <div class="grid flex flex-column align-items-center">
                        <div class="flex justify-content-center align-items-center border-circle" style="width:3.2rem; height:3.2rem;">
                            <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s"/>
                        </div>
                        <h1 class="text-900 font-bold text-4xl lg:text-5xl mb-2">Verification Link</h1>
                        <span class="text-600 text-center">Please wait...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
        verif:[],
    };
  },
  created() {
    this.checkLogin();
  },
  methods: {
    checkLogin(){
      this.axios.get('/api/cek-verif-id/'+this.$route.params.code).then((res)=>{
        this.verif = res.data;
          if(!this.verif) {
             this.$router.push({ name: 'error', params: { stat: 'notvalid' } }) }
          else{
            var loggedIn = localStorage.getItem('loggedIn');
            if(loggedIn){
                var status = this.$route.params.status;
                if(status == 'requestor'){
                    this.$router.push('/check-requester/'+this.verif.ireq_id);
                }
                else if (status == 'ictmanager'){
                    this.$router.push('/check-ict-manager/'+this.verif.ireq_id);
                }
                else if(status == 'higherlevel'){
                    this.$router.push('/check-higher-level/'+this.verif.ireq_id);
                }
            }
            else{
                this.$router.push('/loginn/'+this.$route.params.status+'/'+this.verif.ireq_id)
            }
           }
        });
    },
  },
};
</script>