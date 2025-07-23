<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Image extends ShopPressWidgets {

	public function get_name() {
		return 'sp-image';
	}

	public function get_title() {
		return __( 'Product Images', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-thumbnail';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function get_style_depends() {
		return array( 'sp-image' );
	}

	public function get_script_depends() {
		return array( 'sp-image' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-images',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$product_gallery_status = sp_is_module_active( 'product_gallery' );

		if ( ! $product_gallery_status ) {
			$this->register_group_styler(
				'image',
				__( 'Image', 'shop-press' ),
				array(
					'container'      => array(
						'label'    => esc_html__( 'Container', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.woocommerce-product-gallery.woocommerce-product-gallery.images',
						'wrapper'  => '{{WRAPPER}} .sp-images',
					),
					'image'          => array(
						'label'    => esc_html__( 'Featured Image', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.woocommerce-product-gallery__wrapper img, {{WRAPPER}} .sp-images .sp-product-gallery images img',
						'wrapper'  => '{{WRAPPER}} .sp-images',
					),
					'zoom_container' => array(
						'label'    => esc_html__( 'Zoom Icon Container', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.woocommerce-product-gallery__trigger, {{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-__trigger',
						'wrapper'  => '{{WRAPPER}} .sp-images .woocommerce-product-gallery',
					),
				)
			);

			$this->register_group_styler(
				'gallery_images',
				__( 'Gallery Images', 'shop-press' ),
				array(
					'gallery_nav_wrap'      => array(
						'label'    => esc_html__( 'Nav Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.flex-control-nav.flex-control-thumbs',
						'wrapper'  => '{{WRAPPER}} .sp-images .woocommerce-product-gallery.images',
					),
					'gallery_images_wrap'   => array(
						'label'    => esc_html__( 'Images Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'li',
						'wrapper'  => '{{WRAPPER}} .sp-images .woocommerce-product-gallery.images .flex-control-nav.flex-control-thumbs',
					),
					'gallery_images'        => array(
						'label'    => esc_html__( 'Images', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'img',
						'wrapper'  => '{{WRAPPER}} .sp-images .woocommerce-product-gallery.images .flex-control-nav.flex-control-thumbs li',
					),
					'gallery_images_active' => array(
						'label'    => esc_html__( 'Active Image', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'img.flex-active',
						'wrapper'  => '{{WRAPPER}} .sp-images .woocommerce-product-gallery.images .flex-control-nav.flex-control-thumbs li',
					),
				)
			);
		}

		$slider_option = sp_get_module_settings( 'product_gallery', 'gallery_type' );

		if ( $slider_option === 'slider' ) {
			$this->register_group_styler(
				'featured_image_slider',
				__( 'Featured Image Slider', 'shop-press' ),
				array(
					'slider_wrapper'        => array(
						'label'    => esc_html__( 'Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery-slider.sp-slider-style',
						'wrapper'  => '{{WRAPPER}} .sp-images',
					),
					'slider_featured_image_wrapper'        => array(
						'label'    => esc_html__( 'Featured Image Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery-image',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style',
					),
					'slider_featured_image'                => array(
						'label'    => esc_html__( 'Featured Image', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'img',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style .sp-product-gallery-image',
					),
					'slider_zoom_container'                => array(
						'label'    => esc_html__( 'Zoom Icon Container', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery__trigger',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style .sp-product-gallery-image',
					),
					'slider_featured_image_prev'           => array(
						'label'    => esc_html__( 'Prev Arrow', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-arrow.slick-prev',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style',
					),
					'slider_featured_image_next'           => array(
						'label'    => esc_html__( 'Next Arrow', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-arrow.slick-next',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style',
					),
					'slider_featured_image_bullets_wrapper' => array(
						'label'    => esc_html__( 'Bullets Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-dots',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style',
					),
					'slider_featured_image_bullets'        => array(
						'label'    => esc_html__( 'Bullets', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'button',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style .slick-dots li',
					),
					'slider_featured_image_bullets_active' => array(
						'label'    => esc_html__( 'Active Bullet', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'button',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery-slider.sp-slider-style .slick-dots li.slick-active',
					),
				)
			);

			$this->register_group_styler(
				'slider_gallery_nav',
				__( 'Gallery Nav', 'shop-press' ),
				array(
					'slider_gallery_nav_wrapper'   => array(
						'label'    => esc_html__( 'Gallery Nav Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery-nav-items',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery',
					),
					'slider_gallery_nav_items'     => array(
						'label'    => esc_html__( 'Gallery Nav Items', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-slide',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-nav-items .sp-product-gallery-nav-items-images',
					),
					'slider_gallery_nav_current_item'     => array(
						'label'    => esc_html__( 'Gallery Nav Current Item', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-slide.slick-current',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-nav-items .sp-product-gallery-nav-items-images',
					),
					'slider_gallery_nav_items_img' => array(
						'label'    => esc_html__( 'Gallery Nav Items Images', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'img',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-nav-items .sp-product-gallery-nav-items-images .slick-slide .sp-product-gallery-nav-item',
					),
					'slider_gallery_nav_prev'      => array(
						'label'    => esc_html__( 'Prev Arrow', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-arrow.slick-prev',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-nav-items .sp-product-gallery-nav-items-images',
					),
					'slider_gallery_nav_next'      => array(
						'label'    => esc_html__( 'Next Arrow', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.slick-arrow.slick-next',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-nav-items .sp-product-gallery-nav-items-images',
					),
				)
			);
		}

		if ( $slider_option !== 'slider' ) {
			$this->register_group_styler(
				'column_gallery',
				__( 'Product Gallery', 'shop-press' ),
				array(
					'columns_gallery_items'          => array(
						'label'    => esc_html__( 'Gallery Items Wrapper', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery-image',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-images',
					),
					'columns_gallery_items_image'    => array(
						'label'    => esc_html__( 'Gallery Items Image', 'shop-press' ),
						'type'     => 'styler',
						'selector' => 'img',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-images .sp-product-gallery-image',
					),
					'columns_gallery_zoom_container' => array(
						'label'    => esc_html__( 'Zoom Icon Container', 'shop-press' ),
						'type'     => 'styler',
						'selector' => '.sp-product-gallery__trigger',
						'wrapper'  => '{{WRAPPER}} .sp-images .sp-product-gallery .sp-product-gallery-images .sp-product-gallery-image',
					),
				)
			);
		}
	}

	protected function register_controls() {
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-image' );
		}
	}

	protected function content_template() {

		$image_url = SHOPPRESS_URL . '/Elementor/widgets/single-product/image/images/t-shirt-with-logo-1-600x600.jpg';

		if ( has_action( 'shoppress_product_image_widget_dummy' ) ) {

			do_action( 'shoppress_product_image_widget_dummy' );
			return;
		}
		?>
		<style>
			ol.flex-control-nav.flex-control-thumbs li {
				display: inline-block;
			}

			ol.flex-control-nav.flex-control-thumbs {
				padding: 0;
				margin-top: 5px;
			}

			.woocommerce div.product .woocommerce-product-gallery.images {
				float: none !important;
			}
		</style>

		<div class="sp-images">
			<div class="sp-product-gallery woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1;">
				<a href="#" class="woocommerce-product-gallery__trigger sp-product-gallery__trigger">
					<img draggable="false" role="img" class="emoji" alt="🔍" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f50d.svg">
				</a>
				<div class="flex-viewport" style="overflow: hidden; position: relative; height: 550px;">
					<figure class="woocommerce-product-gallery__wrapper sp-product-gallery__wrapper" style="width: 1000%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
						<div class="sp-product-gallery-image woocommerce-product-gallery__image flex-active-slide" style="position: relative; overflow: hidden; width: 550px; margin-right: 0px; float: left; display: block;">
							<a href="<?php echo esc_url( $image_url ); ?>"><img width="600" height="600" src="<?php echo esc_url( $image_url ); ?>" class="wp-post-image">
						</div>
						<div data-thumb="<?php echo esc_url( $image_url ); ?>" class="sp-product-gallery-image woocommerce-product-gallery__image" style="width: 550px; margin-right: 0px; float: left; display: block;">
							<a href="<?php echo esc_url( $image_url ); ?>"><img width="600" height="600" src="<?php echo esc_url( $image_url ); ?>"></a>
						</div>
						<div class="sp-product-gallery-image woocommerce-product-gallery__image" style="width: 550px; margin-right: 0px; float: left; display: block;">
							<a href="<?php echo esc_url( $image_url ); ?>"><img width="600" height="600" src="<?php echo esc_url( $image_url ); ?>"></a>
						</div>
					</figure>
				</div>
				<ol class="flex-control-nav flex-control-thumbs">
					<li><img onload="this.width = this.naturalWidth; this.height = this.naturalHeight" src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/single-product/image/images/' ); ?>t-shirt-with-logo-1-100x100.jpg" class="flex-active" draggable="false" width="100" height="100"></li>
					<li><img onload="this.width = this.naturalWidth; this.height = this.naturalHeight" src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/single-product/image/images/' ); ?>hoodie-with-pocket-2-100x100.jpg" draggable="false" width="100" height="100"></li>
					<li><img onload="this.width = this.naturalWidth; this.height = this.naturalHeight" src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/single-product/image/images/' ); ?>hoodie-with-zipper-2-100x100.jpg" draggable="false" width="100" height="100"></li>
				</ol>
			</div>
		</div>
		<?php
	}
}
