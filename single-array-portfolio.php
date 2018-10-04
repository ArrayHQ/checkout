<?php
/**
 * The Template for displaying all single portfolio items.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area">
				<div id="content" class="site-content container" role="main">

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

				<!-- Get the next/previous post navs -->
				<?php checkout_post_navs(); ?>

				<!-- If comments are open or we have at least one comment, load up the comment template. -->
				<?php if ( comments_open() || '0' != get_comments_number() ) { ?>
					<?php comments_template(); ?>
				<?php } ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>