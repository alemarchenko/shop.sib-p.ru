$(document).ready(function() {

    $(document).on('click', '.js-rstuning__selectbox__opener', function() {
        var $selectbox = $(this).closest('.js-rstuning__selectbox');

        if ($selectbox.hasClass('open')) {
            $selectbox
                .removeClass('open')
                .one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                    $selectbox.removeClass('closed')
                });
        } else {
            var $selectboxWasOpened = $('.js-rstuning__selectbox.open')
                .not($selectbox);

            $selectboxWasOpened
                .removeClass('open')
                .one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                    $selectboxWasOpened.removeClass('closed')
                });

            $selectbox
                .removeClass('closed')
                .addClass('open');
        }

        return false;
    });

    $(document).on('click', '.js-rstuning__selectbox__option:not(.active)', function(e) {
        var $option = $(this),
            $select = $option.closest('.js-rstuning__selectbox__select'),
            $input = $select.closest('.js-rs_option_info').find('input'),
            el = e.target;

        $input.val($option.attr('data-value'));
        $select.closest('.js-rs_option_info').find('.js-rstuning__selectbox__value').html($option.html());
        while ((el = el.parentElement) && !el.classList.contains('js-rs_option_info'));
        el.querySelector('input').value = $option.attr('data-value');
        el.querySelector('input').dispatchEvent(new Event('change'));
        $select.find('.js-rstuning__selectbox__option.active').removeClass('active');
        $option.addClass('active');
        $option.closest('.js-rstuning__selectbox').toggleClass('open');

        return false;
    });

    $(document).on('click', '.js-rstuning__selectbox__option.active', function(e) {
        $(this).closest('.js-rstuning__selectbox').toggleClass('open');

        return false;
    });

	$(document).on('click', function(e) {
        var $tuning = $('.js-rstuning');

		if ($tuning.hasClass('open')) {
			if ($(e.target).closest('.js-rstuning__selectbox.open').length > 0) {

			} else {
                var $selectboxOpened = $tuning.find('.js-rstuning__selectbox.open');

                $selectboxOpened
                    .removeClass('open')
                    .one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                        $selectboxOpened.removeClass('closed')
                    });
			}
		} else {
            var $selectboxOpened = $tuning.find('.js-rstuning__selectbox.open');

            $selectboxOpened
                .removeClass('open')
                .one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                    $selectboxOpened.removeClass('closed')
                });
        }
	});

});
