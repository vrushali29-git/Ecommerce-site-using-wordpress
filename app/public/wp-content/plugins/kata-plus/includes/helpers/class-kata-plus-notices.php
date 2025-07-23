<?php

/**
 * Class Kata Plus Notices.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.3
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Notices' ) ) {
	class Kata_Plus_Notices {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $instance;

		/**
		 * Maximum Post Size.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $max_php_file_size;

		/**
		 * Maximum PHP Input Time.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $get_php_max_input_time;

		/**
		 * Maximum Execution Size.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $max_execution_time;

		/**
		 * Maximum Post Size.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $post_max_size;

		/**
		 * Maximum Upload File Size.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $max_upload_size;

		/**
		 * Minimum Memory requirement.
		 *
		 * @since   1.0.3
		 * @access  public
		 * @var     Kata_Plus_Notices
		 */
		public static $min_recommended_memory;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.3
		 * @return  object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.3
		 */
		public function __construct() {
			$this->definitions();
			$this->actions();
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.3
		 */
		public function definitions() {
			self::$max_php_file_size      = '40M';
			self::$get_php_max_input_time = 300;
			self::$max_execution_time     = 300;
			self::$post_max_size          = '128M';
			self::$max_upload_size        = 32;
			self::$min_recommended_memory = '512M';
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.3
		 */
		public function actions() {
			// add_action( 'admin_notices', [ $this, 'notice' ] );
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.3
		 */
		public static function messages() {
			return array(
				'server_resources' => array(
					'title'                  => __( 'Evaluate Your Server Resources', 'kata-plus' ),
					'php_version'            => __( 'Current PHP Version is ', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_php_version()['value'],
					'php_post_max_size'      => __( 'Current PHP Max File Size is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_php_max_filesize()['value'],
					'memory_limit_bytes'     => __( 'Current Memory limit is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_memory_limit()['value'],
					'get_php_max_input_time' => __( 'Current PHP Max Input Time is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_php_max_input_time()['value'],
					'max_execution_time'     => __( 'Current PHP Maximum Execution Time is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_php_max_execution_time()['value'],
					'post_max_size'          => __( 'Current PHP Max Post Size is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_php_post_max_size()['value'],
					'max_upload_size'        => __( 'Current Max Upload Size is', 'kata-plus' ) . ' ' . Kata_Plus_System_Status::get_max_upload_size()['value'],
				),
			);
		}

		/**
		 * Evaluate Server Resources
		 *
		 * @since   1.0.3
		 */
		public static function EvaluateServerResources() {
			$messages = self::messages()['server_resources'];
			$html     = '<ul class="kata-plus-notice-list">';
			$reports  = '';

			if ( version_compare( phpversion(), '7.4', '<' ) ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['php_version'] . ', </span>';
				$reports .= '<span class="need-value">' . __( 'We highly recommend using PHP 7.4 or higher.', 'kata-plus' ) . '</span>';
				$reports .= '</li>';
			}

			$current_memory = wp_convert_hr_to_bytes( Kata_Plus_System_Status::get_php_max_filesize()['value'] );
			$need_memory    = wp_convert_hr_to_bytes( self::$max_php_file_size );
			if ( $current_memory < $need_memory ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['php_post_max_size'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . self::$max_php_file_size . '</span>';
				$reports .= '</li>';
			}

			$memory_limit_bytes    = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
			$min_recommended_bytes = wp_convert_hr_to_bytes( self::$min_recommended_memory );
			if ( $memory_limit_bytes < $min_recommended_bytes ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['memory_limit_bytes'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . esc_html( self::$min_recommended_memory ) . '</span>';
				$reports .= '</li>';
			}
			if ( Kata_Plus_System_Status::get_php_max_input_time()['value'] < self::$get_php_max_input_time ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['get_php_max_input_time'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . self::$get_php_max_input_time . ' </span>';
				$reports .= '</li>';
			}
			if ( Kata_Plus_System_Status::get_php_max_execution_time()['value'] < self::$max_execution_time ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['max_execution_time'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . self::$max_execution_time . ' </span>';
				$reports .= '</li>';
			}

			$current_post_max_size = wp_convert_hr_to_bytes( Kata_Plus_System_Status::get_php_post_max_size()['value'] );
			$need_post_max_size = wp_convert_hr_to_bytes( self::$post_max_size );
			if ( $current_post_max_size < $need_post_max_size ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['post_max_size'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . self::$post_max_size . 'M </span>';
				$reports .= '</li>';
			}
			if ( wp_max_upload_size() < self::$max_upload_size ) {
				$reports .= '<li class="kata-plus-notice-list-item">';
				$reports .= '<span class="current-value">' . $messages['max_upload_size'] . ' </span>';
				$reports .= '<span class="need-value">' . __( 'Change to ', 'kata-plus' ) . self::$max_upload_size . ' MB </span>';
				$reports .= '</li>';
			}
			$html .= $reports;
			$html .= '</ul>';
			$html .= '<p><a href="' . esc_url( admin_url( 'admin.php?page=kata-plus-help' ) ) . '">' . __( 'System Status', 'kata-plus' ) . '</a></p>';
			$title = $messages['title'];
			if ( $reports ) {
				return array(
					'title' => $title,
					'html'  => $html,
				);
			}
			return false;
		}

		/**
		 * Notice
		 *
		 * @since   1.0.3
		 */
		public function notice() {
			$result       = self::EvaluateServerResources();
			$kata_options = get_option( 'kata_options' );
			if ( ! $result ) {
				return;
			}
			if ( isset( $_GET['resource_recommendation'] ) ) {
				$kata_options['notices']['resource_recommendation'] = sanitize_text_field( $_GET['resource_recommendation'] );
				update_option( 'kata_options', $kata_options );
				return false;
			}
			if ( isset( $kata_options['notices']['resource_recommendation'] ) && 'true' == $kata_options['notices']['resource_recommendation'] ) {
				return false;
			}
			?>
			<style>.kata-plus-notice-dismiss { position: absolute; top: -7px; right: 0; width: 40px; height: 40px; padding: 0 !important; background: transparent !important; box-shadow: none !important; } .kata-notice .kata-plus-notice-list { list-style: inside; } .kata-notice.notice { position: relative; padding-left: 270px; background-image: url(<?php echo esc_url( Kata_Plus::$assets . '/images/admin/dashboard/dashboard-notice.png' ); ?>); background-repeat: no-repeat; background-position: 2px 2px; background-size: 252px; border-left-color: #908efc; min-height: 170px; padding-right: 10px; } .kata-notice.notice h2 { margin-bottom: 8px; margin-top: 21px; font-size: 17px; font-weight: 700; } .kata-notice.notice a:not(.notice-dismiss) { border-radius: 3px; border-color: #6d6af8; color: #fff; text-shadow: unset; background: #6d6af8; font-weight: 400; font-size: 14px; line-height: 18px; text-decoration: none; text-transform: capitalize; padding: 12px 20px; display: inline-block; margin: 7px 0 13px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); letter-spacing: 0.4px; } .kata-notice.notice a:not(.notice-dismiss):hover { border-color: #17202e; background: #17202e; } .kt-dashboard-row .kata-notice.notice { display: block; margin: 0 0 25px; border-left: 4px solid #6d6af8; border-radius: 2px; overflow: hidden; box-shadow: 0 2px 7px -1px rgba(0,0,0,0.04); } .kt-dashboard-row .kata-notice.notice .notice-dismiss { display: none; } .kt-dashboard-row .kata-notice.notice h2 { letter-spacing: -0.3px; } .kata-notice.notice a:not(.notice-dismiss) {margin-right: 10px;} </style>
			<div class="kata-notice kt-dashboard-box notice notice-success">
				<h2><?php echo wp_kses_post( $result['title'] ); ?></h2>
				<?php echo wp_kses_post( $result['html'] ); ?>
				<a class="notice-dismiss" style="z-index: 9;" href="?resource_recommendation=true"></a>
			</div>
			<?php
		}
	} // class

	Kata_Plus_Notices::get_instance();
}
