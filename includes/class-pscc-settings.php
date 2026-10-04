<?php
/**
 * Settings storage.
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reads plugin settings from the pscc_settings option.
 */
class PSCC_Settings {

	const OPTION_KEY = 'pscc_settings';

	/**
	 * Default settings.
	 *
	 * @return array
	 */
	public static function defaults(): array {
		return array(
			'enabled'           => true,
			'position'          => 'bottom',
			'layout'            => 'bar',
			'style'             => 'light',
			'banner_title'      => __( 'We use cookies', 'pavel-silinskii-cookie-consent' ),
			'banner_text'       => __( 'This website uses cookies to ensure you get the best experience on our website.', 'pavel-silinskii-cookie-consent' ),
			'accept_text'       => __( 'Accept', 'pavel-silinskii-cookie-consent' ),
			'decline_text'      => __( 'Decline', 'pavel-silinskii-cookie-consent' ),
			'show_decline'      => true,
			'privacy_page_id'   => 0,
			'privacy_link_text' => __( 'Privacy Policy', 'pavel-silinskii-cookie-consent' ),
			'cookie_duration'   => 365,
			'button_color'      => '#2271b1',
		);
	}

	/**
	 * Stored settings merged over defaults.
	 *
	 * @return array
	 */
	public static function get(): array {
		$stored = get_option( self::OPTION_KEY, array() );

		return wp_parse_args( is_array( $stored ) ? $stored : array(), self::defaults() );
	}
}
