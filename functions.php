<?php
/**
 * Checkout functions
 *
 * @package Checkout
 *
 * @since Checkout 1.0
 */

/**
 * Set the content width
 *
 * @since checkout 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 770; /* pixels */
}


if ( ! function_exists( 'checkout_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since Checkout 1.0
 */
function checkout_setup() {

	/**
	 * Load Getting Started page and initialize theme update class
	 */
	require_once( get_template_directory() . '/inc/admin/updater/theme-updater.php' );

	/**
	 * TGM activation class
	 */
	require_once( get_template_directory() . '/inc/admin/tgm/tgm-activation.php' );

	/**
	 * Load the Typekit class
	 */
	require_once( get_template_directory() . '/inc/typekit/typekit.php' );

	/**
	 * Load the Customizer settings
	 */
	require_once( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Load the Icon widget
	 */
	require_once( get_template_directory() . '/inc/widgets/icon-select/icon-text.php' );

	/**
	 * Load the Gravatar profile widget
	 */
	require_once( get_template_directory() . '/inc/widgets/gravatar-profile.php' );

	/**
	 * Easy Digital Downloads functions
	 */
	if( class_exists( 'Easy_Digital_Downloads' ) ) {
		require_once( get_template_directory() . '/inc/edd/edd.php' );
	}

	/*
	 * Add title output
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Featured Product Paging Thumb
	add_image_size( 'paging-thumb', 75, 75, true );

	// Testimonial Thumb
	add_image_size( 'testimonial-thumb', 65, 65, true );

	// Featured Product Image
	add_image_size( 'featured-post', 800, 800, true );

	// Portfolio Column Images
	add_image_size( 'portfolio-thumb', 600, 450, true );

	// Featured Images
	add_image_size( 'blog-image', 1000, 750, true );

	/**
	 * Add editor styles
	 */
	add_editor_style();

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Custom Header support
	 */
	$defaults = array(
		'default-image' => '',
		'flex-width'    => true,
		'width'         => 1400,
		'flex-height'   => true,
		'height'        => 600,
	);
	add_theme_support( 'custom-header', $defaults );

	/**
	 * Register Menus
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'checkout' ),
		'footer'  => __( 'Footer Menu', 'checkout' )
	) );

	/**
	 * Make theme available for translation
	 */
	load_theme_textdomain( 'checkout', get_template_directory() . '/languages' );

	/**
	 * Add excerpt to pages
	 */
	add_post_type_support( 'page', 'excerpt' );

	/**
	 * Gallery post format
	 */
	add_theme_support( 'post-formats', array( 'gallery' ) );
	add_post_type_support( 'download', 'post-formats' );

	/**
	 * Enable HTML5 markup
	 */
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery'
	) );

	/**
	 * Enable Portfolio support
	 */
	add_theme_support( 'array_themes_portfolio_support' );

	/**
	 * Enable video support
	 */
	add_theme_support( 'array_themes_video_support' );

	/**
	 * Enable Testimonial support
	 */
	add_theme_support( 'testimonials' );

}
endif;
add_action( 'after_setup_theme', 'checkout_setup' );


/**
 * Load Jetpack compatibility file
 */
require_once get_template_directory() . '/inc/jetpack.php';


/**
 * Redirect to Getting Started page on theme activation
 *
 * @since checkout 1.0
 */
function checkout_redirect_on_activation() {
	global $pagenow;

	if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {

		wp_redirect( admin_url( "themes.php?page=checkout-license" ) );

	}
}
add_action( 'admin_init', 'checkout_redirect_on_activation' );


/**
 * Enqueue Checkout's scripts and styles
 *
 * @since checkout 1.0
 */
function checkout_scripts() {

	/**
	 * Main stylesheet
	 */
	wp_enqueue_style( 'checkout-style', get_stylesheet_uri() );

	/**
	 * Enqueue Google fonts.
	 * Only load them when not using Typekit fonts.
	 */
	if( 1 == get_option( 'checkout_disable_typekit', '' ) || 'valid' != get_option( 'checkout_license_key_status', '' ) ) {
		wp_enqueue_style( 'checkout-fonts', checkout_fonts_url(), array(), null );
	}

	/**
	 * FontAwesome icon font
	 */
	wp_enqueue_style( 'checkout-fontawesome-css', get_template_directory_uri() . "/inc/fonts/fontawesome/css/font-awesome.css", array( 'checkout-style' ), '4.3.0' );

	/**
	 * Enqueue jQuery
	 */
	wp_enqueue_script( 'jquery' );

	/**
	 * Enqueue hoverIntent
	 */
	wp_enqueue_script( 'hoverIntent' );

	/**
	 * Enqueue Checkout's javascript
	 */
	wp_enqueue_script( 'checkout-custom-js', get_template_directory_uri() . '/js/checkout.js', array( 'jquery' ), '1.0', true );

	/**
	 * Enqueue masonry on testimonial pages
	 */
	if( post_type_exists( 'testimonial' ) && is_post_type_archive( 'testimonial' ) ||
			is_page_template( 'templates/template-testimonials.php' ) ||
			is_page_template( 'templates/template-homepage-widgets.php' ) ||
			is_page_template( 'templates/template-homepage-shop.php' ) ||
			is_page_template( 'templates/template-homepage-portfolio.php' ) ) {

			wp_enqueue_script( 'masonry' );

			// Only load masonry js on pages with testimonials
			wp_localize_script( 'checkout-custom-js', 'checkout_masonry_js_vars', array(
					'load_masonry' => 'true'
				)
			);
		} else {
			wp_localize_script( 'checkout-custom-js', 'checkout_masonry_js_vars', array(
					'load_masonry' => false
				)
			);
		}

	/**
	 * Enqueue matchHeight javascript
	 */
	wp_enqueue_script( 'checkout-match-height-js', get_template_directory_uri() . '/js/jquery.matchHeight.js', array(), '0.5.2', true );

	/**
	 * Enqueue ResponsiveSlides script
	 */
	wp_enqueue_script( 'checkout-slides-js', get_template_directory_uri() . '/js/responsiveslides.js', array( 'jquery' ), '1.54', true );

	/**
	 * Enqueue TouchSwipe
	 */
	wp_enqueue_script( 'checkout-swipe-js', get_template_directory_uri() . '/js/jquery.touchSwipe.js', array( 'jquery' ), '1.6.6', true );

	/**
	 * Enqueue FastClick
	 */
	wp_enqueue_script( 'checkout-fastclick-js', get_template_directory_uri() . '/js/fastclick.js', array( 'jquery' ), '1.06', true );

	/**
	 * Enqueue LightGallery script
	 */
	wp_enqueue_script( 'checkout-lightgallery-js', get_template_directory_uri() . '/js/light-gallery/js/lightGallery.js', array( 'jquery' ), '1.1.4', true );

	/**
	 * Enqueue LightGallery stylesheet
	 */
	wp_enqueue_style( 'checkout-venobox-css', get_template_directory_uri() . "/js/light-gallery/css/lightGallery.css", array( 'checkout-style' ), '1.1.4' );

	/**
	 * Enqueue FitVids
	 */
	wp_enqueue_script( 'checkout-fitvids-js', get_template_directory_uri() . '/js/jquery.fitvids.js', array(), '1.1', true );

	/**
	 * Enqueue jQuery Cookie
	 */
	if( class_exists( 'Easy_Digital_Downloads' ) && function_exists( 'EDD_FES' ) && is_page( EDD_FES()->helper->get_option( 'fes-vendor-dashboard-page', false ) ) ) {

		if( isset( $_GET['task'] ) && 'products' === $_GET['task'] ) {
			wp_enqueue_script( 'checkout-cookie-js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), '1.4.1', true );
		}
	}

	/**
	 * Enqueue comment reply script
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'checkout_scripts' );


/**
 * Return the Google font stylesheet URL
 *
 * @since checkout 1.0
 */
function checkout_fonts_url() {

	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Montserrat, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'checkout' );

	/* Translators: If there are characters in your language that are not
	 * supported by Arimo, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$arimo = _x( 'on', 'Arimo font: on or off', 'checkout' );

	if ( 'off' !== $montserrat || 'off' !== $arimo ) {
		$font_families = array();

		if ( 'off' !== $montserrat )
			$font_families[] = 'Montserrat:400,700';

		if ( 'off' !== $arimo )
			$font_families[] = 'Arimo:400,700,400italic,700italic';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue Google fonts style to admin for editor styles
 *
 * @since checkout 1.0
 */
function checkout_admin_fonts( $hook_suffix ) {
	wp_enqueue_style( 'checkout-fonts', checkout_fonts_url(), array(), null );

	// Load FontAwesome in the admin for the icon widget
	wp_enqueue_style( 'checkout-fontawesome-admin-css', get_template_directory_uri() . "/inc/fonts/fontawesome/css/font-awesome.css", array(), '4.3.0' );
}
add_action( 'admin_enqueue_scripts', 'checkout_admin_fonts' );


/**
 * Detect if there is javascript and add respective classes
 *
 * @since checkout 1.0
 */
function checkout_js_class () {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'checkout_js_class', 1 );


/**
 * Add large size attribute to Gallery format images
 */
function checkout_carousel_gallery( $out, $pairs, $atts ) {
	global $post;

	if ( has_post_format( 'gallery' ) ) {
		$out['size'] = 'large';
	}
	return $out;
}
add_filter( 'shortcode_atts_gallery', 'checkout_carousel_gallery', 10, 3 );


/**
 * Gets the gallery shortcode data from post content
 *
 * @since checkout 1.0
 */
function checkout_gallery_data() {
	global $post;
	$pattern = get_shortcode_regex();
	if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
		&& array_key_exists( 2, $matches )
		&& in_array( 'gallery', $matches[2] ) )
	{

		return $matches;
	}
}


/**
 * If the post has a carousel gallery, remove the first gallery from the post
 *
 * @since checkout 1.0
 */
function checkout_content_filter_gallery() {

	if( has_post_format( 'gallery' ) ) {

		$content = get_the_content();

		/**
		 * Get the gallery data from the post.
		 */
		$gallery_data = checkout_gallery_data();

		/**
		 * Grab the first shortcode in post content, strip it out, and
		 * display the post content without the first gallery.
		 */
		if( $gallery_data  && is_array( $gallery_data ) ) {
			$gallery = $gallery_data[0][0];
			$content = str_replace( $gallery, '', $content );
		}
		echo apply_filters( 'the_content', $content );

	} else {

		/**
		 * Display the normal post content on standard post format.
		 */
		the_content( __( 'Read More', 'checkout' ) );
	}
}


/**
 * Show full-width images on attachment pages
 *
 * @since checkout 1.0
 */
function checkout_prepend_attachment( $p ) {
	if ( is_attachment() ) {
		return '<p class="attachment">' . wp_get_attachment_link( 0, 'blog-image', false ) . '</p>';
	}
}
add_filter( 'prepend_attachment', 'checkout_prepend_attachment' );


/**
 * Custom excerpt length
 *
 * @since checkout 1.0
 */
function checkout_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'checkout_excerpt_length', 999 );


/**
 * Replace default excerpt [...]
 *
 * @since checkout 1.0
 */
function checkout_new_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'checkout_new_excerpt_more' );


 /**
 * Custom excerpt read more link on archives
 *
 * @since checkout 1.0
 */
function checkout_custom_excerpt( $text ) {
	global $post;

	if( is_archive() && ! is_post_type_archive() || is_search() ) {
		$excerpt = '<p>' . $text . '<p><a class="more-link" href="' . get_permalink() . '"> ' . __( 'Read More', 'checkout' ) . ' </a></p>';
		return $excerpt;
	} else {
		return $text;
	}
}
add_filter( 'the_excerpt', 'checkout_custom_excerpt' );


/**
 * Custom read more link
 *
 * @since checkout 1.0
 */
function checkout_modify_read_more_link() {
	return '<p><a class="more-link" href="' . get_permalink() . '"> ' . __( 'Read More', 'checkout' ) . ' </a></p>';
}
add_filter( 'the_content_more_link', 'checkout_modify_read_more_link' );


/**
 * Search drop menu
 *
 * @since checkout 1.0
 */
function checkout_menu_search ( $items, $args ) {
    if ( get_theme_mod( 'checkout_show_search', 1 ) && $args->theme_location == 'primary') {
        $items .= '<li class="menu-item menu-item-has-children header-search"><a href="#"><i class="fa fa-search"></i></a><ul class="sub-menu"><li class="drop-search"> ' . get_search_form( false ) . ' </li></ul></li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'checkout_menu_search', 10, 2 );


/**
 * Checks the gallery 'link' attribute and sets a filter if it's set to 'file'.
 *
 * @since checkout 1.0
 */
function checkout_get_gallery_type( $out, $pairs, $atts ) {
	if( isset( $atts['link'] ) && 'file' == $atts['link'] ) {
		add_filter( 'gallery_style', 'checkout_gallery_style' );
	}
	return $out;
}
add_filter( 'shortcode_atts_gallery', 'checkout_get_gallery_type', 10, 3 );


/**
 * Adds a data-link="file" attribute to the wrapper div output
 * by the [gallery] shortcode if the gallery has 'link="file"' set.
 *
 * @since checkout 1.0
 */
function checkout_gallery_style( $gallery_div ) {
	remove_filter( 'gallery_style', 'checkout_gallery_style' );
	$gallery_div = str_replace( '<div id=', '<div data-link="file" id=', $gallery_div );
	return $gallery_div;
}


/**
 * Removes the built-in styles in the Subtitles plugin.
 *
 * @since checkout 1.0
 */
function checkout_remove_subtitles_styles() {
	if ( class_exists( 'Subtitles' ) &&  method_exists( 'Subtitles', 'subtitle_styling' ) ) {
		remove_action( 'wp_head', array( Subtitles::getInstance(), 'subtitle_styling' ) );
	}
}
add_action( 'template_redirect', 'checkout_remove_subtitles_styles' );


/**
 * Add subtitle support to downloads and portfolio items
 *
 * @since checkout 1.0
 */
function checkout_add_subtitles_support() {
    add_post_type_support( 'download', 'subtitles' );
    add_post_type_support( 'array-portfolio', 'subtitles' );
    add_post_type_support( 'testimonial', 'subtitles' );
}
add_action( 'init', 'checkout_add_subtitles_support' );


/**
 * Remove automatic output of subtitles
 *
 * @since checkout 1.0
 */
function checkout_subtitles_mod_supported_views() {
	// Subtitle output handled by theme (templates/template-titles.php)
}
add_filter( 'subtitle_view_supported', 'checkout_subtitles_mod_supported_views' );


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function checkout_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'checkout' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'checkout_wp_title', 10, 2 );


if ( ! function_exists( 'checkout_post_navs' ) ) :
/**
 * Displays next/previous post navigations
 *
 * @since checkout 1.0
 */
function checkout_post_navs() {
	$nav_args = array(
	    'prev_text'          => '<div class="nav-text"><span><i class="fa fa-angle-left"></i> ' . __( 'Previous', 'checkout' ) . '</span> <h4>%title</h4></div>',
	    'next_text'          => '<div class="nav-text"><span>' . __( 'Next', 'checkout' ) . ' <i class="fa fa-angle-right"></i></span> <h4>%title</h4></div>',
	    'screen_reader_text' => __( 'Post navigation', 'checkout' ),
	);

	echo get_the_post_navigation( $nav_args );
}
endif;


if ( ! function_exists( 'checkout_page_navs' ) ) :
/**
 * Displays post pagination links
 *
 * @since checkout 1.0
 */
function checkout_page_navs( $query = false ) {

	global $wp_query;
	if( $query ) {
		$temp_query = $wp_query;
		$wp_query = $query;
	}

	// Return early if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	} ?>
	<div class="page-navigation">
		<?php
			$big = 999999999; // need an unlikely integer

			echo paginate_links( array(
				'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'  => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total'   => $wp_query->max_num_pages
			) );
		?>
	</div>
	<?php
	if( isset( $temp_query ) ) {
		$wp_query = $temp_query;
	}
}
endif;

/**
 * Removes certain page templates from the page template
 * dropdown if certain plugins aren't activated.
 *
 * @since checkout 1.0
 */
function checkout_filter_page_templates( $page_templates ) {

	if( ! function_exists( 'EDD' ) ) :
		unset( $page_templates['templates/template-downloads.php'] );
		unset( $page_templates['templates/template-homepage-shop.php'] );
		unset( $page_templates['templates/template-pricing.php'] );
	endif;

	if( ! class_exists( 'EDD_Front_End_Submissions' ) ) :
		unset( $page_templates['templates/template-vendor.php'] );
	endif;

	if ( ! class_exists( 'Array_Toolkit' ) ) :
		unset( $page_templates['templates/template-testimonials.php'] );
	endif;

	return $page_templates;
}
add_filter( 'theme_page_templates', 'checkout_filter_page_templates' );


/**
 * Custom comment output
 *
 * @since checkout 1.0
 */
function checkout_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

		<div class="comment-block" id="comment-<?php comment_ID(); ?>">

			<div class="comment-info">
				<div class="comment-author vcard">
					<div class="vcard-wrap">
						<?php echo get_avatar( $comment->comment_author_email, 100 ); ?>
					</div>
				</div>

				<div class="comment-text">
					<div class="comment-meta commentmetadata">
						<?php printf( __( '<cite class="fn">%s</cite>', 'checkout' ), get_comment_author_link() ); ?>

						<div class="comment-time">
							<?php
								printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'checkout' ), get_comment_date(), get_comment_time() )
								);
							?>
							<?php edit_comment_link( '<i class="icon-edit"></i>', '' ); ?>
						</div>
					</div>
					<?php comment_text(); ?>

					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>

			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'checkout' ); ?></em>
			<?php endif; ?>
		</div>
<?php
}


/**
 * Add comment title to comments template
 *
 * @since checkout 1.0
 */
function checkout_comments_section_title() {
	echo "<h3 id='comments-title'>";
	printf( _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'checkout' ), number_format_i18n( get_comments_number() ) );
	echo "</h3>";
}
add_filter( 'checkout_comments_title', 'checkout_comments_section_title', 5 );


/**
 * Register widget areas
 *
 * @since checkout 1.0
 */
function checkout_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'checkout' ),
		'id'            => 'footer',
		'description'   => __( 'Widgets added here will appear in your site&apos;s footer section.', 'checkout' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage Text Column Widgets', 'checkout' ),
		'id'            => 'homepage-text',
		'description'   => __( 'Widgets added here will be shown at the top of the Homepage Widgets page template.', 'checkout' ),
		'before_widget' => '<aside id="%1$s" class="widget column equal %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Team Page Widgets', 'checkout' ),
		'id'            => 'team',
		'description'   => __( 'Widgets added here will appear on the Team page template. Use the Gravatar Profile widget to add team member profiles.', 'checkout' ),
		'before_widget' => '<aside id="%1$s" class="widget column equal %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if( class_exists( 'Easy_Digital_Downloads' ) ) {

		register_sidebar( array(
			'name'          => __( 'Pricing Page Widget', 'checkout' ),
			'id'            => 'pricing',
			'description'   => __( 'Widgets added here will appear on the Pricing page template. Add the Checkout Pricing Table widget here to display a price table.', 'checkout' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

	}

	register_sidebar( array(
		'name'          => __( 'Sidebar Widgets', 'checkout' ),
		'id'            => 'sidebar-widgets',
		'description'   => __( 'Widgets added here will appear on the sidebar of posts and pages.', 'checkout' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'checkout_widgets_init' );
