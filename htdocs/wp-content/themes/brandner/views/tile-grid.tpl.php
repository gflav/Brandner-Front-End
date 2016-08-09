<?php

  // set title
  if(!isset($title) || empty($title)):
    $title = has_term('ready-to-ship', 'product_category', $post) ? 'Shown In' : 'MATERIAL / FINISHES';
  endif;
  
  $finish_ids = get_post_meta($post->ID, '_finishes', true);
  if(!empty($finish_ids)):
  
    $tiles = array();
  
    $finishes = new WP_Query(array(
      'post_type' => 'finish',
      'post__in' => $finish_ids,
      'posts_per_page' => -1,
    ));
    
    if($finishes->have_posts()):
      while($finishes->have_posts()):
      
        $finishes->the_post();
        $p = $finishes->post;
        
        $tile = (object)array(
          'thumb' => get_the_post_thumbnail($p, 'finishes-thumb', array('title' => '')),
          'image' => get_the_post_thumbnail($p, 'finishes-large'),
          'details' => '<div class="details"><h5>' . get_the_title($p) . '</h5>' . get_the_content($p) . '</div>',
        );
        
        $tiles[] = $tile;
      
      endwhile;
    endif;
    
  wp_reset_postdata();
  
?>
<div class="tile-grid">
  <h3><?php echo $title; ?></h3>
  <div class="product-finish-container">
  <?php foreach($tiles as $tile): ?>
    <div class="product-finish">
      <?php echo $tile->thumb; ?>
      <div class="product-finish-tooltip" style="display:none;">
        <div class="row">
          <div class="col-sm-6">
            <?php echo $tile->image; ?>
          </div>
          <div class="col-sm-6">
            <?php echo $tile->details; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>