<div class="container">
	<div id="site-content">
	<?php
	if(have_posts()):
		while(have_posts()): the_post();
  ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="cart-header">
          <h2><?php the_title(); ?></h2>
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
                      <a class="btn btn-checkout" href="/checkout/">Proceed to checkout</a>
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