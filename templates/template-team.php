<?php
/**
 * Template Name: Team
 *
 * Template for displaying content and widgets for the team page.
 * Loads widgets from the Team Page Widgets section.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<!-- Get the text widgets -->
		<?php if ( is_active_sidebar( 'team' ) ) : ?>
			<section class="widget-section team-section">
				<?php dynamic_sidebar( 'team' ); ?>
			</section>
		<?php endif; ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area">
				<div id="content" class="site-content container" role="main">

					<?php while ( have_posts() ) : the_post();
						if( get_the_content() ) { ?>
							<article <?php post_class( 'post' ); ?>>
								<div class="post-content page-content">
									<div class="post-text">
										<?php the_content(); ?>
										<?php wp_link_pages(); ?>
									</div><!-- .post-text -->
								</div>
							</article><!-- .post-class -->
						<?php } ?>
					<?php endwhile; // end of the loop. ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>