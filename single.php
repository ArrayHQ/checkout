<?php
/**
 * The Template for displaying all single posts.
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

						<!-- Get the next/previous post navs -->
						<?php checkout_post_navs(); ?>

						<!-- If comments are open or we have at least one comment, load up the comment template. -->
						<?php if ( comments_open() || '0' != get_comments_number() ) { ?>
							<?php comments_template(); ?>
						<?php } ?>

					<?php endwhile; // end of the loop. ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->

			<!-- Get the post sidebar -->
			<?php get_template_part( 'partials/content-sidebar' ); ?>
		</div><!-- #main .site-main -->

<?php get_footer(); ?>