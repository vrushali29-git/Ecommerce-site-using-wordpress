<?php

/**
 * Learnpress Compatibility Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Learnpress_Compatibility' ) ) {
	class Kata_Plus_Learnpress_Compatibility extends Kata_Plus_Compatibility {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Learnpress_Compatibility
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
			$this->definitions();
			$this->actions();
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			if ( ! class_exists( 'LP_Shortcodes' ) ) {
				require_once WP_PLUGIN_DIR . '/learnpress/inc/class-lp-shortcodes.php';
			}
			add_action( 'loop_start', array( $this, 'static_pages' ) );
		}

		/**
		 * Edit learnpress static pages content.
		 *
		 * @since   1.0.0
		 */
		public function static_pages() {
			global $post;

			if ( $post ) {
				if ( $post->ID == learn_press_get_page_id( 'profile' ) ) {
					if ( '[learn_press_profile]' === $post->post_content ) {
						$post->post_content = '';
					}
				} elseif ( $post->ID == learn_press_get_page_id( 'checkout' ) ) {
					if ( '[learn_press_checkout]' === $post->post_content ) {
						$post->post_content = '';
					}
				} elseif ( $post->ID == learn_press_get_page_id( 'become_a_teacher' ) ) {
					if ( '[learn_press_become_teacher_form]' === $post->post_content ) {
						$post->post_content = '';
					}
				}
				return $post;
			}
		}
	}
	Kata_Plus_Learnpress_Compatibility::get_instance();
}
