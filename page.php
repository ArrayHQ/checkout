<?php
/**
 * The template for displaying all pages.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

if ( is_active_sidebar( 'sidebar-widgets' ) ) {
	$primary_class = 'primary-sidebar';
} else {
	$primary_class = 'primary-no-sidebar';
}

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area <?php echo $primary_class; ?>">
				<div id="content" class="site-content container" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<!-- Load post content from format-standard.php -->
						<?php get_template_part( 'partials/content-standard' ); ?>

					<?php endwhile; // end of the loop. ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->

			<!-- Get the page sidebar -->
			<?php get_template_part( 'partials/content-sidebar' ); ?>

		</div><!-- #main .site-main -->

<?php get_footer(); ?>