<?php
/**
 * The template for displaying Comments.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<?php if ( class_exists( 'EDD_Reviews' ) && ! comments_open() ) {
	$comment_class = 'closed-with-reviews';
} else {
	$comment_class = 'open-with-reviews';
} ?>

<div class="comments-section <?php echo $comment_class; if ( '0' == get_comments_number() ) { echo "no-comments"; } ?>">
	<div id="comments">
		<div class="comments-wrap">
			<?php if ( have_comments() ) : ?>
				<?php do_action( 'checkout_comments_title' ); ?>
				<ol class="commentlist"><?php wp_list_comments( "callback=checkout_comment" ); ?></ol>
			<?php endif; ?>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comment-nav-below" role="navigation">
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'checkout' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'checkout' ) ); ?></div>
				</nav>
			<?php endif; // check for comment navigation ?>

			<?php if ( ! comments_open() && get_comments_number() ) : ?>
				<p class="no-comments"><?php _e( 'Comments are closed.' , 'checkout' ); ?></p>
			<?php endif; ?>

			<?php comment_form(  ); ?>
		</div><!-- .comments-wrap -->
	</div><!-- #comments -->
</div><!-- .comments-section -->
