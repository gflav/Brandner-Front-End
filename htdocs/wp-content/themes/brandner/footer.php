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
<?php wp_footer(); ?>
</body>
</html>