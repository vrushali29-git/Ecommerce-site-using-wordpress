<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Review extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-review';
	}

	public function get_title() {
		return __( 'Product Review Count', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-review';
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
					'selector' => '.sp-product-review',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'review_item',
			__( 'Review', 'shop-press' ),
			array(
				'rev_count' => array(
					'label'    => esc_html__( 'Review Count', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.review-count',
					'wrapper'  => '{{WRAPPER}} .sp-product-review',
				),
				'rev_icon'  => array(
					'label'    => esc_html__( 'Review Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i',
					'wrapper'  => '{{WRAPPER}} .sp-product-review',
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
			'icon',
			array(
				'label' => __( 'Icon', 'shop-press' ),
				'type'  => Controls_Manager::ICONS,
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
			'icon' => $settings['icon'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-review', $args );
		}
	}

	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<div class="sp-product-review">
			{{{ iconHTML.value }}}
			<span class="review-count">
				2
			</span>
		</div>
		<?php
	}
}
