<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class BrandAttribute extends ShopPressWidgets {

	public function get_name() {
		return 'sp-brand-attribute';
	}

	public function get_title() {
		return __( 'Brand Attribute', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-brands';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-brands', 'sp-brands-rtl' );
		} else {
			return array( 'sp-brands' );
		}
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'att_brand_wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'single_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
		$this->register_group_styler(
			'att_brand',
			__( 'Brand', 'shop-press' ),
			array(
				'single_att_brand_items_wrapper' => array(
					'label'    => esc_html__( 'Items Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brands-items',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands',
				),
				'single_att_brand_item' => array(
					'label'    => esc_html__( 'Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brand-item',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands .sp-brands-items',
				),
				'single_att_brand_link' => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands .sp-brands-items .sp-brand-item',
				),
				'single_att_brand_image_wrapper' => array(
					'label'    => esc_html__( 'Image Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brand-img-wrapper',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands .sp-brands-items .sp-brand-item a',
				),
				'single_att_brand_image' => array(
					'label'    => esc_html__( 'Image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands .sp-brands-items .sp-brand-item a .sp-brand-img-wrapper',
				),
				'single_att_brand_title' => array(
					'label'    => esc_html__( 'Brand Name', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.brand-name',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands .sp-brands-items .sp-brand-item a',
				),
				'single_att_brand_label' => array(
					'label'    => esc_html__( 'Brand Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-pr-brand-label',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid.sp-single-brands',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'brand_label',
			array(
				'label' => __( 'Label', 'shop-press' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'display_name',
			array(
				'label'        => __( 'Brand Name', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'false',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'display_logo',
			array(
				'label'        => __( 'Brand Logo', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'brand_label'  => $settings['brand_label'],
			'display_name' => $settings['display_name'],
			'display_logo' => $settings['display_logo'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-brand-attribute', $args );
		}
	}

	protected function content_template() {
		$image_url = SHOPPRESS_URL . '/Elementor/widgets/single-product/image/images/t-shirt-with-logo-1-600x600.jpg';

		?>
			<div class="sp-brands sp-brands-attrs sp-brands-grid sp-single-brands">
				<div class="sp-pr-brand-label">{{{ settings.brand_label }}}</div>
				<div class="sp-brands-items">
					<div class="sp-brand-item">
						<a href="#">
							<# if ( settings.display_logo ) { #>
								<div class="sp-brand-img-wrapper">
								<svg width="57" height="16" viewBox="0 0 57 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.42 15V0.84H3.66V12.02H10.64V15H0.42ZM18.7122 15.16C17.2589 15.16 15.9989 14.86 14.9322 14.26C13.8789 13.6467 13.0655 12.8 12.4922 11.72C11.9189 10.6267 11.6322 9.36667 11.6322 7.94C11.6322 6.47333 11.9255 5.2 12.5122 4.12C13.1122 3.02667 13.9455 2.18 15.0122 1.58C16.0789 0.966667 17.3389 0.66 18.7922 0.66C20.2189 0.66 21.4589 0.96 22.5122 1.56C23.5789 2.16 24.4055 3 24.9922 4.08C25.5922 5.14667 25.8922 6.40667 25.8922 7.86C25.8922 9.28667 25.5989 10.5533 25.0122 11.66C24.4255 12.7533 23.5922 13.6133 22.5122 14.24C21.4455 14.8533 20.1789 15.16 18.7122 15.16ZM18.7722 12.1C19.6789 12.1 20.4122 11.8933 20.9722 11.48C21.5322 11.0667 21.9389 10.54 22.1922 9.9C22.4455 9.26 22.5722 8.60667 22.5722 7.94C22.5722 7.44667 22.4989 6.95333 22.3522 6.46C22.2189 5.96667 21.9989 5.51333 21.6922 5.1C21.3989 4.68667 21.0122 4.35333 20.5322 4.1C20.0522 3.84667 19.4589 3.72 18.7522 3.72C17.8589 3.72 17.1322 3.92667 16.5722 4.34C16.0122 4.74 15.5989 5.26 15.3322 5.9C15.0789 6.54 14.9522 7.21333 14.9522 7.92C14.9522 8.62667 15.0855 9.3 15.3522 9.94C15.6189 10.58 16.0322 11.1 16.5922 11.5C17.1655 11.9 17.8922 12.1 18.7722 12.1ZM34.313 15.16C32.8996 15.16 31.673 14.86 30.633 14.26C29.6063 13.66 28.813 12.82 28.253 11.74C27.7063 10.6467 27.433 9.36 27.433 7.88C27.433 6.77333 27.5863 5.78 27.893 4.9C28.213 4.02 28.673 3.26667 29.273 2.64C29.873 2 30.593 1.51333 31.433 1.18C32.2863 0.833333 33.2463 0.66 34.313 0.66C35.393 0.66 36.373 0.84 37.253 1.2C38.1463 1.56 38.8796 2.10667 39.453 2.84C40.0396 3.56 40.4196 4.47333 40.593 5.58H37.353C37.2596 5.15333 37.0863 4.80667 36.833 4.54C36.5796 4.26 36.2596 4.05333 35.873 3.92C35.4863 3.78667 35.0396 3.72 34.533 3.72C33.8396 3.72 33.253 3.84 32.773 4.08C32.293 4.32 31.9063 4.64667 31.613 5.06C31.3196 5.46 31.1063 5.91333 30.973 6.42C30.8396 6.92667 30.773 7.44667 30.773 7.98C30.773 8.68667 30.893 9.36 31.133 10C31.3863 10.6267 31.7796 11.1333 32.313 11.52C32.8596 11.9067 33.5796 12.1 34.473 12.1C34.993 12.1 35.473 12.0267 35.913 11.88C36.353 11.7333 36.713 11.5067 36.993 11.2C37.273 10.88 37.4396 10.4867 37.493 10.02H33.893V7.26H40.873V7.92C40.873 9.44 40.633 10.74 40.153 11.82C39.673 12.9 38.9463 13.7267 37.973 14.3C37.013 14.8733 35.793 15.16 34.313 15.16ZM49.4934 15.16C48.0401 15.16 46.7801 14.86 45.7134 14.26C44.6601 13.6467 43.8468 12.8 43.2734 11.72C42.7001 10.6267 42.4134 9.36667 42.4134 7.94C42.4134 6.47333 42.7068 5.2 43.2934 4.12C43.8934 3.02667 44.7268 2.18 45.7934 1.58C46.8601 0.966667 48.1201 0.66 49.5734 0.66C51.0001 0.66 52.2401 0.96 53.2934 1.56C54.3601 2.16 55.1868 3 55.7734 4.08C56.3734 5.14667 56.6734 6.40667 56.6734 7.86C56.6734 9.28667 56.3801 10.5533 55.7934 11.66C55.2068 12.7533 54.3734 13.6133 53.2934 14.24C52.2268 14.8533 50.9601 15.16 49.4934 15.16ZM49.5534 12.1C50.4601 12.1 51.1934 11.8933 51.7534 11.48C52.3134 11.0667 52.7201 10.54 52.9734 9.9C53.2268 9.26 53.3534 8.60667 53.3534 7.94C53.3534 7.44667 53.2801 6.95333 53.1334 6.46C53.0001 5.96667 52.7801 5.51333 52.4734 5.1C52.1801 4.68667 51.7934 4.35333 51.3134 4.1C50.8334 3.84667 50.2401 3.72 49.5334 3.72C48.6401 3.72 47.9134 3.92667 47.3534 4.34C46.7934 4.74 46.3801 5.26 46.1134 5.9C45.8601 6.54 45.7334 7.21333 45.7334 7.92C45.7334 8.62667 45.8668 9.3 46.1334 9.94C46.4001 10.58 46.8134 11.1 47.3734 11.5C47.9468 11.9 48.6734 12.1 49.5534 12.1Z" fill="#D1D1D1"/>
</svg>

								</div>
							<# } #>
							<# if ( settings.display_name ) { #>
								<div class="brand-name">Brand Name</div>
							<# } #>
						</a>
					</div>
				</div>
			</div>
		<?php
	}
}
