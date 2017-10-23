
    <div class="row search-form-row">
      <div class="col-md-12">
        <?php get_search_form(); ?>
      </div>
    </div>
	
    <div class="search-results-container">
      <?php if (get_search_query()) : ?>
        <div class="search-count-container">
          <?php $search_count = tbo_get_search_result_count(); ?>
          <?php echo $search_count; ?> <?php echo _n('Result', 'Results', $search_count, 'brandner'); ?> for "<?php echo get_search_query(); ?>"
        </div>
        <ul class="search-results-list">
        <?php while (have_posts() ) : the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
        </ul>
        <?php
        
        $pager = paginate_links(array(
          'mid_size' => 5,
          'type' => 'plain',
          'prev_next' => FALSE,
        ));
        
        if(!empty($pager)): ?>
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <span class="search-pager-label"><?php echo sprintf(_n('Page', 'Pages', $search_count, 'brandner')); ?></span>
            <nav class="navigation pagination" role="navigation">
              <div class="nav-links"><?php echo $pager; ?></div>
            </nav>
          </div>
          <div class="col-sm-12 col-md-6">
            <div class="trigger-top">Back to top</div>
          </div>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
