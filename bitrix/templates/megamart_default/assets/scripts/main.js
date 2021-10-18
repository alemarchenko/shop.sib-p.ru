(function ($$1) {
	'use strict';

	$$1 = $$1 && Object.prototype.hasOwnProperty.call($$1, 'default') ? $$1['default'] : $$1;

	function lazyImages (context) {
	  $(context).find('[data-lazy-img]').Lazy({
	    effect: "fadeIn",
	    effectTime: 300,
	    afterLoad: function afterLoad($item) {
	      $item.removeClass('lazy-anim-bg lazy-anim-img').removeAttr('data-lazy-img');
	    }
	  });
	}

	function _typeof(obj) {
	  "@babel/helpers - typeof";

	  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
	    _typeof = function (obj) {
	      return typeof obj;
	    };
	  } else {
	    _typeof = function (obj) {
	      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
	    };
	  }

	  return _typeof(obj);
	}

	function _classCallCheck(instance, Constructor) {
	  if (!(instance instanceof Constructor)) {
	    throw new TypeError("Cannot call a class as a function");
	  }
	}

	function _defineProperties(target, props) {
	  for (var i = 0; i < props.length; i++) {
	    var descriptor = props[i];
	    descriptor.enumerable = descriptor.enumerable || false;
	    descriptor.configurable = true;
	    if ("value" in descriptor) descriptor.writable = true;
	    Object.defineProperty(target, descriptor.key, descriptor);
	  }
	}

	function _createClass(Constructor, protoProps, staticProps) {
	  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
	  if (staticProps) _defineProperties(Constructor, staticProps);
	  return Constructor;
	}

	function _inherits(subClass, superClass) {
	  if (typeof superClass !== "function" && superClass !== null) {
	    throw new TypeError("Super expression must either be null or a function");
	  }

	  subClass.prototype = Object.create(superClass && superClass.prototype, {
	    constructor: {
	      value: subClass,
	      writable: true,
	      configurable: true
	    }
	  });
	  if (superClass) _setPrototypeOf(subClass, superClass);
	}

	function _getPrototypeOf(o) {
	  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
	    return o.__proto__ || Object.getPrototypeOf(o);
	  };
	  return _getPrototypeOf(o);
	}

	function _setPrototypeOf(o, p) {
	  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
	    o.__proto__ = p;
	    return o;
	  };

	  return _setPrototypeOf(o, p);
	}

	function _isNativeReflectConstruct() {
	  if (typeof Reflect === "undefined" || !Reflect.construct) return false;
	  if (Reflect.construct.sham) return false;
	  if (typeof Proxy === "function") return true;

	  try {
	    Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {}));
	    return true;
	  } catch (e) {
	    return false;
	  }
	}

	function _assertThisInitialized(self) {
	  if (self === void 0) {
	    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
	  }

	  return self;
	}

	function _possibleConstructorReturn(self, call) {
	  if (call && (typeof call === "object" || typeof call === "function")) {
	    return call;
	  }

	  return _assertThisInitialized(self);
	}

	function _createSuper(Derived) {
	  var hasNativeReflectConstruct = _isNativeReflectConstruct();

	  return function _createSuperInternal() {
	    var Super = _getPrototypeOf(Derived),
	        result;

	    if (hasNativeReflectConstruct) {
	      var NewTarget = _getPrototypeOf(this).constructor;

	      result = Reflect.construct(Super, arguments, NewTarget);
	    } else {
	      result = Super.apply(this, arguments);
	    }

	    return _possibleConstructorReturn(this, result);
	  };
	}

	function _superPropBase(object, property) {
	  while (!Object.prototype.hasOwnProperty.call(object, property)) {
	    object = _getPrototypeOf(object);
	    if (object === null) break;
	  }

	  return object;
	}

	function _get(target, property, receiver) {
	  if (typeof Reflect !== "undefined" && Reflect.get) {
	    _get = Reflect.get;
	  } else {
	    _get = function _get(target, property, receiver) {
	      var base = _superPropBase(target, property);

	      if (!base) return;
	      var desc = Object.getOwnPropertyDescriptor(base, property);

	      if (desc.get) {
	        return desc.get.call(receiver);
	      }

	      return desc.value;
	    };
	  }

	  return _get(target, property, receiver || target);
	}

	var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

	function createCommonjsModule(fn, module) {
		return module = { exports: {} }, fn(module, module.exports), module.exports;
	}

	/** Detect free variable `global` from Node.js. */

	var freeGlobal = _typeof(commonjsGlobal) == 'object' && commonjsGlobal && commonjsGlobal.Object === Object && commonjsGlobal;
	var _freeGlobal = freeGlobal;

	/** Detect free variable `self`. */

	var freeSelf = (typeof self === "undefined" ? "undefined" : _typeof(self)) == 'object' && self && self.Object === Object && self;
	/** Used as a reference to the global object. */

	var root = _freeGlobal || freeSelf || Function('return this')();
	var _root = root;

	/** Built-in value references. */

	var _Symbol2 = _root.Symbol;
	var _Symbol = _Symbol2;

	/** Used for built-in method references. */

	var objectProto = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty = objectProto.hasOwnProperty;
	/**
	 * Used to resolve the
	 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
	 * of values.
	 */

	var nativeObjectToString = objectProto.toString;
	/** Built-in value references. */

	var symToStringTag = _Symbol ? _Symbol.toStringTag : undefined;
	/**
	 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
	 *
	 * @private
	 * @param {*} value The value to query.
	 * @returns {string} Returns the raw `toStringTag`.
	 */

	function getRawTag(value) {
	  var isOwn = hasOwnProperty.call(value, symToStringTag),
	      tag = value[symToStringTag];

	  try {
	    value[symToStringTag] = undefined;
	    var unmasked = true;
	  } catch (e) {}

	  var result = nativeObjectToString.call(value);

	  if (unmasked) {
	    if (isOwn) {
	      value[symToStringTag] = tag;
	    } else {
	      delete value[symToStringTag];
	    }
	  }

	  return result;
	}

	var _getRawTag = getRawTag;

	/** Used for built-in method references. */
	var objectProto$1 = Object.prototype;
	/**
	 * Used to resolve the
	 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
	 * of values.
	 */

	var nativeObjectToString$1 = objectProto$1.toString;
	/**
	 * Converts `value` to a string using `Object.prototype.toString`.
	 *
	 * @private
	 * @param {*} value The value to convert.
	 * @returns {string} Returns the converted string.
	 */

	function objectToString(value) {
	  return nativeObjectToString$1.call(value);
	}

	var _objectToString = objectToString;

	/** `Object#toString` result references. */

	var nullTag = '[object Null]',
	    undefinedTag = '[object Undefined]';
	/** Built-in value references. */

	var symToStringTag$1 = _Symbol ? _Symbol.toStringTag : undefined;
	/**
	 * The base implementation of `getTag` without fallbacks for buggy environments.
	 *
	 * @private
	 * @param {*} value The value to query.
	 * @returns {string} Returns the `toStringTag`.
	 */

	function baseGetTag(value) {
	  if (value == null) {
	    return value === undefined ? undefinedTag : nullTag;
	  }

	  return symToStringTag$1 && symToStringTag$1 in Object(value) ? _getRawTag(value) : _objectToString(value);
	}

	var _baseGetTag = baseGetTag;

	/**
	 * Checks if `value` is classified as an `Array` object.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.1.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
	 * @example
	 *
	 * _.isArray([1, 2, 3]);
	 * // => true
	 *
	 * _.isArray(document.body.children);
	 * // => false
	 *
	 * _.isArray('abc');
	 * // => false
	 *
	 * _.isArray(_.noop);
	 * // => false
	 */
	var isArray = Array.isArray;
	var isArray_1 = isArray;

	/**
	 * Checks if `value` is object-like. A value is object-like if it's not `null`
	 * and has a `typeof` result of "object".
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
	 * @example
	 *
	 * _.isObjectLike({});
	 * // => true
	 *
	 * _.isObjectLike([1, 2, 3]);
	 * // => true
	 *
	 * _.isObjectLike(_.noop);
	 * // => false
	 *
	 * _.isObjectLike(null);
	 * // => false
	 */
	function isObjectLike(value) {
	  return value != null && _typeof(value) == 'object';
	}

	var isObjectLike_1 = isObjectLike;

	/** `Object#toString` result references. */

	var stringTag = '[object String]';
	/**
	 * Checks if `value` is classified as a `String` primitive or object.
	 *
	 * @static
	 * @since 0.1.0
	 * @memberOf _
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a string, else `false`.
	 * @example
	 *
	 * _.isString('abc');
	 * // => true
	 *
	 * _.isString(1);
	 * // => false
	 */

	function isString(value) {
	  return typeof value == 'string' || !isArray_1(value) && isObjectLike_1(value) && _baseGetTag(value) == stringTag;
	}

	var isString_1 = isString;

	/**
	 * Checks if `value` is the
	 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
	 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
	 *
	 * @static
	 * @memberOf _
	 * @since 0.1.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
	 * @example
	 *
	 * _.isObject({});
	 * // => true
	 *
	 * _.isObject([1, 2, 3]);
	 * // => true
	 *
	 * _.isObject(_.noop);
	 * // => true
	 *
	 * _.isObject(null);
	 * // => false
	 */
	function isObject(value) {
	  var type = _typeof(value);

	  return value != null && (type == 'object' || type == 'function');
	}

	var isObject_1 = isObject;

	function _parseOptions (options) {
	  return isString_1(options) && options.trim() != '' ? $$1.parseJSON(options) : isObject_1(options) ? options : {};
	}

	/**
	 * Removes all key-value entries from the list cache.
	 *
	 * @private
	 * @name clear
	 * @memberOf ListCache
	 */
	function listCacheClear() {
	  this.__data__ = [];
	  this.size = 0;
	}

	var _listCacheClear = listCacheClear;

	/**
	 * Performs a
	 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
	 * comparison between two values to determine if they are equivalent.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to compare.
	 * @param {*} other The other value to compare.
	 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
	 * @example
	 *
	 * var object = { 'a': 1 };
	 * var other = { 'a': 1 };
	 *
	 * _.eq(object, object);
	 * // => true
	 *
	 * _.eq(object, other);
	 * // => false
	 *
	 * _.eq('a', 'a');
	 * // => true
	 *
	 * _.eq('a', Object('a'));
	 * // => false
	 *
	 * _.eq(NaN, NaN);
	 * // => true
	 */
	function eq(value, other) {
	  return value === other || value !== value && other !== other;
	}

	var eq_1 = eq;

	/**
	 * Gets the index at which the `key` is found in `array` of key-value pairs.
	 *
	 * @private
	 * @param {Array} array The array to inspect.
	 * @param {*} key The key to search for.
	 * @returns {number} Returns the index of the matched value, else `-1`.
	 */

	function assocIndexOf(array, key) {
	  var length = array.length;

	  while (length--) {
	    if (eq_1(array[length][0], key)) {
	      return length;
	    }
	  }

	  return -1;
	}

	var _assocIndexOf = assocIndexOf;

	/** Used for built-in method references. */

	var arrayProto = Array.prototype;
	/** Built-in value references. */

	var splice = arrayProto.splice;
	/**
	 * Removes `key` and its value from the list cache.
	 *
	 * @private
	 * @name delete
	 * @memberOf ListCache
	 * @param {string} key The key of the value to remove.
	 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
	 */

	function listCacheDelete(key) {
	  var data = this.__data__,
	      index = _assocIndexOf(data, key);

	  if (index < 0) {
	    return false;
	  }

	  var lastIndex = data.length - 1;

	  if (index == lastIndex) {
	    data.pop();
	  } else {
	    splice.call(data, index, 1);
	  }

	  --this.size;
	  return true;
	}

	var _listCacheDelete = listCacheDelete;

	/**
	 * Gets the list cache value for `key`.
	 *
	 * @private
	 * @name get
	 * @memberOf ListCache
	 * @param {string} key The key of the value to get.
	 * @returns {*} Returns the entry value.
	 */

	function listCacheGet(key) {
	  var data = this.__data__,
	      index = _assocIndexOf(data, key);
	  return index < 0 ? undefined : data[index][1];
	}

	var _listCacheGet = listCacheGet;

	/**
	 * Checks if a list cache value for `key` exists.
	 *
	 * @private
	 * @name has
	 * @memberOf ListCache
	 * @param {string} key The key of the entry to check.
	 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
	 */

	function listCacheHas(key) {
	  return _assocIndexOf(this.__data__, key) > -1;
	}

	var _listCacheHas = listCacheHas;

	/**
	 * Sets the list cache `key` to `value`.
	 *
	 * @private
	 * @name set
	 * @memberOf ListCache
	 * @param {string} key The key of the value to set.
	 * @param {*} value The value to set.
	 * @returns {Object} Returns the list cache instance.
	 */

	function listCacheSet(key, value) {
	  var data = this.__data__,
	      index = _assocIndexOf(data, key);

	  if (index < 0) {
	    ++this.size;
	    data.push([key, value]);
	  } else {
	    data[index][1] = value;
	  }

	  return this;
	}

	var _listCacheSet = listCacheSet;

	/**
	 * Creates an list cache object.
	 *
	 * @private
	 * @constructor
	 * @param {Array} [entries] The key-value pairs to cache.
	 */

	function ListCache(entries) {
	  var index = -1,
	      length = entries == null ? 0 : entries.length;
	  this.clear();

	  while (++index < length) {
	    var entry = entries[index];
	    this.set(entry[0], entry[1]);
	  }
	} // Add methods to `ListCache`.


	ListCache.prototype.clear = _listCacheClear;
	ListCache.prototype['delete'] = _listCacheDelete;
	ListCache.prototype.get = _listCacheGet;
	ListCache.prototype.has = _listCacheHas;
	ListCache.prototype.set = _listCacheSet;
	var _ListCache = ListCache;

	/**
	 * Removes all key-value entries from the stack.
	 *
	 * @private
	 * @name clear
	 * @memberOf Stack
	 */

	function stackClear() {
	  this.__data__ = new _ListCache();
	  this.size = 0;
	}

	var _stackClear = stackClear;

	/**
	 * Removes `key` and its value from the stack.
	 *
	 * @private
	 * @name delete
	 * @memberOf Stack
	 * @param {string} key The key of the value to remove.
	 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
	 */
	function stackDelete(key) {
	  var data = this.__data__,
	      result = data['delete'](key);
	  this.size = data.size;
	  return result;
	}

	var _stackDelete = stackDelete;

	/**
	 * Gets the stack value for `key`.
	 *
	 * @private
	 * @name get
	 * @memberOf Stack
	 * @param {string} key The key of the value to get.
	 * @returns {*} Returns the entry value.
	 */
	function stackGet(key) {
	  return this.__data__.get(key);
	}

	var _stackGet = stackGet;

	/**
	 * Checks if a stack value for `key` exists.
	 *
	 * @private
	 * @name has
	 * @memberOf Stack
	 * @param {string} key The key of the entry to check.
	 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
	 */
	function stackHas(key) {
	  return this.__data__.has(key);
	}

	var _stackHas = stackHas;

	/** `Object#toString` result references. */

	var asyncTag = '[object AsyncFunction]',
	    funcTag = '[object Function]',
	    genTag = '[object GeneratorFunction]',
	    proxyTag = '[object Proxy]';
	/**
	 * Checks if `value` is classified as a `Function` object.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.1.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a function, else `false`.
	 * @example
	 *
	 * _.isFunction(_);
	 * // => true
	 *
	 * _.isFunction(/abc/);
	 * // => false
	 */

	function isFunction(value) {
	  if (!isObject_1(value)) {
	    return false;
	  } // The use of `Object#toString` avoids issues with the `typeof` operator
	  // in Safari 9 which returns 'object' for typed arrays and other constructors.


	  var tag = _baseGetTag(value);
	  return tag == funcTag || tag == genTag || tag == asyncTag || tag == proxyTag;
	}

	var isFunction_1 = isFunction;

	/** Used to detect overreaching core-js shims. */

	var coreJsData = _root['__core-js_shared__'];
	var _coreJsData = coreJsData;

	/** Used to detect methods masquerading as native. */

	var maskSrcKey = function () {
	  var uid = /[^.]+$/.exec(_coreJsData && _coreJsData.keys && _coreJsData.keys.IE_PROTO || '');
	  return uid ? 'Symbol(src)_1.' + uid : '';
	}();
	/**
	 * Checks if `func` has its source masked.
	 *
	 * @private
	 * @param {Function} func The function to check.
	 * @returns {boolean} Returns `true` if `func` is masked, else `false`.
	 */


	function isMasked(func) {
	  return !!maskSrcKey && maskSrcKey in func;
	}

	var _isMasked = isMasked;

	/** Used for built-in method references. */
	var funcProto = Function.prototype;
	/** Used to resolve the decompiled source of functions. */

	var funcToString = funcProto.toString;
	/**
	 * Converts `func` to its source code.
	 *
	 * @private
	 * @param {Function} func The function to convert.
	 * @returns {string} Returns the source code.
	 */

	function toSource(func) {
	  if (func != null) {
	    try {
	      return funcToString.call(func);
	    } catch (e) {}

	    try {
	      return func + '';
	    } catch (e) {}
	  }

	  return '';
	}

	var _toSource = toSource;

	/**
	 * Used to match `RegExp`
	 * [syntax characters](http://ecma-international.org/ecma-262/7.0/#sec-patterns).
	 */

	var reRegExpChar = /[\\^$.*+?()[\]{}|]/g;
	/** Used to detect host constructors (Safari). */

	var reIsHostCtor = /^\[object .+?Constructor\]$/;
	/** Used for built-in method references. */

	var funcProto$1 = Function.prototype,
	    objectProto$2 = Object.prototype;
	/** Used to resolve the decompiled source of functions. */

	var funcToString$1 = funcProto$1.toString;
	/** Used to check objects for own properties. */

	var hasOwnProperty$1 = objectProto$2.hasOwnProperty;
	/** Used to detect if a method is native. */

	var reIsNative = RegExp('^' + funcToString$1.call(hasOwnProperty$1).replace(reRegExpChar, '\\$&').replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$');
	/**
	 * The base implementation of `_.isNative` without bad shim checks.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a native function,
	 *  else `false`.
	 */

	function baseIsNative(value) {
	  if (!isObject_1(value) || _isMasked(value)) {
	    return false;
	  }

	  var pattern = isFunction_1(value) ? reIsNative : reIsHostCtor;
	  return pattern.test(_toSource(value));
	}

	var _baseIsNative = baseIsNative;

	/**
	 * Gets the value at `key` of `object`.
	 *
	 * @private
	 * @param {Object} [object] The object to query.
	 * @param {string} key The key of the property to get.
	 * @returns {*} Returns the property value.
	 */
	function getValue(object, key) {
	  return object == null ? undefined : object[key];
	}

	var _getValue = getValue;

	/**
	 * Gets the native function at `key` of `object`.
	 *
	 * @private
	 * @param {Object} object The object to query.
	 * @param {string} key The key of the method to get.
	 * @returns {*} Returns the function if it's native, else `undefined`.
	 */

	function getNative(object, key) {
	  var value = _getValue(object, key);
	  return _baseIsNative(value) ? value : undefined;
	}

	var _getNative = getNative;

	/* Built-in method references that are verified to be native. */

	var Map = _getNative(_root, 'Map');
	var _Map = Map;

	/* Built-in method references that are verified to be native. */

	var nativeCreate = _getNative(Object, 'create');
	var _nativeCreate = nativeCreate;

	/**
	 * Removes all key-value entries from the hash.
	 *
	 * @private
	 * @name clear
	 * @memberOf Hash
	 */

	function hashClear() {
	  this.__data__ = _nativeCreate ? _nativeCreate(null) : {};
	  this.size = 0;
	}

	var _hashClear = hashClear;

	/**
	 * Removes `key` and its value from the hash.
	 *
	 * @private
	 * @name delete
	 * @memberOf Hash
	 * @param {Object} hash The hash to modify.
	 * @param {string} key The key of the value to remove.
	 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
	 */
	function hashDelete(key) {
	  var result = this.has(key) && delete this.__data__[key];
	  this.size -= result ? 1 : 0;
	  return result;
	}

	var _hashDelete = hashDelete;

	/** Used to stand-in for `undefined` hash values. */

	var HASH_UNDEFINED = '__lodash_hash_undefined__';
	/** Used for built-in method references. */

	var objectProto$3 = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$2 = objectProto$3.hasOwnProperty;
	/**
	 * Gets the hash value for `key`.
	 *
	 * @private
	 * @name get
	 * @memberOf Hash
	 * @param {string} key The key of the value to get.
	 * @returns {*} Returns the entry value.
	 */

	function hashGet(key) {
	  var data = this.__data__;

	  if (_nativeCreate) {
	    var result = data[key];
	    return result === HASH_UNDEFINED ? undefined : result;
	  }

	  return hasOwnProperty$2.call(data, key) ? data[key] : undefined;
	}

	var _hashGet = hashGet;

	/** Used for built-in method references. */

	var objectProto$4 = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$3 = objectProto$4.hasOwnProperty;
	/**
	 * Checks if a hash value for `key` exists.
	 *
	 * @private
	 * @name has
	 * @memberOf Hash
	 * @param {string} key The key of the entry to check.
	 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
	 */

	function hashHas(key) {
	  var data = this.__data__;
	  return _nativeCreate ? data[key] !== undefined : hasOwnProperty$3.call(data, key);
	}

	var _hashHas = hashHas;

	/** Used to stand-in for `undefined` hash values. */

	var HASH_UNDEFINED$1 = '__lodash_hash_undefined__';
	/**
	 * Sets the hash `key` to `value`.
	 *
	 * @private
	 * @name set
	 * @memberOf Hash
	 * @param {string} key The key of the value to set.
	 * @param {*} value The value to set.
	 * @returns {Object} Returns the hash instance.
	 */

	function hashSet(key, value) {
	  var data = this.__data__;
	  this.size += this.has(key) ? 0 : 1;
	  data[key] = _nativeCreate && value === undefined ? HASH_UNDEFINED$1 : value;
	  return this;
	}

	var _hashSet = hashSet;

	/**
	 * Creates a hash object.
	 *
	 * @private
	 * @constructor
	 * @param {Array} [entries] The key-value pairs to cache.
	 */

	function Hash(entries) {
	  var index = -1,
	      length = entries == null ? 0 : entries.length;
	  this.clear();

	  while (++index < length) {
	    var entry = entries[index];
	    this.set(entry[0], entry[1]);
	  }
	} // Add methods to `Hash`.


	Hash.prototype.clear = _hashClear;
	Hash.prototype['delete'] = _hashDelete;
	Hash.prototype.get = _hashGet;
	Hash.prototype.has = _hashHas;
	Hash.prototype.set = _hashSet;
	var _Hash = Hash;

	/**
	 * Removes all key-value entries from the map.
	 *
	 * @private
	 * @name clear
	 * @memberOf MapCache
	 */

	function mapCacheClear() {
	  this.size = 0;
	  this.__data__ = {
	    'hash': new _Hash(),
	    'map': new (_Map || _ListCache)(),
	    'string': new _Hash()
	  };
	}

	var _mapCacheClear = mapCacheClear;

	/**
	 * Checks if `value` is suitable for use as unique object key.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is suitable, else `false`.
	 */
	function isKeyable(value) {
	  var type = _typeof(value);

	  return type == 'string' || type == 'number' || type == 'symbol' || type == 'boolean' ? value !== '__proto__' : value === null;
	}

	var _isKeyable = isKeyable;

	/**
	 * Gets the data for `map`.
	 *
	 * @private
	 * @param {Object} map The map to query.
	 * @param {string} key The reference key.
	 * @returns {*} Returns the map data.
	 */

	function getMapData(map, key) {
	  var data = map.__data__;
	  return _isKeyable(key) ? data[typeof key == 'string' ? 'string' : 'hash'] : data.map;
	}

	var _getMapData = getMapData;

	/**
	 * Removes `key` and its value from the map.
	 *
	 * @private
	 * @name delete
	 * @memberOf MapCache
	 * @param {string} key The key of the value to remove.
	 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
	 */

	function mapCacheDelete(key) {
	  var result = _getMapData(this, key)['delete'](key);
	  this.size -= result ? 1 : 0;
	  return result;
	}

	var _mapCacheDelete = mapCacheDelete;

	/**
	 * Gets the map value for `key`.
	 *
	 * @private
	 * @name get
	 * @memberOf MapCache
	 * @param {string} key The key of the value to get.
	 * @returns {*} Returns the entry value.
	 */

	function mapCacheGet(key) {
	  return _getMapData(this, key).get(key);
	}

	var _mapCacheGet = mapCacheGet;

	/**
	 * Checks if a map value for `key` exists.
	 *
	 * @private
	 * @name has
	 * @memberOf MapCache
	 * @param {string} key The key of the entry to check.
	 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
	 */

	function mapCacheHas(key) {
	  return _getMapData(this, key).has(key);
	}

	var _mapCacheHas = mapCacheHas;

	/**
	 * Sets the map `key` to `value`.
	 *
	 * @private
	 * @name set
	 * @memberOf MapCache
	 * @param {string} key The key of the value to set.
	 * @param {*} value The value to set.
	 * @returns {Object} Returns the map cache instance.
	 */

	function mapCacheSet(key, value) {
	  var data = _getMapData(this, key),
	      size = data.size;
	  data.set(key, value);
	  this.size += data.size == size ? 0 : 1;
	  return this;
	}

	var _mapCacheSet = mapCacheSet;

	/**
	 * Creates a map cache object to store key-value pairs.
	 *
	 * @private
	 * @constructor
	 * @param {Array} [entries] The key-value pairs to cache.
	 */

	function MapCache(entries) {
	  var index = -1,
	      length = entries == null ? 0 : entries.length;
	  this.clear();

	  while (++index < length) {
	    var entry = entries[index];
	    this.set(entry[0], entry[1]);
	  }
	} // Add methods to `MapCache`.


	MapCache.prototype.clear = _mapCacheClear;
	MapCache.prototype['delete'] = _mapCacheDelete;
	MapCache.prototype.get = _mapCacheGet;
	MapCache.prototype.has = _mapCacheHas;
	MapCache.prototype.set = _mapCacheSet;
	var _MapCache = MapCache;

	/** Used as the size to enable large array optimizations. */

	var LARGE_ARRAY_SIZE = 200;
	/**
	 * Sets the stack `key` to `value`.
	 *
	 * @private
	 * @name set
	 * @memberOf Stack
	 * @param {string} key The key of the value to set.
	 * @param {*} value The value to set.
	 * @returns {Object} Returns the stack cache instance.
	 */

	function stackSet(key, value) {
	  var data = this.__data__;

	  if (data instanceof _ListCache) {
	    var pairs = data.__data__;

	    if (!_Map || pairs.length < LARGE_ARRAY_SIZE - 1) {
	      pairs.push([key, value]);
	      this.size = ++data.size;
	      return this;
	    }

	    data = this.__data__ = new _MapCache(pairs);
	  }

	  data.set(key, value);
	  this.size = data.size;
	  return this;
	}

	var _stackSet = stackSet;

	/**
	 * Creates a stack cache object to store key-value pairs.
	 *
	 * @private
	 * @constructor
	 * @param {Array} [entries] The key-value pairs to cache.
	 */

	function Stack(entries) {
	  var data = this.__data__ = new _ListCache(entries);
	  this.size = data.size;
	} // Add methods to `Stack`.


	Stack.prototype.clear = _stackClear;
	Stack.prototype['delete'] = _stackDelete;
	Stack.prototype.get = _stackGet;
	Stack.prototype.has = _stackHas;
	Stack.prototype.set = _stackSet;
	var _Stack = Stack;

	var defineProperty = function () {
	  try {
	    var func = _getNative(Object, 'defineProperty');
	    func({}, '', {});
	    return func;
	  } catch (e) {}
	}();

	var _defineProperty = defineProperty;

	/**
	 * The base implementation of `assignValue` and `assignMergeValue` without
	 * value checks.
	 *
	 * @private
	 * @param {Object} object The object to modify.
	 * @param {string} key The key of the property to assign.
	 * @param {*} value The value to assign.
	 */

	function baseAssignValue(object, key, value) {
	  if (key == '__proto__' && _defineProperty) {
	    _defineProperty(object, key, {
	      'configurable': true,
	      'enumerable': true,
	      'value': value,
	      'writable': true
	    });
	  } else {
	    object[key] = value;
	  }
	}

	var _baseAssignValue = baseAssignValue;

	/**
	 * This function is like `assignValue` except that it doesn't assign
	 * `undefined` values.
	 *
	 * @private
	 * @param {Object} object The object to modify.
	 * @param {string} key The key of the property to assign.
	 * @param {*} value The value to assign.
	 */

	function assignMergeValue(object, key, value) {
	  if (value !== undefined && !eq_1(object[key], value) || value === undefined && !(key in object)) {
	    _baseAssignValue(object, key, value);
	  }
	}

	var _assignMergeValue = assignMergeValue;

	/**
	 * Creates a base function for methods like `_.forIn` and `_.forOwn`.
	 *
	 * @private
	 * @param {boolean} [fromRight] Specify iterating from right to left.
	 * @returns {Function} Returns the new base function.
	 */
	function createBaseFor(fromRight) {
	  return function (object, iteratee, keysFunc) {
	    var index = -1,
	        iterable = Object(object),
	        props = keysFunc(object),
	        length = props.length;

	    while (length--) {
	      var key = props[fromRight ? length : ++index];

	      if (iteratee(iterable[key], key, iterable) === false) {
	        break;
	      }
	    }

	    return object;
	  };
	}

	var _createBaseFor = createBaseFor;

	/**
	 * The base implementation of `baseForOwn` which iterates over `object`
	 * properties returned by `keysFunc` and invokes `iteratee` for each property.
	 * Iteratee functions may exit iteration early by explicitly returning `false`.
	 *
	 * @private
	 * @param {Object} object The object to iterate over.
	 * @param {Function} iteratee The function invoked per iteration.
	 * @param {Function} keysFunc The function to get the keys of `object`.
	 * @returns {Object} Returns `object`.
	 */

	var baseFor = _createBaseFor();
	var _baseFor = baseFor;

	var _cloneBuffer = createCommonjsModule(function (module, exports) {
	  /** Detect free variable `exports`. */
	  var freeExports =  exports && !exports.nodeType && exports;
	  /** Detect free variable `module`. */

	  var freeModule = freeExports && 'object' == 'object' && module && !module.nodeType && module;
	  /** Detect the popular CommonJS extension `module.exports`. */

	  var moduleExports = freeModule && freeModule.exports === freeExports;
	  /** Built-in value references. */

	  var Buffer = moduleExports ? _root.Buffer : undefined,
	      allocUnsafe = Buffer ? Buffer.allocUnsafe : undefined;
	  /**
	   * Creates a clone of  `buffer`.
	   *
	   * @private
	   * @param {Buffer} buffer The buffer to clone.
	   * @param {boolean} [isDeep] Specify a deep clone.
	   * @returns {Buffer} Returns the cloned buffer.
	   */

	  function cloneBuffer(buffer, isDeep) {
	    if (isDeep) {
	      return buffer.slice();
	    }

	    var length = buffer.length,
	        result = allocUnsafe ? allocUnsafe(length) : new buffer.constructor(length);
	    buffer.copy(result);
	    return result;
	  }

	  module.exports = cloneBuffer;
	});

	/** Built-in value references. */

	var Uint8Array = _root.Uint8Array;
	var _Uint8Array = Uint8Array;

	/**
	 * Creates a clone of `arrayBuffer`.
	 *
	 * @private
	 * @param {ArrayBuffer} arrayBuffer The array buffer to clone.
	 * @returns {ArrayBuffer} Returns the cloned array buffer.
	 */

	function cloneArrayBuffer(arrayBuffer) {
	  var result = new arrayBuffer.constructor(arrayBuffer.byteLength);
	  new _Uint8Array(result).set(new _Uint8Array(arrayBuffer));
	  return result;
	}

	var _cloneArrayBuffer = cloneArrayBuffer;

	/**
	 * Creates a clone of `typedArray`.
	 *
	 * @private
	 * @param {Object} typedArray The typed array to clone.
	 * @param {boolean} [isDeep] Specify a deep clone.
	 * @returns {Object} Returns the cloned typed array.
	 */

	function cloneTypedArray(typedArray, isDeep) {
	  var buffer = isDeep ? _cloneArrayBuffer(typedArray.buffer) : typedArray.buffer;
	  return new typedArray.constructor(buffer, typedArray.byteOffset, typedArray.length);
	}

	var _cloneTypedArray = cloneTypedArray;

	/**
	 * Copies the values of `source` to `array`.
	 *
	 * @private
	 * @param {Array} source The array to copy values from.
	 * @param {Array} [array=[]] The array to copy values to.
	 * @returns {Array} Returns `array`.
	 */
	function copyArray(source, array) {
	  var index = -1,
	      length = source.length;
	  array || (array = Array(length));

	  while (++index < length) {
	    array[index] = source[index];
	  }

	  return array;
	}

	var _copyArray = copyArray;

	/** Built-in value references. */

	var objectCreate = Object.create;
	/**
	 * The base implementation of `_.create` without support for assigning
	 * properties to the created object.
	 *
	 * @private
	 * @param {Object} proto The object to inherit from.
	 * @returns {Object} Returns the new object.
	 */

	var baseCreate = function () {
	  function object() {}

	  return function (proto) {
	    if (!isObject_1(proto)) {
	      return {};
	    }

	    if (objectCreate) {
	      return objectCreate(proto);
	    }

	    object.prototype = proto;
	    var result = new object();
	    object.prototype = undefined;
	    return result;
	  };
	}();

	var _baseCreate = baseCreate;

	/**
	 * Creates a unary function that invokes `func` with its argument transformed.
	 *
	 * @private
	 * @param {Function} func The function to wrap.
	 * @param {Function} transform The argument transform.
	 * @returns {Function} Returns the new function.
	 */
	function overArg(func, transform) {
	  return function (arg) {
	    return func(transform(arg));
	  };
	}

	var _overArg = overArg;

	/** Built-in value references. */

	var getPrototype = _overArg(Object.getPrototypeOf, Object);
	var _getPrototype = getPrototype;

	/** Used for built-in method references. */
	var objectProto$5 = Object.prototype;
	/**
	 * Checks if `value` is likely a prototype object.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
	 */

	function isPrototype(value) {
	  var Ctor = value && value.constructor,
	      proto = typeof Ctor == 'function' && Ctor.prototype || objectProto$5;
	  return value === proto;
	}

	var _isPrototype = isPrototype;

	/**
	 * Initializes an object clone.
	 *
	 * @private
	 * @param {Object} object The object to clone.
	 * @returns {Object} Returns the initialized clone.
	 */

	function initCloneObject(object) {
	  return typeof object.constructor == 'function' && !_isPrototype(object) ? _baseCreate(_getPrototype(object)) : {};
	}

	var _initCloneObject = initCloneObject;

	/** `Object#toString` result references. */

	var argsTag = '[object Arguments]';
	/**
	 * The base implementation of `_.isArguments`.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
	 */

	function baseIsArguments(value) {
	  return isObjectLike_1(value) && _baseGetTag(value) == argsTag;
	}

	var _baseIsArguments = baseIsArguments;

	/** Used for built-in method references. */

	var objectProto$6 = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$4 = objectProto$6.hasOwnProperty;
	/** Built-in value references. */

	var propertyIsEnumerable = objectProto$6.propertyIsEnumerable;
	/**
	 * Checks if `value` is likely an `arguments` object.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.1.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
	 *  else `false`.
	 * @example
	 *
	 * _.isArguments(function() { return arguments; }());
	 * // => true
	 *
	 * _.isArguments([1, 2, 3]);
	 * // => false
	 */

	var isArguments = _baseIsArguments(function () {
	  return arguments;
	}()) ? _baseIsArguments : function (value) {
	  return isObjectLike_1(value) && hasOwnProperty$4.call(value, 'callee') && !propertyIsEnumerable.call(value, 'callee');
	};
	var isArguments_1 = isArguments;

	/** Used as references for various `Number` constants. */
	var MAX_SAFE_INTEGER = 9007199254740991;
	/**
	 * Checks if `value` is a valid array-like length.
	 *
	 * **Note:** This method is loosely based on
	 * [`ToLength`](http://ecma-international.org/ecma-262/7.0/#sec-tolength).
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
	 * @example
	 *
	 * _.isLength(3);
	 * // => true
	 *
	 * _.isLength(Number.MIN_VALUE);
	 * // => false
	 *
	 * _.isLength(Infinity);
	 * // => false
	 *
	 * _.isLength('3');
	 * // => false
	 */

	function isLength(value) {
	  return typeof value == 'number' && value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
	}

	var isLength_1 = isLength;

	/**
	 * Checks if `value` is array-like. A value is considered array-like if it's
	 * not a function and has a `value.length` that's an integer greater than or
	 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
	 * @example
	 *
	 * _.isArrayLike([1, 2, 3]);
	 * // => true
	 *
	 * _.isArrayLike(document.body.children);
	 * // => true
	 *
	 * _.isArrayLike('abc');
	 * // => true
	 *
	 * _.isArrayLike(_.noop);
	 * // => false
	 */

	function isArrayLike(value) {
	  return value != null && isLength_1(value.length) && !isFunction_1(value);
	}

	var isArrayLike_1 = isArrayLike;

	/**
	 * This method is like `_.isArrayLike` except that it also checks if `value`
	 * is an object.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is an array-like object,
	 *  else `false`.
	 * @example
	 *
	 * _.isArrayLikeObject([1, 2, 3]);
	 * // => true
	 *
	 * _.isArrayLikeObject(document.body.children);
	 * // => true
	 *
	 * _.isArrayLikeObject('abc');
	 * // => false
	 *
	 * _.isArrayLikeObject(_.noop);
	 * // => false
	 */

	function isArrayLikeObject(value) {
	  return isObjectLike_1(value) && isArrayLike_1(value);
	}

	var isArrayLikeObject_1 = isArrayLikeObject;

	/**
	 * This method returns `false`.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.13.0
	 * @category Util
	 * @returns {boolean} Returns `false`.
	 * @example
	 *
	 * _.times(2, _.stubFalse);
	 * // => [false, false]
	 */
	function stubFalse() {
	  return false;
	}

	var stubFalse_1 = stubFalse;

	var isBuffer_1 = createCommonjsModule(function (module, exports) {
	  /** Detect free variable `exports`. */
	  var freeExports =  exports && !exports.nodeType && exports;
	  /** Detect free variable `module`. */

	  var freeModule = freeExports && 'object' == 'object' && module && !module.nodeType && module;
	  /** Detect the popular CommonJS extension `module.exports`. */

	  var moduleExports = freeModule && freeModule.exports === freeExports;
	  /** Built-in value references. */

	  var Buffer = moduleExports ? _root.Buffer : undefined;
	  /* Built-in method references for those with the same name as other `lodash` methods. */

	  var nativeIsBuffer = Buffer ? Buffer.isBuffer : undefined;
	  /**
	   * Checks if `value` is a buffer.
	   *
	   * @static
	   * @memberOf _
	   * @since 4.3.0
	   * @category Lang
	   * @param {*} value The value to check.
	   * @returns {boolean} Returns `true` if `value` is a buffer, else `false`.
	   * @example
	   *
	   * _.isBuffer(new Buffer(2));
	   * // => true
	   *
	   * _.isBuffer(new Uint8Array(2));
	   * // => false
	   */

	  var isBuffer = nativeIsBuffer || stubFalse_1;
	  module.exports = isBuffer;
	});

	/** `Object#toString` result references. */

	var objectTag = '[object Object]';
	/** Used for built-in method references. */

	var funcProto$2 = Function.prototype,
	    objectProto$7 = Object.prototype;
	/** Used to resolve the decompiled source of functions. */

	var funcToString$2 = funcProto$2.toString;
	/** Used to check objects for own properties. */

	var hasOwnProperty$5 = objectProto$7.hasOwnProperty;
	/** Used to infer the `Object` constructor. */

	var objectCtorString = funcToString$2.call(Object);
	/**
	 * Checks if `value` is a plain object, that is, an object created by the
	 * `Object` constructor or one with a `[[Prototype]]` of `null`.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.8.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 * }
	 *
	 * _.isPlainObject(new Foo);
	 * // => false
	 *
	 * _.isPlainObject([1, 2, 3]);
	 * // => false
	 *
	 * _.isPlainObject({ 'x': 0, 'y': 0 });
	 * // => true
	 *
	 * _.isPlainObject(Object.create(null));
	 * // => true
	 */

	function isPlainObject(value) {
	  if (!isObjectLike_1(value) || _baseGetTag(value) != objectTag) {
	    return false;
	  }

	  var proto = _getPrototype(value);

	  if (proto === null) {
	    return true;
	  }

	  var Ctor = hasOwnProperty$5.call(proto, 'constructor') && proto.constructor;
	  return typeof Ctor == 'function' && Ctor instanceof Ctor && funcToString$2.call(Ctor) == objectCtorString;
	}

	var isPlainObject_1 = isPlainObject;

	/** `Object#toString` result references. */

	var argsTag$1 = '[object Arguments]',
	    arrayTag = '[object Array]',
	    boolTag = '[object Boolean]',
	    dateTag = '[object Date]',
	    errorTag = '[object Error]',
	    funcTag$1 = '[object Function]',
	    mapTag = '[object Map]',
	    numberTag = '[object Number]',
	    objectTag$1 = '[object Object]',
	    regexpTag = '[object RegExp]',
	    setTag = '[object Set]',
	    stringTag$1 = '[object String]',
	    weakMapTag = '[object WeakMap]';
	var arrayBufferTag = '[object ArrayBuffer]',
	    dataViewTag = '[object DataView]',
	    float32Tag = '[object Float32Array]',
	    float64Tag = '[object Float64Array]',
	    int8Tag = '[object Int8Array]',
	    int16Tag = '[object Int16Array]',
	    int32Tag = '[object Int32Array]',
	    uint8Tag = '[object Uint8Array]',
	    uint8ClampedTag = '[object Uint8ClampedArray]',
	    uint16Tag = '[object Uint16Array]',
	    uint32Tag = '[object Uint32Array]';
	/** Used to identify `toStringTag` values of typed arrays. */

	var typedArrayTags = {};
	typedArrayTags[float32Tag] = typedArrayTags[float64Tag] = typedArrayTags[int8Tag] = typedArrayTags[int16Tag] = typedArrayTags[int32Tag] = typedArrayTags[uint8Tag] = typedArrayTags[uint8ClampedTag] = typedArrayTags[uint16Tag] = typedArrayTags[uint32Tag] = true;
	typedArrayTags[argsTag$1] = typedArrayTags[arrayTag] = typedArrayTags[arrayBufferTag] = typedArrayTags[boolTag] = typedArrayTags[dataViewTag] = typedArrayTags[dateTag] = typedArrayTags[errorTag] = typedArrayTags[funcTag$1] = typedArrayTags[mapTag] = typedArrayTags[numberTag] = typedArrayTags[objectTag$1] = typedArrayTags[regexpTag] = typedArrayTags[setTag] = typedArrayTags[stringTag$1] = typedArrayTags[weakMapTag] = false;
	/**
	 * The base implementation of `_.isTypedArray` without Node.js optimizations.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
	 */

	function baseIsTypedArray(value) {
	  return isObjectLike_1(value) && isLength_1(value.length) && !!typedArrayTags[_baseGetTag(value)];
	}

	var _baseIsTypedArray = baseIsTypedArray;

	/**
	 * The base implementation of `_.unary` without support for storing metadata.
	 *
	 * @private
	 * @param {Function} func The function to cap arguments for.
	 * @returns {Function} Returns the new capped function.
	 */
	function baseUnary(func) {
	  return function (value) {
	    return func(value);
	  };
	}

	var _baseUnary = baseUnary;

	var _nodeUtil = createCommonjsModule(function (module, exports) {
	  /** Detect free variable `exports`. */
	  var freeExports =  exports && !exports.nodeType && exports;
	  /** Detect free variable `module`. */

	  var freeModule = freeExports && 'object' == 'object' && module && !module.nodeType && module;
	  /** Detect the popular CommonJS extension `module.exports`. */

	  var moduleExports = freeModule && freeModule.exports === freeExports;
	  /** Detect free variable `process` from Node.js. */

	  var freeProcess = moduleExports && _freeGlobal.process;
	  /** Used to access faster Node.js helpers. */

	  var nodeUtil = function () {
	    try {
	      // Use `util.types` for Node.js 10+.
	      var types = freeModule && freeModule.require && freeModule.require('util').types;

	      if (types) {
	        return types;
	      } // Legacy `process.binding('util')` for Node.js < 10.


	      return freeProcess && freeProcess.binding && freeProcess.binding('util');
	    } catch (e) {}
	  }();

	  module.exports = nodeUtil;
	});

	/* Node.js helper references. */

	var nodeIsTypedArray = _nodeUtil && _nodeUtil.isTypedArray;
	/**
	 * Checks if `value` is classified as a typed array.
	 *
	 * @static
	 * @memberOf _
	 * @since 3.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
	 * @example
	 *
	 * _.isTypedArray(new Uint8Array);
	 * // => true
	 *
	 * _.isTypedArray([]);
	 * // => false
	 */

	var isTypedArray = nodeIsTypedArray ? _baseUnary(nodeIsTypedArray) : _baseIsTypedArray;
	var isTypedArray_1 = isTypedArray;

	/**
	 * Gets the value at `key`, unless `key` is "__proto__" or "constructor".
	 *
	 * @private
	 * @param {Object} object The object to query.
	 * @param {string} key The key of the property to get.
	 * @returns {*} Returns the property value.
	 */
	function safeGet(object, key) {
	  if (key === 'constructor' && typeof object[key] === 'function') {
	    return;
	  }

	  if (key == '__proto__') {
	    return;
	  }

	  return object[key];
	}

	var _safeGet = safeGet;

	/** Used for built-in method references. */

	var objectProto$8 = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$6 = objectProto$8.hasOwnProperty;
	/**
	 * Assigns `value` to `key` of `object` if the existing value is not equivalent
	 * using [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
	 * for equality comparisons.
	 *
	 * @private
	 * @param {Object} object The object to modify.
	 * @param {string} key The key of the property to assign.
	 * @param {*} value The value to assign.
	 */

	function assignValue(object, key, value) {
	  var objValue = object[key];

	  if (!(hasOwnProperty$6.call(object, key) && eq_1(objValue, value)) || value === undefined && !(key in object)) {
	    _baseAssignValue(object, key, value);
	  }
	}

	var _assignValue = assignValue;

	/**
	 * Copies properties of `source` to `object`.
	 *
	 * @private
	 * @param {Object} source The object to copy properties from.
	 * @param {Array} props The property identifiers to copy.
	 * @param {Object} [object={}] The object to copy properties to.
	 * @param {Function} [customizer] The function to customize copied values.
	 * @returns {Object} Returns `object`.
	 */

	function copyObject(source, props, object, customizer) {
	  var isNew = !object;
	  object || (object = {});
	  var index = -1,
	      length = props.length;

	  while (++index < length) {
	    var key = props[index];
	    var newValue = customizer ? customizer(object[key], source[key], key, object, source) : undefined;

	    if (newValue === undefined) {
	      newValue = source[key];
	    }

	    if (isNew) {
	      _baseAssignValue(object, key, newValue);
	    } else {
	      _assignValue(object, key, newValue);
	    }
	  }

	  return object;
	}

	var _copyObject = copyObject;

	/**
	 * The base implementation of `_.times` without support for iteratee shorthands
	 * or max array length checks.
	 *
	 * @private
	 * @param {number} n The number of times to invoke `iteratee`.
	 * @param {Function} iteratee The function invoked per iteration.
	 * @returns {Array} Returns the array of results.
	 */
	function baseTimes(n, iteratee) {
	  var index = -1,
	      result = Array(n);

	  while (++index < n) {
	    result[index] = iteratee(index);
	  }

	  return result;
	}

	var _baseTimes = baseTimes;

	/** Used as references for various `Number` constants. */
	var MAX_SAFE_INTEGER$1 = 9007199254740991;
	/** Used to detect unsigned integer values. */

	var reIsUint = /^(?:0|[1-9]\d*)$/;
	/**
	 * Checks if `value` is a valid array-like index.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
	 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
	 */

	function isIndex(value, length) {
	  var type = _typeof(value);

	  length = length == null ? MAX_SAFE_INTEGER$1 : length;
	  return !!length && (type == 'number' || type != 'symbol' && reIsUint.test(value)) && value > -1 && value % 1 == 0 && value < length;
	}

	var _isIndex = isIndex;

	/** Used for built-in method references. */

	var objectProto$9 = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$7 = objectProto$9.hasOwnProperty;
	/**
	 * Creates an array of the enumerable property names of the array-like `value`.
	 *
	 * @private
	 * @param {*} value The value to query.
	 * @param {boolean} inherited Specify returning inherited property names.
	 * @returns {Array} Returns the array of property names.
	 */

	function arrayLikeKeys(value, inherited) {
	  var isArr = isArray_1(value),
	      isArg = !isArr && isArguments_1(value),
	      isBuff = !isArr && !isArg && isBuffer_1(value),
	      isType = !isArr && !isArg && !isBuff && isTypedArray_1(value),
	      skipIndexes = isArr || isArg || isBuff || isType,
	      result = skipIndexes ? _baseTimes(value.length, String) : [],
	      length = result.length;

	  for (var key in value) {
	    if ((inherited || hasOwnProperty$7.call(value, key)) && !(skipIndexes && ( // Safari 9 has enumerable `arguments.length` in strict mode.
	    key == 'length' || // Node.js 0.10 has enumerable non-index properties on buffers.
	    isBuff && (key == 'offset' || key == 'parent') || // PhantomJS 2 has enumerable non-index properties on typed arrays.
	    isType && (key == 'buffer' || key == 'byteLength' || key == 'byteOffset') || // Skip index properties.
	    _isIndex(key, length)))) {
	      result.push(key);
	    }
	  }

	  return result;
	}

	var _arrayLikeKeys = arrayLikeKeys;

	/**
	 * This function is like
	 * [`Object.keys`](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
	 * except that it includes inherited enumerable properties.
	 *
	 * @private
	 * @param {Object} object The object to query.
	 * @returns {Array} Returns the array of property names.
	 */
	function nativeKeysIn(object) {
	  var result = [];

	  if (object != null) {
	    for (var key in Object(object)) {
	      result.push(key);
	    }
	  }

	  return result;
	}

	var _nativeKeysIn = nativeKeysIn;

	/** Used for built-in method references. */

	var objectProto$a = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$8 = objectProto$a.hasOwnProperty;
	/**
	 * The base implementation of `_.keysIn` which doesn't treat sparse arrays as dense.
	 *
	 * @private
	 * @param {Object} object The object to query.
	 * @returns {Array} Returns the array of property names.
	 */

	function baseKeysIn(object) {
	  if (!isObject_1(object)) {
	    return _nativeKeysIn(object);
	  }

	  var isProto = _isPrototype(object),
	      result = [];

	  for (var key in object) {
	    if (!(key == 'constructor' && (isProto || !hasOwnProperty$8.call(object, key)))) {
	      result.push(key);
	    }
	  }

	  return result;
	}

	var _baseKeysIn = baseKeysIn;

	/**
	 * Creates an array of the own and inherited enumerable property names of `object`.
	 *
	 * **Note:** Non-object values are coerced to objects.
	 *
	 * @static
	 * @memberOf _
	 * @since 3.0.0
	 * @category Object
	 * @param {Object} object The object to query.
	 * @returns {Array} Returns the array of property names.
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 *   this.b = 2;
	 * }
	 *
	 * Foo.prototype.c = 3;
	 *
	 * _.keysIn(new Foo);
	 * // => ['a', 'b', 'c'] (iteration order is not guaranteed)
	 */

	function keysIn(object) {
	  return isArrayLike_1(object) ? _arrayLikeKeys(object, true) : _baseKeysIn(object);
	}

	var keysIn_1 = keysIn;

	/**
	 * Converts `value` to a plain object flattening inherited enumerable string
	 * keyed properties of `value` to own properties of the plain object.
	 *
	 * @static
	 * @memberOf _
	 * @since 3.0.0
	 * @category Lang
	 * @param {*} value The value to convert.
	 * @returns {Object} Returns the converted plain object.
	 * @example
	 *
	 * function Foo() {
	 *   this.b = 2;
	 * }
	 *
	 * Foo.prototype.c = 3;
	 *
	 * _.assign({ 'a': 1 }, new Foo);
	 * // => { 'a': 1, 'b': 2 }
	 *
	 * _.assign({ 'a': 1 }, _.toPlainObject(new Foo));
	 * // => { 'a': 1, 'b': 2, 'c': 3 }
	 */

	function toPlainObject(value) {
	  return _copyObject(value, keysIn_1(value));
	}

	var toPlainObject_1 = toPlainObject;

	/**
	 * A specialized version of `baseMerge` for arrays and objects which performs
	 * deep merges and tracks traversed objects enabling objects with circular
	 * references to be merged.
	 *
	 * @private
	 * @param {Object} object The destination object.
	 * @param {Object} source The source object.
	 * @param {string} key The key of the value to merge.
	 * @param {number} srcIndex The index of `source`.
	 * @param {Function} mergeFunc The function to merge values.
	 * @param {Function} [customizer] The function to customize assigned values.
	 * @param {Object} [stack] Tracks traversed source values and their merged
	 *  counterparts.
	 */

	function baseMergeDeep(object, source, key, srcIndex, mergeFunc, customizer, stack) {
	  var objValue = _safeGet(object, key),
	      srcValue = _safeGet(source, key),
	      stacked = stack.get(srcValue);

	  if (stacked) {
	    _assignMergeValue(object, key, stacked);
	    return;
	  }

	  var newValue = customizer ? customizer(objValue, srcValue, key + '', object, source, stack) : undefined;
	  var isCommon = newValue === undefined;

	  if (isCommon) {
	    var isArr = isArray_1(srcValue),
	        isBuff = !isArr && isBuffer_1(srcValue),
	        isTyped = !isArr && !isBuff && isTypedArray_1(srcValue);
	    newValue = srcValue;

	    if (isArr || isBuff || isTyped) {
	      if (isArray_1(objValue)) {
	        newValue = objValue;
	      } else if (isArrayLikeObject_1(objValue)) {
	        newValue = _copyArray(objValue);
	      } else if (isBuff) {
	        isCommon = false;
	        newValue = _cloneBuffer(srcValue, true);
	      } else if (isTyped) {
	        isCommon = false;
	        newValue = _cloneTypedArray(srcValue, true);
	      } else {
	        newValue = [];
	      }
	    } else if (isPlainObject_1(srcValue) || isArguments_1(srcValue)) {
	      newValue = objValue;

	      if (isArguments_1(objValue)) {
	        newValue = toPlainObject_1(objValue);
	      } else if (!isObject_1(objValue) || isFunction_1(objValue)) {
	        newValue = _initCloneObject(srcValue);
	      }
	    } else {
	      isCommon = false;
	    }
	  }

	  if (isCommon) {
	    // Recursively merge objects and arrays (susceptible to call stack limits).
	    stack.set(srcValue, newValue);
	    mergeFunc(newValue, srcValue, srcIndex, customizer, stack);
	    stack['delete'](srcValue);
	  }

	  _assignMergeValue(object, key, newValue);
	}

	var _baseMergeDeep = baseMergeDeep;

	/**
	 * The base implementation of `_.merge` without support for multiple sources.
	 *
	 * @private
	 * @param {Object} object The destination object.
	 * @param {Object} source The source object.
	 * @param {number} srcIndex The index of `source`.
	 * @param {Function} [customizer] The function to customize merged values.
	 * @param {Object} [stack] Tracks traversed source values and their merged
	 *  counterparts.
	 */

	function baseMerge(object, source, srcIndex, customizer, stack) {
	  if (object === source) {
	    return;
	  }

	  _baseFor(source, function (srcValue, key) {
	    stack || (stack = new _Stack());

	    if (isObject_1(srcValue)) {
	      _baseMergeDeep(object, source, key, srcIndex, baseMerge, customizer, stack);
	    } else {
	      var newValue = customizer ? customizer(_safeGet(object, key), srcValue, key + '', object, source, stack) : undefined;

	      if (newValue === undefined) {
	        newValue = srcValue;
	      }

	      _assignMergeValue(object, key, newValue);
	    }
	  }, keysIn_1);
	}

	var _baseMerge = baseMerge;

	/**
	 * This method returns the first argument it receives.
	 *
	 * @static
	 * @since 0.1.0
	 * @memberOf _
	 * @category Util
	 * @param {*} value Any value.
	 * @returns {*} Returns `value`.
	 * @example
	 *
	 * var object = { 'a': 1 };
	 *
	 * console.log(_.identity(object) === object);
	 * // => true
	 */
	function identity(value) {
	  return value;
	}

	var identity_1 = identity;

	/**
	 * A faster alternative to `Function#apply`, this function invokes `func`
	 * with the `this` binding of `thisArg` and the arguments of `args`.
	 *
	 * @private
	 * @param {Function} func The function to invoke.
	 * @param {*} thisArg The `this` binding of `func`.
	 * @param {Array} args The arguments to invoke `func` with.
	 * @returns {*} Returns the result of `func`.
	 */
	function apply(func, thisArg, args) {
	  switch (args.length) {
	    case 0:
	      return func.call(thisArg);

	    case 1:
	      return func.call(thisArg, args[0]);

	    case 2:
	      return func.call(thisArg, args[0], args[1]);

	    case 3:
	      return func.call(thisArg, args[0], args[1], args[2]);
	  }

	  return func.apply(thisArg, args);
	}

	var _apply = apply;

	/* Built-in method references for those with the same name as other `lodash` methods. */

	var nativeMax = Math.max;
	/**
	 * A specialized version of `baseRest` which transforms the rest array.
	 *
	 * @private
	 * @param {Function} func The function to apply a rest parameter to.
	 * @param {number} [start=func.length-1] The start position of the rest parameter.
	 * @param {Function} transform The rest array transform.
	 * @returns {Function} Returns the new function.
	 */

	function overRest(func, start, transform) {
	  start = nativeMax(start === undefined ? func.length - 1 : start, 0);
	  return function () {
	    var args = arguments,
	        index = -1,
	        length = nativeMax(args.length - start, 0),
	        array = Array(length);

	    while (++index < length) {
	      array[index] = args[start + index];
	    }

	    index = -1;
	    var otherArgs = Array(start + 1);

	    while (++index < start) {
	      otherArgs[index] = args[index];
	    }

	    otherArgs[start] = transform(array);
	    return _apply(func, this, otherArgs);
	  };
	}

	var _overRest = overRest;

	/**
	 * Creates a function that returns `value`.
	 *
	 * @static
	 * @memberOf _
	 * @since 2.4.0
	 * @category Util
	 * @param {*} value The value to return from the new function.
	 * @returns {Function} Returns the new constant function.
	 * @example
	 *
	 * var objects = _.times(2, _.constant({ 'a': 1 }));
	 *
	 * console.log(objects);
	 * // => [{ 'a': 1 }, { 'a': 1 }]
	 *
	 * console.log(objects[0] === objects[1]);
	 * // => true
	 */
	function constant(value) {
	  return function () {
	    return value;
	  };
	}

	var constant_1 = constant;

	/**
	 * The base implementation of `setToString` without support for hot loop shorting.
	 *
	 * @private
	 * @param {Function} func The function to modify.
	 * @param {Function} string The `toString` result.
	 * @returns {Function} Returns `func`.
	 */

	var baseSetToString = !_defineProperty ? identity_1 : function (func, string) {
	  return _defineProperty(func, 'toString', {
	    'configurable': true,
	    'enumerable': false,
	    'value': constant_1(string),
	    'writable': true
	  });
	};
	var _baseSetToString = baseSetToString;

	/** Used to detect hot functions by number of calls within a span of milliseconds. */
	var HOT_COUNT = 800,
	    HOT_SPAN = 16;
	/* Built-in method references for those with the same name as other `lodash` methods. */

	var nativeNow = Date.now;
	/**
	 * Creates a function that'll short out and invoke `identity` instead
	 * of `func` when it's called `HOT_COUNT` or more times in `HOT_SPAN`
	 * milliseconds.
	 *
	 * @private
	 * @param {Function} func The function to restrict.
	 * @returns {Function} Returns the new shortable function.
	 */

	function shortOut(func) {
	  var count = 0,
	      lastCalled = 0;
	  return function () {
	    var stamp = nativeNow(),
	        remaining = HOT_SPAN - (stamp - lastCalled);
	    lastCalled = stamp;

	    if (remaining > 0) {
	      if (++count >= HOT_COUNT) {
	        return arguments[0];
	      }
	    } else {
	      count = 0;
	    }

	    return func.apply(undefined, arguments);
	  };
	}

	var _shortOut = shortOut;

	/**
	 * Sets the `toString` method of `func` to return `string`.
	 *
	 * @private
	 * @param {Function} func The function to modify.
	 * @param {Function} string The `toString` result.
	 * @returns {Function} Returns `func`.
	 */

	var setToString = _shortOut(_baseSetToString);
	var _setToString = setToString;

	/**
	 * The base implementation of `_.rest` which doesn't validate or coerce arguments.
	 *
	 * @private
	 * @param {Function} func The function to apply a rest parameter to.
	 * @param {number} [start=func.length-1] The start position of the rest parameter.
	 * @returns {Function} Returns the new function.
	 */

	function baseRest(func, start) {
	  return _setToString(_overRest(func, start, identity_1), func + '');
	}

	var _baseRest = baseRest;

	/**
	 * Checks if the given arguments are from an iteratee call.
	 *
	 * @private
	 * @param {*} value The potential iteratee value argument.
	 * @param {*} index The potential iteratee index or key argument.
	 * @param {*} object The potential iteratee object argument.
	 * @returns {boolean} Returns `true` if the arguments are from an iteratee call,
	 *  else `false`.
	 */

	function isIterateeCall(value, index, object) {
	  if (!isObject_1(object)) {
	    return false;
	  }

	  var type = _typeof(index);

	  if (type == 'number' ? isArrayLike_1(object) && _isIndex(index, object.length) : type == 'string' && index in object) {
	    return eq_1(object[index], value);
	  }

	  return false;
	}

	var _isIterateeCall = isIterateeCall;

	/**
	 * Creates a function like `_.assign`.
	 *
	 * @private
	 * @param {Function} assigner The function to assign values.
	 * @returns {Function} Returns the new assigner function.
	 */

	function createAssigner(assigner) {
	  return _baseRest(function (object, sources) {
	    var index = -1,
	        length = sources.length,
	        customizer = length > 1 ? sources[length - 1] : undefined,
	        guard = length > 2 ? sources[2] : undefined;
	    customizer = assigner.length > 3 && typeof customizer == 'function' ? (length--, customizer) : undefined;

	    if (guard && _isIterateeCall(sources[0], sources[1], guard)) {
	      customizer = length < 3 ? undefined : customizer;
	      length = 1;
	    }

	    object = Object(object);

	    while (++index < length) {
	      var source = sources[index];

	      if (source) {
	        assigner(object, source, index, customizer);
	      }
	    }

	    return object;
	  });
	}

	var _createAssigner = createAssigner;

	/**
	 * This method is like `_.assign` except that it recursively merges own and
	 * inherited enumerable string keyed properties of source objects into the
	 * destination object. Source properties that resolve to `undefined` are
	 * skipped if a destination value exists. Array and plain object properties
	 * are merged recursively. Other objects and value types are overridden by
	 * assignment. Source objects are applied from left to right. Subsequent
	 * sources overwrite property assignments of previous sources.
	 *
	 * **Note:** This method mutates `object`.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.5.0
	 * @category Object
	 * @param {Object} object The destination object.
	 * @param {...Object} [sources] The source objects.
	 * @returns {Object} Returns `object`.
	 * @example
	 *
	 * var object = {
	 *   'a': [{ 'b': 2 }, { 'd': 4 }]
	 * };
	 *
	 * var other = {
	 *   'a': [{ 'c': 3 }, { 'e': 5 }]
	 * };
	 *
	 * _.merge(object, other);
	 * // => { 'a': [{ 'b': 2, 'c': 3 }, { 'd': 4, 'e': 5 }] }
	 */

	var merge = _createAssigner(function (object, source, srcIndex) {
	  _baseMerge(object, source, srcIndex);
	});
	var merge_1 = merge;

	/**
	 * Gets the timestamp of the number of milliseconds that have elapsed since
	 * the Unix epoch (1 January 1970 00:00:00 UTC).
	 *
	 * @static
	 * @memberOf _
	 * @since 2.4.0
	 * @category Date
	 * @returns {number} Returns the timestamp.
	 * @example
	 *
	 * _.defer(function(stamp) {
	 *   console.log(_.now() - stamp);
	 * }, _.now());
	 * // => Logs the number of milliseconds it took for the deferred invocation.
	 */

	var now = function now() {
	  return _root.Date.now();
	};

	var now_1 = now;

	/** Used to match a single whitespace character. */
	var reWhitespace = /\s/;
	/**
	 * Used by `_.trim` and `_.trimEnd` to get the index of the last non-whitespace
	 * character of `string`.
	 *
	 * @private
	 * @param {string} string The string to inspect.
	 * @returns {number} Returns the index of the last non-whitespace character.
	 */

	function trimmedEndIndex(string) {
	  var index = string.length;

	  while (index-- && reWhitespace.test(string.charAt(index))) {}

	  return index;
	}

	var _trimmedEndIndex = trimmedEndIndex;

	/** Used to match leading whitespace. */

	var reTrimStart = /^\s+/;
	/**
	 * The base implementation of `_.trim`.
	 *
	 * @private
	 * @param {string} string The string to trim.
	 * @returns {string} Returns the trimmed string.
	 */

	function baseTrim(string) {
	  return string ? string.slice(0, _trimmedEndIndex(string) + 1).replace(reTrimStart, '') : string;
	}

	var _baseTrim = baseTrim;

	/** `Object#toString` result references. */

	var symbolTag = '[object Symbol]';
	/**
	 * Checks if `value` is classified as a `Symbol` primitive or object.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
	 * @example
	 *
	 * _.isSymbol(Symbol.iterator);
	 * // => true
	 *
	 * _.isSymbol('abc');
	 * // => false
	 */

	function isSymbol(value) {
	  return _typeof(value) == 'symbol' || isObjectLike_1(value) && _baseGetTag(value) == symbolTag;
	}

	var isSymbol_1 = isSymbol;

	/** Used as references for various `Number` constants. */

	var NAN = 0 / 0;
	/** Used to detect bad signed hexadecimal string values. */

	var reIsBadHex = /^[-+]0x[0-9a-f]+$/i;
	/** Used to detect binary string values. */

	var reIsBinary = /^0b[01]+$/i;
	/** Used to detect octal string values. */

	var reIsOctal = /^0o[0-7]+$/i;
	/** Built-in method references without a dependency on `root`. */

	var freeParseInt = parseInt;
	/**
	 * Converts `value` to a number.
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to process.
	 * @returns {number} Returns the number.
	 * @example
	 *
	 * _.toNumber(3.2);
	 * // => 3.2
	 *
	 * _.toNumber(Number.MIN_VALUE);
	 * // => 5e-324
	 *
	 * _.toNumber(Infinity);
	 * // => Infinity
	 *
	 * _.toNumber('3.2');
	 * // => 3.2
	 */

	function toNumber(value) {
	  if (typeof value == 'number') {
	    return value;
	  }

	  if (isSymbol_1(value)) {
	    return NAN;
	  }

	  if (isObject_1(value)) {
	    var other = typeof value.valueOf == 'function' ? value.valueOf() : value;
	    value = isObject_1(other) ? other + '' : other;
	  }

	  if (typeof value != 'string') {
	    return value === 0 ? value : +value;
	  }

	  value = _baseTrim(value);
	  var isBinary = reIsBinary.test(value);
	  return isBinary || reIsOctal.test(value) ? freeParseInt(value.slice(2), isBinary ? 2 : 8) : reIsBadHex.test(value) ? NAN : +value;
	}

	var toNumber_1 = toNumber;

	/** Error message constants. */

	var FUNC_ERROR_TEXT = 'Expected a function';
	/* Built-in method references for those with the same name as other `lodash` methods. */

	var nativeMax$1 = Math.max,
	    nativeMin = Math.min;
	/**
	 * Creates a debounced function that delays invoking `func` until after `wait`
	 * milliseconds have elapsed since the last time the debounced function was
	 * invoked. The debounced function comes with a `cancel` method to cancel
	 * delayed `func` invocations and a `flush` method to immediately invoke them.
	 * Provide `options` to indicate whether `func` should be invoked on the
	 * leading and/or trailing edge of the `wait` timeout. The `func` is invoked
	 * with the last arguments provided to the debounced function. Subsequent
	 * calls to the debounced function return the result of the last `func`
	 * invocation.
	 *
	 * **Note:** If `leading` and `trailing` options are `true`, `func` is
	 * invoked on the trailing edge of the timeout only if the debounced function
	 * is invoked more than once during the `wait` timeout.
	 *
	 * If `wait` is `0` and `leading` is `false`, `func` invocation is deferred
	 * until to the next tick, similar to `setTimeout` with a timeout of `0`.
	 *
	 * See [David Corbacho's article](https://css-tricks.com/debouncing-throttling-explained-examples/)
	 * for details over the differences between `_.debounce` and `_.throttle`.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.1.0
	 * @category Function
	 * @param {Function} func The function to debounce.
	 * @param {number} [wait=0] The number of milliseconds to delay.
	 * @param {Object} [options={}] The options object.
	 * @param {boolean} [options.leading=false]
	 *  Specify invoking on the leading edge of the timeout.
	 * @param {number} [options.maxWait]
	 *  The maximum time `func` is allowed to be delayed before it's invoked.
	 * @param {boolean} [options.trailing=true]
	 *  Specify invoking on the trailing edge of the timeout.
	 * @returns {Function} Returns the new debounced function.
	 * @example
	 *
	 * // Avoid costly calculations while the window size is in flux.
	 * jQuery(window).on('resize', _.debounce(calculateLayout, 150));
	 *
	 * // Invoke `sendMail` when clicked, debouncing subsequent calls.
	 * jQuery(element).on('click', _.debounce(sendMail, 300, {
	 *   'leading': true,
	 *   'trailing': false
	 * }));
	 *
	 * // Ensure `batchLog` is invoked once after 1 second of debounced calls.
	 * var debounced = _.debounce(batchLog, 250, { 'maxWait': 1000 });
	 * var source = new EventSource('/stream');
	 * jQuery(source).on('message', debounced);
	 *
	 * // Cancel the trailing debounced invocation.
	 * jQuery(window).on('popstate', debounced.cancel);
	 */

	function debounce(func, wait, options) {
	  var lastArgs,
	      lastThis,
	      maxWait,
	      result,
	      timerId,
	      lastCallTime,
	      lastInvokeTime = 0,
	      leading = false,
	      maxing = false,
	      trailing = true;

	  if (typeof func != 'function') {
	    throw new TypeError(FUNC_ERROR_TEXT);
	  }

	  wait = toNumber_1(wait) || 0;

	  if (isObject_1(options)) {
	    leading = !!options.leading;
	    maxing = 'maxWait' in options;
	    maxWait = maxing ? nativeMax$1(toNumber_1(options.maxWait) || 0, wait) : maxWait;
	    trailing = 'trailing' in options ? !!options.trailing : trailing;
	  }

	  function invokeFunc(time) {
	    var args = lastArgs,
	        thisArg = lastThis;
	    lastArgs = lastThis = undefined;
	    lastInvokeTime = time;
	    result = func.apply(thisArg, args);
	    return result;
	  }

	  function leadingEdge(time) {
	    // Reset any `maxWait` timer.
	    lastInvokeTime = time; // Start the timer for the trailing edge.

	    timerId = setTimeout(timerExpired, wait); // Invoke the leading edge.

	    return leading ? invokeFunc(time) : result;
	  }

	  function remainingWait(time) {
	    var timeSinceLastCall = time - lastCallTime,
	        timeSinceLastInvoke = time - lastInvokeTime,
	        timeWaiting = wait - timeSinceLastCall;
	    return maxing ? nativeMin(timeWaiting, maxWait - timeSinceLastInvoke) : timeWaiting;
	  }

	  function shouldInvoke(time) {
	    var timeSinceLastCall = time - lastCallTime,
	        timeSinceLastInvoke = time - lastInvokeTime; // Either this is the first call, activity has stopped and we're at the
	    // trailing edge, the system time has gone backwards and we're treating
	    // it as the trailing edge, or we've hit the `maxWait` limit.

	    return lastCallTime === undefined || timeSinceLastCall >= wait || timeSinceLastCall < 0 || maxing && timeSinceLastInvoke >= maxWait;
	  }

	  function timerExpired() {
	    var time = now_1();

	    if (shouldInvoke(time)) {
	      return trailingEdge(time);
	    } // Restart the timer.


	    timerId = setTimeout(timerExpired, remainingWait(time));
	  }

	  function trailingEdge(time) {
	    timerId = undefined; // Only invoke if we have `lastArgs` which means `func` has been
	    // debounced at least once.

	    if (trailing && lastArgs) {
	      return invokeFunc(time);
	    }

	    lastArgs = lastThis = undefined;
	    return result;
	  }

	  function cancel() {
	    if (timerId !== undefined) {
	      clearTimeout(timerId);
	    }

	    lastInvokeTime = 0;
	    lastArgs = lastCallTime = lastThis = timerId = undefined;
	  }

	  function flush() {
	    return timerId === undefined ? result : trailingEdge(now_1());
	  }

	  function debounced() {
	    var time = now_1(),
	        isInvoking = shouldInvoke(time);
	    lastArgs = arguments;
	    lastThis = this;
	    lastCallTime = time;

	    if (isInvoking) {
	      if (timerId === undefined) {
	        return leadingEdge(lastCallTime);
	      }

	      if (maxing) {
	        // Handle invocations in a tight loop.
	        clearTimeout(timerId);
	        timerId = setTimeout(timerExpired, wait);
	        return invokeFunc(lastCallTime);
	      }
	    }

	    if (timerId === undefined) {
	      timerId = setTimeout(timerExpired, wait);
	    }

	    return result;
	  }

	  debounced.cancel = cancel;
	  debounced.flush = flush;
	  return debounced;
	}

	var debounce_1 = debounce;

	var ResizeSensor$1 = createCommonjsModule(function (module) {

	  (function () {
	    /**
	     * Class for dimension change detection.
	     *
	     * @param {Element|Element[]|Elements|jQuery} element
	     * @param {Function} callback
	     *
	     * @constructor
	     */
	    var ResizeSensor = function ResizeSensor(element, callback) {
	      /**
	       *
	       * @constructor
	       */
	      function EventQueue() {
	        this.q = [];

	        this.add = function (ev) {
	          this.q.push(ev);
	        };

	        var i, j;

	        this.call = function () {
	          for (i = 0, j = this.q.length; i < j; i++) {
	            this.q[i].call();
	          }
	        };
	      }
	      /**
	       * @param {HTMLElement} element
	       * @param {String}      prop
	       * @returns {String|Number}
	       */


	      function getComputedStyle(element, prop) {
	        if (element.currentStyle) {
	          return element.currentStyle[prop];
	        } else if (window.getComputedStyle) {
	          return window.getComputedStyle(element, null).getPropertyValue(prop);
	        } else {
	          return element.style[prop];
	        }
	      }
	      /**
	       *
	       * @param {HTMLElement} element
	       * @param {Function}    resized
	       */


	      function attachResizeEvent(element, resized) {
	        if (!element.resizedAttached) {
	          element.resizedAttached = new EventQueue();
	          element.resizedAttached.add(resized);
	        } else if (element.resizedAttached) {
	          element.resizedAttached.add(resized);
	          return;
	        }

	        element.resizeSensor = document.createElement('div');
	        element.resizeSensor.className = 'resize-sensor';
	        var style = 'position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;';
	        var styleChild = 'position: absolute; left: 0; top: 0; transition: 0s;';
	        element.resizeSensor.style.cssText = style;
	        element.resizeSensor.innerHTML = '<div class="resize-sensor-expand" style="' + style + '">' + '<div style="' + styleChild + '"></div>' + '</div>' + '<div class="resize-sensor-shrink" style="' + style + '">' + '<div style="' + styleChild + ' width: 200%; height: 200%"></div>' + '</div>';
	        element.appendChild(element.resizeSensor);

	        if (!{
	          fixed: 1,
	          absolute: 1
	        }[getComputedStyle(element, 'position')]) {
	          element.style.position = 'relative';
	        }

	        var expand = element.resizeSensor.childNodes[0];
	        var expandChild = expand.childNodes[0];
	        var shrink = element.resizeSensor.childNodes[1];
	        var shrinkChild = shrink.childNodes[0];
	        var lastWidth, lastHeight;

	        var reset = function reset() {
	          expandChild.style.width = expand.offsetWidth + 10 + 'px';
	          expandChild.style.height = expand.offsetHeight + 10 + 'px';
	          expand.scrollLeft = expand.scrollWidth;
	          expand.scrollTop = expand.scrollHeight;
	          shrink.scrollLeft = shrink.scrollWidth;
	          shrink.scrollTop = shrink.scrollHeight;
	          lastWidth = element.offsetWidth;
	          lastHeight = element.offsetHeight;
	        };

	        reset();

	        var changed = function changed() {
	          if (element.resizedAttached) {
	            element.resizedAttached.call();
	          }
	        };

	        var addEvent = function addEvent(el, name, cb) {
	          if (el.attachEvent) {
	            el.attachEvent('on' + name, cb);
	          } else {
	            el.addEventListener(name, cb);
	          }
	        };

	        var onScroll = function onScroll() {
	          if (element.offsetWidth != lastWidth || element.offsetHeight != lastHeight) {
	            changed();
	          }

	          reset();
	        };

	        addEvent(expand, 'scroll', onScroll);
	        addEvent(shrink, 'scroll', onScroll);
	      }

	      var elementType = Object.prototype.toString.call(element);
	      var isCollectionTyped = '[object Array]' === elementType || '[object NodeList]' === elementType || '[object HTMLCollection]' === elementType || 'undefined' !== typeof jQuery && element instanceof jQuery //jquery
	      || 'undefined' !== typeof Elements && element instanceof Elements //mootools
	      ;

	      if (isCollectionTyped) {
	        var i = 0,
	            j = element.length;

	        for (; i < j; i++) {
	          attachResizeEvent(element[i], callback);
	        }
	      } else {
	        attachResizeEvent(element, callback);
	      }

	      this.detach = function () {
	        if (isCollectionTyped) {
	          var i = 0,
	              j = element.length;

	          for (; i < j; i++) {
	            ResizeSensor.detach(element[i]);
	          }
	        } else {
	          ResizeSensor.detach(element);
	        }
	      };
	    };

	    ResizeSensor.detach = function (element) {
	      if (element.resizeSensor) {
	        element.removeChild(element.resizeSensor);
	        delete element.resizeSensor;
	        delete element.resizedAttached;
	      }
	    }; // make available to common module loader


	    {
	      module.exports = ResizeSensor;
	    }
	  })();
	});

	/**
	 * Sticky Sidebar JavaScript Plugin.
	 * @version 3.3.1
	 * @author Ahmed Bouhuolia <a.bouhuolia@gmail.com>
	 * @license The MIT License (MIT)
	 */
	var StickySidebar = function () {
	  // ---------------------------------
	  // # Define Constants
	  // ---------------------------------
	  //
	  var EVENT_KEY = '.stickySidebar';
	  var DEFAULTS = {
	    /**
	     * Additional top spacing of the element when it becomes sticky.
	     * @type {Numeric|Function}
	     */
	    topSpacing: 0,

	    /**
	     * Additional bottom spacing of the element when it becomes sticky.
	     * @type {Numeric|Function}
	     */
	    bottomSpacing: 0,

	    /**
	     * Container sidebar selector to know what the beginning and end of sticky element.
	     * @type {String|False}
	     */
	    containerSelector: false,

	    /**
	     * Inner wrapper selector.
	     * @type {String}
	     */
	    innerWrapperSelector: '.inner-wrapper-sticky',

	    /**
	     * The name of CSS class to apply to elements when they have become stuck.
	     * @type {String|False}
	     */
	    stickyClass: 'is-affixed',

	    /**
	     * Detect when sidebar and its container change height so re-calculate their dimensions.
	     * @type {Boolean}
	     */
	    resizeSensor: true,

	    /**
	     * The sidebar returns to its normal position if its width below this value.
	     * @type {Numeric}
	     */
	    minWidth: false
	  }; // ---------------------------------
	  // # Class Definition
	  // ---------------------------------
	  //

	  /**
	   * Sticky Sidebar Class.
	   * @public
	   */

	  var StickySidebar = /*#__PURE__*/function () {
	    /**
	     * Sticky Sidebar Constructor.
	     * @constructor
	     * @param {HTMLElement|String} sidebar - The sidebar element or sidebar selector.
	     * @param {Object} options - The options of sticky sidebar.
	     */
	    function StickySidebar(sidebar) {
	      var _this = this;

	      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

	      _classCallCheck(this, StickySidebar);

	      this.options = StickySidebar.extend(DEFAULTS, options); // Sidebar element query if there's no one, throw error.

	      this.sidebar = 'string' === typeof sidebar ? document.querySelector(sidebar) : sidebar;
	      if ('undefined' === typeof this.sidebar) throw new Error("There is no specific sidebar element.");
	      this.sidebarInner = false;
	      this.container = this.sidebar.parentElement; // Current Affix Type of sidebar element.

	      this.affixedType = 'STATIC';
	      this.direction = 'down';
	      this.support = {
	        transform: false,
	        transform3d: false
	      };
	      this._initialized = false;
	      this._reStyle = false;
	      this._breakpoint = false;
	      this._resizeListeners = []; // Dimensions of sidebar, container and screen viewport.

	      this.dimensions = {
	        translateY: 0,
	        topSpacing: 0,
	        lastTopSpacing: 0,
	        bottomSpacing: 0,
	        lastBottomSpacing: 0,
	        sidebarHeight: 0,
	        sidebarWidth: 0,
	        containerTop: 0,
	        containerHeight: 0,
	        viewportHeight: 0,
	        viewportTop: 0,
	        lastViewportTop: 0
	      }; // Bind event handlers for referencability.

	      ['handleEvent'].forEach(function (method) {
	        _this[method] = _this[method].bind(_this);
	      }); // Initialize sticky sidebar for first time.

	      this.initialize();
	    }
	    /**
	     * Initializes the sticky sidebar by adding inner wrapper, define its container, 
	     * min-width breakpoint, calculating dimensions, adding helper classes and inline style.
	     * @private
	     */


	    _createClass(StickySidebar, [{
	      key: "initialize",
	      value: function initialize() {
	        var _this2 = this;

	        this._setSupportFeatures(); // Get sticky sidebar inner wrapper, if not found, will create one.


	        if (this.options.innerWrapperSelector) {
	          this.sidebarInner = this.sidebar.querySelector(this.options.innerWrapperSelector);
	          if (null === this.sidebarInner) this.sidebarInner = false;
	        }

	        if (!this.sidebarInner) {
	          var wrapper = document.createElement('div');
	          wrapper.setAttribute('class', 'inner-wrapper-sticky');
	          this.sidebar.appendChild(wrapper);

	          while (this.sidebar.firstChild != wrapper) {
	            wrapper.appendChild(this.sidebar.firstChild);
	          }

	          this.sidebarInner = this.sidebar.querySelector('.inner-wrapper-sticky');
	        } // Container wrapper of the sidebar.


	        if (this.options.containerSelector) {
	          var containers = document.querySelectorAll(this.options.containerSelector);
	          containers = Array.prototype.slice.call(containers);
	          containers.forEach(function (container, item) {
	            if (!container.contains(_this2.sidebar)) return;
	            _this2.container = container;
	          });
	          if (!containers.length) throw new Error("The container does not contains on the sidebar.");
	        } // If top/bottom spacing is not function parse value to integer.


	        if ('function' !== typeof this.options.topSpacing) this.options.topSpacing = parseInt(this.options.topSpacing) || 0;
	        if ('function' !== typeof this.options.bottomSpacing) this.options.bottomSpacing = parseInt(this.options.bottomSpacing) || 0; // Breakdown sticky sidebar if screen width below `options.minWidth`.

	        this._widthBreakpoint(); // Calculate dimensions of sidebar, container and viewport.


	        this.calcDimensions(); // Affix sidebar in proper position.

	        this.stickyPosition(); // Bind all events.

	        this.bindEvents(); // Inform other properties the sticky sidebar is initialized.

	        this._initialized = true;
	      }
	      /**
	       * Bind all events of sticky sidebar plugin.
	       * @protected
	       */

	    }, {
	      key: "bindEvents",
	      value: function bindEvents() {
	        window.addEventListener('resize', this, {
	          passive: true,
	          capture: false
	        });
	        window.addEventListener('scroll', this, {
	          passive: true,
	          capture: false
	        });
	        this.sidebar.addEventListener('update' + EVENT_KEY, this);

	        if (this.options.resizeSensor && 'undefined' !== typeof ResizeSensor) {
	          new ResizeSensor(this.sidebarInner, this.handleEvent);
	          new ResizeSensor(this.container, this.handleEvent);
	        }
	      }
	      /**
	       * Handles all events of the plugin.
	       * @param {Object} event - Event object passed from listener.
	       */

	    }, {
	      key: "handleEvent",
	      value: function handleEvent(event) {
	        this.updateSticky(event);
	      }
	      /**
	       * Calculates dimensions of sidebar, container and screen viewpoint
	       * @public
	       */

	    }, {
	      key: "calcDimensions",
	      value: function calcDimensions() {
	        if (this._breakpoint) return;
	        var dims = this.dimensions; // Container of sticky sidebar dimensions.

	        dims.containerTop = StickySidebar.offsetRelative(this.container).top;
	        dims.containerHeight = this.container.clientHeight;
	        dims.containerBottom = dims.containerTop + dims.containerHeight; // Sidebar dimensions.

	        dims.sidebarHeight = this.sidebarInner.offsetHeight;
	        dims.sidebarWidth = this.sidebar.offsetWidth; // Screen viewport dimensions.

	        dims.viewportHeight = window.innerHeight;

	        this._calcDimensionsWithScroll();
	      }
	      /**
	       * Some dimensions values need to be up-to-date when scrolling the page.
	       * @private
	       */

	    }, {
	      key: "_calcDimensionsWithScroll",
	      value: function _calcDimensionsWithScroll() {
	        var dims = this.dimensions;
	        dims.sidebarLeft = StickySidebar.offsetRelative(this.sidebar).left;
	        dims.viewportTop = document.documentElement.scrollTop || document.body.scrollTop;
	        dims.viewportBottom = dims.viewportTop + dims.viewportHeight;
	        dims.viewportLeft = document.documentElement.scrollLeft || document.body.scrollLeft;
	        dims.topSpacing = this.options.topSpacing;
	        dims.bottomSpacing = this.options.bottomSpacing;
	        if ('function' === typeof dims.topSpacing) dims.topSpacing = parseInt(dims.topSpacing(this.sidebar)) || 0;
	        if ('function' === typeof dims.bottomSpacing) dims.bottomSpacing = parseInt(dims.bottomSpacing(this.sidebar)) || 0;

	        if ('VIEWPORT-TOP' === this.affixedType) {
	          // Adjust translate Y in the case decrease top spacing value.
	          if (dims.topSpacing < dims.lastTopSpacing) {
	            dims.translateY += dims.lastTopSpacing - dims.topSpacing;
	            this._reStyle = true;
	          }
	        } else if ('VIEWPORT-BOTTOM' === this.affixedType) {
	          // Adjust translate Y in the case decrease bottom spacing value.
	          if (dims.bottomSpacing < dims.lastBottomSpacing) {
	            dims.translateY += dims.lastBottomSpacing - dims.bottomSpacing;
	            this._reStyle = true;
	          }
	        }

	        dims.lastTopSpacing = dims.topSpacing;
	        dims.lastBottomSpacing = dims.bottomSpacing;
	      }
	      /**
	       * Determine whether the sidebar is bigger than viewport.
	       * @public
	       * @return {Boolean}
	       */

	    }, {
	      key: "isSidebarFitsViewport",
	      value: function isSidebarFitsViewport() {
	        return this.dimensions.sidebarHeight < this.dimensions.viewportHeight;
	      }
	      /**
	       * Observe browser scrolling direction top and down.
	       */

	    }, {
	      key: "observeScrollDir",
	      value: function observeScrollDir() {
	        var dims = this.dimensions;
	        if (dims.lastViewportTop === dims.viewportTop) return;
	        var furthest = 'down' === this.direction ? Math.min : Math.max; // If the browser is scrolling not in the same direction.

	        if (dims.viewportTop === furthest(dims.viewportTop, dims.lastViewportTop)) this.direction = 'down' === this.direction ? 'up' : 'down';
	      }
	      /**
	       * Gets affix type of sidebar according to current scrollTop and scrollLeft.
	       * Holds all logical affix of the sidebar when scrolling up and down and when sidebar 
	       * is bigger than viewport and vice versa.
	       * @public
	       * @return {String|False} - Proper affix type.
	       */

	    }, {
	      key: "getAffixType",
	      value: function getAffixType() {
	        var dims = this.dimensions,
	            affixType = false;

	        this._calcDimensionsWithScroll();

	        var sidebarBottom = dims.sidebarHeight + dims.containerTop;
	        var colliderTop = dims.viewportTop + dims.topSpacing;
	        var colliderBottom = dims.viewportBottom - dims.bottomSpacing; // When browser is scrolling top.

	        if ('up' === this.direction) {
	          if (colliderTop <= dims.containerTop) {
	            dims.translateY = 0;
	            affixType = 'STATIC';
	          } else if (colliderTop <= dims.translateY + dims.containerTop) {
	            dims.translateY = colliderTop - dims.containerTop;
	            affixType = 'VIEWPORT-TOP';
	          } else if (!this.isSidebarFitsViewport() && dims.containerTop <= colliderTop) {
	            affixType = 'VIEWPORT-UNBOTTOM';
	          } // When browser is scrolling up.

	        } else {
	          // When sidebar element is not bigger than screen viewport.
	          if (this.isSidebarFitsViewport()) {
	            if (dims.sidebarHeight + colliderTop >= dims.containerBottom) {
	              dims.translateY = dims.containerBottom - sidebarBottom;
	              affixType = 'CONTAINER-BOTTOM';
	            } else if (colliderTop >= dims.containerTop) {
	              dims.translateY = colliderTop - dims.containerTop;
	              affixType = 'VIEWPORT-TOP';
	            } // When sidebar element is bigger than screen viewport.

	          } else {
	            if (dims.containerBottom <= colliderBottom) {
	              dims.translateY = dims.containerBottom - sidebarBottom;
	              affixType = 'CONTAINER-BOTTOM';
	            } else if (sidebarBottom + dims.translateY <= colliderBottom) {
	              dims.translateY = colliderBottom - sidebarBottom;
	              affixType = 'VIEWPORT-BOTTOM';
	            } else if (dims.containerTop + dims.translateY <= colliderTop) {
	              affixType = 'VIEWPORT-UNBOTTOM';
	            }
	          }
	        } // Make sure the translate Y is not bigger than container height.


	        dims.translateY = Math.max(0, dims.translateY);
	        dims.translateY = Math.min(dims.containerHeight, dims.translateY);
	        dims.lastViewportTop = dims.viewportTop;
	        return affixType;
	      }
	      /**
	       * Gets inline style of sticky sidebar wrapper and inner wrapper according 
	       * to its affix type.
	       * @private
	       * @param {String} affixType - Affix type of sticky sidebar.
	       * @return {Object}
	       */

	    }, {
	      key: "_getStyle",
	      value: function _getStyle(affixType) {
	        if ('undefined' === typeof affixType) return;
	        var style = {
	          inner: {},
	          outer: {}
	        };
	        var dims = this.dimensions;

	        switch (affixType) {
	          case 'VIEWPORT-TOP':
	            style.inner = {
	              position: 'fixed',
	              top: dims.topSpacing,
	              left: dims.sidebarLeft - dims.viewportLeft,
	              width: dims.sidebarWidth
	            };
	            break;

	          case 'VIEWPORT-BOTTOM':
	            style.inner = {
	              position: 'fixed',
	              top: 'auto',
	              left: dims.sidebarLeft,
	              bottom: dims.bottomSpacing,
	              width: dims.sidebarWidth
	            };
	            break;

	          case 'CONTAINER-BOTTOM':
	          case 'VIEWPORT-UNBOTTOM':
	            var translate = this._getTranslate(0, dims.translateY + 'px');

	            if (translate) style.inner = {
	              transform: translate
	            };else style.inner = {
	              position: 'absolute',
	              top: dims.translateY,
	              width: dims.sidebarWidth
	            };
	            break;
	        }

	        switch (affixType) {
	          case 'VIEWPORT-TOP':
	          case 'VIEWPORT-BOTTOM':
	          case 'VIEWPORT-UNBOTTOM':
	          case 'CONTAINER-BOTTOM':
	            style.outer = {
	              height: dims.sidebarHeight,
	              position: 'relative'
	            };
	            break;
	        }

	        style.outer = StickySidebar.extend({
	          height: '',
	          position: ''
	        }, style.outer);
	        style.inner = StickySidebar.extend({
	          position: 'relative',
	          top: '',
	          left: '',
	          bottom: '',
	          width: '',
	          transform: this._getTranslate()
	        }, style.inner);
	        return style;
	      }
	      /**
	       * Cause the sidebar to be sticky according to affix type by adding inline
	       * style, adding helper class and trigger events.
	       * @function
	       * @protected
	       * @param {string} force - Update sticky sidebar position by force.
	       */

	    }, {
	      key: "stickyPosition",
	      value: function stickyPosition(force) {
	        if (this._breakpoint) return;
	        force = this._reStyle || force || false;
	        var offsetTop = this.options.topSpacing;
	        var offsetBottom = this.options.bottomSpacing;
	        var affixType = this.getAffixType();

	        var style = this._getStyle(affixType);

	        if ((this.affixedType != affixType || force) && affixType) {
	          var affixEvent = 'affix.' + affixType.toLowerCase().replace('viewport-', '') + EVENT_KEY;
	          StickySidebar.eventTrigger(this.sidebar, affixEvent);
	          if ('STATIC' === affixType) StickySidebar.removeClass(this.sidebar, this.options.stickyClass);else StickySidebar.addClass(this.sidebar, this.options.stickyClass);

	          for (var key in style.outer) {
	            var _unit = 'number' === typeof style.outer[key] ? 'px' : '';

	            this.sidebar.style[key] = style.outer[key];
	          }

	          for (var _key in style.inner) {
	            var _unit2 = 'number' === typeof style.inner[_key] ? 'px' : '';

	            this.sidebarInner.style[_key] = style.inner[_key] + _unit2;
	          }

	          var affixedEvent = 'affixed.' + affixType.toLowerCase().replace('viewport-', '') + EVENT_KEY;
	          StickySidebar.eventTrigger(this.sidebar, affixedEvent);
	        } else {
	          if (this._initialized) this.sidebarInner.style.left = style.inner.left;
	        }

	        this.affixedType = affixType;
	      }
	      /**
	       * Breakdown sticky sidebar when window width is below `options.minWidth` value.
	       * @protected
	       */

	    }, {
	      key: "_widthBreakpoint",
	      value: function _widthBreakpoint() {
	        if (window.innerWidth <= this.options.minWidth) {
	          this._breakpoint = true;
	          this.affixedType = 'STATIC';
	          this.sidebar.removeAttribute('style');
	          StickySidebar.removeClass(this.sidebar, this.options.stickyClass);
	          this.sidebarInner.removeAttribute('style');
	        } else {
	          this._breakpoint = false;
	        }
	      }
	      /**
	       * Switches between functions stack for each event type, if there's no 
	       * event, it will re-initialize sticky sidebar.
	       * @public
	       */

	    }, {
	      key: "updateSticky",
	      value: function updateSticky() {
	        var _this3 = this;

	        var event = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	        if (this._running) return;
	        this._running = true;

	        (function (eventType) {
	          requestAnimationFrame(function () {
	            switch (eventType) {
	              // When browser is scrolling and re-calculate just dimensions
	              // within scroll. 
	              case 'scroll':
	                _this3._calcDimensionsWithScroll();

	                _this3.observeScrollDir();

	                _this3.stickyPosition();

	                break;
	              // When browser is resizing or there's no event, observe width
	              // breakpoint and re-calculate dimensions.

	              case 'resize':
	              default:
	                _this3._widthBreakpoint();

	                _this3.calcDimensions();

	                _this3.stickyPosition(true);

	                break;
	            }

	            _this3._running = false;
	          });
	        })(event.type);
	      }
	      /**
	       * Set browser support features to the public property.
	       * @private
	       */

	    }, {
	      key: "_setSupportFeatures",
	      value: function _setSupportFeatures() {
	        var support = this.support;
	        support.transform = StickySidebar.supportTransform();
	        support.transform3d = StickySidebar.supportTransform(true);
	      }
	      /**
	       * Get translate value, if the browser supports transfrom3d, it will adopt it.
	       * and the same with translate. if browser doesn't support both return false.
	       * @param {Number} y - Value of Y-axis.
	       * @param {Number} x - Value of X-axis.
	       * @param {Number} z - Value of Z-axis.
	       * @return {String|False}
	       */

	    }, {
	      key: "_getTranslate",
	      value: function _getTranslate() {
	        var y = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	        var x = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
	        var z = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
	        if (this.support.transform3d) return 'translate3d(' + y + ', ' + x + ', ' + z + ')';else if (this.support.translate) return 'translate(' + y + ', ' + x + ')';else return false;
	      }
	      /**
	       * Destroy sticky sidebar plugin.
	       * @public
	       */

	    }, {
	      key: "destroy",
	      value: function destroy() {
	        window.removeEventListener('resize', this, {
	          caption: false
	        });
	        window.removeEventListener('scroll', this, {
	          caption: false
	        });
	        this.sidebar.classList.remove(this.options.stickyClass);
	        this.sidebar.style.minHeight = '';
	        this.sidebar.removeEventListener('update' + EVENT_KEY, this);
	        var styleReset = {
	          inner: {},
	          outer: {}
	        };
	        styleReset.inner = {
	          position: '',
	          top: '',
	          left: '',
	          bottom: '',
	          width: '',
	          transform: ''
	        };
	        styleReset.outer = {
	          height: '',
	          position: ''
	        };

	        for (var key in styleReset.outer) {
	          this.sidebar.style[key] = styleReset.outer[key];
	        }

	        for (var _key2 in styleReset.inner) {
	          this.sidebarInner.style[_key2] = styleReset.inner[_key2];
	        }

	        if (this.options.resizeSensor && 'undefined' !== typeof ResizeSensor) {
	          ResizeSensor.detach(this.sidebarInner, this.handleEvent);
	          ResizeSensor.detach(this.container, this.handleEvent);
	        }
	      }
	      /**
	       * Determine if the browser supports CSS transform feature.
	       * @function
	       * @static
	       * @param {Boolean} transform3d - Detect transform with translate3d.
	       * @return {String}
	       */

	    }], [{
	      key: "supportTransform",
	      value: function supportTransform(transform3d) {
	        var result = false,
	            property = transform3d ? 'perspective' : 'transform',
	            upper = property.charAt(0).toUpperCase() + property.slice(1),
	            prefixes = ['Webkit', 'Moz', 'O', 'ms'],
	            support = document.createElement('support'),
	            style = support.style;
	        (property + ' ' + prefixes.join(upper + ' ') + upper).split(' ').forEach(function (property, i) {
	          if (style[property] !== undefined) {
	            result = property;
	            return false;
	          }
	        });
	        return result;
	      }
	      /**
	       * Trigger custom event.
	       * @static
	       * @param {DOMObject} element - Target element on the DOM.
	       * @param {String} eventName - Event name.
	       * @param {Object} data - 
	       */

	    }, {
	      key: "eventTrigger",
	      value: function eventTrigger(element, eventName, data) {
	        try {
	          var event = new CustomEvent(eventName, {
	            detail: data
	          });
	        } catch (e) {
	          var event = document.createEvent('CustomEvent');
	          event.initCustomEvent(eventName, true, true, data);
	        }

	        element.dispatchEvent(event);
	      }
	      /**
	       * Extend options object with defaults.
	       * @function
	       * @static
	       */

	    }, {
	      key: "extend",
	      value: function extend(defaults, options) {
	        var results = {};

	        for (var key in defaults) {
	          if ('undefined' !== typeof options[key]) results[key] = options[key];else results[key] = defaults[key];
	        }

	        return results;
	      }
	      /**
	       * Get current coordinates left and top of specific element.
	       * @static
	       */

	    }, {
	      key: "offsetRelative",
	      value: function offsetRelative(element) {
	        var result = {
	          left: 0,
	          top: 0
	        };

	        do {
	          var offsetTop = element.offsetTop;
	          var offsetLeft = element.offsetLeft;
	          if (!isNaN(offsetTop)) result.top += offsetTop;
	          if (!isNaN(offsetLeft)) result.left += offsetLeft;
	          element = 'BODY' === element.tagName ? element.parentElement : element.offsetParent;
	        } while (element);

	        return result;
	      }
	      /**
	       * Add specific class name to specific element.
	       * @static 
	       * @param {ObjectDOM} element 
	       * @param {String} className 
	       */

	    }, {
	      key: "addClass",
	      value: function addClass(element, className) {
	        if (!StickySidebar.hasClass(element, className)) {
	          if (element.classList) element.classList.add(className);else element.className += ' ' + className;
	        }
	      }
	      /**
	       * Remove specific class name to specific element
	       * @static
	       * @param {ObjectDOM} element 
	       * @param {String} className 
	       */

	    }, {
	      key: "removeClass",
	      value: function removeClass(element, className) {
	        if (StickySidebar.hasClass(element, className)) {
	          if (element.classList) element.classList.remove(className);else element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
	        }
	      }
	      /**
	       * Determine weather the element has specific class name.
	       * @static
	       * @param {ObjectDOM} element 
	       * @param {String} className 
	       */

	    }, {
	      key: "hasClass",
	      value: function hasClass(element, className) {
	        if (element.classList) return element.classList.contains(className);else return new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
	      }
	    }]);

	    return StickySidebar;
	  }();

	  return StickySidebar;
	}();
	// -------------------------

	window.StickySidebar = StickySidebar;

	var stickyDefaultOptions = {
	  bottomSpacing: 0,
	  topSpacing: 10,
	  resizeSensor: false // containerSelector: '.l-main__container'

	};
	function stickySidebar (element) {
	  var stickyOptions = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	  var $lastChildrenItem, sticky;
	  $lastChildrenItem = $$1(element).children(':not(script,style,link,.resize-sensor):last');

	  if ($lastChildrenItem.length) {
	    $lastChildrenItem.attr('style', 'margin-bottom: 0 !important');
	  }

	  if (RS.Options.fixingCompactHeader) {
	    stickyOptions.topSpacing = $$1('.js-compact-header').children(0).height() + 10;
	  }

	  stickyOptions = merge_1({}, stickyDefaultOptions, stickyOptions);
	  sticky = new StickySidebar(element, stickyOptions);

	  if (sticky) {
	    var updateSticky = debounce_1(function () {
	      sticky.updateSticky();
	    }, 0);
	    new ResizeSensor$1(sticky.container, function () {
	      updateSticky();
	    });
	    new ResizeSensor$1(sticky.sidebarInner, function () {
	      updateSticky();
	    }); // kostyl' dlya obnovleniya polozheniya sajdbara pri izmenenii vysoty rabochej oblasti

	    new ResizeSensor$1(document.querySelector('.l-main__container'), function () {
	      sticky.direction = 'down';
	      sticky.updateSticky();
	    });
	  }
	}

	function sidebar (context) {
	  $(context).find('[data-sticky-sidebar]').each(function (key, node) {
	    var options = _parseOptions(node.getAttribute('data-sticky-sidebar'));

	    stickySidebar(node, options);
	  }); // stickySidebar(document.querySelector('.l-main__inner-content'));
	}

	/* Built-in method references for those with the same name as other `lodash` methods. */

	var nativeKeys = _overArg(Object.keys, Object);
	var _nativeKeys = nativeKeys;

	/** Used for built-in method references. */

	var objectProto$b = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$9 = objectProto$b.hasOwnProperty;
	/**
	 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
	 *
	 * @private
	 * @param {Object} object The object to query.
	 * @returns {Array} Returns the array of property names.
	 */

	function baseKeys(object) {
	  if (!_isPrototype(object)) {
	    return _nativeKeys(object);
	  }

	  var result = [];

	  for (var key in Object(object)) {
	    if (hasOwnProperty$9.call(object, key) && key != 'constructor') {
	      result.push(key);
	    }
	  }

	  return result;
	}

	var _baseKeys = baseKeys;

	/**
	 * Creates an array of the own enumerable property names of `object`.
	 *
	 * **Note:** Non-object values are coerced to objects. See the
	 * [ES spec](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
	 * for more details.
	 *
	 * @static
	 * @since 0.1.0
	 * @memberOf _
	 * @category Object
	 * @param {Object} object The object to query.
	 * @returns {Array} Returns the array of property names.
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 *   this.b = 2;
	 * }
	 *
	 * Foo.prototype.c = 3;
	 *
	 * _.keys(new Foo);
	 * // => ['a', 'b'] (iteration order is not guaranteed)
	 *
	 * _.keys('hi');
	 * // => ['0', '1']
	 */

	function keys(object) {
	  return isArrayLike_1(object) ? _arrayLikeKeys(object) : _baseKeys(object);
	}

	var keys_1 = keys;

	/** Used for built-in method references. */

	var objectProto$c = Object.prototype;
	/** Used to check objects for own properties. */

	var hasOwnProperty$a = objectProto$c.hasOwnProperty;
	/**
	 * Assigns own enumerable string keyed properties of source objects to the
	 * destination object. Source objects are applied from left to right.
	 * Subsequent sources overwrite property assignments of previous sources.
	 *
	 * **Note:** This method mutates `object` and is loosely based on
	 * [`Object.assign`](https://mdn.io/Object/assign).
	 *
	 * @static
	 * @memberOf _
	 * @since 0.10.0
	 * @category Object
	 * @param {Object} object The destination object.
	 * @param {...Object} [sources] The source objects.
	 * @returns {Object} Returns `object`.
	 * @see _.assignIn
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 * }
	 *
	 * function Bar() {
	 *   this.c = 3;
	 * }
	 *
	 * Foo.prototype.b = 2;
	 * Bar.prototype.d = 4;
	 *
	 * _.assign({ 'a': 0 }, new Foo, new Bar);
	 * // => { 'a': 1, 'c': 3 }
	 */

	var assign = _createAssigner(function (object, source) {
	  if (_isPrototype(source) || isArrayLike_1(source)) {
	    _copyObject(source, keys_1(source), object);
	    return;
	  }

	  for (var key in source) {
	    if (hasOwnProperty$a.call(source, key)) {
	      _assignValue(object, key, source[key]);
	    }
	  }
	});
	var assign_1 = assign;

	var Slider = function ($) {
	  var Default = {
	    items: 4,
	    margin: 30,
	    navText: ['<svg class="icon-svg"><use xlink:href="#svg-arrow-left"></use></svg>', '<svg class="icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>'],
	    responsive: {
	      0: {
	        items: 1
	      },
	      380: {
	        items: 2
	      },
	      576: {
	        items: 2
	      },
	      768: {
	        items: 2
	      },
	      992: {
	        items: 3
	      },
	      1200: {
	        items: 4
	      }
	    }
	  };

	  var Slider = /*#__PURE__*/function () {
	    function Slider(element, name, config) {
	      _classCallCheck(this, Slider);

	      this.element = element;
	      this.name = name;
	      this.$element = $(this.element);
	      this.config = assign_1({}, Default, config);
	      this.instance = undefined;
	      this.prepare();
	      this.findDotsContainer();
	      this.findNavContainer();

	      if (this.config.nav) {
	        this.createMobileNav();
	      }

	      this.initSlider();
	    }

	    _createClass(Slider, [{
	      key: "prepare",
	      value: function prepare() {
	        // remove show classes
	        this.$element.removeClass(function (index, className) {
	          return (className.match(/(^|\s)show-items-\S+/g) || []).join(' ');
	        }); // add owl-carousel container

	        this.$element.addClass('owl-carousel'); // Clear Grid

	        if (this.$element.hasClass('row')) {
	          this.$element.removeClass('row');
	          this.$element.children('[class*=col]').removeClass(function (index, className) {
	            return (className.match(/(^|\s)col-\S+/g) || []).join(' ');
	          });
	        }
	      }
	    }, {
	      key: "findDotsContainer",
	      value: function findDotsContainer() {
	        var $container = $('[data-slider-dots=' + this.name + ']');

	        if ($container.length) {
	          var dotsId = 'slider-dots-' + this.name;
	          $container.addClass('slider-dots').attr('id', dotsId);
	          this.config.dots = true;
	          this.config.dotsContainer = '#' + dotsId;
	        } else {
	          this.config.dots = false;
	        }
	      }
	    }, {
	      key: "findNavContainer",
	      value: function findNavContainer() {
	        var $container = $('[data-slider-nav=' + this.name + ']');

	        if ($container.length) {
	          var navId = 'slider-nav-' + this.name;
	          $container.addClass('slider-nav').attr('id', navId);
	          this.config.nav = true;
	          this.config.navContainer = '#' + navId;
	        } else {
	          this.config.nav = false;
	        }
	      }
	    }, {
	      key: "createMobileNav",
	      value: function createMobileNav() {
	        var _this = this;

	        var $prevButton = $('<button>').addClass('owl-prev').html(this.config.navText[0]),
	            $nextButton = $('<button>').addClass('owl-next').html(this.config.navText[1]),
	            $container = $('[data-slider-nav-sm=' + this.name + ']');

	        if (!$container.length) {
	          $container = $('<div>').addClass('slider-nav-sm');
	          this.$element.after($container);
	        }

	        $container.append($prevButton, $nextButton);
	        $prevButton.on('click', function () {
	          _this.$element.trigger('prev.owl.carousel');
	        });
	        $nextButton.on('click', function () {
	          _this.$element.trigger('next.owl.carousel');
	        });
	      }
	    }, {
	      key: "initSlider",
	      value: function initSlider() {
	        this.config.onTranslated = function () {
	          var lazyloadInstance = $('.lazyload').data("plugin_lazy");

	          if (lazyloadInstance) {
	            lazyloadInstance.update();
	          }
	        };

	        this.instance = this.$element.owlCarousel(this.config);
	      }
	    }]);

	    return Slider;
	  }();

	  return Slider;
	}(jQuery);

	function sliders (context) {
	  var sliders = {};
	  $(context).find('[data-slider]').each(function (key, node) {
	    var name = node.getAttribute('data-slider');

	    var options = _parseOptions(node.getAttribute('data-slider-options'));

	    if (!name) {
	      name = 'slider_' + Object.keys(app.sliders).length + 1;
	    }

	    sliders[name] = new Slider(node, name, options);
	  });
	}

	var Popover = function ($) {
	  var Default = {
	    delay: {
	      show: 300,
	      hide: 600
	    },
	    trigger: 'focus',
	    template: '<div class="popover" role="tooltip">' + '<svg class="popover-close icon-svg"><use xlink:href="#svg-close"></use></svg>' + '<div class="arrow"></div>' + '<h3 class="popover-header"></h3>' + '<div class="popover-body"></div></div>',
	    sanitize: false // svg close icon

	  };

	  var Popover = /*#__PURE__*/function () {
	    function Popover(element, name, config) {
	      _classCallCheck(this, Popover);

	      this.element = element;
	      this.name = name;
	      this.$element = $(this.element);
	      this.config = assign_1({}, Default, config);
	      this.instance = undefined;
	      this.prepare();
	      this.initPopover();
	    }

	    _createClass(Popover, [{
	      key: "prepare",
	      value: function prepare() {}
	    }, {
	      key: "initPopover",
	      value: function initPopover() {
	        var _this = this;

	        this.instance = this.$element.popover(this.config);
	        this.$element.on('click', function (e) {
	          e.stopPropagation();
	          return false;
	        });
	        this.$element.on('shown.bs.popover', function (e) {
	          $(this).addClass('active');
	        });
	        this.$element.on('inserted.bs.popover', function (e) {
	          var id = e.target.getAttribute('aria-describedby');
	          $('#' + id).find('.popover-close').on('click', function () {
	            this.$element.popover('hide');
	          }.bind(this));
	        }.bind(this));

	        if (this.config.trigger == 'focus' || this.config.trigger == 'click') {
	          this.$element.on('keyup', function (e) {
	            if (e.key === 'Escape') {
	              _this.$element.popover('hide');

	              _this.$element.blur();
	            }
	          });
	        }

	        this.$element.on('hidden.bs.popover', function (e) {
	          $(this).removeClass('active');
	        });
	      }
	    }]);

	    return Popover;
	  }();

	  return Popover;
	}(jQuery);

	function popovers (context) {
	  var popovers = {};
	  $(context).find('[data-popover]').each(function (key, node) {
	    var name = node.getAttribute('data-popover');

	    var options = _parseOptions(node.getAttribute('data-popover-options'));

	    if (!name) {
	      name = 'popover_' + Object.keys(app.popovers).length + 1;
	    }

	    popovers[name] = new Popover(node, name, options);
	  });
	}

	var Timer = function ($) {
	  var Default = {
	    blockClass: ".js-timer-item",
	    progressClass: ".js-progress",
	    progressTextClass: "js-progress-text"
	  };

	  var Timer = function Timer(element, name, config) {
	    _classCallCheck(this, Timer);

	    this.element = element;
	    this.name = name;
	    this.timerInterval = false;
	    this.setup(config);
	    this.initialize();
	  };

	  Timer.prototype.setup = function (options) {
	    this.options = assign_1({}, Default, options);
	    this.dateNow = parseInt(BX.message('SERVER_TIME'));
	    this.timeLimit = this.options.DATE_TO - this.options.DATE_FROM;
	    this.timeLeft = this.options.DATE_TO - this.dateNow;
	    this.quantity = parseInt(this.options.QUANTITY);
	  };

	  Timer.prototype.initialize = function () {
	    this.obDays = this.element.querySelector('[data-entity="timer-days"]');
	    this.obHours = this.element.querySelector('[data-entity="timer-hours"]');
	    this.obMinutes = this.element.querySelector('[data-entity="timer-minutes"]');
	    this.obSeconds = this.element.querySelector('[data-entity="timer-seconds"]');
	    this.obQuantity = this.element.querySelector('[data-entity="timer-quantity"]');
	    this.showTimer = !!this.obDays && !!this.obHours && !!this.obMinutes && !!this.obSeconds;

	    if (this.timeLeft > 0) {
	      BX.onCustomEvent('onTimerStart');
	    }

	    if (this.showTimer) {
	      this.changeInfo();
	    }

	    this.setQuantity();
	    this.timerInterval = window.setInterval($.proxy(function () {
	      this.dateNow += 1;
	      this.timeLeft = this.options.DATE_TO - this.dateNow;

	      if (this.timeLeft < 1 && this.options.AUTO_RENEWAL == 'Y') {
	        while (this.timeLeft < 1) {
	          this.timeLeft += this.timeLimit;
	        }
	      }

	      if (this.showTimer) {
	        this.changeInfo();
	      }

	      if (this.timeLeft == 0) {
	        BX.onCustomEvent('onTimerEnd');
	        window.clearInterval(this.timerInterval);
	      }
	    }, this), 1000);
	  };

	  Timer.prototype.setQuantity = function () {
	    if (!this.obQuantity) {
	      return;
	    }

	    if (this.quantity > 0) {
	      this.obQuantity.querySelector('[data-entity="timer-quantity-value"]').innerHTML = this.quantity;
	    } else {
	      this.obQuantity.style.display = "none";
	    }
	  };

	  Timer.prototype.changeInfo = function () {
	    if (this.timeLeft >= 0) {
	      var days = parseInt(this.timeLeft / (60 * 60) / 24),
	          hourse = parseInt(this.timeLeft / (60 * 60)),
	          hours = parseInt(this.timeLeft / (60 * 60) % 24),
	          minutes = parseInt(this.timeLeft / 60) % 60,
	          quantity = parseInt(this.quantity),
	          seconds = parseInt(this.timeLeft) % 60;
	      /*
	      			var widthTimerPerc = false;
	      
	      			if (!!dataTimer.DINAMICA_DATA)
	      			{
	      				if (dataTimer.DINAMICA_DATA == 'evenly')
	      				{
	      					widthTimerPerc = Math.floor(100 - ((this.timeLeft / limit) * 100));
	      
	      					this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
	      					this.$element.find(options.textLinePercent).text(widthTimerPerc);
	      				}
	      				else
	      				{
	      					var prevPerc = false,
	      							firstPerc = false;
	      
	      					for (var timePerc in dataTimer.DINAMICA_DATA)
	      					{
	      						if (!prevPerc)
	      						{
	      							prevPerc = timePerc;
	      							firstPerc = timePerc;
	      						}
	      						if (prevPerc < hourse && hourse < timePerc)
	      						{
	      							widthTimerPerc = dataTimer.DINAMICA_DATA[timePerc];
	      							break;
	      						}
	      						prevPerc = timePerc;
	      					}
	      
	      					if (!widthTimerPerc)
	      					{
	      						if (hourse > prevPerc)
	      						{
	      							widthTimerPerc = dataTimer.DINAMICA_DATA[prevPerc];
	      						}
	      						else if (hourse < prevPerc)
	      						{
	      							widthTimerPerc = dataTimer.DINAMICA_DATA[firstPerc];
	      						}
	      					}
	      
	      					if (widthTimerPerc)
	      					{
	      						this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
	      						this.$element.find(options.textLinePercent).text(widthTimerPerc);
	      					}
	      				}
	      			}
	      			else
	      			{
	      				widthTimerPerc = Math.floor((this.timeLeft / limit) * 100);
	      				this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
	      				this.$element.find(options.textLinePercent).text(widthTimerPerc);
	      			}
	      */

	      if (days < 1) {
	        this.obDays.style.display = 'none'; // this.obDays.querySelector('[data-entity="timer-value"]').innerHTML = '00';

	        this.obSeconds.style.display = '';
	        this.obSeconds.querySelector('[data-entity="timer-value"]').innerHTML = seconds < 10 ? '0' + seconds : seconds;
	      } else if (days > 0) {
	        this.obDays.style.display = '';
	        this.obDays.querySelector('[data-entity="timer-value"]').innerHTML = days < 10 ? '0' + days : days;
	        this.obSeconds.style.display = 'none'; // this.obSeconds.querySelector('[data-entity="timer-value"]').innerHTML = '00';
	      }

	      this.obMinutes.querySelector('[data-entity="timer-value"]').innerHTML = minutes < 10 ? '0' + minutes : minutes;
	      this.obHours.querySelector('[data-entity="timer-value"]').innerHTML = hours < 10 ? '0' + hours : hours;
	    }
	  };

	  return Timer;
	}(jQuery);

	function timers (context, options) {
	  var timers = {};
	  $(context).find('[data-timer]').each(function (key, node) {
	    var name = node.getAttribute('data-timer');

	    if ($.isEmptyObject(options)) {
	      options = _parseOptions(node.getAttribute('data-options'));
	    }

	    if (!name) {
	      name = 'timer_' + Object.keys(app.timers).length + 1;
	    }

	    var $node = $(node),
	        data = $node.data('redsign.timer');

	    if (!data) {
	      timers[name] = new Timer(node, name, options);
	      $node.data('redsign.timer', timers[name]);
	    } else {
	      data.setup(options);
	    }
	  });
	}

	function isDesktop (options) {
	  return $$1(window).innerWidth() >= 768;
	}

	var Menu = function ($) {
	  var Default = {
	    animationIn: 'animate-in',
	    animationOut: 'animate-out',
	    animateInBack: 'animate-in-back',
	    animateOutBack: 'animate-out-back',
	    selectors: {
	      items: 'li',
	      submenu: 'ul',
	      isOpen: '.is-open',
	      back: '.is-back',
	      parent: 'body'
	    }
	  };

	  var onAnimationEvent = function onAnimationEvent(el, type, listener) {
	    var events = {
	      'animationend': ['webkitAnimationEnd', 'oAnimationEnd', 'MSAnimationEnd', 'animationend']
	    };

	    if (events[type]) {
	      events[type].forEach(function (eventName) {
	        $(el).on(eventName, listener);
	      });
	    }
	  };

	  var offAnimationEvent = function offAnimationEvent(el, type) {
	    var events = {
	      'animationend': ['webkitAnimationEnd', 'oAnimationEnd', 'MSAnimationEnd', 'animationend']
	    };

	    if (events[type]) {
	      events[type].forEach(function (eventName) {
	        $(el).off(eventName);
	      });
	    }
	  };

	  var Menu = /*#__PURE__*/function () {
	    function Menu($el, options) {
	      _classCallCheck(this, Menu);

	      this.options = merge_1({}, Default, options);
	      this.$menu = $el;
	      this.$items = this.$menu.find(this.options.selectors.items).not(this.options.selectors.back);
	      this.$back = this.$menu.find(this.options.selectors.back);
	      this.$parent = this.$menu.closest(this.options.selectors.parent);
	      this.offsets = [];
	      this.initEvents();
	    }

	    _createClass(Menu, [{
	      key: "hasSubmenu",
	      value: function hasSubmenu($item) {
	        return $item.children(this.options.selectors.submenu).length > 0;
	      }
	    }, {
	      key: "openSubmenu",
	      value: function openSubmenu($item) {
	        var _this = this;

	        var $submenu = $item.children(this.options.selectors.submenu);
	        var $flyin = $submenu.clone().css('opacity', 0).insertAfter(this.$menu);
	        setTimeout(function () {
	          _this.offsets.push(_this.$parent.scrollTop());

	          _this.$parent.scrollTop(0);

	          $flyin.addClass(_this.options.animationIn);

	          _this.$menu.addClass(_this.options.animationOut);

	          onAnimationEvent(_this.$menu, 'animationend', function () {
	            offAnimationEvent(_this.$menu, 'animationend');

	            _this.$menu.removeClass(_this.options.animationOut).addClass('is-view');

	            $item.addClass('is-open').closest(_this.options.selectors.submenu).addClass('is-view');
	            $flyin.remove();
	          });
	        });
	      }
	    }, {
	      key: "back",
	      value: function back($item) {
	        var _this2 = this;

	        var $submenu = $item.closest(this.options.selectors.submenu);
	        var $flyin = $submenu.clone().insertAfter(this.$menu);
	        setTimeout(function () {
	          $flyin.addClass(_this2.options.animateOutBack);

	          _this2.$menu.addClass(_this2.options.animateInBack);

	          onAnimationEvent(_this2.$menu, 'animationend', function () {
	            offAnimationEvent(_this2.$menu, 'animationend');

	            _this2.$menu.removeClass(_this2.options.animationOut + ' ' + _this2.options.animateInBack);

	            $flyin.remove();
	          });
	          $item.closest('.is-open').removeClass('is-open');
	          $item.closest('.is-view').removeClass('is-view');

	          _this2.$parent.scrollTop(_this2.offsets.pop());
	        });
	      }
	    }, {
	      key: "initEvents",
	      value: function initEvents() {
	        var self = this;
	        self.$items.on('click', function (event) {
	          event.stopPropagation();
	          var $item = $(this);

	          if (self.hasSubmenu($item)) {
	            event.preventDefault();
	            self.openSubmenu($item);
	          }
	        });
	        self.$back.on('click', function (event) {
	          event.stopPropagation();
	          event.preventDefault();
	          self.back($(this));
	        });
	      }
	    }]);

	    return Menu;
	  }();

	  return Menu;
	}($$1);

	function dlMenu () {
	  new Menu($('.b-dl-menu'), {
	    selectors: {
	      parent: '.l-compact-menu'
	    }
	  });
	}

	function effects () {
	  // c-icon-count siblings
	  $(document).on('mouseenter', '.c-icon-count', function () {
	    var _this = this;

	    $(this).velocity({
	      opacity: 1
	    }, {
	      duration: 200
	    });
	    $(this).siblings('.c-icon-count').velocity('stop').velocity({
	      opacity: 0.6
	    }, {
	      duration: 200
	    });
	    $(this).one('mouseleave', function () {
	      $(_this).velocity({
	        opacity: 1
	      }, {
	        duration: 200
	      });
	      $(_this).siblings('.c-icon-count').velocity({
	        opacity: 1
	      }, {
	        duration: 200
	      });
	    });
	  });
	}

	function links (context) {
	  $$1(context).find('.js-link-scroll[href*="#"]:not([href="#"])').each(function (key, node) {
	    node.addEventListener('click', function (e) {
	      e.preventDefault();

	      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
	        var hash = this.href.replace(/[^#]*(.*)/, '$1'),
	            $target = $$1(this.hash); // element = document.getElementById(hash.slice(1));

	        $target = $target.length ? $target : $$1('[name=' + this.hash.slice(1) + ']');

	        if ($target.length) {
	          var t = Math.round($target.offset().top);

	          if (RS.Options.fixingCompactHeader == true) {
	            var compactHeader = document.querySelector(RS.Options.compactHeaderSelector);

	            if (compactHeader != undefined) {
	              t += t < 0 ? -70 : -70;
	            }
	          }

	          window.scroll({
	            top: t,
	            behavior: 'smooth'
	          });
	          window.history.replaceState('', document.title, window.location.href.replace(location.hash, '') + this.hash);
	          $target.click();
	        }
	      }
	    }, false);
	  });
	  $$1(context).find('.js-link-up').each(function (key, node) {
	    node.addEventListener('click', function (e) {
	      e.preventDefault();
	      window.scroll({
	        top: 0,
	        behavior: 'smooth'
	      });
	    });
	  });
	}

	function navSlide (context) {
	  $(context).find('.nav-slide').each(function () {
	    var $nav = $(this);
	    var $line = $('<li>').addClass('nav-slide-line');
	    var $currentActive = $nav.find('.active');
	    var $items = $nav.find('.nav-item');

	    var setActive = function setActive($item) {
	      var $span = $item.children('span');
	      $line.css({
	        'width': $span.outerWidth(),
	        'left': $span.position().left + $nav.scrollLeft()
	      });
	    };

	    $nav.append($line);
	    setActive($nav.find('.active'));
	    $nav.find('.nav-link').on('mouseenter', function () {
	      var $item = $(this);
	      setActive($item);
	    });
	    $(this).on('mouseleave', function () {
	      setActive($currentActive);
	    });

	    if ($nav.attr('role') == 'tablist') {
	      $items.on('shown.bs.tab', function () {
	        var $item = $(this).children('.nav-link');
	        $currentActive = $item;
	        setActive($currentActive);
	      });
	    } else {
	      $items.find('.nav-link').on('click', function () {
	        var $item = $(this);
	        $currentActive.removeClass('active');
	        $item.addClass('active');
	        $currentActive = $item;
	        setActive($currentActive);
	      });
	    }
	  });
	  $(context).find('.nav-wrap').each(function () {
	    var $this = $(this),
	        $nav = $this.children('.nav');
	    var $navScroll = $('<div/>', {
	      'class': 'nav-scroll scroll-element'
	    }).html('<div class="scroll-arrow scroll-arrow_less"><svg class="icon-svg"><use xlink:href="#svg-arrow-left"></use></svg></div>\
					<div class="scroll-arrow scroll-arrow_more"><svg class="icon-svg"><use xlink:href="#svg-arrow-right"></use></svg></div>\
					<div class="scroll-element_outer">\
						<div class="scroll-element_size"></div>\
						<div class="scroll-element_track"></div>\
						<div class="scroll-bar"></div>\
				</div>').appendTo($this);
	    $nav.scrollbar({
	      showArrows: true,
	      scrollx: $navScroll,
	      scrollStep: 200
	    });
	  });
	}

	var FloatButton = function ($) {
	  var Default = {
	    button: '[data-float-button]',
	    buttonOffset: 32,
	    showClass: 'showed',
	    unfixedClass: 'unfixed'
	  };

	  var FloatButton = /*#__PURE__*/function () {
	    function FloatButton(options) {
	      _classCallCheck(this, FloatButton);

	      this.options = merge_1({}, Default, options);
	      this.init();
	    }

	    _createClass(FloatButton, [{
	      key: "init",
	      value: function init() {
	        this.findDomElements();
	        this.initEvents();
	      }
	    }, {
	      key: "findDomElements",
	      value: function findDomElements() {
	        this.$button = $(this.options.button);
	        this.$parent = this.$button.parent();
	      }
	    }, {
	      key: "initEvents",
	      value: function initEvents() {
	        var _this = this;

	        var onScroll = function onScroll() {
	          return _this.onScroll();
	        };

	        if (isDesktop()) {
	          $(window).scroll(onScroll);
	        }

	        $(window).resize(debounce_1(function () {
	          $(window).off('scroll', onScroll);

	          if (isDesktop()) {
	            if (_this.$button.hasClass(_this.options.unfixedClass)) {
	              var buttonStyles = {
	                position: 'fixed',
	                top: 'auto'
	              };

	              _this.$button.removeClass(_this.options.unfixedClass).css(buttonStyles);
	            }

	            $(window).scroll(onScroll);
	          }
	        }));
	      }
	    }, {
	      key: "checkShow",
	      value: function checkShow() {
	        var scrollTop = $(window).scrollTop();
	        var windowHeight = $(window).outerHeight();

	        if (scrollTop > windowHeight) {
	          if (!this.$button.hasClass(this.options.showClass)) {
	            this.$button.addClass(this.options.showClass);
	          }

	          return true;
	        } else {
	          if (this.$button.hasClass(this.options.showClass)) {
	            this.$button.removeClass(this.options.showClass);
	          }

	          return false;
	        }
	      }
	    }, {
	      key: "toggleFixing",
	      value: function toggleFixing() {
	        if (this.$button.hasClass(this.options.unfixedClass)) {
	          var scrollTop = $(window).scrollTop();
	          var windowHeight = $(window).outerHeight();
	          var parentOffset = this.$parent.offset().top;

	          if (scrollTop + windowHeight < parentOffset) {
	            var buttonStyles = {
	              position: 'fixed',
	              top: 'auto'
	            };
	            this.$button.removeClass(this.options.unfixedClass).css(buttonStyles);
	          }
	        } else {
	          var buttonOffset = this.$button.offset().top;
	          var buttonHeight = this.$button.height();
	          var _parentOffset = this.$parent.offset().top;

	          if (buttonOffset + buttonHeight + this.options.buttonOffset >= _parentOffset) {
	            var _buttonStyles = {
	              position: 'absolute',
	              top: _parentOffset - buttonHeight - this.options.buttonOffset
	            };
	            this.$button.addClass(this.options.unfixedClass).css(_buttonStyles);
	          }
	        }
	      }
	    }, {
	      key: "onScroll",
	      value: function onScroll() {
	        if (this.checkShow()) {
	          this.toggleFixing();
	        }
	      }
	    }]);

	    return FloatButton;
	  }();

	  return FloatButton;
	}($$1);

	function upButton (context) {
	  var _this = this;

	  $(context).find('[data-float-button]').each(function (key, node) {
	    new FloatButton({
	      button: _this
	    });
	  });
	}

	function scrollbar (context) {
	  $$1(context).find('[data-scrollbar]').each(function (key, node) {
	    var options = _parseOptions(node.getAttribute('data-slider-options'));

	    options = assign_1({}, options);
	    $$1(node).scrollbar(options);
	  });
	}

	var smoothscroll = createCommonjsModule(function (module, exports) {
	  /* smoothscroll v0.4.4 - 2019 - Dustan Kasten, Jeremias Menichelli - MIT License */
	  (function () {

	    function polyfill() {
	      // aliases
	      var w = window;
	      var d = document; // return if scroll behavior is supported and polyfill is not forced

	      if ('scrollBehavior' in d.documentElement.style && w.__forceSmoothScrollPolyfill__ !== true) {
	        return;
	      } // globals


	      var Element = w.HTMLElement || w.Element;
	      var SCROLL_TIME = 468; // object gathering original scroll methods

	      var original = {
	        scroll: w.scroll || w.scrollTo,
	        scrollBy: w.scrollBy,
	        elementScroll: Element.prototype.scroll || scrollElement,
	        scrollIntoView: Element.prototype.scrollIntoView
	      }; // define timing method

	      var now = w.performance && w.performance.now ? w.performance.now.bind(w.performance) : Date.now;
	      /**
	       * indicates if a the current browser is made by Microsoft
	       * @method isMicrosoftBrowser
	       * @param {String} userAgent
	       * @returns {Boolean}
	       */

	      function isMicrosoftBrowser(userAgent) {
	        var userAgentPatterns = ['MSIE ', 'Trident/', 'Edge/'];
	        return new RegExp(userAgentPatterns.join('|')).test(userAgent);
	      }
	      /*
	       * IE has rounding bug rounding down clientHeight and clientWidth and
	       * rounding up scrollHeight and scrollWidth causing false positives
	       * on hasScrollableSpace
	       */


	      var ROUNDING_TOLERANCE = isMicrosoftBrowser(w.navigator.userAgent) ? 1 : 0;
	      /**
	       * changes scroll position inside an element
	       * @method scrollElement
	       * @param {Number} x
	       * @param {Number} y
	       * @returns {undefined}
	       */

	      function scrollElement(x, y) {
	        this.scrollLeft = x;
	        this.scrollTop = y;
	      }
	      /**
	       * returns result of applying ease math function to a number
	       * @method ease
	       * @param {Number} k
	       * @returns {Number}
	       */


	      function ease(k) {
	        return 0.5 * (1 - Math.cos(Math.PI * k));
	      }
	      /**
	       * indicates if a smooth behavior should be applied
	       * @method shouldBailOut
	       * @param {Number|Object} firstArg
	       * @returns {Boolean}
	       */


	      function shouldBailOut(firstArg) {
	        if (firstArg === null || _typeof(firstArg) !== 'object' || firstArg.behavior === undefined || firstArg.behavior === 'auto' || firstArg.behavior === 'instant') {
	          // first argument is not an object/null
	          // or behavior is auto, instant or undefined
	          return true;
	        }

	        if (_typeof(firstArg) === 'object' && firstArg.behavior === 'smooth') {
	          // first argument is an object and behavior is smooth
	          return false;
	        } // throw error when behavior is not supported


	        throw new TypeError('behavior member of ScrollOptions ' + firstArg.behavior + ' is not a valid value for enumeration ScrollBehavior.');
	      }
	      /**
	       * indicates if an element has scrollable space in the provided axis
	       * @method hasScrollableSpace
	       * @param {Node} el
	       * @param {String} axis
	       * @returns {Boolean}
	       */


	      function hasScrollableSpace(el, axis) {
	        if (axis === 'Y') {
	          return el.clientHeight + ROUNDING_TOLERANCE < el.scrollHeight;
	        }

	        if (axis === 'X') {
	          return el.clientWidth + ROUNDING_TOLERANCE < el.scrollWidth;
	        }
	      }
	      /**
	       * indicates if an element has a scrollable overflow property in the axis
	       * @method canOverflow
	       * @param {Node} el
	       * @param {String} axis
	       * @returns {Boolean}
	       */


	      function canOverflow(el, axis) {
	        var overflowValue = w.getComputedStyle(el, null)['overflow' + axis];
	        return overflowValue === 'auto' || overflowValue === 'scroll';
	      }
	      /**
	       * indicates if an element can be scrolled in either axis
	       * @method isScrollable
	       * @param {Node} el
	       * @param {String} axis
	       * @returns {Boolean}
	       */


	      function isScrollable(el) {
	        var isScrollableY = hasScrollableSpace(el, 'Y') && canOverflow(el, 'Y');
	        var isScrollableX = hasScrollableSpace(el, 'X') && canOverflow(el, 'X');
	        return isScrollableY || isScrollableX;
	      }
	      /**
	       * finds scrollable parent of an element
	       * @method findScrollableParent
	       * @param {Node} el
	       * @returns {Node} el
	       */


	      function findScrollableParent(el) {
	        while (el !== d.body && isScrollable(el) === false) {
	          el = el.parentNode || el.host;
	        }

	        return el;
	      }
	      /**
	       * self invoked function that, given a context, steps through scrolling
	       * @method step
	       * @param {Object} context
	       * @returns {undefined}
	       */


	      function step(context) {
	        var time = now();
	        var value;
	        var currentX;
	        var currentY;
	        var elapsed = (time - context.startTime) / SCROLL_TIME; // avoid elapsed times higher than one

	        elapsed = elapsed > 1 ? 1 : elapsed; // apply easing to elapsed time

	        value = ease(elapsed);
	        currentX = context.startX + (context.x - context.startX) * value;
	        currentY = context.startY + (context.y - context.startY) * value;
	        context.method.call(context.scrollable, currentX, currentY); // scroll more if we have not reached our destination

	        if (currentX !== context.x || currentY !== context.y) {
	          w.requestAnimationFrame(step.bind(w, context));
	        }
	      }
	      /**
	       * scrolls window or element with a smooth behavior
	       * @method smoothScroll
	       * @param {Object|Node} el
	       * @param {Number} x
	       * @param {Number} y
	       * @returns {undefined}
	       */


	      function smoothScroll(el, x, y) {
	        var scrollable;
	        var startX;
	        var startY;
	        var method;
	        var startTime = now(); // define scroll context

	        if (el === d.body) {
	          scrollable = w;
	          startX = w.scrollX || w.pageXOffset;
	          startY = w.scrollY || w.pageYOffset;
	          method = original.scroll;
	        } else {
	          scrollable = el;
	          startX = el.scrollLeft;
	          startY = el.scrollTop;
	          method = scrollElement;
	        } // scroll looping over a frame


	        step({
	          scrollable: scrollable,
	          method: method,
	          startTime: startTime,
	          startX: startX,
	          startY: startY,
	          x: x,
	          y: y
	        });
	      } // ORIGINAL METHODS OVERRIDES
	      // w.scroll and w.scrollTo


	      w.scroll = w.scrollTo = function () {
	        // avoid action when no arguments are passed
	        if (arguments[0] === undefined) {
	          return;
	        } // avoid smooth behavior if not required


	        if (shouldBailOut(arguments[0]) === true) {
	          original.scroll.call(w, arguments[0].left !== undefined ? arguments[0].left : _typeof(arguments[0]) !== 'object' ? arguments[0] : w.scrollX || w.pageXOffset, // use top prop, second argument if present or fallback to scrollY
	          arguments[0].top !== undefined ? arguments[0].top : arguments[1] !== undefined ? arguments[1] : w.scrollY || w.pageYOffset);
	          return;
	        } // LET THE SMOOTHNESS BEGIN!


	        smoothScroll.call(w, d.body, arguments[0].left !== undefined ? ~~arguments[0].left : w.scrollX || w.pageXOffset, arguments[0].top !== undefined ? ~~arguments[0].top : w.scrollY || w.pageYOffset);
	      }; // w.scrollBy


	      w.scrollBy = function () {
	        // avoid action when no arguments are passed
	        if (arguments[0] === undefined) {
	          return;
	        } // avoid smooth behavior if not required


	        if (shouldBailOut(arguments[0])) {
	          original.scrollBy.call(w, arguments[0].left !== undefined ? arguments[0].left : _typeof(arguments[0]) !== 'object' ? arguments[0] : 0, arguments[0].top !== undefined ? arguments[0].top : arguments[1] !== undefined ? arguments[1] : 0);
	          return;
	        } // LET THE SMOOTHNESS BEGIN!


	        smoothScroll.call(w, d.body, ~~arguments[0].left + (w.scrollX || w.pageXOffset), ~~arguments[0].top + (w.scrollY || w.pageYOffset));
	      }; // Element.prototype.scroll and Element.prototype.scrollTo


	      Element.prototype.scroll = Element.prototype.scrollTo = function () {
	        // avoid action when no arguments are passed
	        if (arguments[0] === undefined) {
	          return;
	        } // avoid smooth behavior if not required


	        if (shouldBailOut(arguments[0]) === true) {
	          // if one number is passed, throw error to match Firefox implementation
	          if (typeof arguments[0] === 'number' && arguments[1] === undefined) {
	            throw new SyntaxError('Value could not be converted');
	          }

	          original.elementScroll.call(this, // use left prop, first number argument or fallback to scrollLeft
	          arguments[0].left !== undefined ? ~~arguments[0].left : _typeof(arguments[0]) !== 'object' ? ~~arguments[0] : this.scrollLeft, // use top prop, second argument or fallback to scrollTop
	          arguments[0].top !== undefined ? ~~arguments[0].top : arguments[1] !== undefined ? ~~arguments[1] : this.scrollTop);
	          return;
	        }

	        var left = arguments[0].left;
	        var top = arguments[0].top; // LET THE SMOOTHNESS BEGIN!

	        smoothScroll.call(this, this, typeof left === 'undefined' ? this.scrollLeft : ~~left, typeof top === 'undefined' ? this.scrollTop : ~~top);
	      }; // Element.prototype.scrollBy


	      Element.prototype.scrollBy = function () {
	        // avoid action when no arguments are passed
	        if (arguments[0] === undefined) {
	          return;
	        } // avoid smooth behavior if not required


	        if (shouldBailOut(arguments[0]) === true) {
	          original.elementScroll.call(this, arguments[0].left !== undefined ? ~~arguments[0].left + this.scrollLeft : ~~arguments[0] + this.scrollLeft, arguments[0].top !== undefined ? ~~arguments[0].top + this.scrollTop : ~~arguments[1] + this.scrollTop);
	          return;
	        }

	        this.scroll({
	          left: ~~arguments[0].left + this.scrollLeft,
	          top: ~~arguments[0].top + this.scrollTop,
	          behavior: arguments[0].behavior
	        });
	      }; // Element.prototype.scrollIntoView


	      Element.prototype.scrollIntoView = function () {
	        // avoid smooth behavior if not required
	        if (shouldBailOut(arguments[0]) === true) {
	          original.scrollIntoView.call(this, arguments[0] === undefined ? true : arguments[0]);
	          return;
	        } // LET THE SMOOTHNESS BEGIN!


	        var scrollableParent = findScrollableParent(this);
	        var parentRects = scrollableParent.getBoundingClientRect();
	        var clientRects = this.getBoundingClientRect();

	        if (scrollableParent !== d.body) {
	          // reveal element inside parent
	          smoothScroll.call(this, scrollableParent, scrollableParent.scrollLeft + clientRects.left - parentRects.left, scrollableParent.scrollTop + clientRects.top - parentRects.top); // reveal parent in viewport unless is fixed

	          if (w.getComputedStyle(scrollableParent).position !== 'fixed') {
	            w.scrollBy({
	              left: parentRects.left,
	              top: parentRects.top,
	              behavior: 'smooth'
	            });
	          }
	        } else {
	          // reveal element in viewport
	          w.scrollBy({
	            left: clientRects.left,
	            top: clientRects.top,
	            behavior: 'smooth'
	          });
	        }
	      };
	    }

	    {
	      // commonjs
	      module.exports = {
	        polyfill: polyfill
	      };
	    }
	  })();
	});
	var smoothscroll_1 = smoothscroll.polyfill;

	var Compact = function ($) {
	  var Default = {
	    selectors: {
	      root: 'body',
	      wrapper: '.l-page',
	      menu: '.l-compact-menu',
	      toggleButton: '[data-compact-menu-toggle]',
	      search: '#compact-title-search'
	    }
	  };
	  var Events = {
	    fixed: 'fixed.compact-header',
	    unfixed: 'unfixed.compact-header',
	    revealMenu: 'reveal.compact-menu',
	    concealMenu: 'conceal.compact-menu'
	  };

	  var Compact = /*#__PURE__*/function () {
	    function Compact(el, options) {
	      _classCallCheck(this, Compact);

	      this.options = $.extend({}, Default, options);
	      this.isRevealMenu = false;
	      this.isFixing = false;
	      this.prevScroll = $(window).scrollTop();
	      this.$element = $(el);
	      this.resolveSelectors();
	    }

	    _createClass(Compact, [{
	      key: "resolveSelectors",
	      value: function resolveSelectors() {
	        this.$root = $(this.options.selectors.root);
	        this.$wrapper = $(this.options.selectors.wrapper);
	        this.$menu = $(this.options.selectors.menu);
	        this.$search = $(this.options.selectors.search);
	      }
	    }, {
	      key: "toggleMenu",
	      value: function toggleMenu(button) {
	        if (this.isRevealMenu) {
	          this.concealMenu(button);
	        } else {
	          this.revealMenu(button);
	        }
	      }
	    }, {
	      key: "revealMenu",
	      value: function revealMenu(button) {
	        this.$menu.addClass('is-open');
	        $(this.options.selectors.toggleButton).addClass('is-active');

	        if (isDesktop()) {
	          this.revealMenuDesktop(button);
	        } else {
	          this.revealMenuMobile(button);
	        }

	        this.isRevealMenu = true;
	        this.$menu.trigger(Events.revealMenu);
	      }
	    }, {
	      key: "revealMenuMobile",
	      value: function revealMenuMobile(button) {
	        var _this = this;

	        var menuOffset = 0;

	        if (this.isFixing) {
	          menuOffset = this.$element.position().top + this.$element.outerHeight();
	        } else {
	          menuOffset = this.$element.offset().top + this.$element.outerHeight() - $(window).scrollTop();
	        }

	        this.$element.css({
	          paddingRight: window.innerWidth - $(document).width() // scrollbar width

	        });
	        this.$root.css({
	          overflow: 'hidden'
	        });
	        this.$menu.css({
	          paddingTop: menuOffset + 'px'
	        });

	        var checkResize = function checkResize() {
	          if (isDesktop()) {
	            _this.$menu.one(Events.concealMenu, function () {
	              return $(window).off('resize', checkResize);
	            });

	            _this.concealMenu();
	          }
	        };

	        $(window).resize(checkResize);
	      }
	    }, {
	      key: "revealMenuDesktop",
	      value: function revealMenuDesktop(button) {
	        var _this2 = this;

	        var documentHeight = $(document).height();
	        var maxHeight = documentHeight - this.$element.outerHeight();

	        var calc = function calc() {
	          _this2.$menu.css({
	            top: _this2.$element.children().outerHeight(),
	            //(this.$element.offset().top + this.$element.children().outerHeight()) + 'px',
	            left: $(button).offset().left // height: $(window).outerHeight() - this.$element.children().outerHeight()

	          });
	        };

	        var conceal = function conceal() {
	          _this2.$menu.one(Events.concealMenu, function () {
	            $(window).off('scroll', onScroll);

	            _this2.$menu.one(Events.concealMenu, function () {
	              return $(window).off('resize', checkResize);
	            });

	            $(document).off('click', onKeyDown);
	          });

	          _this2.concealMenu();
	        };

	        var onScroll = function onScroll() {
	          if (_this2.isFixing) {
	            calc();
	          } else {
	            conceal();
	          }
	        };

	        var checkResize = function checkResize() {
	          documentHeight = $(document).height();
	          maxHeight = documentHeight - _this2.$element.outerHeight();

	          if (!isDesktop()) {
	            conceal();
	          } else {
	            calc();
	          }
	        };

	        var onKeyDown = function onKeyDown() {
	          var $target = $(event.target);

	          if (!$target.is(_this2.$menu) || $target.closest(_this2.$menu).length == 0) {
	            conceal();
	          }
	        }; //setTimeout(() => {


	        calc(); //});

	        $(window).on('scroll', onScroll);
	        $(window).resize(checkResize);
	        $(document).on('click', onKeyDown);
	      }
	    }, {
	      key: "concealMenu",
	      value: function concealMenu() {
	        this.$root.css('overflow', 'auto');
	        this.$wrapper.attr('style', '');
	        this.$menu.attr('style', '');
	        this.$element.css({
	          paddingRight: 0
	        });
	        this.$menu.removeClass('is-open');
	        $(this.options.selectors.toggleButton).removeClass('is-active');
	        this.isRevealMenu = false;
	        this.$menu.trigger(Events.concealMenu);
	      }
	    }, {
	      key: "revealMobileSearch",
	      value: function revealMobileSearch() {
	        var _this3 = this;

	        if (!this.$search.length) {
	          return;
	        }

	        this.$search.addClass('js-is-open').css({
	          'top': -this.$search.outerHeight(),
	          'opacity': 0,
	          'display': 'block',
	          'will-change': 'top'
	        });
	        setTimeout(function () {
	          _this3.$search.velocity('stop').velocity({
	            'top': 0,
	            'opacity': 1
	          }, {
	            complete: function complete() {
	              _this3.$search.find('input:eq(0)').focus();
	            }
	          });
	        });
	      }
	    }, {
	      key: "concealMobileSearch",
	      value: function concealMobileSearch() {
	        var _this4 = this;

	        this.$search.velocity('stop').velocity({
	          'opacity': 0,
	          'top': -this.$search.outerHeight()
	        }, {
	          complete: function complete() {
	            _this4.$search.css({
	              display: 'none'
	            });
	          }
	        });
	      }
	    }, {
	      key: "fixing",
	      value: function fixing() {
	        var _this5 = this;

	        var height = this.$element.outerHeight();
	        var $wrap = $('<div>').css({
	          height: isDesktop() ? 0 : height,
	          position: 'relative',
	          top: 0
	        });
	        this.$element.wrap($wrap);
	        $(window).resize(function () {
	          height = _this5.$element.outerHeight();

	          _this5.$element.parent().css({
	            height: isDesktop() ? 0 : height
	          });
	        });

	        var fixing = function fixing() {
	          if (!_this5.isFixing) {
	            var checkScroll = isDesktop() ? $(window).scrollTop() >= 200 && $(window).scrollTop() <= _this5.prevScroll : $(window).scrollTop() >= _this5.$element.offset().top;
	            _this5.prevScroll = $(window).scrollTop();

	            if (checkScroll) {
	              _this5.$element.css({
	                position: 'fixed',
	                width: '100%',
	                top: '-1px' // i dont undestand why safari is so stupid

	              });

	              _this5.isFixing = true;

	              _this5.$element.addClass('is-fixed');

	              _this5.$element.trigger(Events.fixed);
	            }
	          } else {
	            var _checkScroll = isDesktop() ? $(window).scrollTop() < 200 || $(window).scrollTop() > _this5.prevScroll : $(window).scrollTop() < _this5.$element.parent().offset().top;

	            _this5.prevScroll = $(window).scrollTop();

	            if (_checkScroll) {
	              _this5.$element.css({
	                position: 'relative'
	              });

	              _this5.isFixing = false;

	              _this5.$element.removeClass('is-fixed');

	              _this5.$element.trigger(Events.unfixed);
	            }
	          }
	        };

	        fixing();
	        $(window).scroll(fixing);
	      }
	    }]);

	    return Compact;
	  }();

	  return Compact;
	}($$1);

	var instance = null;
	function getInstance() {
	  var selector = (RS.Options || {}).compactHeaderSelector;

	  if (!instance) {
	    if (selector) {
	      var header = document.querySelector(selector);
	      instance = new Compact(header);
	    }
	  } else {
	    instance.resolveSelectors();
	  }

	  return instance;
	}
	function init() {
	  var compactHeader = getInstance(); // Fixing header

	  if ((RS.Options || {}).fixingCompactHeader) {
	    compactHeader.fixing();
	  } // Toggle compact menu


	  $$1(document).on('click', '[data-compact-menu-toggle]', function (event) {
	    event.preventDefault();
	    compactHeader.toggleMenu(this);
	  }); // Mobile search

	  $$1(document).on('click', '[data-compact-search-open]', function (event) {
	    event.preventDefault();
	    compactHeader.revealMobileSearch();
	  });
	  $$1(document).on('click', '[data-compact-search-close]', function (event) {
	    event.preventDefault();
	    compactHeader.concealMobileSearch();
	  }); // update menu indicator

	  BX.addCustomEvent('GlobalStateChanged', function () {
	    var el = compactHeader.$element;
	    if (el.find('.c-icon-count.has-items').length) el.find('.hamburger').addClass('hamburger--has-indicator');else el.find('.hamburger').removeClass('hamburger--has-indicator');
	  }); // $(window).resize(() => {
	  // 	if (compactHeader.$search.hasClass('js-is-open')) {
	  // 		compactHeader.concealMobileSearch();
	  // 	}
	  // });
	}

	var windowPopupOptions = {
	  infobar: false,
	  buttons: false,
	  slideShow: false,
	  fullScreen: false,
	  animationEffect: "slide-down-in",
	  animationDuration: 300,
	  thumbs: false,
	  //modal: true,
	  // hideScrollbar: false,
	  ajax: {
	    settings: {
	      cache: true,
	      data: {
	        cache: true,
	        fancybox: true
	      }
	    }
	  },
	  touch: false,
	  keyboard: true,
	  btnTpl: {
	    smallBtn: ''
	  },
	  baseTpl: '<div class="fancybox-container popup-form" role="dialog" tabindex="-1">' + '<div class="fancybox-bg"></div>' + '<div class="fancybox-inner">' + '<div class="fancybox-stage"></div>' + '</div>' + '</div>',
	  beforeLoad: function beforeLoad(instance, slide, b) {
	    $$1('.l-page').addClass('filter-blur');

	    if (RS.Panel && RS.Panel.openned) ;
	  },
	  afterLoad: function afterLoad(instance, slide) {
	    var obContent = slide.$content.get(0),
	        data = BX.parseJSON(obContent.innerHTML);

	    if (data) {
	      var pageAssets = BX.processHTML(data.JS);

	      if (pageAssets.STYLE.length > 0) {
	        BX.loadCSS(pageAssets.STYLE);
	      }

	      if (pageAssets.SCRIPT) {
	        var processed = BX.processHTML(data.DATA, false);
	        obContent.innerHTML = processed.HTML;
	        BX.ajax.processScripts(pageAssets.SCRIPT, false, BX.proxy(function () {
	          BX.ajax.processScripts(processed.SCRIPT);
	        }, this));
	      }
	    }

	    if (RS.Init) {
	      RS.Init(['bmd', 'popup', 'nav-slide', 'scrollbar'], this.$content);
	    }

	    this.$content.wrapAll('<div>');
	    var $wrapper = this.$content.parent();
	    $wrapper.prepend('<button data-fancybox-close class="fancybox-close-small"><svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg></button>');

	    if (slide.opts.$orig.data('fancybox-title') !== false) {
	      var title = !!slide.opts.title && slide.opts.title.length ? slide.opts.title : !!instance.opts.title && instance.opts.title.length ? instance.opts.title : !!slide.opts.$orig ? slide.opts.$orig.data('fancybox-title') || slide.opts.$orig.attr('title') || this.opts.$orig.text() : undefined;

	      if (title !== undefined) {
	        this.$content.parent().prepend('<div class="fancybox-title fancybox-title-inside-wrap">' + title + '</div>');
	      }
	    }
	  },
	  afterShow: function afterShow(instance, slide) {},
	  beforeClose: function beforeClose() {
	    $$1('.l-page').removeClass('filter-blur');
	  },
	  afterClose: function afterClose(instance) {
	    setTimeout(function () {
	      $$1('.js-fix-scroll').removeClass('js-fix-scroll--fixed');
	    });
	  }
	};

	var fullscreenPopupOptions = merge_1({}, windowPopupOptions, {
	  slideClass: "fullscreen",
	  animationEffect: 'zoom-in-out',
	  spinnerTpl: '<div><div class="fancybox-loading"></div></div>',
	  ajax: {
	    settings: {
	      cache: true,
	      data: {
	        cache: true,
	        fancybox: true,
	        fancyboxFullscreen: true
	      }
	    }
	  },
	  afterLoad: function afterLoad(instance, slide) {
	    this.$content.wrapAll('<div>');
	    var $wrapper = this.$content.parent();
	    $wrapper.prepend('<button data-fancybox-close class="fancybox-close-small"><svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg></button>');
	  }
	});

	function init$1(context) {
	  $$1(context).find('[data-type="ajax"],[data-fancybox][data-type="inline"]').each(function () {
	    var _this = this;

	    if (this.dataset.popupInited == 'true') {
	      return;
	    }

	    var options = _parseOptions(this.getAttribute('data-popup-options'));

	    var popupType = (RS.Options || {}).defaultPopupType;

	    if (this.getAttribute('data-popup-type')) {
	      popupType = this.getAttribute('data-popup-type');
	    }

	    var openPanel = function openPanel(link, type) {
	      var activeItem = function activeItem() {
	        link.classList.add('is-active');
	        $$1(document).one('panel.closed', function () {
	          link.classList.remove('is-active');
	        });
	      };

	      if (!link.classList.contains('is-active')) {
	        if (RS.Panel.openned) {
	          $$1(document).one('panel.before_open', function () {
	            activeItem();
	          });
	        } else {
	          activeItem();
	        }

	        RS.Panel.open(link, type).then(function (content) {
	          if (RS.Init) {
	            RS.Init(['bmd', 'popup'], content);
	          }
	        });
	      } else {
	        RS.Panel.close(link);
	      }
	    };

	    switch (popupType) {
	      case 'side':
	        $$1(this).click(function (e) {
	          e.preventDefault();
	          openPanel(_this, 'right');
	        });
	        break;

	      case 'fullscreen':
	        options = merge_1({}, fullscreenPopupOptions, options);
	        $$1(this).fancybox(options);
	        break;

	      case 'bottom':
	        $$1(this).click(function (e) {
	          e.preventDefault();
	          openPanel(_this, 'bottom');
	        });
	        break;

	      case 'window':
	      default:
	        options = merge_1({}, windowPopupOptions, options);
	        $$1(this).fancybox(options);
	        break;
	    }

	    this.dataset.popupInited = 'true';
	  });
	}
	function popup () {
	  var content = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	  var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'window';
	  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

	  switch (type) {
	    case 'window':
	    default:
	      options = merge_1({}, windowPopupOptions, options);
	      $$1.fancybox.open(content, options);
	      break;
	  }
	}

	var AllModules = ['bmd', 'sidebar', 'sliders', 'popovers', // 'timers',
	'popup', 'lazy-images', 'compact-header', 'dl-menu', 'effects', 'links', 'nav-slide', 'upbutton'];
	function init$2 () {
	  var modules = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : AllModules;
	  var context = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document.body;
	  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
	  modules.forEach(function (module) {
	    switch (module) {
	      case 'bmd':
	        $$1(context).bootstrapMaterialDesign();
	        break;

	      case 'sidebar':
	        sidebar(context);
	        break;

	      case 'sliders':
	        sliders(context);
	        break;

	      case 'popovers':
	        popovers(context);
	        break;

	      case 'timers':
	        timers(context, options);
	        break;

	      case 'popup':
	        init$1(context);
	        break;

	      case 'lazy-images':
	        lazyImages(context);
	        break;

	      case 'compact-header':
	        init();
	        break;

	      case 'dl-menu':
	        dlMenu();
	        break;

	      case 'links':
	        smoothscroll.polyfill();
	        links(context);
	        break;

	      case 'effects':
	        effects();
	        break;

	      case 'nav-slide':
	        navSlide(context);
	        break;

	      case 'upbutton':
	        upButton(context);
	        break;

	      case 'scrollbar':
	        scrollbar(context);
	        break;
	    }
	  });
	}

	function bxloader (context) {
	  if (!window.BX) return;
	  var BX = window.BX,
	      defaultShowWait = BX.showWait,
	      defaultCloseWait = BX.closeWait,
	      lastWait = [];

	  BX.showWait = function (node, msg) {
	    node = BX(node) || document.body || document.documentElement;

	    if (BX.findParent(node, {
	      className: 'rs-megamart'
	    })) {
	      var container_id = node.id || Math.random();
	      var obMsg = node.bxmsg = node;
	      BX.addClass(node, 'overlay is-loading');
	      lastWait[lastWait.length] = obMsg;
	      return obMsg;
	    } else {
	      defaultShowWait(node, msg);
	    }
	  };

	  BX.closeWait = function (node, obMsg) {
	    node = BX(node) || document.body || document.documentElement;

	    if (BX.findParent(node, {
	      className: 'rs-megamart'
	    })) {
	      if (node && !obMsg) obMsg = node.bxmsg;
	      if (node && !obMsg && BX.hasClass(node, 'bx-core-waitwindow')) obMsg = node;
	      if (node && !obMsg) obMsg = BX('wait_' + node.id);
	      if (!obMsg) obMsg = lastWait.pop();

	      if (obMsg && obMsg.parentNode) {
	        for (var i = 0, len = lastWait.length; i < len; i++) {
	          if (obMsg == lastWait[i]) {
	            lastWait = BX.util.deleteFromArray(lastWait, i);
	            break;
	          }
	        }
	      }

	      BX.removeClass(obMsg, 'overlay is-loading');
	      if (node) node.bxmsg = null;
	    } else {
	      defaultCloseWait(node, obMsg);
	    }
	  };
	}

	function imageInCache (src) {
	  var image = new Image();
	  image.src = src;
	  return image.complete;
	}

	var $overlay;

	function show() {
	  var d = new $$1.Deferred();

	  if (!$overlay) {
	    $overlay = $$1('<div>');
	    $overlay.css({
	      'position': 'fixed',
	      'opacity': '0',
	      'width': '100%',
	      'height': '100%',
	      'top': '0',
	      'left': '0',
	      'right': '0',
	      'bottom': '0',
	      'background-color': 'rgba(0, 0, 0, 0.5)',
	      'z-index': '9998',
	      'cursor': 'pointer',
	      'display': 'none'
	    });
	    $$1('body').append($overlay);
	  }

	  $$1(document).trigger('overlay.before_show');
	  $overlay.show().velocity({
	    'opacity': 1
	  }, {
	    duration: 300,
	    complete: function complete() {
	      $$1(document).trigger('overlay.after_show');
	      d.resolve($overlay);
	    }
	  });
	  return d.promise();
	}

	function hide() {
	  if (!$overlay) {
	    return;
	  }

	  var d = new $$1.Deferred();
	  $$1(document).trigger('overlay.before_hide');
	  $overlay.velocity({
	    'opacity': 0
	  }, {
	    duration: 300,
	    complete: function complete() {
	      $overlay.hide();
	      d.resolve($overlay);
	      $$1(document).trigger('overlay.after_hide');
	    }
	  });
	  return d.promise();
	}

	var PositionPanel = /*#__PURE__*/function () {
	  function PositionPanel(panel) {
	    _classCallCheck(this, PositionPanel);

	    this.panel = panel;
	    this.blocks = {};
	    this.$preload = undefined;
	  }

	  _createClass(PositionPanel, [{
	    key: "createBlock",
	    value: function createBlock() {
	      var link = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	      var content = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      var url = link.getAttribute('data-src') || link.href;
	      var block = document.createElement('div');
	      block.classList.add('panel-block');

	      if (link.title || link.innerText) {
	        var blockTitlte = document.createElement('div');
	        blockTitlte.classList.add('panel-block__title');
	        var title = link.title || link.innerText;
	        blockTitlte.innerText = title;
	        block.appendChild(blockTitlte);
	      }

	      var blockContent = document.createElement('div');
	      blockContent.classList.add('panel-block__content');
	      blockContent.innerHTML = content;
	      block.appendChild(blockContent);
	      this.blocks[url] = block;
	      this.$inner.append(block);
	      return block;
	    }
	  }, {
	    key: "updateBlock",
	    value: function updateBlock() {
	      var link = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	      var content = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      var url = link.getAttribute('data-src') || link.href;
	      var block = this.blocks[url];
	      $$1(block).find('.panel-block__content').html(content);
	      return block;
	    }
	  }, {
	    key: "update",
	    value: function update() {
	      var _this = this;

	      var url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	      var d = $$1.Deferred();

	      if (!this.blocks[url]) {
	        d.reject();
	        return d.promise();
	      }

	      this.panel.load(url).then(function (content) {
	        var block = _this.blocks[url];
	        $$1(block).find('.panel-block__content').html(content);
	        d.resolve();
	      });
	      return d;
	    }
	  }, {
	    key: "open",
	    value: function open() {
	      var _this2 = this;

	      var link = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	      var d = $$1.Deferred();
	      var url = link.getAttribute('data-src') || link.href;

	      var afterFn = function afterFn() {
	        _this2.hidePreload();

	        link.setAttribute('data-need-reload', 'N');
	        d.resolve(_this2.blocks[url]);

	        _this2.$panel.addClass('is-open');
	      };

	      if (this.blocks[url]) {
	        var block = this.blocks[url];
	        var needCache = link.getAttribute('data-need-cache') == 'Y';
	        var needReload = link.getAttribute('data-need-reload') == 'Y';

	        if (!needCache || needCache && needReload) {
	          show().then(function ($overlay) {
	            _this2.showPreload($overlay);

	            _this2.panel.load(url).then(function (content) {
	              var block = _this2.updateBlock(link, content);

	              return _this2.show(block);
	            }).then(function () {
	              return afterFn();
	            });
	          });
	        } else {
	          show();
	          this.show(block);
	          afterFn();
	        }
	      } else {
	        show().then(function ($overlay) {
	          _this2.showPreload($overlay);

	          _this2.panel.load(url).then(function (content) {
	            var block = _this2.createBlock(link, content);

	            return _this2.show(block);
	          }).then(function () {
	            return afterFn();
	          });

	          $overlay.on('click', function () {
	            return _this2.panel.close();
	          });
	        });
	      }

	      d.then(function () {
	        $$1(document).on('click.outside', function (e) {// if (
	          // 	$(e.target) != this.$inner &&
	          // 	!!!$(e.target).closest(this.$panel).length &&
	          // 	$(e.target).closest(document).length > 0
	          // ) {
	          // 	this.panel.close();
	          // }
	        });
	      });
	      return d.promise();
	    }
	  }, {
	    key: "close",
	    value: function close() {
	      var _this3 = this;

	      $$1(document).off('click.outside');
	      return $$1.when(this.hide(), this.hidePreload(), hide()).then(function () {
	        _this3.$panel.removeClass('is-open');

	        return true;
	      });
	    }
	  }]);

	  return PositionPanel;
	}();

	var RightPanel = /*#__PURE__*/function (_PositionPanel) {
	  _inherits(RightPanel, _PositionPanel);

	  var _super = _createSuper(RightPanel);

	  function RightPanel() {
	    _classCallCheck(this, RightPanel);

	    return _super.apply(this, arguments);
	  }

	  _createClass(RightPanel, [{
	    key: "type",
	    get: function get() {
	      return 'right';
	    }
	  }, {
	    key: "$panel",
	    get: function get() {
	      return $$1('#side-panel');
	    }
	  }, {
	    key: "$inner",
	    get: function get() {
	      if (!this.$innerObj) {
	        this.$innerObj = this.$panel.find('#side-panel-inner');
	      }

	      return this.$innerObj;
	    }
	  }, {
	    key: "$container",
	    get: function get() {
	      return this.$panel.find('#side-panel-container');
	    }
	  }, {
	    key: "showPreload",
	    value: function showPreload($container) {
	      if ($container && $container.length) {
	        this.$preload = $$1('<div>').addClass('panel-loader').append('<span></span><span></span><span></span><span></span>');
	        $container.append(this.$preload);
	      }
	    }
	  }, {
	    key: "hidePreload",
	    value: function hidePreload() {
	      if (this.$preload) {
	        this.$preload.remove();
	      }
	    }
	  }, {
	    key: "getInnerWidth",
	    value: function getInnerWidth(block) {
	      var $block = $$1(block);
	      var $blockClone = $block.clone();
	      var innerWidth;
	      $blockClone.css({
	        position: 'absolute',
	        visibility: 'hidden',
	        left: '-99999px',
	        top: '-99999px',
	        display: 'block'
	      });
	      $$1('body').append($blockClone);
	      innerWidth = $blockClone.outerWidth() > 500 ? $blockClone.outerWidth() : 500;

	      if (innerWidth > $$1(window).width()) {
	        innerWidth = $$1(window).width() - 60;
	      }

	      return innerWidth;
	    }
	  }, {
	    key: "show",
	    value: function show(block) {
	      $$1(document).trigger('panel.show');
	      var blockWidth = this.getInnerWidth(block);

	      var AnimationComplete = function AnimationComplete() {
	        $$1(block).addClass('is-showed');
	        $$1(document).trigger('panel.showed');
	        setTimeout(function () {
	          $$1(block).velocity({
	            opacity: 1
	          }, {
	            duration: 300
	          });
	        }, 100);
	      };

	      var $controls = $$1('.side-panel__controls');
	      this.$panel.append($controls.clone());
	      this.$inner.append($controls);
	      this.$inner.css({
	        width: blockWidth,
	        right: -blockWidth
	      }).velocity({
	        right: 0
	      }, {
	        duration: 300,
	        easing: [.17, .67, .83, .67],
	        complete: AnimationComplete
	      });
	    }
	  }, {
	    key: "hide",
	    value: function hide() {
	      var _this = this;

	      var d = $$1.Deferred();
	      var $inner = this.$inner;
	      var blockWidth = $inner.outerWidth();
	      $inner.velocity({
	        right: -blockWidth
	      }, {
	        duration: 300,
	        complete: function complete() {
	          var $controls = _this.$inner.find('.side-panel__controls');

	          _this.$panel.children('.side-panel__controls').remove();

	          _this.$panel.append($controls);

	          $inner.children('.panel-block').removeClass('is-showed').css('opacity', 0);
	          d.resolve();
	        }
	      });
	      return d.promise();
	    }
	  }]);

	  return RightPanel;
	}(PositionPanel);

	var MIN_HEIGHT = 500;
	var MAX_HEIGHT = 900;
	function dragResize (options) {
	  if (!options.dragArea || !options.container) {
	    return;
	  }

	  var dragArea = options.dragArea;
	  var container = options.container;
	  var minHeight = options.minHeight || MIN_HEIGHT;
	  var maxHeight = options.maxHeight || MAX_HEIGHT;

	  var onResize = options.onResize || function () {};

	  var isResizing = false;
	  var clicked = false;
	  dragArea.addEventListener('mousedown', function (e) {
	    isResizing = true;
	    clicked = {
	      height: container.clientHeight,
	      clientY: e.clientY
	    };

	    var onMouseMove = function onMouseMove(e) {
	      if (!isResizing) {
	        return;
	      }

	      var currentHeight = Math.max(clicked.clientY - e.clientY + clicked.height, minHeight);

	      if (currentHeight > minHeight && currentHeight < maxHeight) {
	        onResize(currentHeight);
	      }
	    };

	    var onMouseUp = function onMouseUp(e) {
	      isResizing = false;
	      clicked = false;
	      document.removeEventListener('mousemove', onMouseMove);
	      document.removeEventListener('mouseup', onMouseUp);
	    };

	    document.addEventListener('mousemove', onMouseMove);
	    document.addEventListener('mouseup', onMouseUp);
	  });
	  dragArea.addEventListener('touchstart', function (e) {
	    isResizing = true;
	    e.stopPropagation();
	    e.preventDefault();
	    clicked = {
	      height: container.clientHeight,
	      clientY: e.touches[0].clientY
	    };

	    var onMouseMove = function onMouseMove(e) {
	      if (!isResizing) {
	        return;
	      }

	      var currentHeight = Math.max(clicked.clientY - e.touches[0].clientY + clicked.height, minHeight);

	      if (currentHeight > minHeight && currentHeight < maxHeight) {
	        onResize(currentHeight);
	      }
	    };

	    var onMouseUp = function onMouseUp(e) {
	      isResizing = false;
	      clicked = false;
	      document.removeEventListener('touchmove', onMouseMove);
	      document.removeEventListener('touchend', onMouseUp);
	    };

	    document.addEventListener('touchmove', onMouseMove);
	    document.addEventListener('touchend', onMouseUp);
	  });
	}

	var STORAGE_KEY = 'bottom_panel_inner_height';

	var getSavedHeight = function getSavedHeight() {
	  return parseInt(localStorage.getItem(STORAGE_KEY)) || MIN_HEIGHT;
	};

	var saveHeight = function saveHeight(height) {
	  localStorage.setItem(STORAGE_KEY, height);
	};

	var BottomPanel = /*#__PURE__*/function (_PositionPanel) {
	  _inherits(BottomPanel, _PositionPanel);

	  var _super = _createSuper(BottomPanel);

	  function BottomPanel(panel) {
	    var _this;

	    _classCallCheck(this, BottomPanel);

	    _this = _super.call(this, panel);

	    var container = _this.$container.get(0);

	    var dragArea = $$1('#bottom-panel-drag-area').get(0);
	    dragResize({
	      dragArea: dragArea,
	      container: container,
	      onResize: function onResize(height) {
	        return _this.onResizeContainer(height);
	      }
	    });
	    var $wrapEl = $$1('<div>').css('height', _this.$panel.height());

	    _this.$panel.wrap($wrapEl);

	    return _this;
	  }

	  _createClass(BottomPanel, [{
	    key: "type",
	    get: function get() {
	      return 'bottom';
	    }
	  }, {
	    key: "$panel",
	    get: function get() {
	      return $$1('#bottom-panel');
	    }
	  }, {
	    key: "$inner",
	    get: function get() {
	      if (!this.$innerObj) {
	        this.$innerObj = this.$panel.find('#bottom-panel-inner');
	      }

	      return this.$innerObj;
	    }
	  }, {
	    key: "$container",
	    get: function get() {
	      return this.$panel.find('#bottom-panel-container');
	    }
	  }, {
	    key: "onResizeContainer",
	    value: function onResizeContainer(height) {
	      this.$container.css('height', height);
	      saveHeight(height);
	    }
	  }, {
	    key: "open",
	    value: function open() {
	      var link = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	      this.$panel.addClass('is-open');
	      this.$container.css({
	        height: getSavedHeight(),
	        bottom: -getSavedHeight()
	      }).velocity({
	        bottom: 0
	      }, {
	        duration: 300
	      });
	      return _get(_getPrototypeOf(BottomPanel.prototype), "open", this).call(this, link);
	    }
	  }, {
	    key: "close",
	    value: function close() {
	      var _this2 = this;

	      var d = $$1.Deferred();
	      this.$container.velocity({
	        bottom: -getSavedHeight()
	      }, {
	        duration: 300,
	        complete: function complete() {
	          _this2.$panel.removeClass('is-open');

	          _this2.$container.css({
	            height: 0,
	            bottom: 0
	          });

	          d.resolve();
	        }
	      });
	      return d.promise().pipe(function () {
	        return _get(_getPrototypeOf(BottomPanel.prototype), "close", _this2).call(_this2);
	      });
	    }
	  }, {
	    key: "showPreload",
	    value: function showPreload($container) {
	      this.$preload = $$1('<div>').addClass('panel-loader').append('<span></span><span></span><span></span><span></span>');
	      this.$inner.append(this.$preload);
	    }
	  }, {
	    key: "hidePreload",
	    value: function hidePreload() {
	      if (this.$preload) {
	        this.$preload.remove();
	      }
	    }
	  }, {
	    key: "show",
	    value: function show(block) {
	      $$1(block).addClass('is-showed');
	      $$1(block).addClass('is-showed');
	      $$1(document).trigger('panel.showed');
	      $$1(block).velocity({
	        opacity: 1
	      }, {
	        duration: 300
	      });
	    }
	  }, {
	    key: "hide",
	    value: function hide() {
	      this.$inner.children('.panel-block').removeClass('is-showed').css('opacity', 0);
	      return true;
	    }
	  }]);

	  return BottomPanel;
	}(PositionPanel);

	var ESCAPE_KEY_CODE = 27;

	var Panel = function ($) {
	  var Panel = /*#__PURE__*/function () {
	    function Panel(options) {
	      var _this = this;

	      _classCallCheck(this, Panel);

	      this.options = $.extend({}, Panel.Defaults, options);
	      this.openned = null;
	      this.initPanels();
	      this.isFancyboxOpened = false;
	      $(document).on('beforeLoad.fb', function () {
	        return _this.isFancyboxOpened = true;
	      });
	      $(document).on('afterClose.fb', function () {
	        return _this.isFancyboxOpened = false;
	      });
	      $(document).keyup(function (e) {
	        if (!_this.isFancyboxOpened && e.keyCode === ESCAPE_KEY_CODE) {
	          _this.close();
	        }
	      });
	      $(document).on('click', '[data-panel-close]', function () {
	        return _this.close();
	      });
	    }

	    _createClass(Panel, [{
	      key: "initPanels",
	      value: function initPanels() {
	        this.panels = {};
	        this.panels['right'] = new RightPanel(this);
	        this.panels['bottom'] = new BottomPanel(this);
	      }
	    }, {
	      key: "load",
	      value: function load(url) {
	        var _this2 = this;

	        var d = $.Deferred();
	        $(document).trigger('panel.before_load', [url]);
	        $.ajax({
	          url: url
	        }).then(function (result) {
	          if (!_this2.openned) {
	            d.reject();
	          }

	          var resultJson = BX.parseJSON(result);

	          if (resultJson) {
	            if (resultJson.STYLES) {
	              _this2.processStyles(resultJson.STYLES);
	            }

	            if (resultJson.SCRIPTS) {
	              _this2.processScripts(resultJson.SCRIPTS, function () {
	                d.resolve(resultJson.HTML);
	              });
	            } else {
	              d.resolve(resultJson.HTML);
	            }
	          } else {
	            d.resolve(result);
	          }
	        });
	        return d.promise();
	      }
	    }, {
	      key: "reload",
	      value: function reload(url) {
	        for (var i in this.panels) {
	          if (!this.panels.hasOwnProperty(i)) {
	            continue;
	          }

	          var panel = this.panels[i];

	          if (panel && panel.blocks[url]) {
	            panel.update(url);
	          }
	        }
	      }
	    }, {
	      key: "processStyles",
	      value: function processStyles() {
	        var html = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	        var processed = BX.processHTML(html, false);
	        BX.loadCSS(processed.STYLE);
	      }
	    }, {
	      key: "processScripts",
	      value: function processScripts() {
	        var html = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	        var successFn = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
	        var processed = BX.processHTML(html, false);
	        BX.ajax.processScripts(processed.SCRIPT, false, function () {
	          return successFn();
	        });
	      }
	    }, {
	      key: "open",
	      value: function open() {
	        var _this3 = this;

	        var link = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
	        var position = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'right';
	        var panel = this.panels[position];

	        if (!panel || !link) {
	          return;
	        }

	        if (this.openned) {
	          return this.close().then(function () {
	            return _this3.open(link, position);
	          });
	        }

	        this.openned = panel;
	        $(document).trigger('panel.before_open');
	        return panel.open(link);
	      }
	    }, {
	      key: "close",
	      value: function close() {
	        var _this4 = this;

	        if (this.openned) {
	          $(document).trigger('panel.before_close');
	          return this.openned.close().then(function () {
	            $(document).trigger('panel.closed');
	            _this4.openned = null;
	            return true;
	          });
	        }

	        return $.Deferred().promise();
	      }
	    }], [{
	      key: "Defaults",
	      get: function get() {
	        return {
	          classes: {}
	        };
	      }
	    }]);

	    return Panel;
	  }();

	  return Panel;
	}(jQuery);

	$.fn.setHtmlByUrl = function (options) {
	  var settings = $.extend({
	    'url': ''
	  }, options);
	  return this.each(function () {
	    if ('' != settings.url) {
	      var $this = $(this);
	      $.ajax({
	        type: 'GET',
	        dataType: 'html',
	        url: settings.url,
	        beforeSend: function beforeSend() {
	          if ('localStorage' in window && window['localStorage'] !== null) {
	            var data = localStorage.getItem(settings.url);

	            if (data) {
	              localStorage.setItem(settings.url, data);
	              $this.append(data);
	              return false;
	            }

	            return true;
	          }
	        },
	        success: function success(data) {
	          localStorage.setItem(settings.url, data);
	          $this.append(data);
	        }
	      });
	    }
	  });
	};

	/*!
	 * @preserve
	 *
	 * Readmore.js jQuery plugin
	 * Author: @jed_foster
	 * Project home: http://jedfoster.github.io/Readmore.js
	 * Licensed under the MIT license
	 *
	 * Debounce function from http://davidwalsh.name/javascript-debounce-function
	 */

	/* global jQuery */
	(function ($) {

	  var readmore = 'readmore',
	      defaults = {
	    speed: 100,
	    collapsedHeight: 200,
	    heightMargin: 16,
	    moreLink: '<a href="#">Read More</a>',
	    lessLink: '<a href="#">Close</a>',
	    embedCSS: true,
	    blockCSS: 'display: block; width: 100%;',
	    startOpen: false,
	    // callbacks
	    beforeToggle: function beforeToggle() {},
	    afterToggle: function afterToggle() {}
	  },
	      cssEmbedded = {},
	      uniqueIdCounter = 0;

	  function debounce(func, wait, immediate) {
	    var timeout;
	    return function () {
	      var context = this,
	          args = arguments;

	      var later = function later() {
	        timeout = null;

	        if (!immediate) {
	          func.apply(context, args);
	        }
	      };

	      var callNow = immediate && !timeout;
	      clearTimeout(timeout);
	      timeout = setTimeout(later, wait);

	      if (callNow) {
	        func.apply(context, args);
	      }
	    };
	  }

	  function uniqueId(prefix) {
	    var id = ++uniqueIdCounter;
	    return String(prefix == null ? 'rmjs-' : prefix) + id;
	  }

	  function setBoxHeights(element) {
	    var el = element.clone().css({
	      height: 'auto',
	      width: element.width(),
	      maxHeight: 'none',
	      overflow: 'hidden'
	    }).insertAfter(element),
	        expandedHeight = el.outerHeight(),
	        cssMaxHeight = parseInt(el.css({
	      maxHeight: ''
	    }).css('max-height').replace(/[^-\d\.]/g, ''), 10),
	        defaultHeight = element.data('defaultHeight');
	    el.remove();
	    var collapsedHeight = cssMaxHeight || element.data('collapsedHeight') || defaultHeight; // Store our measurements.

	    element.data({
	      expandedHeight: expandedHeight,
	      maxHeight: cssMaxHeight,
	      collapsedHeight: collapsedHeight
	    }) // and disable any `max-height` property set in CSS
	    .css({
	      maxHeight: 'none'
	    });
	  }

	  var resizeBoxes = debounce(function () {
	    $('[data-readmore]').each(function () {
	      var current = $(this),
	          isExpanded = current.attr('aria-expanded') === 'true';
	      setBoxHeights(current);
	      current.css({
	        height: current.data(isExpanded ? 'expandedHeight' : 'collapsedHeight')
	      });
	    });
	  }, 100);

	  function embedCSS(options) {
	    if (!cssEmbedded[options.selector]) {
	      var styles = ' ';

	      if (options.embedCSS && options.blockCSS !== '') {
	        styles += options.selector + ' + [data-readmore-toggle], ' + options.selector + '[data-readmore]{' + options.blockCSS + '}';
	      } // Include the transition CSS even if embedCSS is false


	      styles += options.selector + '[data-readmore]{' + 'transition: height ' + options.speed + 'ms;' + 'overflow: hidden;' + '}';

	      (function (d, u) {
	        var css = d.createElement('style');
	        css.type = 'text/css';

	        if (css.styleSheet) {
	          css.styleSheet.cssText = u;
	        } else {
	          css.appendChild(d.createTextNode(u));
	        }

	        d.getElementsByTagName('head')[0].appendChild(css);
	      })(document, styles);

	      cssEmbedded[options.selector] = true;
	    }
	  }

	  function Readmore(element, options) {
	    this.element = element;
	    this.options = $.extend({}, defaults, options);
	    embedCSS(this.options);
	    this._defaults = defaults;
	    this._name = readmore;
	    this.init(); // IE8 chokes on `window.addEventListener`, so need to test for support.

	    if (window.addEventListener) {
	      // Need to resize boxes when the page has fully loaded.
	      window.addEventListener('load', resizeBoxes);
	      window.addEventListener('resize', resizeBoxes);
	    } else {
	      window.attachEvent('load', resizeBoxes);
	      window.attachEvent('resize', resizeBoxes);
	    }
	  }

	  Readmore.prototype = {
	    init: function init() {
	      var current = $(this.element);
	      current.data({
	        defaultHeight: this.options.collapsedHeight,
	        heightMargin: this.options.heightMargin
	      });
	      setBoxHeights(current);
	      var collapsedHeight = current.data('collapsedHeight'),
	          heightMargin = current.data('heightMargin');

	      if (current.outerHeight(true) <= collapsedHeight + heightMargin) {
	        // The block is shorter than the limit, so there's no need to truncate it.
	        return true;
	      } else {
	        var id = current.attr('id') || uniqueId(),
	            useLink = this.options.startOpen ? this.options.lessLink : this.options.moreLink;
	        current.attr({
	          'data-readmore': '',
	          'aria-expanded': this.options.startOpen,
	          'id': id
	        });
	        current.after($(useLink).on('click', function (_this) {
	          return function (event) {
	            _this.toggle(this, current[0], event);
	          };
	        }(this)).attr({
	          'data-readmore-toggle': '',
	          'aria-controls': id
	        }));

	        if (!this.options.startOpen) {
	          current.css({
	            height: collapsedHeight
	          });
	        }
	      }
	    },
	    toggle: function toggle(trigger, element, event) {
	      if (event) {
	        event.preventDefault();
	      }

	      if (!trigger) {
	        trigger = $('[aria-controls="' + _this.element.id + '"]')[0];
	      }

	      if (!element) {
	        element = _this.element;
	      }

	      var $element = $(element),
	          newHeight = '',
	          newLink = '',
	          expanded = false,
	          collapsedHeight = $element.data('collapsedHeight');

	      if ($element.height() <= collapsedHeight) {
	        newHeight = $element.data('expandedHeight') + 'px';
	        newLink = 'lessLink';
	        expanded = true;
	      } else {
	        newHeight = collapsedHeight;
	        newLink = 'moreLink';
	      } // Fire beforeToggle callback
	      // Since we determined the new "expanded" state above we're now out of sync
	      // with our true current state, so we need to flip the value of `expanded`


	      this.options.beforeToggle(trigger, $element, !expanded);
	      $element.css({
	        'height': newHeight
	      }); // Fire afterToggle callback

	      $element.on('transitionend', function (_this) {
	        return function () {
	          _this.options.afterToggle(trigger, $element, expanded);

	          $(this).attr({
	            'aria-expanded': expanded
	          }).off('transitionend');
	        };
	      }(this));
	      $(trigger).replaceWith($(this.options[newLink]).on('click', function (_this) {
	        return function (event) {
	          _this.toggle(this, element, event);
	        };
	      }(this)).attr({
	        'data-readmore-toggle': '',
	        'aria-controls': $element.attr('id')
	      }));
	    },
	    destroy: function destroy() {
	      $(this.element).each(function () {
	        var current = $(this);
	        current.attr({
	          'data-readmore': null,
	          'aria-expanded': null
	        }).css({
	          maxHeight: '',
	          height: ''
	        }).next('[data-readmore-toggle]').remove();
	        current.removeData();
	      });
	    }
	  };

	  $.fn.readmore = function (options) {
	    var args = arguments,
	        selector = this.selector;
	    options = options || {};

	    if (_typeof(options) === 'object') {
	      return this.each(function () {
	        if ($.data(this, 'plugin_' + readmore)) {
	          var instance = $.data(this, 'plugin_' + readmore);
	          instance.destroy.apply(instance);
	        }

	        options.selector = selector;
	        $.data(this, 'plugin_' + readmore, new Readmore(this, options));
	      });
	    } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
	      return this.each(function () {
	        var instance = $.data(this, 'plugin_' + readmore);

	        if (instance instanceof Readmore && typeof instance[options] === 'function') {
	          instance[options].apply(instance, Array.prototype.slice.call(args, 1));
	        }
	      });
	    }
	  };
	})(jQuery);

	global.RS = global.RS || {};
	merge_1(global.RS, {
	  Init: init$2,
	  Animations: {},
	  EventHandlers: {},
	  Utils: {
	    Popper: Popper,
	    ResizeSensor: ResizeSensor$1,
	    isDesktop: isDesktop,
	    imageInCache: imageInCache,
	    popup: popup,
	    overlay: {
	      show: show,
	      hide: hide
	    }
	  }
	});
	$$1(document).ready(function () {
	  var panel = new Panel();
	  merge_1(global.RS, {
	    Panel: panel
	  });
	});

	function onReady() {
	  // prevent sidebar menu item click event
	  $$1(document).on('click', '.js-smenu-item__toggle', function (e) {
	    e.preventDefault();
	  }); // reload page after city change

	  BX.addCustomEvent('rs.location_change', function (result) {
	    if (result.redirect != undefined) {
	      window.location.href = result.redirect;
	    } else {
	      BX.reload();
	    }
	  }); // Fix work of fancybox

	  var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
	  $$1(document).on('beforeLoad.fb', function (e, instance, slide) {
	    $$1(".side-panel-controls, .side-panel__inner").css('margin-right', scrollbarWidth);
	    $$1(".bottom-panel__container").css('overflow-y', 'scroll');
	    $$1('.js-fix-scroll').addClass('js-fix-scroll--fixed').css('padding-right', scrollbarWidth);
	  });
	  $$1(document).on('afterClose.fb', function (e, instance, slide) {
	    if (!$$1.fancybox.getInstance()) {
	      $$1(".side-panel-controls, .side-panel__inner, .bottom-panel__inner").css('margin-right', 0);
	      $$1(".bottom-panel__container").css('overflow-y', 'visible');
	      $$1('.js-fix-scroll').removeClass('js-fix-scroll--fixed').css('padding-right', 0);
	    }
	  }); // accordions fix

	  $$1(document).on('show.bs.collapse', '.collapse', function () {
	    var $card = $$1(this).closest('.card');
	    $card.addClass('card-active');
	  });
	  $$1(document).on('hidden.bs.collapse', '.collapse', function () {
	    var $card = $$1(this).closest('.card');
	    $card.removeClass('card-active');
	  }); // Update captcha code

	  $$1(document).on('click', '[data-captcha-update-code]', function (e) {
	    e.preventDefault();
	    var $el = $$1(this);
	    var $form = $el.closest('form');

	    if (!$form.length) {
	      retirn;
	    }

	    $$1.getJSON(RS.Options.siteDir + 'ajax/captcha.php', function (res) {
	      var $img = $form.find('img[src*="/bitrix/tools/captcha.php"]');
	      $img.attr('src', res.src);
	      var $captchaSid = $form.find('input[name="captcha_sid"]');
	      $captchaSid.val(res.code);
	    });
	  });
	}

	$$1(window).ready(onReady);

	function onReady$1() {
	  init$2();
	  bxloader();
	} // composite data recieved


	function onFrameDataReceived() {
	  var json = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

	  if (!(json.dynamicBlocks || []).length) {
	    return;
	  }

	  json.dynamicBlocks.forEach(function (block, index) {
	    init$2([], document.querySelector(block.ID));
	  });
	}

	$(window).ready(onReady$1);

	if (window.frameCacheVars !== undefined) {
	  BX.addCustomEvent("onFrameDataReceived", function (json) {
	    return onFrameDataReceived(json);
	  });
	}

}(jQuery));

//# sourceMappingURL=main.js.map
