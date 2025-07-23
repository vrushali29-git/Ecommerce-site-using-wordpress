<?php
/**
 * Grid module config.
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

class Kata_Plus_Grid extends Widget_Base {
	public function get_name() {
		return 'kata-plus-grid';
	}

	public function get_title() {
		return esc_html__( 'Portfolio', 'kata-plus-pro' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-posts-grid';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-grid', 'kata-plus-lightgallery' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-lightgallery', 'kata-plus-grid-js' );
	}

	protected function register_controls() {
		// Categories
		$terms              = get_terms( 'grid_category' );
		$categories_options = array();
		foreach ( $terms as $term ) {
			$categories_options[ $term->slug ] = $term->name;
		}
		// Posts
		$args          = array(
			'post_type'   => 'kata_grid',
			'post_status' => 'publish',
			'order'       => 'DESC',
		);
		$arr_posts     = new WP_Query( $args );
		$posts_options = array();
		foreach ( $arr_posts->get_posts() as $post ) {
			$posts_options[ $post->ID ] = $post->post_title;
		}
		$this->start_controls_section(
			'kata_plus_grid_content',
			array(
				'label' => esc_html__( 'Content Settings', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'kata_plus_grid_mode', // param_name
			array(
				'label'   => esc_html__( 'Content', 'kata-plus-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'all_posts'    => esc_html__( 'All Posts', 'kata-plus-pro' ),
					'custom_posts' => esc_html__( 'Custom Posts', 'kata-plus-pro' ),
				),
				'default' => 'all_posts',
			)
		);
		$this->add_control(
			'posts_per_page', // param_name
			array(
				'label'       => __( 'Posts per page', 'kata-plus-pro' ),
				'description' => __( 'Set to "-1" to show all posts', 'kata-plus-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -1,
				'max'         => 1000,
				'step'        => 1,
				'default'     => 10,
			)
		);
		$this->add_control(
			'kata_plus_grid_categories_mode', // param_name
			array(
				'label'     => esc_html__( 'Categories', 'kata-plus-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'all'    => esc_html__( 'All', 'kata-plus-pro' ),
					'custom' => esc_html__( 'Custom', 'kata-plus-pro' ),
				),
				'default'   => 'all',
				'condition' => array(
					'kata_plus_grid_mode' => 'custom_posts',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_categories', // param_name
			array(
				'label'     => esc_html__( 'Select Categories', 'kata-plus-pro' ),
				'type'      => Controls_Manager::SELECT2,
				'dynamic'   => array( 'active' => true ),
				'multiple'  => true,
				'options'   => $categories_options,
				'default'   => '',
				'condition' => array(
					'kata_plus_grid_mode'            => 'custom_posts',
					'kata_plus_grid_categories_mode' => 'custom',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_show_posts', // param_name
			array(
				'label'     => esc_html__( 'Show Posts', 'kata-plus-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'all'    => esc_html__( 'All', 'kata-plus-pro' ),
					'custom' => esc_html__( 'Custom', 'kata-plus-pro' ),
				),
				'default'   => 'all',
				'condition' => array(
					'kata_plus_grid_mode'            => 'custom_posts',
					'kata_plus_grid_categories_mode' => 'all',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_posts', // param_name
			array(
				'label'     => esc_html__( 'Select Posts', 'kata-plus-pro' ),
				'type'      => Controls_Manager::SELECT2,
				'dynamic'   => array(
					'active' => true,
				),
				'multiple'  => true,
				'options'   => $posts_options,
				'condition' => array(
					'kata_plus_grid_mode'            => 'custom_posts',
					'kata_plus_grid_show_posts'      => 'custom',
					'kata_plus_grid_categories_mode' => 'all',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Settings', 'kata-plus-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_responsive_control(
			'kata_plus_grid_settings_items', // param_name
			array(
				'label'      => esc_html__( 'Items Per Row', 'kata-plus-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'post' ),
				'range'      => array(
					'post' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'post',
					'size' => 3,
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'kata_plus_grid_appearance',
			array(
				'label' => esc_html__( 'Appearance Settings', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'thumbnail_size',
			array(
				'label'       => __( 'Thumbnail Dimension', 'kata-plus-pro' ),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'kata-plus-pro' ),
				'default'     => array(
					'width'  => '450',
					'height' => '450',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_show_title', // param_name
			array(
				'label'        => esc_html__( 'Title', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'Hide', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'kata_plus_grid_show_date', // param_name
			array(
				'label'        => esc_html__( 'Date', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'Hide', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'kata_plus_grid_show_item_categories', // param_name
			array(
				'label'        => esc_html__( 'Categories', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'Hide', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'categories_all_item',
			array(
				'label'     => __( '​Display name of all items', 'kata-plus-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'All',
				'condition' => array(
					'kata_plus_grid_show_item_categories' => 'yes',
				),
			)
		);
		$this->add_control(
			'categories_seperator',
			array(
				'label'   => __( 'Category Separator', 'kata-plus-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ',',
			)
		);
		$this->add_control(
			'kata_plus_grid_show_excerpt', // param_name
			array(
				'label'        => esc_html__( 'Excerpt', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'Hide', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'kata_plus_grid_show_modal',
			array(
				'label'   => esc_html__( 'Link', 'kata-plus-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'modal'  => esc_html__( 'Modal', 'kata-plus-pro' ),
					'single' => esc_html__( 'Single Post', 'kata-plus-pro' ),
				),
				'default' => 'modal',
				'toggle'  => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'design_tab',
			array(
				'label' => esc_html__( 'Design', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'post_has_link',
			array(
				'label'        => esc_html__( 'Use Link', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Use', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'No', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$repeater->add_control(
			'kata_plus_grid_post_link',
			array(
				'label'         => esc_html__( 'Link', 'kata-plus-pro' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'condition'     => array( 'post_has_link' => 'yes' ),
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);
		$repeater->add_control(
			'kata_plus_grid_post_icon',
			array(
				'label'   => esc_html__( 'Icon', 'kata-plus-pro' ),
				'type'    => 'kata_plus_icons',
				'default' => 'font-awesome/twitter',
			)
		);
		$repeater->add_control(
			'styler_grid_nav_post_icon',
			array(
				'label'    => esc_html__( 'Icon Style', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}}',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'kata_plus_grid_posts_icons',
			array(
				'label'         => esc_html__( 'Add Icons', 'kata-plus-pro' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'title_field'   => '{{{ kata_plus_grid_post_icon }}}',
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'grid_nav_post_element_title',
			array(
				'label'   => esc_html__( 'Title', 'kata-plus-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'New Element',
			)
		);
		$repeater->add_control(
			'styler_grid_nav_post_element',
			array(
				'label'    => esc_html__( 'Element', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}}',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'kata_plus_grid_posts_elements',
			array(
				'label'         => esc_html__( 'Element', 'kata-plus-pro' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'title_field'   => '{{{grid_nav_post_element_title}}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'load_more_tab',
			array(
				'label' => esc_html__( 'See More Settings', 'kata-plus-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_responsive_control(
			'kata_plus_grid_load_more', // param_name
			array(
				'label'        => esc_html__( 'Show See More Button', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'Hide', 'kata-plus-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'kata_plus_grid_load_more_text',
			array(
				'label'     => esc_html__( 'See More Text', 'kata-plus-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'See More',
				'condition' => array(
					'kata_plus_grid_load_more' => 'yes',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_load_more_link', // param_name
			array(
				'label'       => esc_html__( 'Link', 'kata-plus-pro' ), // heading
				'type'        => Controls_Manager::URL, // type
				'placeholder' => esc_html__( 'https://your-link.com', 'kata-plus-pro' ),
				'default'     => array(
					'url'         => get_site_url( null, '/grid/' ),
					'is_external' => true,
					'nofollow'    => true,
				),
				'condition'   => array(
					'kata_plus_grid_load_more' => 'yes',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_load_more_icon',
			array(
				'label'     => esc_html__( 'See More Button Icon', 'kata-plus-pro' ),
				'type'      => 'kata_plus_icons',
				'condition' => array(
					'kata_plus_grid_load_more' => 'yes',
				),
			)
		);
		$this->add_control(
			'kata_plus_grid_load_more_icon_position',
			array(
				'label'     => esc_html__( 'See More Icon Position', 'kata-plus-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'after'  => esc_html__( 'After', 'kata-plus-pro' ),
					'before' => esc_html__( 'Before', 'kata-plus-pro' ),
				),
				'default'   => 'after',
				'condition' => array(
					'kata_plus_grid_load_more'       => 'yes',
					'kata_plus_grid_load_more_icon!' => '',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_grid',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Item', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_grid_post_item',
			array(
				'label'    => esc_html__( 'Item', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid .kata-grid-item',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_grid_overlay',
			array(
				'label'    => esc_html__( 'Overlay', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-overlay',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_content_wrapper',
			array(
				'label'    => esc_html__( 'Content Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-grid-content-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_thumbnail_wrapper',
			array(
				'label'    => esc_html__( 'Thumbnail Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-image',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_image',
			array(
				'label'    => esc_html__( 'Thumbnail', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-image img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_date',
			array(
				'label'    => esc_html__( 'Date', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-date',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_excerpt',
			array(
				'label'    => esc_html__( 'Excerpt', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-excerpt',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_post_categories_wrapper',
			array(
				'label'    => esc_html__( 'Categories Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-item-category',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_post_categories_a',
			array(
				'label'    => esc_html__( 'Categories item', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-item-category a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_post_categories_separator',
			array(
				'label'    => esc_html__( 'Categories Separator', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.grid-item-category .separator',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);
		$this->add_control(
			'styler_grid_nav_load_more_button',
			array(
				'label'    => esc_html__( 'See More button', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid-button',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_grid_nav_load_more_button_icon',
			array(
				'label'    => esc_html__( 'See More button icon', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid-button .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-grid-item',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cat_filter_style',
			array(
				'label' => esc_html__( 'Filters', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_grid_cat_filter_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid-wrap .masonry-category-filters',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_grid_cat_filter_itmes',
			array(
				'label'    => esc_html__( 'Items', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-grid-wrap .masonry-category-filters span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		// Style options End
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
} // Class
