<?php
/**
 * Product Review.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

if ( get_option( 'default_comment_status' ) !== 'open' ) {
	return;
}

$custom_heading = $args['custom_heading'] ?? false;
$review_heading = $args['review_heading'] ?? '';
$html_tag       = $args['heading_tag'] ?? 'h4';
?>

<div class="sp-reviews-wrapper">
	<?php
	if ( $custom_heading ) {
		?>
		<<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?> class="sp-reviews-heading">
			<?php echo esc_html( $review_heading ); ?>
		</<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?>>
		<?php
	}

		comments_template();
	?>
</div>
