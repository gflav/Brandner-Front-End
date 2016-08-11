<?php
  $type = in_array($post->post_type, array('bd_product', 'finish')) ? 'product' : $post->post_type;
  if($type == 'product'):
?>
<div class="btn-group">
  <?php if(is_plugin_active('woocommerce/woocommerce.php')): ?>
  <?php $product = get_field('product', $post->ID); ?>
    <?php if(!empty($product)): ?>
    <?php $product = $product[0]; ?>
      <a class="btn btn-buy-now" href="#" data-product-id="<?php echo $product->ID; ?>">Purchase</a>
      <a class="btn btn-cart-add" href="#" data-product-id="<?php echo $product->ID; ?>">Add to Cart</a>
    <?php else: ?>
      <a class="btn btn-quote" href="#" data-product-id="<?php echo $post->ID; ?>">Get a Quote</a>
    <?php endif; ?>
  <?php endif; ?>
  <?php if($download = tbo_get_field('download', $post->ID)): ?>
  <a class="btn btn-download" href="<?php echo $download; ?>" target="_blank">Download Tear Sheet</a>
  <?php endif; ?>
  <a class="btn btn-share">Share</a>
  <a class="btn btn-print" data-print="<?php echo (isset($download) ? $download : ''); ?>">Print</a>
</div>
<?php elseif($type == 'limited'): ?>
  <?php /** limited post type **/ ?>
  <?php $count = count(tbo_get_gallery($post)); ?>
<div class="btn-group">
  <a class="btn btn-disabled" href="#"><?php echo sprintf(_n('%d Piece', '%d Pieces', $count, 'brandner'), $count); ?> Available</a>
  <?php if(!empty($count)): ?>
  <a class="btn btn-gallery" href="<?php echo get_the_permalink($post); ?>" title="<?php echo get_the_title($post); ?>" data-route="<?php echo tbo_get_api_route($post); ?>" data-post-type="<?php echo $post->post_type; ?>" data-post-id="<?php echo $post->ID; ?>">View Gallery</a>
  <?php endif; ?>
</div>
<?php elseif($type == 'press-release'): ?>
<?php
  $publication_link = get_field('publication_link', $post->ID);
  if(!empty($publication_link)): ?>
<div class="btn-group">
  <a class="btn btn-link" href="<?php echo $publication_link; ?>" target="_blank">View Publication</a>
</div>
<?php endif; ?>
<?php else: ?>
<div class="btn-group">
  <a class="btn btn-gallery" href="<?php echo get_the_permalink($post); ?>" title="<?php echo get_the_title($post); ?>" data-route="<?php echo tbo_get_api_route($post); ?>" data-post-type="<?php echo $post->post_type; ?>" data-post-id="<?php echo $post->ID; ?>">View Gallery</a>
  <a class="btn btn-share" href="<?php echo get_the_permalink($post); ?>">Share</a>
  <a class="btn btn-print" href="<?php echo get_the_permalink($post); ?>">print</a>
</div>
<?php endif; ?>