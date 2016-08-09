(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    source: null,
    template: null,
    
    init: function() {
      
      this.setup();
      this.listen();
      
    },
    
    setup: function() {
      
      this.templates();
      
    },
    
    templates: function() {
      
    },
    
    listen: function() {
      
    }
    
  };
  
})(jQuery);