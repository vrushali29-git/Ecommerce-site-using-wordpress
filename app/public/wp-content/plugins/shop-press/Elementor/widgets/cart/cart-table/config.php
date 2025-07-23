<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class CartTable extends ShopPressWidgets {
	public function get_name() {
		return 'sp-cart-table';
	}

	public function get_title() {
		return __( 'Cart Table', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-cart-table';
	}

	public function get_categories() {
		return array( 'sp_woo_cart' );
	}

	public function get_style_depends() {
		return array( 'sp-radio-button' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'      => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-cart',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_wrapper' => array(
					'label'    => esc_html__( 'Form Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-cart-form',
					'wrapper'  => '{{WRAPPER}} .sp-cart',
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
					'selector' => 'table.shop_table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thead' => array(
					'label'    => esc_html__( 'thead', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table thead',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table tr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table th',
					'wrapper'  => '{{WRAPPER}}',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table td',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table tbody',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tfoot' => array(
					'label'    => esc_html__( 'tfoot', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.shop_table tfoot',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'cart_item',
			__( 'Cart Item', 'shop-press' ),
			array(
				'item_wrap'                     => array(
					'label'    => esc_html__( 'Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.product-item-wrap',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name',
				),
				'thumbnail'                     => array(
					'label'    => esc_html__( 'Thumbnail', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name .product-item-wrap',
				),
				'name'                          => array(
					'label'    => esc_html__( 'Product Name', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.product-name',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name .product-item-wrap',
				),
				'variation_wrapper'             => array(
					'label'    => esc_html__( 'Variation Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'dl.variation',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name .product-item-wrap',
				),
				'variation_label'               => array(
					'label'    => esc_html__( 'Variation Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'dt',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name .product-item-wrap dl.variation',
				),
				'variation_value'               => array(
					'label'    => esc_html__( 'Variation Value', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'dd',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-name .product-item-wrap dl.variation',
				),
				'quantity'                      => array(
					'label'    => esc_html__( 'Quantity Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.quantity',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-quantity',
				),
				'quantity_input'                => array(
					'label'    => esc_html__( 'Quantity Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-quantity .quantity',
				),
				'modern_quantity_input_buttons' => array(
					'label'    => esc_html__( 'Modern Quantity Input Buttons', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-quantity-control svg',
					'wrapper'  => '{{WRAPPER}} table.shop_table td.product-quantity .quantity',
				),
				'price'                         => array(
					'label'    => esc_html__( 'Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td.product-subtotal',
					'wrapper'  => '{{WRAPPER}} table.shop_table',
				),
				'cart_item_remove_icon'         => array(
					'label'    => esc_html__( 'Remove Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'svg',
					'wrapper'  => '{{WRAPPER}} table.shop_table .product-remove a',
				),
			)
		);

		$this->register_group_styler(
			'coupon',
			__( 'Actions', 'shop-press' ),
			array(
				'actions_wrapper'       => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td.actions',
					'wrapper'  => '{{WRAPPER}} table.cart',
				),
				'coupon_wrapper'        => array(
					'label'    => esc_html__( 'Coupon Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.coupon',
					'wrapper'  => '{{WRAPPER}} table.cart td.actions',
				),
				'input_coupon'          => array(
					'label'    => esc_html__( 'Coupon Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input#coupon_code',
					'wrapper'  => '{{WRAPPER}} table.cart td.actions .coupon',
				),
				'btn_coupon'            => array(
					'label'    => esc_html__( 'Coupon Submit Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.button',
					'wrapper'  => '{{WRAPPER}} table.cart td.actions .coupon',
				),
				'continue_shopping_btn' => array(
					'label'    => esc_html__( 'Continue Shopping Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.button.continue-shopping',
					'wrapper'  => '{{WRAPPER}}',
				),
				'update_cart_btn'       => array(
					'label'    => esc_html__( 'Update Cart Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button[name="update_cart"]',
					'wrapper'  => '{{WRAPPER}} .woocommerce-cart-form table.shop_table',
				),
			)
		);

		$this->register_group_styler(
			'buttons',
			__( 'Buttons', 'shop-press' ),
			array()
		);

		$this->register_group_styler(
			'thumbnail',
			__( 'Thumbnail', 'shop-press' ),
			array(
				'product_thumbnail_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.product-thumbnail',
					'wrapper'  => '{{WRAPPER}}',
				),
				'product_thumbnail'         => array(
					'label'    => esc_html__( 'Thumbnail', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.product-thumbnail a img',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'remove_icon',
			__( 'Remove Icon', 'shop-press' ),
			array(
				'remove_icon_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.remove',
					'wrapper'  => '{{WRAPPER}} .product-remove',
				),
				'remove_icon'         => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'path',
					'wrapper'  => '{{WRAPPER}} a.remove svg',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'hide_header_table',
			array(
				'label'   => __( 'Hide Table Header', 'shop-press' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'hide_coupon_form',
			array(
				'label'   => __( 'Hide Coupon Form', 'shop-press' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'quantity_style',
			array(
				'label'   => __( 'Quantity Style', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'modern',
				'options' => array(
					'default' => __( 'Default', 'shop-press' ),
					'modern'  => __( 'Modern', 'shop-press' ),
				),
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public static function replace_quantity_input( $located, $template_name ) {

		if ( 'global/quantity-input.php' !== $template_name ) {
			return $located;
		}

		return sp_get_template_path( 'global/quantity-input-modern' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'hide_header_table' => $settings['hide_header_table'],
			'hide_coupon_form'  => $settings['hide_coupon_form'],
			'quantity_style'    => $settings['quantity_style'] ?? 'modern',
		);

		if ( 'modern' === $args['quantity_style'] ) {
			add_action( 'wc_get_template', array( __CLASS__, 'replace_quantity_input' ), 10, 2 );
		}

		sp_load_builder_template( 'cart/cart-table', $args );

		if ( 'modern' === $args['quantity_style'] ) {
			remove_action( 'wc_get_template', array( __CLASS__, 'replace_quantity_input' ), 10, 2 );
		}
	}
}
