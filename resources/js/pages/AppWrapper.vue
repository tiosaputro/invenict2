<template>
    <Login v-if="$route.path === '/login'" />
    <LoginLegality v-else-if="$route.name === 'Login Legality'" />
    <Error v-else-if="$route.name === 'error'" />
    <NotFound v-else-if="$route.path === '/notfound'" />
    <Access v-else-if="$route.path === '/access'" />
    <Detail v-else-if="$route.name === 'Detail Peripheral'" />
    <CheckVerif v-else-if="$route.name === 'Check Verif'" />
    <CheckLegality v-else-if="$route.name === 'Check Legality'" />
    <CheckReviewer v-else-if="$route.name === 'Ict Request Verifikasi Reviewer'" />
    <App v-else @change-theme="changeTheme" />
</template>

<script>
import EventBus from './AppEventBus';
import App from './App';
import Login from './Login';
import Error from './Error';
import NotFound from './NotFound';
import Access from './Access';
import Detail from './Master_peripheral_detail_qrcode';
import LoginLegality from './LoginLegality';
import CheckVerif from './checkVerif';
import CheckLegality from './CheckLegality';
import CheckReviewer from './CheckVerifReviewer';

export default {
    methods: {
        changeTheme(event) {
            let themeElement = document.getElementById('theme-link');
            themeElement.setAttribute('href', themeElement.getAttribute('href').replace(this.$appState.theme, event.theme));
            this.$appState.theme = event.theme;
            this.$appState.darkTheme = event.dark;
            EventBus.emit('change-theme', event);

            if (event.theme.startsWith('md')) {
                this.$primevue.config.ripple = true;
            }
        }
    },
    components: {
        App,
        Login,
        Error,
        NotFound,
        Access,
        Detail,
        LoginLegality,
        CheckVerif,
        CheckLegality,
        CheckReviewer
    }
}
</script>