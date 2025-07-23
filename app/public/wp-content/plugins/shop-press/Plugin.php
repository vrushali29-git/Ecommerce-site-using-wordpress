<?php
/**
 * Plugin.
 *
 * @package ShopPress
 */

namespace ShopPress;

defined( 'ABSPATH' ) || exit;

use ShopPress\Admin;
use ShopPress\Compatibility;
use ShopPress\Templates;
use ShopPress\Elementor;
use ShopPress\Modules\MenuCart;
use ShopPress\Modules\Wishlist\Main;

final class Plugin {
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
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		Assets::init();

		if ( is_admin() ) {
			Modules\AdminMessage::get_instance();
			Admin\Page::init();
		}

		// TODO: improve this line
		if ( ! in_array( 'woocommerce/woocommerce.php', (array) get_option( 'active_plugins', array() ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		$this->hooks();
		$this->init_shoppress();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.0
	 */
	private function hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ), 99 );
		add_action( 'init', array( $this, 'init_compatibility' ) );
		add_action( 'admin_init', array( __CLASS__, 'upgrade_default_options' ), 99 );
		add_action( 'init', array( __CLASS__, 'rewrite_rules' ), 99 );
		add_filter( 'render_block', array( __CLASS__, 'replace_blocks_with_shortcodes' ), 10, 2 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'fragment' ), 99, 1 );
		add_action( 'shoppress/builder/before_load_template', array( __CLASS__, 'setup_postdata' ), 9, 1 );
		add_action( 'shoppress/builder/after_load_template', array( __CLASS__, 'reset_postdata' ), 9, 1 );
	}

	/**
	 * Load the localization file.
	 *
	 * @since   1.2.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'shop-press', false, basename( __DIR__ ) . '/languages' );
	}

	/**
	 * Enqueue style.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {

		wp_enqueue_script( 'sp-frontend' );
		wp_enqueue_style( 'sp-frontend' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-frontend-rtl' );
		}

		wp_localize_script(
			'sp-frontend',
			'shoppress_frontend',
			apply_filters(
				'shoppress_frontend_localize',
				array(
					'ajax' => array(
						'url'   => admin_url( 'admin-ajax.php' ),
						'nonce' => wp_create_nonce( 'shoppress_nonce' ),
					),
					'i18n' => array(
						'days'        => __( 'Days', 'shop-press' ),
						'hours'       => __( 'Hours', 'shop-press' ),
						'minutes'     => __( 'Minutes', 'shop-press' ),
						'seconds'     => __( 'Seconds', 'shop-press' ),
						'star'        => __( 'Star', 'shop-press' ),
						'stars'       => __( 'Stars', 'shop-press' ),
						'add_to_cart' => __( 'Add to Cart', 'shop-press' ),
					),
				)
			)
		);
	}

	/**
	 * Admin enqueue style and script.
	 *
	 * @since 1.3.3
	 */
	public function admin_enqueue() {

		wp_enqueue_script( 'sp-backend' );
		wp_enqueue_style( 'sp-backend' );

		wp_localize_script(
			'sp-backend',
			'shoppress_backend',
			apply_filters(
				'shoppress_backend_localize',
				array(
					'ajax' => array(
						'url'   => admin_url( 'admin-ajax.php' ),
						'nonce' => wp_create_nonce( 'shoppress_backend_nonce' ),
					),
					'i18n' => array(),
				)
			)
		);
	}

	/**
	 * Load the dependencies.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		// General functions
		require_once SHOPPRESS_PATH . 'includes/functions/functions-general.php';
		require_once SHOPPRESS_PATH . 'includes/styler/styler.php';
	}

	/**
	 * Load the shoppress dependencies.
	 *
	 * @since 1.0.0
	 */
	private function init_shoppress() {
		Admin\Main::init();
		Templates\Main::init();
		BlockEditor\Integration::init();
		Elementor\Integration::init();
		Modules\RecentlyViewedProducts::init();
		Modules\AjaxSearch::init();

		$modules = apply_filters(
			'shoppress_modules',
			array(
				'wishlist'                => Modules\Wishlist\Main::class,
				'compare'                 => Modules\Compare::class,
				'quick_view'              => Modules\QuickView::class,
				'catalog_mode'            => Modules\CatalogMode::class,
				'mobile_panel'            => Modules\MobilePanel::class,
				'size_chart'              => Modules\SizeChart::class,
				'rename_label'            => Modules\RenameLabel::class,
				'sticky_add_to_cart'      => Modules\StickyAddToCart::class,
				'single_ajax_add_to_cart' => Modules\SingleAjaxAddToCart::class,
				'backorder'               => Modules\Backorder::class,
				'flash_sale_countdown'    => Modules\FlashSalesCountdown::class,
				'notifications'           => Modules\Notifications::class,
				'variation_swatches'      => Modules\VariationSwatches\Main::class,
				'menu_cart'               => Modules\MenuCart::class,
				'multi_step_checkout'     => Modules\MultiStep::class,
				'shopify_checkout'        => Modules\Shopify::class,
			)
		);

		foreach ( $modules as $module_id => $module_class ) {

			if ( sp_is_module_active( $module_id ) ) {

				if ( class_exists( $module_class ) ) {
					$module_class::init();
				}
			}
		}

		$templates = apply_filters(
			'shoppress_templates',
			array(
				'single'     => Templates\Single\Main::class,
				'archive'    => Templates\Archive::class,
				'shop'       => Templates\Shop::class,
				'checkout'   => Templates\Checkout\Main::class,
				'cart'       => Templates\Cart::class,
				'empty_cart' => Templates\EmptyCart::class,
				'my_account' => Templates\MyAccount::class,
				'thank_you'  => Templates\Thankyou::class,
			)
		);

		foreach ( $templates as $template_id => $template_class ) {

			if ( sp_is_template_active( $template_id ) ) {

				if ( class_exists( $template_class ) ) {
					$template_class::init();
				}
			}
		}

		add_action( 'widgets_init', array( __CLASS__, 'register_shoppress_widget_layered_nav_widget' ), 99 );

		do_action( 'shoppress/after_init' );
	}

	/**
	 * ShopPress compatibilities.
	 *
	 * @since 1.1.0
	 */
	public function init_compatibility() {
		// Init Astra theme compatibility
		if ( defined( 'ASTRA_THEME_VERSION' ) ) {
			Compatibility\Astra::init();
		}

		// Init Kata theme compatibility
		if ( defined( 'KATA_VERSION' ) ) {
			Compatibility\KataTheme::init();
		}

		// Init Storefront theme compatibility
		if ( function_exists( 'storefront_is_woocommerce_activated' ) ) {
			Compatibility\Storefront::init();
		}
	}

	/**
	 * ShopPress rewrite rules.
	 *
	 * @since 1.2.0
	 */
	public static function rewrite_rules() {

		if ( 'yes' === get_option( 'sp_need_rewrite_rules', 'no' ) ) {

			flush_rewrite_rules();

			delete_option( 'sp_need_rewrite_rules' );
		}
	}

	/**
	 * Add default option.
	 *
	 * @since   1.0.0
	 */
	public static function add_sp_option() {
		$options = Admin\DefaultOptions\Options::get_default_options();

		$options = json_encode( $options );

		add_option( 'sp_admin', $options );
	}

	/**
	 * Register shoppress widget layered nav
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_shoppress_widget_layered_nav_widget() {

		if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {

			require_once SHOPPRESS_PATH . 'includes/wp-widget/LayeredNav.php';
			register_widget( 'ShopPress_Widget_Layered_Nav' );
		}
	}

	/**
	 * Check if Elementor plugin is active.
	 *
	 * @since 1.1.4
	 *
	 * @return bool
	 */
	public static function is_active_elementor() {

		if ( ! function_exists( 'is_plugin_active' ) ) {

			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'elementor/elementor.php' );
	}

	/**
	 * Compatibility with Woo 8.3
	 *
	 * @since 1.2.0
	 */
	public static function replace_blocks_with_shortcodes( $content, $block ) {

		if ( is_checkout() && ( sp_is_template_active( 'checkout' ) || sp_is_module_active( 'multi_step_checkout' ) ) ) {

			if ( $block && $block['blockName'] === 'woocommerce/checkout' ) {

				$content = '[woocommerce_checkout]';
			}
		}

		if ( is_cart() && sp_is_template_active( 'cart' ) ) {

			if ( $block && $block['blockName'] === 'woocommerce/cart' ) {

				$content = '[woocommerce_cart]';
			}
		}

		return $content;
	}

	/**
	 * Upgrade default options admin settings.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function upgrade_default_options() {

		$version     = get_option( 'sp_admin_options_version', '1.0.0' );
		$pro_version = get_option( 'sp_pro_admin_options_version', '1.0.0' );
		if ( version_compare( $version, SHOPPRESS_VERSION, '>=' ) && ( defined( 'SHOPPRESS_PRO_VERSION' ) && version_compare( $pro_version, SHOPPRESS_PRO_VERSION, '>=' ) ) ) {
			return;
		}

		$options = Admin\DefaultOptions\Options::get_default_options();

		$settings = get_option( 'sp_admin', array() );
		$settings = ! is_array( $settings ) ? json_decode( $settings, true ) : $settings;
		foreach ( $options as $group_key => $g_options ) {

			foreach ( $g_options as $s_group_key => $s_options ) {

				if ( ! isset( $settings[ $group_key ][ $s_group_key ] ) ) {

					$settings[ $group_key ][ $s_group_key ] = $s_options;
				} else {

					foreach ( $s_options as $s_option_key => $s_option ) {

						if ( ! isset( $settings[ $group_key ][ $s_group_key ][ $s_option_key ] ) ) {

							$settings[ $group_key ][ $s_group_key ][ $s_option_key ] = $s_option;
						}
					}
				}
			}
		}

		update_option( 'sp_admin', json_encode( $settings ) );
		update_option( 'sp_admin_options_version', SHOPPRESS_VERSION );
		if ( defined( 'SHOPPRESS_PRO_VERSION' ) ) {
			update_option( 'sp_pro_admin_options_version', SHOPPRESS_PRO_VERSION );
		}
	}

	public static function fragment( $fragments ) {

		$cart_items_count                  = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$fragments['.sp-cart-items-count'] = '<span class="sp-cart-items-count">' . $cart_items_count . '</span>';
		ob_start();
			MenuCart::cart_content();
		$fragments['.sp-mini-cart.sp-mini-cart-style-1'] = ob_get_clean();

		if ( sp_is_module_active( 'wishlist' ) ) {

			$count                                 = Main::get_total_wishlist_products();
			$fragments['.sp-wishlist-items-count'] = '<span class="sp-wishlist-items-count">' . $count . '</span>';
		}

		return $fragments;
	}

	/**
	 * Set the post data.
	 *
	 * @since 1.2.5
	 *
	 * @return void
	 */
	public static function setup_postdata( $builder ) {

		if ( in_array( $builder, Templates\Render::get_builders_need_set_post_data() ) ) {

			Templates\Utils::setup_postdata();
		}
	}

	/**
	 * Reset the post data.
	 *
	 * @since 1.2.5
	 *
	 * @return void
	 */
	public static function reset_postdata( $builder ) {

		if ( in_array( $builder, Templates\Render::get_builders_need_set_post_data() ) ) {

			Templates\Utils::reset_postdata();
		}
	}
}
