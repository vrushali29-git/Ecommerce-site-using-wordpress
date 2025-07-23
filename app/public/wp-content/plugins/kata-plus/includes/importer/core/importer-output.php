<?php
/**
 * Importer Output.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$importer      = new Kata_Plus_Importer();
$demos         = $importer->demos();
$cats          = '';
$cat_terms     = array();
$get_terms_cat = $get_terms_tag = false;
$demos_categories_in18 = array(
	__( 'Business', 'kata-plus' ),
	__( 'Design', 'kata-plus' ),
	__( 'Food', 'kata-plus' ),
	__( 'Health', 'kata-plus' ),
	__( 'Magazine', 'kata-plus' ),
	__( 'Personal', 'kata-plus' ),
	__( 'Software', 'kata-plus' ),
	__( 'pro', 'kata-plus' ),
	__( 'fast', 'kata-plus' ),
	__( 'free', 'kata-plus' ),
);

?>
<div id="kata-importer-wrapper" class="kata-importer-wrapper">
	<div class="kata-container">
		<div class="kata-row">
			<div class="kata-col-sm-12">
				<div class="kata-demo-importer-search-tools">
					<div class="kata-logo"><img src="<?php echo esc_url( Kata_Plus::$assets . 'images/admin/kata-icon.svg' ); ?>" alt="Kata"></div>
					<div class="kata-demo-importer-cats-and-search">
						<div class="demo-categories">
							<select name="demo-categories" id="demo-categories">
								<option value="<?php echo esc_attr( 'all' ); ?>"><?php echo __( 'All Categories', 'kata-plus' ); ?></option>
								<?php
								/**
								 * Terms
								 */
								if ( is_object( $demos ) ) {
									foreach ( $demos as $key => $value ) {
										if ( ! $get_terms_cat ) {
											foreach ( $value->cat_terms as $cat_term ) {
												?>
												<option value="<?php echo esc_attr( str_replace( ' ', '-', strtolower( $cat_term->name ) ) ); ?>">
													<?php echo esc_html__( $cat_term->name, 'kata-plus' ); ?>
												</option>
												<?php
											}
											$get_terms_cat = true;
											break;
										}
									}
								}
								?>
							</select>
						</div>
						<div class="kata-demo-importer-search-box">
							<input type="text" placeholder="<?php echo esc_attr__( 'Search Demo...', 'kata-plus' ); ?>">
							<?php echo Kata_Plus_Helpers::get_icon( '', 'font-awesome/search', '', 'data-show="false"' ); ?>
						</div>
						<div class="kata-demos-demo-type-wrapper">
							<ul class="kata-demotypes">
								<li class="demotypeitem active" value="all"><?php echo __( 'All', 'kata-plus' ); ?><span>0</span></li>
								<li class="demotypeitem" value="<?php echo esc_attr( 'pro' ); ?>"><?php echo __( 'Pro', 'kata-plus' ); ?><span>0</span></li>
								<li class="demotypeitem" value="<?php echo esc_attr( 'fast' ); ?>"><?php echo __( 'Fast', 'kata-plus' ); ?><span>0</span></li>
								<li class="demotypeitem" value="<?php echo esc_attr( 'free' ); ?>"><?php echo __( 'Free', 'kata-plus' ); ?><span>0</span></li>
							</ul>
						</div>
					</div>
					<div class="kata-demo-importer-wish-list" data-show="false">
						<i class="kata-icon" data-show="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.419c-2.826-5.695-11.999-4.064-11.999 3.27 0 7.27 9.903 10.938 11.999 15.311 2.096-4.373 12-8.041 12-15.311 0-7.327-9.17-8.972-12-3.27z"/></svg></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="kata-container">
		<div class="kata-row">
			<?php
			if ( $demos ) {
				/**
				 * Demos
				 */
				foreach ( $demos as $key => $value ) {
					$key       = $value->key;
					$is_pro    = $value->is_pro ? 'pro' : 'free';
					$is_hidden = $value->is_hidden;

					if ( ! $is_hidden ) {
						if ( $value->categories ) {
							foreach ( $value->categories as $categories ) {
								$cats .= str_replace( ' ', '-', $categories->name ) . ' ';
							}
						}
						if ( $value->tags ) {
							foreach ( $value->tags as $tag ) {
								$cats .= $tag->name . ' ';
							}
						}
						$cats        = rtrim( $cats, ' ' );
						$value       = $value->data;
						$e_con     = isset( $value->e_container ) && $value->e_container == 'on' ? 'true' : 'false';
						$websitetype = isset( $cats ) && ! empty( $cats ) ? strtolower( $cats ) : '';
						if ( Kata_Plus_Helpers::string_is_contain( $websitetype, 'fast' ) ) {
							$is_pro = 'fast';
						}
						$image         = $value->images[0] ? $value->images[0] : get_template_directory_uri() . '/screenshot.png';
						$kata_options  = get_option( 'kata_options' );
						$imported_demo = isset( $kata_options['imported_demos'] ) && in_array( $key, $kata_options['imported_demos'] ) ? __( 'This demo was already imported', 'kata-plus' ) : '';
						?>
						<div class="kata-col-sm-4 kata-importer" data-econ="<?php echo esc_attr( $e_con ); ?>" data-wishlist="false" website-type="all <?php echo esc_attr( $websitetype ); ?>" demo-name="<?php echo esc_attr( strtolower( $value->name ) ); ?>" import-status="<?php echo esc_attr( $imported_demo ); ?>" demo-screenshot="<?php echo esc_attr( $image ); ?>" data-key="<?php echo esc_attr( $key ); ?>" demo-type="<?php echo esc_attr( $is_pro ); ?>">
							<div class="kata-demp-wrapper">
								<div class="kata-demo-addto-wishlist"> <i class="kata-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.419c-2.826-5.695-11.999-4.064-11.999 3.27 0 7.27 9.903 10.938 11.999 15.311 2.096-4.373 12-8.041 12-15.311 0-7.327-9.17-8.972-12-3.27z"/></svg></i> </div>
								<img class="kata-importer-screenshot lozad" data-src="<?php echo esc_url( $image ); ?>">
								<a class="kata-importer-preview" href="<?php echo esc_url( @$value->preview_url ); ?>" target="_blank"><?php echo esc_attr__( 'Preview', 'kata-plus' ); ?></a>
								<h3 class="kata-importer-name">
									<span class="demo-type <?php echo esc_attr( $is_pro ); ?>"><?php echo esc_html__( $is_pro, 'kata-plus' ); ?></span>
									<?php echo esc_html( $value->name ); ?>
								</h3>
								<button class="kata-btn kata-btn-importer"><?php echo esc_html__( 'Import', 'kata-plus' ); ?></button>
								<button class="kata-btn kata-btn-xd" title="<?php echo esc_attr__( 'Download .XD File', 'kata-plus' ); ?>"><?php echo esc_html__( '.XD', 'kata-plus' ); ?></button>
								<div class="kata-lightbox-wrapper wp-clearfix"></div>
							</div>
						</div>
						<?php
						$cats = '';
					}
				}
			} else {
				?>
				<div class="kata-notice" style="background: #fff; border: 1px solid #e3e5e7; border-left-width: 4px; box-shadow: 0 1px 1px #0000000a; background-repeat: no-repeat; background-position: 2px 2px; background-size: 252px; border-left-color: #d10000; padding: 15px; margin-bottom: 20px; border-radius: 6px;">
					<?php echo __( 'Oops! There are no demos available', 'kata-plus' ); ?>
				</div>
				<?php
			}
			?>
		</div> <!-- end .row -->
	</div> <!-- end .kata-container -->
</div> <!-- .kata-importer -->
