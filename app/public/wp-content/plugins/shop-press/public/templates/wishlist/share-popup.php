<?php
/**
 * Wishlist share popup
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$share_url_patterns = sp_get_social_share_links();
$socials            = sp_get_module_settings( 'wishlist_general_settings', 'social_media' );
$socials            = is_array( $socials ) ? array_column( $socials, 'value' ) : array();

?>
<div id="sp-wishlist-share-popup" class="sp-popup-overlay" style="display:none;">
	<div class="sp-popup-content">
		<div class="sp-close-popup"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg></div>
		<h3 class="sp-popup-title"><?php esc_html_e( 'Share Wishlist', 'shop-press' ); ?></h3>
		<label class="sp-wishlist-link-wrapper">
			<input type="text" class="sp-wishlist-link" disabled>
			<svg class="sp-copy-to-clipboard" xmlns="http://www.w3.org/2000/svg" width="14.45" height="16" viewBox="0 0 14.45 16"><path id="copy" d="M13.85,5.1h.775A2.325,2.325,0,0,1,16.95,7.425v7.75A2.325,2.325,0,0,1,14.625,17.5h-6.2A2.325,2.325,0,0,1,6.1,15.175V14.4H5.325A2.325,2.325,0,0,1,3,12.075V4.325A2.325,2.325,0,0,1,5.325,2h6.2A2.325,2.325,0,0,1,13.85,4.325Zm-1.085,0V4.635a1.55,1.55,0,0,0-1.55-1.55H5.635a1.55,1.55,0,0,0-1.55,1.55v7.13a1.55,1.55,0,0,0,1.55,1.55H6.1V7.425A2.325,2.325,0,0,1,8.425,5.1ZM8.735,6.185a1.55,1.55,0,0,0-1.55,1.55v7.13a1.55,1.55,0,0,0,1.55,1.55h5.58a1.55,1.55,0,0,0,1.55-1.55V7.735a1.55,1.55,0,0,0-1.55-1.55Z" transform="translate(-2.75 -1.75)" fill="#bcc4ce" stroke="#bcc4ce" stroke-width="0.5" fill-rule="evenodd"/></svg>
		</label>
		<ul class="sp-wishlist-share-links">
			<?php
			foreach ( $share_url_patterns as $item ) :
				if ( ! in_array( $item['social'], $socials ) ) {
					continue;
				}
				?>
				<li class="sp-wishlist-share-link <?php echo esc_attr( $item['social'] ); ?>">
					<a href="#" data-pattern="<?php echo esc_attr( $item['url'] ); ?>" target="_blank">
						<div class="sp-wishlist-share-icon">
							<i><?php echo $item['icon']; ?></i>
						</div>
						<div class="sp-wishlist-share-label">
							<?php
								echo esc_html(
									sprintf(
										__( 'Share on %s', 'shop-press' ),
										__( ucfirst( $item['title'] ), 'shop-press' )
									)
								);
							?>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
