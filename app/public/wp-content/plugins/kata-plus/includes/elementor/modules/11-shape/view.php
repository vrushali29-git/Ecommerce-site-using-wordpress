<?php
/**
 * Shape Toggle view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

use Elementor\Group_Control_Image_Size;

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->get_settings();
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>
<div class="kata-plus-shape-element"> 
	<?php
	switch ( $settings['shape'] ) {
		case 'circle':
			?>
				<div class="circle-shape dbg-color"></div>
				<?php
			break;

		case 'square':
			?>
				<div class="square-shape dbg-color"></div>
				<?php
			break;

		case 'custom':
			?>
				<div class="custom-shape"></div>
				<?php
			break;

		case 'img':
			?>
				<div class="img-shape kata-lazyload">
					<?php
					if ( Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) ) {
						$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
						Kata_Plus_Helpers::get_attachment_svg_path( $settings['image']['id'], $settings['image']['url'], $svg_size );
					} else {
						echo Kata_Plus_Helpers::get_attachment_image_html( $settings );
					}
					?>
				</div>
				<?php
			break;

		case 'svg':
			?>
				<div class="svg-shape">
					<?php
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
					Kata_Plus_Helpers::get_attachment_svg_path( $settings['image']['id'], $settings['image']['url'], $svg_size );
					?>
				</div>
				<?php
			break;

		default:
			?>
				<div class="circle-shape"></div>
				<?php
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
