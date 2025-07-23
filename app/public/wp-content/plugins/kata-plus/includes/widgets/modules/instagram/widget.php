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

if ( ! class_exists( 'Kata_Plus_Widget_Instagram' ) ) {
	class Kata_Plus_Widget_Instagram extends Kata_Plus_Widgets_Base {
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
				'slug'        => 'kata-plus-instagram',
				'name'        => esc_html__( 'Kata Instagram', 'kata-plus' ),
				'description' => esc_html__( 'Short description of the widget goes here.', 'kata-plus' ),
				'fields'      => array(
					'title'        => array(
						'title'   => esc_html__( 'Title', 'kata-plus' ),
						'type'    => 'text',
						'default' => esc_html__( 'Instagram' ),
					),
					'username'     => array(
						'title'       => esc_html__( 'Username or hashtag', 'kata-plus' ),
						'description' => esc_html__( 'Please enter the user name with @ and hashtag with #.', 'kata-plus' ),
						'type'        => 'text',
						'default'     => 'instagram',
					),
					'post_number'  => array(
						'title'   => esc_html__( 'Number Of Post', 'kata-plus' ),
						'type'    => 'text',
						'default' => 9,
					),
					'img_size'     => array(
						'title'   => esc_html__( 'Image Size', 'kata-plus' ),
						'type'    => 'select',
						'default' => 'thumbnail',
						'options' => array(
							'thumbnail' => esc_html__( 'Thumbnail', 'kata-plus' ),
							'large'     => esc_html__( 'Large', 'kata-plus' ),
							'original'  => esc_html__( 'Original', 'kata-plus' ),
						),
					),
					'link_to_post' => array(
						'title'   => esc_html__( 'Link the image to instagram\'s post', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => 'on',
					),
					'target_blank' => array(
						'title'   => esc_html__( 'Open links in new tab', 'kata-plus' ),
						'type'    => 'checkbox',
						'default' => 'on',
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
			// Frontend Scripts
			add_action(
				'wp_enqueue_scripts',
				function () {
					wp_enqueue_style( 'kata-plus-widget-instagram', Kata_Plus::$assets . 'css/frontend/widget-instagram.css', array(), Kata_Plus::$version );
				}
			);
		}
	} // end class

	add_action(
		'widgets_init',
		function () {
			register_widget( 'Kata_Plus_Widget_Instagram' );
		}
	);
}
