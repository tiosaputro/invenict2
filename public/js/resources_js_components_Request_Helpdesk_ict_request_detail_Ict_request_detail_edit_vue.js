"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_ict_request_detail_Ict_request_detail_edit_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      errors: [],
      error: [],
      detail: [],
      preview: '',
      pdf: false,
      image: false,
      foto: '',
      ict: [],
      kodeperi: [],
      kode: '',
      type: [],
      bu: [],
      token: localStorage.getItem('token'),
      checkname: [],
      checkto: [],
      id: localStorage.getItem('id'),
      cekTipeReq: ''
    };
  },
  created: function created() {
    this.cekUser();
  },
  methods: {
    getAttach: function getAttach(event) {
      this.foto = event.target.files[0];
      this.error.foto = '';

      if (this.foto.size > 1024 * 1024) {
        this.error.foto = "File too big (> 1MB)";
      }

      if (this.foto['type'] === 'image/jpeg' || this.foto['type'] === 'image/jpg' || this.foto['type'] === 'image/png') {
        this.pdf = false;
        this.image = true;
        this.preview = URL.createObjectURL(this.foto);
      }

      if (this.foto['type'] === 'application/pdf') {
        this.image = false;
        this.pdf = true;
        this.preview = URL.createObjectURL(this.foto);
      }

      this.createFile(this.foto);
    },
    createFile: function createFile(foto) {
      var reader = new FileReader();
      var vm = this.ict;

      reader.onload = function (e) {
        vm.image = e.target.result;
      };

      reader.readAsDataURL(foto);
    },
    getIreq: function getIreq() {
      this.cekTipeReq = this.ict.ireq_type;

      if (this.cekTipeReq == 'S') {
        this.ict.ireq_qty = null;
        this.ict.invent_code = '';
      }
    },
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

          if (_this.checkname.includes("Request") || _this.checkto.includes("/ict-request")) {
            _this.getIct();
          } else {
            _this.$router.push('/access');
          }
        });
      } else {
        this.$router.push('/login');
      }
    },
    getKode: function getKode() {
      var _this2 = this;

      this.axios.get('/api/getAddDetail', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.type = response.data.ref;
        _this2.kodeperi = response.data.kode;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this2.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Session login expired'
          });

          localStorage.clear();
          localStorage.setItem("Expired", "true");
          setTimeout(function () {
            return _this2.$router.push('/login');
          }, 2000);
        }
      });
    },
    getIct: function getIct() {
      var _this3 = this;

      this.axios.get('/api/edit-ict-detail/' + this.$route.params.ireq + '/' + this.$route.params.code, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this3.ict = response.data;

        if (_this3.ict.ireq_attachment) {
          if (_this3.ict.ireq_attachment.split('.').pop() == 'jpeg' || _this3.ict.ireq_attachment.split('.').pop() == 'png' || _this3.ict.ireq_attachment.split('.').pop() == 'jpg') {
            _this3.image = true;
          } else {
            _this3.pdf = true;
          }
        }

        _this3.cekTipeReq = _this3.ict.ireq_type;

        _this3.getKode();
      });
    },
    UpdateIctDetail: function UpdateIctDetail() {
      var _this4 = this;

      if (!this.error.foto) {
        this.errors = [];
        this.error = [];

        if (this.ict.ireq_type == 'P') {
          if (this.ict.ireq_type != null && this.ict.invent_code != null) {
            this.axios.put('/api/update-ict-detail/' + this.$route.params.ireq + '/' + this.$route.params.code, this.ict, {
              headers: {
                'Authorization': 'Bearer ' + this.token,
                'content-type': 'multipart/form-data'
              }
            }).then(function () {
              _this4.$toast.add({
                severity: "success",
                summary: "Success Message",
                detail: "Success Update"
              });

              setTimeout(function () {
                return _this4.$router.push('/ict-request-detail/' + _this4.$route.params.code);
              }, 1000);
            })["catch"](function (error) {
              _this4.errors = error.response.data.errors;
            });
          } else {
            if (this.ict.ireq_type == null) {
              this.error.ireq_type = "Request Type not filled";
            }

            if (this.ict.invent_code == null) {
              this.error.invent_code = "Peripheral not filled";
            }

            if (this.ict.ireq_remark == null) {
              this.error.ireq_remark = "Remark not filled";
            }
          }
        } else {
          if (this.ict.ireq_type != null) {
            this.axios.put('/api/update-ict-detail/' + this.$route.params.ireq + '/' + this.$route.params.code, this.ict, {
              headers: {
                'Authorization': 'Bearer ' + this.token
              }
            }).then(function () {
              _this4.$toast.add({
                severity: "success",
                summary: "Success Message",
                detail: "Success Update"
              });

              setTimeout(function () {
                return _this4.$router.push('/ict-request-detail/' + _this4.$route.params.code);
              }, 1000);
            })["catch"](function (error) {
              _this4.errors = error.response.data.errors;
            });
          } else {
            if (this.ict.ireq_type == null) {
              this.error.ireq_type = "Request Type not filled";
            }

            if (this.ict.ireq_remark == null) {
              this.error.ireq_remark = "Remark not filled";
            }
          }
        }
      } //size foto

    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");


var _withScopeId = function _withScopeId(n) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.pushScopeId)("data-v-64bca986"), n = n(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.popScopeId)(), n;
};

var _hoisted_1 = {
  "class": "card"
};

var _hoisted_2 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "ICT Request (Detail)", -1
  /* HOISTED */
  );
});

var _hoisted_3 = {
  "class": "row"
};
var _hoisted_4 = {
  "class": "col-sm-6"
};
var _hoisted_5 = {
  "class": "field grid"
};

var _hoisted_6 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "120px"
    }
  }, "No. Request", -1
  /* HOISTED */
  );
});

var _hoisted_7 = {
  "class": "col-fixed w-9rem"
};
var _hoisted_8 = {
  "class": "field grid"
};

var _hoisted_9 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "120px"
    }
  }, "Request Type", -1
  /* HOISTED */
  );
});

var _hoisted_10 = {
  "class": "col-fixed w-9rem"
};
var _hoisted_11 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_12 = {
  key: 1,
  "class": "p-error"
};
var _hoisted_13 = {
  key: 0,
  "class": "field grid"
};

var _hoisted_14 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "120px"
    }
  }, "Peripheral", -1
  /* HOISTED */
  );
});

var _hoisted_15 = {
  "class": "col-fixed w-9rem"
};
var _hoisted_16 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_17 = {
  key: 1,
  "class": "p-error"
};
var _hoisted_18 = {
  key: 1,
  "class": "field grid"
};

var _hoisted_19 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "120px"
    }
  }, "Qty", -1
  /* HOISTED */
  );
});

var _hoisted_20 = {
  "class": "col-fixed w-9rem"
};
var _hoisted_21 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_22 = {
  "class": "field grid"
};

var _hoisted_23 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem",
    style: {
      "width": "120px"
    }
  }, "Remark", -1
  /* HOISTED */
  );
});

var _hoisted_24 = {
  "class": "col-fixed w-9rem"
};
var _hoisted_25 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_26 = {
  key: 2,
  "class": "field grid"
};

var _hoisted_27 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-10 md-4"
  }, "Attachment (JPEG,PNG,JPG,PDF) MAX SIZE 1MB", -1
  /* HOISTED */
  );
});

var _hoisted_28 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_29 = {
  "class": "form-group"
};
var _hoisted_30 = {
  "class": "col-sm-6"
};
var _hoisted_31 = {
  key: 0,
  "class": "field grid"
};

var _hoisted_32 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem"
  }, null, -1
  /* HOISTED */
  );
});

var _hoisted_33 = {
  "class": "col-10 md-6"
};
var _hoisted_34 = {
  "class": "card",
  style: {
    "height": "20 rem"
  }
};
var _hoisted_35 = ["src"];
var _hoisted_36 = ["src"];
var _hoisted_37 = {
  key: 1,
  "class": "field grid"
};

var _hoisted_38 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem"
  }, null, -1
  /* HOISTED */
  );
});

var _hoisted_39 = {
  "class": "col-10 md-6"
};
var _hoisted_40 = {
  "class": "card"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");

  var _component_InputNumber = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputNumber");

  var _component_Textarea = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Textarea");

  var _component_Button = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Button");

  var _component_Pdf = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Pdf");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_2];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
    onSubmit: _cache[8] || (_cache[8] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(function () {
      return $options.UpdateIctDetail && $options.UpdateIctDetail.apply($options, arguments);
    }, ["prevent"]))
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
    type: "text",
    modelValue: $data.ict.ireq_no,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $data.ict.ireq_no = $event;
    }),
    disabled: ""
  }, null, 8
  /* PROPS */
  , ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
    options: $data.type,
    type: "text",
    modelValue: $data.ict.ireq_type,
    "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
      return $data.ict.ireq_type = $event;
    }),
    optionLabel: "name",
    optionValue: "code",
    placeholder: "Select One",
    onChange: _cache[2] || (_cache[2] = function ($event) {
      return $options.getIreq();
    }),
    showClear: true,
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.error.ireq_type
    })
  }, null, 8
  /* PROPS */
  , ["options", "modelValue", "class"]), $data.errors.ireq_type ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.ireq_type[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.error.ireq_type ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_12, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.ireq_type), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), this.cekTipeReq == 'P' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_13, [_hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
    modelValue: $data.ict.invent_code,
    "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
      return $data.ict.invent_code = $event;
    }),
    options: $data.kodeperi,
    optionLabel: "name",
    optionValue: "code",
    placeholder: "Select One ",
    showClear: true,
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.invent_code
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "options", "class"]), $data.errors.invent_code ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.invent_code[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.error.invent_code ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.invent_code), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), this.cekTipeReq == 'P' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_18, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputNumber, {
    modelValue: $data.ict.ireq_qty,
    "onUpdate:modelValue": _cache[4] || (_cache[4] = function ($event) {
      return $data.ict.ireq_qty = $event;
    }),
    placeholder: "Enter Qty",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.ireq_qty
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "class"]), $data.errors.ireq_qty ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.ireq_qty[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [_hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Textarea, {
    autoResize: true,
    rows: "5",
    type: "text",
    modelValue: $data.ict.ireq_remark,
    "onUpdate:modelValue": _cache[5] || (_cache[5] = function ($event) {
      return $data.ict.ireq_remark = $event;
    }),
    placeholder: "Enter Remark",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.ireq_remark
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "class"]), $data.errors.ireq_remark ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.ireq_remark[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), this.cekTipeReq == 'P' || this.cekTipeReq == 'S' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_26, [_hoisted_27, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "file",
    name: "foto",
    accept: "image/jpg,image/png,image/jpeg,application/pdf",
    ref: "fileInput",
    "class": "form-control",
    onChange: _cache[6] || (_cache[6] = function () {
      return $options.getAttach && $options.getAttach.apply($options, arguments);
    })
  }, null, 544
  /* HYDRATE_EVENTS, NEED_PATCH */
  ), $data.error.foto ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.foto), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    "class": "p-button-rounded p-button-primary mr-2",
    icon: "pi pi-check",
    label: "Save",
    type: "submit"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    label: "Cancel",
    "class": "p-button-rounded p-button-secondary mt-2",
    icon: "pi pi-times",
    onClick: _cache[7] || (_cache[7] = function ($event) {
      return _ctx.$router.go(-1);
    })
  })])], 32
  /* HYDRATE_EVENTS */
  )]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_30, [this.image ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_31, [_hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_34, [this.preview ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("img", {
    key: 0,
    src: $data.preview,
    "class": "ict-image"
  }, null, 8
  /* PROPS */
  , _hoisted_35)) : !this.preview ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("img", {
    key: 1,
    src: '/attachment_request/' + this.ict.ireq_attachment,
    "class": "ict-image"
  }, null, 8
  /* PROPS */
  , _hoisted_36)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])) : this.pdf ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_37, [_hoisted_38, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_39, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_40, [this.preview ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Pdf, {
    key: 0,
    src: $data.preview,
    "class": "ict-pdf"
  }, null, 8
  /* PROPS */
  , ["src"])) : !this.preview ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Pdf, {
    key: 1,
    src: '/attachment_request/' + this.ict.ireq_attachment,
    "class": "ict-pdf"
  }, null, 8
  /* PROPS */
  , ["src"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])]);
}

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".ict-image[data-v-64bca986] {\n  height: 330pt;\n  -o-object-fit: contain;\n     object-fit: contain;\n  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);\n}\n.ict-pdf[data-v-64bca986] {\n  height: 100%;\n  width: 100%;\n  -o-object-fit: contain;\n     object-fit: contain;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_style_index_0_id_64bca986_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_style_index_0_id_64bca986_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_style_index_0_id_64bca986_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ict_request_detail_edit_vue_vue_type_template_id_64bca986_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true */ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true");
/* harmony import */ var _Ict_request_detail_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ict_request_detail_edit.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js");
/* harmony import */ var _Ict_request_detail_edit_vue_vue_type_style_index_0_id_64bca986_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss */ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Ict_request_detail_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Ict_request_detail_edit_vue_vue_type_template_id_64bca986_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-64bca986"],['__file',"resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js ***!
  \*************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_detail_edit.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true":
/*!*******************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true ***!
  \*******************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_template_id_64bca986_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_template_id_64bca986_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=template&id=64bca986&scoped=true");


/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss":
/*!**********************************************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss ***!
  \**********************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Ict_request_detail_edit_vue_vue_type_style_index_0_id_64bca986_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/ict_request_detail/Ict_request_detail_edit.vue?vue&type=style&index=0&id=64bca986&scoped=true&lang=scss");


/***/ })

}]);