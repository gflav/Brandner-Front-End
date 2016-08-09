
<?php if(!empty($terms)): ?>
<div class="menu-collection-nav-side-container">
	<h3 class="menu-collection-nav-side"><span class="title-large"><?php echo $title; ?></span><span class="title-small"><?php echo $term_current->name; ?></span></h3>
	<ul class="menu-collection-nav-side">
    <?php foreach($terms as $term): ?>
		<li class="<?php if($term->term_id == $term_current->term_id): ?>current-menu-item<?php endif; ?>">
      <a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>
      <?php
			
				$post_type = tbo_arg(0) == 'collections' ? 'bd_product' : 'finish';
				$taxonomy = tbo_arg(0) == 'collections' ? 'product_category' : 'finish_category';
			
        $products = new WP_Query(array(
					'post_type' => $post_type,
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'terms' => $term->slug,
							'field' => 'slug',
						),
					),
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => -1,
				));
        
				if($products->have_posts()):
				
				?>
				<ul>
					<?php
					while($products->have_posts()): $products->the_post();
					
						$permalink = get_the_permalink($post);
						$currentlink = is_single() ? get_the_permalink($item) : '';
            
					?>
					<li <?php if($currentlink == $permalink): ?>class="current-menu-item"<?php endif; ?>>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
					<?php
					endwhile;
					?>
				</ul>
				<?php
				endif;
				wp_reset_postdata();
        ?>
    </li>
	<?php
    endforeach;
	?>
	</ul><!-- .collection-nav-sub -->
</div><!-- .collection-nav-sub-container -->
<?php endif; ?>