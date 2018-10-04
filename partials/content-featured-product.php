<?php
/**
 * The template containing the Featured Posts area.
 * The Featured Posts tag is set up in Appearance -> Customize -> Homepage Settings
 *
 * @package Checkout
 */
?>

								<li>
									<?php
										$get_bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-post', false, '');
										$bg_image_url = esc_url( $get_bg_image_url[0] );

										if ( ! $get_bg_image_url ) {
											$bg_image_url = get_template_directory_uri() . '/images/default-image.jpg';
										}
									?>

									<!-- Get the featured image -->
									<?php if ( $bg_image_url ) { ?>
										<div class="split-left">
											<a href="<?php the_permalink(); ?>" class="split-left-image" rel="bookmark" style="background-image: url(<?php echo esc_url( $bg_image_url ); ?>);"> </a>
										</div>
									<?php } ?>

									<div class="split-right equal">
										<h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

										<?php if ( 'download' == get_post_type() ) { ?>
											<p>
												<?php if ( edd_has_variable_prices( get_the_ID() ) ) {
													// Get the price range
													echo checkout_edd_price_range();

												} else {
													// Get the single price
													edd_price( get_the_ID() );

												} ?>
											</p>
										<?php } ?>

										<?php the_excerpt(); ?>

										<?php if ( 'array-portfolio' == get_post_type() ) {
											get_template_part( 'partials/content-portfolio-meta' );
										} else if ( 'download' == get_post_type() ) {
											get_template_part( 'partials/content-download-meta' );
										} else {
											get_template_part( 'partials/content-meta' );
										} ?>
									</div>
								</li>