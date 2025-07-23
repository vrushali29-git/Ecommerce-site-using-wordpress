<?php
/**
 * Search module view.
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

$settings    = $this->get_settings_for_display();
$livesearch  = $settings['livesearch'] == 'yes' ? true : false;
$posttype    = $settings['posttype'] ? $settings['posttype'] : '';
$taxonomy    = $settings['taxonomy'] == 'disable' ? null : $settings['taxonomy'];
$button      = $settings['button'] ? $settings['button'] : '';
$placeholder = $settings['placeholder'] ? $settings['placeholder'] : '';
$layout      = $settings['layout'] ? $settings['layout'] : '';
$icon        = ( ! empty( $settings['icon'] ) ) ? Kata_Plus_Helpers::get_icon( '', $settings['icon'] ) : '';
switch ( $layout ) {
	case 'modal':
	case 'toggle':
		?>
		<div class="kt-search-open-as <?php echo esc_attr( $layout ); ?>">
			<a href="#" class="kt-toggle-wrapper kata-lazyload">
				<?php
				if ( $settings['toggleplaceholder'] ) :
					switch ( $settings['toggleplaceholder'] ) {
						case 'image':
							if ( $settings['image']['id'] ) {
								if ( isset( $settings['image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
									echo Kata_Plus_Helpers::get_image_srcset( $settings['image']['id'], 'full' );
								} elseif ( ! empty( $settings['image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'imagei' ) {
									echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'image' );
								} elseif ( ! empty( $settings['image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['image']['url'], 'svg' ) && $settings['symbol'] == 'svg' ) {
									$svg_size = '';
									if ( isset( $settings['image_custom_dimension']['width'] ) || isset( $settings['image_custom_dimension']['height'] ) ) {
										$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'], $settings['image_custom_dimension']['width'], $settings['image_custom_dimension']['height'] );
									} else {
										$svg_size = Kata_Plus_Helpers::svg_resize( $settings['image_size'] );
									}
									Kata_Plus_Helpers::get_attachment_svg_path( $settings['image']['id'], $settings['image']['url'], $svg_size );
								}
							}
							break;

						case 'icon':
						default:
							echo Kata_Plus_Helpers::get_icon( '', $settings['placeholder_icon'], '', '' );
							break;
					}
				endif;
				echo '<span class="kt-search-toggle-text">' . wp_kses( $settings['text'], wp_kses_allowed_html( 'post' ) ) . '</span>';
				?>
			</a>
			<div class="kt-search-wrapper" style="display:none;"><?php Kata_Plus_Frontend::search( $livesearch, $posttype, $taxonomy, $placeholder, $button, $icon ); ?></div>
			<?php
			if ( 'modal' == $layout ) {
				echo '<div class="kt-search-overlay" style="display:none;"></div>';
				echo '<div class="kt-close-overlay" style="display:none;"><a href="#" class="kt-close-search-modal">' . Kata_Plus_Helpers::get_icon( '', 'themify/close' ) . '</a></div>';
			}
			?>
		</div>
		<?php
		break;

	default:
		Kata_Plus_Frontend::search( $livesearch, $posttype, $taxonomy, $placeholder, $button, $icon );
		break;
}

if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
