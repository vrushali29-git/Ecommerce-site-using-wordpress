<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ShopPress\Elementor\ShopPressWidgets;

class Sharing extends ShopPressWidgets {

	public function get_name() {
		return 'sp-sharing';
	}

	public function get_title() {
		return __( 'Product Sharing', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-sharing';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	// public function get_style_depends() {
	// return array( 'sp-sharing' );
	// }

	// public function get_script_depends() {
	// return array( 'sp-sharing' );
	// }

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-sharing',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'socials',
			__( 'Socials', 'shop-press' ),
			array(
				'link_container' => array(
					'label'    => esc_html__( 'Social Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-sharing-button',
					'wrapper'  => '{{WRAPPER}} .sp-sharing',
				),
				'link'           => array(
					'label'    => esc_html__( 'Share Icon Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-sharing-label',
					'wrapper'  => '{{WRAPPER}} .sp-sharing .sp-sharing-button',
				),
				'link_icon'      => array(
					'label'    => esc_html__( 'Share Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'path',
					'wrapper'  => '{{WRAPPER}} .sp-sharing .sp-sharing-button .sp-sharing-icon',
				),
				'items_wrapper'  => array(
					'label'    => esc_html__( 'Sharing Items Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ul',
					'wrapper'  => '{{WRAPPER}} .sp-sharing',
				),
				'sharing_items'  => array(
					'label'    => esc_html__( 'Sharing Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li.sp-sharing-item ',
					'wrapper'  => '{{WRAPPER}} ul',
				),
				'item_text'      => array(
					'label'    => esc_html__( 'Item Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} li.sp-sharing-item',
				),
				'item_icon'      => array(
					'label'    => esc_html__( 'Item Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i',
					'wrapper'  => '{{WRAPPER}} li.sp-sharing-item a',
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
			'type',
			array(
				'label'   => __( 'Type', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'       => __( 'Icon', 'shop-press' ),
					'label'      => __( 'Label', 'shop-press' ),
					'icon-label' => __( 'Icon + Label', 'shop-press' ),
				),
				'default' => 'icon-label',
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Share',
			)
		);

		$share_url_patterns   = sp_get_social_share_links();
		$social_share_options = array_column( $share_url_patterns, 'title', 'social' );

		$repeater = new Repeater();
		$repeater->add_control(
			'social_item',
			array(
				'label'   => __( 'Social', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $social_share_options,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label' => __( 'Title', 'shop-press' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'social_class',
			array(
				'label' => __( 'Custom Class', 'shop-press' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'shop-press' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'links',
			array(
				'label'       => __( 'List', 'shop-press' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'social_item' => 'x',
						'title'       => 'X',
					),
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
			'type'  => $settings['type'],
			'label' => $settings['label'],
			'links' => $settings['links'],
		);

		sp_load_builder_template( 'single-product/product-sharing', $args );
	}
}
