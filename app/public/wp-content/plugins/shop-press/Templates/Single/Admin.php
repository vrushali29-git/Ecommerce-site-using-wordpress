<?php
/**
 * Single component admin settings.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates\Single;

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates\Main as Templates;

class Admin {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( sp_get_template_settings( 'single', 'custom_single_template' ) ) {
			add_action( 'init', array( __CLASS__, 'product_templates_taxonomy' ) );
			add_action( 'save_post_product', array( __CLASS__, 'save_product' ), 10, 3 );
		}

		if ( sp_get_template_settings( 'single', 'templates_by_category' ) && is_admin() ) {
			add_action( 'product_cat_add_form_fields', array( __CLASS__, 'product_taxonomy_custom_metadata' ), 10, 2 );
			add_action( 'product_cat_edit_form_fields', array( __CLASS__, 'product_taxonomy_custom_metadata' ), 10, 2 );
			add_action( 'edited_product_cat', array( __CLASS__, 'save_product_taxonomy_custom_metadata' ), 10, 3 );
			add_action( 'create_product_cat', array( __CLASS__, 'save_product_taxonomy_custom_metadata' ), 10, 3 );
			add_filter( 'manage_edit-product_cat_columns', array( __CLASS__, 'product_cat_columns' ) );
			add_filter( 'manage_product_cat_custom_column', array( __CLASS__, 'product_cat_column' ), 10, 3 );
		}
	}

	/**
	 * Product Custom Meta
	 *
	 * @since 1.0.0
	 */
	public static function product_taxonomy_custom_metadata( $term ) {
		$singles = Main::get_single_templates();
		$meta    = isset( $term->term_id ) ? get_term_meta( $term->term_id, 'single_product_template', true ) : '0';

		?>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="single_product_template"><?php _e( 'Single Product Template', 'shop-press' ); ?></label>
			</th>
			<td>
				<select name="single_product_template" id="single_product_template">
					<option value=""><?php echo __( 'Default', 'shop-press' ); ?></option>
					<?php foreach ( $singles as $key => $single ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $meta == $key ) ? 'selected' : ''; ?> ><?php echo esc_html( $single ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update Product Custom Meta
	 *
	 * @since 1.0.0
	 */
	public static function save_product_taxonomy_custom_metadata( $term_id ) {

		// if something is in the field
		if ( isset( $_POST['single_product_template'] ) ) {
			$term_meta = get_term_meta( $term_id );
			// Save the taxonomy value.
			update_term_meta( $term_id, 'single_product_template', sanitize_text_field( $_POST['single_product_template'] ) );
		}
	}

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 *
	 * @return void
	 */
	public static function product_cat_columns( $columns ) {
		$new_columns                            = array();
		$new_columns['cb']                      = $columns['cb'] ?? '';
		$new_columns['single_product_template'] = __( 'Single', 'shop-press' );
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 *
	 * @return void
	 */
	public static function product_cat_column( $columns, $column, $id ) {

		if ( $column == 'single_product_template' ) {
			$meta    = get_term_meta( $id, 'single_product_template', true );
			$columns = $meta ? get_the_title( $meta ) : __( 'Default', 'shop-press' );
		}

		return $columns;
	}


	/**
	 * Register taxonomy for product templates.
	 *
	 * @since 1.2.0
	 */
	public static function product_templates_taxonomy() {
		$labels = array(
			'name'          => 'Templates',
			'singular_name' => 'Template',
			'menu_name'     => 'Template',
			'all_items'     => 'All Templates',
		);
		$args   = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'public'       => true,
			'show_ui'      => true,
			'show_in_menu' => false,
			'meta_box_cb'  => array( __CLASS__, 'woo_products_templates' ),
		);

		register_taxonomy( 'woo_custom_single_templates', 'product', $args );
	}

	/**
	 * Select template metabox.
	 *
	 * @since 1.2.0
	 */
	public static function woo_products_templates( $post, $box ) {
		$templates = Main::get_single_templates();
		$meta      = get_post_meta( $post->ID, 'sp_woo_single_template', true );

		if ( $templates ) {
			echo '
			<select name="shoppress_woo_custom_templates" style="width: 100%; margin: 10px 0;">
			<option value="">' . esc_html( 'Default', 'shop-press' ) . '</option>';

			if ( '' !== $meta && isset( $meta['name'] ) ) {
				echo '<option value="' . esc_attr( $meta['name'] ) . '" selected>' . esc_attr( $meta['name'] ) . '</option>';
			}

			foreach ( $templates as $template ) {
				echo '<option value="' . esc_attr( $template ) . '">' . esc_attr( $template ) . '</option>';
			}
			echo '</select>';
		}
	}

	/**
	 * Gets the page id by title.
	 *
	 * @since 1.2.0
	 */
	public static function get_page_id( string $title ) {
		$page_id = '';

		$page = Templates::get_template_by_title( $title, 'shoppress_pages' );

		if ( $page ) {
			$page_id = $page->ID;
		}

		return $page_id;
	}

	/**
	 * Save template.
	 *
	 * @since 1.2.0
	 */
	public static function save_product( $post_id, $post ) {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$page = array();

		$template = isset( $_POST['shoppress_woo_custom_templates'] ) ? sanitize_text_field( $_POST['shoppress_woo_custom_templates'] ) : '';

		$page['name'] = $template;
		$page['id']   = static::get_page_id( $template );

		if ( isset( $template ) && '' !== $template ) {
			update_post_meta( $post_id, 'sp_woo_single_template', $page );
		} else {
			update_post_meta( $post_id, 'sp_woo_single_template', '' );
		}
	}
}
