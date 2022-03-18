"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_laporan_LaporanRqsDivisiReqPerTahun_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    var _this = this;

    return {
      bulanRequestor: null,
      tahunRequestor: null,
      tahunn: [],
      loading: false,
      req: [],
      token: localStorage.getItem('token'),
      checkname: [],
      checkto: [],
      id: localStorage.getItem('id'),
      items: [{
        label: 'Pdf',
        icon: 'bi bi-file-earmark-pdf text-danger',
        command: function command() {
          window.open('api/req-div-req-per-tahun-pdf/' + _this.tahunRequestor);
        }
      }, {
        label: 'Excel',
        icon: 'bi bi-file-earmark-excel text-success',
        command: function command() {
          window.open('api/req-div-req-per-tahun-excel/' + _this.tahunRequestor);
        }
      }]
    };
  },
  created: function created() {
    this.cekUser();
  },
  methods: {
    cekUser: function cekUser() {
      var _this2 = this;

      if (this.id) {
        this.axios.get('api/cek-user/' + this.id, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function (response) {
          _this2.checkto = response.data.map(function (x) {
            return x.to;
          });
          _this2.checkname = response.data.map(function (x) {
            return x.name;
          });

          if (_this2.checkname.includes("Divisi Requestor Per Tahun") || _this2.checkto.includes("/report-div-req-per-tahun")) {
            _this2.getTahun();
          } else {
            _this2.$router.push('/access');
          }
        });
      } else {
        this.$router.push('/login');
      }
    },
    getPerDivisiRequestorTahun: function getPerDivisiRequestorTahun() {
      var _this3 = this;

      if (this.tahunRequestor != null) {
        this.loading = true;
        this.axios.get('api/count-per-divreq-tahun/' + this.tahunRequestor, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function (response) {
          _this3.req = response.data;
          _this3.loading = false;
        });
      }
    },
    getTahun: function getTahun() {
      var _this4 = this;

      this.axios.get('api/get-tahun', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this4.tahunn = response.data.grafik;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this4.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem("Expired", "true");
          setTimeout(function () {
            return _this4.$router.push('/login');
          }, 2000);
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47 ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "grid"
};
var _hoisted_2 = {
  "class": "col-12"
};
var _hoisted_3 = {
  "class": "card"
};

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "Laporan Request Divisi Requestor Per Tahun", -1
/* HOISTED */
);

var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading data. Please wait. ");

var _hoisted_6 = {
  "class": "table-header p-text-left"
};
var _hoisted_7 = {
  "class": "p-grid p-dir-col"
};
var _hoisted_8 = {
  "class": "p-col"
};
var _hoisted_9 = {
  "class": "box"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_ConfirmDialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ConfirmDialog");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");

  var _component_Column = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Column");

  var _component_SplitButton = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SplitButton");

  var _component_DataTable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DataTable");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DataTable, {
    value: $data.req,
    rows: 25,
    rowHover: true,
    responsiveLayout: "scroll",
    loading: $data.loading,
    stripedRows: ""
  }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createSlots)({
    loading: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_5];
    }),
    header: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.getPerDivisiRequestorTahun();
        }),
        showClear: true,
        modelValue: $data.tahunRequestor,
        "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
          return $data.tahunRequestor = $event;
        }),
        options: $data.tahunn,
        optionValue: "tahun",
        optionLabel: "tahun",
        placeholder: "Pilih Tahun"
      }, null, 8
      /* PROPS */
      , ["modelValue", "options"])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [$data.tahunRequestor ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 0,
        field: "div_name",
        header: "Divisi Requestor",
        style: {
          "min-width": "10rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.tahunRequestor ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 1,
        field: "jumlah",
        header: "Jumlah Request",
        style: {
          "min-width": "10rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
    }),
    _: 2
    /* DYNAMIC */

  }, [$data.tahunRequestor ? {
    name: "footer",
    fn: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SplitButton, {
        label: "Print",
        model: $data.items
      }, null, 8
      /* PROPS */
      , ["model"])])])])];
    })
  } : undefined]), 1032
  /* PROPS, DYNAMIC_SLOTS */
  , ["value", "loading"])])])]);
}

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LaporanRqsDivisiReqPerTahun_vue_vue_type_template_id_3507cd47__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47 */ "./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47");
/* harmony import */ var _LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js");



_LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _LaporanRqsDivisiReqPerTahun_vue_vue_type_template_id_3507cd47__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_LaporanRqsDivisiReqPerTahun_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47":
/*!************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47 ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_LaporanRqsDivisiReqPerTahun_vue_vue_type_template_id_3507cd47__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_LaporanRqsDivisiReqPerTahun_vue_vue_type_template_id_3507cd47__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/laporan/LaporanRqsDivisiReqPerTahun.vue?vue&type=template&id=3507cd47");


/***/ })

}]);