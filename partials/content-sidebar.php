<?php
/**
 * The template part for displaying the sidebar on posts and pages.
 *
 * @package Checkout
 */
?>

			<?php
			// Get the sidebar widgets
			if ( is_active_sidebar( 'sidebar-widgets' ) ) : ?>
				<div id="secondary-page">

					<?php do_action( 'checkout_above_page_sidebar' ); ?>

					<?php dynamic_sidebar( 'sidebar-widgets' ); ?>

					<?php do_action( 'checkout_below_page_sidebar' ); ?>

				</div><!-- #secondary -->
			<?php endif; ?>