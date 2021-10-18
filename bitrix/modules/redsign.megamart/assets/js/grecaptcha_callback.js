if (!window._grecaptchaCallback)
{
    window._grecaptchaCallback = function()
    {
        window._grecaptchaClientId = grecaptcha.render('grecaptcha-inline-badge', {
            sitekey: "#PUBLIC_KEY#",
            badge: "inline",
            size: 'invisible'
        });
    }
}