<?php
/**
 * Plugin Name:       Pavel Silinskii Cookie Consent
 * Plugin URI:        https://github.com/pavelsilinskiiwork/pavel-silinskii-cookie-consent
 * Description:       Lightweight GDPR/CCPA cookie consent banner with bar and popup layouts, light/dark themes, and one-click accept/decline.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      8.0
 * Author:            Pavel Silinskii
 * Author URI:        https://profiles.wordpress.org/pavelsilinskii/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pavel-silinskii-cookie-consent
 * Domain Path:       /languages
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PSCC_VERSION', '1.0.0' );
define( 'PSCC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PSCC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PSCC_PLUGIN_FILE', __FILE__ );

require_once PSCC_PLUGIN_DIR . 'includes/class-pscc-installer.php';
require_once PSCC_PLUGIN_DIR . 'includes/class-pscc-settings.php';
require_once PSCC_PLUGIN_DIR . 'includes/class-pscc-frontend.php';
require_once PSCC_PLUGIN_DIR . 'admin/class-pscc-admin.php';

register_activation_hook( __FILE__, array( 'PSCC_Installer', 'activate' ) );

add_action(
	'plugins_loaded',
	function () {
		PSCC_Frontend::init();

		if ( is_admin() ) {
			PSCC_Admin::init();
		}
	}
);
