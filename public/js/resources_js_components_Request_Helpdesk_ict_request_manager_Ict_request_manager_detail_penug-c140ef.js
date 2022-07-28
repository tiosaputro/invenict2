"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_ict_request_manager_Ict_request_manager_detail_penug-c140ef"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var primevue_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! primevue/api */ "./node_modules/primevue/api/api.esm.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      loading: true,
      detail: [],
      kode: [],
      filters: {
        'global': {
          value: null,
          matchMode: primevue_api__WEBPACK_IMPORTED_MODULE_0__.FilterMatchMode.CONTAINS
        }
      },
      code: this.$route.params.code,
      token: localStorage.getItem('token'),
      checkname: [],
      checkto: [],
      id: localStorage.getItem('id'),
      status: ''
    };
  },
  mounted: function mounted() {
    this.cekUser();
  },
  methods: {
    cekUser: function cekUser() {
      var _this = this;

      if (this.id) {
        this.axios.get('/api/cek-user/' + this.id, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function (response) {
          _this.checkto = response.data.map(function (x) {
            return x.to;
          });
          _this.checkname = response.data.map(function (x) {
            return x.name;
          });

          if (_this.checkname.includes("Approval Manager") || _this.checkto.includes("/ict-request-manager")) {
            _this.getIctDetail();

            _this.getNoreq();
          } else {
            _this.$router.push('/access');
          }
        });
      } else {
        this.$router.push('/login');
      }
    },
    getIctDetail: function getIctDetail() {
      var _this2 = this;

      this.axios.get('/api/ict-detail-penugasan/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.detail = response.data;
        _this2.loading = false;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this2.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem('Expired', 'true');
          setTimeout(function () {
            return _this2.$router.push('/login');
          }, 2000);
        }
      });
    },
    getNoreq: function getNoreq() {
      var _this3 = this;

      this.axios.get('/api/get-noreq/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this3.kode = response.data;
        _this3.status = response.data.cekstatus;
      });
    },
    CetakPdf: function CetakPdf() {
      var _this4 = this;

      this.loading = true;
      this.axios.get('/api/print-out-ict-request/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        var responseHtml = response.data;
        var myWindow = window.open("", "response", "resizable=yes");
        myWindow.document.write(responseHtml);
        _this4.loading = false;
      });
    } // CetakExcelSedangDikerjakan(){
    //   window.open('/api/report-ict-detail-excel-tab-sedang-dikerjakan/'+this.$route.params.code);
    // }

  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346 ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail) ", -1
/* HOISTED */
);

var _hoisted_5 = {
  style: {
    "width": "140px"
  }
};
var _hoisted_6 = {
  "class": "table-header text-right"
};
var _hoisted_7 = {
  "class": "p-input-icon-left"
};

var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-search"
}, null, -1
/* HOISTED */
);

var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Not Found ");

var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading data. Please wait. ");

var _hoisted_11 = {
  "class": "p-grid p-dir-col"
};
var _hoisted_12 = {
  "class": "p-col"
};
var _hoisted_13 = {
  "class": "box"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_ConfirmDialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ConfirmDialog");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Column = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Column");

  var _component_Button = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Button");

  var _component_DataTable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DataTable");

  var _directive_tooltip = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDirective)("tooltip");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    end: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_5, "No. Request: " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.kode.noreq), 1
      /* TEXT */
      )];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DataTable, {
    value: $data.detail,
    paginator: true,
    rows: 25,
    loading: $data.loading,
    filters: $data.filters,
    rowHover: true,
    paginatorTemplate: "FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",
    rowsPerPageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
    currentPageReportTemplate: "Showing {first} to {last} of {totalRecords} ICT Request (Detail)",
    responsiveLayout: "scroll"
  }, {
    header: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.filters['global'].value,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
          return $data.filters['global'].value = $event;
        }),
        placeholder: "Search. . ."
      }, null, 8
      /* PROPS */
      , ["modelValue"])])])];
    }),
    empty: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_9];
    }),
    loading: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_10];
    }),
    footer: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Back",
        "class": "p-button-raised p-button mr-2",
        icon: "pi pi-chevron-left",
        onClick: _cache[1] || (_cache[1] = function ($event) {
          return _ctx.$router.push({
            name: 'Ict Request Manager'
          });
        })
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Pdf",
        "class": "p-button-raised p-button-danger mr-2",
        icon: "pi pi-file-pdf",
        onClick: _cache[2] || (_cache[2] = function ($event) {
          return $options.CetakPdf();
        })
      }, null, 512
      /* NEED_PATCH */
      ), [[_directive_tooltip, 'Click to print out (PDF)', void 0, {
        bottom: true
      }]]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <Button\r\n                    v-if=\"this.status == 'T'\" \r\n                    label=\"Excel\"\r\n                    class=\"p-button-raised p-button-success mt-2\"\r\n                    icon=\"pi pi-print\"\r\n                    @click=\"CetakExcelSedangDikerjakan()\" \r\n                  /> ")])])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireqd_id",
        header: "No.Detail",
        sortable: true,
        style: {
          "min-width": "6rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_type",
        header: "Request Type",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "name",
        header: "Peripheral",
        sortable: true,
        style: {
          "min-width": "4rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_qty",
        header: "Qty",
        sortable: true,
        style: {
          "min-width": "6rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_remark",
        header: "Remark",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_assigned_to",
        header: "Personnel (ICT)",
        sortable: true,
        style: {
          "min-width": "4rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_status",
        header: "Status",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
            "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)('user-request status-' + slotProps.data.status.toLowerCase())
          }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(slotProps.data.ireq_status), 3
          /* TEXT, CLASS */
          )];
        }),
        _: 1
        /* STABLE */

      })];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["value", "loading", "filters"])])])]);
}

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ict_request_manager_detail_penugasan_vue_vue_type_template_id_18392346__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346 */ "./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346");
/* harmony import */ var _Ict_request_manager_detail_penugasan_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Ict_request_manager_detail_penugasan_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Ict_request_manager_detail_penugasan_vue_vue_type_template_id_18392346__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_manager_detail_penugasan_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_manager_detail_penugasan_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346":
/*!*********************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346 ***!
  \*********************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_manager_detail_penugasan_vue_vue_type_template_id_18392346__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_manager_detail_penugasan_vue_vue_type_template_id_18392346__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_manager/Ict_request_manager_detail_penugasan.vue?vue&type=template&id=18392346");


/***/ })

}]);