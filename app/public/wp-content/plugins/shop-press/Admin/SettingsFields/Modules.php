<?php
/**
 * Modules Fields.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\SettingsFields;

use ShopPress\Modules\Wishlist\Main;

defined( 'ABSPATH' ) || exit;

class Modules {
	/**
	 * Get variation swatches fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $variation_swatches_fields
	 */
	public static function variation_swatches() {
		$variation_swatches_fields = array(
			'heading'                               => array(
				'title'     => __( 'Variation Swatches', 'shop-press' ),
				'component' => 'heading',
			),
			'variation_swatches_covert_selects'     => array(
				'title'     => __( 'Convert dropdown to label swatches', 'shop-press' ),
				'name'      => 'variation_swatches_covert_selects',
				'default'   => true,
				'component' => 'switch',
			),
			'hide_higher_price'                     => array(
				'title'     => __( 'Hide higher price', 'shop-press' ),
				'name'      => 'hide_higher_price',
				'default'   => false,
				'component' => 'switch',
			),
			'clear_variation_by_reselect'           => array(
				'title'     => __( 'Clear on reselect', 'shop-press' ),
				'name'      => 'clear_variation_by_reselect',
				'default'   => true,
				'component' => 'switch',
			),

			'tooltip_divider'                       => array(
				'title'     => __( 'Tooltip', 'shop-press' ),
				'component' => 'divider',
			),
			'variation_swatches_tooltip'            => array(
				'title'     => __( 'Display Tooltip', 'shop-press' ),
				'name'      => 'variation_swatches_tooltip',
				'default'   => false,
				'component' => 'switch',
			),
			'image_as_tooltip'                      => array(
				'title'      => __( 'Swatch image as tooltip', 'shop-press' ),
				'name'       => 'image_as_tooltip',
				'default'    => false,
				'component'  => 'switch',
				'tooltip'    => array(
					'content' => __( 'Display swatch image as a tooltip for swatches with an image.', 'shop-press' ),
				),
				'conditions' => array(
					'name'   => 'variation_swatches',
					'parent' => 'modules',
					'terms'  => array(
						array(
							'name'     => 'variation_swatches_tooltip',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
			),
			'label_divider'                         => array(
				'title'     => __( 'Label', 'shop-press' ),
				'component' => 'divider',
			),
			'display_variation_label'               => array(
				'title'     => __( 'Display variation label', 'shop-press' ),
				'name'      => 'display_variation_label',
				'default'   => true,
				'component' => 'switch',
			),
			'display_selected_swatch_label'         => array(
				'title'      => __( 'Display selected swatch label', 'shop-press' ),
				'name'       => 'display_selected_swatch_label',
				'default'    => true,
				'component'  => 'switch',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'variation_swatches',
					'terms'  => array(
						array(
							'name'     => 'display_variation_label',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
				'tooltip'    => array(
					'content' => __( 'Display the chosen swatches next to "attribute label". ', 'shop-press' ),
				),
			),
			'shape_style_divider'                   => array(
				'title'     => __( 'Styles', 'shop-press' ),
				'component' => 'divider',
			),
			'shape_style'                           => array(
				'title'     => __( 'Shape Style', 'shop-press' ),
				'name'      => 'shape_style',
				'options'   => array(
					array(
						'label' => __( 'Rounded', 'shop-press' ),
						'value' => 'rounded',
					),
					array(
						'label' => __( 'Squared', 'shop-press' ),
						'value' => 'squared',
					),
				),
				'component' => 'select',
			),
			// 'hide_variations_for_out_of_stock_products'       => array(
			// 'title'     => __( 'Hide variations for out of stock products', 'shop-press' ),
			// 'name'      => 'hide_variations_for_out_of_stock_products',
			// 'default'   => false,
			// 'component' => 'switch',
			// ),
			'disabled_variation_style'              => array(
				'title'     => __( 'Disabled variation style', 'shop-press' ),
				'name'      => 'disabled_variation_style',
				'options'   => array(
					array(
						'label' => __( 'Hide', 'shop-press' ),
						'value' => 'hide',
					),
					array(
						'label' => __( 'Blur', 'shop-press' ),
						'value' => 'blur',
					),
					array(
						'label' => __( 'Blur with cross', 'shop-press' ),
						'value' => 'blur_with_cross',
					),
				),
				'default'   => array(
					'label' => __( 'Blur', 'shop-press' ),
					'value' => 'blur',
				),
				'component' => 'select',
			),
			'variation_image_gallery_divider'       => array(
				'title'     => __( 'Variation Image Gallery', 'shop-press' ),
				'component' => 'divider',
			),
			'variation_image_gallery'               => array(
				'title'     => __( 'Enable variation image gallery', 'shop-press' ),
				'name'      => 'variation_image_gallery',
				'default'   => false,
				'is_pro'    => true,
				'component' => 'switch',
				'tooltip'   => array(
					'content' => __( 'Assign a separate gallery for each variation by enabling this option.', 'shop-press' ),
				),
			),
			'variation_swatches_quick_shop_divider' => array(
				'title'     => __( 'Quick Shop', 'shop-press' ),
				'component' => 'divider',
			),
			'variation_swatches_quick_shop'         => array(
				'title'     => __( 'Enable quick shop', 'shop-press' ),
				'name'      => 'variation_swatches_quick_shop',
				'default'   => false,
				'is_pro'    => true,
				'component' => 'switch',
				'tooltip'   => array(
					'content' => __( 'Add product variations on the shop and archive page to the cart more quickly by enabling this option', 'shop-press' ),
				),
			),
		);

		return $variation_swatches_fields;
	}

	/**
	 * Get rename label fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $rename_label_fields
	 */
	public static function rename_label() {
		$rename_label_fields = array(
			'heading'                     => array(
				'title'     => __( 'Rename Label', 'shop-press' ),
				'component' => 'heading',
			),
			'shop_page'                   => array(
				'title'     => __( 'Shop Page', 'shop-press' ),
				'component' => 'divider',
			),
			'shop_cart_text'              => array(
				'title'       => __( 'Shop page add to cart text', 'shop-press' ),
				'name'        => 'shop_cart_text',
				'default'     => '',
				'placeholder' => __( 'Add to cart', 'shop-press' ),
				'component'   => 'text',
			),
			'single_page'                 => array(
				'title'     => __( 'Single Page', 'shop-press' ),
				'component' => 'divider',
			),
			'single_cart_text'            => array(
				'title'       => __( 'Single page add to cart text', 'shop-press' ),
				'name'        => 'single_cart_text',
				'default'     => '',
				'placeholder' => __( 'Add to cart', 'shop-press' ),
				'component'   => 'text',
			),
			'single_description_tab_text' => array(
				'title'       => __( 'Single page description tab', 'shop-press' ),
				'name'        => 'single_description_tab_text',
				'default'     => '',
				'placeholder' => __( 'Description', 'shop-press' ),
				'component'   => 'text',
			),
			'single_information_tab_text' => array(
				'title'       => __( 'Single page information tab', 'shop-press' ),
				'name'        => 'single_information_tab_text',
				'default'     => '',
				'placeholder' => __( 'Information', 'shop-press' ),
				'component'   => 'text',
			),
			'single_reviews_tab_text'     => array(
				'title'       => __( 'Single page reviews tab', 'shop-press' ),
				'name'        => 'single_reviews_tab_text',
				'default'     => '',
				'placeholder' => __( 'Reviews', 'shop-press' ),
				'component'   => 'text',
			),
			'checkout_page'               => array(
				'title'     => __( 'Checkout Page', 'shop-press' ),
				'component' => 'divider',
			),
			'checkout_order_button_text'  => array(
				'title'       => __( 'Checkout order button text', 'shop-press' ),
				'name'        => 'checkout_order_button_text',
				'default'     => '',
				'placeholder' => __( 'Place order', 'shop-press' ),
				'component'   => 'text',
			),
		);

		return $rename_label_fields;
	}

	/**
	 * Get backorder fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $backorder_fields
	 */
	public static function backorder() {
		$backorder_fields = array(
			'heading'         => array(
				'title'     => __( 'Backorder', 'shop-press' ),
				'component' => 'heading',
			),
			'backorder_limit' => array(
				'title'     => __( 'Purchase Limit', 'shop-press' ),
				'name'      => 'backorder_limit',
				'default'   => '',
				'component' => 'number',
			),
			'backorder_date'  => array(
				'title'     => __( 'Date', 'shop-press' ),
				'name'      => 'backorder_date',
				'default'   => '',
				'component' => 'date',
			),
			'backorder_msg'   => array(
				'title'       => __( 'Message', 'shop-press' ),
				'name'        => 'backorder_msg',
				'default'     => '',
				'rows'        => 4,
				'default'     => __( 'Will be available on {date}', 'shop-press' ),
				'placeholder' => __( 'Will be available on {date}', 'shop-press' ),
				'component'   => 'textarea',
				'tooltip'     => array(
					'content' => __( 'Enter the message that you would like to display to your users.</br>Date placeholder: {date}', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-backorder/',
				),
			),
		);

		return $backorder_fields;
	}

	/**
	 * Get size chart fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $fields
	 */
	public static function size_chart() {
		$positions = array(
			array(
				'value' => 'woocommerce_product_meta_start',
				'label' => __( 'Product Meta (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_product_meta_end',
				'label' => __( 'Product Meta (After)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_single_product_summary',
				'label' => __( 'Product Summary (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_after_single_product_summary',
				'label' => __( 'Product Summary (After)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_before_add_to_cart_form',
				'label' => __( 'Add to Cart (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_after_add_to_cart_form',
				'label' => __( 'Add to Cart (After)', 'shop-press' ),
			),
		);

		$fields = array(
			array(
				'title'       => __( 'Size Chart', 'shop-press' ),
				'description' => __( 'The Size Chart module allows you to offer products with different size charts, so you can make as many of them as you want.', 'shop-press' ),
				'component'   => 'heading',
			),
			'chart_as'            => array(
				'title'     => __( 'Display as', 'shop-press' ),
				'component' => 'select',
				'name'      => 'chart_as',
				'default'   => array(
					'value' => 'button-popup',
					'label' => __( 'Button - Popup', 'shop-press' ),
				),
				'options'   => array(
					array(
						'value' => 'button-popup',
						'label' => __( 'Button - Popup', 'shop-press' ),
					),
					array(
						'value' => 'tab',
						'label' => __( 'Tab', 'shop-press' ),
					),
				),
			),
			'button_position'     => array(
				'title'        => __( 'Button Position', 'shop-press' ),
				'component'    => 'select',
				'name'         => 'button_position',
				'default'      => array(
					'value' => 'woocommerce_before_add_to_cart_form',
					'label' => __( 'Add to Cart (Before)', 'shop-press' ),
				),
				'options'      => $positions,
				'is_clearable' => true,
				'conditions'   => array(
					'name'   => 'size_chart',
					'parent' => 'modules',
					'terms'  => array(
						array(
							'name'     => 'chart_as',
							'operator' => '==',
							'value'    => 'button-popup',
						),
					),
				),
			),
			'tab_priority'        => array(
				'title'      => __( 'Tab Priority', 'shop-press' ),
				'name'       => 'tab_priority',
				'default'    => 100,
				'component'  => 'number',
				'conditions' => array(
					'name'   => 'size_chart',
					'parent' => 'modules',
					'terms'  => array(
						array(
							'name'     => 'chart_as',
							'operator' => '==',
							'value'    => 'tab',
						),
					),
				),
			),
			'size_charts_heading' => array(
				'title'     => __( 'Size Charts', 'shop-press' ),
				'component' => 'divider',
			),
			'size_charts'         => array(
				'component'   => 'dynamic_items_list',
				'post_type'   => 'shoppress_size_chart',
				'edit'        => true,
				'can_add_new' => true,
			),
		);

		if ( ! sp_is_module_active( 'size_chart' ) ) {

			$fields = Utils::add_notice_to_fields(
				__( 'Save the settings before adding a new item.', 'shop-press' ),
				'warning',
				$fields
			);
		}

		return $fields;
	}

	/**
	 * Get flash sale countdown fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $flash_sale_countdown_fields
	 */
	public static function flash_sale_countdown() {
		$positions = array(
			array(
				'value' => 'woocommerce_product_meta_start',
				'label' => __( 'Product Meta (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_product_meta_end',
				'label' => __( 'Product Meta (After)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_single_product_summary',
				'label' => __( 'Product Summary (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_after_single_product_summary',
				'label' => __( 'Product Summary (After)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_before_add_to_cart_form',
				'label' => __( 'Add to Cart (Before)', 'shop-press' ),
			),
			array(
				'value' => 'woocommerce_after_add_to_cart_form',
				'label' => __( 'Add to Cart (After)', 'shop-press' ),
			),
		);

		$flash_sale_countdown_fields = array(
			'heading'             => array(
				'title'     => __( 'Flash Sale Countdown', 'shop-press' ),
				'component' => 'heading',
			),
			'timer_title'         => array(
				'title'       => __( 'Countdown', 'shop-press' ),
				'name'        => 'timer_title',
				'default'     => __( 'Countdown', 'shop-press' ),
				'placeholder' => __( 'Countdown', 'shop-press' ),
				'component'   => 'text',
			),
			'override_sale_price' => array(
				'title'     => __( 'Override sale price', 'shop-press' ),
				'name'      => 'override_sale_price',
				'default'   => false,
				'component' => 'switch',
			),
			'show_product_page'   => array(
				'title'     => __( 'Show on product page', 'shop-press' ),
				'name'      => 'show_product_page',
				'default'   => false,
				'component' => 'switch',
			),
			'position'            => array(
				'title'        => __( 'Position', 'shop-press' ),
				'name'         => 'position',
				'default'      => array(
					'value' => 'woocommerce_product_meta_start',
					'label' => __( 'Product Meta (Before)', 'shop-press' ),
				),
				'options'      => $positions,
				'is_clearable' => true,
				'component'    => 'select',
				'conditions'   => array(
					'parent' => 'modules',
					'name'   => 'flash_sale_countdown',
					'terms'  => array(
						array(
							'name'     => 'show_product_page',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'sale_events_heading' => array(
				'title'     => __( 'Sale Events', 'shop-press' ),
				'component' => 'divider',
			),
			'sale_events'         => array(
				'name'      => 'sale_events',
				'component' => 'repeater',
				'switcher'  => 'event_enable',
				'heading'   => 'event_name',
				'fields'    => array(
					'event_name'                      => array(
						'title'       => __( 'Event Name', 'shop-press' ),
						'name'        => 'event_name',
						'placeholder' => __( 'Event Name', 'shop-press' ),
						'component'   => 'text',
					),
					'event_valid_from'                => array(
						'title'     => __( 'Valid From', 'shop-press' ),
						'name'      => 'event_valid_from',
						'component' => 'date',
					),
					'event_valid_to'                  => array(
						'title'     => __( 'Valid to', 'shop-press' ),
						'name'      => 'event_valid_to',
						'component' => 'date',
					),
					'event_product_categories'        => array(
						'title'     => __( 'Categories', 'shop-press' ),
						'component' => 'group_fields',
						'full_row'  => true,
						'className' => array( 'sp-group-field-half' ),
						'fields'    => array(
							'event_product_categories_operator' => array(
								'name'         => 'event_product_categories_operator',
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
							'event_product_categories' => array(
								'name'          => 'event_product_categories',
								'placeholder'   => __( 'Search categories...', 'shop-press' ),
								'is_searchable' => true,
								'is_multi'      => true,
								'component'     => 'select_product_category',
								'conditions'    => array(
									'parent'        => 'modules',
									'name'          => 'flash_sale_countdown',
									'repeater_name' => 'sale_events',
									'terms'         => array(
										array(
											'name'     => 'event_product_categories_operator',
											'operator' => '!==',
											'value'    => 'all',
										),
									),
								),
							),

						),
					),
					'event_products'                  => array(
						'title'     => __( 'Products', 'shop-press' ),
						'component' => 'group_fields',
						'full_row'  => true,
						'className' => array( 'sp-group-field-half' ),
						'fields'    => array(
							'event_products_operator' => array(
								'name'         => 'event_products_operator',
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
							'event_products'          => array(
								'name'          => 'event_products',
								'placeholder'   => __( 'Search products...', 'shop-press' ),
								'is_searchable' => true,
								'is_multi'      => true,
								'component'     => 'select_product',
								'conditions'    => array(
									'parent'        => 'modules',
									'name'          => 'flash_sale_countdown',
									'repeater_name' => 'sale_events',
									'terms'         => array(
										array(
											'name'     => 'event_products_operator',
											'operator' => '!==',
											'value'    => 'all',
										),
									),
								),
							),
						),
					),
					'event_discount_type'             => array(
						'title'     => __( 'Discount Type', 'shop-press' ),
						'name'      => 'event_discount_type',
						'options'   => array(
							array(
								'value' => 'fixed_discount',
								'label' => __( 'Fixed Discount', 'shop-press' ),
							),
							array(
								'value' => 'percentage_discount',
								'label' => __( 'Percentage Discount', 'shop-press' ),
							),
							array(
								'value' => 'fixed_price',
								'label' => __( 'Fixed Price', 'shop-press' ),
							),
						),
						'component' => 'select',
					),
					'event_discount_value'            => array(
						'title'     => __( 'Discount Value', 'shop-press' ),
						'name'      => 'event_discount_value',
						'component' => 'number',
					),
					'event_apply_discount_registered' => array(
						'title'     => __( 'Apply only registered customers', 'shop-press' ),
						'name'      => 'event_apply_discount_registered',
						'component' => 'switch',
					),
					'event_apply_user_roles'          => array(
						'title'         => __( 'Apply only For user roles', 'shop-press' ),
						'name'          => 'event_apply_user_roles',
						'placeholder'   => __( 'Search roles...', 'shop-press' ),
						'is_searchable' => true,
						'is_multi'      => true,
						'component'     => 'select',
						'options'       => Utils::get_option_roles(),
					),
				),
			),
		);

		return $flash_sale_countdown_fields;
	}

	/**
	 * Get menu cart fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function menu_cart() {
		$fields = array(
			'heading'           => array(
				'title'     => __( 'Menu Cart', 'shop-press' ),
				'component' => 'heading',
			),
			'cart_menu_id'      => array(
				'title'         => __( 'Select a menu', 'shop-press' ),
				'name'          => 'cart_menu_id',
				'placeholder'   => __( 'Search menu...', 'shop-press' ),
				'is_searchable' => true,
				'is_clearable'  => true,
				'component'     => 'select_menu',
			),
			'menu_cart_display' => array(
				'title'     => __( 'Display', 'shop-press' ),
				'component' => 'divider',
			),
			'display_cart_as'   => array(
				'title'     => __( 'Display as', 'shop-press' ),
				'name'      => 'display_cart_as',
				'component' => 'select',
				'default'   => array(
					'label' => __( 'Dropdown', 'shop-press' ),
					'value' => 'dropdown',
				),
				'options'   => array(
					array(
						'label' => __( 'Dropdown', 'shop-press' ),
						'value' => 'dropdown',
					),
					array(
						'label' => __( 'Drawer', 'shop-press' ),
						'value' => 'drawer',
					),
				),
			),
			'drawer_position'   => array(
				'title'      => __( 'Drawer position', 'shop-press' ),
				'name'       => 'drawer_position',
				'component'  => 'select',
				'default'    => array(
					'label' => __( 'left', 'shop-press' ),
					'value' => 'left',
				),
				'options'    => array(
					array(
						'label' => __( 'Left', 'shop-press' ),
						'value' => 'left',
					),
					array(
						'label' => __( 'Right', 'shop-press' ),
						'value' => 'right',
					),
				),
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'menu_cart',
					'terms'  => array(
						array(
							'name'     => 'display_cart_as',
							'operator' => '===',
							'value'    => 'drawer',
						),
					),
				),
			),
			'menu_cart_icon'    => array(
				'title'     => __( 'Icon', 'shop-press' ),
				'component' => 'divider',
			),
			'icon_type_field'   => array(
				'component' => 'group_fields',
				'full_row'  => true,
				'fields'    => array(
					'icon_type'   => array(
						'name'      => 'icon_type',
						'component' => 'toggle_radio',
						'default'   => 'icons_pack',
						'options'   => array(
							array(
								'label' => __( 'Select Icon', 'shop-press' ),
								'value' => 'icons_pack',
							),
							array(
								'label' => __( 'Upload Icon', 'shop-press' ),
								'value' => 'custom_icon',
							),
						),
					),
					'icons_pack'  => array(
						'title'      => __( 'Select cart icon', 'shop-press' ),
						'name'       => 'icons_pack',
						'component'  => 'icons_pack',
						'icons_pack' => 'cart',
						'default'    => 'cart-1',
						'conditions' => array(
							'parent' => 'modules',
							'name'   => 'menu_cart',
							'terms'  => array(
								array(
									'name'     => 'icon_type',
									'operator' => '===',
									'value'    => 'icons_pack',
								),
							),
						),
					),
					'custom_icon' => array(
						'title'      => __( 'Upload Cart Icon', 'shop-press' ),
						'name'       => 'custom_icon',
						'component'  => 'image',
						'className'  => array( 'sp-icon-upload' ),
						'default'    => '',
						'conditions' => array(
							'parent' => 'modules',
							'name'   => 'menu_cart',
							'terms'  => array(
								array(
									'name'     => 'icon_type',
									'operator' => '===',
									'value'    => 'custom_icon',
								),
							),
						),
					),
				),
			),
		);

		return $fields;
	}

	/**
	 * Return sticky add to cart fields.
	 *
	 * @since 1.2.1
	 *
	 * @return array
	 */
	public static function sticky_add_to_cart() {

		$fields = array(
			'heading'        => array(
				'title'     => __( 'Sticky Add to Cart', 'shop-press' ),
				'component' => 'heading',
			),
			'hide_on_mobile' => array(
				'title'     => __( 'Hide on mobile', 'shop-press' ),
				'name'      => 'hide_on_mobile',
				'default'   => false,
				'component' => 'switch',
			),
		);

		return $fields;
	}

	/**
	 * Get wishlist fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $wishlist_fields
	 */
	public static function wishlist() {
		$wishlist_fields = array(
			'heading'                        => array(
				'title'     => __( 'Wishlist', 'shop-press' ),
				'component' => 'heading',
			),
			'wishlist_general_settings'      => array(
				'title'     => __( 'General Settings', 'shop-press' ),
				'component' => 'link',
				'link'      => 'wishlist_general_settings',
				'icon'      => 'general-settings',
				'parent'    => 'Modules',
			),
			'wishlist_table_settings'        => array(
				'title'     => __( 'My Wishlist Settings', 'shop-press' ),
				'component' => 'link',
				'link'      => 'wishlist_table_settings',
				'icon'      => 'wishlist-table',
				'parent'    => 'Modules',
			),
			'wishlist_all_wishlist_settings' => array(
				'title'     => __( 'All Wishlists', 'shop-press' ),
				'component' => 'link',
				'link'      => 'wishlist_all_wishlist_settings',
				'icon'      => 'wishlist-all-wishlist',
				'parent'    => 'Modules',
			),
		);

		return $wishlist_fields;
	}

	/**
	 * Get wishlist general settings fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $wishlist_fields
	 */
	public static function wishlist_general_settings() {
		$social_share_options = array();
		$share_url_patterns   = sp_get_social_share_links();
		foreach ( $share_url_patterns as $social_id => $social ) {

			$social_share_options[] = array(
				'value' => $social_id,
				'label' => $social['title'],
			);
		}

		$wishlist_fields = array(
			'heading'                                  => array(
				'title'     => __( 'Wishlist General Settings', 'shop-press' ),
				'component' => 'heading',
			),
			'wishlist_page'                            => array(
				'title'         => __( 'Wishlist Page', 'shop-press' ),
				'name'          => 'wishlist_page',
				'default'       => '',
				'placeholder'   => __( 'Search pages...', 'shop-press' ),
				'is_searchable' => true,
				'is_multi'      => false,
				'is_clearable'  => true,
				'component'     => 'select_page',
				'tooltip'       => array(
					'content' => __( 'use [shoppress-wishlist-page] shortcode in page.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
			),
			'disable_notices'                          => array(
				'title'     => __( 'Disable Notices', 'shop-press' ),
				'component' => 'select',
				'name'      => 'disable_notices',
				'is_multi'  => true,
				'default'   => array(),
				'options'   => array(
					array(
						'value' => 'add_to_wishlist',
						'label' => __( 'Add to Wishlist', 'shop-press' ),
					),
					array(
						'value' => 'remove_from_wishlist',
						'label' => __( 'Remove from Wishlist', 'shop-press' ),
					),
				),
			),
			'enable_wishlist_only_for_logged_in_users' => array(
				'title'     => __( 'Enable wishlist only for logged-in users', 'shop-press' ),
				'name'      => 'enable_wishlist_only_for_logged_in_users',
				'default'   => true,
				'component' => 'switch',
			),
			'add_menu_to_wc_myaccount'                 => array(
				'title'     => __( 'Add wishlist to my account page menu', 'shop-press' ),
				'name'      => 'add_menu_to_wc_myaccount',
				'default'   => false,
				'tooltip'   => array(
					'content' => __( 'Users can access their Wishlist on the My Account page by activating this option.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
				'component' => 'switch',
			),
			'delete_guest_wishlists'                   => array(
				'title'      => __( 'Delete the guest wishlists after specific days', 'shop-press' ),
				'name'       => 'delete_guest_wishlists',
				'component'  => 'switch',
				'default'    => false,
				'is_pro'     => true,
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'enable_wishlist_only_for_logged_in_users',
							'operator' => '!==',
							'value'    => true,
						),
					),
				),
			),
			'wishlist_menu'                            => array(
				'title'     => __( 'Wishlist Menu', 'shop-press' ),
				'component' => 'divider',
			),
			'wishlist_menu_module'                     => array(
				'title'     => __( 'Add wishlist to the menu', 'shop-press' ),
				'name'      => 'wishlist_menu_module',
				'default'   => false,
				'tooltip'   => array(
					'content' => __( 'Activate this option to add the Wishlist item to your site menu.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
				'component' => 'switch',
			),
			'select_wishlist_menu'                     => array(
				'title'         => __( 'Select a Menu', 'shop-press' ),
				'name'          => 'select_wishlist_menu',
				'default'       => '',
				'placeholder'   => __( 'Search menu...', 'shop-press' ),
				'is_searchable' => true,
				'is_clearable'  => true,
				'component'     => 'select_menu',
				'conditions'    => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'wishlist_menu_module',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'display_wishlist_menu_as'                 => array(
				'title'      => __( 'Display as', 'shop-press' ),
				'name'       => 'display_wishlist_menu_as',
				'component'  => 'select',
				'default'    => array(
					'label' => __( 'Dropdown', 'shop-press' ),
					'value' => 'dropdown',
				),
				'options'    => array(
					array(
						'label' => __( 'Dropdown', 'shop-press' ),
						'value' => 'dropdown',
					),
					array(
						'label' => __( 'Drawer', 'shop-press' ),
						'value' => 'drawer',
					),
				),
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'wishlist_menu_module',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'drawer_position'                          => array(
				'title'      => __( 'Drawer position', 'shop-press' ),
				'name'       => 'drawer_position',
				'component'  => 'select',
				'default'    => array(
					'label' => __( 'left', 'shop-press' ),
					'value' => 'left',
				),
				'options'    => array(
					array(
						'label' => __( 'Left', 'shop-press' ),
						'value' => 'left',
					),
					array(
						'label' => __( 'Right', 'shop-press' ),
						'value' => 'right',
					),
				),
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'display_wishlist_menu_as',
							'operator' => '===',
							'value'    => 'drawer',
						),
						array(
							'name'     => 'wishlist_menu_module',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'icon_type_field'                          => array(
				'component'  => 'group_fields',
				'full_row'   => true,
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'wishlist_menu_module',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
				'fields'     => array(
					'icon_type'   => array(
						'name'      => 'icon_type',
						'component' => 'toggle_radio',
						'default'   => 'icons_pack',
						'options'   => array(
							array(
								'label' => __( 'Select Icon', 'shop-press' ),
								'value' => 'icons_pack',
							),
							array(
								'label' => __( 'Upload Icon', 'shop-press' ),
								'value' => 'custom_icon',
							),
						),
					),
					'icons_pack'  => array(
						'title'      => __( 'Select Wishlist Icon', 'shop-press' ),
						'name'       => 'icons_pack',
						'component'  => 'icons_pack',
						'icons_pack' => 'wishlist',
						'default'    => 'wishlist-1',
						'conditions' => array(
							'parent' => 'modules',
							'name'   => 'wishlist_general_settings',
							'terms'  => array(
								array(
									'name'     => 'icon_type',
									'operator' => '===',
									'value'    => 'icons_pack',
								),
							),
						),
					),
					'custom_icon' => array(
						'title'      => __( 'Upload Wishlist Icon', 'shop-press' ),
						'name'       => 'custom_icon',
						'component'  => 'image',
						'className'  => array( 'sp-icon-upload' ),
						'default'    => '',
						'conditions' => array(
							'parent' => 'modules',
							'name'   => 'wishlist_general_settings',
							'terms'  => array(
								array(
									'name'     => 'icon_type',
									'operator' => '===',
									'value'    => 'custom_icon',
								),
							),
						),
					),
				),
			),
			'wishlist_labels'                          => array(
				'title'     => __( 'Wishlist Labels', 'shop-press' ),
				'component' => 'divider',
			),
			'add_label'                                => array(
				'title'       => __( 'Add Label', 'shop-press' ),
				'name'        => 'add_label',
				'default'     => __( 'Add to Wishlist', 'shop-press' ),
				'placeholder' => __( 'Add to Wishlist', 'shop-press' ),
				'component'   => 'text',
			),
			'remove_label'                             => array(
				'title'       => __( 'Remove Label', 'shop-press' ),
				'name'        => 'remove_label',
				'default'     => __( 'Remove', 'shop-press' ),
				'placeholder' => __( 'Remove', 'shop-press' ),
				'component'   => 'text',
			),
			'after_product_is_added_to_wishlist'       => array(
				'title'     => __( 'After the product is added to Wishlist', 'shop-press' ),
				'name'      => 'after_product_is_added_to_wishlist',
				'default'   => array(
					'value' => 'remove_wishlist',
					'label' => __( 'Show "Remove from Wishlist" link', 'shop-press' ),
				),
				'component' => 'select',
				'options'   => array(
					array(
						'value' => 'view_wishlist',
						'label' => __( 'Show "View Wishlist" link', 'shop-press' ),
					),
					array(
						'value' => 'remove_wishlist',
						'label' => __( 'Show "Remove from Wishlist" link', 'shop-press' ),
					),
				),
			),
			'view_wishlist_label'                      => array(
				'title'      => __( 'View Wishlist Label', 'shop-press' ),
				'name'       => 'view_wishlist_label',
				'default'    => '',
				'component'  => 'text',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'after_product_is_added_to_wishlist',
							'operator' => '===',
							'value'    => 'view_wishlist',
						),
					),
				),
			),
			'wishlist_locations'                       => array(
				'title'     => __( 'Wishlist Button Location', 'shop-press' ),
				'component' => 'divider',
			),
			'location_product_page'                    => array(
				'title'        => __( 'Product Single Page', 'shop-press' ),
				'name'         => 'location_product_page',
				'default'      => array(
					'value' => 'woocommerce_before_add_to_cart_form',
					'label' => __( 'Before add to cart', 'shop-press' ),
				),
				'options'      => array(
					array(
						'value' => 'woocommerce_after_add_to_cart_form',
						'label' => __( 'After add to cart', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_before_add_to_cart_form',
						'label' => __( 'Before add to cart', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_product_meta_start',
						'label' => __( 'Before product metadata', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_product_meta_end',
						'label' => __( 'After product metadata', 'shop-press' ),
					),
				),
				'component'    => 'select',
				'is_clearable' => true,
				'tooltip'      => array(
					'content' => __( 'Specify the location of the Wishlist button on product page.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
			),
			'location_products_loop'                   => array(
				'title'        => __( 'Products Loop', 'shop-press' ),
				'name'         => 'location_products_loop',
				'default'      => array(
					'value' => 'woocommerce_after_shop_loop_item',
					'label' => __( 'Before add to cart', 'shop-press' ),
				),
				'options'      => array(
					array(
						'value' => 'woocommerce_before_shop_loop_item',
						'label' => __( 'Before thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_after_shop_loop_item',
						'label' => __( 'Before add to cart', 'shop-press' ),
					),
					// array(
					// 'value' => 'woocommerce_overlay_thumbnail',
					// 'label' => __( 'Overlay on the thumbnail', 'shop-press' ),
					// ),
				),
				'is_clearable' => true,
				'component'    => 'select',
				'tooltip'      => array(
					'content' => __( 'Specify the location of the Wishlist button in product loops.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
			),
			'delete_guest_wishlists_after_x_days'      => array(
				'title'      => __( 'Delete the guest wishlists after x days', 'shop-press' ),
				'name'       => 'delete_guest_wishlists_after_x_days',
				'component'  => 'number',
				'default'    => false,
				'is_pro'     => true,
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'enable_wishlist_only_for_logged_in_users',
							'operator' => '!==',
							'value'    => true,
						),
						array(
							'name'     => 'delete_guest_wishlists',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'multi_wishlist_divider'                   => array(
				'component' => 'divider',
				'title'     => __( 'Multi Wishlist', 'shop-press' ),
				'is_pro'    => true,
			),
			'enable_multi_wishlist'                    => array(
				'title'     => __( 'Enable multi wishlist', 'shop-press' ),
				'name'      => 'enable_multi_wishlist',
				'component' => 'switch',
				'default'   => false,
				'is_pro'    => true,
			),
			'enable_multi_wishlist_only_for_logged_in_users' => array(
				'title'      => __( 'Enable only for logged-in users', 'shop-press' ),
				'name'       => 'enable_multi_wishlist_only_for_logged_in_users',
				'component'  => 'switch',
				'default'    => true,
				'is_pro'     => true,
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'enable_multi_wishlist',
							'operator' => '===',
							'value'    => true,
						),
						array(
							'name'     => 'enable_wishlist_only_for_logged_in_users',
							'operator' => '===',
							'value'    => false,
						),
					),
				),
			),
			'when_clicking_on_add_to_wishlist'         => array(
				'title'      => __( 'When clicking on add to Wishlist', 'shop-press' ),
				'name'       => 'when_clicking_on_add_to_wishlist',
				'component'  => 'select',
				'default'    => array(
					'value' => 'automatically_add_to_the_default_list',
					'label' => __( 'Automatically Add to The Default List', 'shop-press' ),
				),
				'is_pro'     => true,
				'options'    => array(
					array(
						'value' => 'automatically_add_to_the_default_list',
						'label' => __( 'Automatically Add to The Default List', 'shop-press' ),
					),
					array(
						'value' => 'show_modal',
						'label' => __( 'Show a Modal Window to Allow Users to Choose a Wishlist', 'shop-press' ),
					),
				),
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'enable_multi_wishlist',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'social_share_divider'                     => array(
				'component' => 'divider',
				'title'     => __( 'Social', 'shop-press' ),
			),
			'enable_show_social_share'                 => array(
				'title'     => __( 'Enable social share', 'shop-press' ),
				'name'      => 'enable_show_social_share',
				'component' => 'switch',
				'default'   => false,
				'is_pro'    => true,
			),
			'social_media'                             => array(
				'title'        => __( 'Social Media', 'shop-press' ),
				'name'         => 'social_media',
				'default'      => $social_share_options,
				'options'      => $social_share_options,
				'component'    => 'select',
				'is_multi'     => true,
				'is_clearable' => true,
				'is_pro'       => true,
				'conditions'   => array(
					'parent' => 'modules',
					'name'   => 'wishlist_general_settings',
					'terms'  => array(
						array(
							'name'     => 'enable_show_social_share',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'help_box'                                 => array(
				'title'     => 'How to use Wishlist',
				'component' => 'help_box',
				'doc_url'   => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				'doc_label' => __( 'Full Documentation', 'shop-press' ),
				'notes'     => array(
					'Create a WordPress page with the preferred title.',
					'Insert the [shoppress-wishlist-page] shortcode within.',
					'Choose the newly made page in the "Wishlist Page" setting.',
				),
			),
		);

		return $wishlist_fields;
	}

	/**
	 * Get wishlist table settings fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $wishlist_fields
	 */
	public static function wishlist_table_settings() {

		$table_conditions = array(
			'parent' => 'modules',
			'name'   => 'wishlist_table_settings',
			'terms'  => array(
				array(
					'name'     => 'my_wishlist_view_type',
					'operator' => '===',
					'value'    => 'table',
				),
			),
		);

		$bulk_actions = Main::get_bulk_actions();
		$fields       = array(
			'heading'                     => array(
				'title'     => __( 'My Wishlist Settings', 'shop-press' ),
				'component' => 'heading',
			),
			'my_wishlist_view_type'       => array(
				'title'     => __( 'My Wishlist view type', 'shop-press' ),
				'component' => 'select',
				'name'      => 'my_wishlist_view_type',
				'options'   => array(
					array(
						'value' => 'table',
						'label' => __( 'Table', 'shop-press' ),
					),
					array(
						'value' => 'grid',
						'label' => __( 'Grid', 'shop-press' ),
					),
				),
				'default'   => 'table',
			),
			'fields_in_table'             => array(
				'title'      => __( 'Show fields in the table', 'shop-press' ),
				'component'  => 'select',
				'name'       => 'fields_in_table',
				'is_multi'   => true,
				'default'    => array(
					array(
						'value' => 'title',
						'label' => __( 'Title', 'shop-press' ),
					),
					array(
						'value' => 'thumbnail',
						'label' => __( 'Thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'price',
						'label' => __( 'Price', 'shop-press' ),
					),
					array(
						'value' => 'date_added',
						'label' => __( 'Date Added', 'shop-press' ),
					),
					array(
						'value' => 'stock',
						'label' => __( 'Stock', 'shop-press' ),
					),
					array(
						'value' => 'add_to_cart',
						'label' => __( 'Add to Cart', 'shop-press' ),
					),
					array(
						'value' => 'remove',
						'label' => __( 'Remove', 'shop-press' ),
					),
				),
				'options'    => array(
					array(
						'value' => 'title',
						'label' => __( 'Title', 'shop-press' ),
					),
					array(
						'value' => 'thumbnail',
						'label' => __( 'Thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'price',
						'label' => __( 'Price', 'shop-press' ),
					),
					array(
						'value' => 'date_added',
						'label' => __( 'Date Added', 'shop-press' ),
					),
					array(
						'value' => 'stock',
						'label' => __( 'Stock', 'shop-press' ),
					),
					array(
						'value' => 'add_to_cart',
						'label' => __( 'Add to Cart', 'shop-press' ),
					),
					array(
						'value' => 'remove',
						'label' => __( 'Remove', 'shop-press' ),
					),
				),
				'conditions' => $table_conditions,
			),
			'bulk_actions'                => array(
				'title'      => __( 'Bulk Actions', 'shop-press' ),
				'component'  => 'select',
				'name'       => 'bulk_actions',
				'is_multi'   => true,
				'default'    => array(),
				'options'    => array_values( $bulk_actions ),
				'conditions' => $table_conditions,
			),
			'custom_label_headings'       => array(
				'title'      => __( 'Custom label for headings', 'shop-press' ),
				'component'  => 'divider',
				'conditions' => $table_conditions,
			),
			'custom_label_item'           => array(
				'name'       => 'custom_label_item',
				'title'      => __( 'Item', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_label_price'          => array(
				'name'       => 'custom_label_price',
				'title'      => __( 'Price', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_label_date_added'     => array(
				'name'       => 'custom_label_date_added',
				'title'      => __( 'Date', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_label_stock'          => array(
				'name'       => 'custom_label_stock',
				'title'      => __( 'Stock', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_label_add_to_cart'    => array(
				'name'       => 'custom_label_add_to_cart',
				'title'      => __( 'Add to Cart', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_label_remove'         => array(
				'name'       => 'custom_label_remove',
				'title'      => __( 'Remove', 'shop-press' ),
				'component'  => 'text',
				'conditions' => $table_conditions,
			),
			'custom_empty_wishlist_texts' => array(
				'title'     => __( 'Empty Wishlist', 'shop-press' ),
				'component' => 'divider',
			),
			'empty_wishlist_title'        => array(
				'name'      => 'empty_wishlist_title',
				'title'     => __( 'Title', 'shop-press' ),
				'component' => 'text',
			),
			'empty_wishlist_button_text'  => array(
				'name'      => 'empty_wishlist_button_text',
				'title'     => __( 'Button Text', 'shop-press' ),
				'component' => 'text',
			),
			'thumbnail_size_heading'      => array(
				'title'      => __( 'Thumbnail Size', 'shop-press' ),
				'component'  => 'divider',
				'conditions' => $table_conditions,
			),
			'thumbnail_size_width'        => array(
				'name'       => 'thumbnail_size_width',
				'title'      => __( 'Width', 'shop-press' ),
				'component'  => 'number',
				'conditions' => $table_conditions,
			),
			'thumbnail_size_height'       => array(
				'name'       => 'thumbnail_size_height',
				'title'      => __( 'Height', 'shop-press' ),
				'component'  => 'number',
				'conditions' => $table_conditions,
			),
		);

		if ( empty( $bulk_actions ) ) {
			unset( $fields['bulk_actions'] );
		}

		return $fields;
	}

	/**
	 * Return wishlist popular products fields.
	 *
	 * @since 1.4.0
	 *
	 * @return array $fields
	 */
	public static function wishlist_popular_products() {
		$fields = array(
			'heading' => array(
				'title'     => __( 'Popular Products', 'shop-press' ),
				'component' => 'heading',
			),
			'items'   => array(
				'component'     => 'list_items',
				'items_group'   => 'shoppress_wishlist_popular_products',
				'view'          => true,
				'meta_in_table' => array(
					'title'          => __( 'Product', 'shop-press' ),
					'category'       => __( 'Category', 'shop-press' ),
					'wishlist_count' => __( 'Wishlist Count', 'shop-press' ),
				),
			),
		);

		return $fields;
	}


	/**
	 * Get wishlist table settings fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $wishlist_fields
	 */
	public static function wishlist_all_wishlist_settings() {

		$fields = array(
			'heading' => array(
				'title'     => __( 'Wishlists', 'shop-press' ),
				'component' => 'heading',
			),
			'list'    => array(
				'component'     => 'dynamic_items_list',
				'post_type'     => 'shoppress_wishlist',
				'display_user'  => true,
				'meta_in_table' => array(
					'total_products' => __( 'Items', 'shop-press' ),
				),
			),
		);

		return $fields;
	}

	/**
	 * Get compare fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $compare_fields
	 */
	public static function compare() {

		$compare_fields = array(
			'heading'                      => array(
				'title'     => __( 'Compare', 'shop-press' ),
				'component' => 'heading',
			),
			'compare_page'                 => array(
				'title'         => __( 'Compare Page', 'shop-press' ),
				'name'          => 'compare_page',
				'default'       => '',
				'placeholder'   => __( 'Search pages...', 'shop-press' ),
				'is_searchable' => true,
				'is_multi'      => false,
				'is_clearable'  => true,
				'component'     => 'select_page',
				'tooltip'       => array(
					'content' => __( 'use [shoppress-compare-page] shortcode in page.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-compare-page/',
				),
			),
			'display_popup_after_update'   => array(
				'title'     => __( 'Popup', 'shop-press' ),
				'name'      => 'display_popup_after_update',
				'component' => 'switch',
				'default'   => false,
				'tooltip'   => array(
					'content' => __( 'Display popup after add to compare.', 'shop-press' ),
				),
			),
			'fields_in_table'              => array(
				'title'     => __( 'Show Fields in The Table', 'shop-press' ),
				'component' => 'select',
				'name'      => 'fields_in_table',
				'is_multi'  => true,
				'default'   => array(
					array(
						'value' => 'title',
						'label' => __( 'Title', 'shop-press' ),
					),
					array(
						'value' => 'thumbnail',
						'label' => __( 'Thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'price',
						'label' => __( 'Price', 'shop-press' ),
					),
					array(
						'value' => 'sku',
						'label' => __( 'Sku', 'shop-press' ),
					),
					array(
						'value' => 'categories',
						'label' => __( 'Categories', 'shop-press' ),
					),
					array(
						'value' => 'add_to_cart',
						'label' => __( 'Add to Cart', 'shop-press' ),
					),
					array(
						'value' => 'ratings',
						'label' => __( 'Ratings', 'shop-press' ),
					),
				),
				'options'   => array(
					array(
						'value' => 'title',
						'label' => __( 'Title', 'shop-press' ),
					),
					array(
						'value' => 'thumbnail',
						'label' => __( 'Thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'price',
						'label' => __( 'Price', 'shop-press' ),
					),
					array(
						'value' => 'sku',
						'label' => __( 'Sku', 'shop-press' ),
					),
					array(
						'value' => 'categories',
						'label' => __( 'Categories', 'shop-press' ),
					),
					array(
						'value' => 'add_to_cart',
						'label' => __( 'Add to Cart', 'shop-press' ),
					),
					array(
						'value' => 'ratings',
						'label' => __( 'Ratings', 'shop-press' ),
					),
				),
			),
			'limit_the_number_of_products' => array(
				'title'     => __( 'Limit the number of products', 'shop-press' ),
				'name'      => 'limit_the_number_of_products',
				'component' => 'number',
				'default'   => 4,
			),
			'thumbnail_size'               => array(
				'title'     => __( 'Thumbnail Size', 'shop-press' ),
				'component' => 'divider',
			),
			'thumbnail_width'              => array(
				'title'     => __( 'Width', 'shop-press' ),
				'name'      => 'thumbnail_width',
				'component' => 'number',
				'default'   => '',
			),
			'thumbnail_height'             => array(
				'title'     => __( 'Height', 'shop-press' ),
				'name'      => 'thumbnail_height',
				'component' => 'number',
				'default'   => '',
			),
			'compare_labels'               => array(
				'title'     => __( 'Labels', 'shop-press' ),
				'component' => 'divider',
			),
			'add_label'                    => array(
				'title'       => __( 'Add Label', 'shop-press' ),
				'name'        => 'add_label',
				'default'     => __( 'Add to Compare', 'shop-press' ),
				'placeholder' => __( 'Add to Compare', 'shop-press' ),
				'component'   => 'text',
			),
			'remove_label'                 => array(
				'title'       => __( 'Remove Label', 'shop-press' ),
				'name'        => 'remove_label',
				'default'     => __( 'Remove', 'shop-press' ),
				'placeholder' => __( 'Remove', 'shop-press' ),
				'component'   => 'text',
			),
			'compare_locations'            => array(
				'title'     => __( 'Location', 'shop-press' ),
				'component' => 'divider',
			),
			'location_product_page'        => array(
				'title'        => __( 'Product Single Page', 'shop-press' ),
				'name'         => 'location_product_page',
				'default'      => array(
					'value' => 'woocommerce_before_add_to_cart_form',
					'label' => __( 'Before add to cart', 'shop-press' ),
				),
				'options'      => array(
					array(
						'value' => 'woocommerce_after_add_to_cart_form',
						'label' => __( 'After add to cart', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_before_add_to_cart_form',
						'label' => __( 'Before add to cart', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_product_meta_start',
						'label' => __( 'Before product metadata', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_product_meta_end',
						'label' => __( 'After product metadata', 'shop-press' ),
					),
				),
				'component'    => 'select',
				'is_clearable' => true,
			),
			'location_products_loop'       => array(
				'title'        => __( 'Products Loop', 'shop-press' ),
				'name'         => 'location_products_loop',
				'default'      => array(
					'value' => 'woocommerce_after_shop_loop_item',
					'label' => __( 'Before add to cart', 'shop-press' ),
				),
				'options'      => array(
					array(
						'value' => 'woocommerce_before_shop_loop_item',
						'label' => __( 'Before thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_after_shop_loop_item',
						'label' => __( 'Before add to cart', 'shop-press' ),
					),
					// array(
					// 'value' => 'woocommerce_overlay_thumbnail',
					// 'label' => __( 'Overlay on the thumbnail', 'shop-press' ),
					// ),
				),
				'component'    => 'select',
				'is_clearable' => true,
			),
		);

		return $compare_fields;
	}

	/**
	 * Get quick view fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $quick_view_fields
	 */
	public static function quick_view() {

		$social_share_options = array();
		$share_url_patterns   = sp_get_social_share_links();
		foreach ( $share_url_patterns as $social_id => $social ) {

			$social_share_options[] = array(
				'value' => $social_id,
				'label' => $social['title'],
			);
		}

		$quick_view_fields = array(
			'heading'                => array(
				'title'     => __( 'Quick View', 'shop-press' ),
				'component' => 'heading',
			),
			'location_products_loop' => array(
				'title'        => __( 'Location in Archive Loop', 'shop-press' ),
				'name'         => 'location_products_loop',
				'default'      => array(
					'value' => 'woocommerce_after_shop_loop_item',
					'label' => __( 'After add to cart', 'shop-press' ),
				),
				'options'      => array(
					array(
						'value' => 'woocommerce_before_shop_loop_item',
						'label' => __( 'Before thumbnail', 'shop-press' ),
					),
					array(
						'value' => 'woocommerce_after_shop_loop_item',
						'label' => __( 'After add to cart', 'shop-press' ),
					),
					// array(
					// 'value' => 'woocommerce_overlay_thumbnail',
					// 'label' => __( 'Overlay on the thumbnail', 'shop-press' ),
					// ),
				),
				'component'    => 'select',
				'is_clearable' => true,
			),
			'social_share'           => array(
				'title'     => __( 'Social Share', 'shop-press' ),
				'name'      => 'social_share',
				'component' => 'switch',
				'default'   => false,
			),
			'social_media'           => array(
				'title'        => __( 'Social Media', 'shop-press' ),
				'name'         => 'social_media',
				'default'      => $social_share_options,
				'options'      => $social_share_options,
				'component'    => 'select',
				'is_multi'     => true,
				'is_clearable' => true,
				'conditions'   => array(
					'parent' => 'modules',
					'name'   => 'quick_view',
					'terms'  => array(
						array(
							'name'     => 'social_share',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
		);

		return $quick_view_fields;
	}

	/**
	 * Get shopify checkout fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $shopify_checkout_fields
	 */
	public static function shopify_checkout() {
		$shopify_checkout_fields = array(
			'heading'            => array(
				'title'     => __( 'Shopify Style Checkout', 'shop-press' ),
				'component' => 'heading',
			),
			'header'             => array(
				'title'     => __( 'Header', 'shop-press' ),
				'component' => 'divider',
			),
			'logo'               => array(
				'title'     => __( 'Logo', 'shop-press' ),
				'name'      => 'logo',
				'component' => 'image',
				'default'   => '',
			),
			'cart_navigation'    => array(
				'title'     => __( 'Navigation', 'shop-press' ),
				'name'      => 'cart_navigation',
				'component' => 'switch',
				'default'   => true,
			),
			'shipping'           => array(
				'title'     => __( 'Shipping', 'shop-press' ),
				'component' => 'divider',
			),
			'shipping_step'      => array(
				'title'     => __( 'Shipping Step', 'shop-press' ),
				'name'      => 'shipping_step',
				'component' => 'switch',
				'default'   => true,
			),
			'bottom'             => array(
				'title'     => __( 'Bottom Menu', 'shop-press' ),
				'component' => 'divider',
			),
			'bottom_menu'        => array(
				'title'     => __( 'Menu', 'shop-press' ),
				'name'      => 'bottom_menu',
				'component' => 'switch',
				'default'   => false,
			),
			'menu_cart_id'       => array(
				'title'         => __( 'Select a Menu', 'shop-press' ),
				'placeholder'   => __( 'Search menu...', 'shop-press' ),
				'name'          => 'menu_cart_id',
				'is_searchable' => true,
				'is_clearable'  => true,
				'component'     => 'select_menu',
				'default'       => '',
				'conditions'    => array(
					'parent' => 'modules',
					'name'   => 'shopify_checkout',
					'terms'  => array(
						array(
							'name'     => 'bottom_menu',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'checkout_fields'    => array(
				'component' => 'divider',
				'title'     => __( 'Billing Fields', 'shop-press' ),
			),
			'phone_number_field' => array(
				'title'     => __( 'Phone Number', 'shop-press' ),
				'name'      => 'phone_number_field',
				'component' => 'switch',
				'default'   => false,
			),
			'company_name_field' => array(
				'title'     => __( 'Company Name', 'shop-press' ),
				'name'      => 'company_name_field',
				'component' => 'switch',
				'default'   => false,
			),
			'billing_address_2'  => array(
				'title'     => __( 'Billing address line 2', 'shop-press' ),
				'name'      => 'billing_address_2',
				'component' => 'switch',
				'default'   => false,
			),
			'shipping_address_2' => array(
				'title'      => __( 'Shipping address line 2', 'shop-press' ),
				'name'       => 'shipping_address_2',
				'component'  => 'switch',
				'default'    => false,
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'shopify_checkout',
					'terms'  => array(
						array(
							'name'     => 'shipping_step',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
		);

		return $shopify_checkout_fields;
	}

	/**
	 * Return mobile panel fields.
	 *
	 * @since 1.3.6
	 *
	 * @return array
	 */
	public static function mobile_panel() {

		$item_types = array(
			array(
				'value' => 'my_account',
				'label' => __( 'My Account', 'shop-press' ),
			),
			array(
				'value' => 'compare',
				'label' => __( 'Compare', 'shop-press' ),
			),
			array(
				'value' => 'cart',
				'label' => __( 'Cart', 'shop-press' ),
			),
			array(
				'value' => 'shop',
				'label' => __( 'Shop', 'shop-press' ),
			),
		);

		if ( sp_is_module_active( 'wishlist' ) ) {
			$item_types[] = array(
				'value' => 'wishlist',
				'label' => __( 'Wishlist', 'shop-press' ),
			);
		}

		$item_types[] = array(
			'value' => 'custom',
			'label' => __( 'Custom', 'shop-press' ),
		);

		$default_items = array(
			array(
				'id'    => '7764324a-c19f-4758-98a2-f71258791156',
				'label' => __( 'Shop', 'shop-press' ),
				'type'  => array(
					'value' => 'shop',
					'label' => __( 'Shop', 'shop-press' ),
				),
				'icon'  => '',
			),
			array(
				'id'    => '54ac79af-ee87-42fd-8acd-835b667ba9cf',
				'label' => __( 'Wishlist', 'shop-press' ),
				'type'  =>
				array(
					'value' => 'wishlist',
					'label' => __( 'Wishlist', 'shop-press' ),
				),
				'icon'  => '',
				'link'  => '',
			),
			array(
				'id'    => '9c750363-dac9-44e8-9b53-782b937b2562',
				'label' => __( 'Cart', 'shop-press' ),
				'cart',
				'type'  => array(
					'value' => 'cart',
					'label' => __( 'Cart', 'shop-press' ),
				),
				'icon'  => '',
			),
			array(
				'id'    => '7c18ab1f-e1c5-4552-bad7-3ecd31c9b48f',
				'label' => __( 'Compare', 'shop-press' ),
				'type'  => array(
					'value' => 'compare',
					'label' => __( 'Compare', 'shop-press' ),
				),
				'icon'  => '',
			),
			array(
				'id'    => 'e12fa859-297b-4baf-8bfb-bd9427d1047b',
				'label' => __( 'My Account', 'shop-press' ),
				'type'  => array(
					'value' => 'my_account',
					'label' => __( 'My Account', 'shop-press' ),
				),
				'icon'  => '',
			),
		);

		$fields = array(
			array(
				'title'     => __( 'Mobile Panel', 'shop-press' ),
				'component' => 'heading',
			),
			'items' => array(
				'name'      => 'items',
				'component' => 'repeater',
				// 'switcher'  => 'status',
				'max_items' => 5,
				'heading'   => 'label',
				'default'   => $default_items,
				'fields'    => array(
					array(
						'title'     => __( 'Label', 'shop-press' ),
						'name'      => 'label',
						'component' => 'text',
					),
					array(
						'title'     => __( 'Type', 'shop-press' ),
						'name'      => 'type',
						'component' => 'select',
						'options'   => $item_types,
					),
					array(
						'title'     => __( 'Icon', 'shop-press' ),
						'name'      => 'icon',
						'component' => 'image',
					),

					array(
						'title'      => __( 'Link', 'shop-press' ),
						'name'       => 'link',
						'component'  => 'text',
						'conditions' => array(
							'parent'        => 'modules',
							'name'          => 'mobile_panel',
							'repeater_name' => 'items',
							'terms'         => array(
								array(
									'name'     => 'type',
									'operator' => '===',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			),
		);

		return $fields;
	}

	/**
	 * Get multi step checkout checkout fields.
	 *
	 * @since 1.3.7
	 *
	 * @return array
	 */
	public static function multi_step_checkout() {
		$fields = array(
			'heading'                                     => array(
				'title'     => __( 'Multi Step Checkout', 'shop-press' ),
				'component' => 'heading',
			),
			'remove_billing_address_2'                    => array(
				'title'     => __( 'Remove billing address line 2', 'shop-press' ),
				'name'      => 'remove_billing_address_2',
				'component' => 'switch',
				'default'   => false,
			),
			'remove_shipping_address_2'                   => array(
				'title'     => __( 'Remove shipping address line 2', 'shop-press' ),
				'name'      => 'remove_shipping_address_2',
				'component' => 'switch',
				'default'   => false,
			),
			'show_return_to_shop'                         => array(
				'title'     => __( 'Show return to shop button', 'shop-press' ),
				'name'      => 'show_return_to_shop',
				'component' => 'switch',
				'default'   => true,
			),
			'display_billing_and_shipping_steps_together' => array(
				'title'     => __( 'Display billing and shipping steps together', 'shop-press' ),
				'name'      => 'display_billing_and_shipping_steps_together',
				'component' => 'switch',
				'default'   => false,
			),
			'display_order_and_payment_steps_together'    => array(
				'title'     => __( 'Display order and payment steps together', 'shop-press' ),
				'name'      => 'display_order_and_payment_steps_together',
				'component' => 'switch',
				'default'   => false,
			),
		);

		return $fields;
	}

	/**
	 * Get catalog mode fields.
	 *
	 * @since 1.4.0
	 *
	 * @return array $catalog_mode_fields
	 */
	public static function catalog_mode() {
		$catalog_mode_fields = array(
			'heading'              => array(
				'title'     => __( 'Catalog Mode', 'shop-press' ),
				'component' => 'heading',
			),
			'hide_add_to_cart'     => array(
				'title'     => __( 'Hide the add to cart button', 'shop-press' ),
				'name'      => 'hide_add_to_cart',
				'default'   => false,
				'component' => 'switch',
			),
			'change_text_and_link' => array(
				'title'      => __( 'Change text and link', 'shop-press' ),
				'name'       => 'change_text_and_link',
				'default'    => false,
				'component'  => 'switch',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'catalog_mode',
					'terms'  => array(
						array(
							'name'     => 'hide_add_to_cart',
							'operator' => '!==',
							'value'    => true,
						),
					),
				),
			),
			'custom_text'          => array(
				'title'      => __( 'Add to cart custom text', 'shop-press' ),
				'name'       => 'custom_text',
				'component'  => 'text',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'catalog_mode',
					'terms'  => array(
						array(
							'name'     => 'hide_add_to_cart',
							'operator' => '!==',
							'value'    => true,
						),
						array(
							'name'     => 'change_text_and_link',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'custom_link'          => array(
				'title'      => __( 'Add to cart custom link', 'shop-press' ),
				'name'       => 'custom_link',
				'component'  => 'text',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'catalog_mode',
					'terms'  => array(
						array(
							'name'     => 'hide_add_to_cart',
							'operator' => '!==',
							'value'    => true,
						),
						array(
							'name'     => 'change_text_and_link',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'hide_price'           => array(
				'title'     => __( 'Hide the price', 'shop-press' ),
				'name'      => 'hide_price',
				'default'   => false,
				'component' => 'switch',
			),
			'price_placeholder'    => array(
				'title'      => __( 'Price Placeholder', 'shop-press' ),
				'name'       => 'price_placeholder',
				'component'  => 'text',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'catalog_mode',
					'terms'  => array(
						array(
							'name'     => 'hide_price',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			'hide_review_tab'      => array(
				'title'     => __( 'Hide the review tab', 'shop-press' ),
				'name'      => 'hide_review_tab',
				'default'   => false,
				'component' => 'switch',
			),
			// 'header'             => array(
			// 'title'     => __( 'Custom Rules', 'shop-press' ),
			// 'component' => 'divider',
			// ),
			// 'rules'    => array(
			// 'title'           => __( 'Rules', 'shop-press' ),
			// 'name'            => 'rules',
			// 'component'       => 'rules',
			// 'rule_conditions' => array(
			// 'products',
			// 'product_categories',
			// 'user_role',
			// 'login_status',
			// ),
			// ),
		);

		return $catalog_mode_fields;
	}
}
