<template>
    <div :class="containerClass" @click="onWrapperClick" v-if="loggedIn">
        <AppTopBar
            @menu-toggle="onMenuToggle"
            :model="menuUser"
            @menuitem-click="onMenuItemClick"
        />

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
        <AppTopBar @menu-toggle="onMenuToggle" />

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
</template>

<script>
import AppTopBar from "./Layout/AppTopbar.vue";
import AppMenu from "./Layout/AppMenu.vue";
import AppFooter from "./Layout/AppFooter.vue";

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
            token: localStorage.getItem("token"),
            loggedIn: localStorage.getItem("loggedIn"),
            menuUser: [],
        };
    },
    created() {
        this.create();
    },
    watch: {
        $route() {
            // this.create();
            this.menuActive = false;
            this.$toast.removeAllGroups();
        },
    },
    methods: {
        create() {
            if (this.windowHeight < 650) {
                document.documentElement.style.fontSize = "14px";
            } else if (this.windowHeight > 650 && this.windowHeight < 1200) {
                document.documentElement.style.fontSize = "16px";
            } else if (this.windowHeight >= 1200) {
                document.documentElement.style.fontSize = "18px";
            }

            if (window.location.pathname == "/") {
                this.$router.push("/dashboard");
            }
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
        AppMenu: AppMenu,
        AppFooter: AppFooter,
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
