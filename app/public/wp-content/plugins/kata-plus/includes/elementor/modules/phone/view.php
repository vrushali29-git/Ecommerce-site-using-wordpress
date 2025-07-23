<?php
/**
 * Phone module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Group_Control_Image_Size;

$settings = $this->get_settings_for_display();
$this->add_inline_editing_attributes( 'label' );
$this->add_inline_editing_attributes( 'phonenumber' );
$label       = ! empty( $settings['label'] ) ? $settings['label'] : '';
$phonenumber = ! empty( $settings['phonenumber'] ) ? $settings['phonenumber'] : '';
$symbol      = $settings['symbol'];

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<?php if ( $label || $phonenumber ) : ?>
	<div class="kata-plus-phone-wrapper">
		<div class="kata-plus-phone-icon-wrap kata-lazyload <?php echo esc_attr( $symbol ); ?>">
			<?php
			if ( ! empty( $settings['phone_icon'] ) && $symbol == 'icon' ) {
				echo Kata_Plus_Helpers::get_icon( '', $settings['phone_icon'], '', '' );
			} elseif ( isset( $settings['phone_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['phone_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_image_srcset( $settings['phone_image']['id'], 'full' );
			} elseif ( ! empty( $settings['phone_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['phone_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'phone_image' );
			} elseif ( ! empty( $settings['phone_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['phone_image']['url'], 'svg' ) && $symbol == 'svg' ) {
				$svg_size = '';
				if ( isset( $settings['phone_image_custom_dimension']['width'] ) || isset( $settings['phone_image_custom_dimension']['height'] ) ) {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['phone_image_size'], $settings['phone_image_custom_dimension']['width'], $settings['phone_image_custom_dimension']['height'] );
				} else {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['phone_image_size'] );
				}
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['phone_image']['id'], $settings['phone_image']['url'], $svg_size );
			}
			?>
		</div>
		<?php if ( $label ) { ?>
			<h5 class="kata-plus-phone-label elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'label' ); ?>><?php echo wp_kses_post( $label ); ?></h5>
		<?php } ?>
		<?php if ( $phonenumber ) { ?>
			<p class="kata-plus-phone-number elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'phonenumber' ); ?>><?php echo wp_kses_post( $phonenumber ); ?></p>
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
