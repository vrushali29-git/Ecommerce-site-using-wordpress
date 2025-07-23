<?php
/**
 * MegaMenu Options - Meta Box.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Meta_Box_MegaMenu_Options' ) ) {
	class Kata_Plus_Meta_Box_MegaMenu_Options {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Meta_Box_MegaMenu_Options
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
			// MegaMenu
			$meta_boxes[] = array(
				'title'      => esc_html__( 'Mega Menu Options', 'kata-plus' ),
				'id'         => 'kata-megamenu',
				'post_types' => array( 'kata_mega_menu' ),
				// 'kata-tabs'  => [
				// 'general' => [
				// 'title' => esc_html__( 'General Settings', 'kata-plus' ),
				// 'icon'  => 'ti-layout-tab-window',
				// ],
				// ],
				'fields'     => array(
					array(
						'name' => esc_attr__( 'Full Width', 'kata-plus' ),
						'id'   => 'mega_menu_full_width',
						'type' => 'switch',
						'std'  => '1',
						// 'kata-tab' => 'general',
					),
					array(
						'name'    => esc_attr__( 'Custom Width(px)', 'kata-plus' ),
						'desc'    => esc_attr__( 'Min & Max is 0 - 10000', 'kata-plus' ),
						'id'      => 'mega_menu_custom_width',
						'type'    => 'number',
						'min'     => 0,
						'max'     => 10000,
						'step'    => 1,
						'visible' => array( 'mega_menu_full_width', '!=', '1' ),
						// 'kata-tab' => 'general',
					),
				),
			);

			return $meta_boxes;
		}
	} // class

	Kata_Plus_Meta_Box_MegaMenu_Options::get_instance();
} // if
