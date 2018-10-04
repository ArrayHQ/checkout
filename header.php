<?php
/**
 *
 * Displays all of the <head> section
 *
 * @package Checkout
 * @since Checkout 1.0
 */
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'checkout_announcement_bar' ); ?>

<div id="page">
	<header id="masthead" class="site-header" role="banner">
		<div class="header-inside">
			<!-- Mobile menu toggle -->
			<div class="menu-toggle">
				<span><i class="fa fa-reorder"></i><?php _e( 'Menu', 'checkout' ); ?></span>
				<span class="menu-close"><i class="fa fa-times"></i><?php _e( 'Close Menu', 'checkout' ); ?></span>
			</div>

			<!-- Header navigation menu -->
			<nav role="navigation" class="site-navigation main-navigation">
				<div class="assistive-text"><i class="fa fa-bars"></i> <?php _e( 'Menu', 'checkout' ); ?></div>

				<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'container_id'   => 'menu',
				) ); ?>
			</nav><!-- .site-navigation .main-navigation -->

			<?php if ( get_theme_mod( 'checkout_logo' ) ) { ?>

				<!-- Show the logo image -->
				<div class="logo">
					<?php if ( is_front_page() ) { echo '<h1 class="logo-image">'; } else { echo '<p class="logo-image">'; } ?>
						<a href="<?php echo home_url( '/' ); ?>">
							<img src="<?php echo esc_url( get_theme_mod( 'checkout_logo' ) );?>" alt="<?php the_title_attribute(); ?>" />
						</a>
					<?php if ( is_front_page() ) { echo '</h1>'; } else { echo '</p>'; } ?>
				</div>

			<?php } else { ?>

				<!-- Show the default site title and tagline -->
				<div class="logo logo-text">
					<?php if ( is_front_page() ) { echo '<h1 class="site-title">'; } else { echo '<p class="site-title">'; } ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					<?php if ( is_front_page() ) { echo '</h1>'; } else { echo '</p>'; } ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				</div>

			<?php } ?>

			<!-- Get page titles and homepage header buttons -->
			<?php get_template_part( 'templates/template-titles' ); ?>
		</div><!-- .header-inside -->

		<!-- Get the header background image -->
		<?php
			// Get header opacity from Appearance > Customize > Header & Footer Image
			$header_opacity = get_theme_mod( 'checkout_bg_opacity', '0.1' );
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) { ?>

			<div class="site-header-bg-wrap">
				<div class="site-header-bg background-effect" style="background-image: url(<?php header_image(); ?>); opacity: <?php echo esc_attr( $header_opacity ); ?>;"></div>
			</div>
		<?php } ?>

	</header><!-- .site-header -->
