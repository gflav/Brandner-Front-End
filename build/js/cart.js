(function($){
  
  $(document).ready(function() {
    $cart.init();
  });
  
  var $cart = {
    
    items: 0,
    count: 0,
    
    init: function() {
      this.setup();
      this.listen();
      this.render();
    },
    
    setup: function() {
      
      if($cart_data) {
        this.count = $cart_data.count;
      }
      
    },
    
    listen: function() {
      
      // buy now
      $('.btn-buy-now').click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        var $pid = $this.attr('data-product-id');
        var $checkout_url = $this.attr('data-checkout-url');
        // add to cart
        $.post('/cart/?add-to-cart='+$pid).done(function() {
          // go to checkout
          window.location.href = $checkout_url;
        });
      });
      
      // add to cart
      $('.btn-cart-add').click(function($evt) {
        $evt.preventDefault();
        var $pid = $(this).attr('data-product-id');
        $modal.open('cart', {url: '/cart/?add-to-cart='+$pid, selector: '#site-content .woocommerce', cache:false});
      });
      
      // view cart
      $('a.icon-cart,li.icon-cart a').click(function($evt) {
        $evt.preventDefault();
        $modal.open('cart', {url: '/cart/', selector: '#site-content .woocommerce'});
      });
      
      $(document).on('click', '.btn-continue-shopping', function($evt) {
        if($(this).closest('.modal').length > 0) {
          $evt.preventDefault();
          $modal.close('cart').remove('cart');
        }
      });
      
      $(document).on('modal.close', function($evt, $name) {
        if($name == 'cart') {
          $modal.remove('cart');
        }
      });
      
    },
    
    render: function() {
      
      if (this.count && this.count > 0) {
        
        var $source = $('#template-cart-items').html();
        var $template = Handlebars.compile($source);
        $('.icon-cart').parent().prepend($template({count: this.count}));
        
      }
      
    }
    
  };
  
  window.$cart = $cart;
  
})(jQuery);