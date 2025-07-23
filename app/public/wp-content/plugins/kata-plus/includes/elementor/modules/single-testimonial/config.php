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

class Kata_Plus_Single_Testimonial extends Widget_Base {
	public function get_name() {
		return 'kata-plus-single-testimonials';
	}

	public function get_title() {
		return esc_html__( 'Single Testimonial', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-single-testimonial';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-testimonials' );
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
				'multiple'  => false,
				'options'   => $posts_array,
				'condition' => array(
					'testp_source' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'inc_owl_img',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'rate',
			array(
				'label'     => __( 'Rate', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
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
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'symbol',
			array(
				'label'     => __( 'Icon Source', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => array(
					'icon'   => __( 'Kata Icons', 'kata-plus' ),
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_icon',
			array(
				'label'     => __( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'font-awesome/quote-left',
				'condition' => array(
					'symbol'        => array(
						'icon',
					),
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_image',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'symbol'        => array(
						'imagei',
						'svg',
					),
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'inc_owl_image',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'symbol'        => array(
						'imagei',
						'svg',
					),
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_name',
			array(
				'label'     => __( 'Name', 'kata-plus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Jane Smith', 'kata-plus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_pos',
			array(
				'label'     => __( 'Position', 'kata-plus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'CEO', 'kata-plus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_cnt',
			array(
				'label'     => __( 'Content', 'kata-plus' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 10,
				'default'   => __( 'would like to highly recommend Kata to anyone looking for a designer who they can trust to produce a beautiful result in their home.', 'kata-plus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_date',
			array(
				'label'       => __( 'Date', 'kata-plus' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => '',
				'placeholder' => __( 'February 19, 2018', 'kata-plus' ),
				'condition'   => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'show_time',
			array(
				'label'        => __( 'Show The Time', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_shape',
			array(
				'label'     => esc_html__( 'Shape', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '{{CURRENT_ITEM}} .kata-plus-shape',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'testp_source!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_html',
			array(
				'label'     => __( 'Custom HTML', 'kata-plus' ),
				'type'      => Controls_Manager::CODE,
				'language'  => 'html',
				'rows'      => 20,
				'condition' => array(
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
				'selector' => '.kata-plus-single-testimonial',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'testimonials_style',
			array(
				'label' => esc_html__( 'Item', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_item',
			array(
				'label'    => esc_html__( 'Item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-single-testimonial .kata-plus-testimonial',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-img-wrap',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-img',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-img img',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-icon',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-icon .kata-icon',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-testimonial-content',
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
				'selector' => '.kata-plus-single-testimonial .name-pos-wrap',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-name-wrap',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-pos',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-cnt',
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
				'selector' => '.kata-plus-single-testimonial .kata-plus-date',
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
				'selector' => '.kata-plus-single-testimonial .kt-ts-stars-wrapper',
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
				'selector' => '.kata-plus-single-testimonial .kt-ts-stars-wrapper .kt-ts-star',
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
				'selector' => '.kata-plus-single-testimonial .kt-ts-stars-wrapper .kt-ts-star.kt-star-full',
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
				'selector' => '.kata-plus-single-testimonial .kt-ts-stars-wrapper .kt-ts-star.kt-ts-star-half',
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
				'selector' => '.kata-plus-single-testimonial .kt-ts-stars-wrapper .kt-ts-star-empty',
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
