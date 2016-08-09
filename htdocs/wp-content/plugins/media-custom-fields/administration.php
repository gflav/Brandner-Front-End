<?php

/**
 * Adding an admin stylesheet
 * 
 * The stylesheet is enqued for use in our admin page. It is later added with the 
 * admin_print_styles-[] action hook to ensure it is only loaded in our own plugin
 * page 
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 */
function tqmcf_admin_styles() {
   wp_enqueue_style( 'tqmcf_admin_stylesheet' );
}
   
   
/**
 * Register our settings
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 */
function tqmcf_register_settings() {
	register_setting( 'tqmcf_settings_group', 'tastique_media_custom_fields' );
}
  
/**
 * Displaying the admin pages
 *
 * This function contains (includes) the code for the admin pages
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 */
function tqmcf_admin_page() {  
   	global $wpdb;
    include('admin/administration_page.php');  
}  

/**
 * Create the admin pages
 *
 * This function takes care of preparing the admin page. It creates the menu entry, the
 * page itself, loads the options, and the stylesheet.
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 */
function tqmcf_admin() { 
	$options_page = add_media_page("Media Custom Fields", "Media Custom Fields", "edit_posts", "media-custom-fields", "tqmcf_admin_page");
    add_action( 'admin_init', 'tqmcf_register_settings' );
    add_action( 'admin_print_styles-' . $options_page, 'tqmcf_admin_styles' ); 
  	wp_register_style( 'tqmcf_admin_stylesheet', TQ_PLUGIN_URL . '/admin.css' );
} 

add_action('admin_menu', 'tqmcf_admin'); 
 







?>
