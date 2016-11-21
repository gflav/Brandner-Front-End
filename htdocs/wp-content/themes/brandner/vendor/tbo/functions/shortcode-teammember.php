<?php

add_shortcode('team_list', 'tbo_shortcode_team_list');
function tbo_shortcode_team_list($atts, $content=NULL) {
  
  $atts = shortcode_atts(array(
      'posts_per_page' => -1,
      'post_type' => 'team-member',
      'format' => 'image-fancy',
      'css_class' => '',
      'heading' => 'Meet the Team',
    ), $atts
  );
  
  extract($atts);
  
  $posts = get_posts(array(
    'posts_per_page' => $posts_per_page,
    'post_type' => $post_type,
    // fixes custom sort
    'orderby' => 'menu_order',
    'order' => 'ASC',
  ));
  
  $atts['posts'] = $posts;
  
  return tbo()->view->load('content-list--image-fancy', $atts);
  
}