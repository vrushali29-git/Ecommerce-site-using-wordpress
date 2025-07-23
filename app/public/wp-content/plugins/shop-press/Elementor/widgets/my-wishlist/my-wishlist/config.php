<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class MyWishlist extends ShopPressWidgets {

	public function get_name() {
		return 'sp-my-wishlist';
	}

	public function get_title() {
		return __( 'My Wishlist', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-my-wishlist';
	}

	public function get_categories() {
		return array( 'sp_wishlist' );
	}

	public function get_script_depends() {
		return array( 'sp-my-wishlist' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-my-wishlist', 'sp-wishlist', 'sp-my-wishlist-rtl' );
		} else {
			return array( 'sp-my-wishlist', 'sp-wishlist' );
		}
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'header',
			__( 'Header', 'shop-press' ),
			array(
				'h_wrapper'           => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-header',
					'wrapper'  => '{{WRAPPER}}',
				),
				'h_icon'              => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.my-wishlist-title svg',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header',
				),
				'h_title'             => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.my-wishlist-title',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header',
				),

				'h_info_share_status' => array(
					'label'    => esc_html__( 'Share Status', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-share-status',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header .sp-wishlist-info',
				),
				'h_info_created_date' => array(
					'label'    => esc_html__( 'Created Date', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-created-date',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header .sp-wishlist-info',
				),

				'h_author_image'      => array(
					'label'    => esc_html__( 'Avatar image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-author-avatar',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header .sp-wishlist-author',
				),
				'h_author_name'       => array(
					'label'    => esc_html__( 'Avatar Name', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-author-name',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-header .sp-wishlist-author',
				),
			)
		);

		$this->register_group_styler(
			'table',
			__( 'Table', 'shop-press' ),
			array(
				'table' => array(
					'label'    => esc_html__( 'table', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thead' => array(
					'label'    => esc_html__( 'thead', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist thead',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist tr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist th',
					'wrapper'  => '{{WRAPPER}}',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist td',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist tbody',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tfoot' => array(
					'label'    => esc_html__( 'tfoot', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist tfoot',
					'wrapper'  => '{{WRAPPER}}',
				),

			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'title',
			__( 'Product Title', 'shop-press' ),
			array(
				'pr_title'      => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-prod-title',
					'wrapper'  => '{{WRAPPER}} .sp-product .sp-product-row .sp-product-col .sp-content',
				),
				'pr_title_link' => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-prod-title a',
					'wrapper'  => '{{WRAPPER}} .sp-product .sp-product-row .sp-product-col .sp-content',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'thumbnail',
			__( 'Product Thumbnail', 'shop-press' ),
			array(
				'pr_thumbnail' => array(
					'label'    => esc_html__( 'Thumbnail', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-thumb img',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .my-wishlist-table tbody tr.sp-wishlist-list td.sp-product .sp-product-row',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'price',
			__( 'Product Price', 'shop-press' ),
			array(
				'pr_price'        => array(
					'label'    => esc_html__( 'Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-price span.woocommerce-Price-amount.amount',
					'wrapper'  => '{{WRAPPER}}',
				),
				'pr_price_symbol' => array(
					'label'    => esc_html__( 'Symbol', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-price span.woocommerce-Price-currencySymbol',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'stock',
			__( 'Product Stock', 'shop-press' ),
			array(
				'pr_stock' => array(
					'label'    => esc_html__( 'Stock', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist table.my-wishlist-table tbody .sp-product-stock',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'button',
			__( 'Product Add To Cart', 'shop-press' ),
			array(
				'pr_button'          => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist table.my-wishlist-table tbody td.sp-product-addtocart .button',
					'wrapper'  => '{{WRAPPER}}',
				),
				'pr_button_icon'     => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist table.my-wishlist-table tbody td.sp-product-addtocart .button i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'pr_button_icon_svg' => array(
					'label'    => esc_html__( 'SVG Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist table.my-wishlist-table tbody td.sp-product-addtocart .button svg',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'remove_btn',
			__( 'Remove Button', 'shop-press' ),
			array(
				'pr_remove'     => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-rmf-wishlist',
					'wrapper'  => '{{WRAPPER}}',
				),
				'pr_svg_remove' => array(
					'label'    => esc_html__( 'SVG Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-remove-item svg',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
			array(
				'view' => 'table',
			)
		);

		$this->register_group_styler(
			'grid_products',
			__( 'Products', 'shop-press' ),
			array(
				'grid_products'     => array(
					'label'    => esc_html__( 'Products', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-grid',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist',
				),
				'grid_product_item' => array(
					'label'    => esc_html__( 'Product Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_thumbnail',
			__( 'Product Thumbnail', 'shop-press' ),
			array(
				'grid_thumbnail_wrapper' => array(
					'label'    => esc_html__( 'Thumbnail Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-thumbnail',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_thumbnail'         => array(
					'label'    => esc_html__( 'Thumbnail', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-thumbnail',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_title',
			__( 'Product Title', 'shop-press' ),
			array(
				'grid_title'      => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-title',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_title_link' => array(
					'label'    => esc_html__( 'Title Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-title',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_price',
			__( 'Product Price', 'shop-press' ),
			array(
				'grid_price'         => array(
					'label'    => esc_html__( 'Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-price',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_regular_price' => array(
					'label'    => esc_html__( 'Regular Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'del',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-price',
				),
				'grid_sale_price'    => array(
					'label'    => esc_html__( 'Sale Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ins',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-price',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_rating',
			__( 'Product Rating', 'shop-press' ),
			array(
				'grid_rating'      => array(
					'label'    => esc_html__( 'Rating Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-rating',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),

				'grid_rating_star' => array(
					'label'    => esc_html__( 'Star', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'svg',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-rating',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_date',
			__( 'Product Added Time', 'shop-press' ),
			array(
				'grid_date'      => array(
					'label'    => esc_html__( 'Added Time', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-date-added',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_date_icon' => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'svg',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-date-added',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_stock',
			__( 'Product Stock', 'shop-press' ),
			array(
				'grid_stock'            => array(
					'label'    => esc_html__( 'Product Stock', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-stock',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_stock_instock'    => array(
					'label'    => esc_html__( 'In Stock', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-stock.instock',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_stock_outofstock' => array(
					'label'    => esc_html__( 'Out Of Stock', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-stock.outofstock',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'grid_remove',
			__( 'Remove Button', 'shop-press' ),
			array(
				'grid_remove'      => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-wishlist-item-remove-item',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item',
				),
				'grid_remove_icon' => array(
					'label'    => esc_html__( 'Button Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-remove-item',
				),
			),
			array(
				'view' => 'grid',
			)
		);

		$this->register_group_styler(
			'pagination',
			__( 'Pagination', 'shop-press' ),
			array(
				'p_wrapper'             => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-pagination',
					'wrapper'  => '{{WRAPPER}}',
				),
				'p_buttons'             => array(
					'label'    => esc_html__( 'Buttons', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.page-numbers li',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-footer .sp-pagination',
				),
				'p_page_numbers'        => array(
					'label'    => esc_html__( 'Page Numbers', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.page-numbers',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-footer .sp-pagination .page-numbers li',
				),
				'p_current_page_number' => array(
					'label'    => esc_html__( 'Current Page Numbers', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.page-numbers.current',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-footer .sp-pagination .page-numbers li',
				),
				'p_prev'                => array(
					'label'    => esc_html__( 'Prev Page', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.page-numbers.prev',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-footer .sp-pagination .page-numbers li',
				),
				'p_next'                => array(
					'label'    => esc_html__( 'Next Page', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.page-numbers.next',
					'wrapper'  => '{{WRAPPER}} .sp-my-wishlist-footer .sp-pagination .page-numbers li',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'general',
			array(
				'label'    => __( 'General', 'shop-press' ),
				'tab'      => Controls_Manager::TAB_STYLE,
				'conditon' => array(
					'view2' => 'true',
				),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => esc_html__( 'View', 'shop-press' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => sp_get_module_settings( 'wishlist_table_settings', 'my_wishlist_view_type' )['value'] ?? 'table',
			)
		);

		$this->end_controls_section();

		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		sp_load_builder_template( 'wishlist/my-wishlist' );
	}
}
