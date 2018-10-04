<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'Array_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// The theme version to use in the updater
define( 'CHECKOUT_SL_THEME_VERSION', wp_get_theme( 'checkout' )->get( 'Version' ) );

// Loads the updater classes
$updater = new Array_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://arraythemes.com', // Site where EDD is hosted
		'item_name'      => 'Checkout WordPress Theme', // Name of theme
		'theme_slug'     => 'checkout', // Theme slug
		'version'        => CHECKOUT_SL_THEME_VERSION, // The current version of this theme
		'author'         => 'Array', // The author of this theme
		'download_id'    => '81505', // Optional, used for generating a license renewal link
		'renew_url'      => '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Getting Started', 'checkout' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'checkout' ),
		'license-key'               => __( 'Enter your license key', 'checkout' ),
		'license-action'            => __( 'License Action', 'checkout' ),
		'deactivate-license'        => __( 'Deactivate License', 'checkout' ),
		'activate-license'          => __( 'Activate License', 'checkout' ),
		'save-license'              => __( 'Save License', 'checkout' ),
		'status-unknown'            => __( 'License status is unknown.', 'checkout' ),
		'renew'                     => __( 'Renew?', 'checkout' ),
		'unlimited'                 => __( 'unlimited', 'checkout' ),
		'license-key-is-active'     => __( 'Typekit fonts and seamless theme updates have been enabled. You will receive a notice in your dashboard when a theme update is available.', 'checkout' ),
		'expires%s'                 => __( 'Your license for Checkout expires %s.', 'checkout' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'checkout' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'checkout' ),
		'license-key-expired'       => __( 'License key has expired.', 'checkout' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'checkout' ),
		'license-is-inactive'       => __( 'License is inactive.', 'checkout' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'checkout' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'checkout' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'checkout' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'checkout' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'checkout' )
	)

);