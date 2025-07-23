<?php
/**
 * Testimonials module view.
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

$settings     = $this->get_settings_for_display();
$attrs        = '';
$testimonials = $settings['testimonials'];
$pttesti      = $settings['pttesti'];
// owl options
$settings['inc_owl_arrow']    = $settings['inc_owl_arrow'] == 'true' ? 'true' : 'false';
$settings['inc_owl_pag']      = $settings['inc_owl_pag'] == 'true' && $settings['inc_owl_pag_num'] != 'dots-and-num' ? 'true' : 'false';
$settings['inc_owl_loop']     = $settings['inc_owl_loop'] == 'true' ? 'true' : 'false';
$settings['inc_owl_autoplay'] = $settings['inc_owl_autoplay'] == 'true' ? 'true' : 'false';
$settings['inc_owl_center']   = $settings['inc_owl_center'] == 'true' ? 'true' : 'false';
$settings['inc_owl_vert']     = $settings['inc_owl_vert'] == 'true' ? 'true' : 'false';
$animateout                   = $settings['inc_owl_vert'] == 'true' ? 'fadeOutUp' : '';
$animatein                    = $settings['inc_owl_vert'] == 'true' ? 'fadeInUp' : '';
$settings['inc_owl_rtl']      = $settings['inc_owl_rtl'] == 'true' ? 'true' : 'false';
$settings['inc_owl_stgpad_tablet']['size'] = isset( $settings['inc_owl_stgpad_tablet']['size'] ) ? $settings['inc_owl_stgpad_tablet']['size'] : '0';
$settings['inc_owl_stgpad_mobile']['size'] = isset( $settings['inc_owl_stgpad_mobile']['size'] ) ? $settings['inc_owl_stgpad_mobile']['size'] : '0';
$settings['inc_owl_margin_tablet']['size'] = isset( $settings['inc_owl_margin_tablet']['size'] ) ? $settings['inc_owl_margin_tablet']['size'] : '0';
$settings['inc_owl_margin_mobile']['size'] = isset( $settings['inc_owl_margin_mobile']['size'] ) ? $settings['inc_owl_margin_mobile']['size'] : '0';
$slide_speed                               = $settings['inc_owl_spd']['size'];
$classes                                   = $settings['inc_owl_pag_num'];
if ( $settings['testp_source'] == 'yes' ) {
	$query = new WP_Query( array( 'post_type' => 'kata_testimonial' ) );
	if ( ! empty( $settings['posts_array'] ) ) {
		foreach ( $settings['posts_array'] as $key => $value ) {
			if ( ! empty( $value ) ) {
				$posts[] = $value;
			}
		}
	} else {
		$posts = array();
	}
	$args  = array(
		'post_type'   => 'kata_testimonial',
		'post_status' => 'publish',
		'post__in'    => $posts,
	);
	$query = new \WP_Query( $args );
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

$owl = array();
$owl['inc_owl_item'] = ! empty( $settings['inc_owl_item'] ) ? $settings['inc_owl_item'] : '3';
$owl['inc_owl_item_laptop'] = ! empty( $settings['inc_owl_item_laptop'] ) ? $settings['inc_owl_item_laptop'] : $owl['inc_owl_item'];
$owl['inc_owl_item_tablet_extra'] = ! empty( $settings['inc_owl_item_tablet_extra'] ) ? $settings['inc_owl_item_tablet_extra'] : $owl['inc_owl_item_laptop'];
$owl['inc_owl_item_tablet'] = ! empty( $settings['inc_owl_item_tablet'] ) ? $settings['inc_owl_item_tablet'] : $owl['inc_owl_item_tablet_extra'];
$owl['inc_owl_item_mobile_extra'] = ! empty( $settings['inc_owl_item_mobile_extra'] ) ? $settings['inc_owl_item_mobile_extra'] : $owl['inc_owl_item_tablet'];
$owl['inc_owl_item_mobile'] = ! empty( $settings['inc_owl_item_mobile'] ) ? $settings['inc_owl_item_mobile'] : $owl['inc_owl_item_mobile_extra'];

?>
<div
class="kata-plus-testimonials kata-owl owl-carousel owl-theme <?php echo esc_attr( $classes ); ?>"
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
data-inc_owl_prev="<?php echo esc_attr( base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_prev'], '', '' ) ) ); ?>"
data-inc_owl_nxt="<?php echo esc_attr( base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_nxt'], '', '' ) ) ); ?>"
data-inc_owl_rtl="<?php echo esc_attr( $settings['inc_owl_rtl'] ); ?>"
>
	<?php
	// Source is testimonial post type
	if ( $settings['testp_source'] == 'yes' ) :
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();
				$content = get_the_content();
				$name    = get_the_title( get_the_ID() );
				$pos     = get_post_meta( get_the_ID(), 'testimonial_position', true );
				$date    = get_post_meta( get_the_ID(), 'testimonial_date', true );
				?>
				<div class="kata-plus-testimonial">
					<div class="kata-plus-img-wrap">
						<?php
						if ( has_post_thumbnail( get_the_ID() ) ) {
							?>
							<div class="kata-plus-img kata-lazyload">
								<?php echo Kata_Plus_Helpers::get_image_srcset( get_post_thumbnail_id( get_the_ID() ), 'full', '', array( 'alt' => get_post_meta( get_the_ID(), 'testimonial_name', true ) ) ); ?>
							</div>
						<?php } else { ?>
							<div class="kata-plus-icon">
								<?php echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/quote-left', 'df-fill', '' ); ?>
							</div>
						<?php } ?>
					</div>
					<div class="kata-plus-testimonial-content">
						<?php if ( ! empty( $content ) ) { ?>
							<div class="kata-plus-cnt-wrap">
								<blockquote class="kata-plus-cnt">
									<?php the_content(); ?>
								</blockquote>
							</div>
						<?php } if ( ! empty( $name ) ) { ?>
							<div class="kata-plus-name-wrap">
								<div class="kata-plus-name">
									<?php echo wp_kses( $name, wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } if ( ! empty( $pos ) ) { ?>
							<div class="kata-plus-pos-wrap">
								<div class="kata-plus-pos">
									<?php echo wp_kses( get_post_meta( get_the_ID(), 'testimonial_position', true ), wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } if ( ! empty( $date ) ) { ?>
							<div class="kata-plus-date-wrap">
								<div class="kata-plus-date">
									<?php echo wp_kses( get_post_meta( get_the_ID(), 'testimonial_date', true ), wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
							<?php
						} if ( $pttesti && $pttesti['0']['pttest_shape'] && $pttesti['0']['pttest_shape']['desktop'] != '' ) {
							foreach ( $pttesti as $shape ) {
								?>
								<div class="kata-plus-shape-wrap elementor-repeater-item-<?php echo esc_attr( $shape['_id'] ); ?>">
									<div class="kata-plus-shape"></div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		}
	endif;
	?>

	<?php
	if ( $settings['testp_source'] != 'yes' ) :
		foreach ( $testimonials as $index => $testimonial ) {
			?>
			<?php
			$inc_owl_name_setting_key = $this->get_repeater_setting_key( 'inc_owl_name', 'testimonials', $index );
			$inc_owl_pos_setting_key  = $this->get_repeater_setting_key( 'inc_owl_pos', 'testimonials', $index );
			$inc_owl_cnt_setting_key  = $this->get_repeater_setting_key( 'inc_owl_cnt', 'testimonials', $index );
			$this->add_inline_editing_attributes( $inc_owl_name_setting_key );
			$this->add_inline_editing_attributes( $inc_owl_pos_setting_key );
			$this->add_inline_editing_attributes( $inc_owl_cnt_setting_key );
			$datee      = $testimonial['inc_owl_date'];
			$date_array = explode( ' ', $datee );
			$show_time  = $testimonial['show_time'];
			?>
			<div class="kata-plus-testimonial elementor-repeater-item-<?php echo esc_attr( $testimonial['_id'] ); ?>" data-item-id="<?php echo isset( $testimonial['pttest_shape']['citem'] ) ? esc_attr( $testimonial['pttest_shape']['citem'] ) : ''; ?>">
				<?php
				if ( $testimonial['rate'] != 'none' ) {
					echo '<div class="kt-ts-stars-wrapper">';
					switch ( $testimonial['rate'] ) {
						case 'one':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'one_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'two':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'two_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'three':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'three_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'four':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'four_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							break;
						case 'five':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-fill', 'df-fill kt-ts-star kt-star-full', '' );
							break;
					}
					echo '</div>';
				}
				?>
				<?php if ( $testimonial['inc_owl_img'] || $testimonial['inc_owl_icon'] ) { ?>

					<div class="kata-plus-img-wrap">
						<?php if ( ! empty( $testimonial['inc_owl_icon'] ) || ! empty( $testimonial['inc_owl_img'] ) ) { ?>
							<div class="kata-plus-icon kata-lazyload">
								<?php
								$symbol = $testimonial['symbol'];
								if ( ! empty( $testimonial['inc_owl_icon'] ) && $symbol == 'icon' ) {
									echo Kata_Plus_Helpers::get_icon( '', $testimonial['inc_owl_icon'], '', '' );
								} elseif ( isset( $testimonial['inc_owl_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $testimonial['inc_owl_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
									echo Kata_Plus_Helpers::get_image_srcset( $testimonial['inc_owl_image']['id'], 'full' );
								} elseif ( ! empty( $testimonial['inc_owl_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $testimonial['inc_owl_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
									echo Kata_Plus_Helpers::get_attachment_image_html( $testimonial, 'inc_owl_image' );
								} elseif ( ! empty( $testimonial['inc_owl_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $testimonial['inc_owl_image']['url'], 'svg' ) && $symbol == 'svg' ) {
									$svg_size = '';
									if ( isset( $testimonial['inc_owl_image_custom_dimension']['width'] ) || isset( $testimonial['inc_owl_image_custom_dimension']['height'] ) ) {
										$svg_size = Kata_Plus_Helpers::svg_resize( $testimonial['inc_owl_image_size'], $testimonial['inc_owl_image_custom_dimension']['width'], $testimonial['inc_owl_image_custom_dimension']['height'] );
									} else {
										$svg_size = Kata_Plus_Helpers::svg_resize( $testimonial['inc_owl_image_size'] );
									}
									Kata_Plus_Helpers::get_attachment_svg_path( $testimonial['inc_owl_image']['id'], $testimonial['inc_owl_image']['url'], $svg_size );
								}
								?>
							</div>
						<?php } ?>
						<?php if ( $testimonial['inc_owl_img']['id'] ) { ?>
							<div class="kata-plus-img kata-lazyload">
								<?php echo Kata_Plus_Helpers::get_image_srcset( $testimonial['inc_owl_img']['id'], 'full', '', array( 'alt' => $testimonial['inc_owl_name'] ) ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="kata-plus-testimonial-content">
					<?php if ( $testimonial['inc_owl_cnt'] ) { ?>
						<div class="kata-plus-cnt-wrap">
							<blockquote class="kata-plus-cnt elementor-inline-editing" <?php echo $this->get_render_attribute_string( $inc_owl_cnt_setting_key ); ?>>
								<?php echo wp_kses( $testimonial['inc_owl_cnt'], wp_kses_allowed_html( 'post' ) ); ?>
							</blockquote>
						</div>
					<?php } ?>
					<div class="name-pos-wrap">
						<?php if ( $testimonial['inc_owl_name'] ) { ?>
							<div class="kata-plus-name-wrap">
								<div class="kata-plus-name elementor-inline-editing" <?php echo $this->get_render_attribute_string( $inc_owl_name_setting_key ); ?>>
									<?php echo wp_kses( $testimonial['inc_owl_name'], wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( $testimonial['inc_owl_pos'] ) { ?>
							<div class="kata-plus-pos-wrap">
								<div class="kata-plus-pos elementor-inline-editing" <?php echo $this->get_render_attribute_string( $inc_owl_pos_setting_key ); ?>>
									<?php echo wp_kses( $testimonial['inc_owl_pos'], wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( $testimonial['inc_owl_date'] ) { ?>
							<?php if ( $show_time == 'yes' ) { ?>
								<div class="kata-plus-date-wrap">
									<div class="kata-plus-date">
										<?php echo wp_kses( $date_array[0] . ' ' . $date_array[1], wp_kses_allowed_html( 'post' ) ); ?>
									</div>
								</div>
							<?php } else { ?>
								<div class="kata-plus-date-wrap">
									<div class="kata-plus-date">
										<?php echo wp_kses( $date_array[0], wp_kses_allowed_html( 'post' ) ); ?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<?php if ( $testimonial['inc_owl_shape'] ) { ?>
						<div class="kata-plus-shape"></div>
					<?php } if ( $testimonial['inc_owl_html'] ) { ?>
						<div class="kata-plus-cshtml">
							<?php echo wp_kses( $testimonial['inc_owl_html'], wp_kses_allowed_html( 'post' ) ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	endif;
	?>
</div>
<?php if ( $settings['inc_owl_pag_num'] == 'dots-and-num' ) { ?>
	<div class="kata-owl-progress-bar"><div class="kata-progress-bar-inner dbg-color"></div></div>
	<?php
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
