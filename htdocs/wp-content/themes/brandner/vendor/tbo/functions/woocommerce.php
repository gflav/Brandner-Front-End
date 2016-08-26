<?php

// add woocommerce support
// remove woocommerce styles
add_action( 'after_setup_theme', 'tbo_woocommerce_support' );
function tbo_woocommerce_support() {
  add_theme_support( 'woocommerce' );
  add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}

// ipn callback
// updates order status
function tbo_woocommcerce_ipn($posted) {
  $order_id = isset($posted['invoice']) ? $posted['invoice'] : '';
  if(!empty($order_id)) {
    $order = new WC_Order($order_id);
    $order->update_status('completed');
  }
}
add_action('paypal_ipn_for_wordpress_payment_status_completed', 'tbo_woocommcerce_ipn', 10, 1);