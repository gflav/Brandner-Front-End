<?php if(!empty($posts)): ?>
<?php
  
  $post_count = count($posts);
  $ul_class = $post_count < 10 ? 'flex-no' : '';
  
?>
<ul class="row <?php echo $ul_class; ?>">
  <?php
    
    foreach($posts as $post):
      echo tbo()->view->load('content-list-item', array('post_count' => $post_count, 'post' => $post, 'anchor' => !empty($anchor), 'css_class' => $css_class));
    endforeach;
  ?>
</ul>
<?php endif; ?>