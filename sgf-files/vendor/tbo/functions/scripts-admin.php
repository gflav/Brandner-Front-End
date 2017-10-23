<?php

// TODO: optimize output with gulp
add_action('admin_enqueue_scripts', 'bd_admin_enqueue');
function bd_admin_enqueue(){
	wp_enqueue_style('bd-admin-css', get_stylesheet_directory_uri().'/vendor/tbo/css/src/admin.css');
}