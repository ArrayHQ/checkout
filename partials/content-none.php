<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package Checkout
 */
?>

<article class="post no-results not-found">
	<div class="post-content">
		<div class="post-text">
			<h2><?php _e( 'Nothing Found', 'checkout' ); ?></h2>

			<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

				<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'checkout' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

			<?php elseif ( is_search() ) : ?>

				<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'checkout' ); ?></p>
				<?php get_search_form(); ?>

			<?php else : ?>

				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'checkout' ); ?></p>
				<?php get_search_form(); ?>

			<?php endif; ?>
		</div><!-- .post-text -->
	</div><!-- .post-content -->
</article><!-- .post -->