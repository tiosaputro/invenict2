require('./bootstrap');
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';
import 'prismjs/themes/prism-coy.css';
import './assets/styles/layout.scss';
import './assets/demo/flags/flags.css';
import './assets/demo/badges.scss';
import './assets/themes/vela-blue/theme.css';
import 'jquery/dist/jquery.min';
import 'popper.js/dist/popper.min';
import 'bootstrap/dist/js/bootstrap.min';
import 'v-calendar/dist/style.css';


import { createApp, reactive } from 'vue';
import axios from 'axios';
import AppWrapper from './pages/AppWrapper.vue';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import BlockViewer from './pages/BlockViewer.vue';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Chart from 'primevue/chart';
import Carousel from 'primevue/carousel';
import CodeHighlight from './pages/AppCodeHighlight.js';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import ConfirmationService from 'primevue/confirmationservice';
import DataTable from 'primevue/datatable';
import { DatePicker } from 'v-calendar';
import Dialog from 'primevue/dialog';
import DialogService from 'primevue/dialogservice';
import DynamicDialog from 'primevue/dynamicdialog';
import Dropdown from 'primevue/dropdown';
import InlineMessage from 'primevue/inlinemessage';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Menu from 'primevue/menu';
import Menubar from "primevue/menubar";
import TieredMenu from "primevue/tieredmenu";
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';
import { OhVueIcon, addIcons } from "oh-vue-icons";
import Password from 'primevue/password';
import ProgressSpinner from 'primevue/progressspinner';
import Pdf from 'pdfvuer';
import PrimeVue from 'primevue/config';
import QrcodeVue from 'qrcode.vue';
import QRCodeVue3 from "qrcode-vue3";
import Rating from 'primevue/rating';
import RadioButton from 'primevue/radiobutton';
import Ripple from 'primevue/ripple';
import router from './router';
import Sidebar from 'primevue/sidebar';
import SplitButton from 'primevue/splitbutton';
import StarRating from 'vue-star-rating';
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
import TreeSelect from 'primevue/treeselect';
import VueAxios from 'vue-axios';
import { ViFileTypePdf,FcImageFile,FaExclamationTriangle,GiPadlock   } from "oh-vue-icons/icons";
addIcons(ViFileTypePdf,FcImageFile,FaExclamationTriangle,GiPadlock  );

router.beforeEach(function(to, from, next) {
    window.scrollTo(0, 0);
    next();
});

const app = createApp(AppWrapper);

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token'); // Replace 'your_token_key' with the actual key used to store your token
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

app.config.globalProperties.$appState = reactive({ theme: 'vela-blue'});
app.use(PrimeVue, { ripple: true, inputStyle: 'outlined' });
app.use(ConfirmationService);
app.use(DialogService);
app.use(ToastService);
app.use(VueAxios,axios);
app.use(router);

app.config.devtools = false;
app.config.debug = false;
app.config.silent = true;

app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);
app.directive('code', CodeHighlight);
app.directive('badge', BadgeDirective);
app.directive('styleclass', StyleClass);

app.component("v-icon", OhVueIcon);

app.component('Badge', Badge);
app.component('BlockViewer', BlockViewer);
app.component('Button', Button);
app.component('Calendar', Calendar);
app.component('Card',Card);
app.component('Chart',Chart);
app.component('Carousel',Carousel);
app.component('ColorPicker', ColorPicker);
app.component('Column', Column);
app.component('ConfirmDialog', ConfirmDialog);
app.component('ConfirmPopup', ConfirmPopup);
app.component('DataTable', DataTable);
app.component('DatePicker',DatePicker);
app.component('Dialog', Dialog);
app.component('DynamicDialog',DynamicDialog);
app.component('Dropdown', Dropdown);
app.component('InlineMessage', InlineMessage);
app.component('InputNumber', InputNumber);
app.component('InputText', InputText);
app.component('Menu', Menu);
app.component("Menubar", Menubar);
app.component("TieredMenu", TieredMenu);
app.component('Message', Message);
app.component('MultiSelect', MultiSelect);
app.component('Password', Password);
app.component('ProgressSpinner', ProgressSpinner);
app.component('Pdf',Pdf);
app.component('QrcodeVue',QrcodeVue);
app.component('QRCodeVue3',QRCodeVue3);
app.component('RadioButton', RadioButton);
app.component('Rating', Rating);
app.component('Sidebar', Sidebar);
app.component('StarRating',StarRating);
app.component('SplitButton', SplitButton);
app.component('StreamBarcodeReader', StreamBarcodeReader);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('Textarea', Textarea);
app.component('Toast', Toast);
app.component('Toolbar', Toolbar);
app.component('ToggleButton', ToggleButton);
app.component('TreeSelect',TreeSelect);

app.mount('#app');