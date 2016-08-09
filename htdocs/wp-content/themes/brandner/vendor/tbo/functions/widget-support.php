<?php

add_action( 'widgets_init', 'tbo_widgets_init' );
function tbo_widgets_init() {
  
  // enable shortcodes in widget areas
  
  add_filter('widget_text', 'do_shortcode');
  
  // register widget areas
  
  register_sidebar(array(
    'name' => 'Footer Top',
    'id' => 'footer-top',
    'description' => 'Top of footer area',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  
  register_sidebar(array(
    'name' => 'Footer Widgets',
    'id' => 'footer-widgets',
    'description' => 'Text widgets for the footer area.',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  
  register_sidebar(array(
    'name' => 'Footer 1',
    'id' => 'footer-1',
    'description' => 'Footer column one.',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ));
  
  register_sidebar(array(
    'name' => 'Footer 2',
    'id' => 'footer-2',
    'description' => 'Footer column two.',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ));
	
}