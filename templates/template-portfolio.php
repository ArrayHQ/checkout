<?php
/**
 * Template Name: Portfolio
 *
 * Template for displaying Portfolio items (available via the Array Toolkit plugin)
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area portfolio-primary">
				<div id="content" class="site-content container" role="main">
					<!-- If there is post content, show it -->
					<?php if( have_posts() ) : while( have_posts() ) : the_post();
	                    if( get_the_content() ) {
	                        get_template_part( 'partials/content-standard' );
	                    }
                    endwhile; endif;

					// Get the portfolio items
					if ( get_query_var( 'paged' ) ) :
						$paged = get_query_var( 'paged' );
					elseif ( get_query_var( 'page' ) ) :
						$paged = get_query_var( 'page' );
					else :
						$paged = 1;
					endif;

					$args = array(
						'post_type'      => 'array-portfolio',
						'posts_per_page' => 9,
						'paged'          => $paged,
					);
					$project_query = new WP_Query ( $args );
					if ( $project_query -> have_posts() ) :
					?>

						<div class="portfolio-wrapper">

							<?php while ( $project_query -> have_posts() ) : $project_query -> the_post();

								get_template_part( 'partials/content-portfolio-thumbs' );

							endwhile; ?>

						</div><!-- .portfolio-wrapper -->

					<?php endif; ?>

					<?php wp_reset_query(); ?>

					<?php checkout_page_navs( $project_query ); ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>