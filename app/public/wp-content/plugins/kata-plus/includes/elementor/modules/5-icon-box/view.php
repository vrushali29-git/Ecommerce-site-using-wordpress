<?php
/**
 * Icon Box module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;

$settings = $this->get_settings_for_display();
$this->add_inline_editing_attributes( 'iconbox_title' );
$this->add_inline_editing_attributes( 'iconbox_subtitle' );
$this->add_inline_editing_attributes( 'iconbox_desc' );
$this->add_inline_editing_attributes( 'linkcnt' );
$space    = ' ';
$title    = $settings['iconbox_title'];
$subtitle = $settings['iconbox_subtitle'];
$desc     = $settings['iconbox_desc'];
$lists    = $settings['icon_box_lists'];
$align    = $settings['iconbox_layout'] . $space . $settings['iconbox_aligne'];
$clss     = $align . $space;
$symbol   = $settings['symbol'];
$url      = Kata_Plus_Helpers::get_link_attr( $settings['iconbox_link'] );
$linkcnt  = $settings['linkcnt'];
$lazyload = '';
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<div class="iconbox-parent">
	<?php if ( $url->src && $settings['link_to_whole_wrapper'] == 'yes' ) { ?>
		<a <?php echo $url->src . ' ' . $url->rel . ' ' . $url->target; ?>>
	<?php } ?>
		<div class="kata-plus-iconbox <?php echo esc_attr( $clss ); ?>">
			<?php if ( $symbol ) { ?>
				<?php
				if ( $settings['iconbox_icon'] || ! empty( $settings['iconbox_image']['id'] ) ) {
					if ( isset( $settings['iconbox_image']['url'] ) ) {
						if ( $symbol != 'svg' || $symbol != 'icon' ) {
							$lazyload = 'kata-lazyload ';
						}
					}
					?>
					<div class="kata-plus-iconbox-icon-wrap <?php echo esc_attr( $lazyload . $symbol ); ?>">
						<?php
						if ( ! empty( $settings['iconbox_icon'] ) && $symbol == 'icon' ) {
							echo Kata_Plus_Helpers::get_icon( '', $settings['iconbox_icon'], '', '' );
						} elseif ( isset( $settings['iconbox_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['iconbox_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
							echo Kata_Plus_Helpers::get_image_srcset( $settings['iconbox_image']['id'], 'full' );
						} elseif ( ! empty( $settings['iconbox_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['iconbox_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
							echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'iconbox_image', $settings['retina_image']['url'] );
						} elseif ( ! empty( $settings['iconbox_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['iconbox_image']['url'], 'svg' ) && $symbol == 'svg' ) {
							$svg_size = '';
							if ( isset( $settings['iconbox_image_custom_dimension']['width'] ) || isset( $settings['iconbox_image_custom_dimension']['height'] ) ) {
								$svg_size = Kata_Plus_Helpers::svg_resize( $settings['iconbox_image_size'], $settings['iconbox_image_custom_dimension']['width'], $settings['iconbox_image_custom_dimension']['height'] );
							} else {
								$svg_size = Kata_Plus_Helpers::svg_resize( $settings['iconbox_image_size'] );
							}
							Kata_Plus_Helpers::get_attachment_svg_path( $settings['iconbox_image']['id'], $settings['iconbox_image']['url'], $svg_size );
						}
						?>
					</div>
				<?php } ?>
			<?php } ?>
			<?php if ( $title || $subtitle || $desc || ! empty( $settings['iconbox_icon'] ) || ! empty( $settings['iconbox_image']['id'] ) ) { ?>
				<?php if ( $settings['iconbox_layout'] == 'vertical' ) { ?>
					<div class="kata-plus-iconbox-cntt">
				<?php } ?>
				<?php if ( $title ) { ?>
					<<?php echo Kata_Plus_Helpers::whitelist_html_tags( $settings['iconbox_title_tag'], 'h3' ); ?> <?php echo $this->get_render_attribute_string( 'iconbox_title' ); ?> data-class="ck-icon-box-title"><?php echo wp_kses( $title, wp_kses_allowed_html( 'post' ) ); ?></<?php echo Kata_Plus_Helpers::whitelist_html_tags( $settings['iconbox_title_tag'], 'h3' ); ?>>
				<?php } ?>
				<?php if ( $subtitle ) { ?>
					<<?php echo Kata_Plus_Helpers::whitelist_html_tags( $settings['iconbox_subtitle_tag'], 'h4' ); ?> <?php echo $this->get_render_attribute_string( 'iconbox_subtitle' ); ?> data-class="ck-icon-box-subtitle"><?php echo wp_kses( $subtitle, wp_kses_allowed_html( 'post' ) ); ?></<?php echo Kata_Plus_Helpers::whitelist_html_tags( $settings['iconbox_subtitle_tag'], 'h4' ); ?>>
				<?php } ?>
				<?php if ( $desc ) { ?>
					<p <?php echo $this->get_render_attribute_string( 'iconbox_desc' ); ?> data-class="ck-icon-box-desc">
						<?php echo wp_kses( $desc, wp_kses_allowed_html( 'post' ) ); ?>
					</p>
				<?php } ?>
				<?php if ( $lists ) { ?>
					<ul class="icon-box-lists">
						<?php foreach ( $lists as $list ) { ?>
							<li class="icon-box-list-item elementor-repeater-item-<?php echo esc_attr( $list['_id'] ); ?>"><?php echo esc_html( $list['icon_box_list'] ); ?></li>
						<?php } ?>
					</ul>
				<?php } ?>
				<?php if ( $url->src && ( $settings['link_icon'] || $linkcnt ) ) { ?>
					<a <?php echo $url->src . ' ' . $url->rel . ' ' . $url->target; ?> <?php echo $this->get_render_attribute_string( 'linkcnt' ); ?> data-class="icon-box-readmore">
						<?php echo wp_kses( $linkcnt, wp_kses_allowed_html( 'post' ) ); ?>
						<?php
						if ( ! empty( $settings['link_icon'] ) ) {
							echo Kata_Plus_Helpers::get_icon( '', $settings['link_icon'], '', '' );
						}
						?>
					</a>
				<?php } ?>
				<?php if ( $settings['iconbox_layout'] == 'vertical' ) { ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php if ( $url->src && $settings['link_to_whole_wrapper'] == 'yes' ) { ?>
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
