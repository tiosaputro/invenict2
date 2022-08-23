"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Request_Helpdesk_Approval_atasan_Verifikasi_from_email_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      verif: [],
      ireq_id: null,
      todayyy: null,
      loading: true
    };
  },
  mounted: function mounted() {
    this.verifId();
  },
  methods: {
    verifId: function verifId() {
      var _this = this;

      localStorage.setItem("loggedIn", "true");
      this.axios.get('/api/cek-verif-id/' + this.$route.params.code).then(function (res) {
        _this.verif = res.data;

        if (res.data == null) {
          localStorage.clear();

          _this.$router.push({
            name: 'error',
            params: {
              stat: 'notvalid'
            }
          });
        } else {
          _this.ireq_id = res.data.ireq_id;
          _this.todayyy = moment__WEBPACK_IMPORTED_MODULE_0___default()(new Date()).format('YYYY-MM-DD HH:mm:s');

          if (_this.verif.expired_at >= _this.todayyy) {
            _this.loginUser();
          } else if (_this.verif.expired_at <= _this.todayyy) {
            _this.axios.get('/sanctum/csrf-cookie').then(function () {
              _this.axios.post('/api/login-approval', _this.verif).then(function (res) {
                localStorage.clear();
                localStorage.setItem("loggedIn", "true");
                localStorage.setItem("token", res.data.token);
                localStorage.setItem("usr_loc", res.data.usr_loc);
                localStorage.setItem("id", res.data.id);
                localStorage.setItem("usr_name", res.data.usr_name);
                _this.loading = false;

                _this.$router.push({
                  name: 'error',
                  params: {
                    stat: 'expired'
                  }
                });
              });
            });
          }
        }
      });
    },
    loginUser: function loginUser() {
      var _this2 = this;

      this.axios.get('/sanctum/csrf-cookie').then(function () {
        _this2.axios.post('/api/login-approval', _this2.verif).then(function (res) {
          localStorage.clear();
          localStorage.setItem("loggedIn", "true");
          localStorage.setItem("token", res.data.token);
          localStorage.setItem("id", res.data.id);
          localStorage.setItem("usr_name", res.data.usr_name);

          _this2.$router.push({
            name: 'Ict Request Verifikasi From Email',
            params: {
              code: _this2.ireq_id,
              status: _this2.$route.params.status
            }
          });

          _this2.loading = false;
        });
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586 ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************/
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

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Verifying... ");

function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_ConfirmDialog = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ConfirmDialog");

  var _component_DataTable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DataTable");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ConfirmDialog), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DataTable, {
    rows: 25,
    loading: $data.loading
  }, {
    loading: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_4];
    }),
    _: 1
    /* STABLE */

  }, 8
  /* PROPS */
  , ["loading"])])])]);
}

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Verifikasi_from_email_vue_vue_type_template_id_f4cde586__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Verifikasi_from_email.vue?vue&type=template&id=f4cde586 */ "./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586");
/* harmony import */ var _Verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Verifikasi_from_email.vue?vue&type=script&lang=js */ "./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Verifikasi_from_email_vue_vue_type_template_id_f4cde586__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Verifikasi_from_email_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Verifikasi_from_email.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586 ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Verifikasi_from_email_vue_vue_type_template_id_f4cde586__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Verifikasi_from_email_vue_vue_type_template_id_f4cde586__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Verifikasi_from_email.vue?vue&type=template&id=f4cde586 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Request&Helpdesk/Approval_atasan/Verifikasi_from_email.vue?vue&type=template&id=f4cde586");


/***/ })

}]);