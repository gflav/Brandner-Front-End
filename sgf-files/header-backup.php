<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<?php echo tbo()->view->load('facebook'); ?>
	
		<header>
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<div id="brandnerdesign-header-content">
							
							<div class="col-sm-3">
								<a id="header-logo" href="/">
									<?php if($logo = tbo_theme_mod('custom-logo')): ?>
									<?php echo $logo; ?>
									<?php endif; ?>
								</a>
							</div>


							<div class="col-sm-9">
	
								<div id="brandnerdesign-header-links-wrapper" class="hidden-xs hidden-sm visible-md">
									
									<?php
										wp_nav_menu(array(
											'theme_location' => 'main',
											'menu' => 'main-nav',
											'container' => null,
											'menu_id' => 'brandnerdesign-header-links'
										));
									?>
									
									<?php
										wp_nav_menu(array(
											'theme_location' => 'header-icons',
											'menu' => 'header-icons',
											'container' => null,
											'menu_id' => 'brandnerdesign-header-icons'
										));
									?>
	
								</div>
                
                <div id="dl-menu" class="dl-menuwrapper">
							    <a id="mobile-menu-button-container" class="hidden-md hidden-lg dl-trigger">
                    <div id="mobile-menu-button">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                  </a>
                  <?php
                    wp_nav_menu(array(
                      'theme_location' => 'mobile-nav',
                      'menu' => 'mobile-nav',
                      'container' => NULL,
                      'menu_id' => 'mobile-links',
                      'menu_class' => 'dl-menu'
                    ));
                  ?>
                </div>
	
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>