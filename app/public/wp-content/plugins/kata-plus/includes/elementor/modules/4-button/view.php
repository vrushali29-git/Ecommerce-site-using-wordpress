<?php

/**
 * Button module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

$settings = $this->get_settings_for_display();
$this->add_inline_editing_attributes( 'btn_text' );
$text        = $settings['btn_text'];
$url         = Kata_Plus_Helpers::get_link_attr( $settings['link'] );
$link_target = ( ! empty( $link['is_external'] ) ) ? 'target="_blank"' : '';
$link_rel    = ( ! empty( $link['nofollow'] ) ) ? 'rel="nofollow"' : '';
$icon        = ( ! empty( $settings['icon'] ) ) ? Kata_Plus_Helpers::get_icon( '', $settings['icon'] ) : '';
$this->add_inline_editing_attributes( 'text' );

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
echo '<div class="kata-plus-button-wrap">';
if ( $text ) {
	if ( $url->src || $settings['link_to_home'] == 'yes' ) {
		$href = $settings['link_to_home'] == 'yes' ? 'href="' . home_url() . '"' : $url->src; ?>
		<a <?php echo $href . ' ' . $url->rel . ' ' . $url->target; ?> class="kata-button kata-lazyload dbg-color">
		<?php
	}
	if ( $settings['button_icon_position'] == 'left' ) {
		if ( ! empty( $settings['icon'] ) && $settings['symbol'] == 'icon' ) {
			echo Kata_Plus_Helpers::get_icon( '', $settings['icon'], '', '' );
		} elseif ( isset( $settings['btn_image'] ) && Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
			echo Kata_Plus_Helpers::get_image_srcset( $settings['btn_image']['id'], 'full' );
		} elseif ( ! empty( $settings['btn_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
			echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'btn_image' );
		} elseif ( ! empty( $settings['btn_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'svg' ) {
			$svg_size = '';
			if ( isset( $settings['btn_image_custom_dimension']['width'] ) || isset( $settings['btn_image_custom_dimension']['height'] ) ) {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['btn_image_size'], $settings['btn_image_custom_dimension']['width'], $settings['btn_image_custom_dimension']['height'] );
			} else {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['btn_image_size'] );
			}
			Kata_Plus_Helpers::get_attachment_svg_path( $settings['btn_image']['id'], $settings['btn_image']['url'], $svg_size );
		}
	}
	?>
	<span class="kata-button-text elementor-inline-editing" <?php $this->get_render_attribute_string( 'btn_text' ); ?>><?php echo esc_html( $text ); ?></span>
	<?php

	if ( $settings['button_icon_position'] == 'right' ) {
		if ( ! empty( $settings['icon'] ) && $settings['symbol'] == 'icon' ) {
			echo Kata_Plus_Helpers::get_icon( '', $settings['icon'], '', '' );
		} elseif ( isset( $settings['btn_image'] ) && Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
			echo Kata_Plus_Helpers::get_image_srcset( $settings['btn_image']['id'], 'full' );
		} elseif ( ! empty( $settings['btn_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
			echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'btn_image' );
		} elseif ( ! empty( $settings['btn_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['btn_image']['url'], 'svg' ) && $settings['symbol'] == 'svg' ) {
			$svg_size = '';
			if ( isset( $settings['btn_image_custom_dimension']['width'] ) || isset( $settings['btn_image_custom_dimension']['height'] ) ) {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['btn_image_size'], $settings['btn_image_custom_dimension']['width'], $settings['btn_image_custom_dimension']['height'] );
			} else {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['btn_image_size'] );
			}
			Kata_Plus_Helpers::get_attachment_svg_path( $settings['btn_image']['id'], $settings['btn_image']['url'], $svg_size );
		}
	}
	if ( $url->src || $settings['link_to_home'] == 'yes' ) {
		?>
	</a>
		<?php
	}
}
echo '</div>';
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
