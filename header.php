<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="main-container">
		<header class="header">
			<div class="header__inner">
				<div class="header__branding header-branding">
					<div class="header-branding__logo">
						<?php the_custom_logo(); ?>
					</div>
					<div class="header-branding__text">
						<?php
						if ( ( is_front_page() && is_home() ) || ( is_home() && ! is_front_page() ) ) :
						?>
							<div class="header-branding__title">
								<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							</div>
						<?php
						else :
						?>
							<div class="header-branding__title">
								<span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
							</div>
						<?php
						endif;
						$mpuniversal_description = get_bloginfo( 'description', 'display' );
						if ( $mpuniversal_description || is_customize_preview() ) :
						?>
							<div class="header-branding__desc">
								<p><?php echo $mpuniversal_description; ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<nav class="main__menu menu">
					<div class="menu__body">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'header-menu',
								'container' 	 => false,
								'menu_class'	 => 'menu__list',
							)
						);
						?>
					</div>
					<button class="menu__icon icon-menu" type="button">
						<span class="icon-menu__line"></span>
					</button>
				</nav>
			</div>
		</header>

		<div class="content">