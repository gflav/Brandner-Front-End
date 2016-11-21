<div class="team-member-container">
  <?php if(!empty($heading)): ?>
  <h2><?php echo $heading; ?></h2>
  <?php endif; ?>
  <?php if(!empty($posts)): ?>
  <ul class="team-member-list">
    <?php foreach($posts as $post): ?>
      <?php $photo = get_the_post_thumbnail($post, $size='profile-photo'); ?>
      <?php if(!empty($photo)): ?>
      <li>
        <div class="profile-photo">
          <?php echo $photo; ?>
          <div class="profile-caption"><?php echo get_the_title($post); ?></div>
        </div>
      </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>