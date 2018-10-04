<?php
/**
 * The template part for displaying the post meta information
 *
 * @package Checkout
 */
$tags = get_the_tags();
?>

	<div class="post-meta">
		<!-- Categories and tags for posts -->
		<?php if ( has_category() || ! empty( $tags ) ) { ?>

			<span class="meta-cat">
				<?php the_category( ' ' ); ?>

				<!-- Tags for posts -->
				<?php if ( is_single() && ! empty( $tags ) ) { ?>

					<span class="meta-tag">
						<?php the_tags( ' ',' ' ); ?>
					</span>

				<?php } ?>
			</span>

		<?php } ?>

		<!-- Show the date on posts -->
		<span class="posted-on post-date updated">
			<i class="fa fa-calendar"></i> <?php echo get_the_date(); ?>
		</span>
	</div><!-- .post-meta -->
