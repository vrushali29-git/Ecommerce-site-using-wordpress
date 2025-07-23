<?php
/**
 * Counter module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->get_settings_for_display();

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

if ( ! $settings['wp_gallery'] ) {
	return;
}

$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );
$url = Kata_Plus_Helpers::get_link_attr( $settings['external_url'] );

$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

if ( $settings['gallery_columns'] ) {
	$this->add_render_attribute( 'shortcode', 'columns', $settings['gallery_columns'] );
}

if ( $settings['gallery_link'] ) {
	$this->add_render_attribute( 'shortcode', 'link', $settings['gallery_link'] );
}

if ( ! empty( $settings['gallery_rand'] ) ) {
	$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
}
?>
<?php if ( $url->src && $settings['link_to_whole_wrapper'] == 'yes' ) { ?>
	<a <?php echo $url->src . ' ' . $url->rel . ' ' . $url->target; ?>>
<?php } ?>
	<div class="kata-plus-image-gallery">
		<?php
		add_filter( 'wp_get_attachment_link', array( $this, 'add_lightbox_data_to_image_link' ), 10, 2 );

		echo do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		remove_filter( 'wp_get_attachment_link', array( $this, 'add_lightbox_data_to_image_link' ) );
		?>
	</div>
<?php if ( $url->src && $settings['link_to_whole_wrapper'] == 'yes' ) { ?>
	</a>
<?php } ?>
<?php

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
