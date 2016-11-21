(function($) {

  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    init: function() {
      setTimeout(function() {
        $controller.listen();  
      });
    },
    
    listen: function() {
      
      $(document).on('click', '.btn', function() {
        var $this = $(this);
        var $label = $controller.findLabel($this);
        $controller.track({eventAction: $this.text(), eventLabel: $label});
      });
      
      $(document).on('click', 'a[href^="tel:"]', function() {
         var $this = $(this);
         $controller.track({eventAction: 'Phone Call', eventLabel: $this.text()});
      });

      $(document).on('click', 'a[href^="mailto:"]', function() {
         var $this = $(this);
         $controller.track({eventAction: 'Mail', eventLabel: $this.text()});
      });
      
    },
    
    // helper functions
    
    track: function($o) {
      try {
        var $options = $.extend({}, {
            hitType: 'event',
            eventCategory: 'Click',
            eventAction: '',
            eventLabel: ''
        }, $o);
        if($.type(window.ga) == 'function') {
          ga('send', $options);
        }
        this.log($options);
      } catch($e) {
        this.log($e);
      }
    },
    
    log: function($msg) {
      if(console) {
        console.log($msg);
      }
    },
    
    findLabel: function($this) {
      
      var $test = $this.closest('.project-featured');
      
      // project
      if($test.length > 0) {
        return $test.find('h2').text();
      }
      
      // product
      $test = $this.closest('.product-detail-container');
      if($test.length > 0) {
        return $('h1').text();
      }
      
    }
    
  };
  
})(jQuery);