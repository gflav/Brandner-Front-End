<div class="container">
	<div id="site-content">
	<?php
	if(have_posts()):
		while(have_posts()): the_post();
    $cart_count = WC()->cart->get_cart_contents_count();
  ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="cart-header">
          <?php
            $title = sprintf( _n( '%s item', '%s items', $cart_count, 'brandner' ), $cart_count ) . ' added to your cart';
            if(empty($cart_count)):
              $title = 'Your Cart';
            endif;
          ?>
          <h2><?php echo $title; ?></h2>
          <a class="trigger-modal-close" href=""><span class="close-x">X</span></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
          <div class="cart-content">
              <div class="cart-details">
                 <?php the_content(); ?>
              </div>
          </div>
      </div>
    </div>
    <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
    <div class="row">
      <div class="col-sm-12">
          <div class="cart-headline">
              <div class="cart-footer">
                  <div class="btn-group">
                      <a class="btn btn-continue-shopping" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">Continue Shopping</a>
                      <?php if($cart_count > 0): ?>
                      <a class="btn btn-checkout" href="<?php echo tbo_get_checkout_url(); ?>">Proceed to checkout</a>
                      <?php endif; ?>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <?php endif; ?>
  <?php
		endwhile;
	endif;
	?>
	</div>
</div>