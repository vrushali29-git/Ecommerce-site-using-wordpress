<?php
/**
 * Ajax Search.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$terms  = get_terms( 'product_cat' );
$is_cat = $args['cat'] === 'yes' ? 'sp-ajax-cat' : '';
?>

<div id="sp-ajax-search-warp" class="<?php esc_attr_e( $is_cat ); ?>">
	<?php if ( $args['cat'] === 'yes' ) : ?>
		<select name="cat">
			<option value=""><?php esc_html_e( $args['c_text'] ); ?></option>
			<?php foreach ( $terms as $key => $term ) : ?>

				<?php if ( 'Uncategorized' === $term->name ) : ?>
					<?php continue; ?>
				<?php endif; ?>

				<option value="<?php esc_attr_e( $term->term_id ); ?>"><?php esc_html_e( $term->name ); ?></option>
			<?php endforeach; ?>
		</select>
	<?php endif; ?>
	<div class="sp-ajax-search">
		<form action="#">
			<input type="text" class="sp-ajax-search-input" name="search" placeholder="<?php esc_attr_e( $args['s_placeholder'] ); ?>">
		</form>
	</div>
	<div class="sp-ajax-search-result" data-limit="<?php esc_attr_e( $args['limit'] ); ?>"></div>
</div>
