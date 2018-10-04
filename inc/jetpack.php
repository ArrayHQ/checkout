<?php
/**
 * Jetpack Compatibility File
 *
 * @package Checkout
 */

function checkout_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => '#posts',
		'footer'    => 'page',
		'footer_widgets' => array( 'footer' ),
		'render'    => 'checkout_render_infinite_posts',
		'wrapper'   => 'new-infinite-posts',
	) );
}
add_action( 'after_setup_theme', 'checkout_jetpack_setup' );


/* Render infinite posts by using template parts */
function checkout_render_infinite_posts() {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'partials/content-standard' );
	}
}