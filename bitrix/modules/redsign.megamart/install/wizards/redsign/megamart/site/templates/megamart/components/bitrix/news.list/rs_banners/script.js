function rsBannersOnReady () {

    var options = $(".js-banner-options"),
        $jsBanners = $(".js-banners"),
		$preloader = $('.js-preloader');

    var isAutoAdjustHeight = false;
    if($(".rs-banners .rs-banners_banner:eq(0)").height() == 0) {
        isAutoAdjustHeight = true;
    }

    $jsBanners.redsignBanners({
        sliderAdapter: "owlAdapter",
        isAutoAdjustHeight: options.data('isAutoAdjustHeight') !== undefined ? options.data('isAutoAdjustHeight') : false,
        isAdjustBlocks: true,
        height:  options.data('height') !== undefined ? options.data('height') : 400,
        params: {
            items: 1,
            loop: $(".rs-banners .rs-banners_banner").length > 1 ? true : false,
            nav: true,
            dots: true,
            video: true,
            autoplay: options.data('autoplay') !== undefined ? options.data('autoplay') : true,
            autoplayTimeout:  options.data('autoplayTimeout') !== undefined ? options.data('autoplayTimeout') : 7000,
            autoplaySpeed:  options.data('autoplaySpeed') !== undefined ? options.data('autoplaySpeed') : 2000,
            smartSpeed:  options.data('autoplaySpeed') !== undefined ? options.data('autoplaySpeed') : 2000,
			navText: ['<svg class="icon-svg rs-banner-nav"><use xlink:href="#svg-arrow-left"></use></svg>',
                '<svg class="icon-svg rs-banner-nav"><use xlink:href="#svg-arrow-right"></use></svg>'],    
			
        }
    });

	var setBannerColor = function (e) {
		var carousel = $(this);

        setTimeout(function () {
            if (carousel.hasClass('owl-loaded')) {
                var $currentItem = carousel.find('.owl-item.active');
                var $banner = $currentItem.find('.rs-banners_banner');
                var color = $banner.data('text-color');
                if (color == 'dark') {
                    $('.l-head')
                        .removeClass('color-light')
                        .addClass('color-dark');
                } else {
                    $('.l-head')
                        .removeClass('color-dark')
                        .addClass('color-light');
                }
            }
        }.bind(this));
	}

	$jsBanners.on('changed.owl.carousel', setBannerColor)

    $jsBanners.on("rs.banners.images:load", function() {

        setTimeout(function() {
            //$(".js-sidebanners.js-sidebanners_selected").addClass('is-loading');
        })

        setTimeout(function() {
          //$(".js-mainbanners-container").css("opacity", 1);
		  $(".js-sidebanners.js-sidebanners_selected").addClass('is-loading');
		  $jsBanners.find('.rs-banners_banner').css('opacity', 1);
        }, 150);

		setTimeout(function () {
			$preloader.fadeOut(600);
		}, 500);

    });

    $(".js-banners").on("rs.banners.adjustblocks", function() {
        $(".rs-banners_infowrap").css("opacity", 1);
    });
}
