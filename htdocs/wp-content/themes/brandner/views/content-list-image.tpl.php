<div class="col-sm-12 col-md-6 capability-pillar-container">
  <div class="brandnerdesign-capabilities-pillar" data-post-id="<?php echo $post->ID; ?>" data-href="<?php echo get_the_permalink($post); ?>">
    <?php if($featured_image = get_the_post_thumbnail($post, 'full', $attr='')): ?>
      <?php echo $featured_image; ?>
    <?php endif; ?>
    <span><?php echo $post->post_title; ?></span>
  </div>
</div>