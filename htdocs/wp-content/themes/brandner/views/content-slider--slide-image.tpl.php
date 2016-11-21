<li class="slideshow--<?php echo $item->content_align; ?>">
  <div class="slide-image"><?php echo $item->image; ?></div>
  <div class="row">
    <div class="col-sm-12 slide-content">
      <?php if(!empty($item->heading)): ?>
      <h2><?php echo $item->heading; ?></h2>
      <?php endif; ?>
      <?php if(!empty($item->description)): ?>
      <p><?php echo $item->description; ?></p>
      <?php endif; ?>
      <?php if(!empty($item->cta_text)): ?>
      <div class="cta-wrapper"><a href="<?php echo esc_url($item->link); ?>" target="_blank"><?php echo esc_html($item->cta_text); ?><span class="icon icon-triangle icon-triangle-up"></span></a></div>
      <?php endif; ?>
    </div>
  </div>
</li>