<?php

// vendor functions
$vendor_path = 'vendor/tbo';
$vendor_files = [
	'functions/helper.php',
	'functions/alter.php',
	'functions/customizer.php',
	'functions/scripts-admin.php',
	'functions/scripts.php',
  'functions/scripts-cart.php',
	'functions/theme-support.php',
	'functions/content-type.php',
	'functions/widget-support.php',
	'functions/rewrite.php',
	'functions/custom-field.php',
	'functions/meta-box.php',
	'functions/shortcode.php',
	'functions/shortcode-projects.php',
	'functions/shortcode-service.php',
  'functions/shortcode-teammember.php',
	'functions/visual-composer.php',
	'functions/filter.php',
  'functions/woocommerce.php',
  'functions/menu-mobile.php',
  'functions/gravity-forms.php',
];
foreach($vendor_files as $file) {
	locate_template("$vendor_path/$file", $load=TRUE, $require_once=TRUE);	
}

// glflav
locate_template("vendor/gflav/functions.php", $load=TRUE, $require_once=TRUE);	

// TODO: don't do this
add_filter('post_type_link', 'bd_custom_perms', 20, 4);
function bd_custom_perms($post_link, $post, $leavename, $sample){
	if( $post->post_type == 'bd_product' ){
		$term = false;
		$slug = get_query_var( 'term' );
		if( $slug ){
			$term = get_term_by( 'slug', $slug, 'product_category' );
		}

		if( !$term ){
			$terms = wp_get_object_terms( $post->ID, 'product_category' );
			foreach( $terms as $term ){
				if( $term->parent ){
					break;
				}
			}
		}

		$term_parent = get_term($term->parent, 'product_category');
		$post_link = home_url('collections/'.$term_parent->slug.'/'.$term->slug.'/'.$post->post_name.'.html');

	}else if( 'finish' == $post->post_type ){
		$term = false;
		$slug = get_query_var( 'term' );
		if( $slug ){
			$term = get_term_by( 'slug', $slug, 'finish_category' );
		}

		if( !$term ){
			$terms = wp_get_object_terms( $post->ID, 'finish_category' );
			foreach( $terms as $term ){
				if( $term->parent ){
					break;
				}
			}
		}

		$term_parent = get_term($term->parent, 'finish_category');
		$post_link = home_url('materials/'.$term_parent->slug.'/'.$term->slug.'/'.$post->post_name.'.html');
	} else if($post->post_type == 'product') {
    // woocommerce product
    $matches = get_posts([
      'numberposts' => 1,
      'post_type' => 'bd_product',
      'meta_query' => [
          'key' => 'product',
          'value' => $post->ID,
          'compare' => 'IN'
        ]
      ]);
    if(!empty($matches)) {
      $display = array_pop($matches);
      $post_link = get_permalink($display);
    }
  }
	return $post_link;
}
