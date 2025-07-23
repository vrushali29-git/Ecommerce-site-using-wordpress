<?php
/**
 * Widget Base Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Widgets_Base' ) ) {
	abstract class Kata_Plus_Widgets_Base extends WP_Widget {
		/**
		 * The directory of the called widget.
		 *
		 * @access  protected
		 * @var     string
		 * @since   1.0.0
		 */
		protected $dir;

		/**
		 * Unique identifier for widget.
		 *
		 * @access  protected
		 * @var     string
		 * @since   1.0.0
		 */
		protected $widget_data;

		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			parent::__construct(
				$this->widget_data['slug'],
				$this->widget_data['name'],
				array(
					'classname'   => $this->widget_data['slug'] . '-widget',
					'description' => $this->widget_data['description'],
				)
			);
			$this->actions();
		}

		/**
		 * Actions.
		 *
		 * @since     1.0.0
		 */
		public function actions() {}

		/**
		 * Processes the widget's options to be saved.
		 *
		 * @param   array   new_instance The new instance of values to be generated via the update.
		 * @param   array   old_instance The previous instance of values before the update.
		 * @since   1.0.0
		 */
		public function update( $new_instance, $old_instance ) {
			$update_instance = array_merge( $new_instance, $old_instance );
			foreach ( $this->widget_data['fields'] as $field_id => $field ) {
				$update_instance[ $field_id ] = ( ! empty( $new_instance[ $field_id ] ) ) ? $new_instance[ $field_id ] : '';
			}
			return $update_instance;
		}

		/**
		 * Generates the administration form for the widget.
		 *
		 * @param   array   instance The array of keys and values for the widget.
		 * @since   1.0.0
		 */
		public function form( $instance ) {
			// Define default values for variables
			$widget_data_default = array();
			foreach ( $this->widget_data['fields'] as $field_id => $field ) {
				$widget_data_default[ $field_id ] = $field['default'];
			}
			$instance = wp_parse_args( (array) $instance, $widget_data_default );
			extract( $instance );
			foreach ( $this->widget_data['fields'] as $field_id => $field ) {
				switch ( $field['type'] ) {
					case 'textarea':
						Kata_Plus_Widgets::textarea_field( $this, $field_id, $field['title'], ${$field_id} );
						break;
					case 'select':
						Kata_Plus_Widgets::select_field( $this, $field_id, $field['title'], $field['options'], ${$field_id} );
						break;
					case 'checkbox':
						Kata_Plus_Widgets::checkbox_field( $this, $field_id, $field['title'], ${$field_id} );
						break;
					case 'image':
						Kata_Plus_Widgets::image_field( $this, $field_id, $field['title'], ${$field_id} );
						break;
					case 'StyleBox':
						if ( ! class_exists( 'Styler_Widget_Field' ) ) {
							require_once realpath( KATA_PLUS_STYLER_DIR . 'widgets/widgets.php' );
						}
						Styler_Widget_Field::field( $this, $field_id, $field['title'], ${$field_id}, $field['styler_selectors'] );
						break;
					default:
						Kata_Plus_Widgets::text_field( $this, $field_id, $field['title'], ${$field_id} );
						break;
				}
			}
		}

		/**
		 * Outputs the content of the widget.
		 *
		 * @param   array   args  The array of form elements
		 * @param   array   instance The current instance of the widget
		 * @since   1.0.0
		 */
		public function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			extract( $instance );

			$widget_string = $before_widget;
			ob_start();
			require $this->dir . 'view.php';
			$widget_string .= ob_get_clean();
			$widget_string .= $after_widget;

			echo wp_kses_post( $widget_string );
		}
	} // class
}
