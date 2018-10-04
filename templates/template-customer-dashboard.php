<?php
/**
 * Template Name: Customer Dashboard
 *
 * Displays customer purchase and account details
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area">
				<div id="content" class="site-content container" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

					<?php if ( ! is_user_logged_in() ) { ?>

						<article class="post">
							<div class="post-content page-content">
								<div class="post-text">
									<?php echo do_shortcode( '[edd_login]' ); ?>
								</div><!-- .post-text -->
							</div>
						</article><!-- .post-class -->

					<?php } else { ?>

						<?php do_action( 'checkout_above_dash_sections' ); ?>

						<article class="post">
							<div class="post-content page-content">
								<div class="post-text">
									<h2 class="account-title"><i class="fa fa-credit-card"></i> <?php _e( 'Purchase History', 'checkout' ); ?></h2>
									<?php echo apply_filters( 'the_content', do_shortcode( '[purchase_history]' ) ); ?>
								</div><!-- .post-text -->
							</div>
						</article><!-- .post-class -->

						<article class="post">
							<div class="post-content page-content">
								<div class="post-text">
									<h2 class="account-title"><i class="fa fa-download"></i> <?php _e( 'Downloads', 'checkout' ); ?></h2>
									<?php echo do_shortcode( '[download_history]' ); ?>
								</div><!-- .post-text -->
							</div>
						</article><!-- .post-class -->

						<?php if ( class_exists( 'EDD_Recurring' ) ) { ?>
							<article class="post">
								<div class="post-content page-content">
									<div class="post-text">
										<h2 class="account-title"><i class="fa fa-refresh"></i> <?php _e( 'Subscriptions', 'checkout' ); ?></h2>
										<?php echo do_shortcode( '[edd_subscriptions]' ); ?>
									</div><!-- .post-text -->
								</div>
							</article><!-- .post-class -->
						<?php } ?>

						<article class="post">
							<div class="post-content page-content">
								<div class="post-text">
									<h2 class="account-title"><i class="fa fa-cog"></i> <?php _e( 'Account Info', 'checkout' ); ?></h2>
									<?php echo do_shortcode( '[edd_profile_editor]' ); ?>
								</div><!-- .post-text -->
							</div>
						</article><!-- .post-class -->

						<?php do_action( 'checkout_below_dash_sections' ); ?>
					<?php } ?>

					<?php endwhile; ?>

				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>
