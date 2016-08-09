(function($){

	var brandnerdesign = {
		
		// global value
		
		KEYCODE_ESC: 27,
		
		KEYWORD_MIN_LENGTH: 3,
    
    setting: {
      sliderSpeed: 5000,
      cssEase: "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
      jsEasing: 'easeInOutCubic'
    },
		
		// init

		init : function() {

			var width = window.innerWidth || document.documentElement.clientWidth;
			brandnerdesign.setMode(width);

			brandnerdesign.UI = {
				window					: $(window),
				html					: $("html"),
				body					: $("body"),
				header					: $("header"),
				homeSlideShow			: $("#brandnerdesign-home-slideshow"),
				homeSlideShowUL			: $("#brandnerdesign-home-slideshow-ul"),
				homeSlideShowPager		: $("#brandnerdesign-home-slideshow-pager"),
				mobileNavBtn 			: $("#mobile-menu-button-container"),
				headerLinks 			: $("#brandnerdesign-header-links")
			};

			brandnerdesign.UI.window.resize(function() {
				brandnerdesign.resizeHandler();
			});

			if(brandnerdesign.UI.homeSlideShow.length){
				brandnerdesign.initHomeSlideShow();
			}

			brandnerdesign.initMobileNav();
			brandnerdesign.initForms();
		},


		initMobileNav : function() {
      
      var $mobile = $('#dl-menu');
      $mobile.dlmenu();

		},

		initForms : function(){

			$(document).on('focus', '.modal-instance-quote input', function($evt) {
				$(this).parent().addClass("focus");
			});

			$(document).on('blur', '.modal-instance-quote input', function($evt) {
				$(this).parent().removeClass("focus");
			});

		},


		initHomeSlideShow : function(){
			brandnerdesign.UI.homeSlideShowUL.slick({
				infinite: true,
				autoplay:false,
				autoplaySpeed: brandnerdesign.setting.sliderSpeed,
				arrows: false,
				dots: true,
				adaptiveHeight: true,
				cssEase: brandnerdesign.setting.cssEase,
				appendDots : brandnerdesign.UI.homeSlideShowPager,
				customPaging: function(slider, i) {
					return $('<span></span>');
				},

			});

		},


		resizeHandler : function() {
			width = window.innerWidth || document.documentElement.clientWidth;
			brandnerdesign.setMode(width);
      if($tbo.getMode() == 'desktop') {
        brandnerdesign.UI.mobileNavBtn.removeClass('open');
        brandnerdesign.UI.body.removeClass("mobile-menu-open");
      }
		},

		setMode : function(w) {
			if(w >= 768){
				brandnerdesign.mode = "desktop";
			}else{
				brandnerdesign.mode = "mobile";
			}
		}
};

$(document).ready(function () {
	brandnerdesign.init();
});

window.brandnerdesign = brandnerdesign;

})(jQuery);
