<?php
/**
 * Rollback Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kata_Plus_Rollback {
	/**
	 * Instance of this class.
	 *
	 * @since   1.3.0
	 * @access  public
	 * @var     Kata_Plus_Rollback
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
		$this->actions();
	}

	/**
	 * Add actions.
	 *
	 * @since   1.3.0
	 */
	public function actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_kata_rollback', array( $this, 'kata_rollback' ) );
	}

	/**
	 * Load plugin upgrader.
	 *
	 * @since   1.3.0
	 */
	private function load_plugin_upgrader() {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once Kata_Plus::$dir . 'includes/admin-panel/class-plugin-upgrader.php';
	}

	/**
	 * enqueue scripts.
	 *
	 * @since   1.3.0
	 */
	public function enqueue_scripts() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'kata-plus-rollback' ) {
			wp_enqueue_style( 'rwmb-select2', RWMB_CSS_URL . 'select2/select2.css', array(), '4.0.10' );
			wp_enqueue_script( 'rwmb-select2', RWMB_JS_URL . 'select2/select2.min.js', array( 'jquery' ), '4.0.10', true );

			wp_enqueue_style( 'kata-notice', Kata_Plus::$assets . 'css/backend/notice.css', array(), Kata_Plus::$version );
			wp_enqueue_style( 'kata-rollback', Kata_Plus::$assets . 'css/backend/rollback.css', array(), Kata_Plus::$version );
			wp_enqueue_script( 'kata-rollback', Kata_Plus::$assets . 'js/backend/rollback.js', array( 'jquery' ), Kata_Plus::$version, true );

			wp_localize_script(
				'kata-rollback',
				'kataRollback',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'krb' ),
				)
			);
		}
	}

	/**
	 * Rollback ajax.
	 *
	 * @since   1.3.0
	 */
	public function kata_rollback() {
		$nonce   = sanitize_text_field( $_POST['nonce'] );
		$version = sanitize_text_field( $_POST['version'] );
		$slug    = sanitize_text_field( $_POST['slug'] );

		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $slug . '/' . $slug . '.php', false, false );

		if ( $plugin_data['Version'] === $version ) {
			wp_send_json( 'Can not return to currnet version', 400 );
			wp_die();
		}

		if ( ! is_plugin_active( $slug . '/' . $slug . '.php' ) ) {
			wp_send_json( 'Plugin is not installed', 400 );
			wp_die();
		}

		if ( ! wp_verify_nonce( $nonce, 'krb' ) ) {
			wp_send_json( 'Wrong nonce!', 400 );
			wp_die();
		}

		$this->load_plugin_upgrader();

		$upgrader = new Kata_Plus_Plugin_Upgrader();

		$result = $upgrader->rollback( $slug, array(), $version );

		if ( ! is_wp_error( $result ) && $result ) {
			wp_send_json( 'updated', 200 );
		} else {
			wp_send_json( 'error', 400 );
		}

		wp_die();
	}
}

Kata_Plus_Rollback::get_instance();
