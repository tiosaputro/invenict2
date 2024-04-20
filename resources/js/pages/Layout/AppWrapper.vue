<template>
    <Login v-if="$route.path === '/login'" />
    <LoginLegality v-else-if="$route.name === 'Login Legality'" />
    <Error v-else-if="$route.name === 'error'" />
    <NotFound v-else-if="$route.path === '/notfound'" />
    <NotFound v-else-if="$route.name === 'Page Not Found'" />
    <Access v-else-if="$route.path === '/access'" />
    <Detail v-else-if="$route.name === 'Detail Peripheral'" />
    <CheckVerif v-else-if="$route.name === 'Check Verif'" />
    <CheckLegality v-else-if="$route.name === 'Check Legality'" />
    <CheckSso v-else-if="$route.name === 'Checking Token SSO'" />
    <CheckReviewer v-else-if="$route.name === 'Ict Request Verifikasi Reviewer'" />
    <App v-else @change-theme="changeTheme" />
</template>

<script>
import EventBus from './AppEventBus';
import App from '../App';
import Login from '../Views/Login';
import Error from '../Layout/Error';
import NotFound from '../Layout/NotFound';
import Access from '../Layout/Access';
import Detail from '../Views/Master_peripheral_detail_qrcode';
import LoginLegality from '../Views/LoginLegality';
import CheckVerif from '../Views/checkVerif';
import CheckLegality from '../Views/CheckLegality';
import CheckReviewer from '../Views/CheckVerifReviewer';
import CheckSso from '../Views/CheckingToken';

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
        CheckReviewer,
        CheckSso,
    }
}
</script>