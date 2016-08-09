<?php
 
if(tbo()->request->isAjax()):
  // ajax
  get_template_part('templates/content', 'page');
else:
  // normal
  get_header();
  get_template_part('templates/content', 'page');
  get_footer();
endif;