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
/* harmony import */ var primevue_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! primevue/api */ "./node_modules/primevue/api/api.esm.js");
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return _defineProperty({
      submitted: false,
      dialogAssign: false,
      assign: [],
      petugas: [],
      kode: '',
      status: '',
      loading: true,
      detail: [],
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
      id: localStorage.getItem('id')
    }, "code", null);
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

          if (_this.checkname.includes("Reviewer") || _this.checkto.includes("/ict-request/reviewer")) {
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
    Assignrequest: function Assignrequest(ireqd_id) {
      var _this2 = this;

      this.axios.get('/api/detail/' + ireqd_id, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.assign = response.data;
      });
      this.axios.get('/api/get-pekerja', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.petugas = response.data;
      });
      this.dialogAssign = true;
    },
    updateAssign: function updateAssign() {
      var _this3 = this;

      this.submitted = true;
      this.code = this.assign.ireqd_id;

      if (this.assign.ireq_assigned_to1 != null) {
        this.axios.put('/api/updateAssignPerDetailFromReject/' + this.code, this.assign, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function () {
          _this3.assign = [];
          _this3.dialogAssign = false;
          _this3.submitted = false;

          _this3.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Berhasil Assign",
            life: 3000
          });

          _this3.getIctDetail();
        });
      }
    },
    Submit: function Submit(ireqd_id) {
      var _this4 = this;

      this.$confirm.require({
        message: "Apakah anda yakin?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: function accept() {
          _this4.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Berhasil Diassign",
            life: 1000
          });

          _this4.axios.get('/api/appd/' + ireqd_id + '/' + _this4.$route.params.code, {
            headers: {
              'Authorization': 'Bearer ' + _this4.token
            }
          });

          _this4.getIctDetail();
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
      var _this5 = this;

      this.axios.get('/api/ict-detail/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this5.detail = response.data;
        _this5.loading = false;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this5.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem('Expired', 'true');
          setTimeout(function () {
            return _this5.$router.push('/login');
          }, 2000);
        }
      });
    },
    getNoreq: function getNoreq() {
      var _this6 = this;

      this.axios.get('/api/get-noreq/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this6.kode = response.data.noreq;
        _this6.status = response.data.cekstatus;
      });
    },
    CetakPdf: function CetakPdf() {
      window.open('/api/report-ict-detaill-pdf/' + this.code);
    },
    CetakExcel: function CetakExcel() {
      window.open('/api/report-ict-detail-excel/' + this.code);
    },
    CetakPdfReject: function CetakPdfReject() {
      window.open('/api/report-ict-detail-pdf-tab-reject/' + this.code);
    },
    CetakExcelReject: function CetakExcelReject() {
      window.open('/api/report-ict-detail-excel-tab-reject/' + this.code);
    }
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
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail) ")], -1
/* HOISTED */
);

var _hoisted_5 = {
  style: {
    "width": "130px"
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
var _hoisted_14 = {
  "class": "field grid"
};

var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "No Request", -1
/* HOISTED */
);

var _hoisted_16 = {
  "class": "col-fixed"
};
var _hoisted_17 = {
  "class": "field grid"
};

var _hoisted_18 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Nama Peripheral", -1
/* HOISTED */
);

var _hoisted_19 = {
  "class": "col-fixed"
};
var _hoisted_20 = {
  "class": "field grid"
};

var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem"
}, "Petugas (ICT)", -1
/* HOISTED */
);

var _hoisted_22 = {
  "class": "col-fixed"
};
var _hoisted_23 = {
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

  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");

  var _component_Dialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dialog");

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
            name: 'Ict Request Reviewer'
          });
        })
      }), _this.status != 'RR' && _this.status != 'RA1' && _this.status != 'RA2' && _this.status != 'T' && _this.status != 'D' && _this.status != 'C' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 0,
        label: "Pdf",
        "class": "p-button-raised p-button-danger mr-2",
        icon: "pi pi-file-pdf",
        onClick: _cache[2] || (_cache[2] = function ($event) {
          return $options.CetakPdf();
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.status != 'RR' && _this.status != 'RA1' && _this.status != 'RA2' && _this.status != 'T' && _this.status != 'D' && _this.status != 'C' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 1,
        label: "Excel",
        "class": "p-button-raised p-button-success mt-2",
        icon: "pi pi-print",
        onClick: _cache[3] || (_cache[3] = function ($event) {
          return $options.CetakExcel();
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.status == 'RR' || _this.status == 'RA1' || _this.status == 'RA2' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 2,
        label: "Pdf",
        "class": "p-button-raised p-button-danger mr-2",
        icon: "pi pi-file-pdf",
        onClick: _cache[4] || (_cache[4] = function ($event) {
          return $options.CetakPdfReject();
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.status == 'RR' || _this.status == 'RA1' || _this.status == 'RA2' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
        key: 3,
        label: "Excel",
        "class": "p-button-raised p-button-success mt-2",
        icon: "pi pi-print",
        onClick: _cache[5] || (_cache[5] = function ($event) {
          return $options.CetakExcelReject();
        })
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])];
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
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_assigned_to1",
        header: "Personnel ICT",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_assigned_to1_reason",
        header: "Alasan",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_assigned_to2",
        header: "Personnel ICT (2)",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_status",
        header: "Status",
        sortable: true,
        style: {
          "min-width": "12rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        style: {
          "min-width": "14rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [slotProps.data.ireq_status == 'Reject By ICT Personnel' && _this.status != 'RT' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 0,
            "class": "p-button-raised p-button-text mr-2",
            label: "Assign",
            onClick: function onClick($event) {
              return $options.Assignrequest(slotProps.data.ireqd_id);
            }
          }, null, 8
          /* PROPS */
          , ["onClick"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), slotProps.data.ireq_assigned_to2 && slotProps.data.ireq_status == 'Reject By ICT Personnel' && _this.status != 'RT' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Button, {
            key: 1,
            "class": "p-button-raised p-button-text mr-2 mt-2",
            label: "Submit",
            onClick: function onClick($event) {
              return $options.Submit(slotProps.data.ireqd_id);
            }
          }, null, 8
          /* PROPS */
          , ["onClick"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
        }),
        _: 1
        /* STABLE */

      })];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["value", "loading", "filters"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dialog, {
    visible: $data.dialogAssign,
    "onUpdate:visible": _cache[11] || (_cache[11] = function ($event) {
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
        label: "Simpan",
        onClick: _cache[9] || (_cache[9] = function ($event) {
          return $options.updateAssign();
        }),
        "class": "p-button",
        autofocus: ""
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Cancel",
        onClick: _cache[10] || (_cache[10] = function ($event) {
          return $options.cancelAssign();
        }),
        "class": "p-button-text"
      })];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_no,
        "onUpdate:modelValue": _cache[6] || (_cache[6] = function ($event) {
          return $data.assign.ireq_no = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [_hoisted_18, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.name,
        "onUpdate:modelValue": _cache[7] || (_cache[7] = function ($event) {
          return $data.assign.name = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [_hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
        modelValue: $data.assign.ireq_assigned_to1,
        "onUpdate:modelValue": _cache[8] || (_cache[8] = function ($event) {
          return $data.assign.ireq_assigned_to1 = $event;
        }),
        options: $data.petugas,
        optionValue: "name",
        optionLabel: "name",
        placeholder: "Pilih Petugas (ICT)",
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
          'p-invalid': $data.submitted && !$data.assign.ireq_assigned_to1
        })
      }, null, 8
      /* PROPS */
      , ["modelValue", "options", "class"]), $data.submitted && !$data.assign.ireq_assigned_to1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_23, " Petugas(ICT) Belum Diisi ")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["visible"])])])]);
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



_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _Ict_request_reviewer_detail_vue_vue_type_template_id_40ea90f8__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_detail.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Ict_request_reviewer_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

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