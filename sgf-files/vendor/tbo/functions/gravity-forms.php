<?php

// allow to hide labels
// NOTE: still need to manually hide the require part =(
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

// change button to button
add_filter( 'gform_submit_button', 'tbo_form_submit_button', 10, 2 );
function tbo_form_submit_button( $button, $form ) {
  return '<button class="btn" id="gform_submit_button_'.$form['id'].'" type="submit">'.$form['button']['text'].'</button>';
}

// chnage the spinner
add_filter( 'gform_ajax_spinner_url', 'tbo_gform_ajax_spinner_url' );
function tbo_gform_ajax_spinner_url($src) {
  return get_stylesheet_directory_uri() . '/vendor/tbo/images/spinner/default.svg';
}

add_filter( 'gform_allowable_tags', 'tbo_theme_gform_allowable_tags' );
function tbo_theme_gform_allowable_tags($allowable_tags) {
  return '';
}