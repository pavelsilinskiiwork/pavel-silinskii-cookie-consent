<?php
/**
 * Admin settings page (Settings API).
 *
 * @package PavelSilinskiiCookieConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings → Cookie Consent.
 */
class PSCC_Admin {

	const PAGE_SLUG = 'pscc-settings';

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'admin_menu', array( self::class, 'add_menu' ) );
		add_action( 'admin_init', array( self::class, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue' ) );
	}

	/**
	 * Add the options page.
	 */
	public static function add_menu(): void {
		add_options_page(
			__( 'Cookie Consent', 'pavel-silinskii-cookie-consent' ),
			__( 'Cookie Consent', 'pavel-silinskii-cookie-consent' ),
			'manage_options',
			self::PAGE_SLUG,
			array( self::class, 'render_page' )
		);
	}

	/**
	 * Register the option, sections and fields.
	 */
	public static function register_settings(): void {
		register_setting(
			'pscc_settings_group',
			PSCC_Settings::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( self::class, 'sanitize' ),
			)
		);

		$sections = array(
			'pscc_general'    => __( 'General', 'pavel-silinskii-cookie-consent' ),
			'pscc_text'       => __( 'Banner Text', 'pavel-silinskii-cookie-consent' ),
			'pscc_privacy'    => __( 'Privacy Policy', 'pavel-silinskii-cookie-consent' ),
			'pscc_appearance' => __( 'Appearance', 'pavel-silinskii-cookie-consent' ),
		);

		foreach ( $sections as $id => $title ) {
			add_settings_section( $id, $title, '__return_false', self::PAGE_SLUG );
		}

		$fields = array(
			// General.
			array( 'enabled', __( 'Enable banner', 'pavel-silinskii-cookie-consent' ), 'pscc_general', 'checkbox', array( 'label' => __( 'Show the cookie consent banner on the site', 'pavel-silinskii-cookie-consent' ) ) ),
			array(
				'position',
				__( 'Position', 'pavel-silinskii-cookie-consent' ),
				'pscc_general',
				'radio',
				array(
					'options' => array(
						'bottom' => __( 'Bottom', 'pavel-silinskii-cookie-consent' ),
						'top'    => __( 'Top', 'pavel-silinskii-cookie-consent' ),
					),
					'desc'    => __( 'Applies to the bar layout.', 'pavel-silinskii-cookie-consent' ),
				),
			),
			array(
				'layout',
				__( 'Layout', 'pavel-silinskii-cookie-consent' ),
				'pscc_general',
				'radio',
				array(
					'options' => array(
						'bar'   => __( 'Bar', 'pavel-silinskii-cookie-consent' ),
						'popup' => __( 'Popup', 'pavel-silinskii-cookie-consent' ),
					),
				),
			),

			// Banner text.
			array( 'banner_title', __( 'Title', 'pavel-silinskii-cookie-consent' ), 'pscc_text', 'text', array() ),
			array( 'banner_text', __( 'Text', 'pavel-silinskii-cookie-consent' ), 'pscc_text', 'textarea', array() ),
			array( 'accept_text', __( 'Accept button label', 'pavel-silinskii-cookie-consent' ), 'pscc_text', 'text', array() ),
			array( 'decline_text', __( 'Decline button label', 'pavel-silinskii-cookie-consent' ), 'pscc_text', 'text', array() ),
			array( 'show_decline', __( 'Show decline button', 'pavel-silinskii-cookie-consent' ), 'pscc_text', 'checkbox', array( 'label' => __( 'Display the Decline button', 'pavel-silinskii-cookie-consent' ) ) ),

			// Privacy policy.
			array( 'privacy_page_id', __( 'Privacy page', 'pavel-silinskii-cookie-consent' ), 'pscc_privacy', 'page', array() ),
			array( 'privacy_link_text', __( 'Link text', 'pavel-silinskii-cookie-consent' ), 'pscc_privacy', 'text', array() ),

			// Appearance.
			array(
				'style',
				__( 'Style', 'pavel-silinskii-cookie-consent' ),
				'pscc_appearance',
				'radio',
				array(
					'options' => array(
						'light' => __( 'Light', 'pavel-silinskii-cookie-consent' ),
						'dark'  => __( 'Dark', 'pavel-silinskii-cookie-consent' ),
					),
				),
			),
			array(
				'cookie_duration',
				__( 'Cookie duration (days)', 'pavel-silinskii-cookie-consent' ),
				'pscc_appearance',
				'number',
				array(
					'min' => 1,
					'max' => 365,
				),
			),
			array( 'button_color', __( 'Button color', 'pavel-silinskii-cookie-consent' ), 'pscc_appearance', 'color', array() ),
		);

		foreach ( $fields as $field ) {
			list( $key, $label, $section, $type, $extra ) = $field;

			add_settings_field(
				'pscc_' . $key,
				$label,
				array( self::class, 'render_field' ),
				self::PAGE_SLUG,
				$section,
				array_merge(
					$extra,
					array(
						'key'       => $key,
						'type'      => $type,
						'label_for' => in_array( $type, array( 'radio', 'checkbox' ), true ) ? '' : 'pscc_' . $key,
					)
				)
			);
		}
	}

	/**
	 * Render a single settings field.
	 *
	 * @param array $args Field arguments.
	 */
	public static function render_field( array $args ): void {
		$s     = PSCC_Settings::get();
		$key   = $args['key'];
		$id    = 'pscc_' . $key;
		$name  = PSCC_Settings::OPTION_KEY . '[' . $key . ']';
		$value = $s[ $key ];

		switch ( $args['type'] ) {
			case 'checkbox':
				printf(
					'<label><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> %4$s</label>',
					esc_attr( $id ),
					esc_attr( $name ),
					checked( ! empty( $value ), true, false ),
					esc_html( $args['label'] )
				);
				break;

			case 'radio':
				foreach ( $args['options'] as $option_value => $option_label ) {
					printf(
						'<label style="margin-right:16px;"><input type="radio" name="%1$s" value="%2$s" %3$s> %4$s</label>',
						esc_attr( $name ),
						esc_attr( $option_value ),
						checked( $value, $option_value, false ),
						esc_html( $option_label )
					);
				}
				break;

			case 'textarea':
				printf(
					'<textarea id="%1$s" name="%2$s" rows="3" class="large-text">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_textarea( $value )
				);
				break;

			case 'number':
				printf(
					'<input type="number" id="%1$s" name="%2$s" value="%3$s" min="%4$d" max="%5$d" class="small-text">',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value ),
					absint( $args['min'] ),
					absint( $args['max'] )
				);
				break;

			case 'color':
				printf(
					'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="pscc-color-field" data-default-color="%4$s">',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value ),
					esc_attr( PSCC_Settings::defaults()['button_color'] )
				);
				break;

			case 'page':
				wp_dropdown_pages(
					array(
						'id'                => esc_attr( $id ),
						'name'              => esc_attr( $name ),
						'selected'          => absint( $value ),
						'show_option_none'  => esc_html__( '— Select a page —', 'pavel-silinskii-cookie-consent' ),
						'option_none_value' => '0',
					)
				);
				break;

			default:
				printf(
					'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text">',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value )
				);
		}

		if ( ! empty( $args['desc'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $args['desc'] ) );
		}
	}

	/**
	 * Sanitize the settings array.
	 *
	 * @param mixed $input Raw submitted value.
	 * @return array
	 */
	public static function sanitize( $input ): array {
		$input    = is_array( $input ) ? $input : array();
		$defaults = PSCC_Settings::defaults();
		$clean    = array();

		$clean['enabled']           = ! empty( $input['enabled'] );
		$clean['position']          = in_array( $input['position'] ?? '', array( 'bottom', 'top' ), true ) ? $input['position'] : $defaults['position'];
		$clean['layout']            = in_array( $input['layout'] ?? '', array( 'bar', 'popup' ), true ) ? $input['layout'] : $defaults['layout'];
		$clean['style']             = in_array( $input['style'] ?? '', array( 'light', 'dark' ), true ) ? $input['style'] : $defaults['style'];
		$clean['banner_title']      = sanitize_text_field( $input['banner_title'] ?? $defaults['banner_title'] );
		$clean['banner_text']       = sanitize_textarea_field( $input['banner_text'] ?? $defaults['banner_text'] );
		$clean['accept_text']       = sanitize_text_field( $input['accept_text'] ?? $defaults['accept_text'] );
		$clean['decline_text']      = sanitize_text_field( $input['decline_text'] ?? $defaults['decline_text'] );
		$clean['show_decline']      = ! empty( $input['show_decline'] );
		$clean['privacy_page_id']   = absint( $input['privacy_page_id'] ?? 0 );
		$clean['privacy_link_text'] = sanitize_text_field( $input['privacy_link_text'] ?? $defaults['privacy_link_text'] );
		$clean['cookie_duration']   = min( 365, max( 1, absint( $input['cookie_duration'] ?? 365 ) ) );
		// sanitize_hex_color() returns '' for empty input and null for invalid input.
		$clean['button_color'] = sanitize_hex_color( $input['button_color'] ?? '' ) ?: $defaults['button_color'];

		// An empty Accept label would render an invisible button.
		if ( '' === $clean['accept_text'] ) {
			$clean['accept_text'] = $defaults['accept_text'];
		}

		return $clean;
	}

	/**
	 * Render the settings page.
	 */
	public static function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'pavel-silinskii-cookie-consent' ) );
		}
		?>
		<div class="wrap pscc-admin-wrap">
			<h1><?php esc_html_e( 'Cookie Consent Settings', 'pavel-silinskii-cookie-consent' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'pscc_settings_group' );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue admin assets on the settings page only.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue( $hook ): void {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style(
			'pscc-admin',
			PSCC_PLUGIN_URL . 'assets/css/pscc-admin.css',
			array(),
			PSCC_VERSION
		);
		wp_enqueue_script(
			'pscc-admin',
			PSCC_PLUGIN_URL . 'assets/js/pscc-admin.js',
			array( 'wp-color-picker' ),
			PSCC_VERSION,
			true
		);
	}
}
