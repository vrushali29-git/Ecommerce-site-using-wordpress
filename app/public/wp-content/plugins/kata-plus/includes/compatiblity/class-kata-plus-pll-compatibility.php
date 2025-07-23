<?php

/**
 * Polylang Compatibility Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.2.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! class_exists( 'Kata_Plus_Pll_Compatibility' ) ) {

	class Kata_Plus_Pll_Compatibility extends Kata_Plus_Compatibility {

		/**
		 * Instance of this class.
		 *
		 * @since   1.2.0
		 * @access  public
		 * @var     Kata_Plus_Pll_Compatibility
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.2.0
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
		 * @since   1.2.0
		 */
		public function __construct() {

			$this->definitions();
			$this->actions();
		}

		/**
		 * Add actions.
		 *
		 * @since   1.2.0
		 */
		public function actions() {

			add_filter( 'pll_get_post_types', array( $this, 'add_cpt_to_pll' ), 10, 2 );
			add_filter( 'pll_get_taxonomies', array( $this, 'add_tax_to_pll' ), 10, 2 );
			add_action( 'elementor/element/wp-post/document_settings/before_section_start', array( $this, 'multi_language' ), 10, 2 );
			add_action( 'elementor/element/wp-page/document_settings/before_section_start', array( $this, 'multi_language' ), 10, 2 );
		}

		/**
		 * Return registred languages.
		 *
		 * @since   1.2.0
		 */
		public function registred_languages() {
			pll_languages_list();
		}

		/**
		 * Add Kata Post types to polylang.
		 *
		 * @since   1.2.0
		 */
		public function add_cpt_to_pll( $post_types, $is_settings ) {

			if ( $is_settings ) {

				unset( $post_types['kata_plus_builder'] );
				unset( $post_types['kata_mega_menu'] );
				unset( $post_types['elementor_library'] );
				unset( $post_types['kata_grid'] );
				unset( $post_types['kata_recipe'] );
				unset( $post_types['kata_team_member'] );
				unset( $post_types['kata_testimonial'] );
			} else {

				$post_types['kata_plus_builder'] = 'kata_plus_builder';
				$post_types['kata_mega_menu']    = 'kata_mega_menu';
				$post_types['elementor_library'] = 'elementor_library';
				$post_types['kata_grid']         = 'kata_grid';
				$post_types['kata_recipe']       = 'kata_recipe';
				$post_types['kata_team_member']  = 'kata_team_member';
				$post_types['kata_testimonial']  = 'kata_testimonial';
			}

			return $post_types;
		}

		/**
		 * Add Kata taxonomies to polylang.
		 *
		 * @since   1.2.0
		 */
		public function add_tax_to_pll( $taxonomies, $is_settings ) {

			if ( $is_settings ) {

				unset( $taxonomies['grid_category'] );
				unset( $taxonomies['grid_tags'] );
				unset( $taxonomies['kata_recipe_category'] );
				unset( $taxonomies['kata_recipe_tag'] );
			} else {

				$taxonomies['grid_category']        = 'grid_category';
				$taxonomies['grid_tags']            = 'grid_tags';
				$taxonomies['kata_recipe_category'] = 'kata_recipe_category';
				$taxonomies['kata_recipe_tag']      = 'kata_recipe_tag';
			}

			return $taxonomies;
		}

		/**
		 * Add get language.
		 *
		 * @since   1.2.0
		 */
		public function get_currnet_language() {

			if ( ! function_exists( 'pll_current_language' ) ) {
				require_once WP_PLUGIN_DIR . '/polylang/include/api.php';
			}

			if ( function_exists( 'pll_default_language' ) && ! pll_current_language() ) {
				return pll_default_language();
			} elseif ( function_exists( 'pll_current_language' ) && pll_current_language() ) {
				return pll_current_language();
			}
		}


		/**
		 * Page Options.
		 *
		 * @since   1.0.0
		 */
		public function multi_language_url( $id ) {

			if ( function_exists( 'PLL' ) ) {

				$currnet_lang = pll_current_language() ? pll_current_language() : pll_default_language();
				$ids          = pll_get_post_translations( $id );
				$out          = '';

				foreach ( $ids as $key => $id ) {
					$out .= '<p class="kata-multi-lang-links">';
					$out .= '<a href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=elementor' ) ) . '" target="_blank">' . __( 'Edit', 'kata-plus' ) . ' ' . $key . ' ' . __( 'version', 'kata-plus' ) . '</a>';
					$out .= '</p>';
				}

				return $out;
			}
		}

		/**
		 * Page Options.
		 *
		 * @since   1.0.0
		 */
		public function multi_language( $page ) {
			$page->start_controls_section(
				'kata_multi_language',
				array(
					'label' => esc_html__( 'Multi-Language', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_SETTINGS,
				)
			);

			$page->add_control(
				'multi_lang_urls',
				array(
					'label'           => esc_html__( 'Important Note', 'plugin-name' ),
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => $this->multi_language_url( get_the_ID() ),
					'content_classes' => 'your-class',
				)
			);

			$page->end_controls_section();
		}

	}

	Kata_Plus_Pll_Compatibility::get_instance();
}
