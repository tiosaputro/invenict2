<template v-if="this.loggedIn">
    <Toast />
    <div
        class="flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden style svgbackground"
    >
        <div
            class="grid justify-content-center p-2 lg:p-0"
            style="min-width: 80%"
        >
            <div class="col-12 mt-5 xl:mt-0 text-center">
                <img
                    :src="'assets/layout/images/logo-emp.png'"
                    alt="Emp logo"
                    class="mb-5"
                    style="width: 250px"
                />
            </div>
            <div
                class="co l-12 xl:col-6"
                style="
                    border-radius: 10px;
                    border: 0.5px solid #fefefe20;
                    background: #fefefe10;
                    backdrop-filter: blur(10px);
                    width: 35em !important;
                "
            >
                <div
                    class="h-full w-full m-0 py-7 px-4"
                    style="padding: 0em 1em 1em 1em !important"
                >
                    <form @submit.prevent="Login">
                        <div
                            class="text-center mb-5"
                            style="margin: 1em !important"
                        >
                            <div
                                style="
                                    color: #ffffffee;
                                    margin: 0em 0.5em 0.5em 0.5em !important;
                                "
                                class="text-3xl font-medium mb-3"
                            >
                                ICT Helpdesk
                            </div>
                        </div>
                        <div
                            class="w-full md:w-10 mx-auto"
                            style="
                                width: auto !important;
                                margin: 0em !important;
                            "
                        >
                            <label
                                for="email1"
                                class="block text-sm font-medium mb-2"
                                >Username</label
                            >
                            <InputText
                                type="text"
                                v-model="email"
                                style="text-transform: lowercase; padding: 1rem"
                                class="w-full mb-3"
                                :class="{ 'p-invalid': submitted && !email }"
                                placeholder="Your Username"
                            />
                            <small class="p-error" v-if="submitted && !email"
                                >Username Not Filled.
                            </small>
                            <small v-if="errors.email" class="p-error">
                                {{ errors.email }}
                            </small>
                            <label
                                for="password1"
                                class="block font-medium text-sm mb-2"
                                >Password</label
                            >
                            <Password
                                v-model="password"
                                placeholder="Your Password"
                                class="w-full mb-3"
                                :toggleMask="true"
                                :class="{ 'p-invalid': submitted && !password }"
                                inputClass="w-full"
                                inputStyle="padding:1rem"
                                :feedback="false"
                            />
                            <small class="p-error" v-if="submitted && !password"
                                >Password Not Filled.
                            </small>
                            <small v-if="errors.password" class="p-error">
                                {{ errors.password }}
                            </small>
                            <div class="text-center">
                                <Button
                                    v-if="!this.loading"
                                    label="Sign In"
                                    type="submit"
                                    class="w-full p-2 text-xl"
                                />
                                <ProgressSpinner
                                    style="width: 50px; height: 50px"
                                    strokeWidth="8"
                                    fill="var(--surface-ground)"
                                    animationDuration=".5s"
                                    v-else
                                />
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
            email: "",
            password: "",
            errors: [],
            error: false,
            errorr: false,
            submitted: false,
            loggedIn: null
        };
    },
    mounted() {
        this.cekStatus();
    },
    computed: {
        logoColor() {
            if (this.$appState.darkTheme) return "white";
            return "dark";
        },
    },
    methods: {
        cekStatus() {
            this.loggedIn = localStorage.getItem("loggedIn");
            if (this.loggedIn) {
                this.$router.push("/dashboard");
            }
        },
        Login() {
            this.errors = [];
            this.submitted = true;
            this.error = false;
            this.errorr = false;
            if (this.email != "" && this.password != "") {
                this.loading = true;
                const data = new FormData();
                data.append("email", this.email.toLowerCase());
                data.append("password", this.password);
                this.axios.get("/sanctum/csrf-cookie").then(() => {
                    this.axios
                        .post("api/login", data)
                        .then((response) => {
                            this.$toast.add({
                                severity: "success",
                                summary: "Login Success!",
                                detail:
                                    "Welcome " + response.data.usr_name + " 👋",
                            });
                            localStorage.clear();
                            localStorage.setItem("loggedIn", "true");
                            localStorage.setItem("token", response.data.token);
                            localStorage.setItem("usr_loc",response.data.usr_loc);
                            localStorage.setItem("active", 0);
                            localStorage.setItem("active1", 0);
                            localStorage.setItem("active2", 0);
                            localStorage.setItem("active3", 0);
                            localStorage.setItem("active4", 0);
                            
                            setTimeout(() =>{
                                    this.$router.push("/dashboard")
                            }, 1000);
                        })
                        .catch((error) => {
                            this.submitted = false;
                            this.loading = false;
                            this.$toast.add({
                                severity: "error",
                                summary: "Error Message",
                                detail: error.response.data.message,
                                life: 3000,
                            });
                        });
                });
            }
        },
    },
};
</script>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
