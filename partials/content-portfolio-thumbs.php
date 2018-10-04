<?php
/**
 * The template used for displaying portfolio columns
 *
 * @package Checkout
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post column portfolio-post equal' ); ?>>

	<!-- If the gallery post format is used, create a carousel -->
	<?php if ( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>

		<!-- Get the carousel template (content-carouse.php) -->
		<?php get_template_part( 'partials/content-carousel' ); ?>

	<?php } else if ( has_post_thumbnail() ) { ?>
		<a class="post-featured-image" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'checkout' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'portfolio-thumb' ); ?></a>
	<?php } ?>

	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<div class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<?php
			// Get the portfolio categories
			if ( class_exists( 'Array_Toolkit' ) ) {
				echo get_the_term_list( get_the_ID(), 'categories', '<div class="post-meta">', _x(' ', '', 'checkout' ), '</div>' );
			}
		?>
	</header><!-- .entry-header -->

</article><!-- #post-## -->