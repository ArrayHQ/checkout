<?php
/**
 * Template Name: Homepage Widgets
 *
 * This template displays a widget grid, a featured post
 * section, testimonials and a call-to-action banner.
 * Settings for this template can be found in Appearance ->
 * Customize -> Homepage Settings while viewing this page template.
 *
 * @package Checkout
 * @since Checkout 1.0
 */
get_header(); ?>

	  <?php do_action( 'checkout_homepage_below_header' ); ?>

		<div id="main" class="site-main homepage-template">
			<div id="primary" class="content-area">
				<div id="content" class="site-content" role="main">

					<?php do_action( 'checkout_homepage_above_content' ); ?>

					<!-- If there is post content, show it -->
					<?php
					if( have_posts() ) : while( have_posts() ) : the_post();
						if( get_the_content() ) {
							echo '<div class="homepage-post-content">';
								get_template_part( 'partials/content-standard' );
							echo '</div>';
						}
					endwhile; endif;
					?>


					<?php do_action( 'checkout_homepage_above_widgets' ); ?>


					<?php
					// Get the text widgets
					if ( is_active_sidebar( 'homepage-text' ) ) : ?>
						<section class="widget-section">
							<?php dynamic_sidebar( 'homepage-text' ); ?>
						</section>
					<?php endif; ?>


					<?php do_action( 'checkout_homepage_above_split' ); ?>


					<!-- Featured post items -->
					<?php
						$featured_tag = get_theme_mod( 'checkout_post_tag_select', '' );
						if ( '' != $featured_tag ) {

							$featured_args = array(
								'post_type'      => 'post',
								'posts_per_page' => 6,
								'no_found_rows'  => true,
								'tag'            => $featured_tag,
							);
							$featured_query = new WP_Query ( $featured_args );
							if ( $featured_query -> have_posts() ) :
						?>
							<section class="split-section">
								<div class="split-container">
									<div class="slide-navs"></div>
								<ul class="rslides product-slides">
									<?php while ( $featured_query -> have_posts() ) : $featured_query -> the_post();
										get_template_part( 'partials/content-featured-product' );
									endwhile; ?>
								</ul>

								<ul id="product-pager">
									<?php while ( $featured_query -> have_posts() ) : $featured_query -> the_post(); ?>

											<li><a class="paging-thumb" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
												<?php if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'paging-thumb' );
												} else { ?>
													<img src="<?php echo get_template_directory_uri(); ?>/images/default-thumb.jpg" alt="placeholder" />
												<?php } ?>
											</a></li>

									<?php endwhile; ?>
								</ul>
							 </div>
							</section><!-- .split-section -->

						<?php endif; ?>
						<?php wp_reset_query(); ?>
					<?php } // If featured_tag exists ?>

					<?php do_action( 'checkout_homepage_above_testimonial' ); ?>

					<!-- Get the testimonial section -->
					<?php get_template_part( 'partials/content-testimonial-section' ); ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

		<?php do_action( 'checkout_homepage_above_cta' ); ?>

		<!-- Get the call-to-action section -->
		<?php get_template_part( 'partials/content-cta-section' ); ?>

<?php get_footer(); ?>
