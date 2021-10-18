this.RS = this.RS || {};
(function (exports) {
	'use strict';

	var FilterConditions = /*#__PURE__*/function () {
	  babelHelpers.createClass(FilterConditions, null, [{
	    key: "PROPERTY_PREFIX",
	    get: function get() {
	      return 'CondIBProp';
	    }
	  }, {
	    key: "ALLOWED_FIELDS",
	    get: function get() {
	      return ['CondIBXmlID', 'CondIBSection', 'CondIBDateActiveFrom', 'CondIBDateActiveTo', 'CondIBSort', 'CondIBDateCreate', 'CondIBCreatedBy', 'CondIBTimestampX', 'CondIBModifiedBy', 'CondIBTags', 'CondCatQuantity', 'CondCatWeight'];
	    }
	  }]);

	  function FilterConditions() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, FilterConditions);
	    this.contId = options.contId;
	    BX.addCustomEvent('onTreeConditionsInit', this.initTree.bind(this));
	  }

	  babelHelpers.createClass(FilterConditions, [{
	    key: "initTree",
	    value: function initTree(arParams, obTree, obControls) {
	      if (this.contId !== arParams.parentContainer) return;
	      this.arParams = arParams;
	      this.obTree = obTree;
	      this.obControls = obControls;
	      this.modifyTreeParams();
	    }
	  }, {
	    key: "modifyTreeParams",
	    value: function modifyTreeParams() {
	      this.obControls.filter(function (control) {
	        return control.group;
	      }).forEach(this.modifyCondGroup);
	      this.obControls.filter(function (control) {
	        return !control.group;
	      }).forEach(this.modifyCondValueGroup);
	    }
	  }, {
	    key: "modifyCondGroup",
	    value: function modifyCondGroup(_ref) {
	      var visual = _ref.visual,
	          control = _ref.control;

	      if (visual) {
	        visual.values.filter(function (v) {
	          return v.True === 'False';
	        }).forEach(function (v, k) {
	          visual.values.splice(k, 1);
	          visual.logic.splice(k, 1);
	        });
	      }

	      if (control) {
	        control.forEach(function (v, k) {
	          v.dontShowFirstOption = true;
	          if (v.id === 'True') delete v.values.False;
	        });
	      }
	    }
	  }, {
	    key: "modifyCondValueGroup",
	    value: function modifyCondValueGroup(ctrl) {
	      if (!((ctrl || {}).children || []).length) return;
	      ctrl.children = ctrl.children.filter(function (_ref2) {
	        var controlId = _ref2.controlId;

	        var _controlId$split = controlId.split(':'),
	            _controlId$split2 = babelHelpers.slicedToArray(_controlId$split, 3),
	            controlName = _controlId$split2[0],
	            iblockId = _controlId$split2[1],
	            propertyId = _controlId$split2[2];

	        return FilterConditions.ALLOWED_FIELDS.includes(controlId) || controlName === FilterConditions.PROPERTY_PREFIX && propertyId;
	      });
	    }
	  }]);
	  return FilterConditions;
	}();

	exports.FilterConditions = FilterConditions;

}((this.RS.DevFunc = this.RS.DevFunc || {})));
//# sourceMappingURL=filter_conditions.bundle.js.map
