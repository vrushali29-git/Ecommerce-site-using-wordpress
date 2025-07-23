<?php
/**
 * The public-facing functionality of the plugin.
 */

namespace ShopPress\Modules\VariationSwatches;

use ShopPress\Templates\Single;
use ShopPress\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Frontend {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		if ( ! is_admin() || wp_doing_ajax() ) {
			$this->hooks();
		}
	}

	/**
	 * Register Class hooks.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function hooks() {
		add_action( 'after_setup_theme', array( $this, 'public_hook_definition' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'enqueue_scripts' ) );
		add_filter( 'wc_get_template', array( __CLASS__, 'filter_wc_template' ), 10, 2 );
		add_filter( 'woocommerce_variation_is_active', array( __CLASS__, 'filter_variation_active_status' ), 10, 2 );
		add_filter( 'woocommerce_format_price_range', array( __CLASS__, 'filter_format_price_range' ), 10, 3 );
	}

	/**
	 * Swatches Frontend Scripts & Style
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( is_page() || is_woocommerce() ) {
			wp_enqueue_style( 'sp-variation-swatches' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-variation-swatches-rtl' );
			}

			wp_enqueue_script( 'sp-variation-swatches' );
		}
	}

	public function public_hook_definition() {
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'swatches_display' ), 100, 2 );
		add_filter( 'woocommerce_dropdown_variation_attribute_options_args', array( $this, 'add_class_for_attribute_type' ), 101, 1 );
	}

	/**
	 * Swatches HTML output
	 *
	 * @since 1.0.0
	 * @access public
	 * @param HTML  $html The swatches HTML
	 * @param Array $args Arguments.
	 */
	public function swatches_display( $html, $args ) {

		global $product;
		$attribute   = $args['attribute'];
		$type        = $this->get_attribute_fields( $attribute, $product );
		$design_type = $this->get_attributes_display_design( $attribute, $product );

		$auto_convert       = sp_get_module_settings( 'variation_swatches', 'variation_swatches_covert_selects' );
		$apply_auto_convert = false;

		if ( $type === 'select' || $type == null ) {
			if ( $auto_convert ) {
				$type               = 'label';
				$apply_auto_convert = true;
			} else {
				$html = $this->wrapp_variation_in_class( $html );
				return $html;
			}
		}

		$swatch_types = array( 'color', 'image', 'label', 'brand' );

		if ( in_array( $type, $swatch_types ) ) {

			$html           = '';
			$attr_type_html = '';

			$attr_type_html .= $this->swatch_display_options_html( $html, $args, $type, $design_type, $apply_auto_convert );

		} else {
			return $html;
		}

		$html = $attr_type_html;
		$html = $this->wrapp_variation_in_class( $html );
		return $html;
	}

	/**
	 * Get Attribute Fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $attribute Product Attribute
	 * @param Object $product Product Object.
	 */
	public function get_attribute_fields( $attribute, $product ) {
		if ( taxonomy_exists( $attribute ) ) {

			$attribute_taxonomies = wc_get_attribute_taxonomies();

			foreach ( $attribute_taxonomies as $tax ) {
				if ( 'pa_' . $tax->attribute_name == $attribute ) {
					return( $tax->attribute_type );
					break;
				}
			}
		} else {
			$product_id          = $product->get_id();
			$attribute           = sanitize_title( $attribute );
			$local_attr_settings = get_post_meta( $product_id, 'th_custom_attribute_settings', true );
			if ( is_array( $local_attr_settings ) && isset( $local_attr_settings[ $attribute ] ) ) {
				$settings = $local_attr_settings[ $attribute ];
				$type     = isset( $settings['type'] ) ? $settings['type'] : '';
				return $type;
			}
			return '';
		}
	}

	/**
	 * Get Attribute Fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $attribute Product Attribute
	 * @param Object $product Product Object.
	 */
	public function get_attributes_display_design( $attribute, $product ) {
		if ( taxonomy_exists( $attribute ) ) {
			$attr_id = $this->get_attribute_id( $attribute );
			// todo: to adds type option. first add some options for it. then handel it from here
			$design_type = 'swatch_design_default';
			return $design_type ? $design_type : 'swatch_design_default';
		} else {
			$product_id          = $product->get_id();
			$local_attr_settings = get_post_meta( $product_id, 'th_custom_attribute_settings', true );
			$attribute           = sanitize_title( $attribute );
			if ( is_array( $local_attr_settings ) && isset( $local_attr_settings[ $attribute ] ) ) {
				$settings = $local_attr_settings[ $attribute ];
				$type     = isset( $settings['design_type'] ) ? $settings['design_type'] : 'swatch_design_default';
				return $type ? $type : 'swatch_design_default';
			}

			return 'swatch_design_default';
		}
	}

	/**
	 * Get Attribute ID
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $taxonomy Product taxonomy
	 */
	public function get_attribute_id( $taxonomy ) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		foreach ( $attribute_taxonomies as $tax ) {
			if ( 'pa_' . $tax->attribute_name == $taxonomy ) {
				return( $tax->attribute_id );
				break;
			}
		}
	}

	/**
	 * Get Attribute ID
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Array $args Attribute Arguments
	 */
	public function add_class_for_attribute_type( $args ) {
		$args['class'] = 'thwvs-select';
		return $args;
	}

	/**
	 * Add Wrap Div to Variation HTML
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $taxonomy Product taxonomy
	 */
	public function wrapp_variation_in_class( $html ) {
		$html = '<div class="sp_vs_fields"> ' . $html . ' </div>';
		return $html;
	}

	/**
	 * Swatch Display Options HTML
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $html
	 * @param Object $args
	 * @param Object $type
	 * @param Object $design_type
	 * @param Object $apply_auto_convert
	 */
	public function swatch_display_loop_html( $html, $args, $type, $design_type, $apply_auto_convert ) {

		$options                     = $args['options'] ?? '';
		$product                     = $args['product'] ?? '';
		$attribute                   = $args['attribute'] ?? '';
		$name                        = $args['name'] ?? 'attribute_' . sanitize_title( $attribute );
		$id                          = $args['id'] ?? sanitize_title( $attribute );
		$product_id                  = $product->get_id();
		$tooltip                     = sp_get_module_settings( 'variation_swatches', 'variation_swatches_tooltip' );
		$image_as_tooltip            = sp_get_module_settings( 'variation_swatches', 'image_as_tooltip' );
		$clear_variation_by_reselect = sp_get_module_settings( 'variation_swatches', 'clear_variation_by_reselect' );
		$disabled_variation_style    = sp_get_module_settings( 'variation_swatches', 'disabled_variation_style' )['value'] ?? 'blur_with_cross';
		$shape_style                 = sp_get_module_settings( 'variation_swatches', 'shape_style' )['value'] ?? '';

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			if ( method_exists( $product, 'get_variation_attributes' ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			} else {
				$product_attributes_ids = $product->get_attributes();
				$attributes             = get_terms(
					array(
						'taxonomy' => $attribute,
						'include'  => $product_attributes_ids,
					)
				);

				$options = array();
				foreach ( $attributes as $a_term ) {
					$options[] = $a_term->slug;
				}
			}
		}

		if ( ! empty( $options ) ) {

			if ( $product ) {

				$terms               = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
				$terms               = taxonomy_exists( $attribute ) ? $terms : $options;
				$local_attr_settings = get_post_meta( $product_id, 'th_custom_attribute_settings', true );
				$local_settings      = isset( $local_attr_settings[ $id ] ) ? $local_attr_settings[ $id ] : '';
				$tt_html             = '';

				$html .= '<ul class="sp-wrapper-ul ' . ( $clear_variation_by_reselect ? 'sp-clear-by-reselect' : '' ) . ' sp-disabled-variation-style-' . $disabled_variation_style . ' sp-variation-style-' . $shape_style . '">';

				foreach ( $terms as $term ) {

					$term_status = false;
					$name        = '';
					$slug        = '';
					$selected    = '';
					$attr_method = '';

					if ( taxonomy_exists( $attribute ) ) {
						$term_status = false;
						$term_value  = $local_settings[ $term->term_id ]['term_value'] ?? '';
						$attr_method = ! empty( $term_value ) && ( ! is_array( $term_value ) || ( isset( $term_value[0] ) && ! empty( $term_value[0] ) ) ) ? 'local' : 'global';
						if ( in_array( $term->slug, $options, true ) ) {
							$term_status = true;
							$name        = apply_filters( 'woocommerce_variation_option_name', $term->name );
							$slug        = $term->slug;
							$selected    = sanitize_title( $args['selected'] ?? '' ) == $term->slug ? 'sp-selected' : '';
						}
					} else {

						$term_status = true;
						$name        = is_a( $term, '\WP_Term' ) ? $term->name : $term;
						$slug        = is_a( $term, '\WP_Term' ) ? $term->slug : $name;
						$selected    = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $term ), false ) : selected( $args['selected'], $term, false );
						$selected    = $selected ? 'sp-selected' : '';
						$attr_method = 'local';

					}

					$attr_class          = preg_replace( '/[^A-Za-z0-9\-\_]/', '', $slug );
					$data_val            = $slug;
					$design_class        = 'attr_' . $design_type;
					$tt_design_class     = 'tooltip_' . $design_type;
					$tt_html             = '';
					$class_image_tooltip = '';

					if ( $tooltip ) {
						$tooltip_content = esc_html( $name );
						if ( $image_as_tooltip && 'image' === $type ) {
							$image               = $this->get_term_value( $name, $term, $attribute, $attr_method, $local_settings );
							$tooltip_content     = $this->get_attachment_image( $image, $name, array( 150, 150 ) );
							$class_image_tooltip = 'image_tooltip';
						}
						$tt_html = '<span class="tooltiptext tooltip_' . esc_attr( $id ) . ' ' . esc_attr( $tt_design_class ) . ' ' . esc_attr( $class_image_tooltip ) . '">' . wp_kses_post( $tooltip_content ) . '</span>';
					}

					if ( $term_status ) {

						switch ( $type ) {
							case 'color':
								$html .= $this->add_color_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $terms );
								break;
							case 'image':
							case 'brand':
								$html .= $this->add_image_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $terms );
								break;
							case 'label':
								$html .= $this->add_label_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $apply_auto_convert, $terms );
								break;
						}
					}
				}

				$html .= '</ul>';
			}
		}

		return $html;
	}

	/**
	 * Swatch Display Options HTML
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object $html
	 * @param Object $args
	 * @param Object $type
	 * @param Object $design_type
	 * @param Object $apply_auto_convert
	 */
	public function swatch_display_options_html( $html, $args, $type, $design_type, $apply_auto_convert ) {

		$html = $this->default_variation_field( $html, $args, $type, $design_type );
		$html = $this->swatch_display_loop_html( $html, $args, $type, $design_type, $apply_auto_convert );

		return $html;
	}

	/**
	 * Add color display
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $id Attribute ID
	 * @param String $name Attribute Name
	 * @param Object $attribute Product attribute slug
	 * @param Object $term Product term
	 * @param String $attr_class attribute class
	 * @param String $selected Selected attribute
	 * @param HTML   $data_val attribute name
	 * @param Object $tt_html Output HTML
	 */
	public function add_color_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $terms ) {

		$color = $this->get_term_value( $name, $term, $attribute, $attr_method, $local_settings );

		$color = is_array( $color ) ? $color : array( 0 => $color );
		$c1    = $color[0];
		$c2    = apply_filters( 'shoppress/variation_swatches/attribute/color/color2', $c1, $color, $id );
		$c1    = "{$c1} 0%, {$c1} 50%";
		$c2    = "{$c2} 50%, {$c2} 100%";
		$deg   = '315deg';

		$html = '
		<li class="sp-wrapper-item-li sp-color-li sp-div sp-checkbox attribute_' . esc_attr( $id ) . ' ' . esc_attr( $attr_class ) . ' ' . esc_attr( $selected ) . ' ' . esc_attr( $design_class ) . ' sp-tooltip" data-attribute_name="attribute_' . esc_attr( $id ) . '" data-value="' . esc_attr( $data_val ) . '" title="' . esc_attr( $name ) . '">' . $tt_html .
			sprintf( '<span class="sp-item-span sp-item-span-color" style="background:linear-gradient(%s,%s,%s);"> </span>', esc_attr( $deg ), esc_attr( $c1 ), esc_attr( $c2 ) ) .
		'</li>';

		return $html;
	}

	/**
	 * Return term value
	 *
	 * @param string   $name
	 * @param \WP_Term $term
	 * @param string   $attribute
	 * @param array    $local_settings
	 *
	 * @since 1.4.0
	 *
	 * @return mixed
	 */
	public function get_term_value( $name, $term, $attribute, $attr_method, $local_settings ) {

		if ( $attr_method === 'global' ) {
			$value = get_term_meta( $term->term_id, 'product_' . $attribute, true );
			$value = empty( $value ) ? $name : $value;
		} else {

			$term_id = is_a( $term, '\WP_Term' ) ? $term->term_id : 0;
			$value   = $local_settings[ $term_id ]['term_value'] ?? $name;
		}

		return $value;
	}

	/**
	 * Return attachment image
	 *
	 * @param int    $image_id
	 * @param string $name
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public function get_attachment_image( $image_id, $name, $size = array( 44, 44 ) ) {

		$image = $image_id ? wp_get_attachment_image_src( $image_id, 'full' ) : '';
		$image = $image ? $image[0] : SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/placeholder.png';

		return '<img class="swatch-preview swatch-image "  src="' . esc_url( $image ) . ' " width="' . ( $size[0] ?? 44 ) . 'px" height="' . ( $size[1] ?? 44 ) . 'px" alt="' . esc_attr( $name ) . '">';
	}

	/**
	 * Add Image display
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $id Attribute ID
	 * @param String $name Attribute Name
	 * @param Object $attribute Product attribute slug
	 * @param Object $term Product term
	 * @param String $attr_class attribute class
	 * @param String $selected Selected attribute
	 * @param HTML   $data_val attribute name
	 * @param Object $tt_html Output HTML
	 */
	public function add_image_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $terms = false ) {

		$value      = $this->get_term_value( $name, $term, $attribute, $attr_method, $local_settings );
		$image_html = $this->get_attachment_image( $value, $name );

		$html = '<li class="sp-wrapper-item-li sp-image-li sp-div sp-checkbox attribute_' . esc_attr( $id ) . ' ' . esc_attr( $attr_class ) . ' ' . esc_attr( $design_class ) . ' ' . esc_attr( $selected ) . ' sp-tooltip" data-attribute_name="attribute_' . esc_attr( $id ) . '" data-value="' . esc_attr( $data_val ) . '" title="' . esc_attr( $name ) . '" >' . $tt_html . $image_html . '</li>';

		return $html;
	}

	/**
	 * Add Label display
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $id Attribute ID
	 * @param String $name Attribute Name
	 * @param Object $attribute Product attribute slug
	 * @param Object $term Product term
	 * @param String $attr_class attribute class
	 * @param String $selected Selected attribute
	 * @param HTML   $data_val attribute name
	 * @param Object $tt_html Output HTML
	 */
	public function add_label_display( $id, $name, $attribute, $term, $attr_class, $selected, $data_val, $tt_html, $design_class, $attr_method, $local_settings, $apply_auto_convert, $terms = false ) {
		$value = $this->get_term_value( $name, $term, $attribute, $attr_method, $local_settings );

		$html = '<li class="sp-wrapper-item-li sp-label-li sp-div sp-checkbox attribute_' . esc_attr( $id ) . ' ' . esc_attr( $attr_class ) . ' ' . esc_attr( $design_class ) . ' ' . esc_attr( $selected ) . ' sp-tooltip" data-attribute_name="attribute_' . esc_attr( $id ) . '" data-value="' . esc_attr( $data_val ) . '" title="' . esc_attr( $name ) . '">
			' . $tt_html . '
		<span class=" sp-item-span item-span-text ">' . esc_html( $value ) . '</span>
		</li>';

		return $html;
	}

	/**
	 * Validate Variation Fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @param HTML   $html Attribute HTML
	 * @param String $args Attribute Name
	 * @param Object $attr Product attribute slug
	 * @param Object $design_type Product term
	 */
	public function default_variation_field( $html, $args, $attr, $design_type ) {
		$args = wp_parse_args(
			apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ),
			array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'show_option_none' => __( 'Choose an option', 'shop-press' ),
			)
		);

		// Get selected value.
		if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
			$selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
			$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		}

		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = (bool) $args['show_option_none'];
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'shop-press' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		$html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-design_type="' . esc_attr( $design_type ) . '" style="display:none" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '" >';
		$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms(
					$product->get_id(),
					$attribute,
					array(
						'fields' => 'all',
					)
				);

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {

					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					$html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
				}
			}
		}

		$html .= '</select>';
		return $html;
	}

	/**
	 * Filter wc template.
	 *
	 * @since 1.2.1
	 *
	 * @return string
	 */
	public static function filter_wc_template( $located, $template_name ) {

		if ( 'single-product/add-to-cart/variable.php' === $template_name ) {
			$located = sp_get_template_path( 'single-product/product-add-to-cart-variable' );
		}

		return $located;
	}

	/**
	 * Filter variation active status
	 *
	 * @param bool                  $is_active
	 * @param \WC_Product_Variation $variation
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	public static function filter_variation_active_status( $is_active, $variation ) {

		if ( ! $variation->is_in_stock() ) {
			$is_active = false;
		}

		return $is_active;
	}

	/**
	 * Filter format price range
	 *
	 * @param string $price
	 * @param int    $from
	 * @param int    $to
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function filter_format_price_range( $price, $from, $to ) {

		$hide_higher_price = sp_get_module_settings( 'variation_swatches', 'hide_higher_price' );
		if ( $hide_higher_price ) {

			return wc_price( $from );
		}

		return $price;
	}

	/**
	 * Returns the list of the brand attributes.
	 *
	 * @since 1.4.8
	 *
	 * @param bool  $hide_empty
	 * @param array $attribute_ids
	 *
	 * @return array
	 */
	public static function get_brand_attributes( $hide_empty = true, $attribute_ids = array() ) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$brand_attributes     = array();

		foreach ( $attribute_taxonomies as $attribute ) {

			if ( $attribute->attribute_type === 'brand' ) {

				$taxonomy_slug = wc_attribute_taxonomy_name( $attribute->attribute_name );
				$terms         = get_terms(
					array(
						'taxonomy'   => $taxonomy_slug,
						'hide_empty' => $hide_empty,
						'include'    => $attribute_ids,
					)
				);

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

					$brand_attributes = array(
						'id'    => $attribute->attribute_id,
						'name'  => $attribute->attribute_label,
						'slug'  => $taxonomy_slug,
						'terms' => $terms,
					);
				}

				break;
			}
		}

		return $brand_attributes;
	}

	/**
	 * Returns brand attributes output.
	 *
	 * @since 1.4.8
	 *
	 * @param array  $terms
	 * @param string $slug
	 * @param bool   $display_name
	 * @param bool   $display_logo
	 *
	 * @return array
	 */
	public static function get_brand_attributes_output( $terms, $slug, $display_name = true, $display_logo = true ) {
		$shop_url       = wc_get_page_permalink( 'shop' );
		$attribute_slug = str_replace( 'pa_', '', $slug );

		if ( is_wp_error( $terms ) ) {
			return false;
		}

		ob_start();

		foreach ( $terms as $term ) {

			$term_value = get_term_meta( $term->term_id, 'product_' . $slug, true );
			$image      = wp_get_attachment_image( $term_value, 'thumbnail' );
			?>
				<div class="sp-brand-item">
					<a href="<?php echo esc_url( "{$shop_url}?filter_{$attribute_slug}={$term->slug}" ); ?>">
			<?php
			if ( $display_logo == 'true' && $image ) {
				?>
					<div class="sp-brand-img-wrapper"><?php echo wp_kses_post( $image ); ?></div>
				<?php
			}
			if ( $display_name == 'true' && $term->name ) {
				?>
					<div class="brand-name"><?php echo esc_html( $term->name ); ?></div>
				<?php
			}
			?>
					</a>
				</div>
			<?php
		}

		return ob_get_clean();
	}
}
