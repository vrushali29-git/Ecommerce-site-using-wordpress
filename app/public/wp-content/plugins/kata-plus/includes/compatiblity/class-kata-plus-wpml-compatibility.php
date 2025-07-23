<?php

/**
 * WPML Compatibility Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.1.11
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! class_exists( 'Kata_Plus_WPML_Compatibility' ) ) {
    class Kata_Plus_WPML_Compatibility extends Kata_Plus_Compatibility {

        /**
         * Instance of this class.
         *
         * @since   1.1.11
         * @access  public
         * @var     Kata_Plus_WPML_Compatibility
         */
        public static $instance;

       /**
         * Provides access to a single instance of a module using the singleton pattern.
         *
         * @since   1.1.11
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
         * @since   1.1.11
         */
        public function __construct() {
            $this->definitions();
            $this->actions();
        }

        /**
         * Add actions.
         *
         * @since   1.1.11
         */
        public function actions() {
            add_action( 'elementor/element/wp-post/document_settings/before_section_start', array( $this, 'multi_language' ), 10, 2 );
            add_action( 'elementor/element/wp-page/document_settings/before_section_start', array( $this, 'multi_language' ), 10, 2 );
			add_action( 'wpml_loaded', array( $this, 'wpml_get_setting_filter' ), 10 );
        }

        /**
         * Return registred languages.
         *
         * @since   1.1.11
         */
        public function wpml_get_setting_filter() {

			if ( get_option( 'kata_plus_builders_wpml' ) ) {
				return;
			}

			$SitePress             = new SitePress();
			$SitePressSettings     = $SitePress->get_setting( 'translation-management' );

			$SitePressSettings[WPML_TM_Post_Edit_TM_Editor_Mode::TM_KEY_FOR_POST_TYPE_USE_NATIVE]['kata_plus_builder'] = true;

			$SitePress->set_setting( 'translation-management', $SitePressSettings, true );

			update_option( 'kata_plus_builders_wpml', 1 );
        }


        /**
         * Return registred languages.
         *
         * @since   1.1.11
         */
        public function registred_languages() {
            return apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
        }

        /**
         * get currnet language.
         *
         * @since   1.1.11
         */
        public function get_currnet_language() {
            return apply_filters( 'wpml_current_language', null );
        }

        /**
         * get default language.
         *
         * @since   1.1.11
         */
        public function get_default_language() {
            return apply_filters( 'wpml_default_language', null );
        }

        /**
         * get translations.
         *
         * @since   1.1.11
         */
        public function get_translations( $id ) {
            return apply_filters( 'wpml_get_element_translations', null, $id, 'post_kata_plus_builder' );
        }

		/**
		 * Page Options.
		 *
		 * @since   1.0.0
		 */
		public function multi_language_url( $id ) {

			if ( class_exists( 'SitePress' ) ) {

				$translations = $this->get_translations( $id );

				$out = '';

				foreach ( $translations as $key => $translation ) {
					$out .= '<p class="kata-multi-lang-links">';
					$out .= '<a href="' . esc_url( admin_url( 'post.php?post=' . $translation->element_id . '&action=elementor' ) ) . '" target="_blank">' . __( 'Edit', 'kata-plus' ) . ' ' . $key . ' ' . __( 'version', 'kata-plus' ) . '</a>';
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
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => $this->multi_language_url( get_the_ID() ),
					'content_classes' => 'your-class',
				)
			);

			$page->end_controls_section();
		}
	}
	Kata_Plus_WPML_Compatibility::get_instance();
}
