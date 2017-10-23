<?php
 /**
  * Template Name: Page w/Wood
  */
 
if(tbo()->request->isAjax()):
  // ajax
  get_template_part('templates/content');
else:
  // normal
  get_header();
  get_template_part('templates/content');
  get_footer();
endif;