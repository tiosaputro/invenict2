"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_ChartDemo_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AppEventBus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../AppEventBus */ "./resources/js/AppEventBus.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  themeChangeListener: null,
  mounted: function mounted() {
    var _this = this;

    this.themeChangeListener = function (event) {
      if (event.dark) _this.applyDarkTheme();else _this.applyLightTheme();
    };

    _AppEventBus__WEBPACK_IMPORTED_MODULE_0__["default"].on('change-theme', this.themeChangeListener);

    if (this.isDarkTheme()) {
      this.applyDarkTheme();
    } else {
      this.applyLightTheme();
    }
  },
  beforeUnmount: function beforeUnmount() {
    _AppEventBus__WEBPACK_IMPORTED_MODULE_0__["default"].off('change-theme', this.themeChangeListener);
  },
  data: function data() {
    return {
      lineData: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
          label: 'First Dataset',
          data: [65, 59, 80, 81, 56, 55, 40],
          fill: false,
          backgroundColor: '#2f4860',
          borderColor: '#2f4860',
          tension: .4
        }, {
          label: 'Second Dataset',
          data: [28, 48, 40, 19, 86, 27, 90],
          fill: false,
          backgroundColor: '#00bb7e',
          borderColor: '#00bb7e',
          tension: .4
        }]
      },
      pieData: {
        labels: ['A', 'B', 'C'],
        datasets: [{
          data: [300, 50, 100],
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
          hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
        }]
      },
      polarData: {
        datasets: [{
          data: [11, 16, 7, 3, 14],
          backgroundColor: ["#FF6384", "#4BC0C0", "#FFCE56", "#E7E9ED", "#36A2EB"],
          label: 'My dataset'
        }],
        labels: ["Red", "Green", "Yellow", "Grey", "Blue"]
      },
      barData: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
          label: 'My First dataset',
          backgroundColor: '#2f4860',
          data: [65, 59, 80, 81, 56, 55, 40]
        }, {
          label: 'My Second dataset',
          backgroundColor: '#00bb7e',
          data: [28, 48, 40, 19, 86, 27, 90]
        }]
      },
      radarData: {
        labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
        datasets: [{
          label: 'My First dataset',
          backgroundColor: 'rgba(179,181,198,0.2)',
          borderColor: 'rgba(179,181,198,1)',
          pointBackgroundColor: 'rgba(179,181,198,1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(179,181,198,1)',
          data: [65, 59, 90, 81, 56, 55, 40]
        }, {
          label: 'My Second dataset',
          backgroundColor: 'rgba(255,99,132,0.2)',
          borderColor: 'rgba(255,99,132,1)',
          pointBackgroundColor: 'rgba(255,99,132,1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(255,99,132,1)',
          data: [28, 48, 40, 19, 96, 27, 100]
        }]
      },
      lineOptions: null,
      pieOptions: null,
      polarOptions: null,
      barOptions: null,
      radarOptions: null
    };
  },
  methods: {
    isDarkTheme: function isDarkTheme() {
      return this.$appState.darkTheme === true;
    },
    applyLightTheme: function applyLightTheme() {
      this.lineOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          },
          y: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          }
        }
      };
      this.barOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          },
          y: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          }
        }
      };
      this.pieOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        }
      };
      this.polarOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        },
        scales: {
          r: {
            grid: {
              color: '#ebedef'
            }
          }
        }
      };
      this.radarOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        },
        scales: {
          r: {
            grid: {
              color: '#ebedef'
            }
          }
        }
      };
    },
    applyDarkTheme: function applyDarkTheme() {
      this.lineOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#ebedef'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: '#ebedef'
            },
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          },
          y: {
            ticks: {
              color: '#ebedef'
            },
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          }
        }
      };
      this.barOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#ebedef'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: '#ebedef'
            },
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          },
          y: {
            ticks: {
              color: '#ebedef'
            },
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          }
        }
      };
      this.pieOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#ebedef'
            }
          }
        }
      };
      this.polarOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#ebedef'
            }
          }
        },
        scales: {
          r: {
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          }
        }
      };
      this.radarOptions = {
        plugins: {
          legend: {
            labels: {
              color: '#ebedef'
            }
          }
        },
        scales: {
          r: {
            grid: {
              color: 'rgba(160, 167, 181, .3)'
            }
          }
        }
      };
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86":
/*!*******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "grid p-fluid"
};
var _hoisted_2 = {
  "class": "col-12 lg:col-6"
};
var _hoisted_3 = {
  "class": "card"
};

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", null, "Linear Chart", -1
/* HOISTED */
);

var _hoisted_5 = {
  "class": "card flex flex-column align-items-center"
};

var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", {
  "class": "align-self-start"
}, "Pie Chart", -1
/* HOISTED */
);

var _hoisted_7 = {
  "class": "card flex flex-column align-items-center"
};

var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", {
  "class": "align-self-start"
}, "Polar Area Chart", -1
/* HOISTED */
);

var _hoisted_9 = {
  "class": "col-12 lg:col-6"
};
var _hoisted_10 = {
  "class": "card"
};

var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", null, "Bar Chart", -1
/* HOISTED */
);

var _hoisted_12 = {
  "class": "card flex flex-column align-items-center"
};

var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", {
  "class": "align-self-start"
}, "Doughnut Chart", -1
/* HOISTED */
);

var _hoisted_14 = {
  "class": "card flex flex-column align-items-center"
};

var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h5", {
  "class": "align-self-start"
}, "Radar Chart", -1
/* HOISTED */
);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Chart = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Chart");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [_hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "line",
    data: $data.lineData,
    options: $data.lineOptions
  }, null, 8
  /* PROPS */
  , ["data", "options"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "pie",
    data: $data.pieData,
    options: $data.pieOptions,
    style: {
      "width": "50%"
    }
  }, null, 8
  /* PROPS */
  , ["data", "options"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "polarArea",
    data: $data.polarData,
    options: $data.polarOptions,
    style: {
      "width": "50%"
    }
  }, null, 8
  /* PROPS */
  , ["data", "options"])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [_hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "bar",
    data: $data.barData,
    options: $data.barOptions
  }, null, 8
  /* PROPS */
  , ["data", "options"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "doughnut",
    data: $data.pieData,
    options: $data.pieOptions,
    style: {
      "width": "50%"
    }
  }, null, 8
  /* PROPS */
  , ["data", "options"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Chart, {
    type: "radar",
    data: $data.radarData,
    options: $data.radarOptions,
    style: {
      "width": "50%"
    }
  }, null, 8
  /* PROPS */
  , ["data", "options"])])])]);
}

/***/ }),

/***/ "./resources/js/components/ChartDemo.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/ChartDemo.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ChartDemo_vue_vue_type_template_id_23d97c86__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChartDemo.vue?vue&type=template&id=23d97c86 */ "./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86");
/* harmony import */ var _ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChartDemo.vue?vue&type=script&lang=js */ "./resources/js/components/ChartDemo.vue?vue&type=script&lang=js");



_ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].render = _ChartDemo_vue_vue_type_template_id_23d97c86__WEBPACK_IMPORTED_MODULE_0__.render
/* hot reload */
if (false) {}

_ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"].__file = "resources/js/components/ChartDemo.vue"

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./resources/js/components/ChartDemo.vue?vue&type=script&lang=js":
/*!***********************************************************************!*\
  !*** ./resources/js/components/ChartDemo.vue?vue&type=script&lang=js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ChartDemo_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./ChartDemo.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86 ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ChartDemo_vue_vue_type_template_id_23d97c86__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_ChartDemo_vue_vue_type_template_id_23d97c86__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./ChartDemo.vue?vue&type=template&id=23d97c86 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/ChartDemo.vue?vue&type=template&id=23d97c86");


/***/ })

}]);