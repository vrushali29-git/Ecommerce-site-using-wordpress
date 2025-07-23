<?php
/**
 * Email module view.
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
$this->add_inline_editing_attributes( 'email' );
$label  = ! empty( $settings['label'] ) ? $settings['label'] : '';
$email  = ! empty( $settings['email'] ) ? $settings['email'] : '';
$symbol = $settings['symbol'];
$url    = Kata_Plus_Helpers::get_link_attr( $settings['email_link'] );

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<?php if ( $label || $email ) : ?>
	<div class="kata-plus-email-wrapper">
		<?php if ( $url->src ) { ?>
			<a <?php echo $url->src . ' ' . $url->rel . ' ' . $url->target; ?>>
		<?php } ?>
		<div class="kata-plus-email-icon-wrap kata-lazyload <?php echo esc_attr( $symbol ); ?>">
			<?php
			if ( ! empty( $settings['email_icon'] ) && $symbol == 'icon' ) {
				echo Kata_Plus_Helpers::get_icon( '', $settings['email_icon'], '', '' );
			} elseif ( isset( $settings['email_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['email_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_image_srcset( $settings['email_image']['id'], 'full' );
			} elseif ( ! empty( $settings['email_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['email_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'email_image' );
			} elseif ( ! empty( $settings['email_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['email_image']['url'], 'svg' ) && $symbol == 'svg' ) {
				$svg_size = '';
				if ( isset( $settings['email_image_custom_dimension']['width'] ) || isset( $settings['email_image_custom_dimension']['height'] ) ) {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['email_image_size'], $settings['email_image_custom_dimension']['width'], $settings['email_image_custom_dimension']['height'] );
				} else {
					$svg_size = Kata_Plus_Helpers::svg_resize( $settings['email_image_size'] );
				}
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['email_image']['id'], $settings['email_image']['url'], $svg_size );
			}
			?>
		</div>
		<?php if ( $label ) { ?>
			<h5 class="kata-plus-email-label elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'label' ); ?>><?php echo wp_kses_post( $label ); ?></h5>
		<?php } ?>
		<?php if ( $email ) { ?>
			<p class="kata-plus-email-number elementor-inline-editing" <?php echo $this->get_render_attribute_string( 'email' ); ?>><?php echo wp_kses_post( $email ); ?></p>
		<?php } ?>
		<?php if ( $url->src ) { ?>
			</a>
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
