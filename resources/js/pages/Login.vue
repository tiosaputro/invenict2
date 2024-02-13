<template v-if="this.loggedIn">
<Toast />
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 mt-5 xl:mt-0 text-center">
                <img :src="'assets/layout/images/logo-emp.png'" alt="Emp logo" class="mb-5" style="width:250px; ">
            </div>
            <div class="co l-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, var(--primary-color), rgba(33, 150, 243, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                  <form @submit.prevent="Login">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Welcome to</div>
                        <span class="text-600 font-medium">System ICT Helpdesk 👋</span>
                    </div>
                        <div class="w-full md:w-10 mx-auto">
                          <label for="email1" class="block text-900 text-xl font-medium mb-2">Username</label>
                            <InputText 
                                type="text"
                                v-model="email"
                                style="text-transform:lowercase; padding:1rem;"
                                class="w-full mb-3"
                                :class="{ 'p-invalid': submitted && !email }" 
                                placeholder="my.name" 
                            />
                            <small class="p-error" v-if="submitted && !email"
                                >Username Not Filled.
                            </small>
                            <small v-if="errors.email" class="p-error"
                                > {{ errors.email }} 
                            </small>
                          <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                            <Password 
                                v-model="password"
                                placeholder="*******"
                                class="w-full mb-3"
                                :toggleMask="true"
                                :class="{ 'p-invalid': submitted && !password }" 
                                inputClass="w-full" 
                                inputStyle="padding:1rem"
                            />
                            <small class="p-error" v-if="submitted && !password"
                                >Password Not Filled.
                            </small>
                            <small v-if="errors.password" class="p-error"
                                > {{errors.password}}  
                            </small>
                            <div class="text-center">
                                <Button v-if="!this.loading" label="Sign In" type="submit" class="w-full p-2 text-xl"/>
                                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" v-else/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            email: '',
            password: '',
            errors :[],
            error:false,
            errorr:false,
            submitted: false,
            loggedIn: null,
        }
    },
     mounted(){
        this.cekStatus();
    },
    computed: {
        logoColor() {
            if (this.$appState.darkTheme) return 'white';
            return 'dark';
        }
    },
    methods:{
    cekStatus(){
        this.loggedIn = localStorage.getItem("loggedIn");
            if(this.loggedIn){
                this.$router.push('/dashboard')
            }
    },
    Login() {
      this.errors = [];
      this.submitted = true;
      this.error = false;
      this.errorr = false;
        if ( this.email != '' && this.password != '') {
            this.loading = true;
            const data = new FormData();
            data.append("email", this.email.toLowerCase());
            data.append("password", this.password);
            this.axios.get('/sanctum/csrf-cookie').then(() => {
            this.axios.post('api/login', data).then((response) => {
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
            }).catch(error=> {
                this.submitted = false;
                this.loading = false;
               if (error.response.status == 422) {
                   this.$toast.add({severity:'error', summary: 'Error Message', detail: error.response.data, life: 3000});
                   };
                if (error.response.status == 500){
                    this.$toast.add({severity:'error', summary: 'Error Message', detail:'Please check your connection', life: 3000});
                }
                if (error.response.status == 404){
                    this.$toast.add({severity:'error', summary: 'Error Message', detail:'Please check your username and password', life: 3000});
                }
                  });
                });
              }
            },
    },
}
</script>

<style scoped>
.pi-eye {
    transform:scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform:scale(1.6);
    margin-right: 1rem;
}
</style>