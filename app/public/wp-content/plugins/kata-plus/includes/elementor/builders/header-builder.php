<?php

/**
 * Header Builder.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;

if ( ! class_exists( 'Kata_Plus_Header_Builder' ) ) {

	class Kata_Plus_Header_Builder extends Kata_Plus_Builders_Base {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Header_Builder
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
			$this->dir          = Kata_Plus::$dir . 'includes/elementor/builders/content-template/header-builder.json';
			$this->name         = 'Kata Header';
			$this->action       = 'kata_header';
			self::$builder_type = $this->action;
			$this->before       = '<div class="kata-header-wrap">';
			$this->after        = '</div>';
			$content               = file_get_contents( $this->dir );
			$this->default_content = json_decode( $content, true );
			remove_action( 'kata_header', 'kata_header_tpl' );
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

			// wp_add_inline_script('kata-plus-template-manager', 'var kata_plus_this_page_name = "header"','before');
			$redirect_url = isset( $_SERVER['REDIRECT_URL'] ) ? $_SERVER['REDIRECT_URL'] : '';

			$direction = is_rtl() ? 'right' : 'left';
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() && get_post_meta( get_the_ID(), '_kata_builder_type', true ) == 'kata_header' ) {
				echo '<style>.elementor-container .elementor-widget-wrap > .elementor-widget {display: inline-block;width: auto;vertical-align: top;}.elementor-top-column:first-child .elementor-widget-wrap {display: flex;justify-content: ' . esc_html( $direction ) . ';}.elementor-top-column .elementor-widget-wrap {display: flex;justify-content:center;}.elementor-top-column:last-child .elementor-widget-wrap {display: flex;justify-content:flex-end;} ul.elementor-editor-element-settings.elementor-editor-section-settings { background-color: #f2ab3c !important; } ul.elementor-editor-element-settings.elementor-editor-section-settings li:hover { background-color: #f2df3c !important; } .elementor-editor-section-settings .elementor-editor-element-setting:first-child:before, .elementor-editor-section-settings .elementor-editor-element-setting:first-child:hover:before { border-right-color: #f2ab3c !important; } .elementor-editor-section-settings .elementor-editor-element-setting:last-child:after, .elementor-editor-section-settings .elementor-editor-element-setting:last-child:hover:after { border-left-color: #f2ab3c !important; } .elementor-section > .elementor-element-overlay:after { outline-color: #f2ab3c !important; }</style>';
			} else {
				echo '<style>.kata-header-wrap .elementor-container .elementor-widget-wrap > .elementor-widget:not(.elementor-widget__width-auto) {display: inline-block;width: auto;vertical-align: top;}.kata-hamburger-menu-template .elementor-container .elementor-widget-wrap > .elementor-widget:not(.elementor-widget__width-auto) { width: 100%; } .kata-header-wrap .elementor-top-column:first-child .elementor-widget-wrap {display: flex;justify-content: ' . esc_html( $direction ) . ';}.kata-header-wrap .elementor-top-column .elementor-widget-wrap {display: flex;justify-content:center;}.kata-header-wrap .elementor-top-column:last-child .elementor-widget-wrap{display: flex; justify-content: flex-end;}</style>';
			}

			if ( class_exists( '\ElementorPro\Plugin' ) ) {

				$elementor_pro = \ElementorPro\Plugin::instance();
				$elementor_pro->enqueue_styles();
			}
		}
	} // end class

	Kata_Plus_Header_Builder::get_instance();
}
