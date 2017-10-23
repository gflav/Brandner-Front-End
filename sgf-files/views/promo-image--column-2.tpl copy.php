<div class="promo-image-column-2-container <?php echo $css_class; ?>">
  <div class="promo-column promo-first">
	<?php if($css_class === ' promo-type-column-2-left') { ?>
    	<?php echo $content; ?>
	<?php } else { ?>
	    <?php echo tbo()->view->load('promo-image', $atts); ?>		
	<?php } ?>		
  </div>
  <div class="promo-column promo-second">
	<?php if($css_class === ' promo-type-column-2-left') { ?>
	    <?php echo tbo()->view->load('promo-image', $atts); ?>		
	<?php } else { ?>
    	<?php echo $content; ?>
	<?php } ?>		
  </div>
</div>