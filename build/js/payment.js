(function($) {
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    selector: '.form-payment',
    el: null,
    
    init: function() {
      
      this.el = $(this.selector);
      if(this.el.length > 0) {
        
        this.listen();
        
      }
      
    },
    
    listen: function() {
      
      $(this.el).submit(function($evt) {
        
        var $account_number = $('[name="account_number"]', this.el);
        var $amount = $('[name="amount"]', this.el);
        
        // simple validation
        if(!$account_number.val() || $amount.val() <= 0) {
          $evt.preventDefault();
        } else {
          // valid
          
          var $item_name = $('[name="item_name"]', this.el);
          $item_name.val($item_name.val().replace('[account_number]', $account_number.val()));
          
        }
        
      });
      
    }
    
  };
  
  window.$payment = $controller; // NOTE: just in case it's needed outside of scope
  
})(jQuery);