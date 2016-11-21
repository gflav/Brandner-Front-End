(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    el: null,
    
    source: null,
    template: null,
    
    init: function() {
      
      this.el = $('.tile-grid');
      if(this.el.length > 0) {
        this.setup();
        this.listen();
      }

    },
    
    setup: function() {
      
      var $width = $(window).width();
      if($width > 768) {
        $width = '580';
      }
      
      $('.product-finish', this.el).each(function() {
        var $this = $(this);
        var $html = $('.product-finish-tooltip', $this).html();
        $this.tooltip({
          placement: 'auto',
          html: true,
          title: $html,
          trigger: 'manual',
          template: '<div class="tooltip tooltip-tile" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="width:'+$width+'px;"></div></div>'
        });
      });
      
    },
    
    templates: function() {
      
    },
    
    listen: function() {
      
      $('.product-finish', this.el).click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        $this.tooltip('show');
      });
      
      $('.product-finish', this.el).hover(
        function hoverOver() {
          var $this = $(this);
          $('.product-finish').not($this).tooltip('hide');
          $this.tooltip('show');
        },
        function hoverOut() {
          var $this = $(this);
          $tbo.once(function() {
            if(!$('.product-finish:hover').length) {
              $this.tooltip('hide');  
            }
          }, 'product-finish-tooltip', 1000);
        }
      );
      
    }
    
  };
  
})(jQuery);