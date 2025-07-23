<?php
/**
 * Banner module view.
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
$this->add_inline_editing_attributes( 'banner_title' );
$this->add_inline_editing_attributes( 'banner_subtitle' );
$this->add_inline_editing_attributes( 'banner_button_txt' );
$image        = $settings['img'];
$shapes       = $settings['banner_shapes'];
$button_txt   = $settings['banner_button_txt'];
$title        = $settings['banner_title'];
$subtitle     = $settings['banner_subtitle'];
$title_tag    = $settings['banner_title_tag'];
$subtitle_tag = $settings['banner_subtitle_tag'];
$banner_tag   = $settings['banner_tag'];
$image_tag    = $settings['image_tag'];
$des_tag      = $settings['description_tag'];
$btn_link     = $settings['button_link'];
$btn_target   = ! empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
$btn_rel      = ! empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
$banner_link  = Kata_Plus_Helpers::get_link_attr( $settings['banner_link'] );

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
?>

	<?php echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $banner_tag, 'figure' ) . ' class="kata-banner-wrap">'; ?>

		<?php
		if ( $banner_link->src ) :
			echo '<a ' . $banner_link->src . ' ' . $banner_link->rel . ' ' . $banner_link->target . ' class="kata-banner-link"></a>';
endif;
		?>
		<?php echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $des_tag, 'div' ) . ' class="kata-banner-description">'; ?>
				<?php
				if ( ! empty( $title ) ) {
					echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $title_tag, 'h4' ) . ' class="kata-banner-title elementor-inline-editing" ' . $this->get_render_attribute_string( 'banner_title' ) . '>' . esc_html( $title ) . '</' . Kata_Plus_Helpers::whitelist_html_tags( $title_tag, 'h4' ) . '>';
				}

				if ( ! empty( $subtitle ) ) {
					echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $subtitle_tag, 'p' ) . ' class="kata-banner-subtitle elementor-inline-editing" ' . $this->get_render_attribute_string( 'banner_subtitle' ) . '>' . esc_html( $subtitle ) . '</' . Kata_Plus_Helpers::whitelist_html_tags( $subtitle_tag, 'p' ) . '>';
				}

				if ( $settings['banner_button_icon'] ) {
					$btn_icon = Kata_Plus_Helpers::get_icon( '', $settings['banner_button_icon'] );
				}

				if ( $button_txt && $btn_link['url'] ) {
					echo '<a href="' . $btn_link['url'] . '" ' . $btn_rel . ' ' . $btn_target . ' class="kata-banner-button elementor-inline-editing" ' . $this->get_render_attribute_string( 'banner_button_txt' ) . '>' . ( ! empty( $btn_icon ) ? $btn_icon : '' ) . '' . esc_html( $button_txt ) . '</a>';
				} elseif ( $button_txt ) {
					echo '<span class="kata-banner-button elementor-inline-editing" ' . $this->get_render_attribute_string( 'banner_button_txt' ) . '>' . ( ! empty( $btn_icon ) ? $btn_icon : '' ) . '' . esc_html( $button_txt ) . '</span>';
				}

				?>
		<?php echo '</' . Kata_Plus_Helpers::whitelist_html_tags( $des_tag, 'div' ) . '>'; ?>

		<?php
		echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $image_tag, 'div' ) . ' class="kata-banner-img kata-lazyload">';

		if ( ! empty( $image['id'] ) ) {
			echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'img', $settings['retina_image']['url'] );
		} else {
			echo '<img src="' . ELEMENTOR_ASSETS_URL . 'images/placeholder.png' . '">';
		}

		?>

		<?php echo '</' . Kata_Plus_Helpers::whitelist_html_tags( $image_tag, 'div' ) . '>'; ?>

		<?php
		if ( $shapes ) {
			foreach ( $shapes as $shape ) {
				echo '<span class="elementor-repeater-item-' . esc_attr( $shape['_id'] ) . ' kata-banner-shape" data-item-id="' . isset( $shape['shape_sk']['citem'] ) ? esc_attr( $shape['shape_sk']['citem'] ) : '' . '"></span>';
			}
		}
		?>
	<?php echo '</' . Kata_Plus_Helpers::whitelist_html_tags( $banner_tag, 'figure' ) . '>'; ?>

<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
