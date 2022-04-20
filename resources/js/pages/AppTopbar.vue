<template>
	<div class="layout-topbar">
		<router-link to="/dashboard" class="layout-topbar-logo">
			<img alt="Logo" :src="topbarImage()" />
		</router-link>
		<button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle">
			<i class="pi pi-bars"></i>
		</button>

		<button class="p-link layout-topbar-menu-button layout-topbar-button"
			v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', 
			leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}">
			<i class="pi pi-ellipsis-v"></i>
		</button>
		<ul class="layout-topbar-menu hidden lg:flex origin-top" v-if="this.user.usr_name">
			<li> 
				<button class="p-link layout-profile-link" @click="onClick">
				  <img :src="'/profile/' + user.usr_foto" class="mr-2" width="30" height="30" v-if="this.user.usr_foto"/>
					<span style="color: white;">Hi, {{user.usr_name}}</span>
				</button>
				<transition name="layout-submenu-wrapper layout-topbar-button">
            		<ul v-show="expanded">
						<li>
							<button class="p-link" @click="Logout"><span style="color: white;" >Logout</span></button>
						</li>
					</ul>
        		</transition>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	data() {
		return {
          token: null,
          user: [],
		  expanded: false,
		 }
		},
	created(){
		setTimeout( () => this.getUser(),1000);
		},
    methods: {
		getUser(){
          this.token =localStorage.getItem('token');
		  if(this.token){
			this.axios.get('/api/user', {headers: {'Authorization': 'Bearer '+this.token}})
			 .then((response) => {
				this.user = response.data;
			}).catch(error=>{
				if (error.response.status == 401) {
					this.$toast.add({
						everity:'error', summary: 'Error', detail:'Sesi Login Expired'
					});
					localStorage.clear();
					localStorage.setItem("Expired","true")
					setTimeout( () => this.$router.push('/login'),2000);
				}
			  });
		  	 }
			},
		Logout(){
			this.axios.get('/api/logout', {headers: {'Authorization': 'Bearer '+this.token}}).then(() => {
				this.user= [];
				localStorage.clear();
				localStorage.setItem("logOut", "true");
				this.$router.push('/login');
           	 });
        	},
		onClick(event){
			this.expanded = !this.expanded;
			event.preventDefault();
		},
        onMenuToggle(event) {
            this.$emit('menu-toggle', event);
        },
		onTopbarMenuToggle(event) {
            this.$emit('topbar-menu-toggle', event);
        },
		topbarImage() {
			return this.$appState.darkTheme ? '/assets/layout/images/logo_emp_new.png' : '/assets/layout/images/logo_emp_new.png';
		}
    },
	computed: {
		darkTheme() {
			return this.$appState.darkTheme;
		}
	}
}
</script>