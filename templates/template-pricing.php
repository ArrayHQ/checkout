<?php
/**
 * Template Name: Pricing
 *
 * Template for displaying content and widgets for the pricing page.
 * Loads widgets from the Pricing Page Widget section.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<!-- Get the variable option price widget -->
		<?php
		if ( is_active_sidebar( 'pricing' ) ) { ?>
			<section class="pricing-section">
				<?php dynamic_sidebar( 'pricing' ); ?>
			</section>
		<?php }

		while ( have_posts() ) : the_post();
			if( get_the_content() ) { ?>
				<div id="main" class="site-main">
					<div id="primary" class="content-area">
						<div id="content" class="site-content container" role="main">

							<article <?php post_class( 'post' ); ?>>
								<div class="post-content page-content">
									<div class="post-text">
										<?php the_content(); ?>
										<?php wp_link_pages(); ?>
									</div><!-- .post-text -->
								</div>
							</article><!-- .post-class -->

						</div><!-- #content .site-content -->
					</div><!-- #primary .content-area -->
				</div><!-- #main .site-main -->

			<?php } ?>
		<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
