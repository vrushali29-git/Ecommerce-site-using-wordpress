<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Plugin;
use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class OnSale extends ShopPressWidgets {

	public function get_name() {
		return 'sp-single-onsale';
	}

	public function get_title() {
		return __( 'Sale Badge', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-sale-badge';
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
					'selector' => '.sp-onsale',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'badge',
			__( 'Badge', 'shop-press' ),
			array(
				'heading' => array(
					'label'    => esc_html__( 'Badge', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.onsale',
					'wrapper'  => '{{WRAPPER}} .sp-onsale',
				),
				'icon'    => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i',
					'wrapper'  => '{{WRAPPER}} .sp-onsale span.onsale',
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
			'label',
			array(
				'label'     => __( 'Label', 'shop-press' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Sale', 'shop-press' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'cart_icon',
			array(
				'label'            => __( 'Icon', 'shop-press' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'cart_icon_pos',
			array(
				'label'   => __( 'Icon Position', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => array(
					'before' => __( 'Before', 'shop-press' ),
					'after'  => __( 'After', 'shop-press' ),
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
			'label'         => $settings['label'],
			'cart_icon'     => $settings['cart_icon'],
			'cart_icon_pos' => $settings['cart_icon_pos'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-sale-badge', $args );
		}
	}

	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.cart_icon, { 'aria-hidden': true }, 'i' , 'object' );
		var render_icon;
		var icon_pos = settings.cart_icon_pos;
		var label = settings.label;
		var icon = iconHTML.value ?? '';

		if ( 'before' === icon_pos ) {
			render_icon = icon + label;
		}

		if ( 'after' === icon_pos ) {
			render_icon = label + icon;
		}
		#>
		<div class="sp-onsale">
			<span class="onsale">{{{ render_icon }}}</span>
		</div>
		<?php
	}
}
