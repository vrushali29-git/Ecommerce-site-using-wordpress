<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;
use ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

class RecentlyViewedProducts extends ShopPressWidgets {

	public function get_name() {
		return 'sp-recently-viewed-products';
	}

	public function get_title() {
		return __( 'Recently Viewed Products', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-recently-viewed-products';
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$product_loop_status = sp_is_template_active( 'products_loop' );

		if ( $product_loop_status ) {

			$templates = Templates\Utils::get_loop_builder_templates();
			$this->add_control(
				'template_id',
				array(
					'label'   => __( 'Template', 'shop-press' ),
					'type'    => Controls_Manager::SELECT2,
					'options' => $templates,
				)
			);

			$this->add_control(
				'template_divider',
				array(
					'type' => Controls_Manager::DIVIDER,
				)
			);
		}

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Limit', 'shop-press' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
			)
		);

		$this->add_control(
			'columns',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Columns', 'shop-press' ),
				'options'   => array(
					'1' => esc_html__( '1', 'shop-press' ),
					'2' => esc_html__( '2', 'shop-press' ),
					'3' => esc_html__( '3', 'shop-press' ),
					'4' => esc_html__( '4', 'shop-press' ),
					'5' => esc_html__( '5', 'shop-press' ),
					'6' => esc_html__( '6', 'shop-press' ),
				),
				'default'   => '4',
				'condition' => array(
					'carousel!' => 'true',
				),
			)
		);

		$this->add_control(
			'custom_heading',
			array(
				'label'        => esc_html__( 'Custom Heading', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'shop-press' ),
				'label_on'     => esc_html__( 'Show', 'shop-press' ),
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'products_heading',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Heading', 'shop-press' ),
				'condition' => array(
					'custom_heading' => 'true',
				),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'     => __( 'Heading HTML Tag', 'shop-press' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => array(
					'h1'   => __( 'h1', 'shop-press' ),
					'h2'   => __( 'h2', 'shop-press' ),
					'h3'   => __( 'h3', 'shop-press' ),
					'h4'   => __( 'h4', 'shop-press' ),
					'h5'   => __( 'h5', 'shop-press' ),
					'h6'   => __( 'h6', 'shop-press' ),
					'div'  => __( 'div', 'shop-press' ),
					'span' => __( 'span', 'shop-press' ),
				),
				'condition' => array(
					'custom_heading' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->carousel_options();

		$this->carousel_stylers();

		$this->custom_heading_stylers();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		if ( $this->is_editor() ) {
			echo '<script>
			jQuery(document).ready(function(){
				typeof sp_product_slider_init === "function" && sp_product_slider_init();
				typeof sp_slider_init === "function" && sp_slider_init();
			});
			</script>';
		}

		$args = array(
			'limit'            => $settings['limit'],
			'columns'          => $settings['columns'],
			'template_id'      => $settings['template_id'],
			'carousel'         => $settings['carousel'],
			'carousel_columns' => $settings['carousel_columns'],
			'slide_speed'      => $settings['slide_speed'],
			'show_controls'    => $settings['show_controls'],
			'autoplay'         => $settings['autoplay'],
			'autoplay_speed'   => $settings['autoplay_speed'],
			'carousel_loop'    => $settings['carousel_loop'],
			'slider_rows'      => $settings['slider_rows'],
			'custom_heading'   => $settings['custom_heading'],
			'products_heading' => $settings['products_heading'],
			'heading_tag'      => $settings['heading_tag'],
		);

		sp_load_builder_template( 'general/recently-viewed-products', $args );
	}
}
