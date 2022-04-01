"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_ict_request_desc_ict_request_desc_detail_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
      status: '',
      kode: '',
      filters: {
        'global': {
          value: null,
          matchMode: primevue_api__WEBPACK_IMPORTED_MODULE_0__.FilterMatchMode.CONTAINS
        }
      },
      code: this.$route.params.code,
      token: localStorage.getItem('token'),
      tes: [],
      ireq: []
    };
  },
  mounted: function mounted() {
    this.getIctDetail();
    this.getNoreq();
  },
  methods: {
    getIctDetail: function getIctDetail() {
      var _this = this;

      this.axios.get('/api/ict-detail/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this.detail = response.data;
        _this.tes = response.data.map(function (x) {
          return x.ireq_assigned_to;
        });

        if (_this.tes.length > 0 && _this.tes[0] != null) {
          _this.ireq = _this.tes;
        } else {}

        _this.loading = false;
      })["catch"](function (error) {
        if (error.response.status == 403) {
          _this.$router.push('/access');
        } else if (error.response.status == 401) {
          _this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem('Expired', 'true');
          setTimeout(function () {
            return _this.$router.push('/login');
          }, 2000);
        }
      });
    },
    getNoreq: function getNoreq() {
      var _this2 = this;

      this.axios.get('/api/get-noreq/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.kode = response.data.noreq;
        _this2.status = response.data.ireq_status;
      });
    },
    DeleteIct: function DeleteIct(ireqd_id) {
      var _this3 = this;

      this.$confirm.require({
        message: "Data ini benar-benar akan dihapus?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: function accept() {
          _this3.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000
          });

          _this3.axios["delete"]('/api/delete-ict-detail/' + ireqd_id, {
            headers: {
              'Authorization': 'Bearer ' + _this3.token
            }
          });

          _this3.getIctDetail();
        },
        reject: function reject() {}
      });
    },
    CetakPdf: function CetakPdf() {
      window.open('/api/report-ict-detail-pdf/' + this.code);
    },
    CetakExcel: function CetakExcel() {
      window.open('/api/report-ict-detail-excel/' + this.code);
    },
    CetakPdfReject: function CetakPdfReject() {
      window.open('/api/report-ict-detail-pdf-reject/' + this.code);
    },
    CetakExcelReject: function CetakExcelReject() {
      window.open('/api/report-ict-detail-excel-reject/' + this.code);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
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

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "my-2"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail) ")], -1
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

var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. ");

var _hoisted_11 = {
  "class": "grid dir-col"
};
var _hoisted_12 = {
  "class": "col"
};
var _hoisted_13 = {
  "class": "box"
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

  var _directive_tooltip = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDirective)("tooltip");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    end: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_5, "No. Request: " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_this.kode), 1
      /* TEXT */
      )];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DataTable, {
    value: $data.detail,
    paginator: true,
    rows: 10,
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
        label: "Kembali",
        "class": "p-button-raised p-button mr-2",
        icon: "pi pi-chevron-left",
        onClick: _cache[1] || (_cache[1] = function ($event) {
          return _ctx.$router.push({
            name: 'Desc'
          });
        })
      })])])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_type",
        header: "Tipe Request",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "name",
        header: "Nama Peripheral",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_desc",
        header: "Deskripsi",
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
        header: "Keterangan",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), _this.ireq.length ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 0,
        field: "ireq_assigned_to",
        header: "Petugas ICT",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_status",
        header: "Status",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        style: {
          "min-width": "12rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [slotProps.data.ireq_status == null ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)(((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 0,
            "class": "p-button-rounded p-button-info mr-2",
            icon: "pi pi-pencil",
            onClick: function onClick($event) {
              return _ctx.$router.push({
                name: 'Edit Ict Request',
                params: {
                  code: slotProps.data.ireq_id
                }
              });
            }
          }, null, 8
          /* PROPS */
          , ["onClick"])), [[_directive_tooltip, 'Edit', void 0, {
            left: true
          }]]) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), slotProps.data.ireq_status == null ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)(((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 1,
            icon: "pi pi-trash",
            "class": "p-button-rounded p-button-danger mr-2",
            onClick: function onClick($event) {
              return $options.DeleteIct(slotProps.data.ireq_id);
            }
          }, null, 8
          /* PROPS */
          , ["onClick"])), [[_directive_tooltip, 'Delete', void 0, {
            right: true
          }]]) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
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

/***/ "./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ict_request_desc_detail_vue_vue_type_template_id_1a29fea4__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ict_request_desc_detail.vue?vue&type=template&id=1a29fea4 */ "./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4");
/* harmony import */ var _ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ict_request_desc_detail.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js");



_ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _ict_request_desc_detail_vue_vue_type_template_id_1a29fea4__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ict_request_desc_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./ict_request_desc_detail.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4 ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ict_request_desc_detail_vue_vue_type_template_id_1a29fea4__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ict_request_desc_detail_vue_vue_type_template_id_1a29fea4__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./ict_request_desc_detail.vue?vue&type=template&id=1a29fea4 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_desc/ict_request_desc_detail.vue?vue&type=template&id=1a29fea4");


/***/ })

}]);