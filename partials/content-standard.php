<?php

/**
 * Template for standard posts and pages.
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
				 * @see content-carousel.php
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
				<?php if( is_single() || is_page() ) { } else { ?>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php } ?>

				<!-- Get post content or excerpt for archive pages -->
				<?php if ( is_archive() || is_search() ) {
					the_excerpt();

				} else {
					checkout_content_filter_gallery();

				}

				wp_link_pages( array(
					'before'      => '<div class="page-links">',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );


				if ( ! is_page() ) {
					if ( 'array-portfolio' == get_post_type() ) {
						get_template_part( 'partials/content-portfolio-meta' );
					} else {
						get_template_part( 'partials/content-meta' );
					}
				} ?>
			</div><!-- .post-text -->

			<!-- Author profile and links -->
			<?php $curauth = get_userdata( $post->post_author ); ?>
			<?php if ( is_singular( 'post' ) ) { ?>
				<header class="author-info">
					<div class="author-profile">
						<div class="author-avatar">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_attr_e( 'Posts by ', 'checkout' ); ?> <?php the_author(); ?>">
									<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'checkout_author_bio_avatar_size', 100 ) ); ?></a>
						</div><!-- .author-avatar -->

						<div class="author-description vcard author">
							<h2><span class="fn"><?php printf( __( 'Published by %s', 'checkout' ), get_the_author() ); ?></span></h2>
							<?php
								$authordesc = get_the_author_meta( 'description' );
								if ( $authordesc ) { ?>
									<p><?php the_author_meta( 'description' ); ?></p>
							<?php } ?>

							<div class="author-links">
								<?php _e( 'Posts by', 'checkout' ); ?> <?php the_author_posts_link(); ?>

								<?php if ( $curauth->user_url ) { ?>
								<a href="<?php echo $curauth->user_url; ?>"><?php _e( 'Visit Website', 'checkout' ); ?></a>
								<?php } ?>
							</div><!-- .author-links -->
						</div><!-- .author-description -->
					</div><!-- .author-profile -->
				</header><!-- author-info -->
			<?php } ?>
		</div><!-- .post-content -->
	</article><!-- .post -->
