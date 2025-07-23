<?php

/**
 * Post Metadata module config.
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

class Kata_Plus_Post_Metadata extends Widget_Base {

	public function get_name() {
		return 'kata-plus-post-metadata';
	}

	public function get_title() {
		return esc_html__( 'Post Metadata', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-post-info';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	public function get_script_depends() {
		return array( 'zilla-likes' );
	}

	public function get_style_depends() {
		return array( 'zilla-likes' );
	}

	protected function register_controls() {
		// Metadata section
		$this->start_controls_section(
			'metadata_section',
			array(
				'label' => esc_html__( 'Metadata', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'categories',
			array(
				'label'     => esc_html__( 'Categories', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
			)
		);
		$this->add_control(
			'cat_icon',
			array(
				'label'     => esc_html__( 'Category Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/folder',
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_control(
			'cat_seprator',
			array(
				'label'     => esc_html__( 'Seperator', 'kata-plus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '|',
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_control(
			'tags',
			array(
				'label'     => esc_html__( 'Tags', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'tags_icon',
			array(
				'label'     => esc_html__( 'Tags Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/tag',
				'condition' => array(
					'tags' => 'yes',
				),
			)
		);
		$this->add_control(
			'date',
			array(
				'label'     => esc_html__( 'Post Date', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => 'yes',
				'separator' => 'before',
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
			'date_icon',
			array(
				'label'     => esc_html__( 'Date Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/calendar',
				'condition' => array(
					'date' => 'yes',
				),
			)
		);
		$this->add_control(
			'comments',
			array(
				'label'     => esc_html__( 'Post Comments', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'comments_icon',
			array(
				'label'     => esc_html__( 'Comments Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/comments',
				'condition' => array(
					'comments' => 'yes',
				),
			)
		);
		$this->add_control(
			'author',
			array(
				'label'     => esc_html__( 'Post Author', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'author_avatar',
			array(
				'label'     => esc_html__( 'Post Author Avatar', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'condition' => array(
					'author' => 'yes',
				),
			)
		);
		$this->add_control(
			'author_avatar_size',
			array(
				'label'     => __( 'Post Author Avatar Size', 'kata-plus' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'' => array(
						'min'  => 10,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'   => array(
					'unit' => '',
					'size' => 22,
				),
				'condition' => array(
					'author'        => 'yes',
					'author_avatar' => 'yes',
				),
			)
		);
		$this->add_control(
			'author_icon',
			array(
				'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/user',
				'condition' => array(
					'author'        => 'yes',
					'author_avatar' => '',
				),
			)
		);
		$this->add_control(
			'like',
			array(
				'label'     => esc_html__( 'Like', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'post_share_count',
			array(
				'label'     => esc_html__( 'Social Share Counter:', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'post_share_count_icon',
			array(
				'label'     => esc_html__( 'Social Share Counter Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'simple-line/action-redo',
				'condition' => array(
					'post_share_count' => 'yes',
				),
			)
		);
		$this->add_control(
			'post_view',
			array(
				'label'     => esc_html__( 'Post View:', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'post_view_icon',
			array(
				'label'     => esc_html__( 'Post View Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'simple-line/eye',
				'condition' => array(
					'post_view' => 'yes',
				),
			)
		);
		$this->add_control(
			'post_time_to_read',
			array(
				'label'     => esc_html__( 'Post Time To Read:', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'kata-plus' ),
				'label_off' => esc_html__( 'Hide', 'kata-plus' ),
				'default'   => '',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'post_time_to_read_icon',
			array(
				'label'     => esc_html__( 'Post Time To Read Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/time',
				'condition' => array(
					'post_time_to_read' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		// Style section
		$this->start_controls_section(
			'style_section_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'post_metadata_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-postmetadata',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_categories',
			array(
				'label'     => esc_html__( 'Categories', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_category_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-category-links',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_category',
			array(
				'label'    => esc_html__( 'Category', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-category-links a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_category_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-category-links .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_tags',
			array(
				'label'     => esc_html__( 'Tags', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tags' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_tags_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-tags-links',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_tags',
			array(
				'label'    => esc_html__( 'Tags', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-tags-links a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_tags_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-tags-links .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_date',
			array(
				'label'     => esc_html__( 'Date', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'date' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_date_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-date',
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
				'selector' => '.kata-post-date a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_date_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-date .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_comments',
			array(
				'label'     => esc_html__( 'Comments', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'comments' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_comments_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-number',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_comments',
			array(
				'label'    => esc_html__( 'Comments', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-number span',
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
			'styler_comments_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-number .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_author',
			array(
				'label'     => esc_html__( 'Author', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'author' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_author_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-author',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_author',
			array(
				'label'    => esc_html__( 'Author', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-author a',
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
			'styler_author_avatar_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-author .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_author_avatar',
			array(
				'label'    => esc_html__( 'Avatar', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-author img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_time_to_read',
			array(
				'label'     => esc_html__( 'Time To Read', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_time_to_read' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_time_to_read_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-time-to-read',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_time_to_read',
			array(
				'label'    => esc_html__( 'Time To Read', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-time-to-read span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_time_to_read_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-time-to-read .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_like',
			array(
				'label'     => esc_html__( 'like', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'like' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_like_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.zilla-likes',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_like',
			array(
				'label'    => esc_html__( 'Like Counter', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.zilla-likes-count',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_like_icon',
			array(
				'label'    => esc_html__( 'Like Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.zilla-likes .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_social_share',
			array(
				'label'     => esc_html__( 'Social Share Counter', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_share_count' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_share_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-share-count',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_share',
			array(
				'label'    => esc_html__( 'Counter', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-share-count span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_share_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-share-count .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_post_view',
			array(
				'label'     => esc_html__( 'Post View Counter', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_view' => 'yes',
				),
			)
		);
		$this->add_control(
			'styler_view_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-view',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_view',
			array(
				'label'    => esc_html__( 'Counter', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-view span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_view_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-view .kata-icon',
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
