(function($){
  
  var $selector = '.product-gallery';
  var $container = null;
  
  $(document).ready(function() {
    init();
  });
  
  function init() {
    $container = $($selector);
    if ($container.length > 0) {
      setup();
      listen();
    }
  }
  
  function setup() {
    
    $container.slick({
      infinite: true,
      autoplay:true,
      autoplaySpeed: brandnerdesign.setting.sliderSpeed,
      arrows: false,
      dots: false,
      cssEase: brandnerdesign.setting.cssEase,
      adaptiveHeight: true,
    });
    
    // set first active
    $('.product-thumbs a').first().toggleClass('active');
    
  }
  
  function listen() {
    
    // events
    
    // set thumbs active
    $container.on('afterChange', function($slick, $currentSlide) {
      var $idx = $currentSlide.currentSlide;
      $('.product-thumbs a').removeClass('active');
      $('.product-thumbs li:eq('+$idx+') a').addClass('active');
    });
    
    // custom pager clicks
    
    $('.product-thumbs a').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      $container.slick('slickGoTo', $this.parent().index());
    });
    
  }

})(jQuery);