<?php

function tbo_nav_menu_item( $title, $url, $order, $parent = 0 ){
  $item = new stdClass();
  $item->ID = 1000000 + $order + parent;
  $item->db_id = $item->ID;
  $item->title = $title;
  $item->url = $url;
  $item->menu_order = $order;
  $item->menu_item_parent = $parent;
  $item->type = '';
  $item->object = '';
  $item->object_id = '';
  $item->classes = array();
  $item->target = '';
  $item->attr_title = '';
  $item->description = '';
  $item->xfn = '';
  $item->status = '';
  return $item;
}

// add collection to the mobile menu
add_filter('wp_nav_menu_objects', 'tbo_nav_menu_objects', 10, 2);
function tbo_nav_menu_objects($items, $menu) {
  
  static $main;
  
  if($menu->menu == 'main-nav') {
    $main = $items;
  }
  
  /**
   * main-nav should come first, so it will be set first.
   * This way it has all the classes we need.
   */
  
  if($menu->menu == 'mobile-nav') {
  
    if(!empty($main)) {
      
      $categories = [];
      $new = [];
      
      $taxonomies = ['product_category', 'finish_category'];
      foreach($taxonomies as $taxonomy) {
        $terms = get_terms(array(
          'taxonomy' => $taxonomy,
          'hide_empty' => TRUE,
          'parent' => 0,
        ));
        foreach($terms as $term) {
          $t = get_terms($taxonomy, array(
            'child_of' => $term->term_id,
          ));
          $term->taxonomy = $taxonomy;
          $term->children = $t;
          $categories[$term->slug] = $term;
          foreach($t as $idx => $i) {
            // products
            $post_type = $taxonomy == 'product_category' ? 'bd_product' : 'finish';
            $args = array(
              'post_type' => $post_type,
              'tax_query' => array(
                array(
                  'taxonomy' => $taxonomy,
                  'terms' => $i->slug,
                  'field' => 'slug',
                ),
              ),
              'orderby' => 'menu_order',
              'order' => 'ASC',
              'posts_per_page' => -1,
            );
            $products = get_posts($args);
            if(!empty($products)) {
              $i->children = $products;
            }
            wp_reset_postdata();
          } // foreach
        } // foreach
      } // foreach
      
      $inc = 0;
      foreach($main as $item) {
        $slug = sanitize_title($item->title);
        if(isset($categories[$slug])) {
          $item->classes[] = 'menu-item-has-children';
          $term = $categories[$slug];
          foreach($term->children as $child) {
            $link = get_term_link($child, $term->taxonomy);
            $m = tbo_nav_menu_item($child->name, $link, ++$inc, $item->ID);
            $new[] = $m;
            if(!empty($child->children)) {
              foreach($child->children as $product) {
                $link = get_permalink($product);
                $new[] = tbo_nav_menu_item($product->post_title, $link, ++$inc, $m->ID);
              }
            }
          }
        }
      }
      
      $items = array_merge($items, $main, $new);
      
    }
    
  }
  
  return $items;
  
}