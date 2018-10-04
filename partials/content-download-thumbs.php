<?php
/**
 * This template is used to display downloads output
 * by the [download] shortcode download archive.
 *
 * @package Checkout
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post column portfolio-post equal edd_download' ); ?>>

	<!-- If the gallery post format is used, create a carousel -->
	<?php if ( has_post_format( 'gallery' ) && get_post_gallery() ) {

		// Save the current post before doing another query
		$current = get_post();

		get_template_part( 'partials/content-carousel' );

		// Restore the current post
		$post = $current;
		setup_postdata( $post );

	} else if ( has_post_thumbnail() ) { ?>
		<a class="post-featured-image" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'checkout' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'portfolio-thumb' ); ?></a>
	<?php } ?>

	<header itemscope class="entry-header">
		<h3 itemprop="name" class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

		<div itemprop="description" class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<?php
			if( class_exists( 'Easy_Digital_Downloads' ) ) {
				// Get the download's author and purchase links
				checkout_download_meta();

				// Get the download's pricing
				get_template_part( '/edd_templates/shortcode-content-cart-button' );
			}
		?>

	</header><!-- .entry-header -->

</article><!-- #post-## -->
