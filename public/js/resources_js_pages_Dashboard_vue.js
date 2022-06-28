"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_pages_Dashboard_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      user: [],
      role_name: [],
      count: [],
      count1: [],
      count2: [],
      count3: [],
      count4: [],
      count5: [],
      usr_name: localStorage.getItem('usr_name'),
      token: localStorage.getItem('token'),
      id: localStorage.getItem('id')
    };
  },
  mounted: function mounted() {
    this.CekUser();
  },
  methods: {
    CekUser: function CekUser() {
      var _this = this;

      this.role_name = localStorage.getItem('rolename');

      if (this.role_name != null) {
        if (this.role_name.includes('Requestor Divisi')) {
          this.getData();
        } else if (this.role_name.includes('Admin')) {
          this.getData5();
        } else if (this.role_name.includes('Atasan Requestor Divisi')) {
          this.getData1();
        } else if (this.role_name.includes('Supervisor')) {
          this.getData2();
        } else if (this.role_name.includes('Personel ICT')) {
          this.getUser();
        } else if (this.role_name.includes('Manager')) {
          this.getData4();
        }
      } else {
        if (this.id) {
          this.axios.get('api/cek-role/' + this.id, {
            headers: {
              'Authorization': 'Bearer ' + this.token
            }
          }).then(function (response) {
            _this.role_name = response.data.map(function (x) {
              return x.rol_name;
            });
            var role = response.data.map(function (x) {
              return x.rol_name;
            });
            localStorage.setItem("rolename", role);

            if (_this.role_name.includes('Requestor Divisi')) {
              _this.getData();
            } else if (_this.role_name.includes('Admin')) {
              _this.getData5();
            } else if (_this.role_name.includes('Atasan Requestor Divisi')) {
              _this.getData1();
            } else if (_this.role_name.includes('Supervisor')) {
              _this.getData2();
            } else if (_this.role_name.includes('Personel ICT')) {
              _this.getUser();
            } else if (_this.role_name.includes('Manager')) {
              _this.getData4();
            }
          });
        } else {
          this.$router.push('/login');
        }
      }
    },
    getData: function getData() {
      var _this2 = this;

      this.axios.get('api/getCountUser/' + this.usr_name, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this2.count = response.data;
      });
    },
    blmDiverifikasiAdmin: function blmDiverifikasiAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 23);
    },
    sdhDiverifikasiAdmin: function sdhDiverifikasiAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 24);
    },
    diRejectAdmin: function diRejectAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 25);
    },
    sdgDikerjakanAdmin: function sdgDikerjakanAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 26);
    },
    sdhDikerjakanAdmin: function sdhDikerjakanAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 27);
    },
    sdhSelesaiAdmin: function sdhSelesaiAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 28);
    },
    totalKeseluruhanAdmin: function totalKeseluruhanAdmin() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 29);
    },
    sdgDireview: function sdgDireview() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 40);
    },
    blmDiverifikasi: function blmDiverifikasi() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 1);
    },
    sdhDiverifikasi: function sdhDiverifikasi() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 2);
    },
    diReject: function diReject() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 3);
    },
    sdgDikerjakan: function sdgDikerjakan() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 4);
    },
    sdhDikerjakan: function sdhDikerjakan() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 5);
    },
    sdhSelesai: function sdhSelesai() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 6);
    },
    totalKeseluruhan: function totalKeseluruhan() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 22);
    },
    //dashboard approval user
    getData1: function getData1() {
      var _this3 = this;

      this.axios.get('api/getCountDivisi1/' + this.usr_name, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this3.count1 = response.data;
      });
    },
    sdgDireview1: function sdgDireview1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 41);
    },
    blmDiverifikasi1: function blmDiverifikasi1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 7);
    },
    sdhDiverifikasi1: function sdhDiverifikasi1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 8);
    },
    diReject1: function diReject1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 9);
    },
    sdgDikerjakan1: function sdgDikerjakan1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 10);
    },
    sdhDikerjakan1: function sdhDikerjakan1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 11);
    },
    sdhSelesai1: function sdhSelesai1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 12);
    },
    totalRequest1: function totalRequest1() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 38);
    },
    getData2: function getData2() {
      var _this4 = this;

      this.axios.get('api/getCountDivisi2', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this4.count2 = response.data;
      });
    },
    atasanDivisi2: function atasanDivisi2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 30);
    },
    IctManager2: function IctManager2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 31);
    },
    direject2: function direject2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 32);
    },
    blmDiassign2: function blmDiassign2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 13);
    },
    penugasanRequest2: function penugasanRequest2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 43);
    },
    sdgDikerjakan2: function sdgDikerjakan2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 14);
    },
    sdhDikerjakan2: function sdhDikerjakan2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 15);
    },
    sdhSelesai2: function sdhSelesai2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 16);
    },
    totalRequest2: function totalRequest2() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 37);
    },
    getUser: function getUser() {
      var _this5 = this;

      this.axios.get('api/user', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this5.user = response.data;

        _this5.getData3();
      });
    },
    getData3: function getData3() {
      var _this6 = this;

      this.axios.get('api/getCountDivisi3/' + this.user.usr_fullname, {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this6.count3 = response.data;
      });
    },
    blmSelesai3: function blmSelesai3() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 17);
    },
    sdHDikerjakan3: function sdHDikerjakan3() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 18);
    },
    sdhSelesai3: function sdhSelesai3() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 19);
    },
    getData4: function getData4() {
      var _this7 = this;

      this.axios.get('api/getCountDivisi4', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this7.count4 = response.data;
      });
    },
    sdgDireview4: function sdgDireview4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 42);
    },
    blmDiverifikasi4: function blmDiverifikasi4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 33);
    },
    sdhDiverifikasi4: function sdhDiverifikasi4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 34);
    },
    direject4: function direject4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 35);
    },
    penugasanRequest4: function penugasanRequest4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 44);
    },
    sdgdikerjakan4: function sdgdikerjakan4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 36);
    },
    sdHDikerjakan4: function sdHDikerjakan4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 20);
    },
    sdhSelesai4: function sdhSelesai4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 21);
    },
    totalRequest4: function totalRequest4() {
      this.$router.push('/ict-request-desc');
      localStorage.setItem('active', 39);
    },
    getData5: function getData5() {
      var _this8 = this;

      this.axios.get('api/getCountAdmin', {
        headers: {
          'Authorization': 'Bearer ' + this.token
        }
      }).then(function (response) {
        _this8.count5 = response.data;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a ***!
  \**************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  key: 0,
  "class": "grid"
};
var _hoisted_2 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_3 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Waiting for verification ", -1
/* HOISTED */
);

var _hoisted_5 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_7 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_8 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Already Verified", -1
/* HOISTED */
);

var _hoisted_10 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_12 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_13 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_14 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Rejected", -1
/* HOISTED */
);

var _hoisted_15 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-times text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_17 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_18 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_20 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-spin pi-spinner",
  style: {
    "fontSize": "2rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_22 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_23 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_24 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_25 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_26 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_27 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_28 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_29 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_30 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_31 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_32 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_33 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_34 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Overall Request", -1
/* HOISTED */
);

var _hoisted_35 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_36 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-book text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_37 = {
  key: 1,
  "class": "grid"
};
var _hoisted_38 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_39 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_40 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Under review ", -1
/* HOISTED */
);

var _hoisted_41 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_42 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-journal-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_43 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_44 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_45 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Waiting for verification ", -1
/* HOISTED */
);

var _hoisted_46 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_47 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_48 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_49 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_50 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Already verified", -1
/* HOISTED */
);

var _hoisted_51 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_52 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_53 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_54 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_55 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Rejected", -1
/* HOISTED */
);

var _hoisted_56 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_57 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-times text-xl",
  style: {
    "fontSize": "4rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_58 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_59 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_60 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_61 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_62 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-spin pi-spinner",
  style: {
    "fontSize": "2rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_63 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_64 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_65 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_66 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_67 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_68 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_69 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_70 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_71 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_72 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_73 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_74 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_75 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Overall Request", -1
/* HOISTED */
);

var _hoisted_76 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_77 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-journal-bookmark-fill text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_78 = {
  key: 2,
  "class": "grid"
};
var _hoisted_79 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_80 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_81 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Under Review ", -1
/* HOISTED */
);

var _hoisted_82 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_83 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-journal-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_84 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_85 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_86 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Waiting for verification ", -1
/* HOISTED */
);

var _hoisted_87 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_88 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_89 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_90 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_91 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Already Verified", -1
/* HOISTED */
);

var _hoisted_92 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_93 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_94 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_95 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_96 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Rejected", -1
/* HOISTED */
);

var _hoisted_97 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_98 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-times text-xl",
  style: {
    "fontSize": "4rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_99 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_100 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_101 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_102 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_103 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-spin pi-spinner",
  style: {
    "fontSize": "2rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_104 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_105 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_106 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_107 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_108 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_109 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_110 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_111 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_112 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_113 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_114 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_115 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_116 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Overall Request", -1
/* HOISTED */
);

var _hoisted_117 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_118 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-book text-xl",
  style: {
    "fontSize": "4rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_119 = {
  key: 3,
  "class": "grid"
};
var _hoisted_120 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_121 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_122 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Waiting for verification", -1
/* HOISTED */
);

var _hoisted_123 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_124 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_125 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_126 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_127 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Atasan Divisi", -1
/* HOISTED */
);

var _hoisted_128 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_129 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-clipboard-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_130 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_131 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_132 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "ICT Manager", -1
/* HOISTED */
);

var _hoisted_133 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_134 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-journal-bookmark-fill text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_135 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_136 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_137 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Rejected", -1
/* HOISTED */
);

var _hoisted_138 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_139 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-times text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_140 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_141 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_142 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Request Assignment", -1
/* HOISTED */
);

var _hoisted_143 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_144 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-hourglass-bottom text-xl",
  style: {
    "color": "gray"
  }
})], -1
/* HOISTED */
);

var _hoisted_145 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_146 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_147 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_148 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_149 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_150 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_151 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_152 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_153 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_154 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "3.5rem",
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_155 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_156 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_157 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_158 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_159 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "fontSize": "3.5rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_160 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_161 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_162 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Overall Request", -1
/* HOISTED */
);

var _hoisted_163 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_164 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-book text-xl",
  style: {
    "fontSize": "3.5rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_165 = {
  key: 4,
  "class": "grid"
};
var _hoisted_166 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_167 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_168 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_169 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_170 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-hourglass-split text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_171 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_172 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_173 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_174 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_175 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_176 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_177 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_178 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_179 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_180 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_181 = {
  key: 5,
  "class": "grid"
};
var _hoisted_182 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_183 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_184 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Under Review ", -1
/* HOISTED */
);

var _hoisted_185 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_186 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-journal-check text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_187 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_188 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_189 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Waiting for verification", -1
/* HOISTED */
);

var _hoisted_190 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_191 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_192 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_193 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_194 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Already Verified", -1
/* HOISTED */
);

var _hoisted_195 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_196 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-check text-xl text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_197 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_198 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_199 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Rejected", -1
/* HOISTED */
);

var _hoisted_200 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_201 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-times text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_202 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_203 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_204 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Request Assignment", -1
/* HOISTED */
);

var _hoisted_205 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_206 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-hourglass-bottom text-xl",
  style: {
    "color": "gray"
  }
})], -1
/* HOISTED */
);

var _hoisted_207 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_208 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_209 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "In Progress", -1
/* HOISTED */
);

var _hoisted_210 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_211 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-spin pi-spinner",
  style: {
    "fontSize": "2rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_212 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_213 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_214 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Done", -1
/* HOISTED */
);

var _hoisted_215 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_216 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "color": "red"
  }
})], -1
/* HOISTED */
);

var _hoisted_217 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_218 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_219 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Close", -1
/* HOISTED */
);

var _hoisted_220 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_221 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "bi bi-check2-all text-xl",
  style: {
    "color": "green"
  }
})], -1
/* HOISTED */
);

var _hoisted_222 = {
  "class": "col-12 lg:col-6 xl:col-3"
};
var _hoisted_223 = {
  "class": "flex justify-content-between mb-3"
};

var _hoisted_224 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  "class": "block text-500 font-medium mb-3"
}, "Overall Request", -1
/* HOISTED */
);

var _hoisted_225 = {
  "class": "text-900 font-medium text-xl"
};

var _hoisted_226 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "flex align-items-center justify-content-center bg-gray-100 border-round",
  style: {
    "width": "2.5rem",
    "height": "2.5rem"
  }
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "pi pi-book text-xl",
  style: {
    "fontSize": "3.5rem",
    "color": "green"
  }
})], -1
/* HOISTED */
);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  return this.role_name.includes('Admin') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[0] || (_cache[0] = function ($event) {
      return $options.blmDiverifikasiAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.belumdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_6])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[1] || (_cache[1] = function ($event) {
      return $options.sdhDiverifikasiAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.sudahdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_11])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[2] || (_cache[2] = function ($event) {
      return $options.diRejectAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.direject), 1
  /* TEXT */
  )]), _hoisted_16])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[3] || (_cache[3] = function ($event) {
      return $options.sdgDikerjakanAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.sedangdikerjakan), 1
  /* TEXT */
  )]), _hoisted_21])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[4] || (_cache[4] = function ($event) {
      return $options.sdhDikerjakanAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_24, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.sudahdikerjakan), 1
  /* TEXT */
  )]), _hoisted_26])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[5] || (_cache[5] = function ($event) {
      return $options.sdhSelesaiAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_28, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_29, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_30, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.sudahselesai), 1
  /* TEXT */
  )]), _hoisted_31])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[6] || (_cache[6] = function ($event) {
      return $options.totalKeseluruhanAdmin();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count5.countrequest), 1
  /* TEXT */
  )]), _hoisted_36])])])])) : this.role_name.includes('Requestor Divisi') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_37, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_38, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[7] || (_cache[7] = function ($event) {
      return $options.sdgDireview();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_39, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_40, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_41, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.sedangdireview), 1
  /* TEXT */
  )]), _hoisted_42])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_43, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[8] || (_cache[8] = function ($event) {
      return $options.blmDiverifikasi();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_44, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_45, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_46, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.belumdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_47])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_48, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[9] || (_cache[9] = function ($event) {
      return $options.sdhDiverifikasi();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_49, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_50, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_51, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.sudahdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_52])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_53, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[10] || (_cache[10] = function ($event) {
      return $options.diReject();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_54, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_55, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_56, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.direject), 1
  /* TEXT */
  )]), _hoisted_57])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_58, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[11] || (_cache[11] = function ($event) {
      return $options.sdgDikerjakan();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_59, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_60, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_61, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.sedangdikerjakan), 1
  /* TEXT */
  )]), _hoisted_62])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_63, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[12] || (_cache[12] = function ($event) {
      return $options.sdhDikerjakan();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_64, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_65, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_66, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.sudahdikerjakan), 1
  /* TEXT */
  )]), _hoisted_67])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_68, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[13] || (_cache[13] = function ($event) {
      return $options.sdhSelesai();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_69, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_70, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_71, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.sudahselesai), 1
  /* TEXT */
  )]), _hoisted_72])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_73, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[14] || (_cache[14] = function ($event) {
      return $options.totalKeseluruhan();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_74, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_75, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_76, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count.countrequest), 1
  /* TEXT */
  )]), _hoisted_77])])])])) : this.role_name.includes('Atasan Requestor Divisi') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_78, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_79, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[15] || (_cache[15] = function ($event) {
      return $options.sdgDireview1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_80, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_81, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_82, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.sedangdireview), 1
  /* TEXT */
  )]), _hoisted_83])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_84, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[16] || (_cache[16] = function ($event) {
      return $options.blmDiverifikasi1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_85, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_86, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_87, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.belumdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_88])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_89, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[17] || (_cache[17] = function ($event) {
      return $options.sdhDiverifikasi1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_90, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_91, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_92, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.sudahdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_93])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_94, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[18] || (_cache[18] = function ($event) {
      return $options.diReject1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_95, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_96, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_97, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.direject), 1
  /* TEXT */
  )]), _hoisted_98])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_99, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[19] || (_cache[19] = function ($event) {
      return $options.sdgDikerjakan1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_100, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_101, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_102, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.sedangdikerjakan), 1
  /* TEXT */
  )]), _hoisted_103])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_104, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[20] || (_cache[20] = function ($event) {
      return $options.sdhDikerjakan1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_105, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_106, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_107, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.sudahdikerjakan), 1
  /* TEXT */
  )]), _hoisted_108])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_109, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[21] || (_cache[21] = function ($event) {
      return $options.sdhSelesai1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_110, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_111, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_112, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.sudahselesai), 1
  /* TEXT */
  )]), _hoisted_113])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_114, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[22] || (_cache[22] = function ($event) {
      return $options.totalRequest1();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_115, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_116, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_117, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count1.total), 1
  /* TEXT */
  )]), _hoisted_118])])])])) : this.role_name.includes('Supervisor') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_119, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_120, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[23] || (_cache[23] = function ($event) {
      return $options.blmDiassign2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_121, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_122, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_123, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.blmDiverifikasi), 1
  /* TEXT */
  )]), _hoisted_124])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_125, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[24] || (_cache[24] = function ($event) {
      return $options.atasanDivisi2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_126, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_127, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_128, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.atasandivisi), 1
  /* TEXT */
  )]), _hoisted_129])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_130, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[25] || (_cache[25] = function ($event) {
      return $options.IctManager2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_131, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_132, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_133, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.manager), 1
  /* TEXT */
  )]), _hoisted_134])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_135, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[26] || (_cache[26] = function ($event) {
      return $options.direject2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_136, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_137, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_138, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.reject), 1
  /* TEXT */
  )]), _hoisted_139])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_140, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[27] || (_cache[27] = function ($event) {
      return $options.penugasanRequest2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_141, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_142, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_143, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.penugasanRequest), 1
  /* TEXT */
  )]), _hoisted_144])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_145, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[28] || (_cache[28] = function ($event) {
      return $options.sdgDikerjakan2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_146, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_147, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_148, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.sdgdikerjakan), 1
  /* TEXT */
  )]), _hoisted_149])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_150, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[29] || (_cache[29] = function ($event) {
      return $options.sdhDikerjakan2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_151, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_152, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_153, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.sdhdikerjakan), 1
  /* TEXT */
  )]), _hoisted_154])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_155, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[30] || (_cache[30] = function ($event) {
      return $options.sdhSelesai2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_156, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_157, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_158, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.sdhselesai), 1
  /* TEXT */
  )]), _hoisted_159])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_160, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[31] || (_cache[31] = function ($event) {
      return $options.totalRequest2();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_161, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_162, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_163, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count2.totalRequest), 1
  /* TEXT */
  )]), _hoisted_164])])])])) : this.role_name.includes('Personel ICT') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_165, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_166, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[32] || (_cache[32] = function ($event) {
      return $options.blmSelesai3();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_167, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_168, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_169, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count3.belumselesai), 1
  /* TEXT */
  )]), _hoisted_170])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_171, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[33] || (_cache[33] = function ($event) {
      return $options.sdHDikerjakan3();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_172, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_173, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_174, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count3.sudahdikerjakan), 1
  /* TEXT */
  )]), _hoisted_175])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_176, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[34] || (_cache[34] = function ($event) {
      return $options.sdhSelesai3();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_177, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_178, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_179, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count3.sudahselesai), 1
  /* TEXT */
  )]), _hoisted_180])])])])) : this.role_name.includes('Manager') ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_181, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_182, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[35] || (_cache[35] = function ($event) {
      return $options.sdgDireview4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_183, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_184, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_185, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.sedangdireview), 1
  /* TEXT */
  )]), _hoisted_186])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_187, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[36] || (_cache[36] = function ($event) {
      return $options.blmDiverifikasi4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_188, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_189, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_190, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.blmdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_191])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_192, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[37] || (_cache[37] = function ($event) {
      return $options.sdhDiverifikasi4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_193, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_194, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_195, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.sudahdiverifikasi), 1
  /* TEXT */
  )]), _hoisted_196])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_197, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[38] || (_cache[38] = function ($event) {
      return $options.direject4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_198, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_199, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_200, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.direject), 1
  /* TEXT */
  )]), _hoisted_201])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_202, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[39] || (_cache[39] = function ($event) {
      return $options.penugasanRequest4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_203, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_204, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_205, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.penugasanrequest), 1
  /* TEXT */
  )]), _hoisted_206])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_207, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[40] || (_cache[40] = function ($event) {
      return $options.sdgdikerjakan4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_208, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_209, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_210, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.sedangdikerjakan), 1
  /* TEXT */
  )]), _hoisted_211])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_212, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[41] || (_cache[41] = function ($event) {
      return $options.sdHDikerjakan4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_213, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_214, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_215, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.sudahdikerjakan), 1
  /* TEXT */
  )]), _hoisted_216])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_217, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[42] || (_cache[42] = function ($event) {
      return $options.sdhSelesai4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_218, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_219, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_220, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.sudahselesai), 1
  /* TEXT */
  )]), _hoisted_221])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_222, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    onClick: _cache[43] || (_cache[43] = function ($event) {
      return $options.totalRequest4();
    }),
    style: {
      "cursor": "pointer"
    },
    "class": "card mb-0"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_223, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_224, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_225, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.count4.totalrequest), 1
  /* TEXT */
  )]), _hoisted_226])])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true);
}

/***/ }),

/***/ "./resources/js/pages/Dashboard.vue":
/*!******************************************!*\
  !*** ./resources/js/pages/Dashboard.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Dashboard_vue_vue_type_template_id_82704d4a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=template&id=82704d4a */ "./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a");
/* harmony import */ var _Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=script&lang=js */ "./resources/js/pages/Dashboard.vue?vue&type=script&lang=js");



_Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _Dashboard_vue_vue_type_template_id_82704d4a__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/pages/Dashboard.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/pages/Dashboard.vue?vue&type=script&lang=js":
/*!******************************************************************!*\
  !*** ./resources/js/pages/Dashboard.vue?vue&type=script&lang=js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Dashboard.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a":
/*!************************************************************************!*\
  !*** ./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_template_id_82704d4a__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_template_id_82704d4a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Dashboard.vue?vue&type=template&id=82704d4a */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Dashboard.vue?vue&type=template&id=82704d4a");


/***/ })

}]);