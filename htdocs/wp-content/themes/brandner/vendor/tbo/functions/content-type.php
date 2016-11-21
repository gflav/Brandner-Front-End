<?php

add_action('init', 'bd_register_taxonomoies');
function bd_register_taxonomoies(){
	
	// aka collections
	register_taxonomy('product_category', array('bd_product'), array(
		'label' => 'Product Categories',
		'labels' => array(
			'name' => _x('Product Categories', 'taxonomy general name'),
			'singular_name' => _x('Product Category', 'taxonomy singular name' ),
			'search_items' =>  __('Search Product Categories'),
			'all_items' => __('All Product Categories'),
			'parent_item' => __('Parent Product Category'),
			'parent_item_colon' => __('Parent Product Category:'),
			'edit_item' => __('Edit Product Category'),
			'update_item' => __('Update Product Category'),
			'add_new_item' => __('Add New Product Category'),
			'new_item_name' => __('New Product Category Name'),
			'menu_name' => __('Product Category'),
		),
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tag_cloud' => true,
		'hierarchical' => true,
		'query_var' => 'product_category',
		'rewrite' => array('slug' => 'collections', 'with_front' => false, 'hierarchical' => true),
		'capabilities' => array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms'
		),
	));

	// aka materials
	register_taxonomy('finish_category', array('finish'), array(
		'label' => 'Finish Categories',
		'labels' => array(
			'name' => _x('Finish Categories', 'taxonomy general name'),
			'singular_name' => _x('Finish Category', 'taxonomy singular name' ),
			'search_items' =>  __('Search Finish Categories'),
			'all_items' => __('All Finish Categories'),
			'parent_item' => __('Parent Finish Category'),
			'parent_item_colon' => __('Parent Finish Category:'),
			'edit_item' => __('Edit Finish Category'),
			'update_item' => __('Update Finish Category'),
			'add_new_item' => __('Add New Finish Category'),
			'new_item_name' => __('New Finish Category Name'),
			'menu_name' => __('Finish Category'),
		),
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tag_cloud' => true,
		'hierarchical' => true,
		'query_var' => 'finish_category',
		'rewrite' => array('slug' => 'materials', 'with_front' => false, 'hierarchical' => true),
		'capabilities' => array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms'
		),
	));
}

add_action('init', 'bd_register_post_types');
function bd_register_post_types(){
  
	register_post_type('bd_slide', array(
		'label' => 'Slides',
		'labels' => array(
			'name' => 'Slides',
			'singular_name' => 'Slide',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new Slide',
			'edit_item' => 'Edit Slide',
			'new_item' => 'New Slide',
			'view_item' => 'View Slide',
			'search_items' => 'Search Slides',
			'not_found' => 'No Slides found',
			'not_found_in_trash' => 'No Slides found in Trash',
		),
		'description' => 'Slides for home sildeshow',
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => get_stylesheet_directory_uri().'/vendor/tbo/images/admin/custom-post-icon-slides.jpg',
		'capability_type' => 'post',
		'hierarchial' => false,
		'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes',),
		'has_archive' => false,
		'rewrite' => false,
		'query_var' => true,
		'can_export' => true,
	));

	register_post_type('finish', array(
		'label' => 'Finishes',
		'labels' => array(
			'name' => 'Finishes',
			'singular_name' => 'Finish',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new Finish',
			'edit_item' => 'Edit Finish',
			'new_item' => 'New Finish',
			'view_item' => 'View Finish',
			'search_items' => 'Search Finishes',
			'not_found' => 'No Finishes found',
			'not_found_in_trash' => 'No Finishes found in Trash',
		),
		'description' => 'Finish types for products',
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => get_stylesheet_directory_uri().'/vendor/tbo/images/admin/finishes-icon.png',
		'capability_type' => 'post',
		'hierarchial' => false,
		'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions'),
		'has_archive' => false,
		'rewrite' => false,
	));

	register_post_type('bd_product', array(
		'label' => 'Products',
		'labels' => array(
			'name' => 'Products',
			'singular_name' => 'Product',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new Product',
			'edit_item' => 'Edit Product',
			'new_item' => 'New Product',
			'view_item' => 'View Product',
			'search_items' => 'Search Products',
			'not_found' => 'No Products found',
			'not_found_in_trash' => 'No Products found in Trash',
		),
		'description' => 'Products listed on site',
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => get_stylesheet_directory_uri().'/vendor/tbo/images/admin/products-icon.png',
		'capability_type' => 'post',
		'hierarchial' => false,
		'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions'),
		'taxonomies' => array('product_category'),
		'has_archive' => false,
		'rewrite' => array(
			'slug' => 'bd_product',
			'with_front' => true,
			'feeds' => false,
			'pages' => true,
		),
		'query_var' => true,
		'can_export' => true,
		'show_in_nav_menus' => true,
	));

	register_post_type('project', array(
		'label' => 'Portfolio',
		'labels' => array(
			'name' => 'Portfolio',
			'singular_name' => 'Project',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new Project',
			'edit_item' => 'Edit Project',
			'new_item' => 'New Project',
			'view_item' => 'View Project',
			'search_items' => 'Search Portfolio',
			'not_found' => 'No Projects found',
			'not_found_in_trash' => 'No Projects found in Trash',
		),
		'description' => 'Portfolio projects',
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 10,
		'menu_icon' => get_stylesheet_directory_uri().'/vendor/tbo/images/admin/portfolio-icon.png',
		'capability_type' => 'post',
		'hierarchial' => false,
		'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions',),
		'has_archive' => true,
		'rewrite' => array(
			'slug' => 'projects',
			'with_front' => true,
			'feeds' => true,
			'pages' => true,
		),
		'query_var' => true,
		'can_export' => true,
		'show_in_nav_menus' => true,
    /** REST API **/
    'show_in_rest'       => true,
    'rest_base'          => 'projects',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
	));
}
