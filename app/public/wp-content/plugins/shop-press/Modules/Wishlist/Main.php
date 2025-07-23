<?php
/**
 * Wishlist.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules\Wishlist;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class Main {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		$wishlist_location_single  = sp_get_module_settings( 'wishlist_general_settings', 'location_product_page' )['value'] ?? '';
		$wishlist_location_archive = sp_get_module_settings( 'wishlist_general_settings', 'location_products_loop' )['value'] ?? '';

		self::setup_cookie();
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_filter( 'body_class', array( __CLASS__, 'filter_body_class' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
		add_filter( 'shoppress_frontend_localize', array( __CLASS__, 'filter_localize' ), 10, 1 );
		add_shortcode( 'shoppress-wishlist-page', array( __CLASS__, 'render_wishlist_page' ) );
		add_filter( 'template_include', array( __CLASS__, 'full_template' ) );
		add_action( 'template_redirect', array( __CLASS__, 'check_view_wishlist' ), 0 );

		add_action( 'wp_ajax_AddWishlist', array( __CLASS__, 'add_to_wishlist_by_ajax' ) );
		add_action( 'wp_ajax_nopriv_AddWishlist', array( __CLASS__, 'add_to_wishlist_by_ajax' ) );

		add_action( 'wp_ajax_sp-my-wishlist-run-action', array( __CLASS__, 'run_action_by_ajax' ) );
		add_action( 'wp_ajax_nopriv_sp-my-wishlist-run-action', array( __CLASS__, 'run_action_by_ajax' ) );

		add_filter( 'shoppress/api/get_posts/prepare_data', array( __CLASS__, 'prepare_list_table_data' ), 9, 3 );

		if ( ! empty( $wishlist_location_single ) ) {
			add_action( $wishlist_location_single, array( __CLASS__, 'wishlist_output' ) );
		}

		if ( ! empty( $wishlist_location_archive ) ) {
			add_action( $wishlist_location_archive, array( __CLASS__, 'wishlist_output' ) );
		}

		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_wishlist_widgets' ), 9 );

		if ( sp_get_module_settings( 'wishlist_general_settings', 'wishlist_menu_module' ) ) {
			MenuWishlist::init();
		}

		if ( sp_get_module_settings( 'wishlist_general_settings', 'add_menu_to_wc_myaccount' ) ) {
			MyAccount::init();
		}
	}

	/**
	 * Check view wishlist and redirect
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public static function check_view_wishlist() {

		$enable_wishlist_only_for_logged_in_users = sp_get_module_settings( 'wishlist_general_settings', 'enable_wishlist_only_for_logged_in_users', false );
		if ( $enable_wishlist_only_for_logged_in_users && ! is_user_logged_in() ) {

			$wishlist_key = get_query_var( 'view' );
			if ( ! $wishlist_key && static::is_wishlist_page() ) {

				$location = esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) );
				wp_redirect( $location );
				exit;
			}
		}
	}

	/**
	 * Register post type
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function register_post_type() {

		register_post_type(
			'shoppress_wishlist',
			array(
				'labels'              => array(
					'name'          => __( 'Wishlist', 'shop-press' ),
					'singular_name' => __( 'Wishlist', 'shop-press' ),
				),
				'public'              => false,
				'show_in_menu'        => false,
				'show_ui'             => true,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'supports'            => array( 'editor', 'title', 'custom-fields' ),
			)
		);
	}

	/**
	 * Is active multi wishlist
	 *
	 * @since 1.2.1
	 *
	 * @return boolean
	 */
	public static function is_active_multi_wishlist() {
		$status = sp_get_module_settings( 'wishlist_general_settings', 'enable_multi_wishlist' );

		return $status;
	}

	/**
	 * Is active multi wishlist for current user
	 *
	 * @since 1.2.1
	 *
	 * @return boolean
	 */
	public static function is_active_multi_wishlist_for_current_user() {
		$status = self::is_active_multi_wishlist();
		if ( ! $status ) {
			return false;
		}

		$enable_wishlist_only_for_logged_in_users = sp_get_module_settings( 'wishlist_general_settings', 'enable_wishlist_only_for_logged_in_users' );
		if ( $enable_wishlist_only_for_logged_in_users && ! is_user_logged_in() ) {
			return false;
		}

		$enable_multi_wishlist_only_for_logged_in_users = sp_get_module_settings( 'wishlist_general_settings', 'enable_multi_wishlist_only_for_logged_in_users' );
		if ( $enable_multi_wishlist_only_for_logged_in_users && ! is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	/**
	 * Return Wishlist data
	 *
	 * @param int|\WP_Post $wishlist
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_wishlist_data( $wishlist ) {

		$share_statuses = array(
			'private' => __( 'Private', 'shop-press' ),
			'public'  => __( 'Public', 'shop-press' ),
		);
		$wishlist       = is_numeric( $wishlist ) ? get_post( $wishlist ) : $wishlist;
		if ( ! is_a( $wishlist, '\WP_Post' ) ) {
			return array();
		}
		$wishlist_key = get_post_meta( $wishlist->ID, 'shoppress_wishlist_key', true );
		$product_ids  = get_post_meta( $wishlist->ID, 'shoppress_wishlist_product_ids', true );
		$product_ids  = is_array( $product_ids ) ? $product_ids : array();
		$author_id    = $wishlist->post_author;
		$user_data    = $author_id ? get_user_by( 'id', $author_id ) : false;
		$share_status = get_post_meta( $wishlist->ID, 'shoppress_wishlist_share_status', true );

		foreach ( $product_ids as $product_id => $product_data ) {

			if ( ! is_array( $product_data ) ) {

				$product_ids[ $product_id ] = array(
					'product_id' => $product_id,
					'date_added' => time(),
				);
			}
		}

		$wishlist_data = array(
			'wishlist_id'        => $wishlist->ID,
			'title'              => $wishlist->post_title,
			'author'             => get_post_meta( $wishlist->ID, 'shoppress_user_id', true ),
			'created_date'       => strtotime( $wishlist->post_date ),
			'type'               => get_post_meta( $wishlist->ID, 'shoppress_wishlist_type', true ),
			'key'                => $wishlist_key,
			'share_status'       => $share_status,
			'share_status_label' => $share_statuses[ $share_status ] ?? '',
			'product_ids'        => $product_ids,
			'total_items'        => count( $product_ids ),
			'author_name'        => $user_data ? $user_data->nickname : get_post_meta( $wishlist->ID, 'shoppress_author_name', true ),
			'author_email'       => $user_data ? $user_data->user_email : get_post_meta( $wishlist->ID, 'shoppress_author_email', true ),
		);

		return $wishlist_data;
	}

	/**
	 * Return Wishlist Posts
	 *
	 * @param array $args
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function get_wishlist_posts( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'post_type'      => 'shoppress_wishlist',
				'post_status'    => 'publish',
				'posts_per_page' => 500,
				'orderby'        => 'id',
				'order'          => 'ASC',
			)
		);

		return get_posts( $args );
	}

	/**
	 * Return Wishlists
	 *
	 * @param array $args
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_wishlists( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'user_id'       => null,
				'wishlist_type' => null, // default|custom
				'wishlist_key'  => null,
			)
		);

		if ( isset( $args['user_id'] ) ) {

			$args['meta_query']['user_id'] = array(
				'key'     => 'shoppress_user_id',
				'value'   => $args['user_id'],
				'compare' => '=',
			);
		}

		if ( ! is_null( $args['wishlist_type'] ) ) {

			$args['meta_query']['wishlist_type'] = array(
				'key'     => 'shoppress_wishlist_type',
				'value'   => $args['wishlist_type'],
				'compare' => '=',
			);
		}

		if ( ! is_null( $args['wishlist_key'] ) ) {

			$args['meta_query']['wishlist_key'] = array(
				'key'     => 'shoppress_wishlist_key',
				'value'   => $args['wishlist_key'],
				'compare' => is_array( $args['wishlist_key'] ) ? 'IN' : '=',
			);
		}

		$_wishlists = self::get_wishlist_posts( $args );
		$wishlists  = array();
		foreach ( $_wishlists as $k => $wishlist ) {

			$wishlist = self::get_wishlist_data( $wishlist );
			if ( empty( $wishlist ) ) {
				continue;
			}
			$wishlist_key               = $wishlist['key'] ?? '';
			$wishlists[ $wishlist_key ] = $wishlist;
		}

		return $wishlists;
	}

	/**
	 * Return Wishlist
	 *
	 * @param int|string $wishlist_id_or_wishlist_key
	 *
	 * @since 1.2.1
	 *
	 * @return array
	 */
	public static function get_wishlist( $wishlist_id_or_wishlist_key ) {

		$wishlist_id = is_numeric( $wishlist_id_or_wishlist_key ) ? $wishlist_id_or_wishlist_key : self::get_wishlist_id_by_wishlist_key( $wishlist_id_or_wishlist_key );

		return self::get_wishlist_data( $wishlist_id );
	}

	/**
	 * Return Wishlist
	 *
	 * @param int|string $wishlist_id_or_wishlist_key
	 *
	 * @since 1.2.1
	 *
	 * @return array
	 */
	public static function delete_wishlist( $wishlist_id ) {
		wp_trash_post( $wishlist_id );
	}

	/**
	 * Return wishlist products
	 *
	 * @param string|array $wishlist_key
	 *
	 * @since 1.2.1
	 *
	 * @return array
	 */
	public static function get_wishlist_products_by_wishlist_key( $wishlist_key ) {

		$wishlists = self::get_wishlists(
			array(
				'wishlist_key' => $wishlist_key,
			)
		);

		$products = array();
		foreach ( $wishlists as $wishlist ) {
			$wishlist_products = $wishlist['product_ids'] ?? array();
			foreach ( $wishlist_products as $product_id => $data ) {
				$data['wishlist_key']    = $wishlist['key'];
				$products[ $product_id ] = $data;
			}
		}

		return $products;
	}

	/**
	 * Return wishlist id
	 *
	 * @param string $wishlist_key
	 *
	 * @since 1.2.1
	 *
	 * @return array
	 */
	public static function get_wishlist_id_by_wishlist_key( $wishlist_key ) {

		$wishlists = self::get_wishlists(
			array(
				'wishlist_key' => $wishlist_key,
			)
		);

		$wishlist = current( $wishlists );

		return $wishlist['wishlist_id'] ?? false;
	}

	/**
	 * Create Wishlist
	 *
	 * @param string $wishlist_title
	 * @param int    $user_id
	 * @param string $wishlist_type
	 * @param string $share_status
	 * @param array  $atts
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function create_wishlist( $wishlist_title, $user_id, $wishlist_type = 'default', $share_status = 'private', $atts = array() ) {

		if ( $user_id ) {
			$wishlists = self::get_wishlists(
				array(
					'user_id'       => $user_id,
					'wishlist_type' => $wishlist_type,
					'name'          => $wishlist_title,
				)
			);

			if ( count( $wishlists ) ) {
				return current( $wishlists );
			}
		}

		$post_args = array(
			'post_type'   => 'shoppress_wishlist',
			'post_status' => 'publish',
			'post_author' => $user_id,
			'post_title'  => $wishlist_title,
			'meta_input'  => array(
				'shoppress_wishlist_type'         => $wishlist_type,
				'shoppress_wishlist_share_status' => $share_status,
			),
		);

		if ( ! $user_id ) {
			$post_args['meta_input']['shoppress_author_email'] = $atts['author_email'] ?? '';
			$post_args['meta_input']['shoppress_author_name']  = $atts['author_name'] ?? '';
		}

		$post_args['meta_input']['shoppress_user_id'] = $user_id;

		$wishlist_id = wp_insert_post( $post_args );

		if ( $wishlist_id ) {

			$wishlist_key = md5( 'sp-wishlist-' . $wishlist_id );
			update_post_meta( $wishlist_id, 'shoppress_wishlist_key', $wishlist_key );
		}

		return self::get_wishlist_data( $wishlist_id );
	}

	/**
	 * Update Wishlist
	 *
	 * @param int|string $name
	 * @param array      $atts
	 *
	 * @since 1.2.1
	 *
	 * @return |array|false
	 */
	public static function update_wishlist( $wishlist_id_or_wishlist_key, $atts ) {

		$user_id     = get_current_user_id();
		$wishlist    = self::get_wishlist( $wishlist_id_or_wishlist_key );
		$wishlist_id = $wishlist['wishlist_id'] ?? false;
		if ( ! $wishlist_id ) {
			return false;
		}

		$post_args = array(
			'ID'         => $wishlist_id,
			'post_title' => $atts['title'] ?? $wishlist['title'],
			'meta_input' => array(
				'shoppress_wishlist_share_status' => $atts['share_status'] ?? $wishlist['share_status'],
			),
		);

		if ( ! $user_id ) {
			$post_args['meta_input']['shoppress_author_email'] = $atts['author_email'] ?? '';
			$post_args['meta_input']['shoppress_author_name']  = $atts['author_name'] ?? '';
		}

		$id = wp_update_post( $post_args );

		return self::get_wishlist( $wishlist_id );
	}

	/**
	 * Adds the wishlist widgets to the existing list of widgets.
	 *
	 * @param array $widgets The array of widgets.
	 *
	 * @since  1.2.0
	 *
	 * @return array The updated array of widgets.
	 */
	public static function add_wishlist_widgets( $widgets ) {
		$widgets['single-wishlist'] = array(
			'editor_type' => 'single',
			'class_name'  => 'Wishlist',
			'is_pro'      => false,
			'path_key'    => 'single-product/wishlist',
		);

		$widgets['my-wishlist'] = array(
			'editor_type' => 'wishlist',
			'class_name'  => 'MyWishlist',
			'is_pro'      => false,
			'path_key'    => 'my-wishlist/my-wishlist',
		);

		$widgets['loop-wishlist'] = array(
			'editor_type' => 'loop',
			'class_name'  => 'LoopBuilder\Wishlist',
			'is_pro'      => false,
			'path_key'    => 'loop/wishlist',
		);

		$widgets['mini-wishlist'] = array(
			'editor_type' => 'general',
			'class_name'  => 'MiniWishlist',
			'is_pro'      => false,
			'path_key'    => 'general/mini-wishlist',
		);

		return $widgets;
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		$builder_id = sp_get_template_settings( 'wishlist', 'page_builder' );
		if ( self::is_wishlist_page() && 'block_editor' === sp_get_builder_type( $builder_id ) ) {

			add_filter(
				'styler/block_editor/post_id',
				function () {
					return sp_get_template_settings( 'wishlist', 'page_builder' );
				}
			);
		}

		wp_enqueue_style( 'sp-pr-general' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-pr-general-rtl' );
		}

		wp_enqueue_script( 'sp-wishlist' );
	}

	/**
	 * Check wishlist builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_wishlist_builder() {
		return sp_get_template_settings( 'wishlist', 'status' ) && sp_get_template_settings( 'wishlist', 'page_builder' ) ? true : false;
	}

	/**
	 * Check static wishlist.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_static_wishlist() {
		return ! self::is_wishlist_builder() ? true : false;
	}

	/**
	 * Check wishlist page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_wishlist_page() {
		$wishlist_page_id = self::get_wishlist_page_id();
		return get_the_ID() === $wishlist_page_id;
	}

	/**
	 * Return wishlist page id.
	 *
	 * @since 1.2.1
	 *
	 * @return int
	 */
	public static function get_wishlist_page_id() {

		return sp_get_module_settings( 'wishlist_general_settings', 'wishlist_page', false )['value'] ?? null;
	}

	/**
	 * Return wishlist page slug.
	 *
	 * @since 1.2.1
	 *
	 * @return string
	 */
	public static function get_wishlist_page_slug() {

		$wishlist_page_id = self::get_wishlist_page_id();
		$wishlist         = $wishlist_page_id ? get_post( $wishlist_page_id ) : false;

		return is_a( $wishlist, '\WP_Post' ) ? $wishlist->post_name : 'wishlist';
	}

	/**
	 * body class.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function filter_body_class( $classes ) {

		if ( self::is_wishlist_page() ) {

			return array_merge( $classes, array( 'woocommerce woocommerce-page ' ) );
		}

		return $classes;
	}

	/**
	 * Setup Cookie.
	 *
	 * @since 1.0.0
	 */
	public static function setup_cookie() {
		if ( ! isset( $_COOKIE['shoppress_wishlist'] ) ) {
			@setcookie( 'shoppress_wishlist', '{}', time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
		}
	}

	/**
	 * Update Localize.
	 *
	 * @since 1.0.0
	 */
	public static function filter_localize( $localize ) {
		$wishlist_title        = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
		$wishlist_remove_title = sp_get_module_settings( 'wishlist_general_settings', 'remove_label' );
		$disable_notices       = sp_get_module_settings( 'wishlist_general_settings', 'disable_notices' );
		$disable_notices       = is_array( $disable_notices ) ? array_column( $disable_notices, 'value' ) : array();

		$localize['wishlist'] = array(
			'add'    => array(
				'message'        => __( 'Product added to wishlist', 'shop-press' ),
				'wishlist_label' => $wishlist_remove_title,
				'status'         => 'yes',
				'wishlist_count' => self::get_total_wishlist_products(),
				'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
				'notice_status'  => in_array( 'add_to_wishlist', $disable_notices ) ? false : true,
			),
			'remove' => array(
				'message'        => __( 'Product removed from wishlist', 'shop-press' ),
				'wishlist_label' => $wishlist_title,
				'status'         => 'no',
				'wishlist_count' => self::get_total_wishlist_products(),
				'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
				'notice_status'  => in_array( 'remove_from_wishlist', $disable_notices ) ? false : true,
			),
		);

		return $localize;
	}

	/**
	 * Wishlist Menu.
	 *
	 * @since 1.1.5
	 */
	public static function wishlist_menu( $wishlist ) {

		$dropdown = '<div class="sp-wishlist-title">' . __( 'Wishlist', 'shop-press' ) . '</div>';

		foreach ( $wishlist as $item => $wishlist_item_data ) {
			$wishlist_key = $wishlist_item_data['wishlist_key'] ?? '';
			$product_id   = $wishlist_item_data['product_id'] ?? '';
			$product      = wc_get_product( $product_id );
			$price        = $product->get_price_html();
			$image        = $product->get_image( array( 60, 60 ) );
			$title        = $product->get_title();
			$product_id   = $product->get_id();

			$dropdown .= '<div class="sp-wishlist-pr">';
			$dropdown .= '<span class="sp-wishlist-img">';
			$dropdown .= wp_kses_post( $image );
			$dropdown .= '</span>';
			$dropdown .= '<div class="sp-mmceta">';
			$dropdown .= '	<a class="sp-wishlist-pr-title" href="' . esc_url( get_permalink( $product_id ) ) . '">';
			$dropdown .= $title;
			$dropdown .= '	</a>';
			$dropdown .= '<div class="sp-wishlist-p-wrap">';
			$dropdown .= '	<span class="sp-wishlist-price">';
			$dropdown .= wp_kses_post( $price );
			$dropdown .= '</span>';
			$dropdown .= '</div>';
			$dropdown .= '</div>';
			$dropdown .= '<div class="sp-rmf-wishlist" data-product_id="' . esc_attr( $product_id ) . '" data-wishlist_key="' . esc_attr( $wishlist_key ) . '">';
			$dropdown .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 36 36"> <g transform="translate(-830 -22)"> <circle cx="18" cy="18" r="18" transform="translate(830 22)" fill="#f1f2f3"/> <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,1,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,1,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" transform="translate(843 35)" fill="#959ca7"/> </g> </svg>';
			$dropdown .= '</div>';
			$dropdown .= '</div>';
		}

		if ( ! $wishlist ) {

			$dropdown .= '<div class="sp-wishlist-empty">';
			$dropdown .= __( 'Your wishlist is currently empty', 'shop-press' );
			$dropdown .= '</div>';
			$dropdown .= '</div>';

			return $dropdown;
		}

		$dropdown .= '<div class="sp-wishlist-link">';
		$dropdown .= '<a href="' . esc_url( self::get_wishlist_page_url() ) . '">';
		$dropdown .= __( 'View Wishlist', 'shop-press' );
		$dropdown .= '</a>';
		$dropdown .= '</div>';

		return $dropdown;
	}

	/**
	 * Wishlist output.
	 *
	 * @since 1.0.0
	 */
	public static function wishlist_output() {
		global $shoppress_cross_sell_popup;
		// Do not display the variations in cross-sell popup
		if ( $shoppress_cross_sell_popup ) {
			return;
		}

		$product_id            = get_the_ID();
		$Wishlist              = self::get_wishlist_products();
		$wishlist_key          = $Wishlist[ $product_id ]['wishlist_key'] ?? false;
		$Wishlist              = $wishlist_key ? 'yes' : 'no';
		$wishlist_title        = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
		$wishlist_remove_title = sp_get_module_settings( 'wishlist_general_settings', 'remove_label' );
		?>

		<div class="sp-wishlist" data-template-type="static" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Wishlist ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
			<div class="sp-wishlist-button">
				<span class="sp-wishlist-icon">
					<?php echo wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ); ?>
				</span>

				<?php if ( $wishlist_title && $Wishlist == 'yes' ) { ?>
					<span class="sp-wishlist-label"><?php echo esc_html( $wishlist_remove_title ); ?></span>
				<?php } elseif ( $wishlist_remove_title && $Wishlist == 'no' ) { ?>
					<span class="sp-wishlist-label"><?php echo esc_html( $wishlist_title ); ?></span>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Return wishlist page url.
	 *
	 * @since 1.2.0
	 *
	 * @return mixed
	 */
	public static function get_wishlist_page_url() {
		$page_id = sp_get_module_settings( 'wishlist_general_settings', 'wishlist_page' )['value'] ?? '';

		return get_permalink( $page_id );
	}

	/**
	 * Add to wishlist
	 *
	 * @param int $wishlist_id
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function _add_to_wishlist( $wishlist_id, $product_id ) {

		$wishlist    = self::get_wishlist_data( $wishlist_id );
		$product_ids = $wishlist['product_ids'] ?? array();

		$product_ids[ $product_id ] = array(
			'product_id' => $product_id,
			'date_added' => time(),
		);

		update_post_meta( $wishlist_id, 'shoppress_wishlist_product_ids', $product_ids );

		return $product_ids;
	}

	/**
	 * Remove to wishlist
	 *
	 * @param int $wishlist_id
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function _remove_from_wishlist( $wishlist_id, $product_id ) {

		$wishlist    = self::get_wishlist_data( $wishlist_id );
		$product_ids = $wishlist['product_ids'] ?? array();

		unset( $product_ids[ $product_id ] );

		update_post_meta( $wishlist_id, 'shoppress_wishlist_product_ids', $product_ids );

		return $product_ids;
	}

	/**
	 * Check user can edit product in the wishlist
	 *
	 * @param int|string $wishlist_id_or_wishlist_key
	 * @param int        $user_id
	 *
	 * @since 1.2.1
	 *
	 * @return bool
	 */
	public static function user_can_edit_product_in_wishlist( $wishlist_id_or_wishlist_key, $user_id ) {

		$wishlist         = self::get_wishlist( $wishlist_id_or_wishlist_key );
		$wishlist_user_id = $wishlist['author'] ?? 0;

		if ( $wishlist_user_id == $user_id ) {

			return true;
		}

		return false;
	}

	/**
	 * Check user can view wishlist
	 *
	 * @param int|string $wishlist_id_or_wishlist_key
	 * @param int        $user_id
	 *
	 * @since 1.2.1
	 *
	 * @return bool
	 */
	public static function user_can_view_wishlist( $wishlist_id_or_wishlist_key, $user_id = null ) {

		$user_id          = is_null( $user_id ) ? get_current_user_id() : $user_id;
		$wishlist         = self::get_wishlist( $wishlist_id_or_wishlist_key );
		$wishlist_user_id = $wishlist['author'] ?? 0;
		$share_status     = $wishlist['share_status'] ?? 'private';

		if ( $wishlist_user_id == $user_id || 'public' === $share_status ) {

			return true;
		}

		return false;
	}

	/**
	 * Add product to the wishlist.
	 *
	 * @param int    $product_id
	 * @param string $wishlist_key
	 * @param string $wishlist_type
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public static function add_to_wishlist( $product_id = null, $wishlist_key = null, $wishlist_type = 'default' ) {

		$user_id     = get_current_user_id();
		$wishlists   = self::get_wishlists( $user_id, $wishlist_key, $wishlist_type );
		$wishlist_id = $wishlists[ $wishlist_key ]['wishlist_id'] ?? false;

		if ( ! $wishlist_id ) {
			return false;
		}

		if ( self::user_can_edit_product_in_wishlist( $wishlist_id, $user_id ) ) {
			return self::_add_to_wishlist( $wishlist_id, $product_id );
		}

		return false;
	}

	/**
	 * Remove product from the wishlist.
	 *
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 */
	public static function remove_from_wishlist( $product_id = null, $wishlist_key = null, $wishlist_type = 'default' ) {

		$user_id = get_current_user_id();

		$wishlists   = self::get_wishlists( $user_id, $wishlist_key, $wishlist_type );
		$wishlist_id = $wishlists[ $wishlist_key ]['wishlist_id'] ?? false;

		if ( ! $wishlist_id ) {
			return false;
		}

		if ( self::user_can_edit_product_in_wishlist( $wishlist_id, $user_id ) ) {
			return self::_remove_from_wishlist( $wishlist_id, $product_id );
		}

		return false;
	}

	/**
	 * Return current user wishlist products in cookie
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_current_user_wishlist_products_in_cookie() {

		return isset( $_COOKIE['shoppress_wishlist'] ) ? json_decode( wp_unslash( $_COOKIE['shoppress_wishlist'] ), true ) : array();
	}

	/**
	 * Return current user wishlists
	 *
	 * @param int $limit
	 * @param int $paged
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_current_user_wishlists( $limit = -1, $paged = 1 ) {

		$user_id = get_current_user_id();
		if ( $user_id ) {

			$args = array(
				'user_id' => $user_id,
			);
		} else {
			$wishlist_keys = self::get_current_user_wishlist_products_in_cookie();
			if ( empty( $wishlist_keys ) ) {

				return array();
			}

			$args = array(
				'user_id'      => 0,
				'wishlist_key' => $wishlist_keys,
			);
		}

		$wishlists = self::get_wishlists( $args );

		if ( is_numeric( $limit ) && -1 !== $limit ) {
			$offset    = $limit > 0 && $paged ? ( ( $paged - 1 ) * $limit ) : 0;
			$wishlists = array_slice( $wishlists, $offset, $limit, true );
		}

		return $wishlists;
	}

	/**
	 * Return current user wishlists
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_current_user_default_wishlist( $create_if_not_exists = true ) {
		$wishlists = self::get_current_user_wishlists();
		$user_id   = get_current_user_id();

		if ( empty( $wishlists ) && $create_if_not_exists ) {
			$wishlist = self::create_wishlist(
				__( 'My Wishlist', 'shop-press' ),
				$user_id
			);
		} else {
			$wishlist = current( $wishlists );
		}

		return is_array( $wishlist ) ? $wishlist : array();
	}

	/**
	 * Return all product ids in wishlists for current user
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_current_user_all_wishlists_product_ids() {

		$products  = array();
		$wishlists = self::get_current_user_wishlists();
		foreach ( $wishlists as $wishlist ) {
			$wishlist_key         = $wishlist['key'] ?? '';
			$wishlist_product_ids = $wishlist['product_ids'] ?? array();
			foreach ( $wishlist_product_ids as $product_id => $product_data ) {

				$product_data['wishlist_key'] = $wishlist_key;
				$products[ $product_id ]      = $product_data;
			}
		}

		return $products;
	}

	/**
	 * Get the wishlist items.
	 *
	 * @param array  $limit
	 * @param string $wishlist_key
	 * @param int    $paged
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_wishlist_products( $limit = -1, $wishlist_key = null, $paged = 1 ) {

		if ( ! is_null( $wishlist_key ) && $wishlist_key ) {

			$products = self::get_wishlist_products_by_wishlist_key( $wishlist_key );
		} else {

			$products = self::get_current_user_all_wishlists_product_ids();
		}
		if ( ! empty( $products ) && is_array( $products ) ) {

			// check if is product
			foreach ( $products as $product_id => $product_data ) {
				$product = wc_get_product( $product_id );
				if ( ! is_a( $product, '\WC_Product' ) ) {
					unset( $products[ $product_id ] );
				}
			}

			if ( is_numeric( $limit ) && -1 !== $limit ) {
				$offset = $limit > 0 && $paged ? ( ( $paged - 1 ) * $limit ) : 0;
				return array_slice( $products, $offset, $limit, true );
			}

			return $products;
		}

		return array();
	}

	/**
	 * Get total wishlist items.
	 *
	 * @param array  $limit
	 * @param string $wishlist_key
	 * @param int    $paged
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_total_wishlist_products( $limit = -1, $wishlist_key = null, $paged = 1 ) {

		$products = self::get_wishlist_products( $limit, $wishlist_key, $paged );
		return count( $products );
	}

	/**
	 * Add to the wishlist.
	 *
	 * @since 1.2.0
	 */
	public static function add_to_wishlist_by_ajax() {
		check_ajax_referer( 'shoppress_nonce', 'nonce' );

		$static_wishlist_title                          = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
		$static_wishlist_remove_title                   = sp_get_module_settings( 'wishlist_general_settings', 'remove_label' );
		$multi_wishlist_status                          = sp_get_module_settings( 'wishlist_general_settings', 'enable_multi_wishlist', false );
		$enable_multi_wishlist_only_for_logged_in_users = sp_get_module_settings( 'wishlist_general_settings', 'enable_multi_wishlist_only_for_logged_in_users' );
		$wishlist_items                                 = self::get_wishlist_products();
		$wishlist_page_url                              = self::get_wishlist_page_url();
		$user_id                                        = get_current_user_id();

		$enable_wishlist_only_for_logged_in_users = sp_get_module_settings( 'wishlist_general_settings', 'enable_wishlist_only_for_logged_in_users', false );
		if ( $enable_wishlist_only_for_logged_in_users && ! is_user_logged_in() ) {

			ob_start();
				self::display_popup_login( true );
			$popup_html = ob_get_clean();
			wp_send_json_success(
				array(
					'message'        => __( 'You must login to site.', 'shop-press' ),
					'message_type'   => 'error',
					'wishlist_label' => $static_wishlist_title,
					'status'         => 'no',
					'button_url'     => get_permalink( wc_get_page_id( 'myaccount' ) ),
					'button_text'    => __( 'Login', 'shop-press' ),
					'wishlist_count' => self::get_total_wishlist_products(),
					'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
					'popup_html'     => $popup_html,
				),
				200
			);
		}

		$product_id   = sanitize_text_field( $_POST['product_id'] );
		$wishlist_key = sanitize_text_field( $_POST['wishlist_key'] ?? false );
		if ( ! $wishlist_key || ! $multi_wishlist_status ) {

			$wishlist     = self::get_current_user_default_wishlist();
			$wishlist_key = $wishlist['key'] ?? false;
		} elseif ( 'add_new' === $wishlist_key && $multi_wishlist_status ) {

			if ( ! $user_id && $enable_multi_wishlist_only_for_logged_in_users ) {

				ob_start();
				self::display_popup_login( true );
				$popup_html = ob_get_clean();
				wp_send_json_success(
					array(
						'message'        => __( 'You must login to site.', 'shop-press' ),
						'message_type'   => 'error',
						'wishlist_label' => $static_wishlist_title,
						'status'         => 'no',
						'button_url'     => get_permalink( wc_get_page_id( 'myaccount' ) ),
						'button_text'    => __( 'Login', 'shop-press' ),
						'wishlist_count' => self::get_total_wishlist_products(),
						'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
						'popup_html'     => $popup_html,
					),
					200
				);
			}

			$wishlist_title        = sanitize_text_field( $_POST['wishlist_title'] ?? false );
			$wishlist_share_status = 'public';
			$wishlist_atts         = array();
			if ( ! $user_id ) {
				$wishlist_atts['author_name']  = sanitize_text_field( $_POST['author_name'] ?? false );
				$wishlist_atts['author_email'] = sanitize_email( $_POST['author_email'] ?? false );
			} else {

				$wishlist_share_status = sanitize_text_field( $_POST['wishlist_share_status'] ?? 'private' );
			}

			if ( ! $wishlist_title ) {

				wp_send_json_success(
					array(
						'message'        => __( 'Please enter the form values correctly.', 'shop-press' ),
						'message_type'   => 'error',
						'wishlist_label' => $static_wishlist_title,
						'status'         => 'no',
						'button_url'     => get_permalink( wc_get_page_id( 'myaccount' ) ),
						'button_text'    => '',
						'wishlist_count' => self::get_total_wishlist_products(),
						'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
					),
					200
				);
			}

			$new_wishlist = self::create_wishlist(
				$wishlist_title,
				$user_id,
				'custom',
				$wishlist_share_status,
				$wishlist_atts
			);

			$wishlist_key = $new_wishlist['key'] ?? false;
		}

		if ( ! $wishlist_key ) {
			return;
		}

		$is_in_wishlist = isset( $wishlist_items[ $product_id ] );
		if ( ! $is_in_wishlist ) {
			$action = static::add_to_wishlist( $product_id, $wishlist_key );
			$result = 'added';
		} elseif ( $is_in_wishlist ) {
			$action = static::remove_from_wishlist( $product_id, $wishlist_key );
			$result = 'removed';
		}

		if ( is_wp_error( $action ) ) {
			wp_send_json_error(
				array(
					'message'      => $action->get_error_message(),
					'message_type' => 'error',
				),
				500
			);
		}

		$after_product_is_added_to_wishlist = sp_get_module_settings( 'wishlist_general_settings', 'after_product_is_added_to_wishlist' )['value'] ?? 'remove_wishlist';
		if ( 'view_wishlist' === $after_product_is_added_to_wishlist ) {

			$view_wishlist_label     = sp_get_module_settings( 'wishlist_general_settings', 'view_wishlist_label' );
			$view_wishlist_page_link = sprintf(
				'<a href="%s">%s</a>',
				$wishlist_page_url,
				$view_wishlist_label ? $view_wishlist_label : __( 'View Wishlist', 'shop-press' ),
			);

			$static_wishlist_remove_title = $view_wishlist_page_link;
			$static_wishlist_title        = $view_wishlist_page_link;
		}

		if ( 'added' === $result ) {

			wp_send_json_success(
				array(
					'message'        => __( 'Product added to wishlist', 'shop-press' ),
					'message_type'   => 'success',
					'wishlist_label' => $static_wishlist_remove_title,
					'status'         => 'yes',
					'button_url'     => $wishlist_page_url,
					'button_text'    => __( 'View Wishlist', 'shop-press' ),
					'wishlist_count' => self::get_total_wishlist_products(),
					'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
					'wishlist_key'   => $wishlist_key,
				),
				200
			);
		} elseif ( 'removed' === $result ) {

			wp_send_json_success(
				array(
					'message'        => __( 'Product removed from wishlist.', 'shop-press' ),
					'message_type'   => 'success',
					'wishlist_label' => $static_wishlist_title,
					'status'         => 'no',
					'button_url'     => $wishlist_page_url,
					'button_text'    => __( 'View Wishlist', 'shop-press' ),
					'wishlist_count' => self::get_total_wishlist_products(),
					'wishlist_menu'  => self::wishlist_menu( self::get_wishlist_products() ),
					'wishlist_key'   => '',
				),
				200
			);
		}

		wp_die();
	}

	/**
	 * Return wishlist bulk actions
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function get_bulk_actions() {

		$bulk_actions = array();

		/**
		 * @param array $bulk_actions
		 *
		 * @since 1.4.0
		 */
		return apply_filters( 'shoppress/wishlist_table_settings/bulk_actions', $bulk_actions );
	}

	/**
	 * Run action by ajax
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public static function run_action_by_ajax() {

		check_ajax_referer( 'shoppress_nonce', 'nonce' );
		$action      = $_POST['action2'] ?? false;
		$product_ids = $_POST['product_ids'] ?? array();

		if ( ! $action || empty( $product_ids ) ) {
			return;
		}
		$data    = array();
		$user_id = get_current_user_id();
		$success = false;
		switch ( $action ) {
			case 'move_to_another_wishlist':
				$move_to              = sanitize_text_field( $_POST['move_to'] ?? '' );
				$current_wishlist_key = sanitize_text_field( $_POST['current_wishlist_key'] ?? '' );
				if ( $move_to && self::user_can_edit_product_in_wishlist( $move_to, $user_id ) ) {

					foreach ( $product_ids as $product_id ) {
						self::remove_from_wishlist( $product_id, $current_wishlist_key );
						self::add_to_wishlist( $product_id, $move_to );
					}

					$success = true;
				}
				break;
			case 'remove_wishlist':
				$current_wishlist_key = sanitize_text_field( $_POST['current_wishlist_key'] ?? '' );
				if ( $current_wishlist_key && self::user_can_edit_product_in_wishlist( $current_wishlist_key, $user_id ) ) {

					foreach ( $product_ids as $product_id ) {
						self::remove_from_wishlist( $product_id, $current_wishlist_key );
					}

					$success = true;
				}
				break;
			case 'add_to_cart':
				$quantity          = 1;
				$passed_validation = count( $product_ids ) ? true : false;
				foreach ( $product_ids as $product_id ) {

					if ( $quantity ) {

						$passed_validation = $passed_validation && apply_filters(
							'woocommerce_add_to_cart_validation',
							true,
							$product_id,
							$quantity
						);
					}
				}

				foreach ( $product_ids as $product_id ) {

					$r = $passed_validation ? WC()->cart->add_to_cart( $product_id, $quantity ) : false;
					if ( $r && is_string( $r ) && $quantity ) {
						wc_add_to_cart_message( array( $product_id => $quantity ), true );
					}
				}

				$message_html = wc_print_notices( true );

				ob_start();
					woocommerce_mini_cart();
				$mini_cart = ob_get_clean();

				$data                 = array(
					'fragments' => apply_filters(
						'woocommerce_add_to_cart_fragments',
						array(
							'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
						)
					),
					'cart_hash' => WC()->cart->get_cart_hash(),
				);
				$data['message_html'] = $message_html;

				$success = true;
				break;
		}

		if ( $success ) {
			wp_send_json_success( $data );
		} else {
			wp_send_json_error( $data );
		}
	}

	/**
	 * Render wishlist page content.
	 *
	 * @param array $atts
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public static function render_wishlist_page( $atts = array() ) {

		ob_start();
		wp_enqueue_style( 'sp-pr-general' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-pr-general-rtl' );
		}

		wp_enqueue_script( 'sp-wishlist' );
		wp_enqueue_style( 'sp-my-wishlist' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-my-wishlist-rtl' );
		}

		do_action( 'shoppress/before_wishlist' );

		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
		if ( self::is_wishlist_builder() ) {

			$builder_id = sp_get_template_settings( 'wishlist', 'page_builder' );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $builder_id );
		} else {
			load_template( sp_get_template_path( 'wishlist/template' ) );
		}
		echo '</div>';

		do_action( 'shoppress/after_wishlist' );

		return ob_get_clean();
	}

	/**
	 * Get full template.
	 *
	 * @since 1.2.0
	 */
	public static function full_template( $template ) {

		if ( self::is_wishlist_page() ) {
			$template = sp_get_template_path( 'full-template' );
		}

		return $template;
	}

	/**
	 * Prepare list table data
	 *
	 * @param array  $post_data
	 * @param int    $post_id
	 * @param string $post_type
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function prepare_list_table_data( $post_data, $post_id, $post_type ) {

		if ( $post_type !== 'shoppress_wishlist' ) {
			return $post_data;
		}

		$product_ids                 = get_post_meta( $post_id, 'shoppress_wishlist_product_ids', true );
		$post_data['total_products'] = count( is_array( $product_ids ) ? $product_ids : array() );

		return $post_data;
	}

	/**
	 * Return wishlist link
	 *
	 * @param int    $wishlist_id
	 * @param string $wishlist_key
	 * @param int    $paged
	 *
	 * @since 1.2.1
	 *
	 * @return string
	 */
	public static function get_wishlist_link( $wishlist_id, $wishlist_key, $paged = 1 ) {

		$url                   = self::get_wishlist_page_url();
		$multi_wishlist_status = self::is_active_multi_wishlist_for_current_user();

		if ( $wishlist_key && $multi_wishlist_status ) {
			$url .= 'view/' . $wishlist_key . '/';
		}

		if ( $paged > 1 ) {
			$url .= 'page/' . $paged . '/';
		}

		return $url;
	}

	/**
	 * Display popup login
	 *
	 * @param boolean $open
	 *
	 * @since 1.2.1
	 *
	 * @return void
	 */
	public static function display_popup_login( $open = false ) {

		require sp_get_template_path( 'wishlist/login-popup' );
	}
}
