<?php

add_action('wp_before_admin_bar_render', 'tbo_admin_bar');
function tbo_admin_bar() {
  
	global $wp_admin_bar;

  if(is_singular('bd_product')) {
    
    $wp_admin_bar->add_node(array(
      'id'    => 'tbo-regenerate-thumbs',
      'title' => 'Regenerate Thumbs',
      'href'  => admin_url('admin-ajax.php?post_id='.get_the_ID())
    ));
    
  }

}