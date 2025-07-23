<?php

namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Attributes extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-attributes';
	}

	public function get_title() {
		return __( 'Product Attributes', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop-attributes';
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	public function get_script_depends() {
		return array( 'sp-variation-swatches' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-variation-swatches', 'sp-variation-swatches-rtl' );
		} else {
			return array( 'sp-variation-swatches' );
		}
	}

	protected function register_controls() {

		$attribute_taxonomies       = wc_get_attribute_taxonomies();
		$attribute_taxonomy_options = array();
		foreach ( $attribute_taxonomies as $attribute_taxonomy ) {

			if ( 'select' === $attribute_taxonomy->attribute_type ) {
				continue;
			}

			$attribute_taxonomy_options[ $attribute_taxonomy->attribute_id ]           = $attribute_taxonomy->attribute_label;
			$attribute_taxonomy_group_by_type[ $attribute_taxonomy->attribute_type ][] = $attribute_taxonomy->attribute_id;
		}

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'attribute_taxonomy',
			array(
				'label'       => __( 'Attribute', 'shop-press' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => key( $attribute_taxonomy_options ),
				'options'     => $attribute_taxonomy_options,
				'description' => __( 'Please note that you must specify the type of the attributes for them to be available in the option above.', 'shop-press' ),
			)
		);

		$this->end_controls_section();

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'       => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-attributes',
					'wrapper'  => '{{WRAPPER}}',
				),
				'items_wrapper' => array(
					'label'    => esc_html__( 'Items Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-ul',
					'wrapper'  => '{{WRAPPER}} .sp-product-attributes',
				),
				'item_wrapper'  => array(
					'label'    => esc_html__( 'Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wrapper-item-li',
					'wrapper'  => '{{WRAPPER}} .sp-product-attributes .sp-wrapper-ul',
				),
			)
		);

		$this->register_group_styler(
			'attribute_colors',
			__( 'Item', 'shop-press' ),
			array(
				'color_item' => array(
					'label'    => esc_html__( 'Color Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-item-span-color',
					'wrapper'  => '{{WRAPPER}} .sp-product-attributes .sp-wrapper-ul .sp-wrapper-item-li',
				),
			),
			array(
				'attribute_taxonomy' => $attribute_taxonomy_group_by_type['color'] ?? array(),
			)
		);

		$this->register_group_styler(
			'attribute_labels',
			__( 'Item', 'shop-press' ),
			array(
				'label_item' => array(
					'label'    => esc_html__( 'Label Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-item-span.item-span-text',
					'wrapper'  => '{{WRAPPER}} .sp-product-attributes .sp-wrapper-ul .sp-wrapper-item-li',
				),
			),
			array(
				'attribute_taxonomy' => $attribute_taxonomy_group_by_type['label'] ?? array(),
			)
		);

		$this->register_group_styler(
			'attribute_images',
			__( 'Item', 'shop-press' ),
			array(
				'image_item' => array(
					'label'    => esc_html__( 'Image Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.swatch-preview.swatch-image',
					'wrapper'  => '{{WRAPPER}} .sp-product-attributes .sp-wrapper-ul .sp-wrapper-item-li',
				),
			),
			array(
				'attribute_taxonomy' => $attribute_taxonomy_group_by_type['image'] ?? array(),
			)
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'attribute_taxonomy' => $settings['attribute_taxonomy'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-attributes', $args );
		}
	}

	protected function content_template() {

		$attribute_taxonomies             = wc_get_attribute_taxonomies();
		$attribute_taxonomy_group_by_type = array();
		foreach ( $attribute_taxonomies as $attribute_taxonomy ) {

			if ( 'select' === $attribute_taxonomy->attribute_type ) {
				continue;
			}

			$attribute_taxonomy_group_by_type[ $attribute_taxonomy->attribute_id ] = $attribute_taxonomy->attribute_type;
		}
		?>
		<#
		var attribute_type = settings.attribute_taxonomy;
		var attribute_taxonomy_group_by_type = <?php echo json_encode( $attribute_taxonomy_group_by_type ); ?>;
		#>
		<div class="sp-product-attributes">
			<ul class="sp-wrapper-ul">
				<# if( 'color' === attribute_taxonomy_group_by_type[attribute_type] ){ #>
					<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_attribute_4-221 blue  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_attribute_4-221" data-value="blue" title="blue"><span class="sp-item-span sp-item-span-color" style="background-color:#1e73be;"> </span></li>
					<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_attribute_4-221 green  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_attribute_4-221" data-value="green" title="green"><span class="sp-item-span sp-item-span-color" style="background-color:#81d742;"> </span></li>
					<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_attribute_4-221 red  attr_swatch_design_default sp-tooltip" data-attribute_name="attribute_attribute_4-221" data-value="red" title="red"><span class="sp-item-span sp-item-span-color" style="background-color:#dd3333;"> </span></li>
				<# } else if( 'image' === attribute_taxonomy_group_by_type[attribute_type] ){ #>
					<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-1 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-1" title="image 1"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 1"></li>
					<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-2 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-2" title="image 2"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 2"></li>
					<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_attribute_6-221 image-3 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_6-221" data-value="image-3" title="image 3"><img class="swatch-preview swatch-image " src="<?php echo esc_url( SHOPPRESS_URL . '/Elementor/widgets/loop/thumbnail/image/' ); ?>t-shirt-with-logo.jpg" width="44px" height="44px" alt="image 3"></li>
				<# } else if( 'label' === attribute_taxonomy_group_by_type[attribute_type] ){ #>
					<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_attribute_8-221 label-1 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_8-221" data-value="label-1" title="label 1"><span class=" sp-item-span item-span-text ">label 1</span></li>
					<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_attribute_8-221 label-2 attr_swatch_design_default  sp-tooltip" data-attribute_name="attribute_attribute_8-221" data-value="label-2" title="label 2"><span class=" sp-item-span item-span-text ">label 2</span></li>
				<# } #>
			</ul>
		</div>
		<?php
	}
}
