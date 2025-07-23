<?php

/**
 * Icon Box module config.
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
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_IconBox extends Widget_Base {

	public function get_name() {
		return 'kata-plus-icon-box';
	}

	public function get_title() {
		return esc_html__( 'Icon Box', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-icon-box';
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-icon-box' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_Tab_content',
			array(
				'label' => esc_html__( 'Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'iconbox_layout',
			array(
				'label'       => __( 'Layout', 'kata-plus' ),
				'description' => __( 'Set vertical or horizontal layout.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'horizontal',
				'options'     => array(
					'horizontal' => __( 'Vertical', 'kata-plus' ),
					'vertical'   => __( 'Horizontal', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'iconbox_aligne',
			array(
				'label'       => __( 'Alignment', 'kata-plus' ),
				'description' => __( 'Set box alignment', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dfalt',
				'options'     => array(
					'dfalt' => __( 'Center', 'kata-plus' ),
					'left'  => __( 'Left', 'kata-plus' ),
					'right' => __( 'Right', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'iconbox_title',
			array(
				'label'   => __( 'Title', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your Title', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'iconbox_title_tag',
			array(
				'label'       => __( 'Title Tag', 'kata-plus' ),
				'description' => __( 'Set a certain tag for title.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'p', 'kata-plus' ),
					'span' => __( 'span', 'kata-plus' ),
					'div'  => __( 'div', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'iconbox_subtitle',
			array(
				'label'   => __( 'Subtitle', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your Subtitle', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'iconbox_subtitle_tag',
			array(
				'label'       => __( 'SubTitle Tag', 'kata-plus' ),
				'description' => __( 'Set a certain tag for subtitle.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h4',
				'options'     => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'p', 'kata-plus' ),
					'span' => __( 'span', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'iconbox_desc',
			array(
				'label'   => __( 'Description', 'kata-plus' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'kata-plus' ),
				'rows'    => 10,
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'icon_box_list',
			array(
				'label' => esc_html__( 'List Content', 'kata-plus' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'icon_box_lists',
			array(
				'label'         => esc_html__( 'Add List', 'kata-plus' ),
				'description'   => esc_html__( 'Add several individual items as list items.', 'kata-plus' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'title_field'   => '{{{ icon_box_list }}}',
			)
		);
		$this->add_control(
			'symbol',
			array(
				'label'       => __( 'Icon Source', 'kata-plus' ),
				'description' => __( 'Set icon source, Kata icons, custom image or custom svg', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'icon',
				'options'     => array(
					'icon'   => __( 'Kata Icons', 'kata-plus' ),
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'SVG', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'iconbox_icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/alarm-clock',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'iconbox_image',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'symbol' => array(
						'imagei',
						'svg',
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'iconbox_image',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'symbol' => array(
						'imagei',
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'retina_image',
			array(
				'label'     => __( 'Choose 2x Retina Image (Optional)', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'symbol' => array(
						'imagei',
					),
				),
			)
		);
		$this->add_control(
			'iconbox_number',
			array(
				'label'     => __( 'Number', 'kata-plus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( '1', 'kata-plus' ),
				'condition' => array(
					'symbol' => array(
						'numberi',
					),
				),
			)
		);
		$this->add_control(
			'iconbox_link',
			array(
				'label'         => __( 'Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'kata-plus' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
			)
		);
		$this->add_control(
			'link_to_whole_wrapper',
			array(
				'label'        => __( 'Make the Whole Wrapper Clickable', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'kata-plus' ),
				'label_off'    => __( 'Off', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'linkcnt',
			array(
				'label'   => __( 'Link Text', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'link_icon',
			array(
				'label' => esc_html__( 'Link Icon', 'kata-plus' ),
				'type'  => 'kata_plus_icons',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'Parent',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'styler_container',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Content', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'svg_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'To style the SVGs please go to Style SVG tab.', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'styler_icon_wrap',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox-icon-wrap',
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
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-iconbox-icon-wrap .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'styler_image_st',
			array(
				'label'     => esc_html__( 'Image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.imagei img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'imagei',
					),
				),
			)
		);
		$this->add_control(
			'styler_content_wrapper',
			array(
				'label'     => esc_html__( 'Content Wrapper', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-iconbox .kata-plus-iconbox-cntt',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'iconbox_layout' => 'vertical',
				),
			)
		);
		$this->add_control(
			'styler_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox [data-class="ck-icon-box-title"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_subtitle',
			array(
				'label'    => esc_html__( 'Subtitle', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox [data-class="ck-icon-box-subtitle"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_desc',
			array(
				'label'    => esc_html__( 'Description', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox [data-class="ck-icon-box-desc"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_desc_ul',
			array(
				'label'    => esc_html__( 'List Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.icon-box-lists',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_desc_li',
			array(
				'label'    => esc_html__( 'List Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.icon-box-list-item',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_rm',
			array(
				'label'    => esc_html__( 'Read more', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox a[data-class="icon-box-readmore"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_rm_icon',
			array(
				'label'    => esc_html__( 'Read more icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-iconbox a[data-class="icon-box-readmore"] i.kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_rn',
			array(
				'label'     => esc_html__( 'Number', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-iconbox-icon-wrap.numberi p',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'numberi',
					),
				),
			)
		);
		// Style options End
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_svg',
			array(
				'label' => esc_html__( 'SVG', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'important_note2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Because certain SVGs use different tags for styling, you need to use the options below to style the uploaded SVG. They SVG tab in the Styler is there to do this.', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_svg_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_path',
			array(
				'label'    => esc_html__( 'Path', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon path',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_rect',
			array(
				'label'    => esc_html__( 'Rect', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon rect',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_line',
			array(
				'label'    => esc_html__( 'Line', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon line',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_circel',
			array(
				'label'    => esc_html__( 'Circle', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon circle',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_ellipse',
			array(
				'label'    => esc_html__( 'Ellipse', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon ellipse',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
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
