<?php

namespace ShopPress\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class HeaderToggle extends ShopPressWidgets {

	public function get_name() {
		return 'sp-header-toggle';
	}

	public function get_title() {
		return esc_html__( 'Off-Canvas', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-header-toggle';
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function get_script_depends() {
		return array( 'sp-header-toggle' );
	}

	public function get_style_depends() {
		return array( 'sp-header-toggle' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Content Toggle Settings', 'shop-press' ),
			)
		);
		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'icon',
				'toggle'  => true,
				'options' => array(
					'image' => array(
						'title' => __( 'Image', 'shop-press' ),
						'icon'  => 'eicon-image',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'shop-press' ),
						'icon'  => 'eicon-icon-box',
					),
					'text'  => array(
						'title' => __( 'Text', 'shop-press' ),
						'icon'  => 'eicon-text',
					),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'shop-press' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'placeholder' => array(
						'image',
						'svg',
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'placeholder' => array(
						'image',
					),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'shop-press' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-bars',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'placeholder' => array(
						'icon',
					),
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'     => esc_html__( 'Text', 'shop-press' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Login', 'shop-press' ),
				'condition' => array(
					'placeholder' => array(
						'text',
						'icon',
					),
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'close_icon',
			array(
				'label'   => esc_html__( 'Close Icon', 'shop-press' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'header_toggle_template',
			array(
				'label'       => esc_html__( 'Choose template', 'shop-press' ),
				'description' => esc_html__( 'Please head over to WP Dashboard > Templates > Saved Templates and add a template. You can then choose the template you like here.', 'shop-press' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => $this->templates(),
			)
		);

		$this->add_control(
			'open_in',
			array(
				'label'       => esc_html__( 'Opening type', 'shop-press' ),
				'description' => esc_html__( 'There is 3 type to show header toggle. Dropdown, Drawer, Popup', 'shop-press' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dropdown',
				'options'     => array(
					'dropdown' => 'Dropdown',
					'drawer'   => 'Drawer',
					'popup'    => 'Popup',
				),
			)
		);
		$this->end_controls_section();

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_placeholder',
			__( 'Placeholder', 'shop-press' ),
			array(
				'placeholder_wrapper' => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-click',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_image'   => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-header-toggle-click img',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_icon'    => array(
					'label'    => __( 'Icon', 'shop-press' ),
					'selector' => '.sp-header-toggle-click i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'close_icon_wrapper'  => array(
					'label'    => __( 'Close Icon Wrapper', 'shop-press' ),
					'selector' => '.shop-press-heaader-toggle-close',
					'wrapper'  => '{{WRAPPER}}',
				),
				'close_icon'          => array(
					'label'    => __( 'Close Icon', 'shop-press' ),
					'selector' => '.shop-press-heaader-toggle-close i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_text'    => array(
					'label'    => __( 'Text', 'shop-press' ),
					'selector' => '.sp-header-toggle-click .header-toggle-text',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_placeholder_active',
			__( 'Active Placeholder', 'shop-press' ),
			array(
				'placeholder_active_wrapper' => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_image'   => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active img',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_icon'    => array(
					'label'    => __( 'Icon', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_text'    => array(
					'label'    => __( 'Text', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active .header-toggle-text',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_toggle',
			__( 'Toggle Content', 'shop-press' ),
			array(
				'toggle_wrapper' => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-content-wrap',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}
	public function templates() {
		$get_templates = Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
		$templates     = array(
			'0' => __( 'Elementor template is not defined yet.', 'shop-press' ),
		);
		if ( ! empty( $get_templates ) ) {
			$templates = array(
				'0' => __( 'Select elementor template', 'shop-press' ),
			);
			foreach ( $get_templates as $template ) {
				$templates[ $template['title'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
		}
		return $templates;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'open_in'                => $settings['open_in'],
			'placeholder'            => $settings['placeholder'],
			'image'                  => $settings['image'],
			'image_size'             => $settings['image_size'],
			'image_custom_dimension' => $settings['image_custom_dimension'],
			'text'                   => $settings['text'],
			'icon'                   => $settings['icon'],
			'header_toggle_template' => $settings['header_toggle_template'],
			'close_icon'             => $settings['close_icon'],
		);

		sp_load_builder_template( 'general/off-canvas', $args );
	}
}
