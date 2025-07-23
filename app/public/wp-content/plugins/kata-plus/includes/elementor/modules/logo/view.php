<?php

/**
 * Logo module view.
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

$settings = $this->get_settings();
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

$kata_options = get_option( 'kata_options' );

$settings['default-logo']['url'] = $settings['default-logo']['url'] ? $settings['default-logo']['url'] : wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
$settings['default-logo']['id']  = $settings['default-logo']['id'] ? $settings['default-logo']['id'] : get_theme_mod( 'custom_logo' );

$settings['default-logo']['url']     = isset( $kata_options['fast-mode']['site-logo'] ) && ! empty( $kata_options['fast-mode']['site-logo'] ) ? wp_get_attachment_url( $kata_options['fast-mode']['site-logo'] ) : $settings['default-logo']['url'];
$settings['default-logo']['id']      = isset( $kata_options['fast-mode']['site-logo'] ) && ! empty( $kata_options['fast-mode']['site-logo'] ) ? $kata_options['fast-mode']['site-logo'] : $settings['default-logo']['id'];
$settings['default-logo']['url']     = $settings['default-logo']['url'] ? $settings['default-logo']['url'] : Kata_Plus::$assets . 'images/logo-150.png';
$settings['transparent-logo']['url'] = $settings['transparent-logo']['url'] ? $settings['transparent-logo']['url'] : Kata_Plus::$assets . 'images/logo-150.png';

?>

<div class="kata-logo">
	<?php if ( $settings['default-logo']['url'] ) { ?>
		<a class="kata-default-logo kata-lazyload" aria-label="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			if ( Kata_Plus_Helpers::string_is_contain( $settings['default-logo']['url'], 'svg' ) && $settings['symbol_default_logo'] == 'svg' ) {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['default-logo_size'], $settings['default-logo_custom_dimension']['width'], $settings['default-logo_custom_dimension']['height'] );
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['default-logo']['id'], $settings['default-logo']['url'], $svg_size );
			} else {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'default-logo' );
			}
			?>
		</a>
	<?php } ?>
	<?php if ( $settings['transparent-logo']['url'] ) { ?>
		<a class="kata-transparent-logo kata-lazyload" aria-label="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			if ( Kata_Plus_Helpers::string_is_contain( $settings['transparent-logo']['url'], 'svg' ) && $settings['symbol_transparent_logo'] == 'svg' ) {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['transparent-logo_size'], $settings['transparent-logo_custom_dimension']['width'], $settings['transparent-logo_custom_dimension']['height'] );
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['transparent-logo']['id'], $settings['transparent-logo']['url'], $svg_size );
			} else {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'transparent-logo' );
			}
			?>
		</a>
	<?php } else { ?>
		<a class="kata-transparent-logo kata-lazyload" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			if ( Kata_Plus_Helpers::string_is_contain( $settings['default-logo']['url'], 'svg' ) && $settings['symbol_default_logo'] == 'svg' ) {
				$svg_size = Kata_Plus_Helpers::svg_resize( $settings['default-logo_size'], $settings['default-logo_custom_dimension']['width'], $settings['default-logo_custom_dimension']['height'] );
				Kata_Plus_Helpers::get_attachment_svg_path( $settings['default-logo']['id'], $settings['default-logo']['url'], $svg_size );
			} else {
				echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'default-logo' );
			}
			?>
		</a>
	<?php } ?>
</div>

<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
