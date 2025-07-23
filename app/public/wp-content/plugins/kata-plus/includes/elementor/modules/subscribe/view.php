<?php
/**
 * Subscribe module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings    = $this->get_settings_for_display();
$action      = $settings['action'];
$button      = $settings['button'];
$icon_btn    = Kata_Plus_Helpers::get_icon( '', $settings['icon'] ) ? Kata_Plus_Helpers::get_icon( '', $settings['icon'] ) : '';
$placeholder = $settings['placeholder'];

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
?>
<div class="kata-subscribe">
	<?php if ( 'feedburner' == $settings['source'] ) : ?>
		<?php
			$feedburner_url = 'https://feedburner.google.com/fb/a/mailverify?uri=' . $settings['feedburner_uri']
		?>
		<form action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(<?php echo esc_url( $feedburner_url ); ?>, 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
			<div class="kata-subscribe-email">
				<input type="email" name="email" placeholder="<?php echo esc_attr( $placeholder ); ?>" required>
				<input type="hidden" value="<?php echo esc_attr( $settings['feedburner_uri'] ); ?>" name="uri">
				<input type="hidden" name="loc" value="en_US">
				<button type="submit" class="kt-submit-sub dbg-color">
					<?php if ( 'left' == $settings['icon_pos'] ) { ?>
						<?php echo $icon_btn; ?>
					<?php } ?>
					<?php echo esc_attr( $button ); ?>
					<?php if ( 'right' == $settings['icon_pos'] ) { ?>
						<?php echo $icon_btn; ?>
					<?php } ?>
				</button>
			</div>
		</form>
	<?php endif; ?>
	<?php if ( 'mailchimp' == $settings['source'] ) : ?>
		<form action="<?php echo esc_url( $action['url'] ); ?>" method="post" target="_blank">
			<div class="kata-subscribe-email">
				<input type="email" name="EMAIL" id="mce-EMAIL" placeholder="<?php esc_attr( $placeholder ); ?>" required>
				<button type="submit" class="kt-submit-sub dbg-color">
					<?php if ( 'left' == $settings['icon_pos'] ) { ?>
						<?php echo $icon_btn; ?>
					<?php } ?>
					<?php echo esc_attr( $button ); ?>
					<?php if ( 'right' == $settings['icon_pos'] ) { ?>
						<?php echo $icon_btn; ?>
					<?php } ?>
				</button>
			</div>
		</form>
	<?php endif; ?>
</div>

<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
