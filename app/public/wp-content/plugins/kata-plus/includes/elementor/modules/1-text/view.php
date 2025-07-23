<?php
/**
 * Text module view.
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

$this->add_inline_editing_attributes( $settings['text_editor'] );
$text     = ! empty( $settings['text'] ) ? $settings['text'] : '';
$text_mce = ! empty( $settings['text_mce'] ) ? $settings['text_mce'] : '';
$text     = $text && $settings['text_editor'] == 'text' ? $text : $text_mce;
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<?php if ( $text ) : ?>
	<div class="kata-plus-text elementor-inline-editing" <?php echo $this->get_render_attribute_string( $settings['text_editor'] ); ?>>
		<?php
			echo wp_kses( $text, wp_kses_allowed_html( 'post' ) );
		?>
	</div>
	<?php
endif;

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
