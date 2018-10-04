<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

				<?php if ( is_search() ) {
					do_action( 'checkout_index_above_posts' );
				} ?>

				<div id="posts" class="posts">

					<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<!-- Load post content from format-standard.php -->
						<?php get_template_part( 'partials/content-standard' ); ?>

					<?php endwhile; // end of the loop. ?>

					<?php else : ?>

						<?php get_template_part( 'partials/content-none' ); ?>

					<?php endif; ?>
				</div>

				<?php checkout_page_navs(); ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->

			<!-- Get the post sidebar -->
			<?php get_template_part( 'partials/content-sidebar' ); ?>
		</div><!-- #main .site-main -->

<?php get_footer(); ?>