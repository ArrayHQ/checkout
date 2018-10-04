<?php
/**
 * The template used for displaying the testimonial section.
 *
 * @package Checkout
 */
?>
					<?php do_action( 'checkout_homepage_above_testimonials' ); ?>

					<!-- If there are testimonials, show them -->
					<?php
						if( post_type_exists( 'testimonial' ) ) {
							// Get number of testimonial posts from customizer
							$testimonial_count = get_option( 'checkout_testimonial_count', '2' );
							$count_testimonials = wp_count_posts( 'testimonial', 'publish' );

							if( $count_testimonials && 0 < $count_testimonials->publish && '0' != $testimonial_count ) { ?>
								<section class="testimonial-section">
									<?php if ( get_option( 'checkout_testimonial_title' ) ) { ?>
											<h3 class="testimonial-title">
												<?php echo get_option( 'checkout_testimonial_title' ); ?>
											</h3>
									<?php } ?>

									<div class="testimonial-section-inside">

										<?php

											$testimonials = new WP_Query( array(
												'post_type'      => 'testimonial',
												'orderby'        => 'rand',
												'posts_per_page' => $testimonial_count,
												'no_found_rows'  => true,
											) );
										?>

										<?php if ( $testimonials->have_posts() ) : ?>

											<?php
												while ( $testimonials->have_posts() ) : $testimonials->the_post();
													 get_template_part( 'partials/content-testimonial' );
												endwhile;
												wp_reset_postdata();
											?>

										<?php endif; ?>
									</div>
								</section><!-- .testimonial-section -->
					<?php } } ?><!-- If testimonials exist, show them -->