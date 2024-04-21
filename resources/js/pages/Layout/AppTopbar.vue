<template>
    <div class="layout-topbar">
        <router-link to="/dashboard" class="layout-topbar-logo">
            <img alt="Logo" :src="topbarImage()" /> ICT HELPDESK SYSTEM
        </router-link>
        <button v-if="isMobile == true" class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle">
			<i class="pi pi-bars"></i>
		</button>
        <Menubar v-if="isMobile == false"
            :model="value"
            pt="{
                action: ({ props, state, context }) => ({
                    class: context.active ? 'bg-primary-200 border-round-sm' : context.focused ? 'bg-primary-300 border-round-sm' : undefined
                })
            }"
        />
        
        <button
            class="p-link layout-topbar-menu-button layout-topbar-button"
            v-styleclass="{     
                selector: '@next',
                enterClass: 'hidden',
                enterActiveClass: 'scalein',
                leaveToClass: 'hidden',
                leaveActiveClass: 'fadeout',
                hideOnOutsideClick: true,
            }"
        >
            <img
                :src="'/profile/' + user.usr_foto"
                class="mr-2"
                width="30"
                height="30"
                alt=""
                v-if="user.usr_foto"
            />
            <img
                :src="'/images/default-profile.png'"
                class="mr-2"
                width="30"
                height="30"
                alt=""
                v-else
            />
        </button>
        <ul class="layout-topbar-menu hidden lg:flex origin-top" v-if="user">
            <li>
                <button class="p-link layout-profile-link" @click="onClick" style="object-fit: contain">
                    <img
                        :src="'/profile/' + user.usr_foto"
                        class="mr-2"
                        width="30"
                        height="30"
                        alt=""
                        v-if="user.usr_foto && isMobile == false"
                    />
                    <img
                        :src="'/images/default-profile.png'"
                        class="mr-2"
                        width="30"
                        height="30"
                        alt=""
                        v-else-if="!user.usr_foto && isMobile == false"
                    />
                    <span style="color: var(--newmenubarbuttontext);"
                        >Hi, {{ user.usr_fullname }}</span
                    >
                </button>
                <transition name="layout-submenu-wrapper layout-topbar-button">
                    <ul v-show="expanded">
                        <li>
                            <button class="p-link layout-profile-link" @click="Logout">
                                <span style="color: rgb(10, 10, 10)">Logout</span>
                            </button>
                        </li>
                    </ul>
                </transition>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: {
        value: Array,
        user: Object
    },
    data() {
        return {
            token: null,
            expanded: false,
            isMobile: false
        };
    },
    mounted() {
        this.isMobile = window.innerWidth <= 768;
        window.addEventListener('resize', this.updateIsMobile);
    },
    methods: {
        updateIsMobile() {
            this.isMobile = window.innerWidth <= 768;
        },
        toUpper(str) {
            return str
                .toLowerCase()
                .split(" ")
                .map(function (word) {
                    return word[0].toUpperCase() + word.substr(1);
                })
                .join(" ");
        },
        Logout() {
            this.axios.get("/api/logout").then(() => {
                localStorage.clear();
                localStorage.setItem("logOut", "true");
                this.$router.push("/login");
            });
        },
        onClick(event) {
            this.expanded = !this.expanded;
            event.preventDefault();
        },
        onMenuToggle(event) {
            this.$emit("menu-toggle", event);
        },
        onTopbarMenuToggle(event) {
            this.$emit("topbar-menu-toggle", event);
        },
        topbarImage() {
            return this.$appState.darkTheme
                ? "/assets/layout/images/logo-emp.png"
                : "/assets/layout/images/logo-emp.png";
        },
    },
    computed: {
        darkTheme() {
            return this.$appState.darkTheme;
        },
    },
};
</script>
<style scoped>
/* Dropdown Button */
.dropbtn {
    background-color: #04aa6d;
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
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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
.dropdown-content a:hover {
    background-color: #ddd;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}
</style>
