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
        var $pid = $(this).attr('data-product-id');
        $modal.open('cart', {url: '/checkout/?add-to-cart='+$pid, selector: '#site-content .woocommerce'});
      });
      
      // add to cart
      $('.btn-cart-add').click(function($evt) {
        $evt.preventDefault();
        var $pid = $(this).attr('data-product-id');
        $modal.open('cart', {url: '/checkout/?add-to-cart='+$pid, selector: '#site-content .woocommerce'});
      });
      
      // view cart
      $('a.icon-cart,li.icon-cart a').click(function($evt) {
        $evt.preventDefault();
        $modal.open('cart', {url: '/cart/', selector: '#site-content .woocommerce'});
      });
      
      $(document).on('click', '.btn-continue-shopping', function($evt) {
        $evt.preventDefault();
        $modal.close('cart');
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