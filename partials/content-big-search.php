<?php
/**
 * Big search field for products
 *
 * @package Checkout
 * @since Checkout 1.2.6
 */

$categories = get_terms( array( 'taxonomy' => 'download_category', 'hide_empty' => false ) );
?>

<div class="big-search">
	<form method="get" id="big-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'checkout' ); ?></label>

		<input type="text" name="s" id="big-search" placeholder="<?php _e( 'Search here...', 'checkout' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" onfocus="if(this.value==this.getAttribute('placeholder'))this.value='';" onblur="if(this.value=='')this.value=this.getAttribute('placeholder');"/><br />

		<div class="search-controls">
		<?php
		/**
		 * Generate list of EDD categories to search
		 */
		if ( $categories ) { ?>

			<div class="search-select-wrap">
				<select class="search-select" name="download_category">

					<option value=""><?php _e( 'Entire Site', 'checkout' ); ?></option>

					<?php
						/**
						 * Generate list of EDD categories
						 */
						foreach ( $categories as $category ) {
							echo '<option value="' . esc_attr( $category->slug ) . '">', $category->name, "</option>";
						}
					?>
				</select>
			</div>

		<?php } ?>

			<input type="submit" class="submit button" name="submit" id="big-search-submit" value="<?php esc_attr_e( 'Search', 'checkout' ); ?>" />
		</div><!-- .search-controls -->
	</form><!-- #big-searchform -->

	<?php
	/**
	 * Generate list of EDD categories to browse
	 */
	if ( $categories ) { ?>

		<div class="search-cats">
			<div class="search-cat-text">
				<?php _e( 'Or browse by category: ', 'checkout' ); ?>
			</div>

			<nav>
			<?php
				/**
				 * Generate list of EDD category links
				 */
				foreach ( $categories as $category ) {
					$link = get_term_link( $category, 'download_category' );

					echo '<a href="' . esc_url( $link ) . '" rel="tag">' . $category->name . '</a>';
				}
			?>
			</nav>
		</div>
	<?php } ?>
</div><!-- .big-search -->