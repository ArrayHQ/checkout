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
		<div class="post-content-forum">
			<div class="post-text">
				<?php if( is_single() || is_page() ) { } else { ?>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php } ?>

				<!-- Get forum content -->
				<?php the_content(); ?>
			</div><!-- .post-text -->
		</div><!-- .post-content -->
	</article><!-- .post -->