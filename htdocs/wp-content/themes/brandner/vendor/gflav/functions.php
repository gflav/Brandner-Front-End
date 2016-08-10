<?php

add_action( 'wp_enqueue_scripts', 'gflav_scripts');
function gflav_scripts() {
  
  $baseDir = realpath(__DIR__);
  $baseUri = get_stylesheet_directory_uri() . '/vendor/gflav';
  
  // css
  $version = filemtime($baseDir.'/css/gflav.css');
  wp_enqueue_style('gflav-style', $baseUri.'/css/gflav.css', $deps=array(), $version);
  
  // js
  $js = [
    'gflav-script' => '/js/gflav.js',
  ];
  
  foreach($js as $handle => $script) {
    if(file_exists($baseDir.$script)) {
      $version = filemtime($baseDir.$script);
      wp_enqueue_script($handle, $baseUri.$script, $deps=array('jquery'), $version, $in_footer=TRUE);
    }
  }
  
}
