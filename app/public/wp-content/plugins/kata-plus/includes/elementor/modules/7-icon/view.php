<?php

/**
 * Icon module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;

$settings = $this->get_settings();
$url      = Kata_Plus_Helpers::get_link_attr( $settings['url'] );

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
?>
<div class="kata-plus-icon kata-lazyload">
	<?php
	$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
	if ( ! empty( $settings['url']['url'] ) ) {
		echo '<a ' . $url->src . $url->rel . $url->target . ' >';
	}

	if ( ! empty( $settings['icon'] ) && $settings['symbol'] == 'icon' ) {
		echo Kata_Plus_Helpers::get_icon( '', $settings['icon'], '', '' );
	} elseif ( Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
		echo Kata_Plus_Helpers::get_image_srcset( $settings['image']['id'], 'full' );
	} elseif ( ! empty( $settings['image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
		echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'image' );
	} elseif ( ! empty( $settings['image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'svg' ) {
		$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
		Kata_Plus_Helpers::get_attachment_svg_path( $settings['image']['id'], $settings['image']['url'], $svg_size );
	}

	if ( ! empty( $settings['url']['url'] ) ) {
		echo '</a>';
	}
	?>

</div>
<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
