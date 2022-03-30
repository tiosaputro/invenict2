require('./bootstrap');
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';
import 'prismjs/themes/prism-coy.css';
import './assets/styles/layout.scss';
import './assets/demo/flags/flags.css';
import '/themes/vela-blue/theme.css';
import 'jquery/dist/jquery.min';
import 'popper.js/dist/popper.min';
import 'bootstrap/dist/js/bootstrap.min';

import { createApp, reactive } from 'vue';
import axios from 'axios';
import router from './router';
import AppWrapper from './pages/AppWrapper.vue';
import PrimeVue from 'primevue/config';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import ConfirmationService from 'primevue/confirmationservice';
import DataTable from 'primevue/datatable';
import { DatePicker } from 'v-calendar';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InlineMessage from 'primevue/inlinemessage';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Menu from 'primevue/menu';
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';
import Password from 'primevue/password';
import QrcodeVue from 'qrcode.vue';
import Rating from 'primevue/rating';
import RadioButton from 'primevue/radiobutton';
import Ripple from 'primevue/ripple';
import Sidebar from 'primevue/sidebar';
import Skeleton from 'primevue/skeleton';
import SplitButton from 'primevue/splitbutton';
import StyleClass from 'primevue/styleclass';
import { StreamBarcodeReader } from 'vue-barcode-reader';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Toolbar from 'primevue/toolbar';
import Tooltip from 'primevue/tooltip';
import ToggleButton from 'primevue/togglebutton';
import Treeselect from 'vue3-treeselect';
import VueAxios from 'vue-axios';
import CodeHighlight from './pages/AppCodeHighlight.js';
import BlockViewer from './pages/BlockViewer.vue';
import StarRating from 'vue-star-rating';
router.beforeEach(function(to, from, next) {
    window.scrollTo(0, 0);
    next();
});

const app = createApp(AppWrapper);

app.config.globalProperties.$appState = reactive({ theme: 'vela-blue'});

app.use(PrimeVue, { ripple: true, inputStyle: 'outlined' });
app.use(ConfirmationService);
app.use(ToastService);
app.use(VueAxios,axios);
app.use(router);

app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);
app.directive('code', CodeHighlight);
app.directive('badge', BadgeDirective);
app.directive('styleclass', StyleClass);

app.component('StarRating',StarRating);
app.component('Badge', Badge);
app.component('BlockViewer', BlockViewer);
app.component('Button', Button);
app.component('Calendar', Calendar);
app.component('ColorPicker', ColorPicker);
app.component('Column', Column);
app.component('ConfirmDialog', ConfirmDialog);
app.component('ConfirmPopup', ConfirmPopup);
app.component('DataTable', DataTable);
app.component('DatePicker',DatePicker);
app.component('Dialog', Dialog);
app.component('Dropdown', Dropdown);
app.component('InlineMessage', InlineMessage);
app.component('InputNumber', InputNumber);
app.component('InputText', InputText);
app.component('Menu', Menu);
app.component('Message', Message);
app.component('MultiSelect', MultiSelect);
app.component('Password', Password);
app.component('QrcodeVue',QrcodeVue);
app.component('RadioButton', RadioButton);
app.component('Rating', Rating);
app.component('Sidebar', Sidebar);
app.component('Skeleton', Skeleton);
app.component('SplitButton', SplitButton);
app.component('StreamBarcodeReader', StreamBarcodeReader);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('Textarea', Textarea);
app.component('Toast', Toast);
app.component('Toolbar', Toolbar);
app.component('ToggleButton', ToggleButton);
app.component('TreeSelect', Treeselect);

app.mount('#app');