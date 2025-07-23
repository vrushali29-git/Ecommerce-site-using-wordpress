<?php
/**
 * Contact Form module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
class Kata_Plus_ContactForm extends Widget_Base {
	public function get_name() {
		return 'kata-plus-contact-form';
	}

	public function get_title() {
		return esc_html__( 'Contact Form', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-contact-form';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-contact-form', 'nice-select' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-contact-form', 'nice-select' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Contact Form Settings', 'kata-plus' ),
			)
		);

		$contact_form7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

		$cform_default     = '';
		$contact_form_name = $contact_form_id = $contact_form_op = array();

		if ( $contact_form7 ) {
			$i = 0;
			foreach ( $contact_form7 as $cform ) {

				$cform_default       = $cform->ID;
				$contact_form_name[] = $cform->post_title;
				$contact_form_id[]   = $cform->ID;
			}
			$contact_form_op = array_combine( $contact_form_id, $contact_form_name );
		} else {
			$contact_form_op[ __( 'No contact forms found', 'kata-plus' ) ] = 0;
		}

		$this->add_control(
			'contact_form',
			array(
				'label'   => __( 'Please select your desired form', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $cform_default,
				'options' => $contact_form_op,
			)
		);

		// Style options End
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_form_wrap',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'General', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_form',
			array(
				'label'    => esc_html__( 'Form', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form form',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_label',
			array(
				'label'    => esc_html__( 'Label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form label',
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
			'styler_form_icons',
			array(
				'label'    => esc_html__( 'Icons', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_box_style_inputs',
			array(
				'label' => esc_html__( 'Inputs', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_input_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form form.wpcf7-form span.wpcf7-form-control-wrap:not(.textarea-wrapper)',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input',
			array(
				'label'    => esc_html__( 'Input', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form input:not([type="submit"])',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input_placeholder',
			array(
				'label'    => esc_html__( 'Placeholder', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form input:not([type="submit"])::placeholder',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_box_style_date',
			array(
				'label' => esc_html__( 'Date', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_date_placeholder',
			array(
				'label'    => esc_html__( 'Date Placeholder', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form input[type="date"]:valid',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_date_picker_icon',
			array(
				'label'    => esc_html__( 'Date picker Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form input[type="date"]::-webkit-calendar-picker-indicator',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_box_style_textarea',
			array(
				'label' => esc_html__( 'Textarea', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_textarea_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form form.wpcf7-form span.wpcf7-form-control-wrap.textarea-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textarea',
			array(
				'label'    => esc_html__( 'Textarea', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form textarea',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textarea_placeholder',
			array(
				'label'    => esc_html__( 'Placeholder', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form textarea::placeholder',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style_button',
			array(
				'label' => esc_html__( 'Submit Button', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_button',
			array(
				'label'    => esc_html__( 'Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form input[type="submit"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'select_style',
			array(
				'label' => esc_html__( 'Dropdown Select', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_select_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .nice-select',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_options_wrapper',
			array(
				'label'    => esc_html__( 'Options Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .nice-select .list',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_options',
			array(
				'label'    => esc_html__( 'Options', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .nice-select .list li.option',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_selected_current_option',
			array(
				'label'    => esc_html__( 'Current Option', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .nice-select .current',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_selected_option',
			array(
				'label'    => esc_html__( 'Selected Option', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-contact-form .nice-select .list li.option.selected',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'select_style_error_and_wranings',
			array(
				'label' => esc_html__( 'Errors And Warnings', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_ew_form',
			array(
				'label'    => esc_html__( 'Form Error', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.wpcf7-form .wpcf7-validation-errors, {{WRAPPER}} .wpcf7-form .wpcf7-mail-sent-ng',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_ew_fild',
			array(
				'label'    => esc_html__( 'Fields Error', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.wpcf7-form .wpcf7-not-valid-tip',
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
