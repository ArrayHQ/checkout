<?php

/**
 * Template for displaying download output.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

$add_class = has_post_thumbnail() ? 'with-featured-image' : 'without-featured-image';
?>

	<article <?php post_class( $add_class . ' post' ); ?>>
		<div class="post-content">

			<?php
			/**
			 * If we have video, display that in place of the featured image
			 */
			if ( get_post_meta( $post->ID, 'array-video', true ) ) {
				echo get_post_meta( $post->ID, 'array-video', true );
			}

			/**
			 * If the gallery post format is used, create a carousel
			 */
			else if ( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>

				<?php
				/**
				 * Get the image carousel template
				 * @see content-carouse.php
				 */
				get_template_part( 'partials/content-carousel' ); ?>

			<?php } else if ( has_post_thumbnail() ) { ?>
				<?php if ( is_single() ) { ?>
					<div class="post-featured-image"><?php the_post_thumbnail( 'blog-image' ); ?></div>
				<?php } else { ?>
					<a class="post-featured-image" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'checkout' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'blog-image' ); ?></a>
				<?php } ?>
			<?php } ?>

			<div class="post-text">
				<!-- Title for improved structured data. Hidden because title is shown in the header -->
				<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><span itemprop="name"><?php the_title(); ?></span></a></h2>

				<?php checkout_content_filter_gallery(); ?>
			</div><!-- .post-text -->

			<?php do_action( 'checkout_after_download_content' ); ?>
		</div><!-- .post-content -->
	</article><!-- .post -->
