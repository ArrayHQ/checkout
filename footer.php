<?php
/**
 * The template for displaying the footer.
 *
 * @package Checkout
 * @since Checkout 1.0
 */
?>

	</div><!-- #page -->

	<footer id="colophon" class="site-footer">
		<div class="site-footer-inside center">
			<?php if ( is_active_sidebar( 'footer' ) ) { ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer' ); ?>
				</div><!-- .footer-widgets -->
			<?php } ?>

			<div class="footer-copy">
				<div class="copyright">
					<div class="site-info">
						<?php
							// Get the footer copyright text
							$footer_copy_text = get_option( 'checkout_footer_text' );

							if ( $footer_copy_text ) {
								// If we have footer text, use it
								$footer_text = $footer_copy_text;
							} else {
								// Otherwise show the fallback theme text
								$footer_text = '&copy; ' . date("Y") . sprintf( __( ' %1$s Theme by %2$s.', 'checkout' ), 'Checkout', '<a href="https://arraythemes.com/" rel="nofollow">Array</a>' );
							}

							// Site description
							$footer_text .= '<span class="sep"> | </span>';
							$footer_text .= get_bloginfo( "description" );
						?>

						<?php echo apply_filters( 'checkout_footer_text', $footer_text ); ?>
					</div><!-- .site-info -->
				</div><!-- .copyright -->

				<nav class="footer-navigation" role="navigation">
					<?php wp_nav_menu( array(
						'theme_location' => 'footer',
						'depth'          => 1,
						'fallback_cb'    => false
					) );?>
				</nav><!-- .footer-navigation -->
			</div><!-- .footer-copy -->
		</div><!-- .site-footer-inside -->

		<!-- Footer background image effect -->
		<?php
			$footer_bg      = get_theme_mod( 'checkout_footer_background_image' );
			$footer_opacity = get_theme_mod( 'checkout_footer_bg_opacity', '0.1' );

			if ( $footer_bg ) { ?>
				<div class="site-footer-bg background-effect" style="background-image: url(<?php echo esc_url( $footer_bg ); ?>); opacity: <?php echo esc_attr( $footer_opacity ); ?>;"></div>
		<?php } ?>
	</footer><!-- #colophon .site-footer -->

<?php wp_footer(); ?>

</body>
</html>