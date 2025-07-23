<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;
use ShopPress\Modules;

class AddToCart extends ShopPressWidgets {

	public function get_name() {
		return 'sp-add-to-cart';
	}

	public function get_title() {
		return __( 'Product Add To Cart', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-add-to-cart';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'   => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-add-to-cart-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'container' => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'form',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper',
				),
			)
		);
		$this->register_group_styler(
			'variations',
			__( 'Variations', 'shop-press' ),
			array(
				'variations-wrapper'      => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'table.variations',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper',
				),
				'label'                   => array(
					'label'    => esc_html__( 'Labels', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations th.label',
				),
				'selected_label'          => array(
					'label'    => esc_html__( 'Selected Attribute Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-selected-attribute-label',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations th.label label',
				),
				'items_list_wrapper'           => array(
					'label'    => esc_html__( 'Items Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td.value',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations',
				),
				'items_wrapper'           => array(
					'label'    => esc_html__( 'Items List', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-ul',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations',
				),
				'item_wrapper'            => array(
					'label'    => esc_html__( 'Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations .sp-wrapper-ul',
				),
				'select_item'             => array(
					'label'    => esc_html__( 'Select', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'select',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper table.variations .sp_vs_fields',
				),
				'color_item_wrapper'      => array(
					'label'    => esc_html__( 'Color Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-color-li',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'color_item'              => array(
					'label'    => esc_html__( 'Color Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-item-span-color',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul .sp-wrapper-item-li',
				),
				'color_item_selected'     => array(
					'label'    => esc_html__( 'Color Item Selected', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-color-li.sp-selected',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'label_item_wrapper'      => array(
					'label'    => esc_html__( 'Label Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-label-li',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'label_item'              => array(
					'label'    => esc_html__( 'Label Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-item-span.item-span-text',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul .sp-wrapper-item-li',
				),
				'label_item_selected'     => array(
					'label'    => esc_html__( 'Label Item Selected', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-label-li.sp-selected',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'image_item_wrapper'      => array(
					'label'    => esc_html__( 'Image Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-image-li',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'image_item'              => array(
					'label'    => esc_html__( 'Image Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.swatch-preview.swatch-image',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul .sp-wrapper-item-li',
				),
				'image_item_selected'     => array(
					'label'    => esc_html__( 'Image Item Selected', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li.sp-image-li.sp-selected',
					'wrapper'  => '{{WRAPPER}} .sp-wrapper-ul',
				),
				'variation_price_wrapper' => array(
					'label'    => esc_html__( 'Variation Price Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-variation-price',
					'wrapper'  => '{{WRAPPER}} .single_variation_wrap',
				),
				'variation_price'         => array(
					'label'    => esc_html__( 'Variation Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.price',
					'wrapper'  => '{{WRAPPER}} .single_variation_wrap .woocommerce-variation-price',
				),
				'reset_button'            => array(
					'label'    => esc_html__( 'Reset Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.reset_variations',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper .variations',
				),
			)
		);
		$this->register_group_styler(
			'quantity',
			__( 'Quantity', 'shop-press' ),
			array(
				'quantity'                => array(
					'label'    => esc_html__( 'Quantity Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.quantity',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper .cart',
				),
				'modern_quantity_wrapper' => array(
					'label'     => esc_html__( 'Quantity input Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => '.sp-quantity-input-modern-wrap',
					'wrapper'   => '{{WRAPPER}} .sp-add-to-cart-wrapper .cart .quantity',
					'condition' => array(
						'quantity_style' => 'modern',
					),
				),
				'quantity_input'          => array(
					'label'    => esc_html__( 'Quantity Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper .cart .quantity',
				),
				'quantity_input'          => array(
					'label'     => esc_html__( 'Quantity Input', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'input.qty',
					'wrapper'   => '{{WRAPPER}} .sp-add-to-cart-wrapper .cart .quantity .sp-quantity-input-modern-wrap',
					'condition' => array(
						'quantity_style' => 'modern',
					),
				),
				'control_minus'           => array(
					'label'     => esc_html__( 'Button Minus', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => '.sp-quantity-control.minus',
					'wrapper'   => '{{WRAPPER}} .sp-add-to-cart-wrapper form .quantity .sp-quantity-input-modern-wrap',
					'condition' => array(
						'quantity_style' => 'modern',
					),
				),
				'control_plus'            => array(
					'label'     => esc_html__( 'Button Plus', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => '.sp-quantity-control.plus',
					'wrapper'   => '{{WRAPPER}} .sp-add-to-cart-wrapper form .quantity .sp-quantity-input-modern-wrap',
					'condition' => array(
						'quantity_style' => 'modern',
					),
				),

			)
		);

		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'button_wrapper' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.button',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper form',
				),
				'icon'           => array(
					'label'    => esc_html__( 'Button Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-add-to-cart-wrapper .button',
				),
			)
		);
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'General', 'shop-press' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'cart_icon',
			array(
				'label'            => __( 'Icon', 'shop-press' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'cart_icon_pos',
			array(
				'label'   => __( 'Icon Position', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => array(
					'before' => __( 'Before', 'shop-press' ),
					'after'  => __( 'After', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'quantity_style',
			array(
				'label'   => __( 'Quantity Style', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'shop-press' ),
					'modern'  => __( 'Modern', 'shop-press' ),
				),
			)
		);

		if ( sp_is_module_active( 'variation_swatches' ) ) {

			$this->add_control(
				'show_variation_dummy_divider',
				array(
					'type' => Controls_Manager::DIVIDER,
				)
			);

			$this->add_control(
				'show_variation_dummy',
				array(
					'label'     => __( 'Variation Swatches Dummy', 'shop-press' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Show', 'shop-press' ),
					'label_off' => __( 'Hide', 'shop-press' ),
				)
			);
		}

		$this->end_controls_section();

		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'cart_icon'      => $settings['cart_icon'],
			'cart_icon_pos'  => $settings['cart_icon_pos'],
			'quantity_style' => $settings['quantity_style'] ?? 'default',
		);

		if ( 'modern' === $args['quantity_style'] ) {
			add_action( 'wc_get_template', array( __CLASS__, 'replace_quantity_input' ), 10, 2 );
		}

		if ( $this->editor_preview() ) {

			if ( ! Modules\CatalogMode::is_catalog_mode() ) {

				sp_load_builder_template( 'single-product/product-add-to-cart', $args );
			}
		}

		if ( 'modern' === $args['quantity_style'] ) {
			remove_action( 'wc_get_template', array( __CLASS__, 'replace_quantity_input' ), 10, 2 );
		}
	}

	public static function replace_quantity_input( $located, $template_name ) {

		if ( 'global/quantity-input.php' !== $template_name ) {
			return $located;
		}

		return sp_get_template_path( 'global/quantity-input-modern' );
	}

	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.cart_icon, { 'aria-hidden': true, 'class': "sp-icon" }, 'i' , 'object' );
		var render_icon;
		var icon_pos = settings.cart_icon_pos;
		var classes = 'default' !== settings.quantity_style ? 'sp-quantity-style-' + settings.quantity_style : '';
		#>

		<div class="sp-add-to-cart-wrapper product-simple {{classes}}">
			<# if ( 'yes' === settings.show_variation_dummy ) { #>

			<table class="variations" cellspacing="0" role="presentation">
				<tbody>
					<tr>
						<th class="label"><label for="pa_color">Color</label></th>
						<td class="value">
						<div class="sp_vs_fields">
							<ul class="sp-wrapper-ul">
								<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_ blue  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_" data-value="blue" title="Blue"><span class="tooltiptext tooltip_ tooltip_swatch_design_default">Blue</span><span class="sp-item-span sp-item-span-color" style="background-color:#82bcf6;"> </span>
								</li>
								<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_ green  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_" data-value="green" title="Green"><span class="tooltiptext tooltip_ tooltip_swatch_design_default">Green</span><span class="sp-item-span sp-item-span-color" style="background-color:#4dbc67;"> </span>
								</li>
								<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_ orange  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_" data-value="orange" title="Orange"><span class="tooltiptext tooltip_ tooltip_swatch_design_default">Orange</span><span class="sp-item-span sp-item-span-color" style="background-color:#ff9940;"> </span>
								</li>
							</ul>
						</div>
						</td>
					</tr>
					<tr>
						<th class="label"><label for="pa_size">Size</label></th>
						<td class="value">
						<div class="sp_vs_fields">
							<ul class="sp-wrapper-ul">
								<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_ 128 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_" data-value="128" title="128">
									<span class="tooltiptext tooltip_ tooltip_swatch_design_default">128</span>
									<span class=" sp-item-span item-span-text ">128</span>
								</li>
								<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_ 256 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_" data-value="256" title="256">
									<span class="tooltiptext tooltip_ tooltip_swatch_design_default">256</span>
									<span class=" sp-item-span item-span-text ">256</span>
								</li>
								<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_ 512 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_" data-value="512" title="512">
									<span class="tooltiptext tooltip_ tooltip_swatch_design_default">512</span>
									<span class=" sp-item-span item-span-text ">512</span>
								</li>
							</ul>
						</div>
						</td>
					</tr>
					<tr>
						<th class="label"><label for="pa_color">Image</label></th>
						<td class="value">
						<div class="sp_vs_fields">
							<ul class="sp-wrapper-ul">
							<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-1 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-1" title="image 1"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 1"></li>
						<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-2 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-2" title="image 2"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 2"></li>
						<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-3 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-3" title="image 3"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 3"></li>
							</ul>
						</div>
						<a class="reset_variations" href="#">Clear</a>
						</td>
					</tr>
				</tbody>
			</table>

			<# } #>

			<form
				class="cart"
				method="post"
			>
				<div class="quantity">
					<# if( 'modern' === settings.quantity_style ) { #>
						<div class="number-input amount sp-quantity-input-modern-wrap">
							<button class="sp-quantity-control minus"><?php echo sp_get_svg_icon( 'sp-qty-minus' ); ?></button>
							<input type="number" id="quantity_65d05ba14198a" class="input-text qty text" name="quantity" value="1" aria-label="Product quantity" size="4" min="1" max="" step="1" placeholder="" inputmode="numeric" autocomplete="off">
							<button class="sp-quantity-control plus"><?php echo sp_get_svg_icon( 'sp-qty-plus' ); ?></button>
						</div>
					<# } else { #>
						<input
							type="number"
							class="input-text qty text"
							name="quantity"
							value="1"
							aria-label="Product quantity"
							size="4"
							min="1"
							max=""
							step="1"
							placeholder=""
							inputmode="numeric"
							autocomplete="off"
						/>
					<# } #>
				</div>

				<button
					type="submit"
					name="add-to-cart"
					class="single_add_to_cart_button button alt ajax_add_to_cart"
				>
					<# if ( 'before' === icon_pos ) {#>
						<# if ( 'svg' === settings.cart_icon.library ) {#>
							<i class="sp-icon">
								{{{ iconHTML.value }}}
							</i>
						<# } else { #>
							{{{ iconHTML.value }}}
						<# } #>
					<# } #>

					Add to cart

					<# if ( 'after' === icon_pos ) {#>
						<# if ( 'svg' === settings.cart_icon.library ) {#>
							<i class="sp-icon">
								{{{ iconHTML.value }}}
							</i>
						<# } else { #>
							{{{ iconHTML.value }}}
						<# } #>
					<# } #>
				</button>
			</form>
		</div>
		<?php
	}
}
