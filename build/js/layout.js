(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    el: null,
    image: null,
    
    init: function() {
      this.el = $('.project-intro-info');
      this.image = $('.project-intro-graphic img');
      if(this.el.length > 0 && this.image.length > 0) {
        this.listen();
        $tbo.once(function() {
          $controller.render();
        }, 'layout', 400);
      }
    },
    
    listen: function() {
      
      $(window).resize(function() {
        $tbo.once(function() {
          $controller.render();
        }, 'layout');
      });
      
    },
    
    render: function() {
      
      if($tbo.getMode() == 'desktop') {
        var $margin_top = Math.abs(this.image.css('margin-top').replace('px', '')) * 2;
        var $height = this.image.height() - $margin_top;
        // check again
        if($height === 0) {
          $tbo.once(function() {
            $controller.render();
          }, 'layout');
        }
        this.el.css('height', $height+'px');
      } else {
        this.el.css('height', 'auto');
      }
      
    }
    
  };
  
})(jQuery);