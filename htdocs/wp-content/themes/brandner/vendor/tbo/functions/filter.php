<?php

// TODO: don't do it like this, just update all the links or make it smarter
add_filter('the_content', 'tbo_filter_the_content_numbers');
function tbo_filter_the_content_numbers($content) {
  $regex = array(
    // email mailto:
    array("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"),
    // phone to tel:
    array('/(^|[\n ])([0-9]{3})-([0-9]{3})-([0-9]{4})/i', '\\1<a href="tel:+1$2$3$4">$2-$3-$4</a>')
  );
  foreach($regex as $r) {
    list($search, $replace) = $r;
    $content = preg_replace($search, $replace, $content);
  }
  return $content;
}

add_filter('the_content', 'tbo_filter_the_content');
function tbo_filter_the_content($content) {
  if(is_singular('press-release')) {
    $publication_date = get_field('publication_date');
    $content = tbo()->view->load('publication-date', ['date' => $publication_date]) . $content;
  }
  return $content;
}

/**
 * Add things to the breadcrumbs.
 */
add_filter('wpseo_breadcrumb_links', 'tbo_filter_wpseo_breadcrumb_links', 10, 1);
function tbo_filter_wpseo_breadcrumb_links($crumbs) {

  if(is_singular('bd_product') || is_singular('finish') || $term = get_query_var('term')) {
    $first_crumb = array_shift($crumbs);
    $id = tbo_arg(0) == 'collections' ? 20 : 2559; // todo:
    array_unshift($crumbs, array('id' => $id));
    array_unshift($crumbs, $first_crumb);
  } else if($keyword = get_query_var('s')) {
    // search
    $first_crumb = array_shift($crumbs);
    array_unshift($crumbs, array('id' => 8074));
    array_unshift($crumbs, $first_crumb);
  }
  
  return $crumbs;
  
}

/**
 * Add a class to the link.
 *
 * Kind of dumb that it doesn't let you set a class in the previous hook.
 */
add_filter('wpseo_breadcrumb_single_link', 'tbo_filter_wpseo_breadcrumb_single_link');
function tbo_filter_wpseo_breadcrumb_single_link($link) {
  if(tbo()->util->contains($link, 'Search')) {
    $link = str_replace('<span ', '<span class="trigger-modal-search" ', $link);
  }
  return $link;
}
