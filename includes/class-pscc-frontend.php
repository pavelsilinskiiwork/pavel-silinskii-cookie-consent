<?php
/**
 * Frontend: banner output, assets, consent AJAX endpoint.
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders the consent banner and handles the consent request.
 */
class PSCC_Frontend {

	const COOKIE_NAME = 'pscc_consent';

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue' ) );
		add_action( 'wp_footer', array( self::class, 'render_banner' ) );
		add_action( 'wp_ajax_nopriv_pscc_consent', array( self::class, 'handle_consent' ) );
		add_action( 'wp_ajax_pscc_consent', array( self::class, 'handle_consent' ) );
	}

	/**
	 * Whether the banner should be output on this request.
	 *
	 * @return bool
	 */
	public static function should_show_banner(): bool {
		$s = PSCC_Settings::get();

		if ( empty( $s['enabled'] ) ) {
			return false;
		}

		// Visitor already decided.
		if ( isset( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Print the banner markup in the footer.
	 */
	public static function render_banner(): void {
		if ( ! self::should_show_banner() ) {
			return;
		}

		$s   = PSCC_Settings::get();
		$tpl = PSCC_PLUGIN_DIR . 'templates/banner.php';

		if ( file_exists( $tpl ) ) {
			include $tpl;
		}
	}

	/**
	 * AJAX: acknowledge the visitor's choice. The cookie itself is set client-side.
	 */
	public static function handle_consent(): void {
		check_ajax_referer( 'pscc_nonce', 'nonce' );

		$consent = isset( $_POST['consent'] ) ? sanitize_text_field( wp_unslash( $_POST['consent'] ) ) : '';

		if ( ! in_array( $consent, array( 'accepted', 'declined' ), true ) ) {
			wp_send_json_error( 'invalid' );
		}

		wp_send_json_success( array( 'consent' => $consent ) );
	}

	/**
	 * Enqueue frontend assets only when the banner is shown.
	 */
	public static function enqueue(): void {
		if ( ! self::should_show_banner() ) {
			return;
		}

		$s = PSCC_Settings::get();

		wp_enqueue_style(
			'pscc-frontend',
			PSCC_PLUGIN_URL . 'assets/css/pscc-frontend.css',
			array(),
			PSCC_VERSION
		);

		wp_enqueue_script(
			'pscc-frontend',
			PSCC_PLUGIN_URL . 'assets/js/pscc-frontend.js',
			array(),
			PSCC_VERSION,
			true
		);

		wp_localize_script(
			'pscc-frontend',
			'psccData',
			array(
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'pscc_nonce' ),
				'cookieDuration' => absint( $s['cookie_duration'] ),
				'cookieName'     => self::COOKIE_NAME,
			)
		);
	}
}
