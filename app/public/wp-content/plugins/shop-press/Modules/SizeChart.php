<?php
/**
 * Size Chart.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class SizeChart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! self::is_size_chart() ) {
			return;
		}

		$chart_as        = sp_get_module_settings( 'size_chart', 'chart_as' )['value'] ?? '';
		$button_position = sp_get_module_settings( 'size_chart', 'button_position' )['value'] ?? '';

		add_action( 'init', array( __CLASS__, 'register_post_type' ), 9 );
		add_filter( 'shoppress/api/get_post', array( __CLASS__, 'add_meta_fields' ), 9, 3 );
		add_filter( 'shoppress/api/post/default_post_fields', array( __CLASS__, 'update_post_fields' ), 9, 2 );

		if ( 'tab' === $chart_as ) {
			add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'add_size_chart_tab' ) );
		} elseif ( 'button-popup' === $chart_as && ! empty( $button_position ) && false === sp_is_template_active( 'single' ) ) {

			add_action( $button_position, array( __CLASS__, 'display_size_chart_button' ) );
		}
		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_elementor_widget' ), 9 );
	}

	/**
	 * Check if Size Chart is enabled.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public static function is_size_chart() {

		return sp_get_module_settings( 'size_chart', 'status' );
	}

	/**
	 * Register post type.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function register_post_type() {

		register_post_type(
			'shoppress_size_chart',
			array(
				'labels'              => array(
					'name'          => __( 'Size Chart', 'shop-press' ),
					'singular_name' => __( 'Size Chart', 'shop-press' ),
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'supports'            => array( 'title', 'editor', 'elementor' ),
			)
		);
	}

	/**
	 * Return meta fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_meta_fields() {

		return array(
			'status',
			'title',
			'product_categories_operator',
			'product_categories',
			'products_operator',
			'products',
			'image',
			'content_type',
		);
	}

	/**
	 * Update the default post fields.
	 *
	 * @param array  $default_post_fields
	 * @param string $post_type
	 *
	 * @since 1.4.3
	 *
	 * @return array
	 */
	public static function update_post_fields( $default_post_fields, $post_type ) {

		if ( 'shoppress_size_chart' !== $post_type ) {
			return $default_post_fields;
		}

		$default_post_fields = array_diff( $default_post_fields, array( 'post_content' ) );

		return $default_post_fields;
	}

	/**
	 * Add meta fields.
	 *
	 * @param array  $post_data
	 * @param int    $post_id
	 * @param string $post_type
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function add_meta_fields( $post_data, $post_id, $post_type ) {

		if ( 'shoppress_size_chart' !== $post_type ) {
			return $post_data;
		}

		$post_data['meta_fields'] = array(
			'status'             => array(
				'title'     => __( 'Enable', 'shop-press' ),
				'name'      => 'status',
				'component' => 'switch',
			),
			'products'           => array(
				'title'     => __( 'Products', 'shop-press' ),
				'component' => 'group_fields',
				'full_row'  => true,
				'className' => array( 'sp-group-field-half' ),
				'fields'    => array(
					'products_operator' => array(
						'name'         => 'products_operator',
						'component'    => 'select',
						'is_clearable' => true,
						'default'      => array(
							'value' => 'all',
							'label' => __( 'All Products', 'shop-press' ),
						),
						'options'      => array(
							array(
								'value' => 'all',
								'label' => __( 'All Products', 'shop-press' ),
							),
							array(
								'label' => __( 'Include', 'shop-press' ),
								'value' => 'include',
							),
							array(
								'label' => __( 'Exclude', 'shop-press' ),
								'value' => 'exclude',
							),
						),
					),
					'products'          => array(
						'name'          => 'products',
						'placeholder'   => __( 'Search products...', 'shop-press' ),
						'is_searchable' => true,
						'is_multi'      => true,
						'component'     => 'select_product',
						'conditions'    => array(
							'parent' => 'meta',
							'terms'  => array(
								array(
									'name'     => 'products_operator',
									'operator' => '!==',
									'value'    => 'all',
								),
							),
						),
					),
				),
			),
			'product_categories' => array(
				'title'     => __( 'Categories', 'shop-press' ),
				'component' => 'group_fields',
				'full_row'  => true,
				'className' => array( 'sp-group-field-half' ),
				'fields'    => array(
					'product_categories_operator' => array(
						'name'         => 'product_categories_operator',
						'component'    => 'select',
						'is_clearable' => true,
						'default'      => array(
							'value' => 'all',
							'label' => __( 'All Categories', 'shop-press' ),
						),
						'options'      => array(
							array(
								'value' => 'all',
								'label' => __( 'All Categories', 'shop-press' ),
							),
							array(
								'label' => __( 'Include', 'shop-press' ),
								'value' => 'include',
							),
							array(
								'label' => __( 'Exclude', 'shop-press' ),
								'value' => 'exclude',
							),
						),
					),
					'product_categories'          => array(
						'name'          => 'product_categories',
						'placeholder'   => __( 'Search categories...', 'shop-press' ),
						'is_searchable' => true,
						'is_multi'      => true,
						'component'     => 'select_product_category',
						'conditions'    => array(
							'parent' => 'meta',
							'terms'  => array(
								array(
									'name'     => 'product_categories_operator',
									'operator' => '!==',
									'value'    => 'all',
								),
							),
						),
					),
				),
			),
			'content_divider'    => array(
				'title'     => __( 'Content', 'shop-press' ),
				'component' => 'divider',
			),
			'content_type_field' => array(
				'component' => 'group_fields',
				'full_row'  => true,
				'fields'    => array(
					'content_type' => array(
						'name'      => 'content_type',
						'component' => 'toggle_radio',
						'default'   => 'upload_image',
						'options'   => array(
							array(
								'label' => __( 'Image', 'shop-press' ),
								'value' => 'upload_image',
							),
							array(
								'label' => __( 'Page Builder', 'shop-press' ),
								'value' => 'page_builder',
							),
						),
					),
					'image'        => array(
						'title'      => __( 'Upload Image (Chart)', 'shop-press' ),
						'name'       => 'image',
						'component'  => 'image',
						'conditions' => array(
							'parent' => 'meta',
							'terms'  => array(
								array(
									'name'     => 'content_type',
									'operator' => '===',
									'value'    => 'upload_image',
								),
							),
						),
					),
					'page_builder' => array(
						'name'       => 'page_builder',
						'component'  => 'single_template',
						'conditions' => array(
							'parent' => 'meta',
							'terms'  => array(
								array(
									'name'     => 'content_type',
									'operator' => '===',
									'value'    => 'page_builder',
								),
							),
						),
					),
				),
			),
		);

		return $post_data;
	}

	/**
	 * Add chart size tab to single product.
	 *
	 * @param array $tabs
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function add_size_chart_tab( $tabs ) {

		if ( is_sp_quick_view_ajax() ) {
			return $tabs;
		}

		$product_id = get_the_ID();
		$size_chart = static::get_available_size_chart( $product_id );
		if ( empty( $size_chart ) ) {
			return $tabs;
		}

		$tab_priority                 = sp_get_module_settings( 'size_chart', 'tab_priority', 100 );
		$tabs['shoppress_size_chart'] = array(
			'title'    => __( 'Size Chart', 'shop-press' ),
			'priority' => $tab_priority,
			'callback' => array( __CLASS__, 'display_size_chart_tab_content' ),
		);

		return $tabs;
	}

	/**
	 * Display chart size.
	 *
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public static function display_size_chart( $product_id ) {

		$size_chart = static::get_available_size_chart( $product_id );
		if ( empty( $size_chart ) ) {
			return;
		}

		$image_url     = sp_get_image_url( $size_chart['image'] );
		$size_chart_id = $size_chart['id'];
		$content_type  = $size_chart['content_type'] ?? '';

		if ( empty( $content_type ) ) {
			return;
		}

		if ( 'page_builder' === $content_type ) {

			if ( did_action( 'elementor/loaded' ) && Plugin::$instance->documents->get( $size_chart_id )->is_built_with_elementor() ) {

				echo Plugin::instance()->frontend->get_builder_content_for_display( $size_chart_id, false );
			} else {

				$content = get_post_field( 'post_content', $size_chart_id );
				echo do_blocks( $content );
			}
		} elseif ( 'upload_image' === $content_type && ! empty( $image_url ) ) {

			return '<p><img src="' . esc_url_raw( $image_url ) . '"/></p>';
		}
	}


	/**
	 * Add chart size tab to single product.
	 *
	 * @since 1.2.0
	 */
	public static function display_size_chart_tab_content() {

		$product_id = get_the_ID();
		echo static::display_size_chart( $product_id );
	}

	/**
	 * Display size chart button.
	 *
	 * @param array $atts
	 *
	 * @since 1.2.0
	 */
	public static function display_size_chart_button( $atts = array() ) {

		if ( is_sp_quick_view_ajax() ) {
			return;
		}

		global $shoppress_sticky_add_to_cart;
		if ( $shoppress_sticky_add_to_cart ) {
			return;
		}
		$product_id = get_the_ID();

		$size_chart = static::get_available_size_chart( $product_id );
		if ( empty( $size_chart ) ) {
			return;
		}

		wp_enqueue_script( 'sp-size-chart' );
		wp_enqueue_style( 'sp-size-chart' );

		$label = $atts['label'] ?? __( 'Size Chart', 'shop-press' );
		$icon  = $atts['icon'] ?? '';
		?>
		<button class="sp-size-chart <?php echo $icon ? 'has-icon' : ''; ?>">
			<?php echo $icon ? wp_kses( $icon, sp_allowd_svg_tags() ) : ''; ?>
			<?php echo esc_html( $label ); ?>
		</button>
		<div class="size-chart-overlay sp-popup-overlay" style="display:none;">
			<div class="size-chart-content sp-popup-content">
				<div class="size-chart-close sp-close-popup"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg></div>
				<?php echo static::display_size_chart( $product_id ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Return size charts
	 *
	 * @since  1.2.0
	 *
	 * @return array
	 */
	public static function get_size_charts() {
		$args            = array(
			'post_type'      => 'shoppress_size_chart',
			'posts_per_page' => 200,
			'fields'         => 'ids',
		);
		$size_charts_ids = get_posts( $args );

		$size_charts = array();
		$meta_keys   = self::get_meta_fields();
		foreach ( $size_charts_ids as $size_chart_id ) {

			foreach ( $meta_keys as $meta_key ) {
				$size_charts[ $size_chart_id ][ $meta_key ] = get_post_meta( $size_chart_id, $meta_key, true );
			}
		}

		return $size_charts;
	}

	/**
	 * Return size charts
	 *
	 * @param int $product_id
	 *
	 * @since  1.2.0
	 *
	 * @return array
	 */
	public static function get_available_size_charts( $product_id = 0 ) {
		$_size_charts = self::get_size_charts();
		$size_charts  = array();
		if ( ! is_array( $_size_charts ) ) {
			return $size_charts;
		}

		$user_id = get_current_user_id();
		if ( $user_id ) {
			$user       = get_userdata( $user_id );
			$user_roles = $user->roles;
		}

		if ( $product_id ) {

			$product    = wc_get_product( $product_id );
			$parent_id  = $product->get_parent_id();
			$product_id = $parent_id ? $parent_id : $product_id;
		}

		foreach ( $_size_charts as $key => $size_chart ) {

			$status       = $size_chart['status'] ?? false;
			$image        = $size_chart['image'] ?? false;
			$content_type = $size_chart['content_type'] ?? false;

			if ( empty( $size_chart ) || ! $status && ( ! $image || ! $content_type ) ) {
				continue;
			}

			$product_categories_operator               = $size_chart['product_categories_operator']['value'] ?? 'all';
			$size_chart['product_categories_operator'] = $product_categories_operator;
			$size_chart['product_categories']          = isset( $size_chart['product_categories'] ) && is_array( $size_chart['product_categories'] ) ? array_column( $size_chart['product_categories'], 'value' ) : array();

			$products_operator               = $size_chart['products_operator']['value'] ?? 'all';
			$size_chart['products_operator'] = $products_operator;
			$size_chart['products']          = is_array( $size_chart['products'] ) ? array_column( $size_chart['products'], 'value' ) : array();
			$size_chart['content_type']      = $size_chart['content_type'] ?? '';
			$size_chart['id']                = $key;

			if ( $product_id ) {

				$product_category_ids         = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
				$product_categories_intersect = array_intersect( $product_category_ids, $size_chart['product_categories'] );

				if (
					! empty( $size_chart['product_categories'] )
					&&
					(
						( 'include' == $product_categories_operator && empty( $product_categories_intersect ) )
						||
						( 'exclude' == $product_categories_operator && ! empty( $product_categories_intersect ) )
					)
				) {
					continue;
				}

				if (
					! empty( $size_chart['products'] )
					&&
					(
						( 'include' == $products_operator && ! in_array( $product_id, $size_chart['products'] ) )
						||
						( 'exclude' == $products_operator && in_array( $product_id, $size_chart['products'] ) )
					)
				) {
					continue;
				}
			}

			$size_charts[ $key ] = $size_chart;
		}

		return $size_charts;
	}

	/**
	 * Return size chart
	 *
	 * @param int $product_id
	 *
	 * @since  1.2.0
	 *
	 * @return array|false
	 */
	public static function get_available_size_chart( $product_id = 0 ) {

		$size_charts = static::get_available_size_charts( $product_id );

		return is_array( $size_charts ) && count( $size_charts ) ? current( $size_charts ) : false;
	}

	/**
	 * Add Add elementor widgets.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function add_elementor_widget( $widgets ) {

		$widgets['single-size-chart'] = array(
			'editor_type' => 'single',
			'class_name'  => 'SizeChart',
			'is_pro'      => false,
			'path_key'    => 'single-product/size-chart',
		);

		return $widgets;
	}
}
