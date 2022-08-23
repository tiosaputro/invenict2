"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_ict_request_reviewer_Ict_request_reviewer_assign_per-0afdf7"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var primevue_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! primevue/api */ "./node_modules/primevue/api/api.esm.js");
var _methods;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      submitted: false,
      loading: true,
      dialogAssign: false,
      detail: [],
      petugas: [],
      assign: [],
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
      checkto: []
    };
  },
  mounted: function mounted() {
    this.cekUser();
  },
  methods: (_methods = {
    getDetail: function getDetail(ireq_attachment) {
      var page = "http://localhost:8000" + '/attachment_request/' + ireq_attachment;
      var myWindow = window.open(page, "_blank");
      myWindow.focus();
    },
    cekUser: function cekUser() {
      var _this = this;

      this.axios.get('/api/cek-user', {
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

        if (_this.checkname.includes("Reviewer") || _this.checkto.includes("/ict-request-reviewer")) {
          _this.getIctDetail();

          _this.getNoreq();
        } else {
          _this.$router.push('/access');
        }
      });
    },
    cancelAssign: function cancelAssign() {
      this.assign = [];
      this.petugas = [];
      this.dialogAssign = false;
    },
    AssignPerDetail: function AssignPerDetail(ireqd_id) {
      var _this2 = this;

      this.axios.get('/api/detail/' + ireqd_id + '/' + this.$route.params.code, {
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
    }
  }, _defineProperty(_methods, "cancelAssign", function cancelAssign() {
    this.submitted = false;
    this.assign = [];
    this.dialogAssign = false;
  }), _defineProperty(_methods, "updateAssign", function updateAssign() {
    var _this3 = this;

    this.submitted = true;

    if (this.assign.ireq_assigned_to1 != null) {
      this.axios.put('/api/updateAssignPerDetail/' + this.$route.params.code, this.assign, {
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
  }), _defineProperty(_methods, "getIctDetail", function getIctDetail() {
    var _this4 = this;

    this.axios.get('/api/ict-detail/' + this.$route.params.code, {
      headers: {
        'Authorization': 'Bearer ' + this.token
      }
    }).then(function (response) {
      _this4.detail = response.data;
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
    });
  }), _defineProperty(_methods, "getNoreq", function getNoreq() {
    var _this5 = this;

    this.axios.get('/api/get-noreq/' + this.$route.params.code, {
      headers: {
        'Authorization': 'Bearer ' + this.token
      }
    }).then(function (response) {
      _this5.kode = response.data;
    });
  }), _methods)
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");


var _withScopeId = function _withScopeId(n) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.pushScopeId)("data-v-3f73e020"), n = n(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.popScopeId)(), n;
};

var _hoisted_1 = {
  "class": "grid"
};
var _hoisted_2 = {
  "class": "col-12"
};
var _hoisted_3 = {
  "class": "card"
};

var _hoisted_4 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail) ", -1
  /* HOISTED */
  );
});

var _hoisted_5 = {
  style: {
    "width": "200px"
  }
};
var _hoisted_6 = {
  "class": "table-header text-right"
};
var _hoisted_7 = {
  "class": "p-input-icon-left"
};

var _hoisted_8 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
    "class": "pi pi-search"
  }, null, -1
  /* HOISTED */
  );
});

var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Not Found ");

var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Loading data. Please wait. ");

var _hoisted_11 = {
  key: 0
};
var _hoisted_12 = {
  key: 1
};
var _hoisted_13 = ["src", "onClick"];
var _hoisted_14 = {
  key: 2
};
var _hoisted_15 = {
  "class": "p-grid p-dir-col"
};
var _hoisted_16 = {
  "class": "p-col"
};
var _hoisted_17 = {
  "class": "box"
};
var _hoisted_18 = {
  "class": "p-fluid"
};
var _hoisted_19 = {
  "class": "field grid"
};

var _hoisted_20 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "100px"
    }
  }, "No Request", -1
  /* HOISTED */
  );
});

var _hoisted_21 = {
  "class": "col-fixed"
};
var _hoisted_22 = {
  "class": "p-fluid"
};
var _hoisted_23 = {
  "class": "field grid"
};

var _hoisted_24 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "100px"
    }
  }, "Request Type", -1
  /* HOISTED */
  );
});

var _hoisted_25 = {
  "class": "col-fixed"
};
var _hoisted_26 = {
  "class": "p-fluid"
};
var _hoisted_27 = {
  "class": "field grid"
};

var _hoisted_28 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "100px"
    }
  }, "Peripheral", -1
  /* HOISTED */
  );
});

var _hoisted_29 = {
  "class": "col-fixed"
};
var _hoisted_30 = {
  "class": "p-fluid"
};
var _hoisted_31 = {
  "class": "field grid"
};

var _hoisted_32 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "100px"
    }
  }, "Remark", -1
  /* HOISTED */
  );
});

var _hoisted_33 = {
  "class": "col-fixed"
};
var _hoisted_34 = {
  "class": "p-fluid"
};
var _hoisted_35 = {
  "class": "field grid"
};

var _hoisted_36 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "100px"
    }
  }, "Petugas (ICT)", -1
  /* HOISTED */
  );
});

var _hoisted_37 = {
  "class": "col-fixed"
};
var _hoisted_38 = {
  key: 0,
  "class": "p-error"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_ConfirmDialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ConfirmDialog");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Column = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Column");

  var _component_Pdf = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Pdf");

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
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Back",
        "class": "p-button-raised p-button mr-2",
        icon: "pi pi-chevron-left",
        onClick: _cache[1] || (_cache[1] = function ($event) {
          return _ctx.$router.push({
            name: 'Ict Request Reviewer'
          });
        })
      })])])])];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_type",
        header: "Request Type",
        sortable: true,
        style: {
          "min-width": "9rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "name",
        header: "Peripheral",
        sortable: true,
        style: {
          "min-width": "9rem"
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
          "min-width": "9rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        header: "Attachment",
        style: {
          "min-width": "10rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [slotProps.data.ireq_attachment == null ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("p", _hoisted_11)) : slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("p", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
            src: '/attachment_request/' + slotProps.data.ireq_attachment,
            "class": "attachment-image",
            style: {
              "cursor": "pointer"
            },
            onClick: function onClick($event) {
              return $options.getDetail(slotProps.data.ireq_attachment);
            }
          }, null, 8
          /* PROPS */
          , _hoisted_13)])) : slotProps.data.ireq_attachment.split('.').pop() == 'pdf' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("p", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Pdf, {
            src: '/attachment_request/' + slotProps.data.ireq_attachment,
            "class": "attachment-image",
            style: {
              "cursor": "pointer"
            },
            onClick: function onClick($event) {
              return $options.getDetail(slotProps.data.ireq_attachment);
            }
          }, null, 8
          /* PROPS */
          , ["src", "onClick"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
        }),
        _: 1
        /* STABLE */

      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        field: "ireq_assigned_to1",
        header: "Personnel (ICT)",
        sortable: true,
        style: {
          "min-width": "9rem"
        }
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Column, {
        style: {
          "min-width": "9rem"
        }
      }, {
        body: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (slotProps) {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
            "class": "p-button-raised p-button-text p-mr-2 p-mb-2",
            label: "Assign",
            onClick: function onClick($event) {
              return $options.AssignPerDetail(slotProps.data.ireqd_id, slotProps.data.ireq_id);
            }
          }, null, 8
          /* PROPS */
          , ["onClick"])];
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
    "onUpdate:visible": _cache[9] || (_cache[9] = function ($event) {
      return $data.dialogAssign = $event;
    }),
    style: {
      width: '500px'
    },
    header: "Assign Per-Detail",
    modal: true,
    closable: false,
    "class": "p-fluid"
  }, {
    footer: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Simpan",
        onClick: _cache[7] || (_cache[7] = function ($event) {
          return $options.updateAssign();
        }),
        "class": "p-button",
        autofocus: ""
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
        label: "Cancel",
        onClick: _cache[8] || (_cache[8] = function ($event) {
          return $options.cancelAssign();
        }),
        "class": "p-button-text"
      })];
    }),
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [_hoisted_20, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_no,
        "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
          return $data.assign.ireq_no = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [_hoisted_24, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_25, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_type,
        "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
          return $data.assign.ireq_type = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [_hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.name,
        "onUpdate:modelValue": _cache[4] || (_cache[4] = function ($event) {
          return $data.assign.name = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_30, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_31, [_hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
        modelValue: $data.assign.ireq_remark,
        "onUpdate:modelValue": _cache[5] || (_cache[5] = function ($event) {
          return $data.assign.ireq_remark = $event;
        }),
        disabled: ""
      }, null, 8
      /* PROPS */
      , ["modelValue"])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_34, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, [_hoisted_36, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_37, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
        modelValue: $data.assign.ireq_assigned_to1,
        "onUpdate:modelValue": _cache[6] || (_cache[6] = function ($event) {
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
      , ["modelValue", "options", "class"]), $data.submitted && !$data.assign.ireq_assigned_to1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_38, " Petugas(ICT) Harus Diisi ")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["visible"])])])]);
}

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".attachment-image[data-v-3f73e020] {\n  width: 50px;\n  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_style_index_0_id_3f73e020_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_style_index_0_id_3f73e020_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_style_index_0_id_3f73e020_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ict_request_reviewer_assign_per_detail_vue_vue_type_template_id_3f73e020_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true */ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true");
/* harmony import */ var _Ict_request_reviewer_assign_per_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js");
/* harmony import */ var _Ict_request_reviewer_assign_per_detail_vue_vue_type_style_index_0_id_3f73e020_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true */ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Ict_request_reviewer_assign_per_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Ict_request_reviewer_assign_per_detail_vue_vue_type_template_id_3f73e020_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-3f73e020"],['__file',"resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true":
/*!************************************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true ***!
  \************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_template_id_3f73e020_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_template_id_3f73e020_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=template&id=3f73e020&scoped=true");


/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true ***!
  \***************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_reviewer_assign_per_detail_vue_vue_type_style_index_0_id_3f73e020_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue?vue&type=style&index=0&id=3f73e020&lang=scss&scoped=true");


/***/ })

}]);