<?php
/**
 * Gap module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings   = $this->get_settings_for_display();
$element_id = $this->get_id();
$icons      = '';
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

if ( $settings['icons'] ) {
	foreach ( $settings['icons'] as $index => $icon ) {
		$icon_name_setting_key = $this->get_repeater_setting_key( 'icon_name', 'icons', $index );
		$this->add_inline_editing_attributes( $icon_name_setting_key );
		$icons .= '<div class="kata-social-icon elementor-repeater-item-' . $icon['_id'] . '">';
		if ( ! empty( $icon['icon_link']['url'] ) ) {
			$icon_url = Kata_Plus_Helpers::get_link_attr( $icon['icon_link'] );
			$icons   .= '<a ' . $icon_url->src . ' ' . esc_attr( $icon_url->rel ) . ' class="social-wrapper" aria-label="social" ' . esc_attr( $icon_url->target ) . '>';
		}
		$icon_i = ! empty( $icon['social_icon'] ) ? Kata_Plus_Helpers::get_icon( '', $icon['social_icon'], '', '' ) : '';
		if ( ! empty( $icon['icon_name'] ) ) {
			$class  = \Elementor\Plugin::$instance->editor->is_edit_mode() ? str_replace( 'class="', 'class="social-name ', $this->get_render_attribute_string( $icon_name_setting_key ) ) : 'class="social-name elementor-repeater-item-' . $icon['_id'] . '"';
			$icon_n = '<span ' . $class . '>' . $icon['icon_name'] . '</span>';
		} else {
			$icon_n = '';
		}
		if ( $settings['name_before_icon'] == 'on' ) {
			$icons .= $icon_n . $icon_i;
		} else {
			$icons .= $icon_i . $icon_n;
		}
		if ( ! empty( $icon['icon_link']['url'] ) ) {
			$icons .= '</a>';
		}
		$icons .= '</div>';
	}
}

echo '
    <div class="kata-social-icon-wrap ' . esc_attr( $settings['display_as'] ) . '">
        ' . $icons . '
    </div>
';

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
