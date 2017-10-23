<?php if(!empty($items)): ?>
<div class="menu-collection-nav-sub-container">
	<ul class="menu-collection-nav-sub">
    <?php foreach($items as $item): ?>
		<li class="collection <?php echo $item->post_name == $sub_nav_slug ? 'current' : ''; ?>"><a href="<?php echo $item->permalink; ?>"><?php echo $item->post_title; ?></a></li>
	<?php
    endforeach;
	?>
	</ul><!-- .collection-nav-sub -->
</div><!-- .collection-nav-sub-container -->
<?php endif; ?>