<?php
/**
 * The Template for displaying all single downloads.
 *
 * @package Checkout
 * @since Checkout 1.0
 */


/**
 * Get the download ID to use below
 */
$download_id = get_the_ID();

get_header(); ?>

		<div id="main" class="site-main">
			<div class="sticky-container">
				<div id="primary">
					<div id="content" class="site-content container" role="main">

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'partials/content-download' ); ?>

							<!-- Get the next/previous post navs -->
							<?php checkout_post_navs(); ?>

							<!-- Get the EDD reviews -->
							<?php if ( class_exists( 'EDD_Reviews' ) ) {
								global $post;
								$user = wp_get_current_user();
								$user_id = ( isset( $user->ID ) ? (int) $user->ID : 0 );

								if ( ! edd_reviews()->is_review_status( 'disabled' ) ) {
								?>
								<div class="comments-section reviews-section">
									<div class="comments">
										<div class="comments-wrap">
										<?php
											edd_get_template_part( 'reviews' );
											if ( get_option( 'thread_comments' ) ) {
												edd_get_template_part( 'reviews-reply' );
											}
										?>
										</div><!-- .comments-wrap -->
									</div><!-- #comments -->
								</div><!-- .comments-section -->
							<?php } }

							if ( comments_open() || '0' != get_comments_number() ) :
								comments_template();
							endif;

							?>

						<?php endwhile; // end of the loop. ?>

					</div><!-- #content .site-content -->
				</div><!-- #primary .content-area -->

				<div id="secondary">
					<div id="sticker">
						<div id="purchase-box" class="purchase-box">
							<?php do_action( 'checkout_above_purchase_sidebar' ); ?>

							<div class="download-price download-aside">
								<?php if ( edd_has_variable_prices( $download_id ) ) { ?>
									<!-- Get the price range -->
									<div class="purchase-price price-range">
										<?php echo edd_price_range( $download_id ); ?>
									</div>
								<?php } else if ( function_exists( 'edd_cp_has_custom_pricing' ) && edd_cp_has_custom_pricing( $download_id ) ) { ?>
									<div class="purchase-price name-price">
										<?php _e( 'Name your price:', 'checkout' ); ?>
									</div>
								<?php } else if ( edd_is_free_download( $download_id ) ) { ?>
									<div class="purchase-price">
										<?php _e( 'Free', 'checkout' ); ?>
									</div>
								<?php } else { ?>
									<!-- Get the single price -->
									<div class="purchase-price">
										<?php edd_price( $download_id ); ?>
									</div>
								<?php } ?>

								<?php
									// Get purchase button settings
									$behavior = get_post_meta( $download_id, '_edd_button_behavior', true );

									$hide_button = get_post_meta( $download_id, '_edd_hide_purchase_link', true ) ? 1 : 0;

									// If it's a direct purchase show this text
									if ( $behavior == 'direct' ) {
										$button_text = __( 'Buy Now', 'checkout' );
									} else {
										// if it's an add to cart purchase, get the text from EDD options
										$button_text = ! empty( $edd_options[ 'add_to_cart_text' ] ) ? $edd_options[ 'add_to_cart_text' ] : __( 'Purchase', 'checkout' );
									}
								?>

								<?php
									// Show the button unless set to not show
									if ( ! $hide_button ) {
										echo edd_get_purchase_link( array(
											'download_id' => $download_id,
											'price'       => false,
											'direct'      => edd_get_download_button_behavior( $download_id ) == 'direct' ? true : false,
											'text'        => $button_text
										) );
									}
								?>

								<?php do_action( 'checkout_below_purchase_button' ); ?>
							</div><!-- .download-price -->

							<?php do_action( 'checkout_below_purchase_sidebar' ); ?>
						</div><!-- .purchase-box -->
					</div><!-- #sticker -->
				</div><!-- #secondary -->
			</div><!-- #main .site-main -->
		</div><!-- .sticky-container -->

<?php get_footer(); ?>
