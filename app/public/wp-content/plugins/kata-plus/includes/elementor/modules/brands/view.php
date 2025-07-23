<?php
/**
 * Brands view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings                                  = $this->get_settings();
$img                                       = $settings['yb_img'];
$num                                       = $settings['yb_num'];
$num_tablet                                = isset( $settings['yb_num_tablet'] ) ? $settings['yb_num_tablet'] : '2';
$num_mobile                                = isset( $settings['yb_num_mobile'] ) ? $settings['yb_num_mobile'] : '1';
$carousel                                  = isset( $settings['tesp_carousel'] ) ? $settings['tesp_carousel'] : '';
$carousel == 'yes' ? $carousel_item        = ' owl-carousel owl-theme kata-owl' : '';
$carousel == 'yes' ? $num                  = '' : '';
$settings['inc_owl_arrow']                 = $settings['inc_owl_arrow'] == 'true' ? 'true' : 'false';
$settings['inc_owl_pag']                   = $settings['inc_owl_pag'] == 'true' ? 'true' : 'false';
$settings['inc_owl_loop']                  = $settings['inc_owl_loop'] == 'true' ? 'true' : 'false';
$settings['inc_owl_autoplay']              = $settings['inc_owl_autoplay'] == 'true' ? 'true' : 'false';
$settings['inc_owl_center']                = $settings['inc_owl_center'] == 'true' ? 'true' : 'false';
$settings['inc_owl_vert']                  = $settings['inc_owl_vert'] == 'true' ? 'true' : 'false';
$animateout                                = $settings['inc_owl_vert'] == 'true' ? 'fadeOutUp' : '';
$animatein                                 = $settings['inc_owl_vert'] == 'true' ? 'fadeInUp' : '';
$settings['inc_owl_rtl']                   = $settings['inc_owl_rtl'] == 'true' ? 'true' : 'false';
$settings['inc_owl_item_tablet']           = ! empty( $settings['inc_owl_item_tablet'] ) ? $settings['inc_owl_item_tablet'] : '2';
$settings['inc_owl_item_mobile']           = ! empty( $settings['inc_owl_item_mobile'] ) ? $settings['inc_owl_item_mobile'] : '1';
$settings['inc_owl_stgpad_tablet']['size'] = isset( $settings['inc_owl_stgpad_tablet']['size'] ) ? $settings['inc_owl_stgpad_tablet']['size'] : '0';
$settings['inc_owl_stgpad_mobile']['size'] = isset( $settings['inc_owl_stgpad_mobile']['size'] ) ? $settings['inc_owl_stgpad_mobile']['size'] : '0';
$settings['inc_owl_margin_tablet']['size'] = isset( $settings['inc_owl_margin_tablet']['size'] ) ? $settings['inc_owl_margin_tablet']['size'] : '0';
$settings['inc_owl_margin_mobile']['size'] = isset( $settings['inc_owl_margin_mobile']['size'] ) ? $settings['inc_owl_margin_mobile']['size'] : '0';
$slide_speed                               = $settings['inc_owl_spd']['size'];
$classes                                   = $settings['inc_owl_pag_num'] == 'true' ? 'dots-num' : '';
$classes                                  .= $carousel == 'yes' ? ' kata-owl owl-carousel owl-theme' : '';
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

if ( $img ) {

	$owl = array();
	$owl['inc_owl_item'] = ! empty( $settings['inc_owl_item'] ) ? $settings['inc_owl_item'] : '3';
	$owl['inc_owl_item_laptop'] = ! empty( $settings['inc_owl_item_laptop'] ) ? $settings['inc_owl_item_laptop'] : $owl['inc_owl_item'];
	$owl['inc_owl_item_tablet_extra'] = ! empty( $settings['inc_owl_item_tablet_extra'] ) ? $settings['inc_owl_item_tablet_extra'] : $owl['inc_owl_item_laptop'];
	$owl['inc_owl_item_tablet'] = ! empty( $settings['inc_owl_item_tablet'] ) ? $settings['inc_owl_item_tablet'] : $owl['inc_owl_item_tablet_extra'];
	$owl['inc_owl_item_mobile_extra'] = ! empty( $settings['inc_owl_item_mobile_extra'] ) ? $settings['inc_owl_item_mobile_extra'] : $owl['inc_owl_item_tablet'];
	$owl['inc_owl_item_mobile'] = ! empty( $settings['inc_owl_item_mobile'] ) ? $settings['inc_owl_item_mobile'] : $owl['inc_owl_item_mobile_extra'];
	?>

	<div class="kata-brands <?php echo esc_attr( $classes ); ?>"
	data-inc_owl_items="<?php echo esc_attr( json_encode( $owl ) ); ?>"
	data-inc_owl_spd="<?php echo esc_attr( $slide_speed ); ?>"
	data-inc_owl_smspd="<?php echo esc_attr( $settings['inc_owl_smspd']['size'] ); ?>"
	data-inc_owl_stgpad="<?php echo esc_attr( $settings['inc_owl_stgpad']['size'] ); ?>"
	data-inc_owl_stgpadtab="<?php echo esc_attr( $settings['inc_owl_stgpad_tablet']['size'] ); ?>"
	data-inc_owl_stgpadmob="<?php echo esc_attr( $settings['inc_owl_stgpad_mobile']['size'] ); ?>"
	data-inc_owl_margin="<?php echo esc_attr( $settings['inc_owl_margin']['size'] ); ?>"
	data-inc_owl_margintab="<?php echo esc_attr( $settings['inc_owl_margin_tablet']['size'] ); ?>"
	data-inc_owl_marginmob="<?php echo esc_attr( $settings['inc_owl_margin_mobile']['size'] ); ?>"
	data-inc_owl_arrow="<?php echo esc_attr( $settings['inc_owl_arrow'] ); ?>"
	data-inc_owl_pag="<?php echo esc_attr( $settings['inc_owl_pag'] ); ?>"
	data-inc_owl_loop="<?php echo esc_attr( $settings['inc_owl_loop'] ); ?>"
	data-inc_owl_autoplay="<?php echo esc_attr( $settings['inc_owl_autoplay'] ); ?>"
	data-inc_owl_center="<?php echo esc_attr( $settings['inc_owl_center'] ); ?>"
	data-animatein="<?php echo esc_attr( $animatein ); ?>"
	data-animateout="<?php echo esc_attr( $animateout ); ?>"
	data-inc_owl_prev="<?php echo base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_prev'], '', '' ) ); ?>"
	data-inc_owl_nxt="<?php echo base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_nxt'], '', '' ) ); ?>"
	data-inc_owl_rtl="<?php echo esc_attr( $settings['inc_owl_rtl'] ); ?>"
	>
		<?php foreach ( $img as $image ) { ?>
			<div class="kata-brands-items kata-lazyload brands-items-<?php echo esc_attr( $num ); ?> brands-items-<?php echo esc_attr( $num_tablet ); ?>-tab brands-items-<?php echo esc_attr( $num_mobile ); ?>-mob">
				<?php Kata_Plus_Helpers::image_resize_output( $image['id'], 'full' ); ?>
			</div>
		<?php } ?>
	</div>
	<?php
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
