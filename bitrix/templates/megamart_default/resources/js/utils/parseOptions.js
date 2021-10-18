import $ from 'jquery'
import _isString from 'lodash/isString'
import _isObject from 'lodash/isObject'

export default function (options) {
	return (
		_isString(options) && options.trim() != '' ?
 			$.parseJSON(options) :
			(_isObject(options) ? options : {})
	)
}
