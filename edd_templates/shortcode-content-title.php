<h3 itemprop="name" class="edd_download_title">
	<a title="<?php the_title_attribute(); ?>" itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h3>

<!-- Bring in the download meta -->
<?php do_action( 'checkout_below_download_title' ); ?>