"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Inventory_master_peripheral_Master_peripheral_create_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      aktif: true,
      displayImage: false,
      preview: '',
      errors: [],
      error: [],
      merks: [],
      kondi: [],
      bisnis: [],
      kategori: [],
      nama: '',
      tgl: '',
      sn: '',
      bu: '',
      merk: '',
      type: '',
      foto: null,
      prevuser: '',
      lastuser: '',
      prevloct: '',
      lastloct: '',
      kondisi: '',
      barcode: '',
      garansi: null,
      mask: {
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname: [],
      checkto: [],
      id: localStorage.getItem('id')
    };
  },
  created: function created() {
    this.cekUser();
  },
  methods: {
    cekUser: function cekUser() {
      var _this = this;

      if (this.id) {
        this.axios.get('api/cek-user/' + this.id, {
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

          if (_this.checkname.includes("Master Peripheral") || _this.checkto.includes("/master-peripheral")) {
            _this.getMerk();
          } else {
            _this.$router.push('/access');
          }
        });
      } else {
        this.$router.push('/login');
      }
    },
    getMerk: function getMerk() {
      var _this2 = this;

      this.axios.get('api/rsrcsupp', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.merks = response.data.merk;
        _this2.bisnis = response.data.bisnis;
        _this2.kondi = response.data.kondisi;
        _this2.kategori = response.data.nama;
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
    // Scan(){
    //   this.aktif = false;
    //   let routeData = this.$router.resolve({name: 'Scan'});
    //   window.open(routeData.href, '_blank');
    //   setTimeout( () => this.getBarcode(),2000);
    // },
    // getBarcode(){
    //   this.barcode = localStorage.getItem('barcode');
    //   if(!this.barcode){
    //     setTimeout( () => this.getBarcode(),3000);
    //   }
    // },
    // hapus(){
    //   localStorage.removeItem('barcode');
    //   this.barcode = null;
    //   this.aktif = true;
    // },
    // fileImage(event) {
    //   this.foto = event.target.files[0];
    //   this.displayImage = true;
    //   this.preview = URL.createObjectURL(event.target.files[0]);
    //   this.createImage(this.foto);
    //   },
    // createImage(invent_photo) {
    //   var image = new Image();
    //   var reader = new FileReader();
    //   var vm = this;
    //   reader.onload = function (e) {
    //     vm.image = e.target.result;
    //   };
    //   reader.readAsDataURL(invent_photo);
    // },
    CreateMaster: function CreateMaster() {
      var _this3 = this;

      this.errors = [];
      this.error = [];

      if ( // this.bu != null &&
      this.merk != null && this.nama != null // this.foto != null
      ) {
        // if(this.image){
        // const data = new FormData();
        // data.append("nama", this.nama);
        // data.append("tgl", this.tgl);
        // data.append("sn", this.sn);
        // data.append("bu", this.bu);
        // data.append("merk", this.merk);
        // data.append("type", this.type);
        // data.append("lastuser", this.lastuser);
        // data.append("prevuser", this.prevuser);
        // data.append("prevloct", this.prevloct);
        // data.append("lastloct", this.lastloct);
        // data.append("foto", this.image);
        // data.append("kondisi", this.kondisi);
        // data.append("barcode", this.barcode);
        // data.append("garansi", this.garansi);
        // this.axios.post('api/add-mas',data,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        //   setTimeout( () => this.$router.push('/master-peripheral'),1000);
        //   this.$toast.add({
        //     severity: "success",
        //     summary: "Success Message",
        //     detail: "Success Create",
        //   });
        // }).catch(error=>{
        //     this.errors = error.response.data.errors;
        // });
        // }
        // else{
        var data = new FormData();
        data.append("nama", this.nama); // data.append("code", this.code);
        // data.append("tgl", this.tgl);
        // data.append("sn", this.sn);
        // data.append("bu", this.bu);

        data.append("merk", this.merk);
        data.append("type", this.type); // data.append("lastuser", this.lastuser);
        // data.append("prevuser", this.prevuser);
        // data.append("prevloct", this.prevloct);
        // data.append("lastloct", this.lastloct);
        // data.append("foto", this.image);
        // data.append("kondisi", this.kondisi);
        // data.append("barcode", this.barcode);
        // data.append("garansi", this.garansi);

        this.axios.post('api/add-mas', data, {
          headers: {
            'Authorization': 'Bearer ' + this.token
          }
        }).then(function () {
          setTimeout(function () {
            return _this3.$router.push('/master-peripheral');
          }, 1000);

          _this3.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create"
          });
        })["catch"](function (error) {
          _this3.errors = error.response.data.errors;
        }); // }
      } else {
        // if(this.bu == null){
        //   this.error.bu = "Bisnis Unit Belum Diisi"
        // }
        if (this.merk == null) {
          this.error.merk = "Merk Belum Diisi";
        }

        if (this.nama == null) {
          this.error.nama = "Nama Peripheral Belum Diisi";
        } // if(this.foto == null){
        //   this.error.foto = "Foto Belum Diisi"
        // }

      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");


var _withScopeId = function _withScopeId(n) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.pushScopeId)("data-v-dd716f14"), n = n(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.popScopeId)(), n;
};

var _hoisted_1 = {
  "class": "card"
};

var _hoisted_2 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "Master Peripheral", -1
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
    "class": "col-fixed w-9rem"
  }, "Kode", -1
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
    "class": "col-fixed w-9rem"
  }, "Nama Peripheral", -1
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
  "class": "field grid"
};

var _hoisted_14 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem"
  }, "Merk", -1
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
  "class": "field grid"
};

var _hoisted_19 = /*#__PURE__*/_withScopeId(function () {
  return /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "col-fixed w-9rem"
  }, "Tipe", -1
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
  key: 1,
  "class": "p-error"
};
var _hoisted_23 = {
  "class": "form-group"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");

  var _component_Toolbar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toolbar");

  var _component_InputText = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InputText");

  var _component_Dropdown = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Dropdown");

  var _component_Button = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Button");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toast), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Toolbar, {
    "class": "mb-4"
  }, {
    start: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_2];
    }),
    _: 1
    /* STABLE */

  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
    onSubmit: _cache[4] || (_cache[4] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(function () {
      return $options.CreateMaster && $options.CreateMaster.apply($options, arguments);
    }, ["prevent"]))
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
    type: "text",
    disabled: ""
  })])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
    modelValue: $data.nama,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $data.nama = $event;
    }),
    options: $data.kategori,
    optionLabel: "name",
    optionValue: "name",
    showClear: true,
    filter: true,
    placeholder: "Pilih Peripheral",
    autofocus: "",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.nama
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "options", "class"]), $data.errors.nama ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.nama[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.error.nama ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_12, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.nama), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [_hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Dropdown, {
    modelValue: $data.merk,
    "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
      return $data.merk = $event;
    }),
    options: $data.merks,
    optionLabel: "name",
    optionValue: "code",
    showClear: true,
    filter: true,
    placeholder: "Pilih Merk",
    autofocus: "",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.merk
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "options", "class"]), $data.errors.merk ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.merk[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.error.merk ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.merk), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InputText, {
    type: "text",
    modelValue: $data.type,
    "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
      return $data.type = $event;
    }),
    placeholder: "Masukan Tipe",
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'p-invalid': $data.errors.type
    })
  }, null, 8
  /* PROPS */
  , ["modelValue", "class"]), $data.errors.type ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors.type[0]), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.error.type ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("small", _hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.error.type), 1
  /* TEXT */
  )) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                  <label style=\"width:155px\">S/N</label>\n                    <div class=\"col-3 md-6\">\n                    <InputText\n                      type =\"text\"\n                      v-model=\"sn\"\n                      placeholder=\"Masukan S/N\"\n                      :class=\"{ 'p-invalid': errors.sn }\"\n                    />\n                      <small v-if=\"errors.sn\" class=\"p-error\">\n                          {{ errors.sn[0] }}\n                      </small>\n                 </div>\n              </div>\n              <div class=\"field grid\">\n                <label style=\"width:155px\">Tgl. Perolehan</label>\n                  <div class=\"col-12 md:col-6\">\n                    <DatePicker v-model=\"tgl\" :masks=\"mask\" >\n                      <template v-slot=\"{ inputValue, togglePopover }\">\n                        <div class=\"flex items-center\">\n                          <input\n                            class=\"bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none\"\n                            :value=\"inputValue\"\n                            @click=\"togglePopover\"\n                            placeholder=\"Pilih Tanggal\"\n                            readonly\n                          />\n                          <Button icon=\"pi pi-calendar\" v-if=\"!tgl\" @click=\"togglePopover()\"/>\n                          <Button icon=\"pi pi-trash\" class=\"p-button-danger\" v-if=\"tgl\" @click=\"tgl = null\" />\n                        </div>\n                       </template>\n                      </DatePicker>\n                      <small v-if=\"errors.tgl\" class=\"p-error\">\n                          {{ errors.tgl[0] }}\n                      </small>\n                  </div>\n              </div>\n               <div class=\"field grid\">\n                  <label style=\"width:155px\">Lama Garansi</label>\n                    <div class=\"col-12 md:col-6\">\n                      <div class=\"p-inputgroup\">\n                      <InputNumber\n                          v-model=\"garansi\"\n                          placeholder=\"Masukan Garansi\"\n                        />\n                        <span class=\"p-inputgroup-addon\"> Tahun </span> \n                    </div>\n                </div>\n              </div>\n              <div class=\"field grid\">\n                <label style=\"width:155px\">Kondisi</label>\n                 <div class=\"col-4\">\n                  <Dropdown \n                    v-model=\"kondisi\"\n                    :options=\"kondi\"\n                    :showClear=\"true\"\n                    :filter=\"true\" \n                    optionLabel=\"name\"\n                    optionValue=\"code\"\n                    placeholder=\"Pilih Kondisi\"\n                    :class=\"{ 'p-invalid': errors.kondisi }\"\n                  />\n                      <small v-if=\"errors.kondisi\" class=\"p-error\">\n                          {{ errors.kondisi[0] }}\n                      </small>\n               </div>\n              </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                <label style=\"width:155px\">QR-Code</label>\n                <div class=\"col-12 md:col-6\">\n                <div class=\"p-inputgroup\">\n                  <InputText v-model=\"barcode\" readonly  v-if=\"barcode\"/>\n                  <img :src=\"'assets/loading2.gif'\" height=\"50\" v-if=\"!aktif && !barcode\">\n                  <Button icon=\"pi pi-trash\" class=\"p-button-danger\" v-if=\"barcode\" @click=\"hapus()\" @v-tooltip=\"'Click to delete'\"/>\n                  <Button icon=\"bi bi-qr-code-scan\" v-if=\"aktif\" class=\"p-button p-button-info\" @click=\"Scan()\" v-tooltip=\"'Click to scan'\" />\n                  </div>\n                      <small v-if=\"errors.barcode\" class=\"p-error\">\n                          {{ errors.barcode[0] }}\n                      </small>\n                </div>\n              </div>  "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                <label for=\"notlp2\" style=\"width:155px\">Bisnis Unit</label>\n                  <div class=\"col-4\">\n                    <Dropdown \n                    v-model=\"bu\"\n                    :options=\"bisnis\"\n                    optionLabel=\"name\"\n                    optionValue=\"code\"\n                    :showClear=\"true\"\n                    :filter=\"true\"\n                    placeholder=\"Pilih Bisnis Unit\"\n                    :class=\"{ 'p-invalid': errors.bu }\"\n                  />\n                      <small v-if=\"errors.bu\" class=\"p-error\">\n                          {{ errors.bu[0] }}\n                      </small>\n                      <small v-if=\"error.bu\" class=\"p-error\">\n                          {{ error.bu }}\n                      </small>\n                </div>\n              </div>\n                <div class=\"field grid\">\n                  <label style=\"width:155px\">Lokasi Terakhir</label>\n                    <div class=\"col-6\">\n                      <InputText\n                        type=\"text\"\n                        v-model=\"lastloct\"\n                        :class=\"{ 'p-invalid': errors.lastloct }\"\n                        placeholder=\"Masukan Lokasi terakhir\"\n                        disabled\n                      /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <small v-if=\"errors.lastloct\" class=\"p-error\">\n                          {{ errors.lastloct[0] }}\n                      </small> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div>\n                  </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                    <label style=\"width:155px\">Pengguna Terakhir</label>\n                      <div class=\"col-6\">\n                        <InputText\n                          type=\"text\"\n                          v-model=\"lastuser\"\n                          :class=\"{ 'p-invalid': errors.lastuser }\"\n                          placeholder=\"Masukan Pengguna Terakhir\"\n                          disabled\n                        /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <small v-if=\"errors.lastuser\" class=\"p-error\">\n                            {{ errors.lastuser[0] }}\n                        </small> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div>\n                    </div>  "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    "class": "p-button-rounded p-button-primary mr-2",
    icon: "pi pi-check",
    label: "Simpan",
    type: "submit"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Button, {
    label: "Cancel",
    "class": "p-button-rounded p-button-secondary mr-2",
    icon: "pi pi-times",
    onClick: _cache[3] || (_cache[3] = function ($event) {
      return _ctx.$router.push('/master-peripheral');
    })
  })])], 32
  /* HYDRATE_EVENTS */
  )]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"col-sm-6\">\n            <div class=\"field grid\">\n              <label style=\"width:155px\">Nama Peripheral</label>\n                <div class=\"col-4\">\n                  <Dropdown\n                    v-model=\"nama\"\n                    :options=\"kategori\"\n                    optionLabel=\"name\"\n                    optionValue=\"code\"\n                    :showClear=\"true\"\n                    :filter=\"true\"\n                    placeholder=\"Pilih Peripheral\"\n                    autofocus\n                    :class=\"{ 'p-invalid': errors.nama }\"\n                  />\n                  <small v-if=\"errors.nama\" class=\"p-error\">\n                    {{ errors.nama[0] }}\n                  </small>\n                    <small v-if=\"error.nama\" class=\"p-error\">\n                      {{ error.nama }}\n                    </small>\n                </div>\n            </div>  "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n              <label style=\"width:155px\"></label>\n                <div class=\"col-10 md-6\">\n                  <div class=\"card\" style=\"height: 20 rem;\">\n                    <img :src=\"preview\" class=\"master-image\"/>\n                  </div>\n                </div>\n            </div>\n            <div class=\"field grid\">\n              <label style=\"width:155px\"></label>\n                <div class=\"col-10 md-6\">\n                  <input type=\"file\" :class=\"{ 'p-invalid': error.foto }\" name=\"foto\" ref=\"fileInput\" class=\"form-control\" @change=\"fileImage\" /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <small class=\"p-error\" v-if=\"error.foto\">\n                      {{ error.foto }}\n                    </small> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div>\n            </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                  <label style=\"width:155px\">Lokasi Sebelumnya</label>\n                    <div class=\"col-6\">\n                    <InputText\n                        type=\"text\"\n                        v-model=\"prevloct\"\n                        :class=\"{ 'p-invalid': errors.prevloct }\"\n                        placeholder=\"Masukan Lokasi sebelumnya\"\n                        disabled\n                    /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <small class=\"p-error\" v-if=\"errors.prevloct\">\n                        {{ errors.prevloct[0] }}\n                      </small> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div>\n                 </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"field grid\">\n                  <label style=\"width:155px\">Penguna Sebelumnya</label>\n                    <div class=\"col-6\">\n                       <InputText\n                        type=\"text\"\n                        v-model=\"prevuser\"\n                        :class=\"{ 'p-invalid': errors.prevuser  }\"\n                        placeholder=\"Masukan Pengguna sebelumnya\"\n                        disabled\n                    /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <small class=\"p-error\" v-if=\"errors.prevuser\">\n                        {{ errors.prevuser[0] }}\n                      </small> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div>\n                 </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" </div> ")])])]);
}

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".master-image[data-v-dd716f14] {\n  height: 200pt;\n  -o-object-fit: contain;\n     object-fit: contain;\n  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_style_index_0_id_dd716f14_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_style_index_0_id_dd716f14_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_style_index_0_id_dd716f14_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Master_peripheral_create_vue_vue_type_template_id_dd716f14_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true */ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true");
/* harmony import */ var _Master_peripheral_create_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Master_peripheral_create.vue?vue&type=script&lang=js */ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js");
/* harmony import */ var _Master_peripheral_create_vue_vue_type_style_index_0_id_dd716f14_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss */ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss");
/* harmony import */ var C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,C_laragon_www_invenict2_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Master_peripheral_create_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Master_peripheral_create_vue_vue_type_template_id_dd716f14_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-dd716f14"],['__file',"resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Master_peripheral_create.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true ***!
  \************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_template_id_dd716f14_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_template_id_dd716f14_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=template&id=dd716f14&scoped=true");


/***/ }),

/***/ "./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss":
/*!***************************************************************************************************************************************************!*\
  !*** ./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss ***!
  \***************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_use_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Master_peripheral_create_vue_vue_type_style_index_0_id_dd716f14_scoped_true_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!../../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12.use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12.use[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Inventory/master_peripheral/Master_peripheral_create.vue?vue&type=style&index=0&id=dd716f14&scoped=true&lang=scss");


/***/ })

}]);