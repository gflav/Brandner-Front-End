<div class="container">
	<div id="site-content">
	<?php
	if(have_posts()):
		while(have_posts()): the_post();
  ?>
    <h1><?php the_title(); ?></h1>
  <?php
			the_content();
		endwhile;
	else:
	?>
		<h2>Page Not Found</h2>
	<?php
	endif;
	?>
	</div>
</div>