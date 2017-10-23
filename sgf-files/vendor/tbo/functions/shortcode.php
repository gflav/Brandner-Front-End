<?php

add_shortcode('logo', 'tbo_shortcode_logo');
function tbo_shortcode_logo() {
  return tbo_theme_mod('custom-logo', 'img-responsive logo');
}

add_shortcode('page_title', 'tbo_shortcode_page_title');
function tbo_shortcode_page_title() {
  return get_the_title(); 
}

add_shortcode('tbo_content_list', 'tbo_shortcode_content_list');
function tbo_shortcode_content_list($atts, $content=NULL) {
  
  extract(shortcode_atts(array(
      'posts_per_page' => -1,
      'post_type' => 'post',
      'format' => 'teaser',
      'css_class' => ''
    ), $atts
  ));
  
  $posts = get_posts(array(
    'posts_per_page'	=> $posts_per_page,
    'post_type'			=> $post_type,
    'orderby' => 'menu_order',
    'order' => 'ASC',
  ));
  
  $output = [];
  
  if($format == 'image') {
    $output[] = '<div class="row">';
  }
  
  if($format == 'list') {
    // list
    $output[] = tbo()->view->load('content-list', array('posts' => $posts, 'anchor' => TRUE, 'css_class' => $css_class));
  } else {
    // special formatting
    foreach($posts as $post) {
      switch($format) {
        case 'image':
          $output[] = tbo()->view->load('content-list-image', array('post' => $post));  
        break;
        case 'teaser':
          $output[] = tbo()->view->load('content-list-teaser', array('post' => $post));  
        break;
      }
    } // foreach
  }
  
  if($format == 'image') {
    $output[] = '</div>';
  }
  
  wp_reset_postdata();
  
  return join('', $output);
  
}

add_shortcode('promo_image', 'tbo_shortcode_promo_image');
function tbo_shortcode_promo_image($atts, $content=NULL) {
  
  extract($atts = shortcode_atts(array(
      'type' => 'post', // NOTE: default is post
      'caption_position' => 'center', // center, left, right
      'caption' => '',
      'image' => '',
      'url' => '',
      'css_class' => '',
    ), $atts
  ));
  
  $atts['css_class'] .= ' promo-type-' . $type;
  
  if($type == 'project' && !empty($url)) {
    if($posts = get_posts(['post_type' => 'project', 'name' => $url, 'posts_per_page' => 1])) {
      $post = array_shift($posts);
      $atts['route'] = '/wp-json/wp/v2/projects/' . $post->ID;
    }
  }
  
  if(!empty($image)) {
    $image = wp_get_attachment_image($image, $size='full');
    $atts['image'] = $image;
  }
  
  if(!empty($content)) {
    $content = tbo()->shortcode($content); // TODO: test if needed
    $atts['content'] = $content;
  }
  
  if(in_array($type, ['column-2-right', 'column-2-left'])) {
    return tbo()->view->load('promo-image--column-2', $atts);
  }
  
  // normal promo image
  return tbo()->view->load('promo-image', $atts);
  
}

add_shortcode('promo_video', 'tbo_shortcode_promo_video');
function tbo_shortcode_promo_video($atts, $content=NULL) {
  
  extract(shortcode_atts(array(
      'type' => 'post', // NOTE: default is post
      //'caption_position' => 'center', // center, left, right
      //'caption' => '',
      'image' => '',
      'video' => '',
      'css_class' => '',
    ), $atts
  ));
  
  $atts['css_class'] .= ' promo-type-' . $type;
  
  if(!empty($image)) {
    $image = wp_get_attachment_image($image, $size='full');
    $atts['image'] = $image;
  }
  
  if(!empty($content)) {
    //$content = tbo()->shortcode($content); // TODO: test if needed
    $atts['content'] = $content;
  }
  
  if(!empty($video)) {
    $atts['url'] = $video;
  }
  
  if(in_array($type, ['column-2-right', 'column-2-left'])) {
    return tbo()->view->load('promo-image--column-2', $atts);
  }
  
  // normal promo image
  return tbo()->view->load('promo-image', $atts);
  
}

add_shortcode('tbo_most_visited', 'tbo_shortcode_most_visited');
function tbo_shortcode_most_visited($atts, $content=NULL) {
  $output = '';
  if(function_exists('wp_most_popular_get_popular')) {
    $options = [
      'limit' => 7,
      'post_type' => array_keys(get_post_types()),
    ];
    $posts = wp_most_popular_get_popular( $options );
    if(!empty($posts)) {
      $output = tbo()->view->load('most-visited-list', ['posts' => $posts]);  
    }
    wp_reset_postdata();
  }
  return $output;
}

