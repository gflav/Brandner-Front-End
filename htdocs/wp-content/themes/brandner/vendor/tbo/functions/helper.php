<?php

function tbo_arg($index=NULL, $path=NULL) {
	static $arguments;
	if(!isset($path)) {
		$path = trim($_SERVER['REQUEST_URI'], '/');
	}
	if(!isset($arguments[$path])) {
		$arguments[$path] = explode('/', $path);
	}
	if(!isset($index)) {
		return $arguments[$path];
	}
	if(isset($arguments[$path][$index])) {
		return $arguments[$path][$index];
	}
}

function tbo_year($year=NULL) {
  if(!isset($year)) {
    $year = date('Y');
  } else {
    $current = date('Y');
    if($year < $current) {
      return $year . '-' . $current;
    } else if($year > $current) {
      return $current . '-' . $year;
    }
  }
  return $year;
}

function tbo_theme_mod($mod, $class='') {
  $output = '';
  $media_id = get_theme_mod($mod);
  if($media_id) {
    $output = wp_get_attachment_image($media_id, $size='full', $icon=FALSE, $attr=array('class' => $class));
  }
  return $output;
}

/**
 * Uses custom for collections & materials, and their children, along with products & finishes.
 *
 * Everything else uses the yoast plugin, if enabled.
 *
 * Else, nothing.
 *
 * @return string
 * The rendered breadcrumb html.
 */
function tbo_breadcrumb() {
	global $post;
  $output = '';
	if ( function_exists('yoast_breadcrumb') ) {
    $output = yoast_breadcrumb('<div id="brandnerdesign-footer-breadcrumbs">','</div>', $dsiplay=FALSE);
  }
  return $output;
}

function tbo_get_nav_sub($slug, $base='collections') {
	$post_parent = $base == 'collections' ? 20 : 2559;
  $items = array();
  $posts = get_posts(array(
		'post_type' => 'page',
		'post_parent' => $post_parent,
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	));
	if($posts) {
    foreach($posts as $p) {
      $p->permalink = get_the_permalink($p);
      $p->featured_image = 
      $items[$p->ID] = $p;
    }
  }
  return $items;
}

function tbo_get_term_image_first($t, $taxonomy) {
	$output = '';
  if($image_id = get_term_meta($t->term_id, 'image', $single=TRUE)) {
    // image has been uploaded
    if($image = wp_get_attachment_image($image_id, $size='product-thumb')) {
      $output = $image;
    }
  } else {
    // first image
    $args = array(
      'numberposts' => 1,
      'post_type' => (tbo_arg(0) == 'collections' ? 'bd_product' : 'finish'),
      'tax_query' => array(
        array(
          'taxonomy' => $taxonomy,
          'field' => 'slug',
          'terms' => $t->slug,
        )
      ),
      //'orderby' => 'menu_order',
    );
    $posts = get_posts($args);
    if($posts) {
      foreach($posts as $item) {
        $output = get_the_post_thumbnail($item, $size='product-thumb');
        break;
      }
    }
  }
	return $output;
}

function tbo_get_search_result_count() {
	global $wp_query;
	$total_results = $wp_query->found_posts;
	return $total_results;
}

function tbo_get_gallery($post, $size='full', $attr='') {
  $items = [];
  $gallery = get_field('post_media', $post);
  if(!empty($gallery)) {
    foreach($gallery as $g) {
      if(!empty($g['bd_img_gal'])) {
        foreach($g['bd_img_gal'] as $item) {
          if(isset($item['sizes'][$size])) {
            $items[] = $item['sizes'][$size]; 
          } else {
            $items[] = $item['url'];
          }
        }  
      }
      // TODO: pull from vimeo part as well
    }
  }
  return $items;
}

function tbo_get_gallery_image($post, $size='full', $attr='') {
  // check image gallery
  $gallery = get_field('post_media', $post);
  if(!empty($gallery)) {
    foreach($gallery as $g) {
      if(!empty($g['bd_img_gal'])) {
        foreach($g['bd_img_gal'] as $item) {
          if($image = wp_get_attachment_image($item['ID'], $size, $icon=FALSE, $attr)) {
            return $image;
          }
        }  
      }
    }
  }
  // check attachments
  $images = get_attached_media('image', $post->ID);
  if(!empty($images)) {
    $image = array_shift($images);
    if($featured_image = wp_get_attachment_image($image->ID, $size, $icon=FALSE, $attr)) {
      return $featured_image;
    }
  }
  return FALSE;
}

// TODO: refactor these 2 functions
function tbo_get_gallery_image_src($post, $size='full') {
  // check image gallery
  $gallery = get_field('post_media', $post);
  if(!empty($gallery)) {
    foreach($gallery as $g) {
      if(!empty($g['bd_img_gal'])) {
        foreach($g['bd_img_gal'] as $item) {
          if(isset($item['sizes'][$size])) {
            return $item['sizes'][$size]; 
          } else {
            return $item['url'];
          }
        }  
      }
    }
  }
  // check attachments
  $images = get_attached_media('image', $post->ID);
  if(!empty($images)) {
    $image = array_shift($images);
    if($featured_image = wp_get_attachment_image_src($image->ID, $size)) {
      return $featured_image;
    }
  }
  return FALSE;
}

function tbo_get_api_route($post) {
  $base  = '/wp-json/wp/v2';
  switch($post->post_type) {
    case 'project':
      return $base . '/projects/' . $post->ID;
    break;
  }
  return $base . '/' . $post->post_type . '/' . $post->ID;
}