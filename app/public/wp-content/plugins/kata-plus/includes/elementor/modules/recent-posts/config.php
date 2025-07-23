<?php
/**
 * Recent Posts module config.
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

if ( ! class_exists( 'Kata_Plus_Recent_Posts' ) ) {
	class Kata_Plus_Recent_Posts extends Widget_Base {
		public function get_name() {
			return 'kata-plus-blog-posts';
		}

		public function get_title() {
			return esc_html__( 'Recent Posts', 'kata-plus' );
		}

		public function get_icon() {
			return 'kata-widget kata-eicon-recent-posts';
		}

		public function get_categories() {
			return array( 'kata_plus_elementor_blog_and_post' );
		}

		public function get_style_depends() {
			return array( 'kata-plus-owlcarousel', 'kata-plus-owl', 'kata-plus-blog-posts' );
		}

		public function get_script_depends() {
			return array( 'kata-plus-owlcarousel', 'kata-plus-owl' );
		}

		protected function register_controls() {
			// Query
			$this->start_controls_section(
				'query_section',
				array(
					'label' => esc_html__( 'Query', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$cat_options = array();
			foreach ( get_categories() as $category ) {
				$cat_options[ $category->slug ] = $category->name;
			}
			$this->add_control(
				'query_categories',
				array(
					'label'    => __( 'Categories', 'kata-plus' ),
					'type'     => Controls_Manager::SELECT2,
					'options'  => $cat_options,
					'default'  => array(),
					'multiple' => true,
				)
			);
			$tag_options = array();
			foreach ( get_tags() as $tag ) {
				$tag_options[ $tag->slug ] = $tag->name;
			}
			$this->add_control(
				'query_tags',
				array(
					'label'    => __( 'Tags', 'kata-plus' ),
					'type'     => Controls_Manager::SELECT2,
					'options'  => $tag_options,
					'default'  => array(),
					'multiple' => true,
				)
			);
			$this->add_control(
				'query_order_by',
				array(
					'label'   => esc_html__( 'Order By', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'date',
					'options' => array(
						'date'          => esc_html__( 'Date', 'kata-plus' ),
						'title'         => esc_html__( 'Title', 'kata-plus' ),
						'comment_count' => esc_html__( 'Comment Count', 'kata-plus' ),
						'menu_order'    => esc_html__( 'Menu Order', 'kata-plus' ),
						'rand'          => esc_html__( 'Random', 'kata-plus' ),
					),
				)
			);
			$this->add_control(
				'query_order',
				array(
					'label'   => esc_html__( 'Order', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => array(
						'DESC' => esc_html__( 'DESC', 'kata-plus' ),
						'ASC'  => esc_html__( 'ASC', 'kata-plus' ),
					),
				)
			);
			$this->end_controls_section();

			// Posts section
			$this->start_controls_section(
				'posts_section',
				array(
					'label' => esc_html__( 'Posts', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$this->add_responsive_control(
				'posts_columns',
				array(
					'label'   => esc_html__( 'Columns Number', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '3',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
				)
			);
			$this->add_control(
				'posts_per_page',
				array(
					'label'   => __( 'Posts Per Page', 'kata-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'min'     => 1,
					'step'    => 1,
					'default' => 10,
				)
			);
			$this->add_control(
				'posts_thumbnail',
				array(
					'label'     => esc_html__( 'Post Thumbnail', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'posts_thumbnail_position',
				array(
					'label'     => esc_html__( 'Post Thumbnail Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title' => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'  => esc_html__( 'After Title', 'kata-plus' ),
					),
					'condition' => array(
						'posts_thumbnail' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_thumbnail_layout_position',
				array(
					'label'     => esc_html__( 'Post Thumbnail Layout Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'top',
					'options'   => array(
						'top'   => esc_html__( 'Top', 'kata-plus' ),
						'left'  => esc_html__( 'Left', 'kata-plus' ),
						'right' => esc_html__( 'Right', 'kata-plus' ),
					),
					'condition' => array(
						'posts_thumbnail'          => 'yes',
						'posts_thumbnail_position' => 'before-title',
					),
				)
			);
			$this->add_control(
				'posts_thumbnail_size',
				array(
					'label'     => esc_html__( 'Post Thumbnail Size', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'full',
					'options'   => array(
						'full'   => esc_html__( 'Full', 'kata-plus' ),
						'custom' => esc_html__( 'Custom', 'kata-plus' ),
					),
					'condition' => array(
						'posts_thumbnail' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_thumbnail_custom_size',
				array(
					'label'       => __( 'Post Thumbnail Custom Size', 'kata-plus' ),
					'description' => __( 'Crop the original image size to any custom size.', 'kata-plus' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'default'     => array(
						'width'  => '',
						'height' => '',
					),
					'condition'   => array(
						'posts_thumbnail'      => 'yes',
						'posts_thumbnail_size' => 'custom',
					),
				)
			);
			$this->add_control(
				'posts_title',
				array(
					'label'     => esc_html__( 'Post Title', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'posts_title_tag',
				array(
					'label'     => esc_html__( 'Post Title Tag', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'div',
						'span' => 'span',
						'p'    => 'p',
					),
					'default'   => 'h3',
					'condition' => array(
						'posts_title' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_excerpt',
				array(
					'label'     => __( 'Post Excerpt', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Show', 'kata-plus' ),
					'label_off' => __( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'posts_excerpt_length',
				array(
					'label'     => __( 'Post Excerpt Length', 'kata-plus' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'' => array(
							'min'  => 1,
							'max'  => 1000,
							'step' => 1,
						),
					),
					'default'   => array(
						'unit' => '',
						'size' => 25,
					),
					'condition' => array(
						'posts_excerpt' => 'yes',
					),
				)
			);
			$this->add_control(
				'ignore_port_format',
				array(
					'label'       => __( 'Ignore Post Format', 'kata-plus' ),
					'description' => __( 'By turning on this option, WordPress default output for content will be ignored.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => __( 'Enable', 'kata-plus' ),
					'label_off'   => __( 'Disable', 'kata-plus' ),
					'default'     => 'yes',
					'condition'   => array(
						'posts_excerpt' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_read_more',
				array(
					'label'     => esc_html__( 'Read More', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'posts_read_more_text',
				array(
					'label'     => esc_html__( 'Read More Text', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => esc_html__( 'Read More', 'kata-plus' ),
					'condition' => array(
						'posts_read_more' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_pagination',
				array(
					'label'     => esc_html__( 'Pagination', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'enable_carousel',
				array(
					'label'     => __( 'Carousel', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Yes', 'kata-plus' ),
					'label_off' => __( 'No', 'kata-plus' ),
					'default'   => '',
				)
			);
			$this->end_controls_section();

			// Posts Metadata section
			$this->start_controls_section(
				'posts_metadata_section',
				array(
					'label' => esc_html__( 'Posts Metadata', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$this->add_control(
				'post_format',
				array(
					'label'     => esc_html__( 'Post Format', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'separator' => 'before',
				)
			);
			$this->add_control(
				'post_format_position',
				array(
					'label'     => esc_html__( 'Post Format Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'post_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_categories',
				array(
					'label'     => esc_html__( 'Categories', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
				)
			);
			$this->add_control(
				'posts_category_icon',
				array(
					'label'     => esc_html__( 'Category Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/folder',
					'condition' => array(
						'posts_categories' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_categories_position',
				array(
					'label'     => esc_html__( 'Categories Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'posts_categories' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_tags',
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
				'posts_tags_position',
				array(
					'label'     => esc_html__( 'Tags Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'posts_tags' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_date',
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
				'custom_date_format',
				array(
					'label'        => esc_html__( 'Custom Date Format', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'On', 'kata-plus' ),
					'label_off'    => __( 'Off', 'kata-plus' ),
					'return_value' => 'yes',
					'condition'    => array(
						'posts_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'date_format_1',
				array(
					'label'     => __( 'Date Format 1', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'm/', 'kata-plus' ),
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'date_format_2',
				array(
					'label'     => __( 'Date Format 2', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'd/', 'kata-plus' ),
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'date_format_3',
				array(
					'label'     => __( 'Date Format 3', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Y', 'kata-plus' ),
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_date_icon',
				array(
					'label'     => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/calendar',
					'condition' => array(
						'posts_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_date_position',
				array(
					'label'     => esc_html__( 'Date Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'posts_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_comments',
				array(
					'label'     => esc_html__( 'Post Comments', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'posts_comments_icon',
				array(
					'label'     => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/comments',
					'condition' => array(
						'posts_comments' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_comments_position',
				array(
					'label'     => esc_html__( 'Comments Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'posts_comments' => 'yes',
					),
				)
			);
			$this->add_control(
				'posts_author',
				array(
					'label'     => esc_html__( 'Post Author', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'post_author_symbol',
				array(
					'label'     => esc_html__( 'Author Symbol', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'icon',
					'options'   => array(
						'icon'   => esc_html__( 'Icon', 'kata-plus' ),
						'avatar' => esc_html__( 'Avatar', 'kata-plus' ),
					),
					'condition' => array(
						'posts_author' => 'yes',
					),
				)
			);
			$this->add_control(
				'avatar_size',
				array(
					'label'     => __( 'Avatar Size', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 5,
					'max'       => 300,
					'step'      => 1,
					'default'   => 20,
					'condition' => array(
						'post_author_symbol' => 'avatar',
					),
				)
			);
			$this->add_control(
				'posts_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/user',
					'condition' => array(
						'posts_author'       => 'yes',
						'post_author_symbol' => 'icon',
					),
				)
			);
			$this->add_control(
				'posts_author_position',
				array(
					'label'     => esc_html__( 'Author Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'posts_author' => 'yes',
					),
				)
			);
			$this->add_control(
				'post_time_to_read',
				array(
					'label'     => esc_html__( 'Post\'s time to read:', 'kata-plus' ),
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
					'label'     => esc_html__( 'Post\'s time to read Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/time',
					'condition' => array(
						'post_time_to_read' => 'yes',
					),
				)
			);
			$this->add_control(
				'post_time_to_read_position',
				array(
					'label'     => esc_html__( 'Post\'s time to read Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'post_time_to_read' => 'yes',
					),
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
				'post_share_count_position',
				array(
					'label'     => esc_html__( 'Social Share Counter Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
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
				'post_view_position',
				array(
					'label'     => esc_html__( 'Post View Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'post_view' => 'yes',
					),
				)
			);
			$this->end_controls_section();

			// First Post section
			$this->start_controls_section(
				'first_post_section',
				array(
					'label' => esc_html__( 'First Post', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$this->add_control(
				'first_post',
				array(
					'label'     => esc_html__( 'Mark First Post as Featured', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'kata-plus' ),
					'label_off' => esc_html__( 'No', 'kata-plus' ),
					'default'   => '',
				)
			);
			$this->add_control(
				'first_post_thumbnail',
				array(
					'label'     => esc_html__( 'Post Thumbnail', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_thumbnail_layout_position',
				array(
					'label'     => esc_html__( 'Post Thumbnail Layout Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'top',
					'options'   => array(
						'top'  => esc_html__( 'Top', 'kata-plus' ),
						'left' => esc_html__( 'Left', 'kata-plus' ),
					),
					'condition' => array(
						'first_post'                    => 'yes',
						'first_post_thumbnail'          => 'yes',
						'first_post_thumbnail_position' => 'before-title',
					),
				)
			);
			$this->add_control(
				'first_post_thumbnail_position',
				array(
					'label'     => esc_html__( 'Post Thumbnail Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title' => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'  => esc_html__( 'After Title', 'kata-plus' ),
					),
					'condition' => array(
						'first_post'           => 'yes',
						'first_post_thumbnail' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_thumbnail_size',
				array(
					'label'     => esc_html__( 'Post Thumbnail Size', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'full',
					'options'   => array(
						'full'   => esc_html__( 'Full', 'kata-plus' ),
						'custom' => esc_html__( 'Custom', 'kata-plus' ),
					),
					'condition' => array(
						'first_post'           => 'yes',
						'first_post_thumbnail' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_thumbnail_custom_size',
				array(
					'label'       => __( 'Post Thumbnail Custom Size', 'kata-plus' ),
					'description' => __( 'Crop the original image size to any custom size.', 'kata-plus' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'default'     => array(
						'width'  => '',
						'height' => '',
					),
					'condition'   => array(
						'first_post'                => 'yes',
						'first_post_thumbnail'      => 'yes',
						'first_post_thumbnail_size' => 'custom',
					),
				)
			);
			$this->add_control(
				'first_post_title',
				array(
					'label'     => esc_html__( 'Title', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_title_tag',
				array(
					'label'     => esc_html__( 'Title Tag', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'div',
						'span' => 'span',
						'p'    => 'p',
					),
					'default'   => 'h3',
					'condition' => array(
						'first_post'       => 'yes',
						'first_post_title' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_excerpt',
				array(
					'label'     => __( 'Excerpt', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Show', 'kata-plus' ),
					'label_off' => __( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_excerpt_length',
				array(
					'label'     => __( 'Excerpt Length', 'kata-plus' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'' => array(
							'min'  => 1,
							'max'  => 1000,
							'step' => 1,
						),
					),
					'default'   => array(
						'unit' => '',
						'size' => 25,
					),
					'condition' => array(
						'first_post'         => 'yes',
						'first_post_excerpt' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_read_more',
				array(
					'label'     => esc_html__( 'Read More', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_read_more_text',
				array(
					'label'     => esc_html__( 'Read More Text', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => esc_html__( 'Read More', 'kata-plus' ),
					'condition' => array(
						'first_post'           => 'yes',
						'first_post_read_more' => 'yes',
					),
				)
			);
			$this->end_controls_section();

			// First Post Metadata section
			$this->start_controls_section(
				'first_post_metadata_section',
				array(
					'label'     => esc_html__( 'First Post Metadata', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_post_format',
				array(
					'label'     => esc_html__( 'Post Format', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_post_post_format_position',
				array(
					'label'     => esc_html__( 'Post Format Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_post_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_categories',
				array(
					'label'     => esc_html__( 'Categories', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => 'yes',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_post_category_icon',
				array(
					'label'     => esc_html__( 'Category Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/folder',
					'condition' => array(
						'first_post_categories' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_categories_position',
				array(
					'label'     => esc_html__( 'Categories Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_categories' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_tags',
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
				'first_post_tags_position',
				array(
					'label'     => esc_html__( 'Tags Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_tags' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date',
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
				'first_post_custom_date_format',
				array(
					'label'        => esc_html__( 'Custom Date Format', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'On', 'kata-plus' ),
					'label_off'    => __( 'Off', 'kata-plus' ),
					'return_value' => 'yes',
					'condition'    => array(
						'first_post_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date_format_1',
				array(
					'label'     => __( 'Date Format 1', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'm/', 'kata-plus' ),
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date_format_2',
				array(
					'label'     => __( 'Date Format 2', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'd/', 'kata-plus' ),
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date_format_3',
				array(
					'label'     => __( 'Date Format 3', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Y', 'kata-plus' ),
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date_icon',
				array(
					'label'     => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/calendar',
					'condition' => array(
						'first_post_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_date_position',
				array(
					'label'     => esc_html__( 'Date Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_date' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_comments',
				array(
					'label'     => esc_html__( 'Post Comments', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_post_comments_icon',
				array(
					'label'     => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/comments',
					'condition' => array(
						'first_post_comments' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_comments_position',
				array(
					'label'     => esc_html__( 'Comments Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_comments' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_author',
				array(
					'label'     => esc_html__( 'Post Author', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_post_author_symbol',
				array(
					'label'     => esc_html__( 'Author Symbol', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'icon',
					'options'   => array(
						'icon'   => esc_html__( 'Icon', 'kata-plus' ),
						'avatar' => esc_html__( 'Avatar', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_author' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_avatar_size',
				array(
					'label'     => __( 'Avatar Size', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 5,
					'max'       => 300,
					'step'      => 1,
					'default'   => 20,
					'condition' => array(
						'first_post_author_symbol' => 'avatar',
					),
				)
			);
			$this->add_control(
				'first_post_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/user',
					'condition' => array(
						'first_post_author'        => 'yes',
						'first_post_author_symbol' => 'icon',
					),
				)
			);
			$this->add_control(
				'first_post_author_position',
				array(
					'label'     => esc_html__( 'Author Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_author' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_time_to_read',
				array(
					'label'     => esc_html__( 'Post\'s time to read:', 'kata-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'kata-plus' ),
					'label_off' => esc_html__( 'Hide', 'kata-plus' ),
					'default'   => '',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_post_time_to_read_icon',
				array(
					'label'     => esc_html__( 'Post\'s time to read Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/time',
					'condition' => array(
						'first_post_time_to_read' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_time_to_read_position',
				array(
					'label'     => esc_html__( 'Post\'s time to read Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_time_to_read' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_share_count',
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
				'first_post_share_count_icon',
				array(
					'label'     => esc_html__( 'Social Share Counter Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/action-redo',
					'condition' => array(
						'first_post_share_count' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_share_count_position',
				array(
					'label'     => esc_html__( 'Social Share Counter Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_share_count' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_view',
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
				'first_post_view_icon',
				array(
					'label'     => esc_html__( 'Post View Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/eye',
					'condition' => array(
						'first_post_view' => 'yes',
					),
				)
			);
			$this->add_control(
				'first_post_view_position',
				array(
					'label'     => esc_html__( 'Post View Position', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'before-title',
					'options'   => array(
						'before-title'  => esc_html__( 'Before Title', 'kata-plus' ),
						'after-title'   => esc_html__( 'After Title', 'kata-plus' ),
						'after-excerpt' => esc_html__( 'After Excerpt', 'kata-plus' ),
					),
					'condition' => array(
						'first_post_view' => 'yes',
					),
				)
			);
			$this->end_controls_section();

			// owl option
			$this->start_controls_section(
				'content_section',
				array(
					'label'     => esc_html__( 'Carousel Settings', 'kata-plus' ),
					'condition' => array(
						'enable_carousel' => 'yes',
					),
				)
			);
			$this->add_responsive_control(
				'inc_owl_item',
				array(
					'label'       => __( 'Item', 'kata-plus' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 1,
					'max'         => 12,
					'step'        => 1,
					'default'     => 3,
					'description' => __( 'Varies between 1/12', 'kata-plus' ),
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
					'return_value' => 'no',
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

			// Posts Style section
			$this->start_controls_section(
				'section_widget_parent',
				array(
					'label' => esc_html__( 'Wrapper', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_posts_container',
				array(
					'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts',
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
				'posts_style_section',
				array(
					'label' => esc_html__( 'Posts', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_posts_post',
				array(
					'label'    => esc_html__( 'Post', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post)',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_thumbnail_wrapper',
				array(
					'label'    => esc_html__( 'Thumbnail Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			// Posts Pagination Style section
			$this->start_controls_section(
				'posts_pagination_style_section',
				array(
					'label' => esc_html__( 'Pagination', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_posts_post_pagination_wrapper',
				array(
					'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .kata-post-pagination',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_pagination',
				array(
					'label'    => esc_html__( 'Pagination', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .kata-post-pagination .page-numbers',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_pagination_current',
				array(
					'label'    => esc_html__( 'Pagination Current', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .kata-post-pagination .page-numbers.current',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_pagination_prev_next',
				array(
					'label'    => esc_html__( 'Pagination Previous/Next', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .kata-post-pagination a.next.page-numbers, .kata-blog-posts .kata-post-pagination a.prev.page-numbers',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			// Posts Metadata Style section
			$this->start_controls_section(
				'posts_metadata_style_section',
				array(
					'label' => esc_html__( 'Metadata', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_posts_post_metadata_container',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (Before Title)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-metadata.before-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_metadata_container_after_title',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (After Title)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-metadata.after-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_post_metadata_container_after_excerpt',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (After Excerpt)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-metadata.after-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_format_wrapper',
				array(
					'label'    => esc_html__( 'Post Format Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-format',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_format_icon',
				array(
					'label'    => esc_html__( 'Post Format Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-format .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_categories_wrapper',
				array(
					'label'    => esc_html__( 'Categories Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-category-links',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_categories',
				array(
					'label'    => esc_html__( 'Categories', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-category-links a',
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
				'styler_posts_metadata_post_category_icon',
				array(
					'label'    => esc_html__( 'Categories Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-category-links i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_tags_wrapper',
				array(
					'label'    => esc_html__( 'Tags Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-tags-links',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_tags',
				array(
					'label'    => esc_html__( 'Tags', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-tags-links a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_wrapper',
				array(
					'label'    => esc_html__( 'Date Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-date',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date',
				array(
					'label'    => esc_html__( 'Date', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-date a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_1',
				array(
					'label'     => esc_html__( 'Custom Date Format 1', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-date a .kt-date-format1',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_2',
				array(
					'label'     => esc_html__( 'Custom Date Format 2', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-date a .kt-date-format2',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_3',
				array(
					'label'     => esc_html__( 'Custom Date Format 3', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-date a .kt-date-format3',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_icon',
				array(
					'label'    => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-date i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments_wrapper',
				array(
					'label'    => esc_html__( 'Comments Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-comments-number',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments',
				array(
					'label'    => esc_html__( 'Comments', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-comments-number span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments_icon',
				array(
					'label'    => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-comments-number i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_wrapper',
				array(
					'label'    => esc_html__( 'Author Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-author',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author',
				array(
					'label'    => esc_html__( 'Author', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-author a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-author i',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'post_author_symbol' => 'icon',
					),
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_avatar',
				array(
					'label'     => esc_html__( 'Author Avatar', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-author .avatar',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'post_author_symbol' => 'avatar',
					),
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read_wrapper',
				array(
					'label'     => esc_html__( 'Time To Read Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-time-to-read',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read',
				array(
					'label'    => esc_html__( 'Time To Read', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-time-to-read span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read_icon',
				array(
					'label'    => esc_html__( 'Time To Read Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-time-to-read i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count_wrapper',
				array(
					'label'    => esc_html__( 'Social Share Counter Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-share-count',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count',
				array(
					'label'    => esc_html__( 'Social Share Counter', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-share-count span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count_icon',
				array(
					'label'    => esc_html__( 'Social Share Counter Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-share-count i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view_wrapper',
				array(
					'label'     => esc_html__( 'Post View Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post:not(.kata-first-post) .kata-post-view',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view',
				array(
					'label'    => esc_html__( 'Post View', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-view span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view_icon',
				array(
					'label'    => esc_html__( 'Post View Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post:not(.kata-first-post) .kata-post-view i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			// First Post Style section
			$this->start_controls_section(
				'first_post_style_section',
				array(
					'label' => esc_html__( 'First Post', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_first_post_post',
				array(
					'label'    => esc_html__( 'Post', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_post_thumbnail_image',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->add_control(
				'styler_first_post_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			// Posts Metadata Style section
			$this->start_controls_section(
				'first_post_metadata_style_section',
				array(
					'label' => esc_html__( 'Fist Post Metadata', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_first_posts_post_metadata_container',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (Before Title)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-metadata.before-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_posts_post_metadata_container_after_title',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (After Title)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-metadata.after-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_posts_post_metadata_container_after_excerpt',
				array(
					'label'    => esc_html__( 'Metadata Wrapper (After Excerpt)', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-metadata.after-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_format_wrapper',
				array(
					'label'     => esc_html__( 'Post Format Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post.kata-first-post .kata-post-format',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_format_icon',
				array(
					'label'    => esc_html__( 'Post Format Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-format .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_categories_wrapper',
				array(
					'label'     => esc_html__( 'Categories Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-category-links',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_categories',
				array(
					'label'    => esc_html__( 'Categories', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-category-links a',
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
				'styler_first_post_metadata_post_category_icon',
				array(
					'label'    => esc_html__( 'Categories Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-category-links i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_tags_wrapper',
				array(
					'label'     => esc_html__( 'Tags Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-category-links i',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_tags',
				array(
					'label'    => esc_html__( 'Tags', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-tags-links a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_date_wrapper',
				array(
					'label'    => esc_html__( 'Date Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_date',
				array(
					'label'    => esc_html__( 'Date', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_posts_metadata_post_date_1',
				array(
					'label'     => esc_html__( 'Custom Date Format 1', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-date a .kt-date-format1',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_first_posts_metadata_post_date_2',
				array(
					'label'     => esc_html__( 'Custom Date Format 2', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-date a .kt-date-format2',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_first_posts_metadata_post_date_3',
				array(
					'label'     => esc_html__( 'Custom Date Format 3', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-date a .kt-date-format3',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'first_post_custom_date_format' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_date_icon',
				array(
					'label'    => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_comments_wrapper',
				array(
					'label'     => esc_html__( 'Comments Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-comments-number',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_comments',
				array(
					'label'    => esc_html__( 'Comments', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-comments-number span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_comments_icon',
				array(
					'label'    => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-comments-number i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_author_wrapper',
				array(
					'label'     => esc_html__( 'Author Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-author',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_author',
				array(
					'label'    => esc_html__( 'Author', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-author a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-author i',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'first_post_author_symbol' => 'icon',
					),
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_author_avatar',
				array(
					'label'     => esc_html__( 'Author Avatar', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-author .avatar',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'first_post_author_symbol' => 'avatar',
					),
				)
			);
			$this->add_control(
				'styler_first_post_metadata_container',
				array(
					'label'     => esc_html__( 'Metadata Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-metadata',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_time_to_read_wrapper',
				array(
					'label'     => esc_html__( 'Time To Read Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post .kata-time-to-read',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_time_to_read',
				array(
					'label'    => esc_html__( 'Time To Read', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post .kata-time-to-read span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_time_to_read_icon',
				array(
					'label'    => esc_html__( 'Time To Read Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post .kata-time-to-read i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_social_share_count_wrapper',
				array(
					'label'     => esc_html__( 'Social Share Counter Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post.kata-first-post .kata-post-share-count',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_social_share_count',
				array(
					'label'    => esc_html__( 'Social Share Counter', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-share-count span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_social_share_count_icon',
				array(
					'label'    => esc_html__( 'Social Share Counter Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-share-count i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_post_view_wrapper',
				array(
					'label'     => esc_html__( 'Post View Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post.kata-first-post .kata-post-view',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_post_view',
				array(
					'label'    => esc_html__( 'Post View', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-view span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_post_view_icon',
				array(
					'label'    => esc_html__( 'Post View Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kata-post-view i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'styler_carousel_settings_section',
				array(
					'label'     => esc_html__( 'Carousel', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_carousel' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_item',
				array(
					'label'    => esc_html__( 'Item', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-item .kata-blog-post',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_arrow_wrapper',
				array(
					'label'    => esc_html__( 'Slider Arrows Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-nav',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_arrow_left_wrapper',
				array(
					'label'    => esc_html__( 'Left Arrow Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-nav .owl-prev',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_arrow_left',
				array(
					'label'    => esc_html__( 'Left Arrow', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-nav .owl-prev i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_arrow_right_wrapper',
				array(
					'label'    => esc_html__( 'Right Arrow Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-nav .owl-next',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_arrow_right',
				array(
					'label'    => esc_html__( 'Right Arrow', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-nav .owl-next i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_boolets',
				array(
					'label'    => esc_html__( 'Bullets Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-dots',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_boolet',
				array(
					'label'    => esc_html__( 'Bullets', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-dots .owl-dot',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_boolet_active',
				array(
					'label'    => esc_html__( 'Active Bullet', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .owl-dots .owl-dot.active',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'carousel_active_item_section',
				array(
					'label'     => esc_html__( 'Carousel Active Item', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_carousel' => 'yes',
					),
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post',
				array(
					'label'    => esc_html__( 'Post', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post)',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_thumbnail_wrapper',
				array(
					'label'    => esc_html__( 'Thumbnail Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.active .kata-blog-post:not(.kata-first-post) .kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'carousel_center_item_section',
				array(
					'label'     => esc_html__( 'Carousel Center Item', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_carousel' => 'yes',
						'inc_owl_center'  => 'yes',
					),
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post',
				array(
					'label'    => esc_html__( 'Post', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post)',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_thumbnail_wrapper',
				array(
					'label'    => esc_html__( 'Thumbnail Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.center .kata-blog-post:not(.kata-first-post) .kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_progress_bar',
				array(
					'label'     => esc_html__( 'Progress Bar', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_carousel' => 'yes',
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
			// end copy
		}

		protected function render() {
			require __DIR__ . '/view.php';
		}
	} // class
}
