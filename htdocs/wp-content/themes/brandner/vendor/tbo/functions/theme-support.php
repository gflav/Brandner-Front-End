<?php

// TODO: no longer needed since there is a plugin for this now
// allow svg uploads
function tbo_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'tbo_mime_types');

// theme setup
add_action( 'after_setup_theme', 'tbo_setup_theme');
function tbo_setup_theme() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'pier4' to the name of your theme in all the template files
	 */
	//load_theme_textdomain( 'brandner', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
  
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );
  
  // thumbnail sizes
  add_image_size('finishes-large', 290, 290, true); // TODO: check if needed
  add_image_size('finishes-thumb', 33, 33, true); // TODO: check if needed
  add_image_size('finishes-archive-thumb', 256, 130, true ); // TODO: check if needed
  add_image_size('limited', 763, 374, true); // TODO: check if needed
	
	// collections
  add_image_size('portfolio-large', 910, 385, true ); // TODO: check if needed
  add_image_size('portfolio-small', 130, 160, true ); // TODO: check if needed
  add_image_size('portfolio-slideshow', 700, 470 ); // TODO: remove
	
	// TODO:
	add_image_size('product-thumb', 256, 170, true);
	
	// product detail page :: gallery
  add_image_size('viewer-large', 873, 471 );
  add_image_size('viewer-thumb', 120, 120, $crop=true);
  
  // team members
  add_image_size('profile-photo', 160, 200, $crop=TRUE);

	// menus
  register_nav_menus(array(
    'main-nav' => 'Main Navigation',
    'mobile-nav' => 'Mobile Navigation',
    'header-icons' => 'Header Icons',
    'footer-nav' => 'Footer Navigation', // TODO: check if needed
		'footer-featured' => 'Footer Featured',
		'footer-popular' => 'Footer Most Visited',
		'footer-utility-nav' => 'Footer Utility Navigation',
  ));
  
	/**
	 * Allows html5 markup on Wordpress stuff. Doesn't matter if theme uses it specifically though.
	 */
	add_theme_support( 'html5', array(
		'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 *
	 * Uncomment and add method to use.
	 */
	//add_editor_style( array( 'css/editor-style.css', tbo_fonts_url() ) );
  
} // function