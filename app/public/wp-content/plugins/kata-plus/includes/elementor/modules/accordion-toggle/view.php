<?php
/**
 * Accordion Toggle view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->get_settings_for_display();
$items    = $settings['at_items'];

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
if ( $items ) {
	?>
	<div class="kata-accordion-plus">
		<?php
		foreach ( $items as $index => $item ) {
			$at_name_setting_key = $this->get_repeater_setting_key( 'at_name', 'at_items', $index );
			$this->add_inline_editing_attributes( $at_name_setting_key );
			$icon       = $item['at_icon'];
			$icon_close = $item['at_icon_close'];
			$name       = $item['at_name'];
			$txt        = $item['at_txt'];
			?>
			<div class="kata-accordion">
				<div class="kata-accordion-btn">
					<p <?php echo $this->get_render_attribute_string( $at_name_setting_key ); ?>><?php echo wp_kses( $name, wp_kses_allowed_html( 'post' ) ); ?></p>
					<?php
					if ( $icon || $icon_close ) {
						echo Kata_Plus_Helpers::get_icon( '', $icon, 'accordion-icon open-icon', '' );
						echo Kata_Plus_Helpers::get_icon( '', $icon_close, 'accordion-icon close-icon', '' );
					}
					?>
				</div>

				<div class="kata-accordion-content" style="display: none;">
					<p><?php echo wp_kses( $txt, wp_kses_allowed_html( 'post' ) ); ?></p>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
