<?php
/**
 * The template for displaying forum pages.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area primary-no-sidebar">
				<div id="content" class="site-content container" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<!-- Load post content from format-standard.php -->
						<?php get_template_part( 'partials/content-forum' ); ?>

					<?php endwhile; // end of the loop. ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->

		</div><!-- #main .site-main -->

<?php get_footer(); ?>