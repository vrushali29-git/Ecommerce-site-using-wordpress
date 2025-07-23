<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class AdditionalFields extends ShopPressWidgets {

	public function get_name() {
		return 'sp-checkout-additional-fields';
	}

	public function get_title() {
		return __( 'Additional Fields', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-additional-fields';
	}

	public function get_categories() {
		return array( 'sp_woo_checkout' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-additional-fields',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'title',
			__( 'Title', 'shop-press' ),
			array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-additional-fields h3',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'textarea',
			__( 'Textarea', 'shop-press' ),
			array(
				'textarea' => array(
					'label'    => esc_html__( 'Textarea', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'textarea#order_comments ',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
		$this->register_group_styler(
			'label',
			__( 'Label', 'shop-press' ),
			array(
				'label'   => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#order_comments_field label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'require' => array(
					'label'    => esc_html__( 'Require', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#order_comments_field label .optional',
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
			'hide_additional_fields_title',
			array(
				'label' => __( 'Hide Title', 'shop-press' ),
				'type'  => Controls_Manager::SWITCHER,
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
			'hide_title' => $settings['hide_additional_fields_title'],
		);

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-additional-fields', $args );
		}
	}
}
