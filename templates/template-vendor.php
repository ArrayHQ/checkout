<?php
/**
 * Template Name: Vendor
 *
 * Vendor template with sidebar profile area
 *
 * @package Checkout
 * @since Checkout 1.0
 */
get_header(); ?>

		<div id="main" class="site-main">
			<div class="sticky-container">
				<div id="secondary">

					<div id="purchase-box" class="purchase-box">
						<?php do_action( 'checkout_above_vendor_sidebar' ); ?>
						<?php do_action( 'checkout_below_vendor_sidebar' ); ?>
					</div><!-- .purchase-box -->

				</div><!-- #secondary -->

				<div id="primary" class="content-area portfolio-primary">
					<div id="content" class="site-content container" role="main">

						<div class="portfolio-wrapper download-wrapper">

							<?php while ( have_posts() ) : the_post(); ?>

								<div class="entry-content">
									<?php if( get_the_content() ) {
										// Display content if provided
				                    	the_content();
				                    } else {
				                    	// Output the [downloads] shortcode if no content provided
				                    	echo do_shortcode( '[downloads]' );
				                    } ?>
								</div>

							<?php endwhile; ?>

						</div><!-- .portfolio-wrapper -->

					</div><!-- #content .site-content -->
				</div><!-- #primary .content-area -->
			</div><!-- .sticky-container -->
			<div style="clear:both;"></div>
		</div><!-- #main .site-main -->

<?php get_footer(); ?>