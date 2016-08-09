<ul>
  <?php foreach($posts as $post): ?>
  <li><a href="<?php echo get_the_permalink($post); ?>"><?php echo get_the_title($post); ?></a></li>
  <?php endforeach; ?>
</ul>