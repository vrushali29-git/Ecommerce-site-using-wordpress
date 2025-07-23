<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package ShopPress
 */
namespace ShopPress\Modules\VariationSwatches;

defined( 'ABSPATH' ) || exit;

class Admin {

	private $version;

	private $taxonomy;

	private $attr_taxonomies;

	private $product_attr_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'hooks' ) );
		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );
	}

	/**
	 * Register Class hooks.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function hooks() {

		$attribute_taxonomies  = wc_get_attribute_taxonomies();
		$this->attr_taxonomies = $attribute_taxonomies;

		add_action( 'admin_init', array( $this, 'hooks' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		// Register Hooks for WooCommerce Products attribute
		foreach ( $attribute_taxonomies as $tax ) {
			$this->product_attr_type = $tax->attribute_type;
			add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );
			add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_column' ) );
			add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
		}

		add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 3 );

		add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 20, 2 );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'woo_data_setting_swatches_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'output_custom_swatches_settings' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_fields' ), 10, 2 );
	}

	/**
	 * Register Attribute Types.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $type add the attribute type
	 */
	public function add_attribute_types( $types ) {
		$more_types = array(
			'color' => __( 'Color', 'shop-press' ),
			'image' => __( 'Image', 'shop-press' ),
			'label' => __( 'Label', 'shop-press' ),
			'brand' => __( 'Brand', 'shop-press' ),
		);

		$types = array_merge( $types, $more_types );
		return $types;
	}

	/**
	 * Swatches Admin Scripts & Style
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $hook it shows current page location
	 */
	public function admin_enqueue( $hook ) {

		if ( strpos( $hook, 'product_page_th_product_variation_swatches_for_woocommerce' ) === false ) {
			if ( ! ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit-tags.php' || $hook == 'term.php' || $hook == 'product_page_product_attributes' ) ) {
				return;
			}
		}

		wp_enqueue_style( 'sp-admin-pickr-style', SHOPPRESS_URL . 'public/modules/variation-swatches/admin/css/sp-pickr.css', SHOPPRESS_VERSION );
		wp_enqueue_style( 'sp-admin-style', SHOPPRESS_URL . 'public/modules/variation-swatches/admin/css/sp-admin.css', SHOPPRESS_VERSION );

		$deps = array( 'jquery', 'jquery-ui-dialog', 'jquery-tiptip', 'wc-enhanced-select', 'select2' );
		wp_enqueue_media();
		wp_enqueue_script( 'sp-admin-script', SHOPPRESS_URL . 'public/modules/variation-swatches/admin/js/sp-admin.js', $deps, SHOPPRESS_VERSION, false );
		wp_enqueue_script( 'sp-admin-script-pickr', SHOPPRESS_URL . 'public/modules/variation-swatches/admin/js/sp-pickr.js', $deps, SHOPPRESS_VERSION, false );

		$sp_vs_var = array(
			'admin_url'         => admin_url(),
			'admin_path'        => plugins_url( '/', __FILE__ ),
			'ajaxurl'           => admin_url( 'admin-ajax.php' ),
			'ajax_banner_nonce' => wp_create_nonce( 'sp_vs_upgrade_notice' ),
			'placeholder_image' => SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/placeholder.svg',
			'upload_image'      => esc_url( SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/upload.svg' ),
			'remove_image'      => esc_url( SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/remove.svg' ),
		);

		wp_localize_script( 'sp-admin-script', 'sp_vs_var', $sp_vs_var );
	}

	/**
	 * Get Product attributes types
	 *
	 * @since 1.0.0
	 * @access public
	 * @return Array list of product attributes
	 */
	public function get_attribute_type( $taxonomy ) {
		foreach ( $this->attr_taxonomies as $tax ) {
			if ( 'pa_' . $tax->attribute_name == $taxonomy ) {
				return( $tax->attribute_type );
				break;
			}
		}
	}

	/**
	 * Add attribute fields to attributes
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $taxonomy the taxonomy slug
	 */
	public function add_attribute_fields( $taxonomy ) {
		$attribute_type = $this->get_attribute_type( $taxonomy );
		$this->product_attribute_fields( $taxonomy, $attribute_type, 'new', 'add' );
	}

	/**
	 * Add Fields to Attributes
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $taxonomy the taxonomy slug
	 * @param String $type the attribute type color, image, label
	 * @param String $value default values attribute
	 * @param String $form for save
	 */
	public function product_attribute_fields( $taxonomy, $type, $value, $form ) {
		switch ( $type ) {
			case 'color':
				$this->add_color_field( $value, $taxonomy );
				break;
			case 'image':
			case 'brand':
				$this->add_image_field( $value, $taxonomy );
				break;
			default:
				break;
		}
	}

	/**
	 * Add Field
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Onject $term attribute term
	 * @param String $value default values attribute
	 */
	public function edit_attribute_fields( $term, $taxonomy ) {
		$attribute_type  = $this->get_attribute_type( $taxonomy );
		$term_fields     = array();
		$term_type_field = get_term_meta( $term->term_id, 'product_' . $taxonomy, true );
		$term_fields     = array(
			'term_type_field' => $term_type_field ? $term_type_field : '',
		);
		$this->product_attribute_fields( $taxonomy, $attribute_type, $term_fields, 'edit' );
	}

	/**
	 * Text Field
	 *
	 * @since 1.0.0
	 * @access private
	 * @param String $value default values attribute
	 * @param String $taxonomy the taxonomy slug
	 */
	private function add_color_field( $value, $taxonomy ) {
		$term_type_field = is_array( $value ) && $value['term_type_field'] ? $value['term_type_field'] : '';
		$label           = __( 'Color', 'shop-press' );

		if ( $value == 'new' ) {

				/**
				 * @param array|string $value
				 * @param string $taxonomy
				 * @param string $term_type_field
				 *
				 * @since 1.4.0
				 */
				do_action( 'shoppress/variation_swatches/admin/terms_field/before_color', $value, $taxonomy, $term_type_field );
			?>

			<div class="sp-types gbl-attr-color gbl-attr-terms gbl-attr-terms-new">
				<label><?php echo esc_html( $label ); ?></label>
				<div class="sp_vs_settings_fields_form thwvs-col-div">
					<input type="hidden" name="<?php echo 'product_' . esc_attr( $taxonomy ); ?>[0]" autocomplete="off" class="thpladmin-colorpick"/>
					<div class="sp-pickr">
						<div class="pickr-btn sp-1"></div>
					</div>
				</div>
			</div>

			<?php
				/**
				 * @param array|string $value
				 * @param string $taxonomy
				 * @param string $term_type_field
				 *
				 * @since 1.4.0
				 */
				do_action( 'shoppress/variation_swatches/admin/terms_field/after_color', $value, $taxonomy, $term_type_field );
		} else {

			$term_type_field = is_array( $term_type_field ) ? $term_type_field : array( 0 => $term_type_field );
			/**
			 * @param array|string $value
			 * @param string $taxonomy
			 * @param string $term_type_field
			 *
			 * @since 1.4.0
			 */
			do_action( 'shoppress/variation_swatches/admin/terms_field/before_color', $value, $taxonomy, $term_type_field );
			?>
			<tr class="gbl-attr-terms gbl-attr-terms-edit" >
				<th><?php echo esc_html( $label ); ?></th>
				<td>
					<div class="sp_vs_settings_fields_form thwvs-col-div">
						<input type="hidden"  name= "<?php echo 'product_' . esc_attr( $taxonomy ); ?>[0]" autocomplete="off" class="thpladmin-colorpick" value="<?php echo esc_attr( $term_type_field[0] ?? '' ); ?>"/>
						<div class="sp-pickr">
							<div class="pickr-btn sp-2"></div>
						</div>
					</div>
				</td>
			</tr>
			<?php
			/**
			 * @param array|string $value
			 * @param string $taxonomy
			 * @param string $term_type_field
			 *
			 * @since 1.4.0
			 */
			do_action( 'shoppress/variation_swatches/admin/terms_field/after_color', $value, $taxonomy, $term_type_field );
		}
	}

	/**
	 * Image Field
	 *
	 * @since 1.0.0
	 * @access private
	 * @param String $value default values attribute
	 * @param String $taxonomy the taxonomy slug
	 */
	private function add_image_field( $value, $taxonomy ) {
		$image = is_array( $value ) && $value['term_type_field'] ? wp_get_attachment_image_src( $value['term_type_field'] ) : '';
		$image = $image ? $image[0] : SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/placeholder.svg';
		$label = __( 'Image', 'shop-press' );

		if ( $value == 'new' ) {
			?>
			<div class="sp-types gbl-attr-img gbl-attr-terms gbl-attr-terms-new">
				<div class='sp-upload-image'>
					<label><?php echo esc_html( $label ); ?></label>
					<div class="tawcvs-term-image-thumbnail">
						<img class="i_index_media_img" src="<?php echo ( esc_url( $image ) ); ?>" width="50px" height="50px" alt="term-image"/>  						</div>
					<div style="line-height:60px;">
						<input type="hidden" class="i_index_media" name="product_<?php echo esc_attr( $taxonomy ); ?>" value="">

						<button type="button" class="sp-upload-image-button button " onclick="sp_vs_upload_icon_image( this,event )">
							<span class="sp-upload-button"><?php echo __( 'Upload', 'shop-press' ); ?></span>
						</button>

						<button type="button" style="display:none" class="sp_vs_remove_image_button button " onclick="sp_vs_remove_icon_image( this,event )">
							<span class="sp-remove-button"><?php echo __( 'Remove', 'shop-press' ); ?></span>
						</button>
					</div>
				</div>
			</div>
			<?php

		} else {
			?>
			<tr class="form-field gbl-attr-img gbl-attr-terms gbl-attr-terms-edit">
				<th><?php echo esc_html( $label ); ?></th>
				<td>
					<div class = 'sp-upload-image'>
						<div class="tawcvs-term-image-thumbnail">
							<img  class="i_index_media_img" src="<?php echo ( esc_url( $image ) ); ?>" width="50px" height="50px" alt="term-image"/>  							</div>
						<div style="line-height:60px;">
							<input type="hidden" class="i_index_media"  name= "product_<?php echo esc_attr( $taxonomy ); ?>" value="">

							<button type="button" class="sp-upload-image-button  button" onclick="sp_vs_upload_icon_image( this,event )">
								<span class="sp-upload-button"><?php echo __( 'Upload', 'shop-press' ); ?></span>
							</button>

							<button type="button" style="<?php echo ( is_array( $value ) && $value['term_type_field'] ? '' : 'display:none' ); ?> "  class="sp_vs_remove_image_button button " onclick="sp_vs_remove_icon_image( this,event )">
								<span class="sp-remove-button"><?php echo __( 'Remove', 'shop-press' ); ?></span>
							</button>
						</div>
					</div>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Save Term Metadata
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Integer $term_id term ID
	 * @param Integer $tt_id the taxonomy term ID
	 * @param String  $taxonomy the taxonomy slug
	 */
	public function save_term_meta( $term_id, $tt_id, $taxonomy ) {

		if ( isset( $_POST[ 'product_' . $taxonomy ] ) && ! empty( $_POST[ 'product_' . $taxonomy ] ) ) {
			update_term_meta( $term_id, 'product_' . $taxonomy, wc_clean( wp_unslash( $_POST[ 'product_' . $taxonomy ] ) ) );
		} else {
			update_term_meta( $term_id, 'product_' . $taxonomy, '' );
		}
	}

	/**
	 * Add column to preview swatches value
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Array Term columns
	 */
	public function add_attribute_column( $columns ) {
		$new_columns          = array();
		$taxonomy             = sanitize_text_field( $_REQUEST['taxonomy'] );
		$new_columns['thumb'] = $this->get_attribute_type( $taxonomy );
		$types                = array( 'color', 'image' );

		if ( in_array( $this->get_attribute_type( $taxonomy ), $types ) ) {

			if ( isset( $columns['cb'] ) ) {
				$new_columns['cb'] = $columns['cb'];
				unset( $columns['cb'] );
			}

			$columns = array_merge( $new_columns, $columns );
		}

			return $columns;
	}

	/**
	 * Add Swatches to terms tabel
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Array  $columns terms columns
	 * @param String $column column slug
	 * @param String $term_id term ID
	 */
	public function add_attribute_column_content( $columns, $column, $term_id ) {

		$taxonomy  = sanitize_text_field( $_REQUEST['taxonomy'] );
		$attr_type = $this->get_attribute_type( $taxonomy );

		$value = get_term_meta( $term_id, 'product_' . $taxonomy, true );

		switch ( $attr_type ) {
			case 'color':
				$value = is_array( $value ) ? $value : array( 0 => $value );
				$c1    = $value[0];
				$c2    = apply_filters( 'shoppress/variation_swatches/attribute/color/color2', $c1, $value, $term_id );
				$c1    = "{$c1} 0%, {$c1} 50%";
				$c2    = "{$c2} 50%, {$c2} 100%";
				$deg   = '315deg';
				printf( '<span style="width: 50px; height: 50px; display: inline-block;background:linear-gradient(%s,%s,%s);"></span>', esc_attr( $deg ), esc_attr( $c1 ), esc_attr( $c2 ) );
				break;

			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/placeholder.png';
				printf( '<img src="%s" width="44px" height="44px" alt="preview-image">', esc_url( $image ) );
				break;
		}
	}

	/**
	 * Render Select option product custom swatches
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Object  $attribute_taxonomy Product Attribute taxonomy
	 * @param Integer $i ID
	 */
	public function product_option_terms( $attribute_taxonomy, $i ) {

		if ( 'select' !== $attribute_taxonomy->attribute_type ) {
			global $post, $thepostid, $product_object;
			$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );

			$product_id = $thepostid;
			if ( is_null( $thepostid ) && isset( $_POST['post_id'] ) ) {
				$product_id = absint( $_POST['post_id'] );
			}

			?>
			<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'shop-press' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
				<?php
					$args      = array(
						'orderby'    => 'name',
						'hide_empty' => 0,
					);
					$all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );

					if ( $all_terms ) :
						$options = array();
						foreach ( $all_terms as $key ) {
							$options[] = $key->term_id;
						}

						foreach ( $all_terms as $term ) :

							$options = ! empty( $options ) ? $options : array();

							echo '<option value="' . esc_attr( $term->term_id ) . '" ' . wc_selected( has_term( absint( $term->term_id ), $taxonomy, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
						endforeach;
					endif;
					?>
			</select>

			<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'shop-press' ); ?></button>
			<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'shop-press' ); ?></button>

			<?php
			$taxonomy  = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
			$attr_type = $attribute_taxonomy->attribute_type;

			if ( ( $attribute_taxonomy->attribute_type == 'label' || $attribute_taxonomy->attribute_type == 'image' || $attribute_taxonomy->attribute_type == 'color' ) ) {
				?>
				<button class="button fr plus sp_vs_add_new_attribute"  data-attr_taxonomy="<?php echo esc_attr( $taxonomy ); ?>"  data-attr_type="<?php echo esc_attr( $attr_type ); ?>"  data-dialog_title="<?php printf( esc_html__( 'Add new %s', '' ), esc_attr( $attribute_taxonomy->attribute_label ) ); ?>">
					<?php esc_html_e( 'Add new', '' ); ?>
				</button>
				<?php

			} else {
				?>
				<button class="button fr plus add_new_attribute">
					<?php esc_html_e( 'Add new', 'shop-press' ); ?>
				</button>
				<?php
			}
		}
	}

	/**
	 * Adds swatches tab settings
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Array $tabs Product data configuration
	 */
	public function woo_data_setting_swatches_tab( $tabs ) {
		$tabs['thwvs_swatches_settings'] = array(
			'label'    => __( 'Swatches Settings', 'shop-press' ),
			'target'   => 'thwvs-product-attribute-settings',
			'priority' => 65,
			'class'    => array(
				'variations_tab',
				'show_if_variable',
			),
		);
		return $tabs;
	}

	/**
	 * Add setting fields to swatches tab settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function output_custom_swatches_settings() {

		global $post, $thepostid, $product_object, $wc_product_attributes;

		$saved_settings = get_post_meta( $thepostid, 'th_custom_attribute_settings', true );

		$type_options = array(
			'select' => __( 'Select', 'shop-press' ),
			'color'  => __( 'Color', 'shop-press' ),
			'label'  => __( 'Label', 'shop-press' ),
			'image'  => __( 'Image', 'shop-press' ),
			'brand'  => __( 'Brand', 'shop-press' ),
		);

		?>
		<div id="thwvs-product-attribute-settings" class="panel wc-metaboxes-wrapper hidden">
			<div id="custom_variations_inner">
				<h2><?php esc_html_e( 'Custom Attribute Settings', 'shop-press' ); ?></h2>

				<?php
				$attributes           = $product_object->get_attributes();
				$i                    = -1;
				$has_custom_attribute = false;

				foreach ( $attributes as $attribute ) {
					$attribute_name = sanitize_title( $attribute->get_name() );
					$type           = '';
					++$i;
						$has_custom_attribute = true;
						$is_taxonomy          = false !== $attribute->is_taxonomy() ? true : false;
					?>
					<div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox closed" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">

						<h3>
							<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'shop-press' ); ?>"></div>
							<strong class="attribute_name"><?php echo wc_attribute_label( $attribute_name ); ?></strong>
						</h3>
						<div class="sp_vs_custom_attribute wc-metabox-content  <?php echo 'thwvs-' . esc_attr( $attribute_name ); ?> hidden">
							<table cellpadding="0" cellspacing="0">
								<tbody>
									<?php if ( ! $is_taxonomy ) : ?>
										<tr>
											<td colspan="2">
												<p class="form-row form-row-full ">
													<label for="custom_attribute_type"><?php esc_html_e( 'Swatch Type', 'shop-press' ); ?></label>
													<span class="woocommerce-help-tip" data-tip=" Determines how this custom attribute's values are displayed">
													</span>
													<select
														name="<?php echo( 'th_attribute_type_' . esc_attr( $attribute_name ) ); ?>"
														class="select short th-attr-select"
														value = ""
														onchange="sp_vs_change_term_type( this,event )"
													>
														<?php
														$type = $this->get_custom_fields_settings( $thepostid, $attribute_name, 'type' );

														foreach ( $type_options as $key => $value ) {
															$default = ( isset( $type ) && $type == $key ) ? 'selected' : '';
															?>
															<option value="<?php echo esc_attr( $key ); ?>" <?php echo $default; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> > <?php echo esc_html( $value ); ?> </option>
															<?php
														}
														?>
													</select>
												</p>
											</td>
										</tr>
									<?php else : ?>
										<input type="hidden" onchange="sp_vs_change_term_type( this,event )" name="<?php echo( 'th_attribute_type_' . esc_attr( $attribute_name ) ); ?>" value="<?php echo esc_attr( $attribute->get_taxonomy_object()->attribute_type ); ?>" />
										<script>
											jQuery(document).ready(function($){
												$('[name="<?php echo( 'th_attribute_type_' . esc_attr( $attribute_name ) ); ?>"]').trigger('change');
											});
										</script>
									<?php endif; ?>
									<tr> <th></th> </tr>

									<tr> <td> <?php $this->custom_attribute_settings_field( $attribute, $thepostid ); ?> </td> </tr>

								</tbody>
							</table>
						</div>
					</div>
						<?php
				}

				if ( ! $has_custom_attribute ) {
					?>
					<div class="inline notice woocommerce-message">

						<p>
						<?php
						esc_html_e( 'No custom attributes added yet.', 'woocommerce-product-variation-swatches' );
						esc_html_e( ' You can add custom attributes from the', 'woocommerce-product-variation-swatches' );
						?>
						<a onclick="spTriggerAttributeTab( this )" href="#woocommerce-product-data"><?php esc_html_e( ' Attributes', 'woocommerce-product-variation-swatches' ); ?> </a> <?php esc_html_e( 'tab', 'woocommerce-product-variation-swatches' ); ?></p>
					</div>
					<?php
				}
				?>

			</div>
		</div>
		<?php
	}

	/**
	 * Gets custom fields setting value
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $post_id the product ID
	 * @param String $attribute the attribute name
	 * @param String $term the attribute terms
	 * @param String $term_key the attribute term key
	 */
	public function get_custom_fields_settings( $post_id, $attribute = false, $term = false, $term_key = false ) {

		$saved_settings = get_post_meta( $post_id, 'th_custom_attribute_settings', true );

		if ( is_array( $saved_settings ) ) {
			if ( $attribute ) {
				if ( isset( $saved_settings[ $attribute ] ) ) {
					$attr_settings = $saved_settings[ $attribute ];

					if ( is_array( $attr_settings ) && $term ) {
						if ( $term === 'type' || $term === 'tooltip_type' || $term === 'radio-type' || $term === 'design_type' ) {
							$term_types = ( isset( $attr_settings[ $term ] ) ) ? $attr_settings[ $term ] : false;
							return $term_types;
						} else {
							$term_settings = isset( $attr_settings[ $term ] ) ? $attr_settings[ $term ] : '';
							if ( is_array( $term_settings ) && $term_key ) {
								$settings_value = isset( $term_settings[ $term_key ] ) ? $term_settings[ $term_key ] : '';
								return $settings_value;
							} else {
								return false;
							}
							return $term_settings;
						}
					}
					return $attr_settings;
				}
				return false;
			}
			return $saved_settings;
		} else {
			return false;
		}
	}

	/**
	 * Add setting fields to swatches tab settings
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $post_id the product ID
	 * @param String $attribute the attribute name
	 * @param String $term the attribute terms
	 * @param String $term_key the attribute term key
	 */
	public function custom_attribute_settings_field( $attribute, $post_id ) {
		$attribute_name = sanitize_title( $attribute->get_name() );
		$type           = $this->get_custom_fields_settings( $post_id, $attribute_name, 'type' );
		$this->output_field_image( $type, $attribute, $post_id );
		$this->output_field_color( $type, $attribute, $post_id );
	}

	/**
	 * Image field output
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $type type for display
	 * @param Object $attribute Custom attribute object
	 * @param String $post_id the product ID
	 */
	public function output_field_image( $type, $attribute, $post_id ) {
		$attribute_name = sanitize_title( $attribute->get_name() );
		$display_status = ( $type == 'image' || $type == 'brand' ) ? 'display:table' : 'display: none';
		$is_taxonomy    = false !== $attribute->is_taxonomy() ? true : false;
		?>
		<table class="sp-custom-table sp-custom-table-image" style="<?php echo esc_attr( $display_status ); ?>">
		<?php
			$i = 0;
		foreach ( $attribute->get_options() as $term ) {
			$term_object = $is_taxonomy ? get_term( $term ) : false;
			$term_title  = $term_object ? $term_object->name : $term;
			$css         = $i == 0 ? 'display:table-row-group' : '';
			$open        = $i == 0 ? 'open' : '';
			?>
				<tr class="sp-term-name">
					<td colspan="2">
						<h3 class="sp-local-head <?php echo esc_attr( $open ); ?>" data-term_name="<?php echo esc_attr( $term ); ?>" onclick="sp_vs_open_body( this,event )"><?php echo esc_html( $term_title ); ?></h3>
						<table class="sp-local-body-table">
							<tbody class="sp-local-body sp-local-body-<?php echo esc_attr( $term ); ?>" style="<?php echo $css; ?>">
								<tr>
									<td width="30%"><?php esc_html_e( 'Term Name', 'shop-press' ); ?></td>
									<td width="70%"><?php echo esc_html( $term_title ); ?></td>
								</tr>
								<tr class="form-field"> <td><?php _e( 'Term Image', 'shop-press' ); ?></td>
									<td>
									<?php
										$term_field = $this->get_custom_fields_settings( $post_id, $attribute_name, $term, 'term_value' );
										$term_field = ( $term_field ) ? $term_field : '';
										$image      = ( $type == 'image' || $type == 'brand' ) ? $this->get_custom_fields_settings( $post_id, $attribute_name, $term, 'term_value' ) : '';
										$image      = ( $image ) ? wp_get_attachment_image_src( $image ) : '';
										$remove_img = ( $image ) ? 'display:inline' : 'display:none';
										$image      = $image ? $image[0] : SHOPPRESS_URL . 'public/modules/variation-swatches/admin/images/placeholder.svg';
									?>

										<div class ="sp-upload-image">
											<div class="tawcvs-term-image-thumbnail" style="float:left;margin-right:10px;">
												<img  class="i_index_media_img" src="<?php echo ( esc_url( $image ) ); ?>" width="60px" height="60px" alt="term-image"/>  												</div>
												<div style="line-height:30px;">
													<input type="hidden" class="i_index_media"  name= "<?php echo esc_attr( sanitize_title( 'image_' . $attribute_name . '_term_' . $term ) ); ?>" value="<?php echo $term_field; ?>">
													<button type="button" class="sp-upload-image-button button " onclick="sp_vs_upload_icon_image( this,event )">
														<span class="sp-upload-button"><?php echo __( 'Upload', 'shop-press' ); ?></span>
													</button>
													<button type="button" style="<?php echo $remove_img; ?>" class="sp_vs_remove_image_button button " onclick="sp_vs_remove_icon_image( this,event )">
														<span class="sp-remove-button"><?php echo __( 'Remove', 'shop-press' ); ?></span>
													</button>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>

				<?php
				++$i;
		}
		?>

		</table>
		<?php
	}

	/**
	 * Color field output
	 *
	 * @since 1.0.0
	 * @access public
	 * @param String $type type for display
	 * @param Object $attribute Custom attribute object
	 * @param String $post_id the product ID
	 */
	public function output_field_color( $type, $attribute, $post_id ) {

		$attribute_name = sanitize_title( $attribute->get_name() );
		$display_status = $type == 'color' ? 'display: table' : 'display: none';
		$is_taxonomy    = false !== $attribute->is_taxonomy() ? true : false;
		?>
		<table class="sp-custom-table sp-custom-table-color" style="<?php echo $display_status; ?>">
			<?php
			$i = 0;
			foreach ( $attribute->get_options() as $term ) {
				$term_object = $is_taxonomy ? get_term( $term ) : false;
				$term_title  = $term_object ? $term_object->name : $term;
				$css         = $i == 0 ? 'display:table-row-group' : '';
				$open        = $i == 0 ? 'open' : '';
				?>
				<tr class="sp-term-name">
					<td colspan="2">
						<h3 class="sp-local-head <?php echo $open; ?>" data-term_name="<?php echo esc_attr( $term ); ?>" onclick="sp_vs_open_body( this,event )"><?php echo esc_html( $term_title ); ?></h3>
						<table class="sp-local-body-table">
							<tbody class="sp-local-body sp-local-body-<?php echo $term; ?>" style="<?php echo $css; ?>">
								<tr>
									<td width="30%"><?php esc_html_e( 'Term Name', 'shop-press' ); ?></td>
									<td width="70%"><?php echo esc_html( $term_title ); ?></td>
								</tr>
								<?php
								$color_type = $this->get_custom_fields_settings( $post_id, $attribute_name, $term, 'color_type' );
								$color_type = $color_type ? $color_type : '';
								?>

								<tr>
									<td><?php esc_html_e( 'Term Color', 'shop-press' ); ?></td>
									<td class = "th-custom-attr-color-td">
									<?php
										$term_field = $type == 'color' ? $this->get_custom_fields_settings( $post_id, $attribute_name, $term, 'term_value' ) : '';
										$term_field = is_array( $term_field ) ? $term_field : array( 0 => $term_field );
									?>

										<div class="sp_vs_settings_fields_form thwvs-col-div" style="margin-bottom: 5px">
											<input type="hidden" name= "<?php echo esc_attr( sanitize_title( 'color_' . $attribute_name . '_term_' . $term ) ); ?>[0]" autocomplete="off" class="thpladmin-colorpick" value="<?php echo esc_attr( $term_field[0] ?? '' ); ?>" style="width:250px;"/>
											<div class="sp-pickr">
												<div class="pickr-btn sp-3"></div>
											</div>
										</div>
										<div class="sp_vs_settings_fields_form thwvs-col-div" style="margin-bottom: 5px">
											<input type="hidden" name= "<?php echo esc_attr( sanitize_title( 'color_' . $attribute_name . '_term_' . $term ) ); ?>[1]" autocomplete="off" class="thpladmin-colorpick" value="<?php echo esc_attr( $term_field[1] ?? '' ); ?>" style="width:250px;"/>
											<div class="sp-pickr">
												<div class="pickr-btn sp-3"></div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<?php
				++$i;
			}
			?>
		</table>
		<?php
	}

	/**
	 * Save custom swatches field
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Intger $post_id Product ID
	 * @param Object $post Product Object
	 */
	public function save_custom_fields( $post_id, $post ) {

		$product             = wc_get_product( $post_id );
		$local_attr_settings = array();

		foreach ( $product->get_attributes() as $attribute ) {

			$attr_settings         = array();
			$attr_name             = sanitize_title( $attribute->get_name() );
			$type_key              = 'th_attribute_type_' . $attr_name;
			$attr_settings['type'] = isset( $_POST[ $type_key ] ) ? sanitize_text_field( $_POST[ $type_key ] ) : '';

			$tt_key                        = sanitize_title( 'th_tooltip_type_' . $attr_name );
			$attr_settings['tooltip_type'] = isset( $_POST[ $tt_key ] ) ? sanitize_text_field( $_POST[ $tt_key ] ) : '';

			$design_type_key              = sanitize_title( 'th_attribute_design_type_' . $attr_name );
			$attr_settings['design_type'] = isset( $_POST[ $design_type_key ] ) ? sanitize_text_field( $_POST[ $design_type_key ] ) : '';

			if ( $attr_settings['type'] == 'radio' ) {
				$radio_style_key             = sanitize_title( $attr_name . '_radio_button_style' );
				$attr_settings['radio-type'] = isset( $_POST[ $radio_style_key ] ) ? sanitize_text_field( $_POST[ $radio_style_key ] ) : '';
			} else {
				$term_settings = array();
				foreach ( $attribute->get_options() as $term ) {
					$term_settings['name'] = $term;

					if ( $attr_settings['type'] == 'color' ) {
						$color_type_key              = sanitize_title( $attr_name . '_color_type_' . $term );
						$term_settings['color_type'] = isset( $_POST[ $color_type_key ] ) ? sanitize_text_field( $_POST[ $color_type_key ] ) : '';
					}

					$term_key = sanitize_title( $attr_settings['type'] . '_' . $attr_name . '_term_' . $term );
					if ( $attr_settings['type'] == 'color' ) {
						$term_value = isset( $_POST[ $term_key ] ) ? $_POST[ $term_key ] : array();
					} else {
						$term_value = isset( $_POST[ $term_key ] ) ? sanitize_text_field( $_POST[ $term_key ] ) : '';
					}
					$term_settings['term_value'] = $term_value;
					$attr_settings[ $term ]      = $term_settings;
				}
			}

			$local_attr_settings[ $attr_name ] = $attr_settings;
		}

		update_post_meta( $post_id, 'th_custom_attribute_settings', $local_attr_settings );
	}
}
