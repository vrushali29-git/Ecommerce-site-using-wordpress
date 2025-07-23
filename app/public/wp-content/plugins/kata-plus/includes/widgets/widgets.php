<?php
/**
 * Widgets Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Widgets' ) ) {
	class Kata_Plus_Widgets {
		/**
		 * The directory of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $dir;

		/**
		 * The url of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $url;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Widgets
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
			$this->definitions();
			// $this->dependencies();
			$this->filters();
			$this->actions();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/widgets/';
			self::$url = Kata_Plus::$url . 'includes/widgets/';
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {
			Kata_Plus_Autoloader::load( self::$dir . 'modules', 'widget-base' );
			foreach ( glob( self::$dir . 'modules/*', GLOB_ONLYDIR ) as $file ) {
				Kata_Plus_Autoloader::load( $file, 'widget' );
			}
		}

		/**
		 * Filters.
		 *
		 * @since   1.0.0
		 */
		public function filters() {
			add_filter( 'wp_list_categories', array( $this, 'replace_post_count_parentheses' ) );
			add_filter( 'wp_dropdown_categories', array( $this, 'replace_post_count_parentheses' ) );
			add_filter( 'get_archives_link', array( $this, 'replace_post_count_parentheses' ) );
		}

		/**
		 * Actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'widgets_init', array( $this, 'remove_widget_areas' ), 11 );
		}

		/**
		 * Remove unusable widget areas.
		 *
		 * @since   1.0.0
		 */
		public function remove_widget_areas() {
			unregister_sidebar( 'footr-sidebar-1' );
			unregister_sidebar( 'footr-sidebar-2' );
			unregister_sidebar( 'footr-sidebar-3' );
		}

		/**
		 * Replace post count parentheses.
		 *
		 * @since   1.0.0
		 */
		public function replace_post_count_parentheses( $cat ) {
			$cat = str_replace( '(', '<span class="kata-post-count">', $cat );
			$cat = str_replace( ')', '</span>', $cat );
			return $cat;
		}

		/**
		 * Text field.
		 *
		 * @since   1.0.0
		 */
		public static function text_field( $self, $field_id, $field_title, $field_variable ) {
			?>
			<p>
				<label for="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"><?php echo esc_html( ucfirst( $field_title ) ); ?>:</label>
				<input
					type="text"
					class="widefat"
					id="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"
					name="<?php echo esc_attr( $self->get_field_name( $field_id ) ); ?>"
					value="<?php echo esc_attr( $field_variable ); ?>"
				>
			</p>
			<?php
		}

		/**
		 * Textarea field.
		 *
		 * @since   1.0.0
		 */
		public static function textarea_field( $self, $field_id, $field_title, $field_variable ) {
			?>
			<p>
				<label for="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"><?php echo esc_html( ucfirst( $field_title ) ); ?>:</label>
				<textarea
					rows="7"
					class="widefat"
					id="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"
					name="<?php echo esc_attr( $self->get_field_name( $field_id ) ); ?>"
				><?php echo esc_attr( $field_variable ); ?></textarea>
			</p>
			<?php
		}

		/**
		 * Select field.
		 *
		 * @since   1.0.0
		 */
		public static function select_field( $self, $field_id, $field_title, $field_options, $field_variable ) {
			?>
			<p>
				<label for="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"><?php echo esc_html( ucfirst( $field_title ) ); ?>:</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"
					name="<?php echo esc_attr( $self->get_field_name( $field_id ) ); ?>"
				>
					<?php foreach ( $field_options as $option_id => $option_title ) { ?>
						<option value="<?php echo esc_attr( $option_id ); ?>" 
													<?php
													if ( $field_variable == $option_id ) {
														echo 'selected="selected"';}
													?>
						><?php echo esc_html( $option_title ); ?></option>
					<?php } ?>
				</select>
			</p>
			<?php
		}

		/**
		 * Checkbox field.
		 *
		 * @since   1.0.0
		 */
		public static function checkbox_field( $self, $field_id, $field_title, $field_variable ) {
			?>
			<p>
				<input
					type="checkbox"
					class="checkbox"
					id="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"
					name="<?php echo esc_attr( $self->get_field_name( $field_id ) ); ?>"
					<?php checked( $field_variable, 'on' ); ?>
				>
				<label for="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"><?php echo esc_html( ucfirst( $field_title ) ); ?></label>
			</p>
			<?php
		}

		/**
		 * Image field.
		 *
		 * @since   1.0.0
		 */
		public static function image_field( $self, $field_id, $field_title, $field_variable ) {
			?>
			<div class="kata-input-media-upload-wrapper">
				<label for="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"><?php echo esc_html( ucfirst( $field_title ) ); ?>:</label>
				<input
					type="hidden"
					class="kata-input-media-upload widefat"
					id="<?php echo esc_attr( $self->get_field_id( $field_id ) ); ?>"
					name="<?php echo esc_attr( $self->get_field_name( $field_id ) ); ?>"
					value="<?php echo esc_attr( $field_variable ); ?>"
				>
				<div class="kata-preview-media-upload">
					<?php if ( ! empty( $field_variable ) ) { ?>
						<img src="<?php echo esc_url( wp_get_attachment_url( $field_variable ) ); ?>" alt="">
					<?php } ?>
				</div>
				<button class="kata-add-media-upload button button-secondary"><?php esc_html_e( 'Upload Image', 'kata-plus' ); ?></button>
				<button class="kata-remove-media-upload button button-secondary" style="display: <?php echo empty( $field_variable ) ? 'none' : 'inline-block'; ?>;"><?php esc_html_e( 'Remove Image', 'kata-plus' ); ?></button>
			</div>
			<?php
		}
	} // class

	Kata_Plus_Widgets::get_instance();
}
