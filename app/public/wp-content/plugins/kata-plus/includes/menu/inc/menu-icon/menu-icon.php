<?php

/**
 * Menu Icon Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Menu_Icon' ) ) {
	class Kata_Plus_Menu_Icon {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Menu_Icon
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
			$this->filters();
			$this->actions();
			$this->dependencies();
		}

		/**
		 * Add filters.
		 *
		 * @since   1.0.0
		 */
		public function filters() {
			// Add menu icon field to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_menu_icon_field' ) );
			// Edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			// Save menu icon field
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_menu_icon_field' ), 10, 3 );
			// Enqueue styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {
			if ( class_exists( 'Elementor\Plugin' ) && class_exists( 'Kata_Plus_Elementor' ) ) {
				Kata_Plus_Autoloader::load( Kata_Plus_Elementor::$dir . 'controls/icon-control', 'icons' );
			}
			Kata_Plus_Autoloader::load( Kata_Plus_Menu::$dir . 'inc/menu-icon', 'edit_custom_walker' );
		}

		/**
		 * Add custom fields to $item nav object in order to be used in custom Walker.
		 *
		 * @access  public
		 * @since   1.0.0
		 */
		function add_menu_icon_field( $menu_item ) {
			$menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
			return $menu_item;
		}

		/**
		 * Save menu custom fields
		 *
		 * @access  public
		 * @since   1.0.0
		 */
		function update_menu_icon_field( $menu_id, $menu_item_db_id, $args ) {
			// Check if element is properly sent
			if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
				$subtitle_value = $_REQUEST['menu-item-icon'][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_icon', $subtitle_value );
			}
		}

		/**
		 * Define new Walker edit
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function edit_walker( $walker, $menu_id ) {
			return 'Kata_Plus_Walker_Nav_Menu_Edit';
		}

		/**
		 * Enqueue styles.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'kata-plus-icon-control', Kata_Plus::$assets . 'css/backend/elementor-icon-control.css', array(), Kata_Plus::$version );
		}

		/**
		 * Enqueue scripts.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function enqueue_scripts() {
			if ( get_current_screen()->base == 'post' ||
			get_current_screen()->id == 'page' ||
			get_current_screen()->id == 'customizer' ||
			get_current_screen()->id == 'kata_plus_builder' ||
			get_current_screen()->id == 'nav-menus' ||
			strpos( get_current_screen()->id, 'kata-plus' ) ||
			get_current_screen()->id == 'edit-elementor_library' ) {
				wp_enqueue_script( 'lozad', Kata_Plus::$assets . 'js/libraries/lozad.min.js', array( 'jquery' ), Kata_Plus::$version, true );
				wp_enqueue_script( 'kata-plus-icon-control', Kata_Plus::$assets . 'js/backend/menu-icon-control.js', array( 'jquery' ), Kata_Plus::$version, true );
			}
		}
	}

	Kata_Plus_Menu_Icon::get_instance();
}
