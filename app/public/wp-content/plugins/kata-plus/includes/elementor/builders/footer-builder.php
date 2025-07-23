<?php

/**
 * Footer Builder.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Footer_Builder' ) ) {

	class Kata_Plus_Footer_Builder extends Kata_Plus_Builders_Base {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Elementor
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
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			$this->dir          = Kata_Plus::$dir . 'includes/elementor/builders/content-template/footer-builder.json';
			$this->name         = esc_html__( 'Kata Footer', 'kata-plus' );
			$this->action       = 'kata_footer';
			self::$builder_type = $this->action;
			$content               = file_get_contents( $this->dir );
			$this->default_content = json_decode( $content, true );
		}

		/**
		 * Enqueue Scripts
		 *
		 * @since     1.0.0
		 */
		public function enqueue_scripts() {

			if ( class_exists( '\Elementor\Plugin' ) ) {

				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
			}

			if ( class_exists( '\ElementorPro\Plugin' ) ) {

				$elementor_pro = \ElementorPro\Plugin::instance();
				$elementor_pro->enqueue_styles();
			}
		}
	} // end class

	Kata_Plus_Footer_Builder::get_instance();
}
