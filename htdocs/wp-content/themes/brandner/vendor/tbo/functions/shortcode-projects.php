<?php

// TODO: remove, just use content_list shortcode
add_shortcode('project_list', 'tbo_shortcode_project_list');
function tbo_shortcode_project_list($atts) {
  $atts['post_type'] = 'project';
  return tbo_shortcode_content_list($atts);
}