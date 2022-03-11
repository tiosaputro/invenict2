<template v-if="this.loggedIn">
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 mt-5 xl:mt-0 text-center">
                <img :src="'assets/layout/images/logo_emp_new.png'" alt="logo" class="mb-5" style="width:250px; ">
            </div>
            <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, var(--primary-color), rgba(33, 150, 243, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                  <form @submit.prevent="Login">
                    <div class="text-center mb-5">
                        <img src="layout/images/avatar.png" alt="Image" height="50" class="mb-3">
                        <div class="text-900 text-3xl font-medium mb-3">Welcome</div>
                        <span class="text-600 font-medium">Sign in to continue</span>
                    </div>
                        <div class="w-full md:w-10 mx-auto">
                          <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                            <InputText 
                                type="email"
                                v-model="email"
                                class="w-full mb-3" 
                                placeholder="Email" 
                                style="padding:1rem;"
                            />
                            <small class="p-error" v-if="submitted && !email"
                                >Email Wajib Diisi.
                            </small>
                            <small v-if="errors.email" class="p-error"
                                > Email doesnt exist.   
                            </small>
                    
                          <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                            <Password 
                                v-model="password"
                                placeholder="Password"
                                class="w-full mb-3"
                                :toggleMask="true"
                                :feedback="false"
                                inputClass="w-full" 
                                inputStyle="padding:1rem"
                            />
                            <small class="p-error" v-if="submitted && !password"
                                >Password Wajib Diisi.
                            </small>
                            <small v-if="errors.password" class="p-error"
                                > Unable to login. Incorrect password.  
                            </small>
                            <Button label="Sign In" type="submit" class="w-full p-3 text-xl"/>
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
            email: '',
            password: '',
            errors :[],
            submitted: false,
            loggedIn: null
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
        if (
        this.email != '' &&
        this.password != ''
        )
        {
            const data = new FormData();
            data.append("email", this.email);
            data.append("password", this.password);

            this.axios.get('/sanctum/csrf-cookie').then(() => {
            this.axios.post('api/login', data).then((response) => {
              this.$toast.add({
                severity: "success",
                summary: "Success Message",
                detail: "Success Login",
                life: 1000,
              });
              localStorage.clear();
              localStorage.setItem("loggedIn", "true");
              localStorage.setItem("token", response.data.token);
              localStorage.setItem("id", response.data.id);
              localStorage.setItem("usr_name", response.data.usr_name);
              setTimeout( () => this.$router.push('/dashboard'), 1000);
            }).catch(error=> {
               if (error.response.status == 422) {
                   this.errors = error.response.data;
                   };
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