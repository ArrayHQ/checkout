<?php
/**
 * Theme options via the Customizer.
 *
 * @package Checkout
 * @since Checkout 1.0
 */


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function checkout_customize_preview_js() {
	wp_enqueue_script( 'checkout_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '201511', true );
}
add_action( 'customize_preview_init', 'checkout_customize_preview_js' );


/**
 * Sanitize header background select option
 */
function checkout_sanitize_background_select( $header_bg ) {

	if( ! in_array( $header_bg, array( 'enable', 'disable' ) ) ) {
		$header_bg = 'enable';
	}
	return $header_bg;
}


/**
 * Sanitize search select option
 */
function checkout_sanitize_search_select( $search_select ) {

	if( ! in_array( $search_select, array( 'enable', 'disable' ) ) ) {
		$search_select = 'enable';
	}
	return $search_select;
}


/**
 * Sanitize text input
 */
function checkout_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Sanitize page drop down
 */
function checkout_sanitize_integer( $input ) {
	if( is_numeric( $input ) ) {
		return intval( $input );
	}
}


/**
 * Sanitize opacity decimal
 */
function checkout_sanitize_decimal( $input ) {
	filter_var( $input, FILTER_FLAG_ALLOW_FRACTION );
	return ( $input );
}


/**
 * Sanitize checkbox
 */
function checkout_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}


/**
 * EDD callback
 */
function checkout_is_edd() {
	if ( class_exists( 'Easy_Digital_Downloads' ) )
		return true;
}


/**
 * Array Toolkit callback
 */
function checkout_is_toolkit() {
	if ( class_exists( 'Array_Toolkit' ) )
		return true;
}


/**
 * Homepage widget conditional callback
 */
function checkout_is_widget_page() {
	if( is_page_template( 'templates/template-homepage-widgets.php' ) ) {
		return is_page_template( 'templates/template-homepage-widgets.php' );
	} else {
		return false;
	}
}


/**
 * Homepage portfolio conditional callback
 */
function checkout_is_portfolio_page() {
	if ( is_page_template( 'templates/template-homepage-portfolio.php' ) && class_exists( 'Array_Toolkit' ) ) {
		return is_page_template( 'templates/template-homepage-portfolio.php' );
	} else {
		return false;
	}
}


/**
 * Homepage download conditional callback
 */
function checkout_is_download_page() {
	if ( is_page_template( 'templates/template-homepage-shop.php' ) && class_exists( 'Easy_Digital_Downloads' ) ) {
		return is_page_template( 'templates/template-homepage-shop.php' );
	} else {
		return false;
	}
}


/**
 * EDD tags dropdown
 */
function checkout_edd_tags_select() {

	$results = array(
		'' => esc_html__( 'None', 'checkout' )
	);

	$edd_tags = get_terms( 'download_tag', array( 'hide_empty' => false ) );

	if ( class_exists( 'Easy_Digital_Downloads' ) && $edd_tags ) {
		foreach( $edd_tags as $key => $value ) {
			$results[$value->slug] = $value->name;
		}
	}
	return $results;
}


/**
 * Sanitizes the EDD tag select
 */
function checkout_sanitize_edd_tag( $input ) {
	$args = array(
		'hide_empty' => false,
		'slug'       => $input
	);
	$valid = get_terms( 'download_tag', $args );

	if( ! empty( $valid ) ) {
		return $input;
	} else {
		return '';
	}
}


/**
 * Portfolio tags dropdown
 */
function checkout_portfolio_tag_select() {

	if ( class_exists( 'Array_Toolkit' ) ) {
		$results = array(
			'' => esc_html__( 'None', 'checkout' )
		);

		$portfolio_tag = get_terms( 'portfolio_tag', array( 'hide_empty' => false ) );

		if( $portfolio_tag ) {
			foreach( $portfolio_tag as $key => $value ) {
				$results[$value->slug] = $value->name;
			}
		}
		return $results;
	}
}


/**
 * Sanitizes the portfolio tag select
 */
function checkout_sanitize_portfolio_tag( $input ) {
	$args = array(
		'hide_empty' => false,
		'slug'       => $input
	);
	$valid = get_terms( 'portfolio_tag', $args );

	if( ! empty( $valid ) ) {
		return $input;
	} else {
		return '';
	}
}


/**
 * Post tags dropdown
 */
function checkout_post_tag_select() {

	$results = array(
		'' => esc_html__( 'None', 'checkout' )
	);

	$post_tags = get_tags( array( 'hide_empty' => false ) );

	if ( $post_tags ) {
		foreach( $post_tags as $key => $value ) {
			$results[$value->slug] = $value->name;
		}
	}
	return $results;
}


/**
 * Sanitizes the portfolio tag select
 */
function checkout_sanitize_post_tag( $input ) {
	$args = array(
		'hide_empty' => false,
		'slug'       => $input
	);
	$valid = get_tags( $args );
	if( ! empty( $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Sanitize CSS output
 */
function checkout_sanitize_css( $text ) {
    return esc_textarea( $text );
}


/**
 * CTA button callback
 */
function checkout_show_cta_button_options( $control ) {
	$option = $control->manager->get_setting( 'checkout_footer_cta_button' );

	return $option->value();
}


/**
 * @param WP_Customize_Manager $wp_customize
 */
function checkout_customizer_register( $wp_customize ) {


	/**
	 * Logo Image
	 */
	$wp_customize->add_setting( 'checkout_logo', array(
		'sanitize_callback' => 'checkout_sanitize_text',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'checkout_logo', array(
		'label'    => esc_html__( 'Logo Upload', 'checkout' ),
		'section'  => 'title_tagline',
		'settings' => 'checkout_logo',
		'priority' => 10
	) ) );

	/**
	 * Homepage Header Button Text
	 */
	$wp_customize->add_setting( 'checkout_footer_text', array(
		'default'           => esc_html__( '', 'checkout' ),
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_footer_text', array(
		'label'             => esc_html__( 'Footer Copyright Text', 'checkout' ),
		'section'           => 'title_tagline',
		'settings'          => 'checkout_footer_text',
		'type'              => 'text',
		'sanitize_callback' => 'checkout_sanitize_text',
		'priority'          => 10
	) );


	/**
	 * Show search in menu
	 */
	$wp_customize->add_setting( 'checkout_show_search', array(
		'default'           => 1,
		'sanitize_callback' => 'checkout_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'checkout_show_search', array(
		'type'     => 'checkbox',
		'label'    => esc_html__( 'Add search menu icon to header.', 'checkout' ),
		'section'  => 'checkout_theme_options_section',
		'priority' => 10
	) );


	/**
	 * Color Options
	 */
	$wp_customize->add_setting( 'checkout_accent_color', array(
		'default'           => '#37BF91',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_accent_color', array(
		'label'     => esc_html__( 'Accent Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_accent_color',
		'priority'  => 2,
	) ) );


	/**
	 * Header and Footer Background Color
	 */
	$wp_customize->add_setting( 'checkout_header_color', array(
		'default'           => '#282E34',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_header_color', array(
		'label'     => esc_html__( 'Header & Footer Background Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_header_color',
		'priority'  => 4,

	) ) );


	/**
	 * Header Title Color
	 */
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->add_setting( 'checkout_title_color', array(
		'default'           => '#FFFFFF',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_title_color', array(
		'label'     => esc_html__( 'Header Title Text Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_title_color',
		'priority'  => 6,
	) ) );


	/**
	 * Header Subtitle Color
	 */
	$wp_customize->add_setting( 'checkout_subtitle_color', array(
		'default'           => '#A2ABB3',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_subtitle_color', array(
		'label'     => esc_html__( 'Header Subtitle Text Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_subtitle_color',
		'priority'  => 8,
	) ) );


	/**
	 * Header Navigation Link Color
	 */
	$wp_customize->add_setting( 'checkout_nav_color', array(
		'default'           => '#b5bdc3',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_nav_color', array(
		'label'     => esc_html__( 'Header Navigation Link Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_nav_color',
		'priority'  => 10,
	) ) );


	/**
	 * Footer Navigation Link Color
	 */
	$wp_customize->add_setting( 'checkout_footer_nav_color', array(
		'default'           => '#b5bdc3',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_footer_nav_color', array(
		'label'     => esc_html__( 'Footer Text and Link Color', 'checkout' ),
		'section'   => 'colors',
		'settings'  => 'checkout_footer_nav_color',
		'priority'  => 12,
	) ) );


	/**
	 * Custom CSS Output
	 */
	$wp_customize->add_setting( 'checkout_customizer_css',
		array(
			'default'              => '',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'checkout_sanitize_css',
			'sanitize_js_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$wp_customize->add_control( 'checkout_customizer_css_control', array(
			'label'     => esc_html__( 'Custom CSS', 'checkout' ),
			'section'   => 'colors',
			'settings'  => 'checkout_customizer_css',
			'type'      => 'textarea',
			'priority'  => 14
		)
	);


	/**
	 * Header and Footer Image Section
	 */
	$wp_customize->add_section( 'header_image', array(
		'title'          => esc_html__( 'Header &amp; Footer Image', 'checkout' ),
		'theme_supports' => 'custom-header',
		'priority'       => 60,
	) );

	$wp_customize->get_setting( 'header_image' )->transport = 'postMessage';

	/**
	 * Header Background Opacity Range
	 */
	$wp_customize->add_setting( 'checkout_bg_opacity', array(
		'default'           => '.1',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'checkout_sanitize_decimal',
	) );

	$wp_customize->add_control( 'checkout_bg_opacity', array(
		'type'        => 'range',
		'priority'    => 10,
		'section'     => 'header_image',
		'label'       => esc_html__( 'Header Image Opacity', 'checkout' ),
		'description' => 'Change the opacity of your header image.',
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 1,
			'step'  => .1,
			'style' => 'width: 100%',
		),
	) );


	/**
	 * Footer Background Image
	 */
	$wp_customize->add_setting( 'checkout_footer_background_image', array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'checkout_sanitize_text',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'checkout_footer_background_image', array(
		'label'    => esc_html__( 'Footer Background Image', 'checkout' ),
		'section'  => 'header_image',
		'settings' => 'checkout_footer_background_image',
		'priority' => 10,
	) ) );


	/**
	 * Footer Background Opacity Range
	 */
	$wp_customize->add_setting( 'checkout_footer_bg_opacity', array(
		'default'           => '.1',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'checkout_sanitize_decimal',
	) );

	$wp_customize->add_control( 'checkout_footer_bg_opacity', array(
		'type'        => 'range',
		'priority'    => 10,
		'section'     => 'header_image',
		'label'       => esc_html__( 'Footer Background Image Opacity', 'checkout' ),
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 1,
			'step'  => .1,
			'style' => 'width: 100%',
		),
	) );


	/**
	 * Theme Options section
	 */
	$wp_customize->add_section( 'checkout_theme_options_section', array(
		'title'           => esc_html__( 'Theme Options', 'checkout' ),
		'priority'        => 1,
	) );

	/**
	 * Search bar
	 */
	$wp_customize->add_setting( 'checkout_search_bar', array(
		'default'           => 'disable',
		'capability'        => 'edit_theme_options',
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_search_select',
	));
	$wp_customize->add_control( 'checkout_search_bar_select', array(
		'settings'        => 'checkout_search_bar',
		'label'           => esc_html__( 'Enable search bar on product pages', 'checkout' ),
		'section'         => 'checkout_theme_options_section',
		'type'            => 'select',
		'active_callback' => 'checkout_is_edd',
		'priority'        => 1,
		'choices'    => array(
			'enabled'  => esc_html__( 'Enable', 'checkout' ),
			'disable'  => esc_html__( 'Disable', 'checkout' ),
		),
	));


	/**
	 * Homepage Template Options
	 */
	$wp_customize->add_panel( 'checkout_home_options', array(
		'priority'       => 2,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => esc_html__( 'Homepage Settings', 'checkout' ),
		'description'    => esc_html__( 'Change the options for this theme.', 'checkout' ),
	) );


	/**
	 * Homepage Header Section
	 */
	$wp_customize->add_section( 'checkout_header_section', array(
		'title'           => esc_html__( 'Header Section', 'checkout' ),
		'priority'        => 2,
		'description'     => esc_html__( 'Add button links to your homepage header section.', 'checkout' ),
		'panel'           => 'checkout_home_options',
	) );


	/**
	 * Homepage Header Button Link
	 */
	$wp_customize->add_setting( 'checkout_header_button_one_link', array(
		'default'           => '',
		'sanitize_callback' => 'checkout_sanitize_integer',
	) );

	$wp_customize->add_control( 'checkout_header_button_one_link', array(
		'type'     => 'dropdown-pages',
		'label'    => 	esc_html__( 'Button One Link', 'checkout' ),
		'settings' => 'checkout_header_button_one_link',
		'section'  => 'checkout_header_section',
		'priority' => 8
	) );


	/**
	 * Homepage Header Button Text
	 */
	$wp_customize->add_setting( 'checkout_header_button_one_text', array(
		'default'           => esc_html__( 'Read More', 'checkout' ),
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_header_button_one_text', array(
		'label'           => esc_html__( 'Button One Text', 'checkout' ),
		'section'         => 'checkout_header_section',
		'settings'        => 'checkout_header_button_one_text',
		'type'            => 'text',
		'priority'        => 10
	) );


	/**
	 * Homepage Header Button Color
	 */
	$wp_customize->add_setting( 'checkout_header_button_one_color', array(
		'default'           => '#37BF91',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_header_button_one_color', array(
		'label'           => esc_html__( 'Button One Color', 'checkout' ),
		'section'         => 'checkout_header_section',
		'settings'        => 'checkout_header_button_one_color',
		'priority'        => 11,
	) ) );


	/**
	 * Homepage Header Button Two Link
	 */
	$wp_customize->add_setting( 'checkout_header_button_two_link', array(
		'default'           => '',
		'sanitize_callback' => 'checkout_sanitize_integer',
	) );

	$wp_customize->add_control( 'checkout_header_button_two_link', array(
		'type'     => 'dropdown-pages',
		'label'    => 	esc_html__( 'Button Two Link', 'checkout' ),
		'settings' => 'checkout_header_button_two_link',
		'section'  => 'checkout_header_section',
		'priority' => 12
	) );


	/**
	 * Homepage Header Button Two Text
	 */
	$wp_customize->add_setting( 'checkout_header_button_two_text', array(
		'default'           => esc_html__( 'Read More', 'checkout' ),
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_header_button_two_text', array(
		'label'           => esc_html__( 'Button Two Text', 'checkout' ),
		'section'         => 'checkout_header_section',
		'settings'        => 'checkout_header_button_two_text',
		'type'            => 'text',
		'priority'        => 14
	) );


	/**
	 * Homepage Header Button Two Color
	 */
	$wp_customize->add_setting( 'checkout_header_button_two_color', array(
		'default'           => '#37BF91',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'checkout_header_button_two_color', array(
		'label'           => esc_html__( 'Button Two Color', 'checkout' ),
		'section'         => 'checkout_header_section',
		'settings'        => 'checkout_header_button_two_color',
		'priority'        => 15,
	) ) );


	/**
	 * Homepage Testimonial Section
	 *
	 * Check for the Array Toolkit and show install link if not installed
	 */
	if ( class_exists( 'Array_Toolkit' ) ) {
		$test_desc = null;
	} else {

		if( is_multisite() ) {
			$toolkitUrl = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=array-toolkit&TB_iframe=true&width=640&height=589' );
		} else {
			$toolkitUrl = admin_url( 'plugin-install.php?tab=plugin-information&plugin=array-toolkit&TB_iframe=true&width=640&height=589' );
		}

		$test_desc = esc_html__( 'To enable the testimonials section, you must first install the Array Toolkit plugin.', 'checkout' );
	}

	$wp_customize->add_section( 'checkout_testimonial_section', array(
		'title'           => esc_html__( 'Testimonial Section', 'checkout' ),
		'priority'        => 3,
		'description'     => $test_desc,
		'panel'           => 'checkout_home_options',
	) );


	/**
	 * Testimonial Section Title
	 */
	$wp_customize->add_setting( 'checkout_testimonial_title', array(
		'default'           => '',
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_testimonial_title', array(
		'label'           => esc_html__( 'Testimonial Section Title', 'checkout' ),
		'section'         => 'checkout_testimonial_section',
		'panel'           => 'checkout_home_options',
		'settings'        => 'checkout_testimonial_title',
		'type'            => 'text',
		'priority'        => 2,
		'active_callback' => 'checkout_is_toolkit'
	) );


	/**
	 * Testimonial Count
	 */
	$wp_customize->add_setting( 'checkout_testimonial_count', array(
		'default'           => '2',
		'capability'        => 'edit_theme_options',
		'type'              => 'option',
		'sanitize_callback' => 'absint',

	));
	$wp_customize->add_control( 'checkout_testimonial_count_select', array(
		'settings'        => 'checkout_testimonial_count',
		'label'           => esc_html__( 'Number of testimonials to show:', 'checkout' ),
		'section'         => 'checkout_testimonial_section',
		'type'            => 'select',
		'active_callback' => 'checkout_is_toolkit',
		'choices'    => array(
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
		),
	));


	/**
	 * Footer CTA Section
	 */
	$wp_customize->add_section( 'checkout_footer_cta_section', array(
		'title'           => esc_html__( 'Call-To-Action Section', 'checkout' ),
		'priority'        => 10,
		'description'     => esc_html__( 'Add a call-to-action banner with text and a button to the footer of your homepage.', 'checkout' ),
		'panel'           => 'checkout_home_options',
	) );


	/**
	 * Footer CTA Section
	 */
	$wp_customize->add_setting( 'checkout_footer_cta_title', array(
		'default'           => '',
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_footer_cta_title', array(
		'label'           => esc_html__( 'Banner Title', 'checkout' ),
		'section'         => 'checkout_footer_cta_section',
		'panel'           => 'checkout_home_options',
		'settings'        => 'checkout_footer_cta_title',
		'type'            => 'text',
		'priority'        => 4,
	) );


	/**
	 * Footer CTA Text
	 */
	$wp_customize->add_setting( 'checkout_footer_cta_subtitle', array(
		'default'           => '',
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_footer_cta_subtitle', array(
		'label'           => esc_html__( 'Banner Subtitle', 'checkout' ),
		'section'         => 'checkout_footer_cta_section',
		'panel'           => 'checkout_home_options',
		'settings'        => 'checkout_footer_cta_subtitle',
		'type'            => 'textarea',
		'priority'        => 6,
	) );


	/**
	 * Footer CTA Button Link Select
	 */
	$wp_customize->add_setting( 'checkout_footer_cta_button', array(
		'default'           => '',
		'sanitize_callback' => 'checkout_sanitize_integer',
	) );

	$wp_customize->add_control( 'checkout_footer_cta_button', array(
		'type'            => 'dropdown-pages',
		'label'           => 	esc_html__( 'Banner Button Link', 'checkout' ),
		'settings'        => 'checkout_footer_cta_button',
		'section'         => 'checkout_footer_cta_section',
		'panel'           => 'checkout_home_options',
		'priority'        => 8,
	) );


	/**
	 * Footer CTA Button Text
	 */
	$wp_customize->add_setting( 'checkout_footer_cta_button_text', array(
		'default'           => esc_html__( 'Read More', 'checkout' ),
		'type'              => 'option',
		'sanitize_callback' => 'checkout_sanitize_text',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'checkout_footer_cta_button_text', array(
		'label'           => esc_html__( 'Banner Button Text', 'checkout' ),
		'section'         => 'checkout_footer_cta_section',
		'panel'           => 'checkout_home_options',
		'settings'        => 'checkout_footer_cta_button_text',
		'active_callback' => 'checkout_show_cta_button_options',
		'type'            => 'text',
		'priority'        => 10,
	) );


	/**
	 * Portfolio Homepage Template Options
	 */
	$wp_customize->add_section( 'checkout_portfolio_section', array(
		'title'           => esc_html__( 'Portfolio Section', 'checkout' ),
		'priority'        =>  2,
		'panel'           => 'checkout_home_options',
		'active_callback' => 'checkout_is_portfolio_page',
		'description'     => esc_html__( 'Select the number of portfolio items to show and select a tag to display featured portfolio items.', 'checkout' ),
	) );


	/**
	 * Number of portfolio items to show on homepage
	 */
	$wp_customize->add_setting( 'checkout_homepage_portfolio_count', array(
		'default'           => '6',
		'capability'        => 'edit_theme_options',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control( 'checkout_homepage_portfolio_count_select', array(
		'settings' => 'checkout_homepage_portfolio_count',
		'label'    => esc_html__( 'Number of portfolio items to show:', 'checkout' ),
		'section'  => 'checkout_portfolio_section',
		'type'     => 'select',
		'choices'  => array(
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
	));


	/**
	 * Portfolio Featured Tag Select
	 */
	$wp_customize->add_setting( 'checkout_portfolio_tag_select', array(
		'default'           => '',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'checkout_sanitize_portfolio_tag',
	) );

	$wp_customize->add_control( 'checkout_portfolio_tag_select', array(
		'settings' => 'checkout_portfolio_tag_select',
		'label'    => esc_html__( 'Featured Portfolio Item Tag', 'checkout' ),
		'section'  => 'checkout_portfolio_section',
		'type'     => 'select',
		'choices'  => checkout_portfolio_tag_select(),
	) );


	/**
	 * Widget Homepage Template Options
	 */
	$wp_customize->add_section( 'checkout_widget_section', array(
		'title'           => esc_html__( 'Featured Post Slider', 'checkout' ),
		'priority'        =>  2,
		'panel'           => 'checkout_home_options',
		'active_callback' => 'checkout_is_widget_page',
		'description'     => esc_html__( 'Choose a post tag to feature in the slider on the homepage.', 'checkout' ),
	) );


	/**
	 * Featured Post Tag Select
	 */
	$wp_customize->add_setting( 'checkout_post_tag_select', array(
		'default'           => '',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'checkout_sanitize_post_tag',
	) );

	$wp_customize->add_control( 'checkout_post_tag_select', array(
		'settings' => 'checkout_post_tag_select',
		'label'    => esc_html__( 'Featured Post Tag', 'checkout' ),
		'section'  => 'checkout_widget_section',
		'type'     => 'select',
		'choices'  => checkout_post_tag_select(),
	) );

}
add_action( 'customize_register', 'checkout_customizer_register' );


/**
 * Output custom color options from Customizer
 */
function checkout_custom_colors() {
	?>
	<style type="text/css">
		<?php if ( get_theme_mod( 'checkout_accent_color' ) ) { ?>
			/* Link color */
			a,
			#comments .bypostauthor .fn:before {
				color: <?php echo get_theme_mod( 'checkout_accent_color', '#37BF91' ); ?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_title_color' ) ) { ?>
		/* Header title color */
		.hero-title h1 {
			color: <?php echo get_theme_mod( 'checkout_title_color', '#FFFFFF' ); ?>;
		}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_subtitle_color' ) ) { ?>
			/* Header subtitle color */
			.hero-title h3,
			.hero-title p {
				color: <?php echo get_theme_mod( 'checkout_subtitle_color', '#A2ABB3' ); ?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_nav_color' ) ) { ?>
			/* Main navigation link color */
			.main-navigation a,
			.site-description {
				color: <?php echo get_theme_mod( 'checkout_nav_color', '#b5bdc3' ); ?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_footer_nav_color' ) ) { ?>
			/* Footer navigation link color */
			.site-footer a,
			.site-footer .copyright a,
			.site-footer,
			.widget .menu li a:before {
				color: <?php echo get_theme_mod( 'checkout_footer_nav_color', '#b5bdc3' ); ?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_accent_color' ) ) { ?>
			/* EDD related colors */
			#content .fes-vendor-menu ul li.active,
			#content .fes-vendor-menu ul li:hover {
				border-top-color: <?php echo get_theme_mod( 'checkout_accent_color', '#37BF91' ); ?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_accent_color' ) ) { ?>
			/* Background color for various elements throughout the theme */
			input[type="submit"],
			.button,
			.post-content .button,
			.cta-button,
			#content .contact-form input[type="submit"],
			#commentform #submit,
			.portfolio-wrapper .rslides_nav:hover,
			.quantities-enabled .single-quantity-mode:not(.free-download) [id^="edd_purchase"] .edd_purchase_submit_wrapper,
			[id^="edd_purchase"] .edd-add-to-cart:not(.download-meta-purchase),
			#mailbag_mailchimp .mailbag-input .button,
			input[type=checkbox]:checked,
			input[type=radio]:checked,
			#content input[type=submit].edd-submit,
			.download-meta .edd_go_to_checkout,
			.page-numbers.current,
			.page-numbers:hover,
			.rslides_nav:hover,
			.main-navigation .edd_checkout,
			#searchform #searchsubmit,
			.edd-cart-added-alert,
			.quantities-enabled [id^="edd_purchase"] .edd_purchase_submit_wrapper,
			.post-password-form input[type="submit"] {
				background: <?php echo get_theme_mod( 'checkout_accent_color', '#37BF91' ) ;?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_header_color' ) ) { ?>
			/* Background color for the header and footer */
			.site-header, .site-footer {
				background-color: <?php echo get_theme_mod( 'checkout_header_color', '#2B3136' ) ;?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_header_button_one_color' ) ) { ?>
			/* Background color for the first button in the header */
			.button-one {
				background: <?php echo get_theme_mod( 'checkout_header_button_one_color', '#37BF91' ) ;?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_header_button_two_color' ) ) { ?>
			/* Background color for the second button in the header */
			.button-two {
				background: <?php echo get_theme_mod( 'checkout_header_button_two_color', '#37BF91' ) ;?>;
			}
		<?php } ?>

		<?php if ( get_theme_mod( 'checkout_customizer_css' ) ) { ?>
			/* Outputs the custom CSS */
			<?php echo get_theme_mod( 'checkout_customizer_css', '' ); ?>
		<?php } ?>
	</style>
<?php
}
add_action( 'wp_head', 'checkout_custom_colors' );
