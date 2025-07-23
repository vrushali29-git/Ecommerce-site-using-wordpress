<?php

/**
 * Blog Builder.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

if ( ! class_exists( 'Kata_Plus_Blog_Builder' ) ) {

	class Kata_Plus_Blog_Builder extends Kata_Plus_Builders_Base {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Blog_Builder
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
			$this->dir             = Kata_Plus::$dir . 'includes/elementor/builders/content-template/blog-builder.json';
			$this->name            = esc_html__( 'Kata Blog', 'kata-plus' );
			$this->action          = 'kata_blog';
			self::$builder_type    = $this->action;
			$content               = file_get_contents( $this->dir );
			$this->default_content = json_decode( $content, true );
		}
	} // end class

	Kata_Plus_Blog_Builder::get_instance();
}
