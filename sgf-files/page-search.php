<?php
/**
 * Template Name: Search Page
 */

/**
 * Custom search page /search/
 */
 
if(tbo()->request->isAjax()):
  // ajax
  get_template_part('templates/content', 'search');
else:
  // normal
  get_header();
  get_template_part('templates/content', 'search');
  get_footer();
endif;