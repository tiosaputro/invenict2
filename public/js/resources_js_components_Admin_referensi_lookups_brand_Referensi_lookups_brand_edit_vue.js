"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Admin_referensi_lookups_brand_Referensi_lookups_brand_edit_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      errors: [],
      id: localStorage.getItem('id'),
      checkname: [],
      checkto: [],
      ref: [],
      stat: [{
        nama: "Aktif",
        code: "T"
      }, {
        nama: "Tidak Aktif",
        code: "F"
      }],
      token: localStorage.getItem('token'),
      name: ''
    };
  },
  created: function created() {
    this.cekUser();
  },
  methods: {
    cekUser: function cekUser() {
      var _this = this;

      this.axios.get('/api/cek-user/' + this.id, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this.checkto = response.data.map(function (x) {
          return x.to;
        });

        if (_this.checkto.includes("/referensi-brand")) {
          _this.getRef();
        } else {
          _this.$router.push('/access');
        }
      });
    },
    getRef: function getRef() {
      var _this2 = this;

      this.axios.get('/api/edit-ref/' + this.$route.params.code + '/' + this.$route.params.type, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.ref = response.data;
      })["catch"](function (error) {
        if (error.response.status == 401) {
          _this2.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Sesi Login Expired'
          });

          localStorage.clear();
          localStorage.setItem("Expired", "true");
          setTimeout(function () {
            return _this2.$router.push('/login');
          }, 2000);
        }
      });
    },
    UpdateLookup: function UpdateLookup() {
      var _this3 = this;

      this.errors = [];
      this.axios.put('/api/update-ref/' + this.$route.params.code + '/' + this.$route.params.type, this.ref, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this3.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update"
        });

        setTimeout(function () {
          return _this3.$router.push('/referensi-brand');
        }, 1000);
      })["catch"](function (error) {
        _this3.errors = error.response.data.errors;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61 ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "card"
};

var _hoisted_2 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "Referensi - Brand", -1
/* HOISTED */
);

var _hoisted_3 = {
  "class": "card-body"
};
var _hoisted_4 = {
  "class": "field grid"
};

var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem",
  style: {
    "width": "120px"
  }
}, "Kode", -1
/* HOISTED */
);

var _hoisted_6 = {
  "class": "col"
};
var _hoisted_7 = {
  "class": "field grid"
};

var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem",
  style: {
    "width": "120px"
  }
}, "Deskripsi", -1
/* HOISTED */
);

var _hoisted_9 = {
  "class": "col"
};
var _hoisted_10 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_11 = {
  "class": "field grid"
};

var _hoisted_12 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-fixed w-9rem",
  style: {
    "width": "120px"
  }
}, "Status", -1
/* HOISTED */
);

var _hoisted_13 = {
  "class": "field col-12 md:col-4"
};
var _hoisted_14 = {
  key: 0,
  "class": "p-error"
};
var _hoisted_15 = {
  "class": "form-group"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");

  var _component_Button = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Button");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "p-mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_2];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
    onSubmit: _cache[4] || (_cache[4] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(function () {
      return $options.UpdateLookup && $options.UpdateLookup.apply($options, arguments);
    }, ["prevent"]))
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\r\n                <label class=\"col-fixed w-9rem\" style=\"width:120px\">Tipe</label>\r\n                 <div class=\"col\">\r\n                  <InputText\r\n                    type=\"text\"\r\n                    v-model=\"ref.lookup_type\"\r\n                  disabled\r\n                  />\r\n                </div>\r\n              </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [_hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
    type: "text",
    modelValue: $data.ref.lookup_code,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $data.ref.lookup_code = $event;
    }),
    disabled: ""
  }, null, 8
  /* PROPS */
  , ["modelValue"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
    type: "text",
    modelValue: $data.ref.lookup_desc,
    "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
      return $data.ref.lookup_desc = $event;
    }),
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.lookup_desc
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "class"]), $data.errors.lookup_desc ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_10, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.lookup_desc[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [_hoisted_12, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
    modelValue: $data.ref.lookup_status,
    "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
      return $data.ref.lookup_status = $event;
    }),
    options: $data.stat,
    showClear: true,
    optionLabel: "nama",
    optionValue: "code",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.lookup_status
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "options", "class"]), $data.errors.lookup_status ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.lookup_status[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    "class": "p-button-rounded p-button-primary mr-2",
    icon: "pi pi-check",
    label: "Simpan",
    type: "submit"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    label: "Cancel",
    "class": "p-button-rounded p-button-secondary mt-2",
    icon: "pi pi-times",
    onClick: _cache[3] || (_cache[3] = function ($event) {
      return _ctx.$router.push('/referensi-brand');
    })
  })])], 32
  /* HYDRATE_EVENTS */
  )])])]);
}

/***/ }),

/***/ "./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Referensi_lookups_brand_edit_vue_vue_type_template_id_74028b61__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61 */ "./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61");
/* harmony import */ var _Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Referensi_lookups_brand_edit.vue?vue&type=script&lang=js */ "./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js");



_Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _Referensi_lookups_brand_edit_vue_vue_type_template_id_74028b61__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js":
/*!************************************************************************************************************************!*\
  !*** ./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Referensi_lookups_brand_edit_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Referensi_lookups_brand_edit.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61 ***!
  \******************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Referensi_lookups_brand_edit_vue_vue_type_template_id_74028b61__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Referensi_lookups_brand_edit_vue_vue_type_template_id_74028b61__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue?vue&type=template&id=74028b61");


/***/ })

}]);