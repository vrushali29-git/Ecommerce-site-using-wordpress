<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;

use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Thumbnail extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-thumbnail';
	}

	public function get_title() {
		return __( 'Product Thumbnail', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-thumbnail';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-loop-thumbnail', 'sp-products-loop', 'sp-products-loop-rtl' );
		} else {
			return array( 'sp-loop-thumbnail', 'sp-products-loop' );
		}
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-thumbnail',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'image',
			__( 'Image', 'shop-press' ),
			array(
				'Wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-thumbnail a',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item'    => array(
					'label'    => esc_html__( 'Image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-thumbnail a img',
					'wrapper'  => '{{WRAPPER}}',
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

		$_image_sizes = self::get_all_image_sizes();
		$image_sizes  = array();
		foreach ( $_image_sizes as $key => $image_size ) {

			$image_sizes[ $key ] = $image_size['title'] ?? '';
		}
		$image_sizes['full'] = __( 'Full', 'shop-press' );
		$this->add_control(
			'thumb_size',
			array(
				'label'   => esc_html__( 'Thumbnail Size', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $image_sizes,
				'default' => 'woocommerce_thumbnail',
			)
		);
		$this->add_control(
			'thumbnail_type',
			array(
				'label'   => esc_html__( 'Thumbnail Type', 'shop-press' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'single'                => array(
						'title' => esc_html__( 'Single', 'shop-press' ),
						'icon'  => 'eicon-image',
					),
					'change_image_on_hover' => array(
						'title' => esc_html__( 'Change image on hover', 'shop-press' ),
						'icon'  => 'eicon-image-rollover',
					),
					'slider'                => array(
						'title' => esc_html__( 'Slider', 'shop-press' ),
						'icon'  => 'eicon-photo-library',
					),
				),
				'default' => 'single',
				'toggle'  => true,
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Arrows', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'shop-press' ),
				'label_off'    => esc_html__( 'Hide', 'shop-press' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'thumbnail_type' => 'slider',
				),
			)
		);

		$this->add_control(
			'show_nav',
			array(
				'label'        => esc_html__( 'Navigation', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'shop-press' ),
				'label_off'    => esc_html__( 'Hide', 'shop-press' ),
				'return_value' => 'true',
				'default'      => 'false',
				'condition'    => array(
					'thumbnail_type' => 'slider',
				),
			)
		);

		$this->add_control(
			'nav_type',
			array(
				'label'     => esc_html__( 'Navigation Type', 'shop-press' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'bullets_nav' => array(
						'title' => esc_html__( 'Bullets', 'shop-press' ),
						'icon'  => 'eicon-navigation-horizontal',
					),
					'images_nav'  => array(
						'title' => esc_html__( 'Images', 'shop-press' ),
						'icon'  => 'eicon-photo-library',
					),
				),
				'default'   => 'bullets_nav',
				'toggle'    => true,
				'condition' => array(
					'thumbnail_type' => 'slider',
					'show_nav'       => 'true',
				),
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
			'thumb_size'     => $settings['thumb_size'],
			// 'change_image' => $settings['change_image'],
			'thumbnail_type' => $settings['thumbnail_type'],
			'show_arrows'    => $settings['show_arrows'],
			'show_nav'       => $settings['show_nav'],
			'nav_type'       => $settings['nav_type'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-thumbnail', $args );
		}
	}

	protected function content_template() {
		?>
		<#
			var classes = '';
			if ( 'change_image_on_hover' === settings.thumbnail_type ) {
				classes += ' has-gallery';
			} else {
				classes += ' sp-slider-style';
			}
		#>

		<div class="sp-product-thumbnail {{ classes }}">
			<a href="#">
				<# if ( 'slider' === settings.thumbnail_type ) { #>
					<# if ( 'true' === settings.show_arrows ) { #>
						<button class="slick-prev slick-arrow" aria-label="Previous" type="button" style="">Previous</button>
						<button class="slick-next slick-arrow" aria-label="Next" type="button" style="">Next</button>
					<# } #>
					<div>
						<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail tns-item" aria-hidden="true" tabindex="-1">
					</div>
					<# if ( 'true' === settings.show_nav && 'bullets_nav' === settings.nav_type ) { #>
						<ul class="slick-dots" style="" role="tablist">
							<li class="slick-active" role="presentation">
								<button type="button" role="tab" id="slick-slide-control20" aria-controls="slick-slide20" aria-label="1 of 4" tabindex="0" aria-selected="true">1</button>
							</li>
							<li role="presentation">
								<button type="button" role="tab" id="slick-slide-control21" aria-controls="slick-slide21" aria-label="2 of 4" tabindex="-1">2</button>
							</li>
						</ul>
					<# } #>
				<# } else { #>
					<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
					<# if ( 'change_image_on_hover' === settings.thumbnail_type ) { #>
						<div class="sp-product-th-gallery">
							<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
						</div>
					<# } #>
				<# } #>
			</a>
			<# if ('true' === settings.show_nav && 'images_nav' === settings.nav_type ) { #>
				<div
					class="sp-nav-slider sp-slider-style"
				>
					<div class="slick-list">
						<div
							class="slick-track"
						>
							<div
								class="sp-thumb-nav slick-current"
							>
								<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo-1-100x100.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
							</div>
							<div
								class="sp-thumb-nav"
							>
								<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>hoodie-with-zipper-2-100x100.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
							</div>
							<div
								class="sp-thumb-nav"
							>
								<img src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>hoodie-with-pocket-2-100x100.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
							</div>
						</div>
					</div>
				</div>
			<# } #>
		</div>
		<?php
	}
}
