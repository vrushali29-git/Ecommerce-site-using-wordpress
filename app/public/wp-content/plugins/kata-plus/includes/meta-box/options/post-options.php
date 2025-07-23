<?php
/**
 * Team Member Options - Meta Box.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Meta_Box_Post' ) ) {
	class Kata_Plus_Meta_Box_Post {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Meta_Box_Post
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return  object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->actions();
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ) );
		}

		public function meta_boxes( $meta_boxes ) {
			// Post Options
			$meta_boxes[] = array(
				'title'      => esc_html__( 'Post Options', 'kata-plus' ),
				'id'         => 'kata-post',
				'post_types' => array( 'post' ),
				'fields'     => array(
					// -> Header Tab
					array(
						'id'        => 'kata_show_header',
						'type'      => 'switch',
						'name'      => esc_attr__( 'Show Header:', 'kata-plus' ),
						'std'       => 1,
						'on_label'  => __( 'Show', 'kata-plus' ),
						'off_label' => __( 'Hide', 'kata-plus' ),
						'kata-tab'  => 'header',
					),
					array(
						'id'      => 'kata_make_header_transparent',
						'type'    => 'select',
						'name'    => esc_attr__( 'Header transparent:', 'kata-plus' ),
						'visible' => array( 'kata_show_header', '=', '1' ),
						'options' => array(
							'default' => __( 'Default', 'kata-plus' ),
							'0'       => __( 'Disable', 'kata-plus' ),
							'1'       => __( 'Enable', 'kata-plus' ),
						),
					),
					array(
						'id'      => 'kata_header_transparent_white_color',
						'type'    => 'select',
						'name'    => esc_attr__( 'Dark Header Transparent', 'kata-plus' ),
						'visible' => array( 'kata_make_header_transparent', '=', '1' ),
						'options' => array(
							'default' => __( 'Default', 'kata-plus' ),
							'0'       => __( 'Disable', 'kata-plus' ),
							'1'       => __( 'Enable', 'kata-plus' ),
						),
					),
					array(
						'id'   => 'post_time_to_read',
						'name' => esc_attr__( 'Post\'s time to read:', 'kata-plus' ),
						'desc' => esc_attr__( 'After fill the above field you can show it by use "time to read" option in blog, single, archive, author, search, related posts, post metadata elementor widgets', 'kata-plus' ),
						'type' => 'text',
					),
					array(
						'id'   => 'kata_post_video',
						'name' => esc_attr__( 'Video URL:', 'kata-plus' ),
						'desc' => esc_attr__( 'You can only insert YouTube, Vimeo or Hosted video link. It only works when the post format is video.', 'kata-plus' ),
						'type' => 'text',
					),
				),
			);

			return $meta_boxes;
		}
	}

	Kata_Plus_Meta_Box_Post::get_instance();
}
