<?php

add_shortcode('vimeo', 'tbo_shortcode_vimeo');
function tbo_shortcode_vimeo($atts, $content=NULL) {
  
  $output = '';
  if(!empty($content)) {
    $url = $content;
    $vimeo_id = str_replace('https://vimeo.com/', '', $url);
    $output .= '<div class="video-container">';
    $output .= '<iframe src="https://player.vimeo.com/video/'.$vimeo_id.'?api=1&title=0&portrait=0&byline=0&badge=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    $output .= '</div>';
  }

  return $output;

}