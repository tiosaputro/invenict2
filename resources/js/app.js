require('./bootstrap');
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';
import 'prismjs/themes/prism-coy.css';
import './assets/styles/layout.scss';
import './assets/demo/flags/flags.css';
import './assets/demo/badges.scss';
import '../css/emp-font.woff';
import './assets/themes/vela-blue/theme.css';
import 'jquery/dist/jquery.min';
import 'popper.js/dist/popper.min';
import 'bootstrap/dist/js/bootstrap.min';
import 'v-calendar/dist/style.css';

import { createApp, reactive } from 'vue';
import axios from 'axios';
import AppWrapper from './pages/Layout/AppWrapper.vue';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import BlockViewer from './pages/Layout/BlockViewer.vue';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Chart from 'primevue/chart';
import Carousel from 'primevue/carousel';
import Checkbox from 'primevue/checkbox';
import CodeHighlight from './pages/Layout/AppCodeHighlight.js';
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
import {FilterMatchMode} from 'primevue/api';
import html2pdf from 'html2pdf.js';
import InlineMessage from 'primevue/inlinemessage';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Menu from 'primevue/menu';
import Menubar from "primevue/menubar";
import moment from 'moment';
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';
import { OhVueIcon, addIcons } from "oh-vue-icons";
import Password from 'primevue/password';
import ProgressSpinner from 'primevue/progressspinner';
import Pdf from 'pdfvuer';
import PrimeVue from 'primevue/config';
import Rating from 'primevue/rating';
import RadioButton from 'primevue/radiobutton';
import Ripple from 'primevue/ripple';
import router from './router';
import Sidebar from 'primevue/sidebar';
import SplitButton from 'primevue/splitbutton';
import SpeedDial from 'primevue/speeddial';
import StarRating from 'vue-star-rating';
import StyleClass from 'primevue/styleclass';
import { StreamBarcodeReader } from 'vue-barcode-reader';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Textarea from 'primevue/textarea';
import TieredMenu from "primevue/tieredmenu";
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
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});
const appUrl = process.env.MIX_APP_URL || 'http://localhost/invenict2/public';
app.config.globalProperties.$moment = moment;
app.config.globalProperties.$html2pdf = html2pdf;
app.config.globalProperties.$FilterMatchMode = FilterMatchMode;
app.config.globalProperties.$baseUrl = appUrl

// Set Axios default base URL
axios.defaults.baseURL = process.env.MIX_APP_URL || 'http://localhost/invenict2/public';
const token = localStorage.getItem('token');
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

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

app.component('Badge', Badge);
app.component('BlockViewer', BlockViewer);
app.component('Button', Button);
app.component('Calendar', Calendar);
app.component('Chart',Chart);
app.component('Carousel',Carousel);
app.component('Checkbox',Checkbox );
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
app.component('RadioButton', RadioButton);
app.component('Rating', Rating);
app.component('Sidebar', Sidebar);
app.component('StarRating',StarRating);
app.component('SplitButton', SplitButton);
app.component('SpeedDial', SpeedDial);
app.component('StreamBarcodeReader', StreamBarcodeReader);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('Textarea', Textarea);
app.component('Toast', Toast);
app.component('Toolbar', Toolbar);
app.component('ToggleButton', ToggleButton);
app.component('TreeSelect',TreeSelect);
app.component("v-icon", OhVueIcon);

app.mount('#app');