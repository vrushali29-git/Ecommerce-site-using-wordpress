<?php
/**
 * Team Member Options - Meta Box.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Meta_Box_Team_Member' ) ) {
	class Kata_Plus_Meta_Box_Team_Member {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Meta_Box_Team_Member
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
			// Team Member
			$meta_boxes[] = array(
				'title'      => esc_html__( 'Team Member Options', 'kata-plus' ),
				'id'         => 'kata-team_member',
				'post_types' => array( 'kata_team_member' ),
				'fields'     => array(
					array(
						'id'   => 'team_job',
						'name' => esc_attr__( 'Job:', 'kata-plus' ),
						'type' => 'text',
					),
					array(
						'id'      => 'team_social',
						'name'    => esc_attr__( 'Social', 'kata-plus' ),
						'type'    => 'text_list',
						'clone'   => true,
						'options' => array(
							'facebook'             => '',
							'https://facebook.com' => '',
						),
					),
				),
			);

			return $meta_boxes;
		}
	}

	Kata_Plus_Meta_Box_Team_Member::get_instance();
}
