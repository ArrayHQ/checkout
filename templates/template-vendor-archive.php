<?php
/**
 * Template Name: Vendor Archive
 *
 * Template for displaying vendors in a grid.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area portfolio-primary vendor-archive-primary">
				<div id="content" class="site-content container" role="main">
					<?php do_action( 'checkout_download_template_above_download' ); ?>

					<!-- Get the EDD downloads -->
					<?php if( class_exists( 'Easy_Digital_Downloads' ) ) { ?>
						<?php
							if ( get_query_var( 'paged' ) ) :
								$paged = get_query_var( 'paged' );
							elseif ( get_query_var( 'page' ) ) :
								$paged = get_query_var( 'page' );
							else :
								$paged = 1;
							endif;

							$args = array(
								'post_type'      => 'download',
								'posts_per_page' => apply_filters( 'checkout_download_num', 12 ),
								'paged'          => $paged,
							);
							$download_query = new WP_Query ( $args );

							if ( $download_query -> have_posts() ) :
						?>

							<div itemscope class="portfolio-wrapper download-wrapper">

								<?php while ( $download_query->have_posts() ) : $download_query->the_post();

									get_template_part( 'partials/content-download-thumbs' );

								endwhile; ?>

							</div><!-- .portfolio-wrapper -->

						<?php endif; ?>

						<?php wp_reset_query(); ?>

						<?php checkout_page_navs( $download_query ); ?>
					<?php } // If EDD is activated ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>