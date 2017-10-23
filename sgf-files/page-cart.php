<?php
 
if(tbo()->request->isAjax()):
  // ajax
  get_template_part('templates/content', 'cart');
else:
  // normal
  get_header();
  get_template_part('templates/content', 'cart');
  get_footer();
endif;