<?php
/**
 * Easy Digital Downloads functions
 *
 * @package Checkout
 */

/**
 * Add a Download Details metabox to downloads
 *
 * @since 1.0
 */
require_once( get_template_directory() . '/inc/admin/metabox/metabox.php' );


/**
 * Add a demo URL to downloads
 *
 * @since 1.0
 */
add_theme_support( 'array_themes_demo_link_support' );


/**
 * Load the Pricing Table widgets
 *
 * @since 1.0
 */
require_once( get_template_directory() . '/inc/widgets/widget-pricing-table-variable.php' );
require_once( get_template_directory() . '/inc/widgets/widget-pricing-table-standard.php' );


/**
 * Enqueue scripts and styles
 *
 * @since 1.0
 */
function checkout_edd_scripts() {

	// EDD javascript
	wp_enqueue_script( 'checkout-edd-js', get_template_directory_uri() . '/js/edd.js', array( 'jquery' ), '1.0', true );

	// If we're on a single download or vendor page, load the sticky script conditionally
	if ( is_singular( 'download' ) || is_page_template( 'templates/template-vendor.php' ) ) {
		wp_localize_script( 'checkout-edd-js', 'checkout_load_js_vars', array(
				'load_sticky' => 'true'
			)
		);
	} else {
		wp_localize_script( 'checkout-edd-js', 'checkout_load_js_vars', array(
				'load_sticky' => false
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'checkout_edd_scripts' );


/**
 * Add body class by filter
 *
 * @since 1.0
 */
function checkout_body_class( $classes ) {

	/**
	 * Add body class to vendor dashboard pages
	 */
	if( function_exists( 'EDD_FES' ) && is_page( EDD_FES()->helper->get_option( 'fes-vendor-dashboard-page', false ) ) ) {
		$classes[] = 'vendor-dashboard';

		if ( ! is_user_logged_in() ) {
			$classes[] = 'not-signed-in';
		}

		if ( ! empty( $_GET['view'] ) && 'application' === $_GET['view'] ) {
			$classes[] = 'vendor-application';
		}

		$task = '';

		if( isset( $_GET['task'] ) && ! empty( $_GET['task'] ) ) {
			$task = $_GET['task'];
		}

		switch( $task ) {
			case 'new-product':
				$classes[] = 'product-management';
				break;

			case 'edit-product':
				$classes[] = 'product-management';
				break;

			case 'earnings':
				$classes[] = 'vendor-earnings';
				break;

			case 'products':
				$classes[] = 'product-list';
				break;

			case 'profile':
				$classes[] = 'edit-profile';
				break;
		}

	}

	/**
	 * Add body class to vendor pages
	 */
	$vendor = get_query_var( 'vendor' );
	if( ! empty( $vendor ) ) {
		$classes[] = 'vendor-archive';
	}

	return $classes;
}
add_filter( 'body_class', 'checkout_body_class' );


/**
 * Add wrapper class to EDD [download] shortcode
 *
 * @since Checkout 1.0
 */
function checkout_edd_download_wrap_class( $class, $atts ) {
	return 'portfolio-wrapper download-wrapper ' . $class;
}
add_filter( 'edd_downloads_list_wrapper_class', 'checkout_edd_download_wrap_class', 10, 2 );


/**
 * Custom pricing range output
 *
 * @since 1.0
 */
function checkout_edd_price_range( $download_id = 0 ) {

	$low   = edd_get_lowest_price_option( $download_id );
	$high  = edd_get_highest_price_option( $download_id );
	$range = '<span class="edd_price">' . edd_currency_filter( edd_format_amount( $low ) );
	$range .= ' – ';
	$range .= edd_currency_filter( edd_format_amount( $high ) ) . '</span>';

	return apply_filters( 'edd_price_range', $range, $download_id, $low, $high );
}


/**
 * Download meta list
 *
 * @since 1.0
 */
if ( ! function_exists( 'checkout_download_meta' ) ) :
function checkout_download_meta() {
	global $post; ?>

		<div class="download-meta">
			<div class="download-meta-price download-price-toggle">
				<div class="download-meta-price-details">

					<?php if ( edd_has_variable_prices( get_the_ID() ) ) { ?>
						<!-- Get the price range -->
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo checkout_edd_price_range(); ?></a>
					<?php } else if ( edd_is_free_download( get_the_ID() ) ) { ?>
						<!-- Get free download text -->
						<a href="<?php the_permalink(); ?>" rel="bookmark"><span class="edd_price"><?php _e( 'Free', 'checkout' ); ?></span></a>
					<?php } else { ?>
						<!-- Get the standard price -->
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php edd_price( get_the_ID() ); ?></a>
					<?php } ?>

					<span class="close-pricing"><?php _e( 'Close', 'checkout' ); ?></span>
				</div>
			</div>

			<div class="download-meta-name">
				<?php if ( class_exists( 'EDD_Front_End_Submissions' ) ) { ?>
					<?php
					printf( '<span class="author vcard"><a rel="author" class="fn" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
						// Get the vendor url
						checkout_fes_author_url( get_the_author_meta( 'ID' ) ),
						// Create a link title
						esc_attr( sprintf( __( 'View all posts by %s', 'checkout' ), get_the_author() ) ),
						// Get the avatar
						get_avatar( get_the_author_meta( 'ID' ), 20 ),
						// Get the author display name
						esc_html( get_the_author_meta( 'display_name' ) )
					);
				?>

				<?php } else { ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'View Details', 'checkout' ); ?></a>
				<?php } ?>
			</div>
		</div><!-- .download-meta -->

	<?php
} endif;
add_action( 'checkout_below_download_title', 'checkout_download_meta' );


/**
 * Get vendor author URL
 *
 * @since 1.0
 */
function checkout_fes_author_url( $author = null ) {
	if ( ! $author ) {
		$author = wp_get_current_user();
	} else {
		$author = new WP_User( $author );
	}

	if ( ! class_exists( 'EDD_Front_End_Submissions' ) ) {
		return get_author_posts_url( $author->ID, $author->user_nicename );
	}

	return EDD_FES()->vendors->get_vendor_store_url( $author->ID );
}


/**
 * Get the download details metabox content
 *
 * @since 1.0
 */
 if ( ! function_exists( 'checkout_edd_download_details' ) ) :
function checkout_edd_download_details( $post_id ) {

	if( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get the download details
	$checkout_download_details = get_post_meta( $post_id, 'checkout_download_details', false );

	// Get the download tags
	$download_tags = get_the_term_list( get_the_ID(), 'download_tag', '', _x(', ', '', 'checkout' ), '' );

	// Get the download categories
	$download_cats = get_the_term_list( get_the_ID(), 'download_category', '', _x(', ', '', 'checkout' ), '' );

	// If the details exist, show them on the single download sidebar
	if( $checkout_download_details || $download_cats || $download_tags ) {

		echo "<div class='download-details download-aside'>";

			if( $checkout_download_details ) {

				if( ! is_array( $checkout_download_details[0] ) ) {
					/**
					 * We likely got this data from FES repeating fields, which stores
					 * the data in a pipe-separated string.
					 */
					$checkout_download_details = explode( '| ', $checkout_download_details[0] );
				}

				echo "<div class='download-features' itemprop='itemCondition'>";
				echo "<ul>";
				foreach ( $checkout_download_details as $key => $value ) {

					if( is_array( $value ) ) {
						foreach ( $value as $string ) {
							if ( ! empty( $string['price_option'] ) && 'all' == $string['price_option'] ) {
								echo "<li>";
								echo $string['text'];
								echo "</li>";
							}
						}
					} else {
						echo "<li>";
						echo $value;
						echo "</li>";
					}

				}
				echo "</ul>";
				echo '</div><!-- .download-features -->';

			}

			if ( $download_cats || $download_tags ) { ?>
				<div class="post-meta">
					<!-- Get the download categories -->
					<?php if ( $download_cats ) { ?>
						<span class="meta-cat">
							<i class="fa fa-folder"></i>
							<?php echo $download_cats; ?>
						</span>
					<?php } ?>

					<!-- Get the download tags -->
					<?php if ( $download_tags ) { ?>
						<span class="meta-tag">
							<i class="fa fa-tags"></i>
							<?php echo $download_tags; ?>
						</span>
					<?php } ?>
				</div><!-- .post-meta -->
			<?php }

		echo "</div>";
	}
} endif;
add_action( 'checkout_below_purchase_sidebar', 'checkout_edd_download_details', 5, 1 );


/**
 * Check for HTML download details for vendors
 *
 * @since 1.5.0
 */
function checkout_download_details_check() {

    if ( ! is_singular( 'download' ) ) {
        return;
    }

    if ( get_post_meta( get_the_ID(), 'checkout_download_sidebar_html', true ) ) {
        remove_action( 'checkout_below_purchase_sidebar', 'checkout_edd_download_details' );
        add_action( 'checkout_below_purchase_sidebar', 'checkout_display_sidebar_html' );
    }
}
add_action( 'template_redirect', 'checkout_download_details_check' );


/**
 * Add HTML download details for vendors
 *
 * @since 1.5.0
 */
function checkout_display_sidebar_html() {
	$html_download_details = get_post_meta( get_the_ID(), 'checkout_download_sidebar_html', true );

	echo "<div class='download-details download-aside'>";
		echo "<div class='download-features' itemprop='itemCondition'>";
			echo $html_download_details;
		echo "</div>";
	echo "</div>";
}


/**
 * Get the download details list items only
 *
 * @since 1.0
 */
function checkout_edd_download_details_list_items( $post_id, $price_key ) {

	if( empty( $price_key ) ) {
		return false;
	}

	if( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get the download details
	$details = get_post_meta( $post_id, 'checkout_download_details', false );

	$items = array();

	if( ! empty( $details ) ) :

		foreach( $details[0] as $key => $item ) :

			if( $item['price_option'] == $price_key ) {
				$items[] = $item['text'];
			}
		endforeach;

	endif;

	return $items;
}


/**
 * Create the author profile for single download pages and vendor pages
 *
 * @since 1.0
 */
function checkout_fes_author_details( $author = null ) {
	// Get author data
	global $post;

	if ( class_exists( 'EDD_Front_End_Submissions' ) ) {

		if ( is_singular( 'download' ) ) {
			$author = new WP_User( $post->post_author );
		} else {
			$author = fes_get_vendor();
		}

		if ( ! $author ) {
			$author = get_current_user_id();
		} ?>

		<div class="download-author download-aside">
			<!-- Show the author avatar -->
			<div class="download-author-avatar">
				<?php echo get_avatar( $author->ID, 75 ) ?>
			</div>

			<!-- Show the author name -->
			<p class="download-author-name">
				<?php
					$store_name = get_user_meta( $author->ID, 'name_of_store', true );
					if ( $store_name ) {
						echo $store_name;
					} else {
						echo fes_get_vendor()->display_name;
					}
				?>
			</p>

			<!-- Show the author description if one exists -->
			<?php if ( '' != $author->description ) : ?>
				<div class="download-author-description">
					<p><?php echo $author->description; ?></p>
				</div>
			<?php endif; ?>

			<!-- Show the author website if one exists -->
			<?php if ( '' != $author->user_url && is_page_template( 'templates/template-vendor.php' ) ) : ?>
				<div class="download-author-description">
					<p><a rel="author" href="<?php echo esc_url( $author->user_url ); ?>"><?php _e( 'Visit Website', 'checkout' ); ?></a></p>
				</div>
			<?php endif; ?>

			<!-- Show the author profile link -->
			<?php if ( is_singular( 'download' ) ) { ?>
				<?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', checkout_fes_author_url( $author->ID ), __( 'View Full Profile', 'checkout' ) ); ?>
			<?php } ?>
		</div><!-- .download-author -->
	<?php }
}
add_action( 'checkout_below_vendor_sidebar', 'checkout_fes_author_details', 5 );


/**
 * Create the author profile for single download pages
 *
 * @since 1.0
 */
function checkout_fes_author_profile( $author = null ) {
	// Get author data
	global $post;

	if ( is_singular( 'download' ) ) {
		$author = new WP_User( $post->post_author );
	} else {
		$author = fes_get_vendor();
	}

	if ( ! $author ) {
		$author = get_current_user_id();
	}

	if ( class_exists( 'EDD_Front_End_Submissions' ) ) { ?>

		<header class="author-info">
			<div class="author-profile">
				<div class="author-avatar">
					<a href="<?php echo esc_url( checkout_fes_author_url( $author->ID ) ); ?>" title="<?php printf( esc_html_x( '%s by %s', 'downloads by seller name', 'checkout' ), edd_get_label_plural( false ), get_the_author() ); ?>">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'checkout_author_bio_avatar_size', 100 ) ); ?>
					</a>
				</div>

				<div class="author-description vcard author">
					<h2><span class="fn"><?php printf( __( '%s', 'checkout' ), get_the_author() ); ?></span></h2>

					<?php
						$authordesc = get_the_author_meta( 'description' );
						if ( $authordesc ) { ?>
							<p><?php the_author_meta( 'description' ); ?></p>
					<?php } ?>

					<div class="author-links">
						<!-- Show the author profile link -->
						<?php printf( '<a class="author-link" href="%s" rel="author">' . esc_html_x( 'View All %s', 'downloads by seller name', 'checkout' ) . '</a>', checkout_fes_author_url( $author->ID ), edd_get_label_plural( false ) ); ?>
					</div><!-- .author-links -->
				</div><!-- .author-description -->
			</div><!-- .author-profile -->
		</header><!-- author-info -->

	<?php }
}
add_action( 'checkout_after_download_content', 'checkout_fes_author_profile', 15 );


/**
 * Create the author contact form for vendors
 *
 * @since 1.0
 */
function checkout_fes_contact_form( $author = '' ) {
	// Get author data
	global $post;

	if ( empty( $author ) ) {

		if ( function_exists( 'fes_get_vendor' ) ) {
			$author = fes_get_vendor();
		} else {
			$author = get_user_by( 'slug', get_query_var( 'vendor' ) );

			if ( ! $author ) {
				$author = get_current_user_id();
			}
		}

	}

	if ( class_exists( 'EDD_Front_End_Submissions' ) && ! empty( $author ) ) : ?>
		<div class="download-aside">
			<?php echo do_shortcode( '[fes_vendor_contact_form id=" ' . absint( $author->ID ) . ' "]' ); ?>
		</div>
	<?php endif;
}
add_action( 'checkout_below_vendor_sidebar', 'checkout_fes_contact_form', 10 );


/**
 * Add checkout cart to menu
 *
 * @since 1.0
 */
if ( ! function_exists( 'checkout_add_cart_widget_to_menu' ) ) :
function checkout_add_cart_widget_to_menu( $items, $args ) {

	if ( 'primary' != $args->theme_location )
		return $items;

	ob_start();

	$widget_args = array(
		'before_widget' => '<div class="widget_edd_cart_widget">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => ''
	);

	$widget = the_widget( 'edd_cart_widget', array( 'title' => '' ), $widget_args );

	$widget = ob_get_clean();

	$link = sprintf( '<li class="current-cart menu-item menu-item-has-children"><a href="%s"><span class="edd-cart-quantity">%d</span></a><ul class="sub-menu"><li class="widget">%s</li></ul></li>', get_permalink( edd_get_option( 'purchase_page' ) ), edd_get_cart_quantity(), $widget );

		return $link . $items;
} endif;
add_filter( 'wp_nav_menu_items', 'checkout_add_cart_widget_to_menu', 10, 2 );


/**
 * Show the list of products when the cart is empty
 *
 * @since 1.0
 */
function checkout_empty_cart_template() {

	echo do_shortcode( '[downloads orderby="random"]' );
}
add_filter( 'edd_cart_empty', 'checkout_empty_cart_template' );


/**
 * Move the purchase button on single download pages
 *
 * @since 1.0
 */
function checkout_move_edd_purchase_button() {
	if ( is_singular( 'download' ) ) {
		remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );
	}
}
add_action( 'template_redirect', 'checkout_move_edd_purchase_button' );


/**
 * Remove default placement of reviews
 *
 * @since 1.5.7
 */
function checkout_move_reviews() {
	if ( class_exists( 'EDD_Reviews' ) ) {
		$edd_reviews = edd_reviews();
		remove_filter( 'the_content', array( $edd_reviews, 'load_frontend' ) );
	}
}
add_action( 'template_redirect', 'checkout_move_reviews' );


/**
 * Review breakdown for single download sidebar
 *
 * @since 1.0
 */
function checkout_reviews_breakdown() {
	if ( class_exists( 'EDD_Reviews' ) && is_singular( 'download' ) ) {
		global $post;

		$reviews = edd_reviews();
		$total = wp_count_comments( $post->ID )->total_comments;

		if ( $total > 0 ) {
			echo $reviews->maybe_show_review_breakdown();
		}
	}
}


/**
 * Add average rating to single download sidebar
 *
 * @since 1.0
 */
function checkout_reviews_average() {
	if ( class_exists( 'EDD_Reviews' ) && is_singular( 'download' ) ) {
		global $post;

		$reviews = edd_reviews();
		$total   = $reviews->count_reviews();
		$user    = wp_get_current_user();
		$user_id = ( isset( $user->ID ) ? (int) $user->ID : 0 );

		// Only show if there are reviews
		if ( $total > 0 && edd_reviews()->is_review_status( 'enabled' ) ) {
			echo '<div class="average-rating download-aside">';

			// Average rating title
			echo  '<p class="average-rating-title"> ' . __( 'Average Rating', 'checkout' ) . ' </p>';

			// Show the average rating
			echo $reviews->display_aggregate_rating();

			// Number of ratings
			echo '<p class="average-rating-count">' . __( 'Based on ', 'checkout' ) . $reviews->count_reviews() . ' ' . _n( 'review', 'reviews', $reviews->count_reviews(), 'checkout' ) . '</p>';

			echo '</div>';
		}

	}
}
add_action( 'checkout_below_purchase_sidebar', 'checkout_reviews_average', 4, 1 );


/**
 * Remove microformat formatting for share buttons
 *
 * @since 1.0
 */
function checkout_raw_title() {
	remove_filter( 'edd_add_schema_microdata', '__return_true' );

	$share_title = the_title_attribute();

	add_filter( 'edd_add_schema_microdata', '__return_true' );

	return $share_title;
}

/**
 * Add sharing buttons to product sidebar
 *
 * @since 1.0
 */
function checkout_share_buttons() {
	?>
	<div class="download-aside checkout-share">
		<ul>
			<li class="share-title"><?php _e( 'Share this:', 'checkout' ); ?></li>

			<li class="share-twitter">
				<a title="<?php _e( 'Share on Twitter', 'checkout' ); ?>" href="https://twitter.com/intent/tweet?text=<?php echo esc_html( checkout_raw_title() ); ?> &mdash; <?php the_permalink(); ?>" onclick="window.open(this.href, 'mywin','left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+',width=600,height=350,toolbar=0'); return false;">
					<i class="fa fa-twitter-square"></i></a>
			</li>

			<li class="share-facebook">
				<a title="<?php _e( 'Share on Facebook', 'checkout' ); ?>" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'mywin','left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+',width=600,height=350,toolbar=0'); return false;">
					<i class="fa fa-facebook-square"></i>
				</a>
			</li>

			<li class="share-pinterest">
				<a href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;https://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class="fa fa-pinterest-square"></i></a>
			</li>

			<li class="share-google">
				<!-- google plus -->
				<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="window.open('https://plus.google.com/share?url=<?php the_permalink(); ?>','gplusshare','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;"><i class="fa fa-google-plus-square"></i></a>
			</li>
		</ul>
	</div>
	<?php
}
add_action( 'checkout_below_purchase_sidebar', 'checkout_share_buttons', 20, 1 );


/**
 * EDD related customizer settings
 *
 * @param WP_Customize_Manager $wp_customize
 */
function checkout_customizer_edd_register( $wp_customize ) {

	/**
	 * EDD Homepage Template Options
	 */
	$wp_customize->add_section( 'checkout_edd_section', array(
		'title'           => __( 'Download Section', 'checkout' ),
		'priority'        => 2,
		'panel'           => 'checkout_home_options',
		'active_callback' => 'checkout_is_download_page',
		'description'     => __( 'Select the number of downloads to show and select a tag to display featured downloads.', 'checkout' ),
	) );


	/**
	 * Number of products to show on homepage
	 */
	$wp_customize->add_setting( 'checkout_homepage_download_count', array(
		'default'           => '6',
		'capability'        => 'edit_theme_options',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'checkout_homepage_download_count_select', array(
		'settings'        => 'checkout_homepage_download_count',
		'label'           => sprintf( __( 'Number of %s to show:', 'checkout' ), edd_get_label_plural() ),
		'section'         => 'checkout_edd_section',
		'type'            => 'select',
		'active_callback' => 'checkout_is_edd',
		'choices'  => array(
			'0'  => '0',
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12',
			'13' => '13',
			'14' => '14',
			'15' => '15',
		),
	) );


	/**
	 * EDD Featured Tag Select
	 */
	$wp_customize->add_setting( 'checkout_edd_tag_select', array(
		'default'           => '',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'checkout_sanitize_edd_tag'
	) );

	$wp_customize->add_control( 'checkout_edd_tag_select', array(
		'settings'        => 'checkout_edd_tag_select',
		'label'           => __( 'Featured Product Tag', 'checkout' ),
		'section'         => 'checkout_edd_section',
		'type'            => 'select',
		'active_callback' => 'checkout_is_edd',
		'choices'         => checkout_edd_tags_select(),
	) );

}
add_action( 'customize_register', 'checkout_customizer_edd_register' );


/**
 * Add search bar to hooks
 *
 * @since 1.2.6
 */
function checkout_big_search() {
	if ( get_option( 'checkout_search_bar', 'disable' ) == 'enable' ) {
		get_template_part( 'partials/content-big-search' );
	}
}
add_action( 'checkout_homepage_above_download', 'checkout_big_search' );
add_action( 'checkout_download_template_above_download', 'checkout_big_search' );
add_action( 'checkout_index_above_posts', 'checkout_big_search' );


/**
 * Add search bar to hooks
 *
 * @since 1.2.6
 */
function checkout_demo_link() {
	global $post;

	if ( get_post_meta( $post->ID, 'array-demo', true ) ) {

		// Set the demo link text
		$demo_text = __( 'Demo', 'checkout' );
		?>

		<a class="edd-demo-link button" target="_blank" href="<?php echo esc_url( get_post_meta( $post->ID, 'array-demo', true ) ); ?>"><?php echo apply_filters( 'checkout_demo_text', $demo_text ); ?></a>
	<?php }
}
add_action( 'checkout_below_purchase_button', 'checkout_demo_link', 1, 1 );


/**
 * Add a vendor archive template to vendor archive page
 *
 * @since 1.5.4
 */
function checkout_vendor_template_include( $template ) {

	if ( ! function_exists( 'EDD_FES' ) ) {
		return $template;
	}

	$vendor_page = EDD_FES()->helper->get_option( 'fes-vendor-page', false );

	if ( ! is_page( $vendor_page ) ) {
		return $template;
	}

	$vendor = get_query_var( 'vendor' );

	if ( empty( $vendor ) ) {
		return locate_template( 'templates/template-vendor-archive.php' );
	}

	return $template;
}
add_filter( 'template_include', 'checkout_vendor_template_include' );


/**
 * Customize the checkout image size for recommended products
 *
 * @since 1.9
 */
 function checkout_filter_edd_checkout_image_size( $array ) {
     return array( 100, 100 );
 }
 add_filter( 'edd_checkout_image_size', 'checkout_filter_edd_checkout_image_size', 10, 1 );
