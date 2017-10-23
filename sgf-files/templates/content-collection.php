<div class="container">
	<div id="site-content">
    <?php

    // housekeeping
		$term_slug = get_query_var('term');
		$term_slug = $term_slug ? $term_slug : $post->post_name;
		$taxonomy = tbo_arg(0) == 'collections' ? 'product_category' : 'finish_category';
    $term_current = get_term_by('slug', $term_slug, $taxonomy);
		$term_parent = get_term($term_current->parent, $taxonomy);
    
		if(is_wp_error($term_parent)) {
			$term_parent = $term_current;
		}
		
		$show_sidebar = $term_current && $term_parent;
		$grid_class = $show_sidebar ? 'col-lg-4' : 'col-lg-3';
		
		// collections or materials page
		if(tbo_arg(0) == $post->post_name) {
			$terms = get_terms(array(
				'taxonomy' => $taxonomy,
				'hide_empty' => TRUE,
				'parent' => 0,
			));
		} else {
			$terms = get_terms($taxonomy, array(
				'child_of' => $term_parent->term_id,
			));
		}
    
    // sub nav items
    $items = tbo_get_nav_sub($post->post_title, tbo_arg(0));
    
    // sub nav display
    echo tbo()->view->load('collection-nav-sub', array('items' => $items));
		
		// collections or materials page
		if(!$term_current && !$term_parent):
		?>
		<div class="row">
			<div class="col-sm-12"><h1><?php the_title(); ?></h1></div>
		</div>	
		<?php 
		endif;
    
    ?>
    <div class="row">
			<?php if($show_sidebar): ?>
      <div class="col-sm-12 col-lg-3 collection-sidebar">
        <?php echo tbo()->view->load('collection-nav-side', array('base_path' => tbo_arg(0), 'title' => $term_parent->name, 'item' => $post, 'terms' => $terms, 'term_current' => $term_current)); ?>
      </div>
			<?php endif; ?>
      <div class="<?php if($show_sidebar): ?>col-sm-12 col-lg-9<?php else: ?>col-sm-12<?php endif; ?> collection-container">
        <div class="row">
        <?php
          // taxonomy list (e.g, /collections/ | /materials/)
          // taxonomy list (e.g, /collections/furniture/ | /materials/steel/)
          if(is_page() || !tbo_arg(2)):
            if(!empty($terms)):
              foreach($terms as $term):
                $params = [
                  'item' => (object)array(
                    'image' => tbo_get_term_image_first($term, $taxonomy),
                    'caption' => $term->name,
                    'permalink' => get_term_link($term),
                  )
                ];
                echo '<div class="'.$grid_class.' col-sm-6">' . tbo()->view->load('caption-image', $params) . '</div>';
              endforeach;
            endif;
					 else:
						// taxonomy list (e.g, /collections/furniture/[sub-category])
            while(have_posts()):
              the_post();
              $params = [
                  'item' => (object)array(
                    'image' => get_the_post_thumbnail($post, $size='product-thumb'),
                    'caption' => $post->post_title,
                    'permalink' => get_the_permalink($post),
                  )
              ];
              echo '<div class="'.$grid_class.' col-sm-6">' . tbo()->view->load('caption-image', $params) . '</div>';
            endwhile;
          endif;
        ?>
        </div>
      </div>
    </div>
	</div>
</div>