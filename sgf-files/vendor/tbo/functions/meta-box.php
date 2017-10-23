<?php

// finishes meta box for products content type

add_action('add_meta_boxes', 'bd_finishes_mb');
function bd_finishes_mb(){
	add_meta_box('bd_finishes', 'Finishes', 'bd_select_finish_mb', 'bd_product', 'side', 'default');
}

function bd_select_finish_mb($pst){
	global $post;

	$f = get_post_meta($pst->ID, '_finishes', true);
	$finishes = new WP_Query(array(
		'post_type' => 'finish',
		'posts_per_page' => -1,
	));
	if($finishes->have_posts()): while($finishes->have_posts()): $finishes->the_post();
	?>
	<div class="finish">
		<input type="checkbox" name="bd_finish[]" value="<?php echo $post->ID; ?>" <?php checked(in_array($post->ID, (array)$f)); ?>>
		<?php the_post_thumbnail(array(50,50)); ?>
		<h4><?php the_title(); ?></h4>
		<p><?php edit_post_link('edit'); ?> | <a href="<?php echo wp_nonce_url( admin_url('/post.php?action=trash&post='.$post->ID), 'trash-'.$post->post_type.'_'.$post->ID); ?>">delete</a></p>
	</div>
	<?php
	endwhile;
	endif;
	wp_reset_postdata();
	wp_nonce_field('save-finish-fields', '_finish');
	?>
	<a class="add-finish" href="<?php echo admin_url('/post-new.php?post_type=finish'); ?>">+ Add New Finish</a>
	<?php
	$post = $pst;
}

add_action('save_post', 'bd_save_finishes', 10, 2);
function bd_save_finishes($post_ID, $post){
	if(isset( $_POST['_finish'] ) && wp_verify_nonce($_POST['_finish'], 'save-finish-fields')){

		$finishes = isset($_POST['bd_finish']) && !empty($_POST['bd_finish']) ? $_POST['bd_finish'] : array();
		if(!update_post_meta($post_ID, '_finishes', $finishes)){
			add_post_meta($post_ID, '_finishes', $finishes, true);
		}
	}
}

