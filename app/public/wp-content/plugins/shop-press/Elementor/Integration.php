<?php
/**
 * Elementor Integrations.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

class Integration {
	/**
	 * init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}

		static::hooks();

		// Init the widgets.
		RegisterWidgets::init();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.0
	 */
	private static function hooks() {
		add_action( 'elementor/editor/after_enqueue_styles', array( __CLASS__, 'editor_styles' ) );
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'setup_categories' ) );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'preview_styles' ) );
		// add_action( 'elementor/init', array( __CLASS__, 'template_library' ), 0 );
		add_action( 'elementor/init', array( __CLASS__, 'activate_fontawesome' ) );
		add_filter( 'elementor/document/wrapper_attributes', array( __CLASS__, 'loop_builder_wrapper_attributes' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'init_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'loop_builder_editor_style' ) );
		add_action( 'elementor/controls/register', array( __CLASS__, 'add_controls' ) );
	}

	/**
	 * Elementor editor styles.
	 *
	 * @since 1.0.0
	 */
	public static function editor_styles() {
		wp_enqueue_style( 'sp-elementor-editor', SHOPPRESS_URL . 'public/admin/elementor/editor.css', null, SHOPPRESS_VERSION );
	}

	/**
	 * Elementor preview styles.
	 *
	 * @since 1.1.3
	 */
	public static function preview_styles() {
		wp_enqueue_style( 'sp-elementor-preview', SHOPPRESS_URL . 'public/admin/elementor/preview.css', null, SHOPPRESS_VERSION );
	}

	/**
	 * Loop builder editor style.
	 *
	 * @since 1.0.0
	 */
	public static function loop_builder_editor_style() {

		if ( get_the_ID() ) {

			$post_type = get_post_type( get_the_ID() );

			if ( 'shoppress_loop' === $post_type ) {

				echo '<style> .shoppress-wrap { max-width: 400px !important; margin: 0 auto !important; } .shoppress-wrap .elementor-section.elementor-section-boxed>.elementor-container { padding: 0 !important } .woocommerce ul.products li.product, .woocommerce-page ul.products li.product {width: 100% !important;} </style>';
			}
		}
	}

	/**
	 * Setup categories.
	 *
	 * @since 1.0.0
	 */
	public static function setup_categories( $elements_manager ) {
		$elements_manager->add_category(
			'sp_woo_single',
			array(
				'icon'  => 'eicon-single-product',
				'title' => __( 'Product Single', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_woo_shop',
			array(
				'icon'  => 'eicon-products-archive',
				'title' => __( 'Shop', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_woo_dashboard',
			array(
				'icon'  => 'eicon-dashboard',
				'title' => __( 'My Account', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_woo_cart',
			array(
				'icon'  => 'eicon-cart-light',
				'title' => __( 'Cart', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_general',
			array(
				'icon'  => 'eicon-global-settings',
				'title' => __( 'ShopPress General', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_woo_checkout',
			array(
				'icon'  => 'eicon-checkout',
				'title' => __( 'Checkout', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_woo_loop',
			array(
				'icon'  => 'eicon-products',
				'title' => __( 'Product Loop', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_wishlist',
			array(
				'icon'  => 'eicon-products',
				'title' => __( 'Wishlist', 'shop-press' ),
			)
		);
		$elements_manager->add_category(
			'sp_compare',
			array(
				'icon'  => 'eicon-products',
				'title' => __( 'Compare', 'shop-press' ),
			)
		);
	}

	/**
	 * Change Post Query Before load.
	 *
	 * @since 1.2.0
	 */
	public static function template_library() {
		require_once SHOPPRESS_PATH . 'Elementor/template-library/TemplatesLib.php';
	}

	/**
	 * Activate Elementor FontAwesome Icons.
	 *
	 * @since 1.2.0
	 */
	public static function activate_fontawesome() {

		if ( ! get_option( 'elementor_load_fa4_shim' ) ) {
			update_option( 'elementor_load_fa4_shim', 'yes' );
		}
	}

	/**
	 * Add Essential HTML to elementor editor.
	 *
	 * @since 1.2.0
	 */
	public static function loop_builder_wrapper_attributes( $attributes ) {

		if ( isset( $attributes['data-elementor-title'] ) && get_post_type() === 'shoppress_loop' && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$attributes['class'] = 'product ' . $attributes['class'];
		}
		return $attributes;
	}

	/**
	 * Init the editor styles.
	 *
	 * @since 1.2.0
	 */
	public static function init_editor_scripts() {
		EditorScripts::init();
	}

	/**
	 * Add controls.
	 *
	 * @since 1.0.0
	 */
	public static function add_controls( $controls_manager ) {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}

		require_once SHOPPRESS_PATH . 'Elementor/control/custom-css/CustomCSS.php';
		require_once SHOPPRESS_PATH . 'Elementor/control/product-select/product-select.php';

		$controls_manager->register( new \ShopPressElementorCustomCSS() );
		$controls_manager->register( new \ShopPressSelectProduct() );
	}
}
