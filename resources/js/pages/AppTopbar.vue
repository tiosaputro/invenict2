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
				<button class="p-link layout-profile-link" @click="onClick" style="object-fit:contain;">
				  <img :src="'/profile/' + user.usr_foto" class="mr-2" width="30" height="30" v-if="this.user.usr_foto"/>
					<span style="color: white;">Hi, {{ toUpper(user.usr_fullname)}}</span>
				</button>
				<transition name="layout-submenu-wrapper layout-topbar-button">
            		<ul v-show="expanded">
						<li>
							<button class="p-link layout-profile-link" @click="Logout"><span style="color: white;" >Logout</span></button>
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
		this.getUser();
		},
    methods: {
		toUpper(str) {
			return str
				.toLowerCase()
				.split(' ')
				.map(function(word) {
					return word[0].toUpperCase() + word.substr(1);
				})
				.join(' ');
		},
		getUser(){
          this.token =localStorage.getItem('token');
		  if(this.token){
			this.axios.get('/api/user', {headers: {'Authorization': 'Bearer '+this.token}})
			 .then((response) => {
				this.user = response.data;
			}).catch(error=>{
				if (error.response.status == 401) {
					this.$toast.add({
						severity:'error', summary: 'Error Message', detail:'Session Login Expired',life: 1000
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
<style scoped>
 /* Dropdown Button */
.dropbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd;}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: #3e8e41;} 
</style>