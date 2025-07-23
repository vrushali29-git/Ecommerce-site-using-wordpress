<?php
/**
 * Loop Thumbnail.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Helpers\ThumbnailGenerator as ThumbGen;

global $product;

$thumbnail_id        = get_post_thumbnail_id();
$gallery             = $product->get_gallery_image_ids();
$slider_images_ids   = array_merge( array( $thumbnail_id ), $gallery );
$has_gallery_slider  = count( $gallery ) >= 1;
$thumb_size          = $args['thumb_size'] ?? 'woocommerce_thumbnail';
$thumbnail_type      = $args['thumbnail_type'] ?? 'single';
$nav_type            = $args['nav_type'] ?? 'bullets_nav';
$show_arrows         = $args['show_arrows'] ?? 'yes';
$show_nav            = $args['show_nav'] ?? false;
$first_image_gallery = '';
$classes             = '';

$id         = wp_rand( 1, 999 ) . '-' . $product->get_id();
$wrapper_id = "sp-pr-thmb-{$id}";
$is_slider  = ( 'slider' === $thumbnail_type && $has_gallery_slider );

$gallery_images = array();
if ( 'change_image_on_hover' === $thumbnail_type ) {
	$classes            .= ' has-gallery';
	$first_image_gallery = isset( $gallery[0] ) ? wp_get_attachment_image( $gallery[0], 'woocommerce_thumbnail', false ) : '';
} elseif ( $is_slider ) {
	$classes        .= ' sp-loop-slider';
	$arrows          = $args['show_arrows'] ?? true;
	$nav             = ( $show_nav && 'bullets_nav' === $nav_type );
	$slider_selector = "#{$wrapper_id} .sp-thmb-slider";
	$nav_selector    = "#{$wrapper_id} .sp-nav-slider";

	$slider_attrs = array(
		'slidesToShow' => 1,
		'rtl'          => is_rtl(),
		'asNavFor'     => ( 'images_nav' === $nav_type && $has_gallery_slider ) ? $nav_selector : '',
		'arrows'       => $arrows == 'true',
		'dots'         => $nav === true,
		'fade'         => true,
	);

	$nav_attrs = array(
		'slidesToShow'  => 3,
		'rtl'           => is_rtl(),
		'asNavFor'      => $slider_selector,
		'focusOnSelect' => true,
		'arrows'        => false,
		'dots'          => false,
	);

	wp_enqueue_style( 'slick' );
	wp_enqueue_script( 'slick' );
}
?>
<div id="<?php echo esc_attr( $wrapper_id ); ?>" class="sp-product-thumbnail <?php esc_attr_e( $classes ); ?>">
	<a href="<?php echo esc_url( get_the_permalink() ); ?>">
		<?php
		echo $is_slider ? '<div class="sp-slider sp-slider-style sp-thmb-slider" data-slick="' . esc_attr( json_encode( $slider_attrs ) ) . '">' : '';
		if ( $thumb_size ) {
			ThumbGen::instance()->image_resize_output(
				$thumbnail_id,
				$thumb_size
			);

			if ( $is_slider ) {

				foreach ( $gallery as $image_id ) {

					echo ThumbGen::instance()->image_resize_output(
						$image_id,
						$thumb_size
					);
				}
			} elseif ( $first_image_gallery && isset( $gallery[0] ) ) {
				?>
				<div class="sp-product-th-gallery">
					<?php
					echo ThumbGen::instance()->image_resize_output(
						$gallery[0],
						$thumb_size
					);
					?>
				</div>
				<?php
			}
		} else {
			ThumbGen::instance()->image_resize_output(
				$thumbnail_id,
				$thumb_size
			);

			if ( $is_slider ) {
				foreach ( $gallery as $image_id ) {
					echo wp_get_attachment_image( $image_id, $thumb_size, false );
				}
			} elseif ( ! empty( $first_image_gallery ) ) {
				?>
				<div class="sp-product-th-gallery"><?php echo wp_kses_post( $first_image_gallery ); ?></div>
				<?php
			}
		}
		echo $is_slider ? '</div>' : '';
		?>
	</a>
	<?php
	if ( $is_slider && 'images_nav' === $nav_type && $gallery && $has_gallery_slider ) {

		echo '<div class="sp-slider sp-nav-slider sp-slider-style" data-slick="' . esc_attr( json_encode( $nav_attrs ) ) . '">';
		foreach ( $slider_images_ids as $image_id ) {
			echo '<div class="sp-thumb-nav">';
			echo wp_get_attachment_image( $image_id, $thumb_size, false );
			echo '</div>';
		}
		echo '</div>';
	}
	?>
</div>
