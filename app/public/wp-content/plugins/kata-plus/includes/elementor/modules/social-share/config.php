<?php
/**
 * Social Share config.
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

class Kata_Social_Share extends Widget_Base {
	public function get_name() {
		return 'kata-plus-social-share';
	}

	public function get_title() {
		return esc_html__( 'Social Share', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-social-share';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	public function get_script_depends() {
		return array( 'kata-social-share' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-social-share' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Social Share', 'kata-plus' ),
			)
		);
		$this->add_control(
			'mode',
			array(
				'label'    => __( 'Mode', 'kata-plus' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => array(
					'kt-social-normal' => __( 'Normal', 'kata-plus' ),
					'kt-social-sticky' => __( 'Sticky', 'kata-plus' ),
				),
				'default'  => 'kt-social-normal',
			)
		);
		$this->add_control(
			'title',
			array(
				'label' => __( 'Title', 'kata-plus' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'shared_count',
			array(
				'label'        => __( 'Share Count', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'socials',
			array(
				'label'    => __( 'Socials', 'kata-plus' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => array(
					'facebook'  => __( 'Facebook', 'kata-plus' ),
					'twitter'   => __( 'Twitter', 'kata-plus' ),
					'linkedin'  => __( 'Linkedin', 'kata-plus' ),
					'reddit'    => __( 'Reddit', 'kata-plus' ),
					'pinterest' => __( 'Pinterest', 'kata-plus' ),
					'email'     => __( 'Email', 'kata-plus' ),
				),
				'default'  => array( 'facebook', 'twitter', 'linkedin' ),
			)
		);
		$this->add_control(
			'icon_image',
			array(
				'label'   => __( 'Image or icon', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'  => __( 'Icon', 'kata-plus' ),
					'image' => __( 'Image', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'custom_dimension',
			array(
				'label'       => __( 'Image Dimension', 'kata-plus' ),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'kata-plus' ),
				'default'     => array(
					'width'  => '50',
					'height' => '50',
				),
				'condition'   => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'facebook_icon',
			array(
				'label'     => esc_html__( 'Facebook Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/facebook',
				'condition' => array(
					'socials'    => 'facebook',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'facebook_image',
			array(
				'label'     => __( 'Facebook', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'facebook',
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'twitter_icon',
			array(
				'label'     => esc_html__( 'Twitter Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/twitter',
				'condition' => array(
					'socials'    => 'twitter',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'twitter_image',
			array(
				'label'     => __( 'Twitter', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'twitter',
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'linkedin_icon',
			array(
				'label'     => esc_html__( 'Linkedin Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/linkedin',
				'condition' => array(
					'socials'    => 'linkedin',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'linkedin_image',
			array(
				'label'     => __( 'Linkedin', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'linkedin',
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'pinterest_icon',
			array(
				'label'     => esc_html__( 'Pinterest Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/pinterest',
				'condition' => array(
					'socials'    => 'pinterest',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'pinterest_image',
			array(
				'label'     => __( 'Pinterest', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'pinterest',
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'reddit_icon',
			array(
				'label'     => esc_html__( 'Reddit Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/reddit',
				'condition' => array(
					'socials'    => 'reddit',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'reddit_image',
			array(
				'label'     => __( 'Reddit', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'reddit',
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'email_icon',
			array(
				'label'     => esc_html__( 'Email Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/email',
				'condition' => array(
					'socials'    => 'email',
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'email_image',
			array(
				'label'     => __( 'Email', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'socials'    => 'email',
					'icon_image' => 'image',
				),
			)
		);
		$this->end_controls_section();

		// Content options Start
		$this->start_controls_section(
			'parent_shape',
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
				'selector' => '.kata-social-share',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_styling',
			array(
				'label' => esc_html__( 'Social Share', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			'styler_socials_wrapper',
			array(
				'label'    => esc_html__( 'Socials Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-socials-icon-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_socials_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-social-share-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_socials_share_count',
			array(
				'label'    => esc_html__( 'Share Count', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-share-count',
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
				'selector' => '.kata-plus-socials-icon-wrapper > a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icons',
			array(
				'label'     => esc_html__( 'Icons', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-socials-icon-wrapper > a .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_facebook',
			array(
				'label'     => esc_html__( 'Facebook Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-facebook .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_reddit',
			array(
				'label'     => esc_html__( 'Reddit Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-reddit .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_pinterest',
			array(
				'label'     => esc_html__( 'Pinterest Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-pinterest .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_twitter',
			array(
				'label'     => esc_html__( 'Twitter Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-twitter .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_linkedin',
			array(
				'label'     => esc_html__( 'Linkedin Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-linkedin .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_email',
			array(
				'label'     => esc_html__( 'Email Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-email .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'icon',
				),
			)
		);
		$this->add_control(
			'styler_facebook_image',
			array(
				'label'     => esc_html__( 'Facebook image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-facebook img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'styler_twitter_image',
			array(
				'label'     => esc_html__( 'Twitter image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-twitter img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'styler_linkedin_image',
			array(
				'label'     => esc_html__( 'Linkedin image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-linkedin img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'styler_email_image',
			array(
				'label'     => esc_html__( 'Email image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-email img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'styler_reddit_image',
			array(
				'label'     => esc_html__( 'Reddit image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-reddit img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
			)
		);
		$this->add_control(
			'styler_pinterest_image',
			array(
				'label'     => esc_html__( 'Pinterest image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-social-share-pinterest img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'icon_image' => 'image',
				),
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
