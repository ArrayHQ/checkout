<?php
/**
 * The template for displaying archive posts.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

if ( is_active_sidebar( 'sidebar-widgets' ) ) {
	$primary_class = 'primary-sidebar';
} else {
	$primary_class = 'primary-no-sidebar';
}

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area <?php echo $primary_class; ?>">
				<div id="content" class="site-content container" role="main">

				<div id="posts" class="posts">
					<?php if ( have_posts() ) : ?>

					<?php
						// Load portfolio post template
						if ( is_post_type_archive( 'array-portfolio' ) || is_tax( array( 'categories' ) ) ) : ?>

							<div class="portfolio-wrapper facetwp-template">

								<?php while ( have_posts() ) : the_post();

									get_template_part( 'partials/content-portfolio-thumbs' );

								endwhile; ?>

							</div><!-- .portfolio-wrapper -->

						<?php
						// Load portfolio post template
						elseif ( is_post_type_archive( 'download' ) || is_tax( array( 'download_category', 'download_tag' ) ) ) : ?>

						<?php do_action( 'checkout_archive_above_download' ); ?>

							<div class="portfolio-wrapper download-wrapper facetwp-template">

								<?php while ( have_posts() ) : the_post();

									get_template_part( 'partials/content-download-thumbs' );

								endwhile; ?>

							</div><!-- .portfolio-wrapper -->

						<?php
						// Load testimonial post template
						elseif ( is_post_type_archive( 'testimonial' ) ) : ?>

							<section class="testimonial-section">
								<div class="testimonial-section-inside">
									<?php while ( have_posts() ) : the_post();

										get_template_part( 'partials/content-testimonial' );

									endwhile; ?>
								</div>
							</section><!-- .testimonial-section -->

							<?php else :

							while ( have_posts() ) : the_post();

								// Load the default post template
								get_template_part( 'partials/content-standard' );

							endwhile;

						endif;

						checkout_page_navs();
						do_action( 'checkout_below_archive_navs' );

					else :

						// Load the empty post template
						get_template_part( 'partials/content-none' );

					endif; ?>
				</div><!-- #posts .posts -->

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->

			<!-- Get the post sidebar -->
			<?php get_template_part( 'partials/content-sidebar' ); ?>
		</div><!-- #main .site-main -->

<?php get_footer(); ?>
