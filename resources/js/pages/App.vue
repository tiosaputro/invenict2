<template>
    <div :class="containerClass" @click="onWrapperClick" v-if="loggedIn">
        <AppTopBar @menu-toggle="onMenuToggle" :value="menuUser" :user="user" @menuitem-click="onMenuItemClick" />
        <div class="layout-sidebar" @click="onSidebarClick" v-if="isMobile == true">
            <AppMenu :model="menuUser" @menuitem-click="onMenuItemClick" />
        </div>
        <div class="layout-main-container">
            <div class="layout-main">
                <router-view />
            </div>
            <AppFooter />
        </div>

        <transition name="layout-mask">
            <div
                class="layout-mask p-component-overlay"
                v-if="mobileMenuActive"
            ></div>
        </transition>
    </div>
    <div :class="containerClass" @click="onWrapperClick" v-else>
        <div class="layout-main-container">
            <div class="layout-main">
                <router-view />
            </div>
            <AppFooter />
        </div>
    </div>
</template>

<script>
import AppTopBar from "./Layout/AppTopbar.vue";
import AppFooter from "./Layout/AppFooter.vue";
import AppMenu from "./Layout/AppMenu.vue";
import { MenuServices } from '../services/MenuServices';
export default {
    emits: ["change-theme"],
    data() {
        return {
            windowHeight: window.innerHeight,
            scale: "16",
            layoutMode: "static",
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            loggedIn: localStorage.getItem("loggedIn"),
            menuUser: [],
            user:[],
            isMobile: false
        };
    },
    watch: {
        $route() {
            this.isMobile = window.innerWidth <= 768;
            window.addEventListener('resize', this.updateIsMobile);
            this.menuActive = false;
            this.$toast.removeAllGroups();
        },
    },
    methods: {
        updateIsMobile() {
            this.isMobile = window.innerWidth <= 768;
        },
        create() {
            if (this.windowHeight < 650) {
                document.documentElement.style.fontSize = "14px";
            } else if (this.windowHeight > 650 && this.windowHeight < 1200) {
                document.documentElement.style.fontSize = "16px";
            } else if (this.windowHeight >= 1200) {
                document.documentElement.style.fontSize = "18px";
            }
           
            const menuServices = new MenuServices();

            menuServices.getdata().then((data) => {
                this.menuUser = data.tree;
                this.user = data.user;
                if(this.isMobile ){
                    this.menuUser[0] = {
                    label: "Home",
                    to: "/dashboard",
                    items: [
                        { label: "Dashboard", to: "/dashboard", items: null }
                    ]
                };
                }
                if (!data.user) {
                    localStorage.clear();
                    localStorage.setItem("logOut", "true");
                    this.$router.push("/login");
                }
            }).catch((error) => {
                console.error("Error fetching data:", error);
            });
        },
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === "overlay") {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }
                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === "static") {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }
            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        onLayoutChange(layoutMode) {
            this.layoutMode = layoutMode;
        },
        addClass(element, className) {
            if (element.classList) element.classList.add(className);
            else element.className += " " + className;
        },
        removeClass(element, className) {
            if (element.classList) element.classList.remove(className);
            else
                element.className = element.className.replace(
                    new RegExp(
                        "(^|\\b)" + className.split(" ").join("|") + "(\\b|$)",
                        "gi"
                    ),
                    " "
                );
        },
        isDesktop() {
            return window.innerWidth >= 992;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === "static")
                    return !this.staticMenuInactive;
                else if (this.layoutMode === "overlay")
                    return this.overlayMenuActive;
            }

            return true;
        },
        changeTheme(event) {
            this.$emit("change-theme", event);
        },
    },
    computed: {
        containerClass() {
            return [
                "layout-wrapper",
                {
                    "layout-overlay": this.layoutMode === "overlay",
                    "layout-static": this.layoutMode === "static",
                    "layout-static-sidebar-inactive":
                        this.staticMenuInactive && this.layoutMode === "static",
                    "layout-overlay-sidebar-active":
                        this.overlayMenuActive && this.layoutMode === "overlay",
                    "layout-mobile-sidebar-active": this.mobileMenuActive,
                    "p-input-filled":
                        this.$primevue.config.inputStyle === "filled",
                    "p-ripple-disabled": this.$primevue.config.ripple === false,
                },
            ];
        },
        logo() {
            return this.$appState.darkTheme
                ? "images/logo-white.svg"
                : "images/logo.svg";
        },
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, "body-overflow-hidden");
        else this.removeClass(document.body, "body-overflow-hidden");
    },
    components: {
        AppTopBar: AppTopBar,
        AppFooter: AppFooter,
        AppMenu: AppMenu,
    },
    mounted() {
        this.isMobile = window.innerWidth <= 768;
        window.addEventListener('resize', this.updateIsMobile);
        this.create();
    },
};
</script>

<style lang="scss" scoped>
@import "../App.scss";
::v-deep(.custom-scrolltop) {
    width: 2rem;
    height: 2rem;
    border-radius: 4px;
    background-color: var(--primary-color);

    &:hover {
        background-color: var(--primary-color);
    }

    .p-scrolltop-icon {
        font-size: 1rem;
        color: var(--primary-color-text);
    }
}
</style>
