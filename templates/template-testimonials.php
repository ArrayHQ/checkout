<?php
/**
 * Template Name: Testimonials
 *
 * Template to display testimonials (available via the Array Toolkit plugin)
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main featured-columns">
			<div id="primary" class="content-area">
				<div id="content" class="site-content" role="main">
					<!-- If there is post content, show it -->
					<?php if( have_posts() ) : while( have_posts() ) : the_post();
	                    if( get_the_content() ) {
	                        get_template_part( 'partials/content-standard' );
	                    }
                    endwhile; endif;

					// Testimonials
					if( post_type_exists( 'testimonial' ) ) {
						$count_testimonials = wp_count_posts( 'testimonial', 'publish' );
						if( $count_testimonials && 0 < $count_testimonials->publish ) {
					?>
						<section class="testimonial-section">
							<div class="testimonial-section-inside">

								<?php
									$testimonials = new WP_Query( array(
										'post_type'      => 'testimonial',
										'posts_per_page' => 30,
										'no_found_rows'  => true,
									) );

								if ( $testimonials->have_posts() ) :

									while ( $testimonials->have_posts() ) : $testimonials->the_post();
										get_template_part( 'partials/content-testimonial' );
									endwhile;
									wp_reset_postdata();

								endif; ?>
							</div>
						</section><!-- .testimonial-section -->
						<?php
						}
					} ?>
				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>