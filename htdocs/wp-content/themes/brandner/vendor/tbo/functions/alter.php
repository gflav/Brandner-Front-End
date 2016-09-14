<?php

// stop wordpress from trying to be smart
// for the .html paths
remove_filter('template_redirect', 'redirect_canonical');

add_action('init', 'tbo_init', 100);
function tbo_init() {
  /**
   * Don't brag.
   **/
  remove_action('wp_head', 'wp_generator');
  if(function_exists('visual_composer')) {
    remove_action('wp_head', array(visual_composer(), 'addMetaData'));
  }
}

// alter post query as needed
add_action('pre_get_posts', 'tbo_pre_get_posts');
function tbo_pre_get_posts($wp_query){
  // all pages unlimited except for search
  if(in_array(tbo_arg(0), ['collections', 'materials'])) {
    $wp_query->set('posts_per_page', -1);
  }
  // enable finish to be visible on the taxonomy page /materials/steel/[sub]
  if(!$wp_query->is_main_query() || !isset($wp_query->query_vars['finish_category']) || !$wp_query->is_archive() || !$wp_query->is_tax())
		return;
	$wp_query->set('post_type', 'finish');
}

// adjust classes for icon links
add_filter( 'nav_menu_link_attributes', 'tbo_menu_link_attributes', 10, 3 );
function tbo_menu_link_attributes($atts, $item, $args) {
  switch($atts['rel']) {
    case 'icon-like':
    case 'icon-cart':
    case 'icon-search':
      $atts['class'] = $atts['rel'];
      $atts['rel'] = '';
    break;
  }
  return $atts;
}