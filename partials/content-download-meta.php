<?php
/**
 * The template part for displaying the post meta information for downloads
 *
 * @package Checkout
 */

if( class_exists( 'Easy_Digital_Downloads' ) ) {
	// Get the download tags
	$download_tags = get_the_term_list( get_the_ID(), 'download_tag', '', _x(' ', '', 'checkout' ), '' );
	// Get the download categories
	$download_cats = get_the_term_list( get_the_ID(), 'download_category', '', _x(' ', '', 'checkout' ), '' );
} else {
	$download_tags = '';
	$download_cats = '';
}

?>

	<!-- Categories and tags for downloads -->
	<?php if ( $download_cats || $download_tags ) { ?>
		<div class="post-meta">
			<!-- Get the download categories -->
			<span class="meta-cat">
				<?php if ( $download_cats ) {
					echo $download_cats;
				} ?>

				<!-- Get the download tags -->
				<?php if ( $download_tags ) { ?>
					<span class="meta-tag">
						<?php echo $download_tags; ?>
					</span>
				<?php } ?>
			</span>
		</div><!-- .post-meta -->
	<?php } ?>