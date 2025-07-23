<?php

/**
 * Text module config.
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

class Kata_Plus_Text extends Widget_Base {

	public function get_name() {
		return 'kata-plus-text';
	}

	public function get_title() {
		return esc_html__( 'Text', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-text';
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Text Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'text_editor',
			array(
				'label'   => __( 'Text Editor', 'kata-plus' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'text'     => array(
						'title' => __( 'Textarea', 'kata-plus' ),
						'icon'  => 'eicon-text-area',
					),
					'text_mce' => array(
						'title' => __( 'TinyMCE', 'kata-plus' ),
						'icon'  => 'eicon-editor-paragraph',
					),
				),
				'default' => 'text_mce',
				'toggle'  => false,
			)
		);
		$this->add_control(
			'text',
			array(
				'label'     => __( 'Text', 'kata-plus' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => __( 'Default description textarea', 'kata-plus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'text_editor' => 'text',
				),
			)
		);
		$this->add_control(
			'text_mce',
			array(
				'label'     => __( 'Text', 'kata-plus' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => __( 'Default description TinyMCE', 'kata-plus' ),
				'condition' => array(
					'text_editor' => 'text_mce',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
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
			'styler_textdiv',
			array(
				'label'    => esc_html__( 'Div', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text div',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textp',
			array(
				'label'    => esc_html__( 'Paragraph', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text p',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text1',
			array(
				'label'    => esc_html__( 'Heading 1', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h1',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text2',
			array(
				'label'    => esc_html__( 'Heading 2', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h2',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text3',
			array(
				'label'    => esc_html__( 'Heading 3', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h3',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text4',
			array(
				'label'    => esc_html__( 'Heading 4', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h4',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text5',
			array(
				'label'    => esc_html__( 'Heading 5', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h5',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_text6',
			array(
				'label'    => esc_html__( 'Heading 6', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text h6',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textspan',
			array(
				'label'    => esc_html__( 'Span', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textstrong',
			array(
				'label'    => esc_html__( 'Strong', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text strong, .kata-plus-text b',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_texta',
			array(
				'label'    => esc_html__( 'Link', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textblockquote',
			array(
				'label'    => esc_html__( 'Blockquote', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-text blockquote',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_textul',
			[
				'label'     => esc_html__( 'ul tag', 'kata-plus' ),
				'type'      => 'styler',
				'isSVG'     => true,
				'isInput'   => false,
				'selector' => '.kata-plus-text ul',
				'wrapper'   => '{{WRAPPER}}',
			]
		);
		$this->add_control(
			'styler_textli',
			[
				'label'     => esc_html__( 'li tag', 'kata-plus' ),
				'type'      => 'styler',
				'isSVG'     => true,
				'isInput'   => false,
				'selector' => '.kata-plus-text li',
				'wrapper'   => '{{WRAPPER}}',
			]
		);
		$this->add_control(
			'styler_textol',
			[
				'label'     => esc_html__( 'ol tag', 'kata-plus' ),
				'type'      => 'styler',
				'isSVG'     => true,
				'isInput'   => false,
				'selector' => '.kata-plus-text ol',
				'wrapper'   => '{{WRAPPER}}',
			]
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}

	protected function content_template() {
		?>
		<#
		var text = text && settings.text_editor == 'text' ? settings.text : settings.text_mce;
		var live = settings.text_editor == 'text' ? 'text' : 'text_mce';
		#>
		<div class="kata-plus-text elementor-inline-editing" data-elementor-setting-key="<# print( live ); #>"><# print( text ) #></div>
		<?php
	}
}
