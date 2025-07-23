<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class CallForPrice extends ShopPressWidgets {

	public function get_name() {
		return 'sp-call-for-price';
	}

	public function get_title() {
		return __( 'Call For Price', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-call-for-price';
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
					'selector' => '.sp-call-for-price',
					'wrapper'  => '{{WRAPPER}}',
				),
				'link'    => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-call-for-price a',
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
			'btn_text',
			array(
				'label'   => __( 'Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Call for price', 'shop-press' ),
			)
		);

		$this->add_control(
			'btn_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'btn_phone_number',
			array(
				'label'   => __( 'Phone Number', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '999-999-9999', 'shop-press' ),
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
			'btn_text'         => $settings['btn_text'],
			'btn_phone_number' => $settings['btn_phone_number'],
		);

		sp_load_builder_template( 'single-product/call-for-price', $args );
	}

	protected function content_template() {
		?>
		<div class="sp-call-for-price">
			<a href="tel:{{{ settings.btn_phone_number }}}" class="button">{{{ settings.btn_text }}}</a>
		</div>
		<?php
	}
}
