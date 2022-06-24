"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_Approval_atasan_Ict_request_verifikasi_from_email_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************************************************************************************************************************************************************/
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
      dialogReject: false,
      submitted: false,
      verif: [],
      kode: [],
      reason: {
        ket: null
      },
      filters: {
        'global': {
          value: null,
          matchMode: primevue_api__WEBPACK_IMPORTED_MODULE_0__.FilterMatchMode.CONTAINS
        }
      },
      code: this.$route.params.code,
      token: localStorage.getItem('token'),
      id: localStorage.getItem('id'),
      status: null
    };
  },
  mounted: function mounted() {
    this.getNoreq();
  },
  methods: {
    cek: function cek() {
      this.status = this.$route.params.status;

      if (this.status == 'approve') {
        this.Approve();
      }

      if (this.status == 'reject') {
        this.dialogReject = true;
      }
    },
    Approve: function Approve() {
      var _this = this;

      this.$confirm.require({
        group: 'positionDialog',
        message: "Are you sure you agree with this application?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        position: 'top',
        accept: function accept() {
          _this.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Successfully approved the request"
          });

          _this.axios.get('/api/updateStatusPermohonan/' + _this.$route.params.code, {
            headers: {
              'Authorization': 'Bearer ' + _this.token
            }
          });

          setTimeout(function () {
            return _this.$router.push('/ict-request-divisi1');
          }, 1000);
        },
        reject: function reject() {}
      });
    },
    updateReject: function updateReject() {
      var _this2 = this;

      this.submitted = true;

      if (this.reason.ket != null) {
        this.axios.put('/api/updateStatusReject/' + this.$route.params.code, this.reason, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function () {
          _this2.dialogReject = false;

          _this2.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Successfully rejected the request"
          });

          setTimeout(function () {
            return _this2.$router.push('/ict-request-divisi1');
          }, 1000);
        });
      }
    },
    cancelReject: function cancelReject() {
      this.dialogReject = false;
      this.reason.ket = null;
      this.submitted = false;
    },
    getIctDetail: function getIctDetail() {
      var _this3 = this;

      this.axios.get('/api/get-verif/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this3.verif = response.data;
        _this3.loading = false;

        _this3.cek();
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this3.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem('Expired', 'true');
          setTimeout(function () {
            return _this3.$router.push('/login');
          }, 2000);
        }
      });
    },
    getNoreq: function getNoreq() {
      var _this4 = this;

      this.axios.get('/api/get-noreq/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this4.kode = response.data;

        _this4.getIctDetail();
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28 ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "grid crud-demo"
};
var _hoisted_2 = {
  "class": "col-12"
};
var _hoisted_3 = {
  "class": "card"
};

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Verifikasi) ", -1
/* HOISTED */
);

var _hoisted_5 = {
  "class": "flex flex-column md:flex-row md:justify-content-between md:align-items-center"
};
var _hoisted_6 = {
  style: {
    "width": "150px"
  }
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

var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. ");

var _hoisted_11 = {
  "class": "p-grid p-dir-col"
};
var _hoisted_12 = {
  "class": "p-col"
};
var _hoisted_13 = {
  "class": "box"
};
var _hoisted_14 = {
  "class": "field grid"
};

var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  style: {
    "width": "100px"
  }
}, "Reason", -1
/* HOISTED */
);

var _hoisted_16 = {
  "class": "col-3 md-6"
};
var _hoisted_17 = {
  key: 0,
  "class": "p-error"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;

  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_ConfirmDialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ConfirmDialog");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Column = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Column");

  var _component_Button = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Button");

  var _component_DataTable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DataTable");

  var _component_Textarea = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Textarea");

  var _component_Dialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dialog");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog, {
    group: "positionDialog"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "p-mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DataTable, {
    value: $data.verif,
    paginator: true,
    rows: 25,
    loading: $data.loading,
    filters: $data.filters,
    rowHover: true,
    paginatorTemplate: "FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",
    rowsPerPageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
    currentPageReportTemplate: "Showing {first} to {last} of {totalRecords} ICT Request (Verifikasi)",
    responsiveLayout: "scroll"
  }, {
    header: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_6, "No. Request: " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.kode.noreq), 1
      /* TEXT */
      ), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
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
        label: "Kembali",
        "class": "p-button-raised p-button mr-2",
        icon: "pi pi-chevron-left",
        onClick: _cache[1] || (_cache[1] = function ($event) {
          return _ctx.$router.push({
            name: 'Ict Request Divisi 1'
          });
        })
      }), _this.kode.ireq_status == 'Permohonan' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 0,
        label: "Approve",
        "class": "p-button-raised p-button-success mr-2",
        icon: "pi pi-check-square",
        onClick: _cache[2] || (_cache[2] = function ($event) {
          return $options.Approve();
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.kode.ireq_status == 'Permohonan' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 1,
        label: "Reject",
        "class": "p-button-raised p-button-danger mr-2",
        icon: "pi pi-times-circle",
        onClick: _cache[3] || (_cache[3] = function ($event) {
          return _this.dialogReject = true;
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireqd_id",
        header: "No. Detail",
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
        field: "invent_desc",
        header: "Peripheral",
        sortable: true,
        style: {
          "min-width": "12rem"
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
      })];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["value", "loading", "filters"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dialog, {
    visible: $data.dialogReject,
    "onUpdate:visible": _cache[7] || (_cache[7] = function ($event) {
      return $data.dialogReject = $event;
    }),
    style: {
      width: '400px'
    },
    header: "ICT Request Form Dialog Reject",
    modal: true,
    position: "top",
    "class": "field grid"
  }, {
    footer: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Yes",
        onClick: _cache[5] || (_cache[5] = function ($event) {
          return $options.updateReject();
        }),
        "class": "p-button",
        autofocus: ""
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "No",
        onClick: _cache[6] || (_cache[6] = function ($event) {
          return $options.cancelReject();
        }),
        "class": "p-button-text"
      })];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Textarea, {
        autoResize: true,
        type: "text",
        rows: "5",
        cols: "20",
        modelValue: $data.reason.ket,
        "onUpdate:modelValue": _cache[4] || (_cache[4] = function ($event) {
          return $data.reason.ket = $event;
        }),
        placeholder: "Give a reason",
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
          'p-invalid': $data.submitted && !$data.reason.ket
        })
      }, null, 8
      /* PROPS */
      , ["modelValue", "class"]), $data.submitted && !$data.reason.ket ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_17, " Reason not filled ")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["visible"])])])]);
}

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ict_request_verifikasi_from_email_vue_vue_type_template_id_23209d28__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28 */ "./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28");
/* harmony import */ var _Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js");



_Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _Ict_request_verifikasi_from_email_vue_vue_type_template_id_23209d28__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28":
/*!**************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28 ***!
  \**************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_verifikasi_from_email_vue_vue_type_template_id_23209d28__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_verifikasi_from_email_vue_vue_type_template_id_23209d28__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Ict_request_verifikasi_from_email.vue?vue&type=template&id=23209d28");


/***/ })

}]);