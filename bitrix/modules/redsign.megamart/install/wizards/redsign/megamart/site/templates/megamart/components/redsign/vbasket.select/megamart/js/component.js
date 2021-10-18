this.VBasket = this.VBasket || {};
(function (exports) {
	'use strict';

	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	var script = {
	  props: {
	    data: Array,
	    useShare: Boolean
	  },
	  computed: {
	    messages: function messages() {
	      return BX.Vue.getFilteredPhrases('RS_VBASKET_');
	    }
	  }
	};

	function normalizeComponent(template, style, script, scopeId, isFunctionalTemplate, moduleIdentifier
	/* server only */
	, shadowMode, createInjector, createInjectorSSR, createInjectorShadow) {
	  if (typeof shadowMode !== 'boolean') {
	    createInjectorSSR = createInjector;
	    createInjector = shadowMode;
	    shadowMode = false;
	  } // Vue.extend constructor export interop.


	  var options = typeof script === 'function' ? script.options : script; // render functions

	  if (template && template.render) {
	    options.render = template.render;
	    options.staticRenderFns = template.staticRenderFns;
	    options._compiled = true; // functional template

	    if (isFunctionalTemplate) {
	      options.functional = true;
	    }
	  } // scopedId


	  if (scopeId) {
	    options._scopeId = scopeId;
	  }

	  var hook;

	  if (moduleIdentifier) {
	    // server build
	    hook = function hook(context) {
	      // 2.3 injection
	      context = context || // cached call
	      this.$vnode && this.$vnode.ssrContext || // stateful
	      this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext; // functional
	      // 2.2 with runInNewContext: true

	      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
	        context = __VUE_SSR_CONTEXT__;
	      } // inject component styles


	      if (style) {
	        style.call(this, createInjectorSSR(context));
	      } // register component module identifier for async chunk inference


	      if (context && context._registeredComponents) {
	        context._registeredComponents.add(moduleIdentifier);
	      }
	    }; // used by ssr in case component is cached and beforeCreate
	    // never gets called


	    options._ssrRegister = hook;
	  } else if (style) {
	    hook = shadowMode ? function (context) {
	      style.call(this, createInjectorShadow(context, this.$root.$options.shadowRoot));
	    } : function (context) {
	      style.call(this, createInjector(context));
	    };
	  }

	  if (hook) {
	    if (options.functional) {
	      // register for functional component in vue file
	      var originalRender = options.render;

	      options.render = function renderWithStyleInjection(h, context) {
	        hook.call(context);
	        return originalRender(h, context);
	      };
	    } else {
	      // inject component registration as beforeCreate hook
	      var existing = options.beforeCreate;
	      options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
	    }
	  }

	  return script;
	}

	/* script */
	var __vue_script__ = script;
	/* template */

	var __vue_render__ = function __vue_render__() {
	  var _vm = this;

	  var _h = _vm.$createElement;

	  var _c = _vm._self._c || _h;

	  return _c("div", {
	    staticClass: "basket-select"
	  }, [_vm._l(_vm.data, function (basket) {
	    return _c("div", {
	      key: basket.ID,
	      staticClass: "basket-select__item",
	      class: {
	        "basket-select__item--active": basket.SELECTED
	      },
	      style: basket.COLOR ? "color: " + basket.COLOR : ""
	    }, [_c("button", {
	      staticClass: "basket-select__button",
	      attrs: {
	        type: "button"
	      },
	      on: {
	        click: function click($event) {
	          return _vm.$emit("select", basket.CODE);
	        }
	      }
	    }, [_c("span", {
	      staticClass: "basket-select__text"
	    }, [_vm._v(_vm._s(basket["~NAME"]))]), _vm._v(" "), _c("span", {
	      staticClass: "basket-select__count",
	      style: basket.COLOR ? "background-color: " + basket.COLOR : ""
	    }, [_vm._v(_vm._s(basket.CNT))])]), _vm._v(" "), basket.SELECTED ? _c("button", {
	      staticClass: "basket-select__icon",
	      attrs: {
	        type: "button",
	        title: _vm.messages.RS_VBASKET_SELECT_EDIT
	      },
	      on: {
	        click: function click($event) {
	          return _vm.$emit("edit", basket);
	        }
	      }
	    }, [_c("svg", {
	      staticClass: "icon-svg"
	    }, [_c("use", {
	      attrs: {
	        "xlink:href": "#svg-edit-2"
	      }
	    })])]) : _vm._e(), _vm._v(" "), _vm.useShare && basket.SELECTED && basket.CNT ? _c("button", {
	      staticClass: "basket-select__icon",
	      attrs: {
	        type: "button",
	        title: _vm.messages.RS_VBASKET_SHARE_MODAL_TITLE
	      },
	      on: {
	        click: function click($event) {
	          return _vm.$emit("share", basket.CODE);
	        }
	      }
	    }, [_c("svg", {
	      staticClass: "icon-svg"
	    }, [_c("use", {
	      attrs: {
	        "xlink:href": "#svg-link"
	      }
	    })])]) : _vm._e(), _vm._v(" "), basket.SELECTED && _vm.data.length > 1 ? _c("button", {
	      staticClass: "basket-select__icon",
	      attrs: {
	        type: "button",
	        title: _vm.messages.RS_VBASKET_SELECT_REMOVE
	      },
	      on: {
	        click: function click($event) {
	          return _vm.$emit("remove", basket.CODE);
	        }
	      }
	    }, [_c("svg", {
	      staticClass: "icon-svg"
	    }, [_c("use", {
	      attrs: {
	        "xlink:href": "#svg-cross"
	      }
	    })])]) : _vm._e()]);
	  }), _vm._v(" "), _c("div", {
	    staticClass: "basket-select__item basket-select__item--edit"
	  }, [_c("button", {
	    staticClass: "basket-select__edit-button",
	    on: {
	      click: function click($event) {
	        return _vm.$emit("create");
	      }
	    }
	  })])], 2);
	};

	var __vue_staticRenderFns__ = [];
	__vue_render__._withStripped = true;
	/* style */

	var __vue_inject_styles__ = undefined;
	/* scoped */

	var __vue_scope_id__ = undefined;
	/* module identifier */

	var __vue_module_identifier__ = undefined;
	/* functional template */

	var __vue_is_functional_template__ = false;
	/* style inject */

	/* style inject SSR */

	/* style inject shadow dom */

	var __vue_component__ = normalizeComponent({
	  render: __vue_render__,
	  staticRenderFns: __vue_staticRenderFns__
	}, __vue_inject_styles__, __vue_script__, __vue_scope_id__, __vue_is_functional_template__, __vue_module_identifier__, false, undefined, undefined, undefined);

	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	var script$1 = {
	  props: {
	    data: Array,
	    useShare: Boolean
	  },
	  data: function data() {
	    return {
	      selectIndex: 0
	    };
	  },
	  computed: {
	    messages: function messages() {
	      return BX.Vue.getFilteredPhrases('RS_VBASKET_');
	    },
	    selectedIndex: function selectedIndex() {
	      return this.data.findIndex(function (item) {
	        return item.SELECTED;
	      });
	    },
	    selected: function selected() {
	      return this.data[this.selectedIndex];
	    }
	  },
	  created: function created() {
	    this.selectIndex = this.selectedIndex;
	  },
	  methods: {
	    handleChange: function handleChange() {
	      if (this.selectIndex === 'new') {
	        this.$emit('create');
	      } else {
	        this.$emit('select', this.data[this.selectIndex].CODE);
	      }
	    }
	  }
	};

	/* script */
	var __vue_script__$1 = script$1;
	/* template */

	var __vue_render__$1 = function __vue_render__() {
	  var _vm = this;

	  var _h = _vm.$createElement;

	  var _c = _vm._self._c || _h;

	  return _c("div", {
	    staticClass: "basket-select-mobile d-flex align-items-center"
	  }, [_c("div", {
	    staticClass: "flex-grow-1"
	  }, [_c("select", {
	    directives: [{
	      name: "model",
	      rawName: "v-model",
	      value: _vm.selectIndex,
	      expression: "selectIndex"
	    }],
	    staticClass: "form-control",
	    on: {
	      change: [function ($event) {
	        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
	          return o.selected;
	        }).map(function (o) {
	          var val = "_value" in o ? o._value : o.value;
	          return val;
	        });
	        _vm.selectIndex = $event.target.multiple ? $$selectedVal : $$selectedVal[0];
	      }, _vm.handleChange]
	    }
	  }, [_vm._l(_vm.data, function (basket, index) {
	    return _c("option", {
	      key: basket.ID,
	      domProps: {
	        value: index
	      }
	    }, [_vm._v(_vm._s(basket.NAME))]);
	  }), _vm._v(" "), _c("option", {
	    attrs: {
	      value: "new"
	    }
	  }, [_vm._v(_vm._s(_vm.messages.RS_VBASKET_SELECT_NEW_BASKET_PLUS))])], 2)]), _vm._v(" "), _c("div", {
	    staticClass: "flex-grow-0 d-flex ml-3"
	  }, [_c("a", {
	    staticClass: "c-icon",
	    attrs: {
	      href: "#",
	      rel: "nofollow"
	    },
	    on: {
	      click: function click($event) {
	        $event.preventDefault();
	        return _vm.$emit("edit", _vm.selected);
	      }
	    }
	  }, [_c("svg", {
	    staticClass: "icon icon-svg"
	  }, [_c("use", {
	    attrs: {
	      "xlink:href": "#svg-edit-2"
	    }
	  })])]), _vm._v(" "), _vm.useShare && _vm.basket.SELECTED && _vm.basket.CNT ? _c("a", {
	    staticClass: "c-icon",
	    attrs: {
	      href: "#",
	      rel: "nofollow"
	    },
	    on: {
	      click: function click($event) {
	        $event.preventDefault();
	        return _vm.$emit("share", _vm.selected.CODE);
	      }
	    }
	  }, [_c("svg", {
	    staticClass: "icon icon-svg"
	  }, [_c("use", {
	    attrs: {
	      "xlink:href": "#svg-link"
	    }
	  })])]) : _vm._e(), _vm._v(" "), _vm.data.length > 1 ? _c("a", {
	    staticClass: "c-icon",
	    attrs: {
	      href: "#",
	      rel: "nofollow"
	    },
	    on: {
	      click: function click($event) {
	        $event.preventDefault();
	        return _vm.$emit("remove", _vm.selected.CODE);
	      }
	    }
	  }, [_c("svg", {
	    staticClass: "icon icon-svg"
	  }, [_c("use", {
	    attrs: {
	      "xlink:href": "#svg-cross"
	    }
	  })])]) : _vm._e()])]);
	};

	var __vue_staticRenderFns__$1 = [];
	__vue_render__$1._withStripped = true;
	/* style */

	var __vue_inject_styles__$1 = undefined;
	/* scoped */

	var __vue_scope_id__$1 = undefined;
	/* module identifier */

	var __vue_module_identifier__$1 = undefined;
	/* functional template */

	var __vue_is_functional_template__$1 = false;
	/* style inject */

	/* style inject SSR */

	/* style inject shadow dom */

	var __vue_component__$1 = normalizeComponent({
	  render: __vue_render__$1,
	  staticRenderFns: __vue_staticRenderFns__$1
	}, __vue_inject_styles__$1, __vue_script__$1, __vue_scope_id__$1, __vue_is_functional_template__$1, __vue_module_identifier__$1, false, undefined, undefined, undefined);

	function unwrapExports(x) {
	  return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x.default : x;
	}
	function createCommonjsModule(fn, module) {
	  return module = {
	    exports: {}
	  }, fn(module, module.exports), module.exports;
	}

	var vueSwatches_min = createCommonjsModule(function (module, exports) {
	  !function (e, t) {
	    module.exports = t();
	  }(window, function () {
	    return function (e) {
	      var t = {};

	      function n(r) {
	        if (t[r]) return t[r].exports;
	        var i = t[r] = {
	          i: r,
	          l: !1,
	          exports: {}
	        };
	        return e[r].call(i.exports, i, i.exports, n), i.l = !0, i.exports;
	      }

	      return n.m = e, n.c = t, n.d = function (e, t, r) {
	        n.o(e, t) || Object.defineProperty(e, t, {
	          configurable: !1,
	          enumerable: !0,
	          get: r
	        });
	      }, n.r = function (e) {
	        Object.defineProperty(e, "__esModule", {
	          value: !0
	        });
	      }, n.n = function (e) {
	        var t = e && e.__esModule ? function () {
	          return e.default;
	        } : function () {
	          return e;
	        };
	        return n.d(t, "a", t), t;
	      }, n.o = function (e, t) {
	        return Object.prototype.hasOwnProperty.call(e, t);
	      }, n.p = "/", n(n.s = "tjUo");
	    }({
	      "29s/": function s(e, t, n) {
	        var r = n("5T2Y"),
	            i = r["__core-js_shared__"] || (r["__core-js_shared__"] = {});

	        e.exports = function (e) {
	          return i[e] || (i[e] = {});
	        };
	      },
	      "2GTP": function GTP(e, t, n) {
	        var r = n("eaoh");

	        e.exports = function (e, t, n) {
	          if (r(e), void 0 === t) return e;

	          switch (n) {
	            case 1:
	              return function (n) {
	                return e.call(t, n);
	              };

	            case 2:
	              return function (n, r) {
	                return e.call(t, n, r);
	              };

	            case 3:
	              return function (n, r, i) {
	                return e.call(t, n, r, i);
	              };
	          }

	          return function () {
	            return e.apply(t, arguments);
	          };
	        };
	      },
	      "2faE": function faE(e, t, n) {
	        var r = n("5K7Z"),
	            i = n("eUtF"),
	            o = n("G8Mo"),
	            s = Object.defineProperty;
	        t.f = n("jmDH") ? Object.defineProperty : function (e, t, n) {
	          if (r(e), t = o(t, !0), r(n), i) try {
	            return s(e, t, n);
	          } catch (e) {}
	          if ("get" in n || "set" in n) throw TypeError("Accessors not supported!");
	          return "value" in n && (e[t] = n.value), e;
	        };
	      },
	      "5K7Z": function K7Z(e, t, n) {
	        var r = n("93I4");

	        e.exports = function (e) {
	          if (!r(e)) throw TypeError(e + " is not an object!");
	          return e;
	        };
	      },
	      "5T2Y": function T2Y(e, t) {
	        var n = e.exports = "undefined" != typeof window && window.Math == Math ? window : "undefined" != typeof self && self.Math == Math ? self : Function("return this")();
	        "number" == typeof __g && (__g = n);
	      },
	      "5vMV": function vMV(e, t, n) {
	        var r = n("B+OT"),
	            i = n("NsO/"),
	            o = n("W070")(!1),
	            s = n("VVlx")("IE_PROTO");

	        e.exports = function (e, t) {
	          var n,
	              c = i(e),
	              a = 0,
	              u = [];

	          for (n in c) {
	            n != s && r(c, n) && u.push(n);
	          }

	          for (; t.length > a;) {
	            r(c, n = t[a++]) && (~o(u, n) || u.push(n));
	          }

	          return u;
	        };
	      },
	      "93I4": function I4(e, t) {
	        e.exports = function (e) {
	          return "object" == babelHelpers.typeof(e) ? null !== e : "function" == typeof e;
	        };
	      },
	      "B+OT": function BOT(e, t) {
	        var n = {}.hasOwnProperty;

	        e.exports = function (e, t) {
	          return n.call(e, t);
	        };
	      },
	      D8kY: function D8kY(e, t, n) {
	        var r = n("Ojgd"),
	            i = Math.max,
	            o = Math.min;

	        e.exports = function (e, t) {
	          return (e = r(e)) < 0 ? i(e + t, 0) : o(e, t);
	        };
	      },
	      FpHa: function FpHa(e, t) {
	        e.exports = "constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(",");
	      },
	      G8Mo: function G8Mo(e, t, n) {
	        var r = n("93I4");

	        e.exports = function (e, t) {
	          if (!r(e)) return e;
	          var n, i;
	          if (t && "function" == typeof (n = e.toString) && !r(i = n.call(e))) return i;
	          if ("function" == typeof (n = e.valueOf) && !r(i = n.call(e))) return i;
	          if (!t && "function" == typeof (n = e.toString) && !r(i = n.call(e))) return i;
	          throw TypeError("Can't convert object to primitive value");
	        };
	      },
	      Hsns: function Hsns(e, t, n) {
	        var r = n("93I4"),
	            i = n("5T2Y").document,
	            o = r(i) && r(i.createElement);

	        e.exports = function (e) {
	          return o ? i.createElement(e) : {};
	        };
	      },
	      JB68: function JB68(e, t, n) {
	        var r = n("Jes0");

	        e.exports = function (e) {
	          return Object(r(e));
	        };
	      },
	      Jes0: function Jes0(e, t) {
	        e.exports = function (e) {
	          if (void 0 == e) throw TypeError("Can't call method on  " + e);
	          return e;
	        };
	      },
	      KUxP: function KUxP(e, t) {
	        e.exports = function (e) {
	          try {
	            return !!e();
	          } catch (e) {
	            return !0;
	          }
	        };
	      },
	      M1xp: function M1xp(e, t, n) {
	        var r = n("a0xu");
	        e.exports = Object("z").propertyIsEnumerable(0) ? Object : function (e) {
	          return "String" == r(e) ? e.split("") : Object(e);
	        };
	      },
	      NV0k: function NV0k(e, t) {
	        t.f = {}.propertyIsEnumerable;
	      },
	      NegM: function NegM(e, t, n) {
	        var r = n("2faE"),
	            i = n("rr1i");
	        e.exports = n("jmDH") ? function (e, t, n) {
	          return r.f(e, t, i(1, n));
	        } : function (e, t, n) {
	          return e[t] = n, e;
	        };
	      },
	      "NsO/": function NsO(e, t, n) {
	        var r = n("M1xp"),
	            i = n("Jes0");

	        e.exports = function (e) {
	          return r(i(e));
	        };
	      },
	      Ojgd: function Ojgd(e, t) {
	        var n = Math.ceil,
	            r = Math.floor;

	        e.exports = function (e) {
	          return isNaN(e = +e) ? 0 : (e > 0 ? r : n)(e);
	        };
	      },
	      P2sY: function P2sY(e, t, n) {
	        e.exports = {
	          default: n("UbbE"),
	          __esModule: !0
	        };
	      },
	      QbLZ: function QbLZ(e, t, n) {

	        t.__esModule = !0;
	        var r,
	            i = (r = n("P2sY")) && r.__esModule ? r : {
	          default: r
	        };

	        t.default = i.default || function (e) {
	          for (var t = 1; t < arguments.length; t++) {
	            var n = arguments[t];

	            for (var r in n) {
	              Object.prototype.hasOwnProperty.call(n, r) && (e[r] = n[r]);
	            }
	          }

	          return e;
	        };
	      },
	      UbbE: function UbbE(e, t, n) {
	        n("o8NH"), e.exports = n("WEpk").Object.assign;
	      },
	      VVlx: function VVlx(e, t, n) {
	        var r = n("29s/")("keys"),
	            i = n("YqAc");

	        e.exports = function (e) {
	          return r[e] || (r[e] = i(e));
	        };
	      },
	      W070: function W070(e, t, n) {
	        var r = n("NsO/"),
	            i = n("tEej"),
	            o = n("D8kY");

	        e.exports = function (e) {
	          return function (t, n, s) {
	            var c,
	                a = r(t),
	                u = i(a.length),
	                l = o(s, u);

	            if (e && n != n) {
	              for (; u > l;) {
	                if ((c = a[l++]) != c) return !0;
	              }
	            } else for (; u > l; l++) {
	              if ((e || l in a) && a[l] === n) return e || l || 0;
	            }

	            return !e && -1;
	          };
	        };
	      },
	      WEpk: function WEpk(e, t) {
	        var n = e.exports = {
	          version: "2.5.3"
	        };
	        "number" == typeof __e && (__e = n);
	      },
	      Y7ZC: function Y7ZC(e, t, n) {
	        var r = n("5T2Y"),
	            i = n("WEpk"),
	            o = n("2GTP"),
	            s = n("NegM"),
	            c = function c(e, t, n) {
	          var a,
	              u,
	              l,
	              p = e & c.F,
	              h = e & c.G,
	              f = e & c.S,
	              d = e & c.P,
	              w = e & c.B,
	              v = e & c.W,
	              b = h ? i : i[t] || (i[t] = {}),
	              g = b.prototype,
	              y = h ? r : f ? r[t] : (r[t] || {}).prototype;

	          for (a in h && (n = t), n) {
	            (u = !p && y && void 0 !== y[a]) && a in b || (l = u ? y[a] : n[a], b[a] = h && "function" != typeof y[a] ? n[a] : w && u ? o(l, r) : v && y[a] == l ? function (e) {
	              var t = function t(_t, n, r) {
	                if (this instanceof e) {
	                  switch (arguments.length) {
	                    case 0:
	                      return new e();

	                    case 1:
	                      return new e(_t);

	                    case 2:
	                      return new e(_t, n);
	                  }

	                  return new e(_t, n, r);
	                }

	                return e.apply(this, arguments);
	              };

	              return t.prototype = e.prototype, t;
	            }(l) : d && "function" == typeof l ? o(Function.call, l) : l, d && ((b.virtual || (b.virtual = {}))[a] = l, e & c.R && g && !g[a] && s(g, a, l)));
	          }
	        };

	        c.F = 1, c.G = 2, c.S = 4, c.P = 8, c.B = 16, c.W = 32, c.U = 64, c.R = 128, e.exports = c;
	      },
	      YqAc: function YqAc(e, t) {
	        var n = 0,
	            r = Math.random();

	        e.exports = function (e) {
	          return "Symbol(".concat(void 0 === e ? "" : e, ")_", (++n + r).toString(36));
	        };
	      },
	      YtwX: function YtwX(e, t) {},
	      a0xu: function a0xu(e, t) {
	        var n = {}.toString;

	        e.exports = function (e) {
	          return n.call(e).slice(8, -1);
	        };
	      },
	      eUtF: function eUtF(e, t, n) {
	        e.exports = !n("jmDH") && !n("KUxP")(function () {
	          return 7 != Object.defineProperty(n("Hsns")("div"), "a", {
	            get: function get() {
	              return 7;
	            }
	          }).a;
	        });
	      },
	      eaoh: function eaoh(e, t) {
	        e.exports = function (e) {
	          if ("function" != typeof e) throw TypeError(e + " is not a function!");
	          return e;
	        };
	      },
	      jmDH: function jmDH(e, t, n) {
	        e.exports = !n("KUxP")(function () {
	          return 7 != Object.defineProperty({}, "a", {
	            get: function get() {
	              return 7;
	            }
	          }).a;
	        });
	      },
	      kwZ1: function kwZ1(e, t, n) {

	        var r = n("w6GO"),
	            i = n("mqlF"),
	            o = n("NV0k"),
	            s = n("JB68"),
	            c = n("M1xp"),
	            a = Object.assign;
	        e.exports = !a || n("KUxP")(function () {
	          var e = {},
	              t = {},
	              n = Symbol(),
	              r = "abcdefghijklmnopqrst";
	          return e[n] = 7, r.split("").forEach(function (e) {
	            t[e] = e;
	          }), 7 != a({}, e)[n] || Object.keys(a({}, t)).join("") != r;
	        }) ? function (e, t) {
	          for (var n = s(e), a = arguments.length, u = 1, l = i.f, p = o.f; a > u;) {
	            for (var h, f = c(arguments[u++]), d = l ? r(f).concat(l(f)) : r(f), w = d.length, v = 0; w > v;) {
	              p.call(f, h = d[v++]) && (n[h] = f[h]);
	            }
	          }

	          return n;
	        } : a;
	      },
	      mqlF: function mqlF(e, t) {
	        t.f = Object.getOwnPropertySymbols;
	      },
	      o8NH: function o8NH(e, t, n) {
	        var r = n("Y7ZC");
	        r(r.S + r.F, "Object", {
	          assign: n("kwZ1")
	        });
	      },
	      rr1i: function rr1i(e, t) {
	        e.exports = function (e, t) {
	          return {
	            enumerable: !(1 & e),
	            configurable: !(2 & e),
	            writable: !(4 & e),
	            value: t
	          };
	        };
	      },
	      tEej: function tEej(e, t, n) {
	        var r = n("Ojgd"),
	            i = Math.min;

	        e.exports = function (e) {
	          return e > 0 ? i(r(e), 9007199254740991) : 0;
	        };
	      },
	      tjUo: function tjUo(e, t, n) {

	        n.r(t);
	        var r = n("QbLZ"),
	            i = n.n(r),
	            o = {
	          basic: {
	            swatches: ["#1FBC9C", "#1CA085", "#2ECC70", "#27AF60", "#3398DB", "#2980B9", "#A463BF", "#8E43AD", "#3D556E", "#222F3D", "#F2C511", "#F39C19", "#E84B3C", "#C0382B", "#DDE6E8", "#BDC3C8"],
	            rowLength: 4
	          },
	          "text-basic": {
	            swatches: ["#CC0001", "#E36101", "#FFCC00", "#009900", "#0066CB", "#000000", "#FFFFFF"],
	            showBorder: !0
	          },
	          "text-advanced": {
	            swatches: [["#000000", "#434343", "#666666", "#999999", "#b7b7b7", "#cccccc", "#d9d9d9", "#efefef", "#f3f3f3", "#ffffff"], ["#980000", "#ff0000", "#ff9900", "#ffff00", "#00ff00", "#00ffff", "#4a86e8", "#0000ff", "#9900ff", "#ff00ff"], ["#e6b8af", "#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#c9daf8", "#cfe2f3", "#d9d2e9", "#ead1dc"], ["#dd7e6b", "#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#a4c2f4", "#9fc5e8", "#b4a7d6", "#d5a6bd"], ["#cc4125", "#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6d9eeb", "#6fa8dc", "#8e7cc3", "#c27ba0"], ["#a61c00", "#cc0000", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3c78d8", "#3d85c6", "#674ea7", "#a64d79"], ["#85200c", "#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#1155cc", "#0b5394", "#351c75", "#741b47"], ["#5b0f00", "#660000", "#783f04", "#7f6000", "#274e13", "#0c343d", "#1c4587", "#073763", "#20124d", "#4c1130"]],
	            borderRadius: "0",
	            rowLength: 10,
	            swatchSize: 24,
	            spacingSize: 0
	          },
	          "material-basic": {
	            swatches: ["#F44336", "#E91E63", "#9C27B0", "#673AB7", "#3F51B5", "#2196F3", "#03A9F4", "#00BCD4", "#009688", "#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#FF5722", "#795548", "#9E9E9E", "#607D8B"]
	          },
	          "material-light": {
	            swatches: ["#EF9A9A", "#F48FB1", "#CE93D8", "#B39DDB", "#9FA8DA", "#90CAF9", "#81D4FA", "#80DEEA", "#80CBC4", "#A5D6A7", "#C5E1A5", "#E6EE9C", "#FFF59D", "#FFE082", "#FFCC80", "#FFAB91", "#BCAAA4", "#EEEEEE", "#B0BEC5"]
	          },
	          "material-dark": {
	            swatches: ["#D32F2F", "#C2185B", "#7B1FA2", "#512DA8", "#303F9F", "#1976D2", "#0288D1", "#0097A7", "#00796B", "#388E3C", "#689F38", "#AFB42B", "#FBC02D", "#FFA000", "#F57C00", "#E64A19", "#5D4037", "#616161", "#455A64"]
	          }
	        };

	        function s(e, t, n, r, i, o, s, c) {
	          var a = babelHelpers.typeof((e = e || {}).default);
	          "object" !== a && "function" !== a || (e = e.default);
	          var u,
	              l = "function" == typeof e ? e.options : e;
	          if (t && (l.render = t, l.staticRenderFns = n, l._compiled = !0), r && (l.functional = !0), o && (l._scopeId = o), s ? (u = function u(e) {
	            (e = e || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) || "undefined" == typeof __VUE_SSR_CONTEXT__ || (e = __VUE_SSR_CONTEXT__), i && i.call(this, e), e && e._registeredComponents && e._registeredComponents.add(s);
	          }, l._ssrRegister = u) : i && (u = c ? function () {
	            i.call(this, this.$root.$options.shadowRoot);
	          } : i), u) if (l.functional) {
	            l._injectStyles = u;
	            var p = l.render;

	            l.render = function (e, t) {
	              return u.call(t), p(e, t);
	            };
	          } else {
	            var h = l.beforeCreate;
	            l.beforeCreate = h ? [].concat(h, u) : [u];
	          }
	          return {
	            exports: e,
	            options: l
	          };
	        }

	        var c = s({
	          name: "swatches",
	          components: {
	            Swatch: s({
	              name: "swatch",
	              components: {
	                Check: s({
	                  name: "check",
	                  data: function data() {
	                    return {};
	                  }
	                }, function () {
	                  var e = this.$createElement,
	                      t = this._self._c || e;
	                  return t("div", {
	                    staticClass: "vue-swatches__check__wrapper vue-swatches--has-children-centered"
	                  }, [t("div", {
	                    staticClass: "vue-swatches__check__circle vue-swatches--has-children-centered"
	                  }, [t("svg", {
	                    staticClass: "check",
	                    attrs: {
	                      version: "1.1",
	                      role: "presentation",
	                      width: "12",
	                      height: "12",
	                      viewBox: "0 0 1792 1792"
	                    }
	                  }, [t("path", {
	                    staticClass: "vue-swatches__check__path",
	                    attrs: {
	                      d: "M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"
	                    }
	                  })])])]);
	                }, [], !1, function (e) {
	                  n("xbiK");
	                }, null, null).exports
	              },
	              props: {
	                borderRadius: {
	                  type: String
	                },
	                disabled: {
	                  type: Boolean
	                },
	                exceptionMode: {
	                  type: String
	                },
	                isException: {
	                  type: Boolean,
	                  default: !1
	                },
	                selected: {
	                  type: Boolean,
	                  default: !1
	                },
	                showCheckbox: {
	                  type: Boolean
	                },
	                showBorder: {
	                  type: Boolean
	                },
	                size: {
	                  type: Number
	                },
	                spacingSize: {
	                  type: Number
	                },
	                swatchColor: {
	                  type: String,
	                  default: ""
	                },
	                swatchStyle: {
	                  type: Object
	                }
	              },
	              data: function data() {
	                return {};
	              },
	              computed: {
	                computedSwatchStyle: function computedSwatchStyle() {
	                  return {
	                    display: this.isException && "hidden" === this.exceptionMode ? "none" : "inline-block",
	                    width: this.size + "px",
	                    height: this.size + "px",
	                    marginBottom: this.spacingSize + "px",
	                    marginRight: this.spacingSize + "px",
	                    borderRadius: this.borderRadius,
	                    backgroundColor: "" !== this.swatchColor ? this.swatchColor : "#FFFFFF",
	                    cursor: this.cursorStyle
	                  };
	                },
	                cursorStyle: function cursorStyle() {
	                  return this.disabled ? "not-allowed" : this.isException && "disabled" === this.exceptionMode ? "not-allowed" : "pointer";
	                },
	                swatchStyles: function swatchStyles() {
	                  return [this.computedSwatchStyle, this.swatchStyle];
	                }
	              }
	            }, function () {
	              var e = this,
	                  t = e.$createElement,
	                  n = e._self._c || t;
	              return n("div", {
	                staticClass: "vue-swatches__swatch",
	                class: {
	                  "vue-swatches__swatch--border": e.showBorder,
	                  "vue-swatches__swatch--selected": e.selected,
	                  "vue-swatches__swatch--is-exception": e.isException || e.disabled
	                },
	                style: e.swatchStyles
	              }, ["" === e.swatchColor ? n("div", {
	                staticClass: "vue-swatches__diagonal--wrapper vue-swatches--has-children-centered"
	              }, [n("div", {
	                staticClass: "vue-swatches__diagonal"
	              })]) : e._e(), e._v(" "), n("check", {
	                directives: [{
	                  name: "show",
	                  rawName: "v-show",
	                  value: e.showCheckbox && e.selected,
	                  expression: "showCheckbox && selected"
	                }]
	              })], 1);
	            }, [], !1, function (e) {
	              n("xcGP");
	            }, null, null).exports
	          },
	          props: {
	            backgroundColor: {
	              type: String,
	              default: "#ffffff"
	            },
	            closeOnSelect: {
	              type: Boolean,
	              default: !0
	            },
	            colors: {
	              type: [Array, Object, String],
	              default: "basic"
	            },
	            exceptions: {
	              type: Array,
	              default: function _default() {
	                return [];
	              }
	            },
	            exceptionMode: {
	              type: String,
	              default: "disabled"
	            },
	            disabled: {
	              type: Boolean,
	              default: !1
	            },
	            fallbackInputClass: {
	              type: [Array, Object, String],
	              default: null
	            },
	            fallbackOkClass: {
	              type: [Array, Object, String],
	              default: null
	            },
	            fallbackOkText: {
	              type: String,
	              default: "Ok"
	            },
	            inline: {
	              type: Boolean,
	              default: !1
	            },
	            maxHeight: {
	              type: [Number, String],
	              default: null
	            },
	            shapes: {
	              type: String,
	              default: "squares"
	            },
	            popoverTo: {
	              type: String,
	              default: "right"
	            },
	            rowLength: {
	              type: [Number, String],
	              default: null
	            },
	            showBorder: {
	              type: Boolean,
	              default: null
	            },
	            showFallback: {
	              type: Boolean,
	              default: !1
	            },
	            showCheckbox: {
	              type: Boolean,
	              default: !0
	            },
	            swatchSize: {
	              type: [Number, String],
	              default: null
	            },
	            swatchStyle: {
	              type: [Object, Array],
	              default: function _default() {}
	            },
	            triggerStyle: {
	              type: [Object, Array],
	              default: function _default() {}
	            },
	            wrapperStyle: {
	              type: [Object, Array],
	              default: function _default() {}
	            },
	            value: {
	              type: String,
	              default: null
	            }
	          },
	          data: function data() {
	            return {
	              presetBorderRadius: null,
	              presetMaxHeight: null,
	              presetRowLength: null,
	              presetShowBorder: null,
	              presetSwatchSize: null,
	              presetSpacingSize: null,
	              internalValue: this.value,
	              internalIsOpen: !1
	            };
	          },
	          computed: {
	            isNested: function isNested() {
	              return !!(this.computedColors && this.computedColors.length > 0 && this.computedColors[0] instanceof Array);
	            },
	            isOpen: function isOpen() {
	              return !this.inline && this.internalIsOpen;
	            },
	            isNoColor: function isNoColor() {
	              return this.checkEquality("", this.internalValue);
	            },
	            computedColors: function computedColors() {
	              return this.colors instanceof Array ? this.colors : this.extractSwatchesFromPreset(this.colors);
	            },
	            computedBorderRadius: function computedBorderRadius() {
	              return null !== this.presetBorderRadius ? this.presetBorderRadius : this.borderRadius;
	            },
	            computedExceptionMode: function computedExceptionMode() {
	              return "hidden" === this.exceptionMode ? this.exceptionMode : "disabled" === this.exceptionMode ? this.exceptionMode : void 0;
	            },
	            computedMaxHeight: function computedMaxHeight() {
	              return null !== this.maxHeight ? Number(this.maxHeight) : null !== this.presetMaxHeight ? this.presetMaxHeight : 300;
	            },
	            computedRowLength: function computedRowLength() {
	              return null !== this.rowLength ? Number(this.rowLength) : null !== this.presetRowLength ? this.presetRowLength : 4;
	            },
	            computedSwatchSize: function computedSwatchSize() {
	              return null !== this.swatchSize ? Number(this.swatchSize) : null !== this.presetSwatchSize ? this.presetSwatchSize : 42;
	            },
	            computedSpacingSize: function computedSpacingSize() {
	              return null !== this.presetSpacingSize ? this.presetSpacingSize : this.spacingSize;
	            },
	            computedShowBorder: function computedShowBorder() {
	              return null !== this.showBorder ? this.showBorder : null !== this.presetShowBorder && this.presetShowBorder;
	            },
	            borderRadius: function borderRadius() {
	              return "squares" === this.shapes ? Math.round(.25 * this.computedSwatchSize) + "px" : "circles" === this.shapes ? "50%" : void 0;
	            },
	            spacingSize: function spacingSize() {
	              return Math.round(.25 * this.computedSwatchSize);
	            },
	            wrapperWidth: function wrapperWidth() {
	              return this.computedRowLength * (this.computedSwatchSize + this.computedSpacingSize);
	            },
	            computedtriggerStyle: function computedtriggerStyle() {
	              return {
	                width: "42px",
	                height: "42px",
	                backgroundColor: this.value ? this.value : "#ffffff",
	                borderRadius: "circles" === this.shapes ? "50%" : "10px"
	              };
	            },
	            triggerStyles: function triggerStyles() {
	              return [this.computedtriggerStyle, this.triggerStyle];
	            },
	            containerStyle: function containerStyle() {
	              var e = {
	                backgroundColor: this.backgroundColor
	              },
	                  t = {};
	              return this.inline ? e : ("right" === this.popoverTo ? t = {
	                left: 0
	              } : "left" === this.popoverTo && (t = {
	                right: 0
	              }), i()({}, t, e, {
	                maxHeight: this.computedMaxHeight + "px"
	              }));
	            },
	            containerStyles: function containerStyles() {
	              return [this.containerStyle];
	            },
	            computedWrapperStyle: function computedWrapperStyle() {
	              var e = {
	                paddingTop: this.computedSpacingSize + "px",
	                paddingLeft: this.computedSpacingSize + "px"
	              };
	              return this.inline ? e : i()({}, e, {
	                width: this.wrapperWidth + "px"
	              });
	            },
	            wrapperStyles: function wrapperStyles() {
	              return [this.computedWrapperStyle, this.wrapperStyle];
	            },
	            computedFallbackWrapperStyle: function computedFallbackWrapperStyle() {
	              var e = {
	                marginLeft: this.computedSpacingSize + "px",
	                paddingBottom: this.computedSpacingSize + "px"
	              };
	              return this.inline ? e : i()({}, e, {
	                width: this.wrapperWidth - this.computedSpacingSize + "px"
	              });
	            },
	            computedFallbackWrapperStyles: function computedFallbackWrapperStyles() {
	              return [this.computedFallbackWrapperStyle];
	            }
	          },
	          watch: {
	            value: function value(e) {
	              this.internalValue = e;
	            }
	          },
	          methods: {
	            checkEquality: function checkEquality(e, t) {
	              return !(!e && "" !== e || !t && "" !== t) && e.toUpperCase() === t.toUpperCase();
	            },
	            checkException: function checkException(e) {
	              return -1 !== this.exceptions.map(function (e) {
	                return e.toUpperCase();
	              }).indexOf(e.toUpperCase());
	            },
	            hidePopover: function hidePopover() {
	              this.internalIsOpen = !1, this.$el.blur(), this.$emit("close", this.internalValue);
	            },
	            onBlur: function onBlur(e) {
	              this.isOpen && (null !== e && this.$el.contains(e) || (this.internalIsOpen = !1, this.$emit("close", this.internalValue)));
	            },
	            onFallbackButtonClick: function onFallbackButtonClick() {
	              this.hidePopover();
	            },
	            showPopover: function showPopover() {
	              this.isOpen || this.inline || this.disabled || (this.internalIsOpen = !0, this.$el.focus(), this.$emit("open"));
	            },
	            togglePopover: function togglePopover() {
	              this.isOpen ? this.hidePopover() : this.showPopover();
	            },
	            updateSwatch: function updateSwatch(e) {
	              var t = (arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}).fromFallbackInput;
	              this.checkException(e) || this.disabled || (this.internalValue = e, this.$emit("input", e), !this.closeOnSelect || this.inline || t || this.hidePopover());
	            },
	            extractSwatchesFromPreset: function extractSwatchesFromPreset(e) {
	              var t;
	              return (t = e instanceof Object ? e : o[e]).borderRadius && (this.presetBorderRadius = t.borderRadius), t.maxHeight && (this.presetMaxHeight = t.maxHeight), t.rowLength && (this.presetRowLength = t.rowLength), t.showBorder && (this.presetShowBorder = t.showBorder), t.swatchSize && (this.presetSwatchSize = t.swatchSize), (0 === t.spacingSize || t.spacingSize) && (this.presetSpacingSize = t.spacingSize), t.swatches;
	            }
	          }
	        }, function () {
	          var e = this,
	              t = e.$createElement,
	              n = e._self._c || t;
	          return n("div", {
	            staticClass: "vue-swatches",
	            attrs: {
	              tabindex: "0"
	            },
	            on: {
	              blur: function blur(t) {
	                return t.target !== t.currentTarget ? null : (n = t, e.onBlur(n.relatedTarget));
	                var n;
	              }
	            }
	          }, [e.inline ? e._e() : n("div", {
	            ref: "trigger-wrapper",
	            on: {
	              click: e.togglePopover
	            }
	          }, [e._t("trigger", [n("div", {
	            staticClass: "vue-swatches__trigger",
	            class: {
	              "vue-swatches--is-empty": !e.value,
	              "vue-swatches--is-disabled": e.disabled
	            },
	            style: e.triggerStyles
	          }, [n("div", {
	            directives: [{
	              name: "show",
	              rawName: "v-show",
	              value: e.isNoColor,
	              expression: "isNoColor"
	            }],
	            staticClass: "vue-swatches__diagonal--wrapper vue-swatches--has-children-centered"
	          }, [n("div", {
	            staticClass: "vue-swatches__diagonal"
	          })])])])], 2), e._v(" "), n("transition", {
	            attrs: {
	              name: "vue-swatches-show-hide"
	            }
	          }, [n("div", {
	            directives: [{
	              name: "show",
	              rawName: "v-show",
	              value: e.inline || e.isOpen,
	              expression: "inline || isOpen"
	            }],
	            staticClass: "vue-swatches__container",
	            class: {
	              "vue-swatches--inline": e.inline
	            },
	            style: e.containerStyles
	          }, [n("div", {
	            staticClass: "vue-swatches__wrapper",
	            style: e.wrapperStyles
	          }, [e.isNested ? e._l(e.computedColors, function (t, r) {
	            return n("div", {
	              key: r,
	              staticClass: "vue-swatches__row"
	            }, e._l(t, function (t) {
	              return n("swatch", {
	                key: t,
	                attrs: {
	                  "border-radius": e.computedBorderRadius,
	                  disabled: e.disabled,
	                  "exception-mode": e.computedExceptionMode,
	                  "is-exception": e.checkException(t),
	                  selected: e.checkEquality(t, e.internalValue),
	                  size: e.computedSwatchSize,
	                  "spacing-size": e.computedSpacingSize,
	                  "show-border": e.computedShowBorder,
	                  "show-checkbox": e.showCheckbox,
	                  "swatch-color": t,
	                  "swatch-style": e.swatchStyle
	                },
	                nativeOn: {
	                  click: function click(n) {
	                    e.updateSwatch(t);
	                  }
	                }
	              });
	            }));
	          }) : e._l(e.computedColors, function (t) {
	            return n("swatch", {
	              key: t,
	              attrs: {
	                "border-radius": e.computedBorderRadius,
	                disabled: e.disabled,
	                "exception-mode": e.computedExceptionMode,
	                "is-exception": e.checkException(t),
	                selected: e.checkEquality(t, e.internalValue),
	                size: e.computedSwatchSize,
	                "spacing-size": e.computedSpacingSize,
	                "show-border": e.computedShowBorder,
	                "show-checkbox": e.showCheckbox,
	                "swatch-color": t,
	                "swatch-style": e.swatchStyle
	              },
	              nativeOn: {
	                click: function click(n) {
	                  e.updateSwatch(t);
	                }
	              }
	            });
	          })], 2), e._v(" "), e.showFallback ? n("div", {
	            staticClass: "vue-swatches__fallback__wrapper",
	            style: e.computedFallbackWrapperStyles
	          }, [n("span", {
	            staticClass: "vue-swatches__fallback__input--wrapper"
	          }, [n("input", {
	            ref: "fallbackInput",
	            staticClass: "vue-swatches__fallback__input",
	            class: e.fallbackInputClass,
	            attrs: {
	              type: "text"
	            },
	            domProps: {
	              value: e.internalValue
	            },
	            on: {
	              input: function input(t) {
	                return e.updateSwatch(t.target.value, {
	                  fromFallbackInput: !0
	                });
	              }
	            }
	          })]), e._v(" "), n("button", {
	            staticClass: "vue-swatches__fallback__button",
	            class: e.fallbackOkClass,
	            on: {
	              click: e.onFallbackButtonClick
	            }
	          }, [e._v("\n          " + e._s(e.fallbackOkText) + "\n        ")])]) : e._e()])])], 1);
	        }, [], !1, function (e) {
	          n("YtwX");
	        }, null, null).exports;
	        n.d(t, "Swatches", function () {
	          return c;
	        }), t.default = c;
	      },
	      w6GO: function w6GO(e, t, n) {
	        var r = n("5vMV"),
	            i = n("FpHa");

	        e.exports = Object.keys || function (e) {
	          return r(e, i);
	        };
	      },
	      xbiK: function xbiK(e, t) {},
	      xcGP: function xcGP(e, t) {}
	    });
	  });
	});
	var Swatches = unwrapExports(vueSwatches_min);
	var vueSwatches_min_1 = vueSwatches_min.VueSwatches;

	//
	var script$2 = {
	  components: {
	    BasketSelectDesktop: __vue_component__,
	    BasketSelectMobile: __vue_component__$1,
	    Swatches: Swatches
	  },
	  props: {
	    data: Array,
	    colors: Array,
	    defaultColor: String,
	    useShare: Boolean
	  },
	  data: function data() {
	    return {
	      windowWidth: 0,
	      hasInputError: false,
	      inputErrorMessage: '',
	      shareLink: '',
	      formData: {
	        code: '',
	        originalName: '',
	        name: '',
	        color: this.defaultColor
	      }
	    };
	  },
	  computed: {
	    messages: function messages() {
	      return BX.Vue.getFilteredPhrases('RS_VBASKET_');
	    },
	    modalTitle: function modalTitle() {
	      if (this.formData.code.trim() == '') {
	        return this.messages.RS_VBASKET_SELECT_NEW_BASKET;
	      } else {
	        return this.messages.RS_VBASKET_SELECT_EDIT_BASKET.replace('#NAME#', this.formData.originalName);
	      }
	    },
	    modalDescription: function modalDescription() {
	      if (this.formData.code.trim() == '') {
	        return this.messages.RS_VBASKET_SELECT_NEW_BASKET_DESCR;
	      } else {
	        return this.messages.RS_VBASKET_SELECT_EDIT_BASKET_DESCR;
	      }
	    },
	    isMobile: function isMobile() {
	      return this.windowWidth < 768;
	    },
	    selected: function selected() {
	      return this.data.find(function (item) {
	        return item.SELECTED;
	      });
	    }
	  },
	  created: function created() {
	    window.addEventListener('resize', this.handleResize);
	    this.handleResize();
	  },
	  mounted: function mounted() {
	    RS.Init(['bmd'], this.$refs.form);
	  },
	  destroyed: function destroyed() {
	    window.removeEventListener('resize', this.handleResize);
	  },
	  methods: {
	    handleResize: function handleResize() {
	      this.windowWidth = $(window).outerWidth();
	    },
	    createBasket: function createBasket() {
	      this.formData = {
	        code: '',
	        name: this.messages.RS_VBASKET_SELECT_BASKET + ' #' + (this.data.length + 1),
	        originalName: '',
	        color: this.defaultColor
	      };
	      RS.Utils.popup(this.$refs.form, 'popup', {
	        title: this.modalTitle
	      });
	    },
	    editBasket: function editBasket(basket) {
	      this.formData = {
	        code: basket.CODE,
	        name: basket['~NAME'],
	        originalName: basket['~NAME'],
	        color: basket.COLOR
	      };
	      RS.Utils.popup(this.$refs.form, 'popup', {
	        title: this.modalTitle
	      });
	    },
	    handleInput: function handleInput() {
	      if (this.hasInputError) {
	        if (this.$refs.input.checkValidity()) {
	          this.hasInputError = false;
	          this.inputErrorMessage = '';
	        } else {
	          this.inputErrorMessage = this.$refs.input.validationMessage;
	        }
	      }
	    },
	    runAction: function runAction(actionName, data) {
	      var action = 'redsign:vbasket.api.userbasket.' + actionName;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction(action, {
	          data: data
	        }).then(function (result) {
	          return result.data ? resolve() : reject(result);
	        }, reject);
	      });
	    },
	    saveAction: function () {
	      var _saveAction = babelHelpers.asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
	        var result;
	        return regeneratorRuntime.wrap(function _callee$(_context) {
	          while (1) {
	            switch (_context.prev = _context.next) {
	              case 0:
	                if (!this.$refs.input.checkValidity()) {
	                  _context.next = 15;
	                  break;
	                }

	                _context.prev = 1;

	                if ($.fancybox.getInstance()) {
	                  $.fancybox.close();
	                }

	                _context.next = 5;
	                return this.runAction('save', {
	                  code: this.formData.code,
	                  name: this.formData.name,
	                  color: this.formData.color
	                });

	              case 5:
	                result = _context.sent;
	                BX.reload();
	                _context.next = 13;
	                break;

	              case 9:
	                _context.prev = 9;
	                _context.t0 = _context["catch"](1);
	                BX.UI.Notification.Center.notify({
	                  content: this.messages.RS_VBASKET_SELECT_SAVE_ERROR
	                });
	                console.error(_context.t0);

	              case 13:
	                _context.next = 17;
	                break;

	              case 15:
	                this.hasInputError = true;
	                this.inputErrorMessage = this.$refs.input.validationMessage;

	              case 17:
	              case "end":
	                return _context.stop();
	            }
	          }
	        }, _callee, this, [[1, 9]]);
	      }));

	      function saveAction() {
	        return _saveAction.apply(this, arguments);
	      }

	      return saveAction;
	    }(),
	    removeAction: function () {
	      var _removeAction = babelHelpers.asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee2(code) {
	        return regeneratorRuntime.wrap(function _callee2$(_context2) {
	          while (1) {
	            switch (_context2.prev = _context2.next) {
	              case 0:
	                _context2.prev = 0;

	                if (!confirm(this.messages.RS_VBASKET_SELECT_ARE_YOUR_SURE)) {
	                  _context2.next = 5;
	                  break;
	                }

	                _context2.next = 4;
	                return this.runAction('remove', {
	                  code: code
	                });

	              case 4:
	                BX.reload();

	              case 5:
	                _context2.next = 11;
	                break;

	              case 7:
	                _context2.prev = 7;
	                _context2.t0 = _context2["catch"](0);
	                BX.UI.Notification.Center.notify({
	                  content: this.messages.RS_VBASKET_SELECT_REMOVE_ERROR
	                });
	                console.error(_context2.t0);

	              case 11:
	              case "end":
	                return _context2.stop();
	            }
	          }
	        }, _callee2, this, [[0, 7]]);
	      }));

	      function removeAction(_x) {
	        return _removeAction.apply(this, arguments);
	      }

	      return removeAction;
	    }(),
	    shareAction: function () {
	      var _shareAction = babelHelpers.asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee3(code) {
	        var result;
	        return regeneratorRuntime.wrap(function _callee3$(_context3) {
	          while (1) {
	            switch (_context3.prev = _context3.next) {
	              case 0:
	                _context3.next = 2;
	                return BX.ajax.runAction('redsign:vbasket.controller.sharecontroller.getLink', {
	                  data: {
	                    code: code
	                  }
	                });

	              case 2:
	                result = _context3.sent;

	                if ((result.data || {}).isSuccess) {
	                  this.shareLink = result.data.link;
	                }

	                RS.Utils.popup(this.$refs.sharing, 'popup', {
	                  title: this.messages.RS_VBASKET_SHARE_MODAL_TITLE
	                });

	              case 5:
	              case "end":
	                return _context3.stop();
	            }
	          }
	        }, _callee3, this);
	      }));

	      function shareAction(_x2) {
	        return _shareAction.apply(this, arguments);
	      }

	      return shareAction;
	    }(),
	    selectAction: function () {
	      var _selectAction = babelHelpers.asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee4(code) {
	        return regeneratorRuntime.wrap(function _callee4$(_context4) {
	          while (1) {
	            switch (_context4.prev = _context4.next) {
	              case 0:
	                _context4.next = 2;
	                return this.runAction('select', {
	                  code: code
	                });

	              case 2:
	                BX.reload();

	              case 3:
	              case "end":
	                return _context4.stop();
	            }
	          }
	        }, _callee4, this);
	      }));

	      function selectAction(_x3) {
	        return _selectAction.apply(this, arguments);
	      }

	      return selectAction;
	    }(),
	    copyShareLink: function copyShareLink() {
	      this.$refs.shareLinkInput.select();
	      this.$refs.shareLinkInput.focus();
	      document.execCommand('copy');
	    }
	  }
	};

	var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());

	function createInjector(context) {
	  return function (id, style) {
	    return addStyle(id, style);
	  };
	}

	var HEAD;
	var styles = {};

	function addStyle(id, css) {
	  var group = isOldIE ? css.media || 'default' : id;
	  var style = styles[group] || (styles[group] = {
	    ids: new Set(),
	    styles: []
	  });

	  if (!style.ids.has(id)) {
	    style.ids.add(id);
	    var code = css.source;

	    if (css.map) {
	      // https://developer.chrome.com/devtools/docs/javascript-debugging
	      // this makes source maps inside style tags work properly in Chrome
	      code += '\n/*# sourceURL=' + css.map.sources[0] + ' */'; // http://stackoverflow.com/a/26603875

	      code += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) + ' */';
	    }

	    if (!style.element) {
	      style.element = document.createElement('style');
	      style.element.type = 'text/css';
	      if (css.media) style.element.setAttribute('media', css.media);

	      if (HEAD === undefined) {
	        HEAD = document.head || document.getElementsByTagName('head')[0];
	      }

	      HEAD.appendChild(style.element);
	    }

	    if ('styleSheet' in style.element) {
	      style.styles.push(code);
	      style.element.styleSheet.cssText = style.styles.filter(Boolean).join('\n');
	    } else {
	      var index = style.ids.size - 1;
	      var textNode = document.createTextNode(code);
	      var nodes = style.element.childNodes;
	      if (nodes[index]) style.element.removeChild(nodes[index]);
	      if (nodes.length) style.element.insertBefore(textNode, nodes[index]);else style.element.appendChild(textNode);
	    }
	  }
	}

	/* script */
	var __vue_script__$2 = script$2;
	/* template */

	var __vue_render__$2 = function __vue_render__() {
	  var _vm = this;

	  var _h = _vm.$createElement;

	  var _c = _vm._self._c || _h;

	  return _c("div", [_vm.isMobile ? [_c("basket-select-mobile", {
	    attrs: {
	      data: _vm.data,
	      useShare: _vm.useShare
	    },
	    on: {
	      create: function create($event) {
	        return _vm.createBasket();
	      },
	      select: _vm.selectAction,
	      edit: _vm.editBasket,
	      remove: _vm.removeAction,
	      share: _vm.shareAction
	    }
	  })] : [_c("basket-select-desktop", {
	    attrs: {
	      data: _vm.data,
	      useShare: _vm.useShare
	    },
	    on: {
	      create: function create($event) {
	        return _vm.createBasket();
	      },
	      select: _vm.selectAction,
	      edit: _vm.editBasket,
	      remove: _vm.removeAction,
	      share: _vm.shareAction
	    }
	  })], _vm._v(" "), _c("div", {
	    staticStyle: {
	      display: "none"
	    }
	  }, [_c("div", {
	    ref: "form",
	    attrs: {
	      title: _vm.modalTitle
	    }
	  }, [_c("div", {
	    staticClass: "vbasket-modal"
	  }, [_c("p", {
	    staticClass: "mt-3"
	  }, [_vm._v(" " + _vm._s(_vm.modalDescription) + " ")]), _vm._v(" "), _c("div", {
	    staticClass: "form-group bmd-form-group",
	    class: {
	      "has-error": _vm.hasInputError,
	      "is-filled": _vm.formData.name.length > 0
	    },
	    staticStyle: {
	      margin: "0",
	      flex: "1"
	    }
	  }, [_c("label", {
	    staticClass: "bmd-label-floating",
	    attrs: {
	      for: "FIELD_NAME"
	    }
	  }, [_vm._v(_vm._s(_vm.messages.RS_VBASKET_SELECT_BASKET_NAME_PLACEHOLDER))]), _vm._v(" "), _c("input", {
	    directives: [{
	      name: "model",
	      rawName: "v-model",
	      value: _vm.formData.name,
	      expression: "formData.name"
	    }],
	    ref: "input",
	    staticClass: "bmd-form-control",
	    attrs: {
	      type: "text",
	      minlength: "3",
	      maxlength: "20",
	      required: ""
	    },
	    domProps: {
	      value: _vm.formData.name
	    },
	    on: {
	      input: [function ($event) {
	        if ($event.target.composing) {
	          return;
	        }

	        _vm.$set(_vm.formData, "name", $event.target.value);
	      }, _vm.handleInput],
	      keyup: function keyup($event) {
	        if (!$event.type.indexOf("key") && _vm._k($event.keyCode, "enter", 13, $event.key, "Enter")) {
	          return null;
	        }

	        return _vm.saveAction($event);
	      }
	    }
	  }), _vm._v(" "), _vm.hasInputError ? _c("span", {
	    staticClass: "help-block",
	    attrs: {
	      id: "helpBlock2"
	    }
	  }, [_vm._v(_vm._s(_vm.inputErrorMessage))]) : _vm._e()]), _vm._v(" "), _c("div", {
	    staticClass: "form-group bmd-form-group"
	  }, [_c("swatches", {
	    ref: "swatches",
	    staticStyle: {
	      margin: "0 -19px",
	      "line-height": "1"
	    },
	    attrs: {
	      "popover-to": "left",
	      "swatch-size": "35.5",
	      colors: _vm.colors,
	      "swatch-style": {
	        "border-radius": "4px",
	        padding: 0,
	        margin: "0 5px 5px"
	      },
	      inline: ""
	    },
	    model: {
	      value: _vm.formData.color,
	      callback: function callback($$v) {
	        _vm.$set(_vm.formData, "color", $$v);
	      },
	      expression: "formData.color"
	    }
	  })], 1), _vm._v(" "), _c("div", {
	    staticClass: "d-block clearfix mt-5"
	  }, [_c("button", {
	    staticClass: "btn btn-primary float-right",
	    attrs: {
	      type: "button"
	    },
	    on: {
	      click: _vm.saveAction
	    }
	  }, [_vm._v(" " + _vm._s(_vm.messages.RS_VBASKET_SELECT_SAVE) + " ")])])])])]), _vm._v(" "), _vm.useShare ? _c("div", {
	    staticStyle: {
	      display: "none"
	    }
	  }, [_c("div", {
	    ref: "sharing",
	    attrs: {
	      title: "sharing"
	    }
	  }, [_c("div", {
	    staticClass: "vbasket-modal"
	  }, [_c("div", {
	    staticClass: "pt-5"
	  }, [_c("label", [_vm._v(_vm._s(_vm.messages.RS_VBASKET_SHARE_MODAL_LABEL))]), _vm._v(" "), _c("div", {
	    staticClass: "d-flex"
	  }, [_c("input", {
	    directives: [{
	      name: "model",
	      rawName: "v-model",
	      value: _vm.shareLink,
	      expression: "shareLink"
	    }],
	    ref: "shareLinkInput",
	    staticClass: "form-control",
	    attrs: {
	      type: "text"
	    },
	    domProps: {
	      value: _vm.shareLink
	    },
	    on: {
	      input: function input($event) {
	        if ($event.target.composing) {
	          return;
	        }

	        _vm.shareLink = $event.target.value;
	      }
	    }
	  }), _vm._v(" "), _c("button", {
	    staticClass: "btn btn-primary ml-2",
	    on: {
	      click: function click($event) {
	        return _vm.copyShareLink();
	      }
	    }
	  }, [_vm._v(" " + _vm._s(_vm.messages.RS_VBASKET_SHARE_MODAL_COPY_LINK) + " ")])])])])])]) : _vm._e()], 2);
	};

	var __vue_staticRenderFns__$2 = [];
	__vue_render__$2._withStripped = true;
	/* style */

	var __vue_inject_styles__$2 = function __vue_inject_styles__(inject) {
	  if (!inject) return;
	  inject("data-v-133f2805_0", {
	    source: "\n.vue-swatches__wrapper {\r\n\tbox-sizing: content-box;\n}\r\n",
	    map: undefined,
	    media: undefined
	  });
	};
	/* scoped */


	var __vue_scope_id__$2 = undefined;
	/* module identifier */

	var __vue_module_identifier__$2 = undefined;
	/* functional template */

	var __vue_is_functional_template__$2 = false;
	/* style inject SSR */

	/* style inject shadow dom */

	var __vue_component__$2 = normalizeComponent({
	  render: __vue_render__$2,
	  staticRenderFns: __vue_staticRenderFns__$2
	}, __vue_inject_styles__$2, __vue_script__$2, __vue_scope_id__$2, __vue_is_functional_template__$2, __vue_module_identifier__$2, false, createInjector, undefined, undefined);

	var Select = /*#__PURE__*/function () {
	  function Select(el, data, params) {
	    babelHelpers.classCallCheck(this, Select);
	    this.$el = el;
	    this.data = data;
	    this.params = params;
	    this.attachTemplate();
	  }

	  babelHelpers.createClass(Select, [{
	    key: "attachTemplate",
	    value: function attachTemplate() {
	      var _data = this.data;
	      var colors = this.params.colors;
	      var defaultColor = this.params.defaultColor;
	      var useShare = this.params.useShare;
	      this.template = BX.Vue.create({
	        el: this.$el,
	        components: {
	          BasketSelect: __vue_component__$2
	        },
	        data: function data() {
	          return {
	            data: _data,
	            colors: colors,
	            defaultColor: defaultColor,
	            useShare: useShare
	          };
	        },
	        template: "<basket-select v-bind=\"{ data, colors, defaultColor, useShare }\"/>"
	      });
	    }
	  }]);
	  return Select;
	}();

	exports.Select = Select;

}((this.VBasket.Components = this.VBasket.Components || {})));
//# sourceMappingURL=component.js.map
