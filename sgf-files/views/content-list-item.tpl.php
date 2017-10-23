<?php
  $link = empty($anchor) ? get_the_permalink($post) : '#'.$post->post_name;
  $col_lg = 'col-lg-4';
  if($post_count == 5) {
    $col_lg = 'col-lg-2';
  }
  if($post_count == 4) {
    $col_lg = 'col-lg-3';
  }
  if($post_count == 3) {
    $col_lg = 'col-lg-4';
  }
?>
<li class="col-sm-12 col-md-6 <?php echo $col_lg; ?> <?php echo isset($css_class) ? $css_class : ''; ?>"><a href="<?php echo $link; ?>" data-post-id="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>