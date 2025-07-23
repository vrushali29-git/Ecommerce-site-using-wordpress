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

if ( ! class_exists( 'Kata_Plus_Widget_Blog_Posts' ) ) {
	class Kata_Plus_Widget_Blog_Posts extends Kata_Plus_Widgets_Base {
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
				'slug'        => 'kata-plus-blog-posts',
				'name'        => esc_html__( 'Kata Blog Posts', 'kata-plus' ),
				'description' => esc_html__( 'Short description of the widget goes here.', 'kata-plus' ),
				'fields'      => array(
					'title'          => array(
						'title'   => esc_html__( 'Title', 'kata-plus' ),
						'type'    => 'text',
						'default' => esc_html__( 'Blog Posts' ),
					),
					'posts_per_page' => array(
						'title'   => esc_html__( 'Number of Posts', 'kata-plus' ),
						'type'    => 'text',
						'default' => '3',
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
					wp_enqueue_style( 'kata-plus-widget-blog-post', Kata_Plus::$assets . 'css/frontend/widget-blog-post.css', array(), Kata_Plus::$version );
				}
			);
		}
	} // end class

	add_action(
		'widgets_init',
		function () {
			register_widget( 'Kata_Plus_Widget_Blog_Posts' );
		}
	);
}
