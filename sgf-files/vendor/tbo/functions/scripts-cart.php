<?php

add_action( 'wp_enqueue_scripts', 'tbo_scripts_cart');
function tbo_scripts_cart() {
  
  if(function_exists('WC')) {
    
    $cart_count = WC()->cart->get_cart_contents_count();
    
    $params = array(
      'count' => $cart_count,
    );
    
    wp_localize_script('tbo-script', '$cart_data', $params);
    
  }
  
}