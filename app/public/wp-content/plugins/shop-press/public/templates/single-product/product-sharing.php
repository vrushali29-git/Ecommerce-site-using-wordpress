<?php
/**
 * Product Sharing.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
	return;
}

$product_url        = get_permalink( $product->get_id() );
$product_name       = $product->get_title();
$share_url_patterns = sp_get_social_share_links();
$URLs               = array();
foreach ( $share_url_patterns as $social_id => $social ) {

	$social_share_link  = $social['url'];
	$social_share_link  = str_replace( '{title}', $product_name, $social_share_link );
	$social_share_link  = str_replace( '{url}', $product_url, $social_share_link );
	$URLs[ $social_id ] = $social_share_link;
}

?>
<div class="sp-sharing">
	<div class="sp-sharing-button">
		<?php if ( 'label' === $args['type'] ) : ?>
			<?php if ( ! empty( $args['label'] ) ) { ?>
				<span class="sp-sharing-label"><?php echo wp_kses( $args['label'], wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>

		<?php if ( 'icon' === $args['type'] ) : ?>
			<span class="sp-sharing-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
					<g id="Icons_Small_Heart" data-name="Icons / Small / Heart" transform="translate(0 0)">
						<path id="Icons_Small_Share" data-name="Icons / Small / Share" d="M14.5,18A3.5,3.5,0,0,1,11,14.5c0-.091.03-.3.053-.469.011-.077.02-.144.025-.186L6.238,11.7A3.918,3.918,0,0,1,3.5,13a3.5,3.5,0,1,1,0-7A3.855,3.855,0,0,1,6.1,7.156l5.083-2.718c-.012-.054-.028-.115-.044-.181A3.665,3.665,0,0,1,11,3.5,3.5,3.5,0,1,1,14.5,7a4.31,4.31,0,0,1-2.632-1.259L6.863,8.554A6.266,6.266,0,0,1,7,9.5c0,.184-.045.713-.073.895l4.747,2.038A3.974,3.974,0,0,1,14.5,11a3.5,3.5,0,1,1,0,7Zm0-5.5a2,2,0,1,0,2,2A2,2,0,0,0,14.5,12.5Zm-11-5a2,2,0,1,0,2,2A2,2,0,0,0,3.5,7.5Zm11-6a2,2,0,1,0,2,2A2,2,0,0,0,14.5,1.5Z" transform="translate(0 0)" fill="#959ca7"/>
					</g>
				</svg>
			</span>
		<?php endif; ?>

		<?php if ( 'icon-label' === $args['type'] ) : ?>
			<span class="sp-sharing-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
					<g id="Icons_Small_Heart" data-name="Icons / Small / Heart" transform="translate(0 0)">
						<path id="Icons_Small_Share" data-name="Icons / Small / Share" d="M14.5,18A3.5,3.5,0,0,1,11,14.5c0-.091.03-.3.053-.469.011-.077.02-.144.025-.186L6.238,11.7A3.918,3.918,0,0,1,3.5,13a3.5,3.5,0,1,1,0-7A3.855,3.855,0,0,1,6.1,7.156l5.083-2.718c-.012-.054-.028-.115-.044-.181A3.665,3.665,0,0,1,11,3.5,3.5,3.5,0,1,1,14.5,7a4.31,4.31,0,0,1-2.632-1.259L6.863,8.554A6.266,6.266,0,0,1,7,9.5c0,.184-.045.713-.073.895l4.747,2.038A3.974,3.974,0,0,1,14.5,11a3.5,3.5,0,1,1,0,7Zm0-5.5a2,2,0,1,0,2,2A2,2,0,0,0,14.5,12.5Zm-11-5a2,2,0,1,0,2,2A2,2,0,0,0,3.5,7.5Zm11-6a2,2,0,1,0,2,2A2,2,0,0,0,14.5,1.5Z" transform="translate(0 0)" fill="#959ca7"/>
					</g>
				</svg>
			</span>
			<?php if ( ! empty( $args['label'] ) ) { ?>
				<span class="sp-sharing-label"><?php echo wp_kses( $args['label'], wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>
	</div>
	<div class="sp-sharing-dropdown">
		<ul>
			<?php
			if ( $args['links'] ) {
				foreach ( $args['links'] as $link ) {
					?>
							<li class="sp-sharing-item <?php esc_attr_e( $link['social_class'] ); ?>">
								<a href="<?php echo esc_url( $URLs[ $link['social_item'] ] ); ?>">
								<?php echo wp_kses_post( sp_render_icon( $link['icon'] ?? '', array( 'aria-hidden' => 'true' ) ) ); ?>
								<?php esc_html_e( $link['title'] ); ?>
								</a>
							</li>
						<?php
				}
			}
			?>
		</ul>
	</div>
</div>
