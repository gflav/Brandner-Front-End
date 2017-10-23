<div class="container">
	<?php if(is_active_sidebar('footer-top')): ?>
		<div class="footer-top">
			<?php dynamic_sidebar('footer-top'); ?>
		</div>
	<?php endif; ?>
	<footer>
    <div id="brandnerdesign-footer-top">
      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-8">
              <?php echo tbo_breadcrumb(); ?>
            </div>
            <div class="col-sm-4">
              <a href="#" class="trigger-top">Back to top</a>
            </div>
          </div>
        </div>
      </div>
    </div>
		<div id="brandnerdesign-footer-content">
			<div class="row">
				<div id="brandnerdesign-footer-content-left" class="col-sm-12 col-lg-6">
					<div class="row">
						<div class="col-sm-6">
              <?php if(is_active_sidebar('footer-1')): ?>
                <?php dynamic_sidebar('footer-1'); ?>
              <?php endif; ?>
						</div>
						<div class="col-sm-6">
							<?php if(is_active_sidebar('footer-2')): ?>
                <?php dynamic_sidebar('footer-2'); ?>
              <?php endif; ?>
						</div>
					</div>
				</div>
				<div id="brandnerdesign-footer-content-right" class="col-sm-12 col-lg-6">
					<?php dynamic_sidebar('footer-widgets'); ?>
				</div>
			</div>
		</div>
		<div class="brandnerdesign-footer-legal">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <?php
            wp_nav_menu(array(
              'theme_location' => 'footer-utility-nav',
              'container' => null,
            ));
          ?>
        </div>
        <div class="col-sm-12 col-md-6">
          <p>&copy;<?php echo tbo_year(2016); ?> BRANDNER DESIGN ALL RIGHTS RESERVED</p>
        </div>
      </div>
		</div>
	</footer>
</div>
<?php echo tbo()->view->load('handlebarsjs/templates'); ?>
<div class="hidden quote-form-hidden">
  <div class="close-quote">
    <span>&times;</span>
  </div>
  <div class="row">
    <div class="col-sm-12 col-lg-7">
      <?php gravity_form( 3, $display_title = true, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex, $echo = true ); ?>
    </div>
    <div class="col-sm-12 col-lg-5">
      <div class="image">
        <img src="" class="img" alt="">
      </div>
      <h4></h4>
      <div class="product-summary"></div>
      <h1></h1>
    </div>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>