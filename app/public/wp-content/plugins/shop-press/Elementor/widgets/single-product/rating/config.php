<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Rating extends ShopPressWidgets {

	public function get_name() {
		return 'sp-rating';
	}

	public function get_title() {
		return __( 'Product Rating', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-rating';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-single-rating',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'modern_rating',
			__( 'Rating', 'shop-press' ),
			array(
				'modern_container' => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-rating',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating.sp-modern-rating',
				),
				'star'             => array(
					'label'    => esc_html__( 'Star', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-rating-star',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating.sp-modern-rating .sp-rating',
				),
			),
			array(
				'rating_type' => 'modern' ?? array(),
			)
		);

		$this->register_group_styler(
			'classic_rating',
			__( 'Rating', 'shop-press' ),
			array(
				'classic_container' => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating.sp-classic-rating',
				),
				'empty_stars'       => array(
					'label'    => esc_html__( 'Empty Star', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating::before',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating.sp-classic-rating',
				),
				'full_stars'        => array(
					'label'    => esc_html__( 'Full Star', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating.sp-classic-rating .star-rating',
				),
			),
			array(
				'rating_type' => 'classic' ?? array(),
			)
		);

		$this->register_group_styler(
			'review_counter',
			__( 'Review Counter', 'shop-press' ),
			array(
				'count_star'  => array(
					'label'    => esc_html__( 'Reviews Count', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.count',
					'wrapper'  => '{{WRAPPER}} a.woocommerce-review-link',
				),
				'review_link' => array(
					'label'    => esc_html__( 'Review Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.woocommerce-review-link',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-rating.sp-single-rating',
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
			'rating_type',
			array(
				'label'   => __( 'Type', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'modern',
				'options' => array(
					'modern'  => __( 'Modern', 'shop-press' ),
					'classic' => __( 'Classic', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'show_review_counter',
			array(
				'label'     => esc_html__( 'Show review counter', 'shop-press' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'shop-press' ),
				'label_on'  => esc_html__( 'On', 'shop-press' ),
				'default'   => 'yes',
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
			'rating_type'         => $settings['rating_type'],
			'show_review_counter' => $settings['show_review_counter'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-rating', $args );
		}
	}

	protected function content_template() {
		?>

		<# if ( 'modern' === settings.rating_type ) { #>
			<div class="woocommerce-product-rating sp-single-rating sp-modern-rating">
				<div class="sp-rating">
					<span class="sp-rating-star">S</span>
					4.0
				</div>
				<# if ( 'yes' === settings.show_review_counter ) { #>
					<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
						<span class="count">1</span>
						review
					</a>
				<# } #>
			</div>
		<# } #>

		<# if ( 'classic' === settings.rating_type ) { #>
			<div class="woocommerce-product-rating sp-single-rating sp-classic-rating">
				<div class="star-rating" role="img" aria-label="Rated 4 out of 5">
					<span style="width:80%"></span>
				</div>
			<# if ( 'yes' === settings.show_review_counter ) { #>
				<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
					<span class="count">1</span>
					review
				</a>
			<# } #>
			</div>
		<# } #>
		<?php
	}
}


