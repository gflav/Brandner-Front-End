<?php
/**
 * Template for displaying search forms.
 */
?>
<div class="search-form-container">
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'What can we help you find?', 'placeholder', 'brandner' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<button type="submit" class="search-submit"><?php echo _x( 'Search', 'submit button', 'brandner' ); ?></button>
	</form>
</div>
