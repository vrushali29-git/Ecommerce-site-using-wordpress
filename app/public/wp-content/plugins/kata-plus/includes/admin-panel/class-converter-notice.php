<?php
/**
 * Converter Notice Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kata_Plus_Converter_Notice {
	/**
	 * Instance of this class.
	 *
	 * @since   1.3.0
	 * @access  public
	 * @var     Kata_Plus_Converter_Notice
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.3.0
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
	 * @since   1.3.0
	 */
	private function __construct() {
		$this->add_converter_option();
		$this->actions();
	}

	/**
	 * Add actions.
	 *
	 * @since   1.3.0
	 */
	public function actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'converter_notice' ) );
		add_action( 'wp_ajax_styler_migrate_rm_notice', array( $this, 'styler_migrate_rm_notice' ) );
		add_action( 'wp_ajax_styler_migrate_date', array( $this, 'styler_migrate_date' ) );
	}

	/**
	 * enqueue scripts.
	 *
	 * @since   1.3.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'kata-notice', Kata_Plus::$assets . 'css/backend/notice.css', array(), Kata_Plus::$version );
		wp_enqueue_script( 'kata-converter-notice', Kata_Plus::$assets . 'js/backend/converter-notice.js', array( 'jquery' ), Kata_Plus::$version, true );

		wp_localize_script(
			'kata-converter-notice',
			'kataConverterNotice',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'kcn' ),
			)
		);
	}

	/**
	 * Add converter option.
	 *
	 * @since 1.3.0
	 */
	private function add_converter_option() {

		if ( $this->check_styler_migrate_notice_option() === false ) {
			add_option( 'styler_migrate_notice', true );
		}

		if ( $this->check_styler_migrate_option() === false ) {
			add_option( 'styler_migrate', true );
		}
	}

	/**
	 * Check styler_migrate_notice option.
	 *
	 * @since 1.3.0
	 */
	private function check_styler_migrate_notice_option() {
		$option = get_option( 'styler_migrate_notice' );

		if ( $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Check styler_migrate option.
	 *
	 * @since 1.3.0
	 */
	private function check_styler_migrate_option() {
		$option = get_option( 'styler_migrate' );

		if ( $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Converter notice content.
	 *
	 * @since   1.3.0
	 */
	public function converter_notice() {
		if ( $this->check_styler_migrate_notice_option() === false || $this->check_styler_migrate_option() === false ) {
			return;
		}

		?>
			<div class="kata-notice kt-dashboard-box notice notice-success is-dismissible">
				<h2><?php echo esc_html__( 'Migrate the styler data to the new version', 'kata-plus' ); ?></h2>
				<p><?php echo esc_html__( 'By clicking on the button below the data of the styler will be migrated to the new version. Please get a full backup of your website before proceeding with the task.', 'kata-plus' ); ?></p>
				<p><a href="" class="kata-notice-btn">
					<i class="dashicons dashicons-update" style="display: none;"></i>
					<span><?php echo esc_html__( 'Migrate', 'kata-plus' ); ?></span>
				</a></p>
				<a class="notice-dismiss" style="z-index: 9;" href="#"></a>
			</div>
		<?php
	}

	/**
	 * Remove migrate notice ajax.
	 *
	 * @since   1.3.0
	 */
	public function styler_migrate_rm_notice() {
		$nonce = sanitize_text_field( $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'kcn' ) ) {
			die( 'Wrong nonce!' );
		}

		update_option( 'styler_migrate_notice', false );

		wp_die();
	}

	/**
	 * Migrate ajax.
	 *
	 * @since   1.3.0
	 */
	public function styler_migrate_date() {
		$nonce = sanitize_text_field( $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'kcn' ) ) {
			die( 'Wrong nonce!' );
		}

		update_option( 'styler_migrate', false );

		// Kata_Plus_Styler_Convertor::get_instance();

		wp_die();
	}
}

Kata_Plus_Converter_Notice::get_instance();
