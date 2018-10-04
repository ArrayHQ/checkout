<?php
/**
 * The template used for displaying the call-to-action banner on the homepage.
 *
 * @package Checkout
 */
?>
		<?php do_action( 'checkout_homepage_above_cta' ); ?>

		<?php if (
			get_option( 'checkout_footer_cta_title' ) ||
			get_option( 'checkout_footer_cta_subtitle' ) ||
			get_option( 'checkout_footer_cta_button' ) ) { ?>
			<section class="section-cta">
				<div class="center">
					<?php
					if ( get_option( 'checkout_footer_cta_title' ) ) {
						echo '<h3>' . get_option( 'checkout_footer_cta_title' ) . '</h3>';
					}

					if ( get_option( 'checkout_footer_cta_subtitle' ) ) {
						echo '<p>' . get_option( 'checkout_footer_cta_subtitle' ) . '</p>';
					}

					if ( get_theme_mod( 'checkout_footer_cta_button' ) ) {
						$button_cta_page_id = get_theme_mod( 'checkout_footer_cta_button' );
						$button_cta_url = get_permalink( $button_cta_page_id );

						if ( get_option( 'checkout_footer_cta_button_text' ) ) {
							$button_cta_text = get_option( 'checkout_footer_cta_button_text' );
						} else {
							$button_cta_text = get_the_title( $button_cta_page_id );
						}
					?>

					<a class="cta-button button" href="<?php echo esc_url( $button_cta_url ); ?>" title="<?php echo esc_attr( $button_cta_text ); ?>">
						<?php echo $button_cta_text; ?>
					</a>
					<?php } ?>
				</div>
			</section>
		<?php } ?>