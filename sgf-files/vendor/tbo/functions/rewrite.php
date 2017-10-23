<?php

// flush rewrite rules
// TODO: what is this and why
//add_action( 'wp_loaded','bd_flush_rules' );
function bd_flush_rules(){
	$rules = get_option( 'rewrite_rules' );
	if (
		!isset($rules['collections/[^/]+/([^/]+)/?$'])
		|| !isset($rules['collections/[^/]+/([^/]+)/([^\.]+).html$'])
		|| !isset($rules['collections/[^/]+/([^/]+)/page/([^/]+)/?$'])
		|| !isset($rules['materials/[^/]+/([^/]+)/?$'])
		|| !isset($rules['materials/[^/]+/([^/]+)/([^\.]+).html$'])
		|| !isset($rules['materials/[^/]+/([^/]+)/page/([^/]+)/?$'])
		|| !isset($rules['materials/limited/?'])
	){
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// rewrite collections & materials 
add_filter( 'rewrite_rules_array','bd_insert_rewrite_rules' );
function bd_insert_rewrite_rules( $rules ){
  
	$newrules = array();
  
  // collections
  
	$newrules['collections/[^/]+/([^/]+)/?$'] = 'index.php?taxonomy=product_category&term=$matches[1]';
	$newrules['collections/[^/]+/([^/]+)/page/([^/]+)/?$'] = 'index.php?taxonomy=product_category&term=$matches[1]&paged=$matches[2]';
  
  // products
	$newrules['collections/[^/]+/([^/]+)/([^\.]+).html$'] = 'index.php?post_type=bd_product&taxonomy=product_category&term=$matches[1]&product_category=$matches[1]&name=$matches[2]';

	// materials
  
	$newrules['materials/[^/]+/([^/]+)/?$'] = 'index.php?taxonomy=finish_category&term=$matches[1]&finish_category=$matches[1]';
	$newrules['materials/[^/]+/([^/]+)/page/([^/]+)/?$'] = 'index.php?taxonomy=finish_category&term=$matches[1]&paged=$matches[2]';
  
  // finishes
	$newrules['materials/[^/]+/([^/]+)/([^\.]+).html$'] = 'index.php?post_type=finish&taxonomy=finish_category&term=$matches[1]&finish_category=$matches[1]&name=$matches[2]';

	// limited to use page
	$newrules['materials/limited/?'] = 'index.php?page_id=1523';

	return $newrules + $rules;

}