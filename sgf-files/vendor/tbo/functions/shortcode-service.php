<?php

// TODO: remove, use content_list shortcode
add_shortcode('service_list', 'tbo_shortcode_service_list');
function tbo_shortcode_service_list($atts) {
  $atts['post_type'] = 'service';
  $atts['css_class'] .= ' center-text services_list';
  return tbo_shortcode_content_list($atts);
}