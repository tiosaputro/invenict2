"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_ict_request_reviewer_Ict_request_reviewer_detail_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js":
/*!***********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var primevue_api__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! primevue/api */ "./node_modules/primevue/api/api.esm.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return _defineProperty({
      showPersonnel1: [],
      showPersonnel2: [],
      showReason: [],
      submitted: false,
      dialogAssign: false,
      assign: [],
      petugas: [],
      kode: '',
      show: false,
      status: '',
      loading: true,
      detail: [],
      filters: {
        'global': {
          value: null,
          matchMode: primevue_api__WEBPACK_IMPORTED_MODULE_1__.FilterMatchMode.CONTAINS
        }
      },
      code: this.$route.params.code,
      token: localStorage.getItem('token'),
      checkname: [],
      checkto: []
    }, "code", null);
  },
  mounted: function mounted() {
    this.getIctDetail();
  },
  methods: {
    formatDate: function formatDate(date) {
      return moment__WEBPACK_IMPORTED_MODULE_0___default()(date).format("DD MMM YYYY HH:mm");
    },
    AssignPerDetail: function AssignPerDetail(ireqd_id) {
      var _this = this;
      this.axios.get('/api/detail/' + ireqd_id + '/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this.assign = response.data;
      });
      this.axios.get('/api/get-pekerja', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this.petugas = response.data;
      });
      this.dialogAssign = true;
    },
    updateAssign: function updateAssign() {
      var _this2 = this;
      this.submitted = true;
      this.code = this.assign.ireqd_id;
      if (this.assign.status == 'RT') {
        if (this.assign.ireq_assigned_to1 != null) {
          this.axios.put('/api/updateAssignPerDetailFromReject/' + this.code, this.assign, {
            headers: {
              'Authorization': 'Bearer ' + this.token
            }
          }).then(function () {
            _this2.assign = [];
            _this2.dialogAssign = false;
            _this2.submitted = false;
            _this2.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000
            });
            _this2.getIctDetail();
          });
        }
      } else {
        if (this.assign.ireq_assigned_to1 != null && this.assign.ireq_assigned_to1_reason != null) {
          this.axios.put('/api/updateAssignPerDetailFromReject/' + this.code, this.assign, {
            headers: {
              'Authorization': 'Bearer ' + this.token
            }
          }).then(function () {
            _this2.assign = [];
            _this2.dialogAssign = false;
            _this2.submitted = false;
            _this2.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000
            });
            _this2.getIctDetail();
          });
        }
      }
    },
    Submit: function Submit(ireqd_id) {
      var _this3 = this;
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: function accept() {
          _this3.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Success Submit",
            life: 1000
          });
          _this3.axios.get('/api/appd/' + ireqd_id + '/' + _this3.$route.params.code, {
            headers: {
              'Authorization': 'Bearer ' + _this3.token
            }
          });
          _this3.getIctDetail();
        },
        reject: function reject() {}
      });
    },
    cancelAssign: function cancelAssign() {
      this.submitted = false;
      this.assign = [];
      this.dialogAssign = false;
    },
    getIctDetail: function getIctDetail() {
      var _this4 = this;
      this.axios.get('/api/ict-detail-reviewer/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this4.detail = response.data.data;
        _this4.showPersonnel1 = response.data.data.map(function (x) {
          return x.ireq_count_status;
        });
        _this4.showPersonnel2 = response.data.data.map(function (x) {
          return x.ireq_count_personnel2;
        });
        _this4.showReason = response.data.data.map(function (x) {
          return x.ireq_count_reason;
        });
        _this4.getNoreq();
        _this4.loading = false;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this4.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true');
          setTimeout(function () {
            return _this4.$router.push('/login');
          }, 2000);
        }
        if (error.response.status == 403) {
          _this4.$router.push('/access');
        }
      });
    },
    getNoreq: function getNoreq() {
      var _this5 = this;
      this.axios.get('/api/get-noreq/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this5.kode = response.data;
        _this5.status = response.data.cekstatus;
        if (_this5.status == 'NT' || _this5.status == 'RT') {
          _this5.show = true;
        }
      });
    },
    CetakPdf: function CetakPdf() {
      var _this6 = this;
      this.loading = true;
      this.axios.get('/api/print-out-ict-request/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        var responseHtml = response.data;
        var myWindow = window.open("", "response", "resizable=yes");
        myWindow.document.write(responseHtml);
        _this6.loading = false;
      });
    } // CetakExcel(){
    //   window.open('/api/report-ict-detail-excel/' +this.code);
    // },
    // CetakPdfReject(){
    //  window.open('/api/report-ict-detail-pdf-tab-reject/' +this.code);
    // },
    // CetakExcelReject(){
    //   window.open('/api/report-ict-detail-excel-tab-reject/' +this.code);
    // },
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8 ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail) ")], -1 /* HOISTED */);
var _hoisted_5 = {
  key: 0
};
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  style: {
    "width": "110px"
  }
}, "No. Request ", -1 /* HOISTED */);
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  style: {
    "width": "110px"
  }
}, "Request Date", -1 /* HOISTED */);
var _hoisted_9 = {
  "class": "table-header text-right"
};
var _hoisted_10 = {
  "class": "p-input-icon-left"
};
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-search"
}, null, -1 /* HOISTED */);
var _hoisted_12 = {
  "class": "grid dir-col"
};
var _hoisted_13 = {
  "class": "col"
};
var _hoisted_14 = {
  "class": "box"
};
var _hoisted_15 = {
  "class": "field grid"
};
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "No Request", -1 /* HOISTED */);
var _hoisted_17 = {
  "class": "col-fixed"
};
var _hoisted_18 = {
  "class": "field grid"
};
var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "No Detail", -1 /* HOISTED */);
var _hoisted_20 = {
  "class": "col-fixed"
};
var _hoisted_21 = {
  "class": "field grid"
};
var _hoisted_22 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Request Type", -1 /* HOISTED */);
var _hoisted_23 = {
  "class": "col-fixed"
};
var _hoisted_24 = {
  "class": "field grid"
};
var _hoisted_25 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Items", -1 /* HOISTED */);
var _hoisted_26 = {
  "class": "col-fixed"
};
var _hoisted_27 = {
  "class": "field grid"
};
var _hoisted_28 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Qty", -1 /* HOISTED */);
var _hoisted_29 = {
  "class": "col-fixed"
};
var _hoisted_30 = {
  "class": "field grid"
};
var _hoisted_31 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Remark", -1 /* HOISTED */);
var _hoisted_32 = {
  "class": "col-fixed"
};
var _hoisted_33 = {
  key: 0,
  "class": "field grid"
};
var _hoisted_34 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Reason", -1 /* HOISTED */);
var _hoisted_35 = {
  "class": "col-fixed"
};
var _hoisted_36 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_37 = {
  "class": "field grid"
};
var _hoisted_38 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Petugas (ICT)", -1 /* HOISTED */);
var _hoisted_39 = {
  "class": "col-fixed"
};
var _hoisted_40 = {
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
  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");
  var _component_Dialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dialog");
  var _directive_tooltip = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDirective)("tooltip");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    end: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_this.kode.ireq_date ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, ": " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_this.kode.noreq), 1 /* TEXT */), _hoisted_7, _hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, ": " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.formatDate(_this.kode.ireq_date)), 1 /* TEXT */)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
    }),
    _: 1 /* STABLE */
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
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_10, [_hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.filters['global'].value,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
          return $data.filters['global'].value = $event;
        }),
        placeholder: "Search. . ."
      }, null, 8 /* PROPS */, ["modelValue"])])])];
    }),
    empty: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Not Found ")];
    }),
    loading: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading ICT Request (Detail) data. Please wait. ")];
    }),
    footer: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Back",
        "class": "p-button-raised p-button mr-2",
        icon: "pi pi-chevron-left",
        onClick: _cache[1] || (_cache[1] = function ($event) {
          return _ctx.$router.push({
            name: 'Ict Request Reviewer'
          });
        })
      }, null, 512 /* NEED_PATCH */), [[_directive_tooltip, 'Click to back', void 0, {
        bottom: true
      }]]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Pdf",
        "class": "p-button-raised p-button-danger mr-2",
        icon: "pi pi-file-pdf",
        onClick: _cache[2] || (_cache[2] = function ($event) {
          return $options.CetakPdf();
        })
      }, null, 512 /* NEED_PATCH */), [[_directive_tooltip, 'Click to print out (PDF)', void 0, {
        bottom: true
      }]])])])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_type",
        header: "Request Type",
        sortable: true,
        style: {
          "min-width": "10rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "name",
        header: "Items",
        sortable: true,
        style: {
          "min-width": "8rem"
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
          "min-width": "10rem"
        }
      }), _this.showPersonnel1.some(function (el) {
        return el > 0;
      }) ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 0,
        field: "ireq_assigned_to1",
        header: "Personnel ICT",
        sortable: true,
        style: {
          "min-width": "10rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.showReason.some(function (el) {
        return el > 0;
      }) ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 1,
        field: "ireq_assigned_to1_reason",
        header: "Reason",
        sortable: true,
        style: {
          "min-width": "8rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.showPersonnel2.some(function (el) {
        return el > 0;
      }) ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Column, {
        key: 2,
        field: "ireq_assigned_to2",
        header: "Personnel ICT (2)",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_status",
        header: "Status",
        sortable: true,
        style: {
          "min-width": "10rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
            "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)('status-bagde status-' + slotProps.data.status.toLowerCase())
          }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(slotProps.data.ireq_status), 3 /* TEXT, CLASS */)];
        }),

        _: 1 /* STABLE */
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        style: {
          "min-width": "17rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [slotProps.data.cekstatus == 'NT' || slotProps.data.cekstatus == 'RT' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 0,
            "class": "p-button-raised p-button-text mr-2 mt-2",
            icon: "pi pi-user-edit",
            label: "Assign",
            onClick: function onClick($event) {
              return $options.AssignPerDetail(slotProps.data.ireqd_id);
            }
          }, null, 8 /* PROPS */, ["onClick"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'RT' || slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'NT' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 1,
            "class": "p-button-raised p-button-success p-button-text mr-2 mt-2",
            icon: "pi pi-check",
            label: "Submit",
            onClick: function onClick($event) {
              return $options.Submit(slotProps.data.ireqd_id);
            }
          }, null, 8 /* PROPS */, ["onClick"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
        }),
        _: 1 /* STABLE */
      })];
    }),

    _: 1 /* STABLE */
  }, 8 /* PROPS */, ["value", "loading", "filters"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dialog, {
    visible: $data.dialogAssign,
    "onUpdate:visible": _cache[13] || (_cache[13] = function ($event) {
      return $data.dialogAssign = $event;
    }),
    style: {
      width: '500px'
    },
    header: "Assign Per-Request",
    modal: true,
    closable: false,
    "class": "fluid"
  }, {
    footer: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Save",
        onClick: _cache[11] || (_cache[11] = function ($event) {
          return $options.updateAssign();
        }),
        "class": "p-button",
        autofocus: ""
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Cancel",
        onClick: _cache[12] || (_cache[12] = function ($event) {
          return $options.cancelAssign();
        }),
        "class": "p-button-text"
      })];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [_hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_no,
        "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
          return $data.assign.ireq_no = $event;
        }),
        disabled: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireqd_id,
        "onUpdate:modelValue": _cache[4] || (_cache[4] = function ($event) {
          return $data.assign.ireqd_id = $event;
        }),
        disabled: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [_hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_type,
        "onUpdate:modelValue": _cache[5] || (_cache[5] = function ($event) {
          return $data.assign.ireq_type = $event;
        }),
        disabled: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [_hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.name,
        "onUpdate:modelValue": _cache[6] || (_cache[6] = function ($event) {
          return $data.assign.name = $event;
        }),
        disabled: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [_hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_qty,
        "onUpdate:modelValue": _cache[7] || (_cache[7] = function ($event) {
          return $data.assign.ireq_qty = $event;
        }),
        disabled: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_30, [_hoisted_31, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Textarea, {
        modelValue: $data.assign.ireq_remark,
        "onUpdate:modelValue": _cache[8] || (_cache[8] = function ($event) {
          return $data.assign.ireq_remark = $event;
        }),
        disabled: "",
        autoResize: ""
      }, null, 8 /* PROPS */, ["modelValue"])])]), $data.assign.status == 'NT' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_33, [_hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Textarea, {
        modelValue: $data.assign.ireq_assigned_to1_reason,
        "onUpdate:modelValue": _cache[9] || (_cache[9] = function ($event) {
          return $data.assign.ireq_assigned_to1_reason = $event;
        }),
        autoResize: true,
        rows: "5",
        cols: "20",
        placeholder: "Enter Reason",
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
          'p-invalid': $data.submitted && !$data.assign.ireq_assigned_to1_reason
        })
      }, null, 8 /* PROPS */, ["modelValue", "class"])]), $data.submitted && !$data.assign.ireq_assigned_to1_reason ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_36, " Reason not filled ")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_37, [_hoisted_38, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_39, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
        modelValue: $data.assign.ireq_assigned_to1,
        "onUpdate:modelValue": _cache[10] || (_cache[10] = function ($event) {
          return $data.assign.ireq_assigned_to1 = $event;
        }),
        options: $data.petugas,
        optionValue: "name",
        optionLabel: "name",
        placeholder: "Select One",
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
          'p-invalid': $data.submitted && !$data.assign.ireq_assigned_to1
        })
      }, null, 8 /* PROPS */, ["modelValue", "options", "class"]), $data.submitted && !$data.assign.ireq_assigned_to1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_40, " Petugas(ICT) not filled ")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])];
    }),
    _: 1 /* STABLE */
  }, 8 /* PROPS */, ["visible"])])])]);
}

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ict_request_reviewer_detail_vue_vue_type_template_id_40ea90f8__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8 */ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8");
/* harmony import */ var _Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ict_request_reviewer_detail.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Ict_request_reviewer_detail_vue_vue_type_template_id_40ea90f8__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js":
/*!*******************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js ***!
  \*******************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_detail.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8":
/*!*************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8 ***!
  \*************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_detail_vue_vue_type_template_id_40ea90f8__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_detail_vue_vue_type_template_id_40ea90f8__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue?vue&type=template&id=40ea90f8");


/***/ })

}]);