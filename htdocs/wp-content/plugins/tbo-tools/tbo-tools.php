<?php
/**
 * @package TBO Tools
 * @version 1.0
 */
/*
Plugin Name: TBO Tools
Plugin URI: https://thebarton.org/
Description: Tools for Wordpress.
Author: The Barton Organization
Version: 1.0
Author URI: https://thebarton.org/
*/

require_once(__DIR__.'/shortcode.php');
require_once(__DIR__.'/visual-composer/slideshow.php');

class TBO_Tools {

  protected static $_instance;
  public $view;

  private function __construct() {
    $this->view = new TBO_Tools_View();
    $this->check = new TBO_Tools_Check();
    $this->request = new TBO_Tools_Request();
    $this->util = new TBO_Tools_Util();
  }

  public static function getInstance() {

    if(!isset(self::$_instance)) {
      self::$_instance = new TBO_Tools();
    }

    return self::$_instance;

  }
  
  public function shortcode($shortcode, $ignore_html=FALSE) {
    return do_shortcode($shortcode, $ignore_html);
  }

  // Views

  // For Themes

  // For Modules

}

class TBO_Tools_Request {
  
  public function isAjax() {
    $result = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    return $result;
  }
  
}

class TBO_Tools_Util {
  
  public function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== FALSE;
  }
  
}

class TBO_Tools_Check {

  public function plain($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }

}

class TBO_Tools_View {

  protected $context;

  /**
   * Switches between a module or theme
   * contetxt. i.e., you can reference this function
   * from a module or from a theme.
   */
  public function centext($context=NULL) {
    if(isset($context)) {
      $this->context = $context;
    }
    return $this;
  }

  public function getContext() {
    switch($this->context) {
      default:
        return tpath('views');
    }
  }

  public function load($view_name, $vars=array()) {

    $file = $this->getContext() . '/' . $view_name . '.tpl.php';
    return $this->fetch($file, $vars);

  }

  public function fetch($file, $vars) {
    if(is_array($vars)) {
      extract($vars, EXTR_SKIP);  
    }
    ob_start();
    $atts = $vars;
    include($file);
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }

}

// start shortcodes

add_shortcode('view', 'tbo_tools_sc_view');
function tbo_tools_sc_view($params) {
  $name = array_shift($params);
  return bt()->view->load($name, $params);
}

// end shortcodes

// start filters

// end filters

// Helper Functions

function tbo() {
  return TBO_Tools::getInstance();
}

function tbo_dump($value) {
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
  die();
}

function url($path) {
  if($path == '<front>') {
    $path = '';
  }
  return get_bloginfo('url') . '/' . $path . '/';
}

function turl($path) {
  return get_stylesheet_uri() . '/' . $path;
}

function tpath($path) {
  return STYLESHEETPATH . '/' . $path;
}

function l($label, $path, $options=array()) {
  $attributes = isset($options['attributes']) ? tbo_tools_attributes($options['attributes']) : '';
  return '<a href="' . url($path) . '"'.$attributes.'>' . $label . '</a>';
}

function tbo_tools_attributes(array $attributes=array()) {
  foreach ($attributes as $attribute => &$data) {
    $data = implode(' ', (array)$data);
    $data = $attribute . '="' . $data . '"';
  }
  return $attributes ? ' ' . implode(' ', $attributes) : '';
}

function tbo_get_field($field_name, $post_id=NULL, $format_value=TRUE) {
  // check acf first, it overrides
  if($field = get_field($field_name, $post_id, $format_value)) {
    return $field;
  }
  // check internal custom field
  if($field = get_post_meta($post_id, $field_name, $format_value)) {
    return $field;
  }
  return NULL;
}