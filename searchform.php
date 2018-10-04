<?php
/**
 * The template for displaying search forms
 *
 * @package Checkout
 * @since Checkout 1.0
 */
?>

	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'checkout' ); ?></label>
		<input type="text" class="field" name="s" placeholder="<?php _e( 'Search here...', 'checkout' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'checkout' ); ?>" />
	</form>