<?php
/**
 * Display admin message.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class AdminMessage {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		$this->add_message_option();
		$this->hooks();
	}

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 *
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hooks.
	 *
	 * @since 1.0.0
	 */
	private function hooks() {

		if ( ! $this->check_installed_plugins() === true ) {
			add_action( 'admin_notices', array( $this, 'message_content' ) );
			add_action( 'shoppress/admin/before_dashboard', array( $this, 'message_content' ) );
		}

		if ( ! is_active_shoppress_pro() ) {
			// add_action( 'admin_notices', array( $this, 'go_pro_message_content' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_ajax_shoppress_admin_message', array( $this, 'shoppress_admin_message' ) );
	}

	/**
	 * enqueue styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'sp-admin-message' );
		wp_enqueue_script( 'sp-admin-message' );

		wp_localize_script(
			'sp-admin-message',
			'shoppressMessage',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'shoppress_message' ),
			)
		);
	}

	/**
	 * Add shoppress_message option.
	 *
	 * @since 1.0.0
	 */
	private function add_message_option() {

		if ( $this->check_option() === false ) {
			add_option( 'shoppress_message', true );
		}

		if ( $this->check_option_pro() === false ) {
			add_option( 'shoppress_message_pro', true );
		}
	}

	/**
	 * Check shoppress_message option.
	 *
	 * @since 1.0.0
	 */
	private function check_option() {
		$option = get_option( 'shoppress_message' );

		if ( $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Check shoppress_message_pro option.
	 *
	 * @since 1.0.0
	 */
	private function check_option_pro() {
		$option = get_option( 'shoppress_message_pro' );

		if ( $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Check current page.
	 *
	 * @since 1.0.0
	 */
	private function check_current_page() {
		$screen = get_current_screen();

		if ( $screen->id == 'dashboard' || $screen->id == 'themes' || $screen->id == 'plugins' || $screen->id == 'toplevel_page_shoppress' ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if both WooCommerce and Elementor are installed.
	 *
	 * @since 1.0.0
	 */
	private function check_installed_plugins() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return ( in_array( 'woocommerce/woocommerce.php', (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) );
	}

	/**
	 * Returns elementor install or activate link.
	 *
	 * @since 1.0.0
	 */
	private function get_elementor_link() {
		$link = '';

		if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
			$link = wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php' );
		} else {
			$link = wp_nonce_url( 'update.php?action=install-plugin&plugin=elementor', 'install-plugin_elementor' );
		}

		return $link;
	}

	/**
	 * Returns WooCommerce install or activate link.
	 *
	 * @since 1.0.0
	 */
	private function get_woocommerce_link() {
		$link = '';

		if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			$link = wp_nonce_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php' );
		} else {
			$link = wp_nonce_url( 'update.php?action=install-plugin&plugin=woocommerce', 'install-plugin_woocommerce' );
		}

		return $link;
	}

	/**
	 * Message content.
	 *
	 * @since 1.0.0
	 */
	public function message_content() {

		if ( ! $this->check_current_page() || $this->check_option() === false ) {
			return;
		}
		?>

		<div class="shoppress-plugin-requires sp-message" data-message="plugins">
			<div class="box-img">
				<img src="<?php echo esc_url( SHOPPRESS_URL ); ?>public/images/logo/ic_shoppress.svg"
					alt="Get Plugin Requires"/>
			</div>
			<div class="box-content">
				<div>
					<h3>Recommended Plugins</h3>
					<p>The <b>ShopPress</b> plugin requires installation of WooCommerce first.</p>
					<div class="box-install">
						<?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
							<a href="<?php echo esc_url( $this->get_woocommerce_link() ); ?>">Install WooCommerce</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<button type="button" class="notice-dismiss sp-message-close">
			</button>
		</div>

		<?php
	}

	/**
	 * Go Pro message content.
	 *
	 * @since 1.0.0
	 */
	public function go_pro_message_content() {

		if ( ! $this->check_current_page() || $this->check_option_pro() === false ) {
			return;
		}

		?>
		<div class="shoppress-plugin-requires sp-go-pro" data-message="pro">
			<div class="box-img">
				<img src="<?php echo esc_url( SHOPPRESS_URL ); ?>public/images/logo/ic_shoppress.svg"
					alt="Get ShopPress by upgrading to Pro version"/>
			</div>
			<div class="box-content">
				<div>
					<h3>Unlock all the Powerful Features</h3>
					<p>Get full access to all advanced features of ShopPress by upgrading to Pro version right away.</p>
					<div class="box-install">
						<a href="https://climaxthemes.com/shoppress/">Go Premium</a>
					</div>
				</div>
			</div>
			<button type="button" class="notice-dismiss sp-pro-close">
			</button>
		</div>
		<?php
	}

	/**
	 * Update shoppress_message option.
	 *
	 * @since 1.0.0
	 */
	public function shoppress_admin_message() {
		$nonce   = sanitize_text_field( $_POST['nonce'] );
		$msgData = sanitize_text_field( $_POST['msgData'] );
		$msgPro  = sanitize_text_field( $_POST['msgPro'] );

		if ( ! wp_verify_nonce( $nonce, 'shoppress_message' ) ) {
			die( 'Wrong nonce!' );
		}

		if ( $msgData === 'plugins' ) {
			update_option( 'shoppress_message', false );
		}

		if ( $msgPro === 'pro' ) {
			update_option( 'shoppress_message_pro', false );
		}

		wp_die();
	}
}
