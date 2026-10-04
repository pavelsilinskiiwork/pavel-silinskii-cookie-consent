<?php
/**
 * Uninstall: remove plugin settings.
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'pscc_settings' );
