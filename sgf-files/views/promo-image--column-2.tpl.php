<div class="promo-image-column-2-container <?php echo $css_class; ?>">
  <div class="promo-column promo-first">
    	<?php echo $content; ?>
  </div>
  <div class="promo-column promo-second">
	    <?php echo tbo()->view->load('promo-image', $atts); ?>		
  </div>
</div>