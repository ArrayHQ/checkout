<?php if( has_post_format( 'gallery' ) && get_post_gallery() ) {

	get_template_part( 'partials/content-carousel' );

} else if ( has_post_thumbnail( get_the_ID() ) ) { ?>

	<div class="edd_download_image">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb' ); ?>
		</a>
	</div>

<?php } ?>