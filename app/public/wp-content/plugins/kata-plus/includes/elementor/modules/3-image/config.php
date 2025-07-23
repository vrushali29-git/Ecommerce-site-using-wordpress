<?php
/**
 * Image module config.
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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Plugin;

class Kata_Plus_Image extends Widget_Base {
	public function get_name() {
		return 'kata-plus-image';
	}

	public function get_title() {
		return esc_html__( 'Image', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-image';
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'symbol',
			array(
				'label'       => __( 'Format', 'kata-plus' ),
				'description' => __( 'Choose image or SVG file format.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'imagei',
				'options'     => array(
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'image',
			array(
				'label'       => __( 'Choose Image', 'kata-plus' ),
				'description' => __( 'Select image size.', 'kata-plus' ),
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			)
		);

		$this->add_control(
			'retina_image',
			array(
				'label'     => __( 'Choose 2x Retina Image (Optional)', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'symbol' => 'imagei',
				),
			)
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'kata-plus' ),
				'description' => __( 'Select image alignment.', 'kata-plus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'kata-plus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'kata-plus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'kata-plus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'caption_source',
			array(
				'label'       => __( 'Caption', 'kata-plus' ),
				'description' => __( 'Set caption or custom caption.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'none'       => __( 'None', 'kata-plus' ),
					'attachment' => __( 'Attachment Caption', 'kata-plus' ),
					'custom'     => __( 'Custom Caption', 'kata-plus' ),
				),
				'default'     => 'none',
			)
		);
		$this->add_control(
			'caption',
			array(
				'label'       => __( 'Custom Caption', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Enter your image caption', 'kata-plus' ),
				'condition'   => array(
					'caption_source' => 'custom',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'link_to',
			array(
				'label'       => __( 'Link to', 'kata-plus' ),
				'description' => __( 'Link image to the media file as lightbox or set accustomed URL.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'options'     => array(
					'none'   => __( 'None', 'kata-plus' ),
					'file'   => __( 'Media File', 'kata-plus' ),
					'custom' => __( 'Custom URL', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link to', 'kata-plus' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'kata-plus' ),
				'condition'   => array(
					'link_to' => 'custom',
				),
				'show_label'  => false,
			)
		);
		$this->add_control(
			'open_lightbox',
			array(
				'label'     => __( 'Lightbox', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'yes',
				'options'   => array(
					'yes' => __( 'Yes', 'kata-plus' ),
					'no'  => __( 'No', 'kata-plus' ),
				),
				'condition' => array(
					'link_to' => 'file',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'styler_image_wrapper',
			array(
				'label'    => __( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-image',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_svg_styler',
			array(
				'label'     => __( 'SVG', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'symbol' => 'svg',
				),
			)
		);

		$this->add_control(
			'styler_svg',
			array(
				'label'    => __( 'SVG Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-image i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'styler_svg_tag',
			array(
				'label'    => __( 'SVG', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-image svg',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image_styler',
			array(
				'label'     => __( 'Image', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'symbol' => 'imagei',
				),
			)
		);

		$this->add_control(
			'styler_image_figure',
			array(
				'label'    => __( 'Image Figure', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-image figure',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'styler_image',
			array(
				'label'    => __( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-image img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'styler_caption',
			array(
				'label'    => 'Caption',
				'type'     => 'styler',
				'selector' => '.widget-image-caption',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'caption_source!' => 'none',
				),
			)
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	private function get_caption( $settings ) {
		$caption = '';
		if ( ! empty( $settings['caption_source'] ) ) {
			switch ( $settings['caption_source'] ) {
				case 'attachment':
					$caption = wp_get_attachment_caption( $settings['image']['id'] );
					break;
				case 'custom':
					$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
			}
		}
		return $caption;
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}

	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		return array(
			'url' => $settings['image']['url'],
		);
	}

	protected function content_template() {
		?>
		<# if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}

			var hasCaption = function() {
				if( ! settings.caption_source || 'none' === settings.caption_source ) {
					return false;
				}
				return true;
			}

			var ensureAttachmentData = function( id ) {
				if ( 'undefined' === typeof wp.media.attachment( id ).get( 'caption' ) ) {
					wp.media.attachment( id ).fetch().then( function( data ) {
						view.render();
					} );
				}
			}

			var getAttachmentCaption = function( id ) {
				if ( ! id ) {
					return '';
				}
				ensureAttachmentData( id );
				return wp.media.attachment( id ).get( 'caption' );
			}

			var getCaption = function() {
				if ( ! hasCaption() ) {
					return '';
				}
				return 'custom' === settings.caption_source ? settings.caption : getAttachmentCaption( settings.image.id );
			}

			var link_url;

			if ( 'custom' === settings.link_to ) {
				link_url = settings.link.url;
			}

			if ( 'file' === settings.link_to ) {
				link_url = settings.image.url;
			}

			#><div class="kata-image kata-lazyload"><#

			var imgClass = '';

			if ( '' !== settings.hover_animation ) {
				imgClass = 'elementor-animation-' + settings.hover_animation;
			}

			if ( hasCaption() ) {
				#><figure class="wp-caption"><#
			}

			if ( link_url ) {
					#><a class="elementor-clickable" data-elementor-open-lightbox="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
			}

				if ( image.url.indexOf('svg') && settings.symbol == 'svg' ) {
					var svg = {
						library: "svg",
						value: {
							id: image.id,
							url: image.url
						}
					};
					var iconHTML = elementor.helpers.renderIcon( view, svg, { 'aria-hidden': true }, 'i' , 'object' );
					#><i class="kata-svg-icon">{{{ iconHTML.value }}}</i><#
				} else {
					#><img src="{{ image_url }}" class="{{ imgClass }}" /><#
				}

			if ( link_url ) {
					#></a><#
			}

			if ( hasCaption() ) {
					#><figcaption class="widget-image-caption wp-caption-text">{{{ getCaption() }}}</figcaption><#
			}

			if ( hasCaption() ) {
				#></figure><#
			}

			#></div><#

		} #>
		<?php
	}
}
