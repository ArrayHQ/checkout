<?php
/**
 * Template for displaying post and page titles in the header.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

if ( is_singular( 'download' ) ) {
	$author = new WP_User( $post->post_author );
} else {
	if ( function_exists( 'fes_get_vendor' ) ) {
		$author = fes_get_vendor();
	} else {
		$author = get_query_var( 'vendor' );
		$author = get_user_by( 'slug', $author );

		if ( ! $author ) {
			$author = get_current_user_id();
		}
	}
}
?>

<?php if ( is_home() && is_front_page() ) { } else { ?>

<div class="hero-title">
	<div class="hero-title-inside">
		<h1 class="entry-title">
			<?php
				if ( is_category() ) :
					single_cat_title();

				elseif ( is_tag() ) :
					single_tag_title();

				elseif ( is_author() ) :
					the_post();
					printf( __( 'Author: %s', 'checkout' ), '' . get_the_author() . '' );
					rewind_posts();

				elseif ( is_day() ) :
					printf( __( 'Day: %s', 'checkout' ), '<span>' . get_the_date() . '</span>' );

				elseif ( is_month() ) :
					printf( __( 'Month: %s', 'checkout' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				elseif ( is_year() ) :
					printf( __( 'Year: %s', 'checkout' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				elseif ( is_404() ) :
					_e( 'Page Not Found', 'checkout' );

				elseif ( is_search() ) :
					printf( __( 'Search Results for: %s', 'checkout' ), '<span>' . get_search_query() . '</span>' );

				// Title for portfolio archive
				elseif ( is_post_type_archive( 'array-portfolio' ) ) :
					 post_type_archive_title();

				// Title for portfolio categories and tags
				elseif ( is_tax( array(
						'array-portfolio', 'categories' ) ) ) :
							single_term_title();

				// Title for download archive
				elseif ( is_post_type_archive( 'download' ) && function_exists( 'edd_get_label_plural' ) ) :
					echo edd_get_label_plural();

				// Title for single downloads
				elseif ( is_singular( 'download' ) ) :
					echo get_the_title();

				// Title for testomonial archive
				elseif ( is_post_type_archive( 'testimonial' ) ) :
					_e( 'Testimonials', 'checkout' );

				// Title for download categories and tags
				elseif ( is_tax( array(
						'download', 'download_category',
						'download', 'download_tag' ) ) ) :
							single_term_title();

				// Title for vendor archive pages
				elseif ( get_query_var( 'vendor' ) ) :
					$store_name = get_user_meta( $author->ID, 'name_of_store', true );
					if ( $store_name ) {
						echo $store_name;
					} else {
						echo fes_get_vendor()->display_name;
					}

				// Title for customer dashboard
				elseif ( is_page_template( 'templates/template-customer-dashboard.php' ) ) :

					if ( is_user_logged_in() ) {
						$current_user = wp_get_current_user();
						echo $current_user->display_name;
					} else {
						_e( 'Customer Login', 'checkout' );
					}

				else :
					single_post_title();

				endif;
			?>
		</h1>


		<?php
			if ( get_query_var( 'vendor' ) ) {
				// Member since date
				printf( __( '<p class="entry-subtitle">Member since %s</p>', 'checkout' ), date( "F Y", strtotime( fes_get_vendor()->user_registered ) ) );
			}

			// Get the category description
			if ( function_exists( 'get_the_archive_description' ) && get_the_archive_description() ) { ?>
				<?php echo get_the_archive_description(); ?>
		<?php } else { ?>
			<?php echo category_description(); ?>
		<?php } ?>


		<?php
			// Get the blog page ID
			if ( ! defined( 'get_the_ID' ) ) {
				$blog_id = get_the_id();
			}
			$page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : $blog_id );

			// Get post and page subtitles
			if ( is_singular() && function_exists( 'the_subtitle' ) ) { ?>
				<?php the_subtitle( '<p class="entry-subtitle">', '</p>' ); ?>
		<?php } elseif ( is_home() && ! is_front_page() ) { ?>
			<?php
				if ( function_exists( 'get_the_subtitle' ) ) {
				    echo '<p class="entry-subtitle">';
				    	echo get_the_subtitle( $page_id );
				    echo '</p>';
				}
			?>
		<?php } ?>

		<?php
		// Show the custom header text and button on homepage templates
		if (
		is_page_template( 'templates/template-homepage-widgets.php' ) ||
		is_page_template( 'templates/template-homepage-shop.php' ) ||
		is_page_template( 'templates/template-homepage-portfolio.php' ) ) {

			// Get the first CTA button link from Appearance > Customize > Theme Options -> Homepage Header Section
			if ( get_theme_mod( 'checkout_header_button_one_link' ) ) {

				$button_page_id = get_theme_mod( 'checkout_header_button_one_link' );
				$button_url = get_permalink( $button_page_id );

				if ( get_option( 'checkout_header_button_one_text' ) ) {
					$button_text = get_option( 'checkout_header_button_one_text' );

				} else {
					$button_text = get_the_title( $button_page_id );
				} ?>

				<a class="cta-button button button-one" href="<?php echo esc_url( $button_url ); ?>" title="<?php echo esc_attr( $button_text ); ?>">
					<?php echo $button_text; ?>
				</a>
				<?php
			}

			// Get the second CTA button link from Appearance > Customize > Theme Options -> Homepage Header Section
			if ( get_theme_mod( 'checkout_header_button_two_link' ) ) {

				$button_two_page_id = get_theme_mod( 'checkout_header_button_two_link' );
				$button_two_url = get_permalink( $button_two_page_id );

				if ( get_option( 'checkout_header_button_two_text' ) ) {
					$button_two_text = get_option( 'checkout_header_button_two_text' );

				} else {
					$button_two_text = get_the_title( $button_two_page_id );
				} ?>

				<a class="cta-button button button-two" href="<?php echo esc_url( $button_two_url ); ?>" title="<?php echo esc_attr( $button_two_text ); ?>"><?php echo $button_two_text; ?></a>
				<?php
			}
		} // if is homepage templates ?>


		<?php do_action( 'checkout_below_page_titles' ); ?>
	</div><!-- .hero-title-inside -->
</div><!-- .hero-title -->

<?php } ?>
