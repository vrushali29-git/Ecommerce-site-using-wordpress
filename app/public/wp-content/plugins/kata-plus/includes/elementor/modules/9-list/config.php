<?php
/**
 * List module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class Kata_Plus_List extends Widget_Base {
	public function get_name() {
		return 'kata-plus-list';
	}

	public function get_title() {
		return esc_html__( 'List', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-editor-list-ul';
	}

	public function get_style_depends() {
		return array( 'kata-plus-list' );
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	protected function register_controls() {

		// Content options Start
		$this->start_controls_section(
			'list_content',
			array(
				'label' => __( 'Kata List', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'list_title',
			array(
				'label'   => __( 'Title', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Title', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'list_description',
			array(
				'label'   => __( 'Description ', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Description', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'list_link',
			array(
				'label'         => __( 'Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'kata-plus' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$repeater->add_control(
			'list_icon',
			array(
				'label'   => __( 'Icon', 'kata-plus' ),
				'type'    => 'kata_plus_icons',
				'default' => 'font-awesome/wordpress',
			)
		);

		$this->add_control(
			'list',
			array(
				'label'       => __( 'Items', 'kata-plus' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'list_title'       => __( 'Themes', 'kata-plus' ),
						'list_description' => __( 'Want to learn how to start theming WordPress?', 'kata-plus' ),
					),
					array(
						'list_title'       => __( 'Plugins', 'kata-plus' ),
						'list_description' => __( 'Ready to dive deep into the world of plugin authoring?', 'kata-plus' ),
					),
				),
				'title_field' => '{{{ list_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_container',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_item',
			array(
				'label'    => esc_html__( 'Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list li',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_item_first_item',
			array(
				'label'    => esc_html__( 'First Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list li:first-child',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_item_last_item',
			array(
				'label'    => esc_html__( 'Last Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list li:last-child',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-lists .list-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_description',
			array(
				'label'    => esc_html__( 'Description', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list li .list-description',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'styler_icon_wrapper',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list .list-icon-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'icon_style_error',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'In order to change the color of icons, Open Styler and navigate to the SVG tab (all Kata icons is SVG)', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'styler_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-list .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		// Style options End
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
}
