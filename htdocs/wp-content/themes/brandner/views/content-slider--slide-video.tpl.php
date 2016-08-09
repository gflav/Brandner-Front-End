<li>
  <?php if(!empty($item->video)): ?>
    <?php if(!empty($item->image)): ?>
      <div class="slide-image"><?php echo $item->image; ?></div>
    <?php else: ?>
      <?php if(tbo()->util->contains($item->video, 'vimeo')): ?>
      <?php echo do_shortcode('[vimeo]'.$item->video.'[/vimeo]'); ?>
      <?php else: ?>
      <?php echo do_shortcode('[video src="'.$item->video.'"]'); ?>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
  <div class="row">
    <div class="col-sm-12 slide-content">
      <?php if(!empty($item->heading)): ?>
      <h2><?php echo $item->heading; ?></h2>
      <?php endif; ?>
      <?php if(!empty($item->video)): ?>
        <div class="play-btn trigger-modal-video" data-video="<?php echo $item->video; ?>">Watch the Video<span class="icon icon-triangle icon-triangle-up"></span></div>
      <?php endif; ?>
    </div>
  </div>
</li>