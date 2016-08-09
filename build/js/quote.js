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
      this.source = $('#template-get-quote-form').html();
      this.template = Handlebars.compile(this.source);
    },
    
    listen: function() {
      
      $('.btn-quote').click(function($evt) {
        
        $evt.preventDefault();
        
        if($modal.exists('quote')) {
          $modal.open('quote');
        } else {
          // create form
          var $title = $('h1').text();
          var $body = $('.post-content').html();
          var $featured_image = $('.product-image:first-child').html();
          $modal.open('quote', {html: $controller.template({title: $title, body: $body, featured_image: $featured_image})});
        }
        
      });
      
    }
    
  };
  
})(jQuery);