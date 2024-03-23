<template>
  <Toast/>
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, rgba(53, 142, 235, 0.8) 10%, rgba(247, 149, 48, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                    <div class="grid flex flex-column align-items-center">
                        <div class="flex justify-content-center align-items-center border-circle" style="width:3.2rem; height:3.2rem;">
                          <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s"/>
                        </div>
                        <h1 class="text-900 font-bold text-4xl lg:text-5xl mb-2">Verification</h1>
                        <span class="text-900 text-center">Please wait...</span>
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
        data:[],
    };
  },
  mounted() {
    this.cekToken();
    },
    methods: {
        cekToken(){
            var myHeaders = new Headers();
            myHeaders.append("Accept", "application/json");
            myHeaders.append("Authorization", "Bearer "+this.$route.params.token);

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                redirect: 'follow'
            };

            fetch("https://intra21.emp.id/api/sso/"+this.$route.params.token, requestOptions)
            .then(response => response.json())
            .then(response => this.getDataUser(response));
            },
        getDataUser(response){
            this.data = response.data.profile_AD;
            this.axios.get('/sanctum/csrf-cookie').then(() => {
             this.axios.post('/api/login-intranet',this.data).then((response)=>{
                this.$toast.add({
                severity: "success",
                summary: "Login Success!",
                detail: "Welcome " + response.data.usr_name + " 👋",
              });
              localStorage.clear();
              localStorage.setItem("loggedIn", "true");
              localStorage.setItem("token", response.data.token);
              localStorage.setItem("usr_loc", response.data.usr_loc);
              localStorage.setItem('active',0);
              localStorage.setItem('active1',0);
              localStorage.setItem('active2',0);
              localStorage.setItem('active3',0);
              setTimeout( () => this.$router.push('/dashboard'), 1000);
             });
          });
        }
    },
};

</script>