<?php
/**
 * Title module config.
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

class Kata_Plus_Title extends Widget_Base {
	public function get_name() {
		return 'kata-plus-title';
	}

	public function get_title() {
		return esc_html__( 'Title', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-editor-h1';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-title' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Title Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'kata-plus' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Write your Title', 'kata-plus' ),
				'default'     => __( 'Title', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'subtitle',
			array(
				'label'       => __( 'Subtitle', 'kata-plus' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Write your Subtitle', 'kata-plus' ),
				'default'     => __( 'Subtitle', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'title_tag',
			array(
				'label'       => __( 'Title tag', 'kata-plus' ),
				'description' => __( 'Set a certain tag for title', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h2',
				'options'     => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'P', 'kata-plus' ),
					'span' => __( 'Span', 'kata-plus' ),
					'div'  => __( 'DIV', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'subtitle_tag',
			array(
				'label'       => __( 'Subtitle tag', 'kata-plus' ),
				'description' => __( 'Set a certain tag for subtitle.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'P', 'kata-plus' ),
					'span' => __( 'Span', 'kata-plus' ),
					'div'  => __( 'DIV', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'link',
			array(
				'label'         => __( 'Link', 'kata-plus' ),
				'description'   => __( 'Set link for title and subtitle.', 'kata-plus' ),
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
			'link_to_home',
			array(
				'label'        => __( 'Link To Home', 'kata-plus' ),
				'description'  => __( 'Set the link to the homepage.', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_shape',
			array(
				'label' => esc_html__( 'Shape', 'kata-plus' ),
			)
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'shape',
			array(
				'label'    => __( 'Shape', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}}',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'shape',
			array(
				'label'         => __( 'Shape', 'kata-plus' ),
				'type'          => Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'fields'        => $repeater->get_controls(),
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
			'styler_title_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-title-wrapper',
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
				'selector' => '.kata-plus-title-wrapper .kata-plus-title',
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
				'selector' => '.kata-plus-title-wrapper .kata-plus-subtitle',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_link',
			array(
				'label'    => esc_html__( 'Link', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-title-wrapper .kata-title-url',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}

	protected function content_template() {
		?>
		<div class="kata-plus-title-wrapper">
			<# if (settings.link.url) { #>
				<a href="<# print(settings.link.url); #>" class="kata-title-url">
			<# } #>

			<# if (settings.title) { #>
				<{{{settings.title_tag}}} class="kata-plus-title elementor-inline-editing" data-elementor-setting-key="title">
					<# print(settings.title); #>
				</{{{settings.title_tag}}}>
			<# } #>

			<# if (settings.subtitle) { #>
				<{{{settings.subtitle_tag}}} class="kata-plus-subtitle elementor-inline-editing" data-elementor-setting-key="subtitle">
					<# print(settings.subtitle); #>
				</{{{settings.subtitle_tag}}}>
			<# } #>

			<# if (settings.link.url) { #>
				</a>
			<# } #>

			<# if (settings.shape) { #>
				<# settings.shape.forEach((element) => { #>
					<span class="elementor-repeater-item-<# print( element._id ) #> kata-plus-shape"></span>
				<# }); #>
			<# } #>
		</div>
		<?php
	}
}
