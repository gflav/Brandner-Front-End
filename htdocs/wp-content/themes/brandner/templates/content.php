<div class="container container-main">
	<div id="site-content">
	<?php
	if(have_posts()):
		while(have_posts()): the_post();
			the_content();
		endwhile;
	else:
	?>
		<h1 class="center-text">Page Not Found</h1>
    <p class="center-text">The requested page cannot be found.</p>
	<?php
	endif;
	?>
	</div>
</div>