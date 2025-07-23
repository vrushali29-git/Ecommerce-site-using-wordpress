<?php
/**
 * Image module view.
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
$symbol   = $settings['symbol'];

if ( empty( $settings['image']['url'] ) ) {
	return;
}

$has_caption = $this->has_caption( $settings );

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

$this->add_render_attribute( 'wrapper', 'class', 'kata-image kata-lazyload' );

if ( ! empty( $settings['shape'] ) ) {
	$this->add_render_attribute( 'wrapper', 'class', 'kata-image-shape-' . $settings['shape'] );
}

$link = $this->get_link_url( $settings );

if ( $link ) {
	$this->add_render_attribute(
		'link',
		array(
			'href'                         => $link['url'],
			'data-elementor-open-lightbox' => $settings['open_lightbox'],
		)
	);

	if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		$this->add_render_attribute(
			'link',
			array(
				'class' => 'elementor-clickable',
			)
		);
	}

	if ( ! empty( $link['is_external'] ) ) {
		$this->add_render_attribute( 'link', 'target', '_blank' );
	}

	if ( ! empty( $link['nofollow'] ) ) {
		$this->add_render_attribute( 'link', 'rel', 'nofollow' );
	}
} ?>
<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
	<?php if ( $has_caption ) : ?>
		<figure class="wp-caption">
	<?php endif; ?>
	<?php if ( $link ) : ?>
			<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
	<?php endif; ?>
		<?php
		$thumbnail_w       = get_option( 'thumbnail_size_w' ) != '0' ? 'width="' . get_option( 'thumbnail_size_w' ) . '"' : '';
		$thumbnail_h       = get_option( 'thumbnail_size_h' ) != '0' ? ' height="' . get_option( 'thumbnail_size_h' ) . '"' : '';
		$medium_w          = get_option( 'medium_size_w' ) != '0' ? 'width="' . get_option( 'medium_size_w' ) . '"' : '';
		$medium_h          = get_option( 'medium_size_h' ) != '0' ? ' height="' . get_option( 'medium_size_h' ) . '"' : '';
		$large_w           = get_option( 'large_size_w' ) != '0' ? 'width="' . get_option( 'large_size_w' ) . '"' : '';
		$large_h           = get_option( 'large_size_h' ) != '0' ? ' height="' . get_option( 'large_size_h' ) . '"' : '';
		$medium_large_w    = get_option( 'medium_large_size_w' ) != '0' ? 'width="' . get_option( 'medium_large_size_w' ) . '"' : '';
		$medium_large_h    = get_option( 'medium_large_size_h' ) != '0' ? ' height="' . get_option( 'medium_large_size_h' ) . '"' : '';
		$thumbnail_size    = $thumbnail_w . $thumbnail_h;
		$medium_size       = $medium_w . $medium_h;
		$medium_large_size = $medium_large_w . $medium_large_h;
		$large_size        = $large_w . $large_h;

		if ( $settings['image_size'] != 'custom' ) {
			switch ( $settings['image_size'] ) {
				case 'thumbnail':
					$svg_size = $thumbnail_size;
					break;
				case 'medium':
					$svg_size = $medium_size;
					break;
				case 'medium_large':
					$svg_size = $medium_large_size;
					break;
				case 'large':
					$svg_size = $large_size;
					break;
				case 'full':
					$svg_size = '';
					break;
			}
		} else {
			$custom_w = $settings['image_custom_dimension']['width'] ? 'width="' . $settings['image_custom_dimension']['width'] . '"' : '';
			$custom_h = $settings['image_custom_dimension']['height'] ? ' height"=' . $settings['image_custom_dimension']['height'] . '"' : '';
			$svg_size = $custom_w . $custom_h;
		}
		if ( Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $symbol == 'svg' ) {
			$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
			Kata_Plus_Helpers::get_attachment_svg_path( $settings['image']['id'], $settings['image']['url'], $svg_size );
		} else {
			echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'image', $settings['retina_image']['url'] );
		}
		?>
	<?php if ( $link ) : ?>
			</a>
	<?php endif; ?>
	<?php if ( $has_caption ) : ?>
			<figcaption class="widget-image-caption wp-caption-text"><?php echo esc_html( $this->get_caption( $settings ) ); ?></figcaption>
	<?php endif; ?>
	<?php if ( $has_caption ) : ?>
		</figure>
	<?php endif; ?>
</div>

<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
