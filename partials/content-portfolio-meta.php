<?php
/**
 * The template part for displaying the post meta information on portfolio items
 *
 * @package Checkout
 */

if ( class_exists( 'Array_Toolkit' ) ) {
	// Get the portfolio categories
	$portfolio_cats = get_the_term_list( get_the_ID(), 'categories', '', _x(' ', '', 'checkout' ), '' );
	// Get the portfolio tags
	$portfolio_tags = get_the_term_list( get_the_ID(), 'portfolio_tag', '', _x(' ', '', 'checkout' ), '' );
} else {
	$portfolio_cats = '';
	$portfolio_tags = '';
}
?>

	<div class="post-meta">
		<!-- Categories and tags for portfolio items -->
		<?php if ( $portfolio_cats || $portfolio_tags ) { ?>

			<span class="meta-cat">
				<?php echo $portfolio_cats; ?>

				<!-- Tags for portfolio items -->
				<?php if ( $portfolio_tags ) { ?>

					<span class="meta-tag">
						<?php echo $portfolio_tags; ?>
					</span>

				<?php } ?>
			</span>

		<?php } ?>

		<!-- Show the date on regular posts -->
		<span class="posted-on">
			<i class="fa fa-calendar"></i> <?php echo get_the_date(); ?>
		</span>
	</div><!-- .post-meta -->