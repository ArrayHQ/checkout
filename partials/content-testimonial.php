<?php
/**
 * The template used for displaying testimonials.
 *
 * @package Checkout
 */

$add_class = has_post_thumbnail() ? 'with-featured-image' : 'without-featured-image';

// Check for subtitles
$testimonial_subtitle = ( function_exists( 'get_the_subtitle' ) && '' != get_the_subtitle() ) ? ' with-subtitle' : ' without-subtitle';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $add_class . $testimonial_subtitle . ' post' ); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>

	<?php if ( has_post_thumbnail() ) { ?>
		<span class="testimonial-thumbnail">
			<?php the_post_thumbnail( 'testimonial-thumb' ); ?>
		</span>
	<?php } ?>

	<header class="entry-header">
		<h3 class="entry-title vcard author"><span class="fn"><?php the_title(); ?></span></h3>
		<?php if ( function_exists( 'the_subtitle' ) ) { ?>
			<?php the_subtitle( '<p class="entry-subtitle">', '</p>' ); ?>
		<?php } ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
