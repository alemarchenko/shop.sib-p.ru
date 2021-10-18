
(function(document, window, grecaptcha)
{
    if (!grecaptcha)
    {
        return;
    }

     //ie11 polyfills
	(function(Element)
	{
		Element.matches = Element.matches ||
			Element.mozMatchesSelector ||
			Element.msMatchesSelector ||
			Element.oMatchesSelector ||
			Element.webkitMatchesSelector;

		Element.closest = Element.closest || function closest(selector)
		{
			if (!this) return null;
			if (this.matches(selector)) return this;
			if (!this.parentElement)
			{
				return null
			}
			else return this.parentElement.closest(selector)
		};
    }(Element.prototype));
   

	function GReCaptchaV3(parent, replacements)
	{
        replacements = replacements || [];
		this.parentObserver = null;
		
		var cachedForms = [];
		
		var generateToken = function(callback)
		{
			window.grecaptcha.ready(function () {
                if (window._grecaptchaClientId != undefined)
                {
                    window.grecaptcha.execute(_grecaptchaClientId, {})
                        .then(function(token) {
                            callback(token);
                        });
                }    
            });
		}

		var retoken = function() {
			cachedForms.forEach(function(form) {
				var input = form.querySelector('input[name="g-recaptcha-response"]');

				if (input)
				{
					generateToken(function(token) {
						input.value = token;
					});
				}
			});
		}

		BX.addCustomEvent('onAjaxSuccess', retoken);

		var createToken = function(form) 
        {
			var input = form.querySelector('g-recaptcha-response');
			if (!input)
			{
				input = document.createElement('input')
				input.type = 'hidden';
				input.name = 'g-recaptcha-response';

				form.insertBefore(input, form.firstChild);
			}

			generateToken(function(token) {
				input.value = token;
			});
        };

		var replacer = function(form)
		{
			var captchaSid = form.querySelector('input[name="captcha_sid"], input[name="captcha_code"]');
			var captchaWord = form.querySelector('input[name="captcha_word"]');

			if (captchaWord)
			{
				form.insertBefore(captchaWord, form.firstChild);
				captchaWord.type = 'hidden';
				captchaWord.required = false;
			}

			if (captchaSid)
			{
				form.insertBefore(captchaSid, form.firstChild);
			}

			for (var i in replacements)
			{
				var replaceElement = form.querySelector(replacements[i]);
				if (replaceElement)
				{
					// replaceElement.parentNode.removeChild(replaceElement);
					replaceElement.setAttribute('style', 'display:none !important');
				}
			}
		}

		var observer = function() {
			var captchas = parent.querySelectorAll('input[name="captcha_sid"], input[name="captcha_code"]');

			for (var i in captchas)
			{
				if (!captchas.hasOwnProperty(i)) continue;

				var form = captchas[i].closest('form');
				if (cachedForms.indexOf(form) !== -1) continue;

				createToken(form);
				replacer(form);

				cachedForms.push(form);
			}
		}

		var init = function() {
			
			if ('MutationObserver' in window) 
			{
				this.parentObserver = new MutationObserver(function() {
					observer();
				});
			
				this.parentObserver.observe(parent, {
					childList: true,
					subtree: true
				});
			}
		}

		init();
	}

    
    window._rsGReCaptcha = new GReCaptchaV3(document, '#SELECTORS#'.split(','));
	
}(document, window, window.grecaptcha));