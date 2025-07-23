<?php

/**
 * Testimonials module config.
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
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_Testimonials extends Widget_Base {

	public function get_name() {
		return 'kata-plus-testimonials';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-testimonial-carousel';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-owlcarousel', 'kata-plus-owl', 'kata-jquery-enllax' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-owlcarousel', 'kata-plus-owl', 'kata-plus-testimonials' );
	}

	protected function register_controls() {
		$args        = array(
			'orderby'     => 'date',
			'order'       => 'DESC',
			'post_type'   => 'kata_testimonial',
			'post_status' => 'publish',
		);
		$posts_array = get_posts( $args );
		if ( ! empty( $posts_array ) ) {
			$post_names = $post_ids = array( '' );
			foreach ( $posts_array as $post_array ) {
				$post_names[] = $post_array->post_title;
				$post_ids[]   = $post_array->ID;
			}
			$posts_array = array_combine( $post_ids, $post_names );
		} else {
			$posts_array = array();
		}
		$this->start_controls_section(
			'content_tes',
			array(
				'label' => esc_html__( 'Content', 'kata-plus' ),
			)
		);
		$this->add_control(
			'testp_source',
			array(
				'label'        => __( 'Read from testimonial post type', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'posts_array',
			array(
				'label'     => esc_html__( 'Select Posts', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $posts_array,
				'condition' => array(
					'testp_source' => array( 'yes' ),
				),
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'inc_owl_img',
			array(
				'label' => __( 'Choose Image', 'kata-plus' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
		$repeater->add_control(
			'rate',
			array(
				'label'   => __( 'Rate', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'       => __( 'None', 'kata-plus' ),
					'one'        => __( '1 Star', 'kata-plus' ),
					'one_half'   => __( '1.5 Star', 'kata-plus' ),
					'two'        => __( '2 Star', 'kata-plus' ),
					'two_half'   => __( '2.5 Star', 'kata-plus' ),
					'three'      => __( '3 Star', 'kata-plus' ),
					'three_half' => __( '3.5 Star', 'kata-plus' ),
					'four'       => __( '4 Star', 'kata-plus' ),
					'four_half'  => __( '4.5 Star', 'kata-plus' ),
					'five'       => __( '5 Star', 'kata-plus' ),
				),
			)
		);
		$repeater->add_control(
			'symbol',
			array(
				'label'   => __( 'Icon Source', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'   => __( 'Kata Icons', 'kata-plus' ),
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$repeater->add_control(
			'inc_owl_icon',
			array(
				'label'     => __( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/quote-left',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$repeater->add_control(
			'inc_owl_image',
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
		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'inc_owl_image',
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
		$repeater->add_control(
			'inc_owl_name',
			array(
				'label'   => __( 'Name', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Jane Smith', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'inc_owl_pos',
			array(
				'label'   => __( 'Position', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'CEO', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'inc_owl_cnt',
			array(
				'label'   => __( 'Content', 'kata-plus' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 10,
				'default' => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'inc_owl_date',
			array(
				'label'       => __( 'Date', 'kata-plus' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => '',
				'placeholder' => __( 'February 19, 2018', 'kata-plus' ),
			)
		);
		$repeater->add_control(
			'show_time',
			array(
				'label'        => __( 'Show The Time', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$repeater->add_control(
			'inc_owl_shape',
			array(
				'label'    => esc_html__( 'Shape', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}} .kata-plus-shape',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$repeater->add_control(
			'inc_owl_html',
			array(
				'label'    => __( 'Custom HTML', 'kata-plus' ),
				'type'     => Controls_Manager::CODE,
				'language' => 'html',
				'rows'     => 20,
			)
		);
		$this->add_control(
			'testimonials',
			array(
				'label'       => __( 'Testimonials', 'kata-plus' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'inc_owl_icon' => __( 'themify/quote-left', 'kata-plus' ),
						'inc_owl_name' => __( 'Emily Parker', 'kata-plus' ),
						'inc_owl_pos'  => __( 'Company CEO', 'kata-plus' ),
						'inc_owl_cnt'  => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
					),
					array(
						'inc_owl_icon' => __( 'themify/quote-left', 'kata-plus' ),
						'inc_owl_name' => __( 'Mary Taylor', 'kata-plus' ),
						'inc_owl_pos'  => __( 'Company CEO', 'kata-plus' ),
						'inc_owl_cnt'  => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
					),
					array(
						'inc_owl_icon' => __( 'themify/quote-left', 'kata-plus' ),
						'inc_owl_name' => __( 'Eric Walker', 'kata-plus' ),
						'inc_owl_pos'  => __( 'Company CEO', 'kata-plus' ),
						'inc_owl_cnt'  => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
					),
					array(
						'inc_owl_icon' => __( 'themify/quote-left', 'kata-plus' ),
						'inc_owl_name' => __( 'Emily Parker', 'kata-plus' ),
						'inc_owl_pos'  => __( 'Company CEO', 'kata-plus' ),
						'inc_owl_cnt'  => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
					),

				),
				'title_field' => '{{{ inc_owl_name }}} {{{ inc_owl_pos }}}',
				'condition'   => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);

		$this->end_controls_section();

		// Shape option
		$this->start_controls_section(
			'shape_section',
			array(
				'label' => esc_html__( 'Shape', 'kata-plus' ),
			)
		);

		$repeater2 = new Repeater();
		$repeater2->add_control(
			'pttest_shape',
			array(
				'label'    => esc_html__( 'Shape', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}} .kata-plus-shape',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'pttesti',
			array(
				'label'     => __( 'Custom Shape', 'kata-plus' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater2->get_controls(),
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->end_controls_section();

		// owl option
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Carousel Settings', 'kata-plus' ),
			)
		);
		$this->add_responsive_control(
			'inc_owl_item',
			array(
				'label'       => __( 'Visible Items', 'kata-plus' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 5,
				'step'        => 1,
				'default'     => 3,
				'description' => __( 'Varies between 1/5', 'kata-plus' ),
			)
		);
		$this->add_control(
			'inc_owl_spd',
			array(
				'label'       => __( 'Slide Speed', 'kata-plus' ),
				'description' => __( 'Varies between 500/6000', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 500,
						'max'  => 6000,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 5000,
				),
			)
		);
		$this->add_control(
			'inc_owl_smspd',
			array(
				'label'       => __( 'Smart Speed', 'kata-plus' ),
				'description' => __( 'Varies between 500/6000', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 500,
						'max'  => 6000,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 1000,
				),
			)
		);
		$this->add_responsive_control(
			'inc_owl_stgpad',
			array(
				'label'       => __( 'Stage Padding', 'kata-plus' ),
				'description' => __( 'Varies between 0/400', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
			)
		);
		$this->add_responsive_control(
			'inc_owl_margin',
			array(
				'label'       => __( 'Margin', 'kata-plus' ),
				'description' => __( 'Varies between 0/400', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 20,
				),
			)
		);
		$this->add_control(
			'inc_owl_arrow',
			array(
				'label'        => __( 'Prev/Next Arrows', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'inc_owl_prev',
			array(
				'label'     => __( 'Left Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'font-awesome/angle-left',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_nxt',
			array(
				'label'     => __( 'Right Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'font-awesome/angle-right',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_pag',
			array(
				'label'        => __( 'Pagination', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'inc_owl_pag_num',
			array(
				'label'     => __( 'Pagination Layout', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'dots'         => __( 'Bullets', 'kata-plus' ),
					'dots-num'     => __( 'Numbers', 'kata-plus' ),
					'dots-and-num' => __( 'Progress bar', 'kata-plus' ),
				),
				'default'   => 'dots',
				'condition' => array(
					'inc_owl_pag' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_loop',
			array(
				'label'        => __( 'Slider loop', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);
		$this->add_control(
			'inc_owl_autoplay',
			array(
				'label'        => __( 'Autoplay', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);
		$this->add_control(
			'inc_owl_center',
			array(
				'label'        => __( 'Center Item', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'inc_owl_rtl',
			array(
				'label'        => __( 'RTL', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'inc_owl_vert',
			array(
				'label'        => __( 'Vertical Slider', 'kata-plus' ),
				'description'  => __( 'This option works only when "Items Per View" is set to 1.', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);
		$this->end_controls_section();

		// Styles
		$this->start_controls_section(
			'widget_style_parent',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_widget_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_widget_stage',
			array(
				'label'    => esc_html__( 'Carousel Stage', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.owl-stage-outer',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'testimonials_style',
			array(
				'label' => esc_html__( 'Items Per View', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_item',
			array(
				'label'    => esc_html__( 'Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-testimonial',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_thumbnail_wrapper',
			array(
				'label'    => esc_html__( 'Image/Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-img-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icon_image_wrapper',
			array(
				'label'    => esc_html__( 'Image Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icon_image',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-img img',
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
				'selector' => '.kata-plus-testimonials .kata-plus-icon',
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
				'selector' => '.kata-plus-testimonials .kata-plus-icon .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_content_wrapper',
			array(
				'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-testimonial-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name_pos_wrap',
			array(
				'label'    => esc_html__( 'Name & Position Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .name-pos-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-name-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_pos',
			array(
				'label'    => esc_html__( 'Position', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-pos',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_cnt',
			array(
				'label'    => esc_html__( 'Content', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-cnt',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_date',
			array(
				'label'    => esc_html__( 'Date', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kata-plus-date',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_stars_wrapper',
			array(
				'label'    => esc_html__( 'Stars Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kt-ts-stars-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_stars',
			array(
				'label'    => esc_html__( 'Stars', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kt-ts-stars-wrapper .kt-ts-star',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_full_star',
			array(
				'label'    => esc_html__( 'Full Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kt-ts-stars-wrapper .kt-ts-star.kt-star-full',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_half_star',
			array(
				'label'    => esc_html__( 'Half Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kt-ts-stars-wrapper .kt-ts-star.kt-ts-star-half',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_empty_star',
			array(
				'label'    => esc_html__( 'Empty Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .kt-ts-stars-wrapper .kt-ts-star-empty',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'active_testimonials_style',
			array(
				'label' => esc_html__( 'Active Item', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'active_styler_item',
			array(
				'label'    => esc_html__( 'Active Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-testimonial',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_thumbnail_wrapper',
			array(
				'label'    => esc_html__( 'Image/Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-img-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_icon_image_wrapper',
			array(
				'label'    => esc_html__( 'Image Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_icon_image',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-img img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_icon_wrapper',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'icon_style_error_2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'In order to change the color of icons, Open Styler and navigate to the SVG tab (all Kata icons is SVG)', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'active_styler_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-icon .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_content_wrapper',
			array(
				'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-testimonial-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_name',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-name-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_pos',
			array(
				'label'    => esc_html__( 'Position', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-pos',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_cnt',
			array(
				'label'    => esc_html__( 'Content', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-cnt',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_date',
			array(
				'label'    => esc_html__( 'Date', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kata-plus-date',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_stars_wrapper',
			array(
				'label'    => esc_html__( 'Stars Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kt-ts-stars-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_stars',
			array(
				'label'    => esc_html__( 'Stars', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kt-ts-stars-wrapper .kt-ts-star',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_full_star',
			array(
				'label'    => esc_html__( 'Full Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kt-ts-stars-wrapper .kt-ts-star.kt-star-full',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_half_star',
			array(
				'label'    => esc_html__( 'Half Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kt-ts-stars-wrapper .kt-ts-star.kt-ts-star-half',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'active_styler_empty_star',
			array(
				'label'    => esc_html__( 'Empty Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active .kt-ts-stars-wrapper .kt-ts-star-empty',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'center_testimonials_style',
			array(
				'label' => esc_html__( 'Center Item', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'center_styler_item',
			array(
				'label'    => esc_html__( 'Center Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-testimonial',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_thumbnail_wrapper',
			array(
				'label'    => esc_html__( 'Image/Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-img-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_icon_image_wrapper',
			array(
				'label'    => esc_html__( 'Image Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_icon_image',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-img img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_icon_wrapper',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'icon_style_error_3',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'In order to change the color of icons, Open Styler and navigate to the SVG tab (all Kata icons is SVG)', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'center_styler_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-icon .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_content_wrapper',
			array(
				'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-testimonial-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_name',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-name-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_pos',
			array(
				'label'    => esc_html__( 'Position', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-pos',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_cnt',
			array(
				'label'    => esc_html__( 'Content', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-cnt',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_date',
			array(
				'label'    => esc_html__( 'Date', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kata-plus-date',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_stars_wrapper',
			array(
				'label'    => esc_html__( 'Stars Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kt-ts-stars-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_stars',
			array(
				'label'    => esc_html__( 'Stars', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kt-ts-stars-wrapper .kt-ts-star',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_full_star',
			array(
				'label'    => esc_html__( 'Full Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kt-ts-stars-wrapper .kt-ts-star.kt-star-full',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_half_star',
			array(
				'label'    => esc_html__( 'Half Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kt-ts-stars-wrapper .kt-ts-star.kt-ts-star-half',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'center_styler_empty_star',
			array(
				'label'    => esc_html__( 'Empty Star', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-item.active.center .kt-ts-stars-wrapper .kt-ts-star-empty',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label' => esc_html__( 'Carousel Arrows', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_arrow_wrapper',
			array(
				'label'    => esc_html__( 'Slider Arrows Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-nav',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_arrow_left_wrapper',
			array(
				'label'    => esc_html__( 'Left Arrow Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-nav .owl-prev',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_arrow_left',
			array(
				'label'    => esc_html__( 'Left Arrow', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => 'i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .owl-prev',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_arrow_right_wrapper',
			array(
				'label'    => esc_html__( 'Right Arrow Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-nav .owl-next',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_arrow_right',
			array(
				'label'    => esc_html__( 'Right Arrow', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => 'i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .owl-next',
				'condition' => array(
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label' => esc_html__( 'Carousel Pagination', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'inc_owl_pag' => array(
						'true',
					),
				),
			)
		);

		$this->add_control(
			'styler_boolets',
			array(
				'label'    => esc_html__( 'Pagination Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-dots',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_pag' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_boolet',
			array(
				'label'    => esc_html__( 'Bullets', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-dots .owl-dot',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_pag' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'styler_boolet_active',
			array(
				'label'    => esc_html__( 'Active Bullet', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-testimonials .owl-dots .owl-dot.active',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'inc_owl_pag' => array(
						'true',
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_progress_bar',
			array(
				'label'     => esc_html__( 'Progress Bar', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'inc_owl_pag_num' => 'dots-and-num',
				),
			)
		);
		$this->add_control(
			'styler_progress_wraper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl-progress-bar',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_progress',
			array(
				'label'    => esc_html__( 'Progress Bar', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl-progress-bar .kata-progress-bar-inner',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_progress_min_number',
			array(
				'label'    => esc_html__( 'Start Number', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl-progress-bar .minitems',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_progress_max_number',
			array(
				'label'    => esc_html__( 'End Number', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl-progress-bar .maxitems',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
}
