<?php
/**
 * Register new widget.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Widget_Author_Box' ) ) {
	class Kata_Plus_Widget_Author_Box extends Kata_Plus_Widgets_Base {
		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->definitions();
			parent::__construct();
		}

		/**
		 * Definitions.
		 *
		 * @since     1.0.0
		 */
		public function definitions() {
			$this->widget_data = array(
				'slug'        => 'kata-plus-author-box',
				'name'        => esc_html__( 'Kata Author Box', 'kata-plus' ),
				'scripts'     => array(
					'css' => array(
						'id'  => 'kata-widgets-author-box',
						'src' => Kata_Plus::$assets . 'css/frontend/author-box.css',
						array(),
						Kata_Plus::$version,
					),
					'js'  => '',
				),
				'description' => esc_html__( 'Short description of the widget goes here.', 'kata-plus' ),
				'fields'      => array(
					'title'          => array(
						'title'   => esc_html__( 'Title', 'kata-plus' ),
						'type'    => 'text',
						'default' => esc_html__( 'Author Box' ),
					),
					'image_id'       => array(
						'title'   => esc_html__( 'Image', 'kata-plus' ),
						'type'    => 'image',
						'default' => '',
					),
					'name'           => array(
						'title'   => esc_html__( 'Name', 'kata-plus' ),
						'type'    => 'text',
						'default' => 'Mark Davis',
					),
					'description'    => array(
						'title'   => esc_html__( 'Description', 'kata-plus' ),
						'type'    => 'textarea',
						'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint doloribus ullam accusamus dolores, accusantium, mollitia rerum magni exercitationem omnis, porro sed? Eum minima ex saepe dicta non delectus expedita vitae.',
					),
					'twitter'        => array(
						'title'   => esc_html__( 'Twitter', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => 'on',
					),
					'twitter_link'   => array(
						'title'   => esc_html__( 'Twitter link', 'kata-plus' ),
						'type'    => 'text',
						'default' => '#',
					),
					'facebook'       => array(
						'title'   => esc_html__( 'Facebook', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => 'on',
					),
					'facebook_link'  => array(
						'title'   => esc_html__( 'Facebook link', 'kata-plus' ),
						'type'    => 'text',
						'default' => '#',
					),
					'instagram'      => array(
						'title'   => esc_html__( 'Instagram', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => 'on',
					),
					'instagram_link' => array(
						'title'   => esc_html__( 'Instagram link', 'kata-plus' ),
						'type'    => 'text',
						'default' => '#',
					),
					'pinterest'      => array(
						'title'   => esc_html__( 'Pinterest', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => '',
					),
					'pinterest_link' => array(
						'title'   => esc_html__( 'Pinterest link', 'kata-plus' ),
						'type'    => 'text',
						'default' => '',
					),
					'dribbble'       => array(
						'title'   => esc_html__( 'Dribbble', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => '',
					),
					'dribbble_link'  => array(
						'title'   => esc_html__( 'Dribbble link', 'kata-plus' ),
						'type'    => 'text',
						'default' => '',
					),
				),
			);

			$this->dir = realpath( __DIR__ ) . '/';
		}

		/**
		 * Actions.
		 *
		 * @since     1.0.0
		 */
		public function actions() {
			// Backend Scripts
			add_action(
				'admin_enqueue_scripts',
				function () {
					wp_enqueue_media();
					wp_enqueue_script( 'kata-plus-widget-media-upload', Kata_Plus::$assets . 'js/backend/widget-media-upload.js', array( 'jquery' ), Kata_Plus::$version, true );
					wp_enqueue_style( 'kata-plus-widget-media-upload', Kata_Plus::$assets . 'css/backend/widget-media-upload.css' );
				}
			);
			// Frontend Scripts
			add_action(
				'wp_enqueue_scripts',
				function () {
					wp_enqueue_style( 'kata-plus-widget-author-box', Kata_Plus::$assets . 'css/frontend/widget-author-box.css', array(), Kata_Plus::$version );
				}
			);
		}
	} // end class

	add_action(
		'widgets_init',
		function () {
			register_widget( 'Kata_Plus_Widget_Author_Box' );
		}
	);
}
