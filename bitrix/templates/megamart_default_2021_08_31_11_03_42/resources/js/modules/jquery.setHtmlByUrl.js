$.fn.setHtmlByUrl = function(options) {
	var settings = $.extend({
		'url': ''
	}, options);
	return this.each(function() {
		if ('' != settings.url) {
			var $this = $(this);
			$.ajax({
				type: 'GET',
				dataType: 'html',
				url: settings.url,
				beforeSend: function() {
					if ('localStorage' in window && window['localStorage'] !== null) {
						const data = localStorage.getItem(settings.url);
						if (data) {
							localStorage.setItem(settings.url, data);
							$this.append(data);
							return false;
						}
						return true;
					}
				},
				success: function(data) {
					localStorage.setItem(settings.url, data);
					$this.append(data);
				},
			});
		}
	});
};
