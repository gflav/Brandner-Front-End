<?php if(!empty($posts)): ?>
<ul class="row">
  <?php
    $post_count = count($posts);
    foreach($posts as $post):
      echo tbo()->view->load('content-list-item', array('post_count' => $post_count, 'post' => $post, 'anchor' => !empty($anchor), 'css_class' => $css_class));
    endforeach;
  ?>
</ul>
<?php endif; ?>