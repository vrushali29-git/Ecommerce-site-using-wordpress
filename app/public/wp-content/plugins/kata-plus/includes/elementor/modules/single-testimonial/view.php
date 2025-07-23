<?php
/**
 * Single testionial module view.
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
$attrs    = '';
$pttesti  = $settings['pttesti'];

if ( $settings['testp_source'] == 'yes' ) {
	$query = new WP_Query( array( 'post_type' => 'kata_testimonial' ) );
	if ( ! empty( $settings['posts_array'] ) ) {
		foreach ( (array) $settings['posts_array'] as $key => $value ) {
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
?>
<div class="kata-plus-single-testimonial">
	<?php
	// Source is testimonial post type
	if ( $settings['testp_source'] == 'yes' ) :
		if ( $query->have_posts() ) {
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
				wp_reset_postdata();
		}
	endif;

	// Source is elementor
	if ( $settings['testp_source'] != 'yes' ) :

			$datee      = $settings['inc_owl_date'];
			$date_array = explode( ' ', $datee );
			$show_time  = $settings['show_time'];
		?>
			<div class="kata-plus-testimonial" data-item-id="<?php echo isset( $testimonial['pttest_shape']['citem'] ) ? esc_attr( $testimonial['pttest_shape']['citem'] ) : ''; ?>">
				<?php
				if ( $settings['rate'] != 'none' ) {
					echo '<div class="kt-ts-stars-wrapper">';
					switch ( $settings['rate'] ) {
						case 'one':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'one_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'two':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'two_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'three':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'three_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'four':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-o', 'df-fill kt-ts-star kt-ts-star-empty', '' );
							break;
						case 'four_half':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star-half-empty', 'df-fill kt-ts-star kt-ts-star-half', '' );
							break;
						case 'five':
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/star', 'df-fill kt-ts-star kt-star-full', '' );
							break;
					}
					echo '</div>';
				}
				?>
				<?php if ( $settings['inc_owl_img'] || $settings['inc_owl_icon'] ) { ?>

					<div class="kata-plus-img-wrap">
						<?php if ( ! empty( $settings['inc_owl_icon'] ) || ! empty( $settings['inc_owl_img'] ) ) { ?>
							<div class="kata-plus-icon kata-lazyload">
								<?php
								$symbol = $settings['symbol'];
								if ( ! empty( $settings['inc_owl_icon'] ) && $symbol == 'icon' ) {
									echo Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_icon'], '', '' );
								} elseif ( isset( $settings['inc_owl_image']['url'] ) && Kata_Plus_Helpers::string_is_contain( $settings['inc_owl_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
									echo Kata_Plus_Helpers::get_image_srcset( $settings['inc_owl_image']['id'], 'full' );
								} elseif ( ! empty( $settings['inc_owl_image']['id'] ) && ! Kata_Plus_Helpers::string_is_contain( $settings['inc_owl_image']['url'], 'svg' ) && $symbol == 'imagei' ) {
									echo Kata_Plus_Helpers::get_attachment_image_html( $settings, 'inc_owl_image' );
								} elseif ( ! empty( $settings['inc_owl_image']['id'] ) && Kata_Plus_Helpers::string_is_contain( $settings['inc_owl_image']['url'], 'svg' ) && $symbol == 'svg' ) {
									$svg_size = '';
									if ( isset( $settings['inc_owl_image_custom_dimension']['width'] ) || isset( $settings['inc_owl_image_custom_dimension']['height'] ) ) {
										$svg_size = Kata_Plus_Helpers::svg_resize( $settings['inc_owl_image_size'], $settings['inc_owl_image_custom_dimension']['width'], $settings['inc_owl_image_custom_dimension']['height'] );
									} else {
										$svg_size = Kata_Plus_Helpers::svg_resize( $settings['inc_owl_image_size'] );
									}
									Kata_Plus_Helpers::get_attachment_svg_path( $settings['inc_owl_image']['id'], $settings['inc_owl_image']['url'], $svg_size );
								}
								?>
							</div>
						<?php } ?>
						<?php if ( $settings['inc_owl_img']['id'] ) { ?>
							<div class="kata-plus-img kata-image kata-lazyload">
								<?php echo Kata_Plus_Helpers::get_image_srcset( $settings['inc_owl_img']['id'], 'full', '', array( 'alt' => $settings['inc_owl_name'] ) ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="kata-plus-testimonial-content">
					<?php if ( $settings['inc_owl_cnt'] ) { ?>
						<div class="kata-plus-cnt-wrap">
							<blockquote class="kata-plus-cnt elementor-inline-editing">
								<?php echo wp_kses( $settings['inc_owl_cnt'], wp_kses_allowed_html( 'post' ) ); ?>
							</blockquote>
						</div>
					<?php } ?>
					<div class="name-pos-wrap">
						<?php if ( $settings['inc_owl_name'] ) { ?>
							<div class="kata-plus-name-wrap">
								<div class="kata-plus-name elementor-inline-editing">
									<?php echo wp_kses( $settings['inc_owl_name'], wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( $settings['inc_owl_pos'] ) { ?>
							<div class="kata-plus-pos-wrap">
								<div class="kata-plus-pos elementor-inline-editing">
									<?php echo wp_kses( $settings['inc_owl_pos'], wp_kses_allowed_html( 'post' ) ); ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( $settings['inc_owl_date'] ) { ?>
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
					<?php if ( $settings['inc_owl_shape'] && $settings['inc_owl_shape']['desktop'] != '' ) { ?>
						<div class="kata-plus-shape"></div>
					<?php } if ( $settings['inc_owl_html'] ) { ?>
						<div class="kata-plus-cshtml">
							<?php echo wp_kses( $settings['inc_owl_html'], wp_kses_allowed_html( 'post' ) ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
	endif;
	?>
</div>
<?php

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
