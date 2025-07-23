<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class StockProgressBar extends ShopPressWidgets {

	public function get_name() {
		return 'sp-stock-progress-bar';
	}

	public function get_title() {
		return __( 'Stock Progress Bar', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-progress-bar';
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
					'selector' => '.sp-stock-progress-bar',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'label',
			__( 'Label', 'shop-press' ),
			array(
				'ordered_wrap'          => array(
					'label'    => esc_html__( 'Ordered Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-stock-progress-bar-total-sales',
					'wrapper'  => '{{WRAPPER}} .sp-stock-progress-bar-labels',
				),
				'ordered'               => array(
					'label'    => esc_html__( 'Ordered Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-stock-progress-bar-labels-ordered',
					'wrapper'  => '{{WRAPPER}} .sp-stock-progress-bar-total-sales',
				),
				'order_count'           => array(
					'label'    => esc_html__( 'Ordered Count', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-stock-progress-bar-labels-ordered-count',
					'wrapper'  => '{{WRAPPER}} .sp-stock-progress-bar-total-sales',
				),
				'items_available'       => array(
					'label'    => esc_html__( 'Items Available Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-stock-progress-bar-stock-qty',
					'wrapper'  => '{{WRAPPER}}',
				),
				'items_available_label' => array(
					'label'    => esc_html__( 'Items Available Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-stock-progress-bar-labels-available',
					'wrapper'  => '{{WRAPPER}} .sp-stock-progress-bar-stock-qty',
				),
				'items_available_count' => array(
					'label'    => esc_html__( 'Items Available Count', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-stock-progress-bar-labels-available-count',
					'wrapper'  => '{{WRAPPER}} .sp-stock-progress-bar-stock-qty',
				),
			)
		);

		$this->register_group_styler(
			'bar',
			__( 'Bar', 'shop-press' ),
			array(
				'bar'     => array(
					'label'    => esc_html__( 'Bar', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-stock-progress-bar-percent',
					'wrapper'  => '{{WRAPPER}}',
				),
				'percent' => array(
					'label'    => esc_html__( 'Percent', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-stock-progress-bar-percent-sold',
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

		$this->add_control(
			'show_ordered',
			array(
				'label'        => esc_html__( 'Ordered', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'shop-press' ),
				'label_off'    => esc_html__( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'ordered_label',
			array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Ordered Label', 'shop-press' ),
				'default' => __( 'Ordered:', 'shop-press' ),
			)
		);

		$this->add_control(
			'show_available',
			array(
				'label'        => __( 'Available', 'shop-press' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'available_label',
			array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Available Label', 'shop-press' ),
				'default' => __( 'Items Available:', 'shop-press' ),
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
			'show_ordered'    => $settings['show_ordered'],
			'show_available'  => $settings['show_available'],
			'ordered_label'   => $settings['ordered_label'],
			'available_label' => $settings['available_label'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/stock-progress-bar', $args );
		}
	}

	protected function content_template() {
		?>
		<#
		var total_sales;
		if ( settings.show_ordered ) {
			total_sales = '<div class="sp-stock-progress-bar-total-sales"> <span class="sp-stock-progress-bar-labels-ordered-count">2</span> <span class="sp-stock-progress-bar-labels-ordered">' + settings.ordered_label + '</span> </div>';
		}

		var stock_qty;
		if ( settings.show_available ) {
			stock_qty = '<div class="sp-stock-progress-bar-stock-qty"> <span class="sp-stock-progress-bar-labels-available-count">3</span> <span class="sp-stock-progress-bar-labels-available">' + settings.available_label + '</span> </div>';
		}
		#>
		<div class="sp-stock-progress-bar">
			<div class="sp-stock-progress-bar-labels">
				{{{ total_sales }}}
				{{{ stock_qty }}}
			</div>
			<div class="sp-stock-progress-bar-percent">
				<div class="sp-stock-progress-bar-percent-sold" style="width: 40%"></div>
			</div>
		</div>
		<?php
	}
}
