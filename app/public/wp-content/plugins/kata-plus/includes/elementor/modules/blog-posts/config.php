<?php
/**
 * posts module config.
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
use Elementor\Repeater;

if ( ! class_exists( 'Kata_Plus_Blog_Posts' ) ) {
	class Kata_Plus_Blog_Posts extends Widget_Base {
		public function get_name() {
			return 'kata-plus-blog-posts-new';
		}

		public function get_title() {
			return esc_html__( 'Blog Posts', 'kata-plus' );
		}

		public function get_icon() {
			return 'kata-widget kata-eicon-post-list';
		}

		public function get_categories() {
			return array( 'kata_plus_elementor_blog_and_post' );
		}

		public function get_style_depends() {
			return array( 'kata-plus-owlcarousel', 'kata-plus-owl', 'kata-plus-blog-posts', 'zilla-likes' );
		}

		public function get_script_depends() {
			return array( 'kata-plus-owlcarousel', 'kata-plus-owlcarousel-thumbs', 'kata-plus-owl', 'zilla-likes', 'kata-blog-posts' );
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
					'label'       => __( 'Categories', 'kata-plus' ),
					'description' => __( 'Select one or more categories to display posts (optional).', 'kata-plus' ),
					'type'        => Controls_Manager::SELECT2,
					'options'     => $cat_options,
					'default'     => array(),
					'multiple'    => true,
				)
			);
			$tag_options = array();
			foreach ( get_tags() as $tag ) {
				$tag_options[ $tag->slug ] = $tag->name;
			}
			$this->add_control(
				'query_tags',
				array(
					'label'       => __( 'Tags', 'kata-plus' ),
					'description' => __( 'Select one or more tags to display posts (optional).', 'kata-plus' ),
					'type'        => Controls_Manager::SELECT2,
					'options'     => $tag_options,
					'default'     => array(),
					'multiple'    => true,
				)
			);
			$this->add_control(
				'query_order_by',
				array(
					'label'       => esc_html__( 'Order By', 'kata-plus' ),
					'description' => esc_html__( 'Ordering article by', 'kata-plus' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'date',
					'options'     => array(
						'date'          => esc_html__( 'Date', 'kata-plus' ),
						'title'         => esc_html__( 'Title', 'kata-plus' ),
						'comment_count' => esc_html__( 'Comment Count', 'kata-plus' ),
						'menu_order'    => esc_html__( 'Menu Order', 'kata-plus' ),
						'rand'          => esc_html__( 'Random', 'kata-plus' ),
						'most_liked'    => esc_html__( 'Most Liked (Trend)', 'kata-plus' ),
						'most_viewed'   => esc_html__( 'Most Viewed (Popular)', 'kata-plus' ),
					),
				)
			);
			$this->add_control(
				'query_order',
				array(
					'label'   => esc_html__( 'Sort', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => array(
						'DESC' => esc_html__( 'DESC', 'kata-plus' ),
						'ASC'  => esc_html__( 'ASC', 'kata-plus' ),
					),
				)
			);
			$this->end_controls_section();

			/**
			 * Settings
			 */
			$this->start_controls_section(
				'posts_section',
				array(
					'label' => esc_html__( 'Settings', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$this->add_responsive_control(
				'post_columns',
				array(
					'label'     => esc_html__( 'Columns Number', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '3',
					'options'   => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'condition' => array(
						'enable_carousel!' => 'yes',
					),
					'selectors' => array(
						'{{WRAPPER}} .kata-blog-posts .row' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
					),
				)
			);
			$this->add_control(
				'post_per_page',
				array(
					'label'       => __( 'Posts Per Page', 'kata-plus' ),
					'description' => __( 'How many posts must show in each page.', 'kata-plus' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => -1,
					'step'        => 1,
					'default'     => 10,
				)
			);
			$this->add_control(
				'post_pagination',
				array(
					'label'       => esc_html__( 'Pagination', 'kata-plus' ),
					'description' => esc_html__( 'Enable pagination for posts.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Show', 'kata-plus' ),
					'label_off'   => esc_html__( 'Hide', 'kata-plus' ),
					'default'     => '',
					'condition'   => array(
						'enable_carousel!'  => 'yes',
						'load_more_button!' => 'yes',
					),
				)
			);
			$this->add_control(
				'load_more_button',
				array(
					'label'       => esc_html__( 'Load More Button', 'kata-plus' ),
					'description' => esc_html__( 'Enable load more button for posts.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Show', 'kata-plus' ),
					'label_off'   => esc_html__( 'Hide', 'kata-plus' ),
					'default'     => '',
					'condition'   => array(
						'enable_carousel!' => 'yes',
						'post_pagination!' => 'yes',
					),
				)
			);
			$this->add_control(
				'load_more_button_text',
				array(
					'label'     => __( 'Button Text', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Show more posts', 'kata-plus' ),
					'condition' => array(
						'enable_carousel!' => 'yes',
						'post_pagination!' => 'yes',
						'load_more_button' => 'yes',
					),
				)
			);
			$this->add_control(
				'enable_carousel',
				array(
					'label'       => __( 'Carousel', 'kata-plus' ),
					'description' => __( 'Enable carousel view for posts.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => __( 'Yes', 'kata-plus' ),
					'label_off'   => __( 'No', 'kata-plus' ),
					'default'     => '',
				)
			);
			$this->add_control(
				'first_post',
				array(
					'label'        => __( 'Distinct First Post', 'kata-plus' ),
					'description'  => __( 'Separate the first post of the widget and style it separately.', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'kata-plus' ),
					'label_off'    => __( 'No', 'kata-plus' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$this->add_control(
				'sort_by',
				array(
					'label'        => __( 'Sorting Option', 'kata-plus' ),
					'description'  => __( 'By enabling this option, users can change the post view according to their needs.', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'kata-plus' ),
					'label_off'    => __( 'No', 'kata-plus' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$this->end_controls_section();

			/**
			 * First Post Components
			 */
			$this->start_controls_section(
				'posts_metadata_section_first_post',
				array(
					'label'     => esc_html__( 'First Post Components', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$first_post = new Repeater();
			$first_post->add_control(
				'post_repeater_select_2',
				array(
					'label'   => esc_html__( 'Component', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'title',
					'options' => array(
						'title'                   => esc_html__( 'Title', 'kata-plus' ),
						'thumbnail'               => esc_html__( 'Thumbnail', 'kata-plus' ),
						'excerpt'                 => esc_html__( 'Excerpt', 'kata-plus' ),
						'post_format'             => esc_html__( 'Post Format', 'kata-plus' ),
						'categories'              => esc_html__( 'Categories', 'kata-plus' ),
						'tags'                    => esc_html__( 'Tags', 'kata-plus' ),
						'date'                    => esc_html__( 'Date', 'kata-plus' ),
						'bookmark'                => esc_html__( 'Bookmark', 'kata-plus' ),
						'comments'                => esc_html__( 'Comments', 'kata-plus' ),
						'author'                  => esc_html__( 'Author', 'kata-plus' ),
						'time_to_read'            => esc_html__( 'Time to read', 'kata-plus' ),
						'share_post'              => esc_html__( 'Share Post', 'kata-plus' ),
						'share_post_counter'      => esc_html__( 'Share Counter', 'kata-plus' ),
						'post_view'               => esc_html__( 'View', 'kata-plus' ),
						'post_like'               => esc_html__( 'Like', 'kata-plus' ),
						'read_more'               => esc_html__( 'Read More', 'kata-plus' ),
						'start_content_wrapper'   => esc_html__( 'Content div Open', 'kata-plus' ),
						'end_content_wrapper'     => esc_html__( 'Content div Close', 'kata-plus' ),
						'start_meta_data_wrapper' => esc_html__( 'Metadata div Open', 'kata-plus' ),
						'end_meta_data_wrapper'   => esc_html__( 'Metadata div Close', 'kata-plus' ),
					),
				)
			);
			$first_post->add_control(
				'posts_title_tag',
				array(
					'label'     => esc_html__( 'post Title Tag', 'kata-plus' ),
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
						'post_repeater_select_2' => 'title',
					),
				)
			);
			$first_post->add_control(
				'title_animation',
				array(
					'label'     => esc_html__( 'Animation Title', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'none'                      => __( 'None', 'kata-plus' ),
						'kata-post-title-underline' => __( 'Underline', 'kata-plus' ),
					),
					'default'   => 'none',
					'condition' => array(
						'post_repeater_select_2' => 'title',
					),
				)
			);
			$first_post->add_control(
				'thumbnail_size',
				array(
					'label'     => esc_html__( 'Thumbnail Size', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'full',
					'options'   => array(
						'full'   => esc_html__( 'Full', 'kata-plus' ),
						'custom' => esc_html__( 'Custom', 'kata-plus' ),
					),
					'condition' => array(
						'post_repeater_select_2' => 'thumbnail',
					),
				)
			);
			$first_post->add_control(
				'posts_thumbnail_custom_size',
				array(
					'label'       => __( 'post Thumbnail Custom Size', 'kata-plus' ),
					'description' => __( 'Crop the original image size to any custom size.', 'kata-plus' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'default'     => array(
						'width'  => '',
						'height' => '',
					),
					'condition'   => array(
						'post_repeater_select_2' => 'thumbnail',
						'thumbnail_size'         => 'custom',
					),
				)
			);
			$first_post->add_control(
				'excerpt_length',
				array(
					'label'     => __( 'post Excerpt Length', 'kata-plus' ),
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
						'post_repeater_select_2' => 'excerpt',
					),
				)
			);
			$first_post->add_control(
				'ignore_port_format',
				array(
					'label'       => __( 'Ignore Post Format', 'kata-plus' ),
					'description' => __( 'By turning on this option, WordPress default output for content will be ignored.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => __( 'Enable', 'kata-plus' ),
					'label_off'   => __( 'Disable', 'kata-plus' ),
					'default'     => 'yes',
					'condition'   => array(
						'post_repeater_select_2' => 'excerpt',
					),
				)
			);
			$first_post->add_control(
				'post_category_icon',
				array(
					'label'     => esc_html__( 'Category Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/folder',
					'condition' => array(
						'post_repeater_select_2' => 'categories',
					),
				)
			);
			$first_post->add_control(
				'post_comments_icon',
				array(
					'label'     => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/comments',
					'condition' => array(
						'post_repeater_select_2' => 'comments',
					),
				)
			);
			$first_post->add_control(
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
						'post_repeater_select_2' => 'author',
					),
				)
			);
			$first_post->add_control(
				'post_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/user',
					'condition' => array(
						'post_author_symbol'     => 'icon',
						'post_repeater_select_2' => 'author',
					),
				)
			);
			$first_post->add_control(
				'avatar_size',
				array(
					'label'     => __( 'Avatar Size', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 5,
					'max'       => 300,
					'step'      => 1,
					'default'   => 20,
					'condition' => array(
						'post_repeater_select_2' => 'author',
						'post_author_symbol'     => 'avatar',
					),
				)
			);
			$first_post->add_control(
				'post_time_to_read_icon',
				array(
					'label'     => esc_html__( 'Time To Read Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/time',
					'condition' => array(
						'post_repeater_select_2' => 'time_to_read',
					),
				)
			);
			$first_post->add_control(
				'share_post_icon',
				array(
					'label'     => esc_html__( 'Post Share Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/share',
					'condition' => array(
						'post_repeater_select_2' => 'share_post',
					),
				)
			);
			$first_post->add_control(
				'post_share_post_counter_icon',
				array(
					'label'     => esc_html__( 'Share Counter Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/action-redo',
					'condition' => array(
						'post_repeater_select_2' => 'share_post_counter',
					),
				)
			);
			$first_post->add_control(
				'post_view_icon',
				array(
					'label'     => esc_html__( 'Views Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/eye',
					'condition' => array(
						'post_repeater_select_2' => 'post_view',
					),
				)
			);
			$first_post->add_control(
				'post_tag_icon',
				array(
					'label'     => esc_html__( 'Tag Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/tag',
					'condition' => array(
						'post_repeater_select_2' => 'tags',
					),
				)
			);
			$first_post->add_control(
				'post_format_gallery_icon',
				array(
					'label'     => esc_html__( 'Gallery Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/gallery',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_link_icon',
				array(
					'label'     => esc_html__( 'Link Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/link',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_image_icon',
				array(
					'label'     => esc_html__( 'Image Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/image',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_quote_icon',
				array(
					'label'     => esc_html__( 'Quote Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/quote-left',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_status_icon',
				array(
					'label'     => esc_html__( 'Status Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/pencil',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_video_icon',
				array(
					'label'     => esc_html__( 'Video Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/video-camera',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_aside_icon',
				array(
					'label'     => esc_html__( 'Aside Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/plus',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'post_format_standard_icon',
				array(
					'label'     => esc_html__( 'Standard Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/notepad',
					'condition' => array(
						'post_repeater_select_2' => 'post_format',
					),
				)
			);
			$first_post->add_control(
				'bookmark_icon',
				array(
					'label'     => esc_html__( 'Bookmark Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/bookmark',
					'condition' => array(
						'post_repeater_select_2' => 'bookmark',
					),
				)
			);
			$first_post->add_control(
				'post_date_icon',
				array(
					'label'     => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/calendar',
					'condition' => array(
						'post_repeater_select_2' => 'date',
					),
				)
			);
			$first_post->add_control(
				'custom_date_format',
				array(
					'label'        => esc_html__( 'Custom Date Format', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'On', 'kata-plus' ),
					'label_off'    => __( 'Off', 'kata-plus' ),
					'return_value' => 'yes',
					'condition'    => array(
						'post_repeater_select_2' => 'date',
					),
				)
			);
			$first_post->add_control(
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
			$first_post->add_control(
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
			$first_post->add_control(
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
			$first_post->add_control(
				'terms_separator',
				array(
					'label'     => __( 'Separator', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => ',',
					'condition' => array(
						'post_repeater_select_2' => array(
							'categories',
							'tags',
						),
					),
				)
			);
			$first_post->add_control(
				'read_more_text',
				array(
					'label'     => __( 'Read More Text', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Read More',
					'condition' => array(
						'post_repeater_select_2' => 'read_more',
					),
				)
			);
			$this->add_control(
				'post_repeaters_2',
				array(
					'label'       => __( 'Component Wrapper', 'kata-plus' ),
					'description' => __( 'Add, Remove and edit articles components', 'kata-plus' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $first_post->get_controls(),
					'title_field' => '{{{ post_repeater_select_2 }}}',
					'default'     => array(
						array( 'post_repeater_select_2' => 'thumbnail' ),
						array( 'post_repeater_select_2' => 'title' ),
						array( 'post_repeater_select_2' => 'excerpt' ),
						array( 'post_repeater_select_2' => 'categories' ),
						array( 'post_repeater_select_2' => 'tags' ),
						array( 'post_repeater_select_2' => 'date' ),
					),
					'condition'   => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->end_controls_section();

			/**
			 * Posts Components
			 */
			$this->start_controls_section(
				'posts_metadata_section',
				array(
					'label' => esc_html__( 'Posts Components', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);
			$posts = new Repeater();
			$posts->add_control(
				'post_repeater_select',
				array(
					'label'   => esc_html__( 'Component', 'kata-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'title',
					'options' => array(
						'title'                   => esc_html__( 'Title', 'kata-plus' ),
						'thumbnail'               => esc_html__( 'Thumbnail', 'kata-plus' ),
						'excerpt'                 => esc_html__( 'Excerpt', 'kata-plus' ),
						'post_format'             => esc_html__( 'Post Format', 'kata-plus' ),
						'categories'              => esc_html__( 'Categories', 'kata-plus' ),
						'tags'                    => esc_html__( 'Tags', 'kata-plus' ),
						'date'                    => esc_html__( 'Date', 'kata-plus' ),
						'bookmark'                => esc_html__( 'Bookmark', 'kata-plus' ),
						'comments'                => esc_html__( 'Comments', 'kata-plus' ),
						'author'                  => esc_html__( 'Author', 'kata-plus' ),
						'time_to_read'            => esc_html__( 'Time to read', 'kata-plus' ),
						'share_post'              => esc_html__( 'Share Post', 'kata-plus' ),
						'share_post_counter'      => esc_html__( 'Share Counter', 'kata-plus' ),
						'post_view'               => esc_html__( 'View', 'kata-plus' ),
						'post_like'               => esc_html__( 'Like', 'kata-plus' ),
						'read_more'               => esc_html__( 'Read More', 'kata-plus' ),
						'start_content_wrapper'   => esc_html__( 'Content div Open', 'kata-plus' ),
						'end_content_wrapper'     => esc_html__( 'Content div Close', 'kata-plus' ),
						'start_meta_data_wrapper' => esc_html__( 'Metadata div Open', 'kata-plus' ),
						'end_meta_data_wrapper'   => esc_html__( 'Metadata div Close', 'kata-plus' ),
					),
				)
			);
			$posts->add_control(
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
						'post_repeater_select' => 'title',
					),
				)
			);
			$posts->add_control(
				'title_animation',
				array(
					'label'     => esc_html__( 'Animation Title', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'none'                      => __( 'None', 'kata-plus' ),
						'kata-post-title-underline' => __( 'Underline', 'kata-plus' ),
					),
					'default'   => 'none',
					'condition' => array(
						'post_repeater_select' => 'title',
					),
				)
			);
			$posts->add_control(
				'thumbnail_size',
				array(
					'label'     => esc_html__( 'Thumbnail Size', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'full',
					'options'   => array(
						'full'   => esc_html__( 'Full', 'kata-plus' ),
						'custom' => esc_html__( 'Custom', 'kata-plus' ),
					),
					'condition' => array(
						'post_repeater_select' => 'thumbnail',
					),
				)
			);
			$posts->add_control(
				'posts_thumbnail_custom_size',
				array(
					'label'       => __( 'post Thumbnail Custom Size', 'kata-plus' ),
					'description' => __( 'Crop the original image size to any custom size.', 'kata-plus' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'default'     => array(
						'width'  => '',
						'height' => '',
					),
					'condition'   => array(
						'post_repeater_select' => 'thumbnail',
						'thumbnail_size'       => 'custom',
					),
				)
			);
			$posts->add_control(
				'excerpt_length',
				array(
					'label'     => __( 'post Excerpt Length', 'kata-plus' ),
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
						'post_repeater_select' => 'excerpt',
					),
				)
			);
			$posts->add_control(
				'ignore_port_format',
				array(
					'label'       => __( 'Ignore Post Format', 'kata-plus' ),
					'description' => __( 'By turning on this option, WordPress default output for content will be ignored.', 'kata-plus' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => __( 'Enable', 'kata-plus' ),
					'label_off'   => __( 'Disable', 'kata-plus' ),
					'default'     => 'yes',
					'condition'   => array(
						'post_repeater_select' => 'excerpt',
					),
				)
			);
			$posts->add_control(
				'post_category_icon',
				array(
					'label'     => esc_html__( 'Category Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/folder',
					'condition' => array(
						'post_repeater_select' => 'categories',
					),
				)
			);
			$posts->add_control(
				'post_comments_icon',
				array(
					'label'     => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/comments',
					'condition' => array(
						'post_repeater_select' => 'comments',
					),
				)
			);
			$posts->add_control(
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
						'post_repeater_select' => 'author',
					),
				)
			);
			$posts->add_control(
				'post_author_icon',
				array(
					'label'     => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/user',
					'condition' => array(
						'post_author_symbol'   => 'icon',
						'post_repeater_select' => 'author',
					),
				)
			);
			$posts->add_control(
				'avatar_size',
				array(
					'label'     => __( 'Avatar Size', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 5,
					'max'       => 300,
					'step'      => 1,
					'default'   => 20,
					'condition' => array(
						'post_repeater_select' => 'author',
						'post_author_symbol'   => 'avatar',
					),
				)
			);
			$posts->add_control(
				'post_time_to_read_icon',
				array(
					'label'     => esc_html__( 'Time To Read Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/time',
					'condition' => array(
						'post_repeater_select' => 'time_to_read',
					),
				)
			);
			$posts->add_control(
				'share_post_icon',
				array(
					'label'     => esc_html__( 'Post Share Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/share',
					'condition' => array(
						'post_repeater_select' => 'share_post',
					),
				)
			);
			$posts->add_control(
				'post_share_post_counter_icon',
				array(
					'label'     => esc_html__( 'Share Counter Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/action-redo',
					'condition' => array(
						'post_repeater_select' => 'share_post_counter',
					),
				)
			);
			$posts->add_control(
				'post_view_icon',
				array(
					'label'     => esc_html__( 'Views Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'simple-line/eye',
					'condition' => array(
						'post_repeater_select' => 'post_view',
					),
				)
			);
			$posts->add_control(
				'post_tag_icon',
				array(
					'label'     => esc_html__( 'Tag Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/tag',
					'condition' => array(
						'post_repeater_select' => 'tags',
					),
				)
			);
			$posts->add_control(
				'post_format_gallery_icon',
				array(
					'label'     => esc_html__( 'Gallery Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/gallery',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_link_icon',
				array(
					'label'     => esc_html__( 'Link Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/link',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_image_icon',
				array(
					'label'     => esc_html__( 'Image Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/image',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_quote_icon',
				array(
					'label'     => esc_html__( 'Quote Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/quote-left',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_status_icon',
				array(
					'label'     => esc_html__( 'Status Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/pencil',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_video_icon',
				array(
					'label'     => esc_html__( 'Video Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/video-camera',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_aside_icon',
				array(
					'label'     => esc_html__( 'Aside Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/plus',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'post_format_standard_icon',
				array(
					'label'     => esc_html__( 'Standard Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/notepad',
					'condition' => array(
						'post_repeater_select' => 'post_format',
					),
				)
			);
			$posts->add_control(
				'bookmark_icon',
				array(
					'label'     => esc_html__( 'Bookmark Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/bookmark',
					'condition' => array(
						'post_repeater_select' => 'bookmark',
					),
				)
			);
			$posts->add_control(
				'post_date_icon',
				array(
					'label'     => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'      => 'kata_plus_icons',
					'default'   => 'themify/calendar',
					'condition' => array(
						'post_repeater_select' => 'date',
					),
				)
			);
			$posts->add_control(
				'custom_date_format',
				array(
					'label'        => esc_html__( 'Custom Date Format', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'On', 'kata-plus' ),
					'label_off'    => __( 'Off', 'kata-plus' ),
					'return_value' => 'yes',
					'condition'    => array(
						'post_repeater_select' => 'date',
					),
				)
			);
			$posts->add_control(
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
			$posts->add_control(
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
			$posts->add_control(
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
			$posts->add_control(
				'terms_separator',
				array(
					'label'     => __( 'Separator', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => ',',
					'condition' => array(
						'post_repeater_select' => array(
							'categories',
							'tags',
						),
					),
				)
			);
			$posts->add_control(
				'read_more_text',
				array(
					'label'     => __( 'Read More Text', 'kata-plus' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Read More',
					'condition' => array(
						'post_repeater_select' => 'read_more',
					),
				)
			);
			$this->add_control(
				'post_repeaters',
				array(
					'label'       => __( 'Component Wrapper', 'kata-plus' ),
					'description' => __( 'Add, Remove and edit articles components', 'kata-plus' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $posts->get_controls(),
					'title_field' => '{{{ post_repeater_select }}}',
					'default'     => array(
						array( 'post_repeater_select' => 'thumbnail' ),
						array( 'post_repeater_select' => 'title' ),
						array( 'post_repeater_select' => 'excerpt' ),
						array( 'post_repeater_select' => 'categories' ),
						array( 'post_repeater_select' => 'tags' ),
						array( 'post_repeater_select' => 'date' ),
					),
				)
			);
			$this->end_controls_section();

			/**
			 * Owl option
			 */
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
					'label'       => __( 'Items Per View', 'kata-plus' ),
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
					'label'        => __( 'Active Center Item', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'kata-plus' ),
					'label_off'    => __( 'No', 'kata-plus' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$this->add_control(
				'active_item',
				array(
					'label'     => __( 'Active Item', 'kata-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'left',
					'options'   => array(
						'left'  => __( 'Left', 'kata-plus' ),
						'right' => __( 'Right', 'kata-plus' ),
					),
					'condition' => array(
						'inc_owl_center!' => 'yes',
					),
				)
			);
			$this->add_control(
				'inc_owl_thumbnail',
				array(
					'label'        => __( 'Thumbnails', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'kata-plus' ),
					'label_off'    => __( 'No', 'kata-plus' ),
					'return_value' => 'true',
					'default'      => 'no',
				)
			);
			$this->add_control(
				'thumbs_size',
				array(
					'label'       => __( 'Thumbnail Dimension', 'kata-plus' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'kata-plus' ),
					'default'     => array(
						'width'  => '100',
						'height' => '100',
					),
					'condition'   => array(
						'inc_owl_thumbnail' => 'true',
					),
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
					'label'     => esc_html__( 'Carousel Stage', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.owl-stage-outer',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'enable_carousel' => 'yes',
					),
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
				'styler_posts_post_wrapper',
				array(
					'label'     => esc_html__( 'Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.ktbl-post-wrapper',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'enable_carousel' => 'yes',
					),
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
					'selector' => '.kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'post_video_icon_styler',
				array(
					'label'    => esc_html__( 'Video Play Button', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post-video-player',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
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
					'selector' => '.kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->end_controls_section();

			// Posts Pagination Style section
			$this->start_controls_section(
				'posts_pagination_style_section',
				array(
					'label'     => esc_html__( 'Pagination', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_carousel!' => 'yes',
					),
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
					'label'    => esc_html__( 'Item', 'kata-plus' ),
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
					'label'    => esc_html__( 'Active Item', 'kata-plus' ),
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
					'label'    => esc_html__( 'Prev & Next Button', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .kata-post-pagination a.next.page-numbers, .kata-blog-posts .kata-post-pagination a.prev.page-numbers',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'sort_by_wrapper',
				array(
					'label'    => esc_html__( 'Sorting Option Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-posts .ktbl-sortoptions',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'sort_by_items',
				array(
					'label'     => esc_html__( 'Sorting Option Item', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-posts .ktbl-sortoptions option',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'after',
				)
			);
			$this->add_control(
				'load_more_button_wrapper_styler',
				array(
					'label'    => esc_html__( 'Load More Button Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.ktbl-load-more-wrap',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'load_more_button_styler',
				array(
					'label'    => esc_html__( 'Load More Button', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.ktbl-load-more',
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
					'label'     => esc_html__( 'Metadata Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-metadata',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_format_wrapper',
				array(
					'label'     => esc_html__( 'Post Format Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-format',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_format_icon',
				array(
					'label'    => esc_html__( 'Post Format Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-format .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_categories_wrapper',
				array(
					'label'     => esc_html__( 'Categories Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-category-links',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_categories',
				array(
					'label'    => esc_html__( 'Categories', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-category-links a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
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
					'selector' => '.kata-category-links i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_tags_wrapper',
				array(
					'label'     => esc_html__( 'Tags Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-tags-links',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_tags',
				array(
					'label'    => esc_html__( 'Tags', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-tags-links a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_tags_icon',
				array(
					'label'    => esc_html__( 'Tags Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-tags-links .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_wrapper',
				array(
					'label'     => esc_html__( 'Date Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-date',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date',
				array(
					'label'    => esc_html__( 'Date', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-date a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_1',
				array(
					'label'    => esc_html__( 'Custom Date Format 1', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-date a .kt-date-format1',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_2',
				array(
					'label'    => esc_html__( 'Custom Date Format 2', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-date a .kt-date-format2',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_3',
				array(
					'label'    => esc_html__( 'Custom Date Format 3', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-date a .kt-date-format3',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_date_icon',
				array(
					'label'    => esc_html__( 'Date Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-date i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments_wrapper',
				array(
					'label'     => esc_html__( 'Comments Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-comments-number',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments',
				array(
					'label'    => esc_html__( 'Comments', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-comments-number span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_comments_icon',
				array(
					'label'    => esc_html__( 'Comments Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-comments-number i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_wrapper',
				array(
					'label'    => esc_html__( 'Author Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-author',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author',
				array(
					'label'    => esc_html__( 'Author', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-author a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_icon',
				array(
					'label'    => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-author i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_author_avatar',
				array(
					'label'    => esc_html__( 'Author Avatar', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-author img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read_wrapper',
				array(
					'label'     => esc_html__( 'Time To Read Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-time-to-read',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read',
				array(
					'label'    => esc_html__( 'Time To Read', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-time-to-read span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_time_to_read_icon',
				array(
					'label'    => esc_html__( 'Time To Read Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-time-to-read i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count_wrapper',
				array(
					'label'     => esc_html__( 'Social Share Counter Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-share-count',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count',
				array(
					'label'    => esc_html__( 'Social Share Counter', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-share-count span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_social_share_count_icon',
				array(
					'label'    => esc_html__( 'Social Share Counter Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-share-count i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_share_post_wrapper',
				array(
					'label'     => esc_html__( 'Share Post Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kt-post-socials-share-wrapper',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_share_post_socials_wrapper',
				array(
					'label'    => esc_html__( 'Share Post Socials Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kt-post-share-toggle-socials-wrapper',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_share_post_socials_link',
				array(
					'label'    => esc_html__( 'Share Post Socials link', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kt-post-share-toggle-socials-wrapper a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_share_post_socials_link_icon',
				array(
					'label'    => esc_html__( 'Share Post Socials Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kt-post-share-toggle-socials-wrapper a svg',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_share_post_socials_icon',
				array(
					'label'    => esc_html__( 'Share Post Toggle Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kt-post-share-toggle-wrapper .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view_wrapper',
				array(
					'label'     => esc_html__( 'Post View Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-view',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view',
				array(
					'label'    => esc_html__( 'Post View', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-view span',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'styler_posts_metadata_post_post_view_icon',
				array(
					'label'    => esc_html__( 'Post View Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-view i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->end_controls_section();

			// First Post Style section
			$this->start_controls_section(
				'first_post_style_section',
				array(
					'label'     => esc_html__( 'First Post', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'first_post' => 'yes',
					),
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
					'selector' => '.kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->add_control(
				'styler_first_post_post_thumbnail_image',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->add_control(
				'styler_first_post_video_icon_styler',
				array(
					'label'    => esc_html__( 'Video Play Button', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post-video-player',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->add_control(
				'styler_first_post_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
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
					'selector' => '.kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->add_control(
				'styler_first_post_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-first-post',
				)
			);
			$this->end_controls_section();

			// Posts Metadata Style section
			$this->start_controls_section(
				'first_post_metadata_style_section',
				array(
					'label'     => esc_html__( 'First Post Metadata', 'kata-plus' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'first_post' => 'yes',
					),
				)
			);
			$this->add_control(
				'styler_first_posts_post_metadata_container',
				array(
					'label'    => esc_html__( 'Metadata Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-metadata',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post.kata-first-post',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_format_wrapper',
				array(
					'label'     => esc_html__( 'Post Format Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-post-format',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}} .kata-blog-post.kata-first-post',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_post_format_icon',
				array(
					'label'    => esc_html__( 'Post Format Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-format .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .kata-blog-post.kata-first-post',
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
					'selector'  => '.kata-first-post .kata-tags-links',
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
				'styler_first_post_metadata_post_tags_icon',
				array(
					'label'    => esc_html__( 'Tags icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-tags-links .kata-icon',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_date_wrapper',
				array(
					'label'     => esc_html__( 'Date Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-first-post .kata-post-date',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
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
					'label'    => esc_html__( 'Custom Date Format 1', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date a .kt-date-format1',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_posts_metadata_post_date_2',
				array(
					'label'    => esc_html__( 'Custom Date Format 2', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date a .kt-date-format2',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_posts_metadata_post_date_3',
				array(
					'label'    => esc_html__( 'Custom Date Format 3', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-date a .kt-date-format3',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
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
					'label'    => esc_html__( 'Author Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-author i',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_first_post_metadata_post_author_avatar',
				array(
					'label'    => esc_html__( 'Author Avatar', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-first-post .kata-post-author img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
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
				'first_styler_posts_metadata_share_post_wrapper',
				array(
					'label'     => esc_html__( 'Share Post Wrapper', 'kata-plus' ),
					'type'      => 'styler',
					'selector'  => '.kata-blog-post.kata-first-post .kt-post-socials-share-wrapper',
					'isSVG'     => true,
					'isInput'   => false,
					'wrapper'   => '{{WRAPPER}}',
					'separator' => 'before',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_share_post_socials_wrapper',
				array(
					'label'    => esc_html__( 'Share Post Socials Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kt-post-share-toggle-socials-wrapper',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_share_post_socials_link',
				array(
					'label'    => esc_html__( 'Share Post Socials link', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kt-post-share-toggle-socials-wrapper a',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_share_post_socials_link_icon',
				array(
					'label'    => esc_html__( 'Share Post Socials Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kt-post-share-toggle-socials-wrapper a svg',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'first_styler_posts_metadata_share_post_socials_icon',
				array(
					'label'    => esc_html__( 'Share Post Toggle Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-blog-post.kata-first-post .kt-post-share-toggle-wrapper .kata-icon',
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
					'label'    => esc_html__( 'Slider Left Arrow Wrapper', 'kata-plus' ),
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
					'label'    => esc_html__( 'Slider Left Arrow', 'kata-plus' ),
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
					'label'    => esc_html__( 'Slider Right Arrow Wrapper', 'kata-plus' ),
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
					'label'    => esc_html__( 'Slider Right Arrow', 'kata-plus' ),
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
						'inc_owl_center!' => 'yes',
					),
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post',
				array(
					'label'    => esc_html__( 'Post', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
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
					'selector' => '.kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_active_styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.kata-owl-active-item .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'carousel_center_item_section',
				array(
					'label'     => esc_html__( 'Active Center Item', 'kata-plus' ),
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
					'selector' => '.kata-post-thumbnail',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_thumbnail',
				array(
					'label'    => esc_html__( 'Thumbnail', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-thumbnail img',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_content_wrapper',
				array(
					'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-content',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_title',
				array(
					'label'    => esc_html__( 'Title', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-title',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_excerpt',
				array(
					'label'    => esc_html__( 'Excerpt', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-excerpt',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->add_control(
				'carousel_center_styler_posts_post_read_more',
				array(
					'label'    => esc_html__( 'Read More', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-post-readmore',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}} .owl-item.center .kata-blog-post:not(.kata-first-post)',
				)
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_thumbnails',
				array(
					'label' => esc_html__( 'Thumbnails', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_control(
				'styler_thumbnail_wrapper',
				array(
					'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-thumbs',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_thumbnail_item',
				array(
					'label'    => esc_html__( 'Items', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-thumbs .owl-thumb-item',
					'isSVG'    => true,
					'isInput'  => false,
					'wrapper'  => '{{WRAPPER}}',
				)
			);
			$this->add_control(
				'styler_thumbnail_item_img',
				array(
					'label'    => esc_html__( 'Images', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.owl-thumbs .owl-thumb-item img',
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
