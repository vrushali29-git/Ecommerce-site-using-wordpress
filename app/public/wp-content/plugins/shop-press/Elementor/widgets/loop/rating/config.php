<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Rating extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-rating';
	}

	public function get_title() {
		return __( 'Product Rating', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-rating';
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
					'selector' => '.sp-loop-rating',
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
					'wrapper'  => '{{WRAPPER}} .sp-loop-rating.sp-modern-rating',
				),

				'star'             => array(
					'label'    => esc_html__( 'Star', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-rating-star',
					'wrapper'  => '{{WRAPPER}} .sp-loop-rating.sp-modern-rating .sp-rating',
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
					'wrapper'  => '{{WRAPPER}} .sp-loop-rating.sp-classic-rating',
				),

				'empty_stars'       => array(
					'label'    => esc_html__( 'Empty Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating::before',
					'wrapper'  => '{{WRAPPER}} .sp-loop-rating.sp-classic-rating',
				),

				'full_stars'        => array(
					'label'    => esc_html__( 'Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} .sp-loop-rating.sp-classic-rating .star-rating',
				),
			),
			array(
				'rating_type' => 'classic' ?? array(),
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

		$this->end_controls_section();

		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		$args = array(
			'rating_type' => $settings['rating_type'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-rating', $args );
		}
	}

	protected function content_template() {
		?>

		<# if ( 'modern' === settings.rating_type ) { #>
			<div class="woocommerce-product-rating sp-loop-rating sp-modern-rating">
				<?php echo '<div class="sp-rating"><span class="sp-rating-star">S</span>4.5</div>'; ?>
			</div>
		<# } #>

		<# if ( 'classic' === settings.rating_type ) { #>
			<div class="woocommerce-product-rating sp-loop-rating sp-classic-rating">
				<div class="star-rating" role="img" aria-label="Rated 4 out of 5">
					<span style="width:80%"></span>
				</div>
			</div>
		<# } #>

		<?php
	}
}
