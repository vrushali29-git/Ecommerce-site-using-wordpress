<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class SuggestPrice extends ShopPressWidgets {

	public function get_name() {
		return 'sp-suggest-price';
	}

	public function get_title() {
		return __( 'Suggest price', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-suggest-price';
	}

	public function get_script_depends() {
		return array( 'sp-suggest-price' );
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'wrapper'  => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-suggest-price',
					'wrapper'  => '{{WRAPPER}}',
				),
				'btn_link' => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-suggest-price a.button',
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
				'default' => __( 'Suggest Price', 'shop-press' ),
			)
		);

		$this->add_control(
			'divider1',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'email_address',
			array(
				'label'       => __( 'Email Address', 'shop-press' ),
				'description' => __( 'The email will sent to this address.', 'shop-press' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'you@yourdomain.com', 'shop-press' ),
			)
		);

		$this->add_control(
			'divider2',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'name_placeholder',
			array(
				'label'   => __( 'Name Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Name', 'shop-press' ),
			)
		);

		$this->add_control(
			'email_placeholder',
			array(
				'label'   => __( 'Email Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Email', 'shop-press' ),
			)
		);

		$this->add_control(
			'price_placeholder',
			array(
				'label'   => __( 'Price Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Price', 'shop-press' ),
			)
		);

		$this->add_control(
			'message_placeholder',
			array(
				'label'   => __( 'Message Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Message', 'shop-press' ),
			)
		);

		$this->add_control(
			'submit_btn_text',
			array(
				'label'   => __( 'Submit Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Send', 'shop-press' ),
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
			'email_address'       => $settings['email_address'],
			'btn_text'            => $settings['btn_text'],
			'name_placeholder'    => $settings['name_placeholder'],
			'email_placeholder'   => $settings['email_placeholder'],
			'price_placeholder'   => $settings['price_placeholder'],
			'message_placeholder' => $settings['message_placeholder'],
			'submit_btn_text'     => $settings['submit_btn_text'],
			'id'                  => $this->get_id(),
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/suggest-price', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-suggest-price">
			<a href="#" class="button">{{{ settings.btn_text }}}</a>
		</div>
		<?php
	}
}
