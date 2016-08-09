<?php

add_action( 'after_setup_theme', 'tbo_woocommerce_support' );
function tbo_woocommerce_support() {
  add_theme_support( 'woocommerce' );
  add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}