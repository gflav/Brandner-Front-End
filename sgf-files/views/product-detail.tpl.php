<div class="container">
	<div id="site-content">
    <?php
		
    // terms
		$taxonomy = tbo_arg(0) == 'collections' ? 'product_category' : 'finish_category';
    $term_current = get_term_by('slug', get_query_var('term'), $taxonomy);
		$term_parent = get_term($term_current->parent, $taxonomy);
    $terms = get_terms($taxonomy, array(
      'child_of' => $term_parent->term_id,
    ));
		
    // sub nav
    $items = tbo_get_nav_sub($post->post_title, tbo_arg(0));
    
    // sub nav
    echo tbo()->view->load('collection-nav-sub', array('items' => $items));
    
    ?>
    <div class="row">
      <div class="col-sm-12 col-lg-3">
        <?php echo tbo()->view->load('collection-nav-side', array('base_path' => tbo_arg(0), 'title' => $term_parent->name, 'item' => $post, 'terms' => $terms, 'term_current' => $term_current)); ?>
      </div>
      <div class="col-sm-12 col-lg-9">
        <div class="row">
          <div class="col-md-12">
            <?php echo tbo()->view->load('product-gallery', array('post' => $post)); ?>  
          </div>
        </div>
        <div class="product-detail-container">
          <div class="row">
            <div class="col-md-8">
              <div class="post-content">
                <h1><?php the_title(); ?></h1>
                <?php $dimensions = tbo_get_field('dimensions'); ?>
                <?php if(!empty($dimensions)): ?>
                <p class="product-dimensions"><?php echo esc_html($dimensions); ?></p>
                <?php endif; ?>
                <?php the_content(); ?>
              </div>
            </div>
            <div class="col-md-4">
             <?php echo tbo()->view->load('tile-grid', array('post' => $post)); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <?php echo tbo()->view->load('buttons', array('post' => $post)); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
</div>