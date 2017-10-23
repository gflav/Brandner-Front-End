<div class="promo-container <?php echo $css_class; ?>">
	<?php if(!empty($url)): ?>
	<a href="<?php echo $url; ?>" <?php if($type == 'project'): ?>title="<?php echo $caption; ?>" class="trigger-gallery" data-route="<?php echo $route; ?>"<?php endif; ?> <?php if(!empty($video)): ?> class="trigger-modal-video" data-video="<?php echo $video; ?>"<?php endif; ?>>
	<?php endif; ?>
	  <?php if($image): ?>
	    <div class="promo-image">
	    <?php echo $image; ?>
	    </div>
	  <?php endif; ?>
	  <?php if($caption): ?>
	    <div class="promo-caption caption-<?php echo $caption_position; ?>">
	      <div class="promo-caption-left">
	        <?php echo $caption; ?>
	      </div>
	      <?php if($type == 'project'): ?>
	      <div class="promo-caption-right icon-project"><span>View Project</span></div>
	      <?php endif; ?>
	    </div>
	  <?php endif; ?>
	  <?php if(!empty($video)): ?>
	  <div class="play-btn">Play Video<span class="icon-triangle-up"></span></div>
	  <?php endif; ?>
	<?php if(!empty($url)): ?>
	</a>
	<?php endif; ?>
	</div>