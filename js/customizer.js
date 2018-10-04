/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).text( to );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );


	// Header background image
	wp.customize( 'header_image', function( value ) {
		value.bind( function( to ) {
			$( ".site-header-bg" ).css( 'background-image', 'url(' + to + ')' );
		} );
	} );


	// Footer background image
	wp.customize( 'checkout_footer_background_image', function( value ) {
		value.bind( function( to ) {
			$( ".site-footer-bg" ).css( 'background-image', 'url(' + to + ')' );
		} );
	} );


	// Header background opacity
	wp.customize( 'checkout_bg_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.site-header .background-effect' ).css( 'opacity', to );
		} );
	} );


	// Footer background opacity
	wp.customize( 'checkout_footer_bg_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer .background-effect' ).css( 'opacity', to );
		} );
	} );


	// Accent colors
	wp.customize( 'checkout_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.button, .post-content .button, .cta-button, #content .contact-form input[type="submit"], #commentform #submit, .portfolio-wrapper .rslides_nav:hover, .quantities-enabled .single-quantity-mode:not(.free-download) [id^="edd_purchase"] .edd_purchase_submit_wrapper, [id^="edd_purchase"] .edd-add-to-cart:not(.download-meta-purchase), #mailbag_mailchimp .mailbag-input .button, input[type=checkbox]:checked, input[type=radio]:checked, #content input[type=submit].edd-submit, .download-meta .edd_go_to_checkout, .page-numbers.current, .page-numbers:hover, .rslides_nav:hover, .main-navigation .edd_checkout, #searchform #searchsubmit' ).css( 'background', to );
		} );
	} );


	// Header title color
	wp.customize( 'checkout_title_color', function( value ) {
		value.bind( function( to ) {
			$( '.hero-title h1' ).css( 'color', to );
		} );
	} );


	// Header title color
	wp.customize( 'checkout_subtitle_color', function( value ) {
		value.bind( function( to ) {
			$( '.hero-title h3, .hero-title p' ).css( 'color', to );
		} );
	} );


	// Homepage header button
	wp.customize( 'checkout_header_button_one_text', function( value ) {
		value.bind( function( to ) {

			var buttonTitle = $( '.hero-title .button-one' ).attr( 'title' );

			if ( to ) {
				$( '.hero-title .button-one' ).text( to );
			} else {
				$( '.hero-title .button-one' ).text( buttonTitle );;
			}

		} );
	} );


	// Homepage header button two
	wp.customize( 'checkout_header_button_two_text', function( value ) {
		value.bind( function( to ) {

			var buttonTwoTitle = $( '.hero-title .button-two' ).attr( 'title' );

			if ( to ) {
				$( '.hero-title .button-two' ).text( to );
			} else {
				$( '.hero-title .button-two' ).text( buttonTwoTitle );
			}

		} );
	} );


	// Homepage header button one color
	wp.customize( 'checkout_header_button_one_color', function( value ) {
		value.bind( function( to ) {
			$( '.button-one' ).css( 'background', to );
		} );
	} );


	// Homepage header button two color
	wp.customize( 'checkout_header_button_two_color', function( value ) {
		value.bind( function( to ) {
			$( '.button-two' ).css( 'background', to );
		} );
	} );


	// Header nav color
	wp.customize( 'checkout_nav_color', function( value ) {
		value.bind( function( to ) {
			$( '.main-navigation a' ).css( 'color', to );
		} );
	} );


	// Footer nav color
	wp.customize( 'checkout_footer_nav_color', function( value ) {
		value.bind( function( to ) {
			$( '.footer-widgets a, .site-footer .copyright a, .site-footer' ).css( 'color', to );
		} );
	} );


	// Footer banner title
	wp.customize( 'checkout_footer_cta_title', function( value ) {
		value.bind( function( to ) {
			$( '.section-cta h3' ).text( to );
		} );
	} );


	// Footer banner subtitle
	wp.customize( 'checkout_footer_cta_subtitle', function( value ) {
		value.bind( function( to ) {
			$( '.section-cta p' ).text( to );
		} );
	} );


	// Footer banner button
	wp.customize( 'checkout_footer_cta_button_text', function( value ) {
		value.bind( function( to ) {

			var buttonCtaTitle = $( '.section-cta .cta-button' ).attr( 'title' );

			if ( to ) {
				$( '.section-cta .cta-button' ).text( to );
			} else {
				$( '.section-cta .cta-button' ).text( buttonCtaTitle );
			}
		} );
	} );


	// Header and footer background color
	wp.customize( 'checkout_header_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-header, .site-footer' ).css( 'background', to );
		} );
	} );


	// Homepage testimonial title
	wp.customize( 'checkout_testimonial_title', function( value ) {
		value.bind( function( to ) {
			$( '.testimonial-title' ).text( to );
		} );
	} );

} )( jQuery );
