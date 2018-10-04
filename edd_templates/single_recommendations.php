<?php
global $post;
$suggestion_data = edd_rp_get_suggestions( $post->ID );

if ( is_array( $suggestion_data ) && !empty( $suggestion_data ) ) :
	$suggestions = array_keys( $suggestion_data );

	$suggested_downloads = new WP_Query( array( 'post__in' => $suggestions, 'post_type' => 'download' ) );

	if ( $suggested_downloads->have_posts() ) : ?>
		<div id="edd-rp-single-wrapper">
			<h5 id="edd-rp-single-header"><?php echo sprintf( __( 'Users who purchased %s, also purchased:', 'edd-rp-txt' ), get_the_title() ); ?></h5>
			<div id="edd-rp-items-wrapper" class="edd-rp-single">
				<?php while ( $suggested_downloads->have_posts() ) : ?>
					<?php $suggested_downloads->the_post();	?>
					<div class="edd-rp-item <?php echo ( !current_theme_supports( 'post-thumbnails' ) ) ? 'edd-rp-nothumb' : ''; ?>">
						<?php do_action( 'edd_rp_item_before' ); ?>

						<a href="<?php the_permalink(); ?>">
							<?php if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( get_the_ID() ) ) :?>
								<div class="edd_cart_item_image">
									<?php echo get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb' ); ?>
								</div>
							<?php else: ?>
							<?php endif; ?>

							<?php the_title( '<span class="edd-rp-item-title">', '</span>' ); ?>

							<?php do_action( 'edd_rp_item_after_title' ); ?>
						</a>

						<?php do_action( 'edd_rp_item_after_thumbnail' ); ?>

						<?php if ( ! edd_has_variable_prices( get_the_ID() ) ) : ?>
							<?php edd_price( get_the_ID() ); ?>
						<?php endif; ?>

						<?php if ( edd_has_variable_prices( get_the_ID() ) ) { ?>
							<!-- Get the price range -->
							<?php echo checkout_edd_price_range(); ?>
						<?php } ?>

						<?php do_action( 'edd_rp_item_after_price' ); ?>

						<?php
						$purchase_link_args = array(
							'download_id' => get_the_ID(),
							'price' => false,
							'direct' => false,
						);
						$purchase_link_args = apply_filters( 'edd_rp_purchase_link_args', $purchase_link_args );
						echo edd_get_purchase_link( $purchase_link_args );
						?>

						<?php do_action( 'edd_rp_item_after' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>

<?php endif; ?>
