<?php
/**
 * Consent banner (bar or popup layout).
 *
 * @package PavelSilinskiiCookieConsent
 *
 * @var array $s Settings from PSCC_Settings::get(), set by PSCC_Frontend::render_banner().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pscc_privacy_url = '';
if ( ! empty( $s['privacy_page_id'] ) ) {
	$pscc_privacy_url = get_permalink( absint( $s['privacy_page_id'] ) );
}
?>
<div id="pscc-banner"
	class="pscc-hidden pscc-<?php echo esc_attr( $s['layout'] ); ?> pscc-<?php echo esc_attr( $s['position'] ); ?> pscc-<?php echo esc_attr( $s['style'] ); ?>"
	data-layout="<?php echo esc_attr( $s['layout'] ); ?>"
	data-position="<?php echo esc_attr( $s['position'] ); ?>"
	data-style="<?php echo esc_attr( $s['style'] ); ?>">
	<?php if ( 'popup' === $s['layout'] ) : ?>
		<div class="pscc-overlay"></div>
	<?php endif; ?>
	<div class="pscc-box">
		<div class="pscc-content">
			<?php if ( ! empty( $s['banner_title'] ) ) : ?>
				<h3 class="pscc-title"><?php echo esc_html( $s['banner_title'] ); ?></h3>
			<?php endif; ?>
			<p class="pscc-text"><?php echo esc_html( $s['banner_text'] ); ?></p>
			<?php if ( $pscc_privacy_url ) : ?>
				<p class="pscc-privacy">
					<a href="<?php echo esc_url( $pscc_privacy_url ); ?>"><?php echo esc_html( $s['privacy_link_text'] ); ?></a>
				</p>
			<?php endif; ?>
		</div>
		<div class="pscc-buttons">
			<button type="button" class="pscc-btn pscc-accept" style="background-color:<?php echo esc_attr( $s['button_color'] ); ?>">
				<?php echo esc_html( $s['accept_text'] ); ?>
			</button>
			<?php if ( ! empty( $s['show_decline'] ) ) : ?>
				<button type="button" class="pscc-btn pscc-decline">
					<?php echo esc_html( $s['decline_text'] ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>
