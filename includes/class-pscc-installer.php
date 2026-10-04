<?php
/**
 * Activation routine.
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin activation.
 */
class PSCC_Installer {

	/**
	 * Runs on plugin activation.
	 *
	 * Nothing to create: no custom tables, and settings fall back to
	 * PSCC_Settings::defaults() until the admin saves them.
	 */
	public static function activate(): void {}
}
