<?php
/**
 * Address module view.
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

$settings = $this->get_settings_for_display();
$this->add_inline_editing_attributes( 'label' );
$this->add_inline_editing_attributes( 'address' );
$label   = ! empty( $settings['label'] ) ? $settings['label'] : '';
$address = ! empty( $settings['address'] ) ? $settings['address'] : '';
$symbol  = $settings['symbol'];

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<?php if ( $label || $address ) : ?>
	<div class="kata-plus-address-wrapper">
		<div class="kata-plus-address-icon-wrap kata-lazyload <?php echo esc_attr( $symbol ); ?>">
			<?php
			if ( ! empty( $settings['address_icon'] ) && $symbol == 'icon' ) {
				echo Kata_Plus_Helpers::get_icon( '', $settings['address_icon'], '', '' );
			} elseif ( isset( $settings['address_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['address_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_image_srcset( $settings['address_image']['id'], 'full' );
			} elseif ( ! empty( $settings['address_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['address_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'address_image' );
			} elseif ( ! empty( $settings['address_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['address_image']['url'], 'svg' ) && $symbol == 'svg' ) {
				$svg_size = '';
				if ( isset( $settings['address_image_custom_dimension']['width'] ) || isset( $settings['address_image_custom_dimension']['height'] ) ) {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['address_image_size'], $settings['address_image_custom_dimension']['width'], $settings['address_image_custom_dimension']['height'] );
				} else {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['address_image_size'] );
				}
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['address_image']['id'], $settings['address_image']['url'], $svg_size );
			}
			?>
		</div>
		<?php if ( $label ) { ?>
			<h5 class="kata-plus-address-label elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'label' ); ?>><?php echo wp_kses_post( $label ); ?></h5>
		<?php } ?>
		<?php if ( $address ) { ?>
			<p class="kata-plus-address elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'address' ); ?>><?php echo wp_kses_post( $address ); ?></p>
		<?php } ?>
	</div>
	<?php
endif;
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
