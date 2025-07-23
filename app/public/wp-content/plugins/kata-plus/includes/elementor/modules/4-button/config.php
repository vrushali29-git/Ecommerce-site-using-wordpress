<?php
/**
 * Button module config.
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
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_Button extends Widget_Base {
	public function get_name() {
		return 'kata-plus-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-button';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-button' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Button Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'btn_text',
			array(
				'label'       => __( 'Text', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Default text', 'kata-plus' ),
				'placeholder' => __( 'Type your text here', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'link',
			array(
				'label'         => __( 'Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'kata-plus' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				),
			)
		);
		$this->add_control(
			'link_to_home',
			array(
				'label'        => __( 'Link To Home', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'kata-plus' ),
				'label_off'    => __( 'Off', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
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
		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'btn_image',
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
				'name'      => 'btn_image',
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
			'button_icon_position',
			array(
				'label'   => __( 'Icon Position', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => __( 'Left', 'kata-plus' ),
					'right' => __( 'Right', 'kata-plus' ),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'st_button_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-button-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'st_button',
			array(
				'label'    => esc_html__( 'Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-button',
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
			'st_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-button',
				'condition' => array(
					'symbol' => array( 'icon' ),
				),
			)
		);
		$this->add_control(
			'st_image',
			array(
				'label'     => esc_html__( 'Image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-button img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array( 'imagei' ),
				),
			)
		);
		$this->add_control(
			'st_svg',
			array(
				'label'     => esc_html__( 'SVG', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-button .kata-svg-icon svg',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array( 'svg' ),
				),
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
		<div class="kata-plus-button-wrap">
			<# if ( settings.link.url || settings.link_to_home == 'yes' ) {
				var $href = settings.link_to_home == 'yes' ? '<?php echo home_url(); ?>' : settings.link.url;
				#>
				<a href="{{{ $href }}}" class="kata-button kata-lazyload dbg-color">
			<# } #>

			<#
			var image = {
				id: settings.btn_image.id,
				url: settings.btn_image.url,
				size: settings.btn_image_size,
				dimension: settings.btn_image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			switch( settings.btn_image_size ) {
				case 'thumbnail':
					var dimension = 'width="150" height="150"';
				break;
				case 'medium':
					var dimension = 'width="300" height="300"';
				break;
				case 'medium_large':
					var dimension = 'width="760"';
				break;
				case 'large':
					var dimension = 'width="1024" height="1024"';
				break;
				case '1536x1536':
					var dimension = 'width="1536" height="1536"';
				break;
				case '2048x2048':
					var dimension = 'width="2048" height="2048"';
				break;
				case 'full':
					var dimension = '';
				break;
				case 'custom':
					var dimension = 'width="' + image.dimension.width + '" height="' + image.dimension.width + '"';
				break;
				default:
					var dimension = '';
				break;
			}

			if( settings.button_icon_position == 'left' ) {
				if ( settings.symbol == 'icon' && settings.icon ) {

					<?php if ( class_exists( 'Kata_Plus_Pro' ) ) : ?>
						var svgUrl = settings.icon.indexOf('-stroke') > 0 ? '<?php echo Kata_Plus_Pro::$assets; ?>fonts/svg-icons/' + settings.icon + '.svg' : '<?php echo Kata_Plus::$assets; ?>fonts/svg-icons/' + settings.icon + '.svg';
					<?php else : ?>
						var svgUrl = '<?php echo Kata_Plus::$assets; ?>fonts/svg-icons/' + settings.icon + '.svg';
					<?php endif; ?>

					var svg = {
						library: "svg",
						value: {
							id: settings.icon,
							url: svgUrl
						}
					};
					<!-- view.on -->
					let iconHTML = elementor.helpers.renderIcon( view, svg, { 'aria-hidden': true }, 'i' , 'object' );
					#><i class="kata-icon">{{{ iconHTML.value }}}</i><#

				} else if ( image.id && image.url.indexOf('svg') > 0 && settings.symbol == 'imagei' ) {
					#><img src="{{{ image.url }}}" {{{ dimension }}} alt=""><#
				} else if ( image.id && image.url.indexOf('svg') <= 0 && settings.symbol == 'imagei') {
					#><img src="{{{ image_url }}}" alt=""><#
				} else if ( image.id && image.url.indexOf('svg') > 0 && settings.symbol == 'svg') {
					var svg = {
						library: "svg",
						value: {
							id: settings.icon,
							url: image.url
						}
					};
					var svgHTML = elementor.helpers.renderIcon( view, svg, { 'aria-hidden': true }, 'i' , 'object' );

					#><i class="kata-svg-icon" {{{ dimension }}}>{{{ svgHTML.value }}}</i><#
				}
			} #>

			<span class="kata-button-text elementor-inline-editing" data-elementor-setting-key="btn_text">
				<# print( settings.btn_text ) #>
			</span>

			<# if( settings.button_icon_position == 'right' ) {
				if ( settings.symbol == 'icon' && settings.icon ) {

					var svgUrl = settings.icon.indexOf('-stroke') > 0 ? '<?php echo class_exists( 'Kata_Plus_Pro' ) ?? Kata_Plus_Pro::$assets; ?>fonts/svg-icons/' + settings.icon + '.svg' : '<?php echo Kata_Plus::$assets; ?>fonts/svg-icons/' + settings.icon + '.svg';

					var svg = {
						library: "svg",
						value: {
							id: 1,
							url: svgUrl
						}
					};
					var iconHTML = elementor.helpers.renderIcon( view, svg, { 'aria-hidden': true }, 'i' , 'object' );

					#><i class="kata-icon">{{{ iconHTML.value }}}</i><#

				} else if ( image.id && image.url.indexOf('svg') > 0 && settings.symbol == 'imagei' ) {
					#><img src="{{{ image.url }}}" {{{ dimension }}} alt=""><#
				} else if ( image.id && image.url.indexOf('svg') <= 0 && settings.symbol == 'imagei') {
					#><img src="{{{ image_url }}}" alt=""><#
				} else if ( image.id && image.url.indexOf('svg') > 0 && settings.symbol == 'svg') {
					var svg = {
						library: "svg",
						value: {
							id: 2,
							url: image.url
						}
					};
					var svgHTML = elementor.helpers.renderIcon( view, svg, { 'aria-hidden': true }, 'i' , 'object' );

					#><i class="kata-svg-icon" {{{ dimension }}}>{{{ svgHTML.value }}}</i><#
				}
			} #>

			<# if ( settings || settings.link_to_home == 'yes' ) {  #>
				</a>
			<# }  #>
		</div>
		<?php
	}
}
