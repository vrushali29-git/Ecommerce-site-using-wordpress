<?php
/**
 * Testimonial Options - Meta Box.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Meta_Box_Testimonial_Options' ) ) {
	class Kata_Plus_Meta_Box_Testimonial_Options {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Meta_Box_Testimonial_Options
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
			// Testimonial
			$meta_boxes[] = array(
				'title'      => esc_html__( 'Testimonial Options', 'kata-plus' ),
				'id'         => 'kata-testimonial',
				'post_types' => array( 'kata_testimonial' ),
				'fields'     => array(
					array(
						'id'   => 'testimonial_position',
						'name' => esc_attr__( 'Position:', 'kata-plus' ),
						'type' => 'text',
					),
					array(
						'id'   => 'testimonial_date',
						'name' => esc_attr__( 'Date:', 'kata-plus' ),
						'type' => 'date',
					),
					array(
						'id'   => 'testimonial_html',
						'name' => esc_attr__( 'Note:', 'kata-plus' ),
						'type' => 'custom_html',
						'std'  => wp_kses(
							'<ol class="alert alert-warning">
								<li>' . __( 'For testimonial Name please fill the Title.', 'kata-plus' ) . '</li>
								<li>' . __( 'For testimonial Position please fill the Position field.', 'kata-plus' ) . '</li>
								<li>' . __( 'For testimonial Image please select a featured image.', 'kata-plus' ) . '</li>
							</ol>',
							wp_kses_allowed_html( 'post' )
						),
					),
				),
			);

			return $meta_boxes;
		}
	} // class

	Kata_Plus_Meta_Box_Testimonial_Options::get_instance();
} // if
