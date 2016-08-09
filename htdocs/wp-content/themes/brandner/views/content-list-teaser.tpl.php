<?php
  $image_size = 'portfolio-large';
  $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $image_size);
  $featured_image_src = $image_src[0];
  if(empty($featured_image_src)) {
    // check gallery
    $featured_image_src = tbo_get_gallery_image_src($post, $image_size);
  }
?>
<div class="project-featured">
  <div class="row">
    <div class="col-sm-12 <?php if(!empty($featured_image_src)): ?>col-lg-5 col-xl-4<?php endif; ?>">
      <a id="<?php echo $post->post_name; ?>"></a>
      <h2><?php echo $post->post_title; ?></h2>
      <?php if($post->post_type == 'press-release'): ?>
        <?php
          $publication_date = get_field('publication_date', $post->ID);
          if(!empty($publication_date)): ?>
            <div class="entry-date"><?php echo $publication_date; ?></div>
        <?php endif; ?>
      <?php endif; ?>
      <?php echo apply_filters( 'the_content', $post->post_content); ?>
      <?php echo tbo()->view->load('buttons', array('post' => $post)); ?>
    </div>
    <?php if(!empty($featured_image_src)): ?>
      <?php if($post->post_type != 'press-release'): ?>
      <div class="col-sm-12 col-lg-7 col-xl-8"><div class="image trigger-gallery" data-route="<?php echo tbo_get_api_route($post); ?>" style="background:url(<?php echo $featured_image_src; ?>) no-repeat;"> <div class="project-caption icon-project">VIEW GALLERY</div></div></div>
      <?php else: ?>
      <div class="col-sm-12 col-lg-7 col-xl-8"><div class="image" style="background:url(<?php echo $featured_image_src; ?>) no-repeat;"></div> </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>