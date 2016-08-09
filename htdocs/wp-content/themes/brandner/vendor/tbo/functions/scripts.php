<?php

add_action( 'wp_enqueue_scripts', 'tbo_scripts');
function tbo_scripts() {
  
  tbo_scripts_deregister();
  
  // TODO: all.min.css / js
  
  $baseDir = realpath(__DIR__ . '/../');
  $baseUri = get_stylesheet_directory_uri() . '/vendor/tbo';
  
  // css
  
  $version = filemtime($baseDir.'/css/all.css');
  wp_enqueue_style('tbo-style', $baseUri.'/css/all.css', $deps=array(), $version);
  
  // js
  $js = [
    'tbo-vendor' => '/js/vendor.min.js',
    'tbo-script' => '/js/all.js',
  ];
  foreach($js as $handle => $script) {
    if(file_exists($baseDir.$script)) {
      $version = filemtime($baseDir.$script);
      wp_enqueue_script($handle, $baseUri.$script, $deps=array('jquery'), $version, $in_footer=TRUE);
    }
  }
  
}

function tbo_scripts_deregister() {
  
  // remove visual composers google fonts because we don't use them
  // TODO:
  
}
