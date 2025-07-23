<?php
/**
 * Fast mode Template 5
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */
$kata_options       = get_option( 'kata_options' )['fast-mode'];
$websitetype_toshow = $kata_options['websitetype'];
$address            = isset( $kata_options['site-address'] ) && ! empty( $kata_options['site-address'] ) ? $kata_options['site-address'] : '0';
$phone              = isset( $kata_options['site-phone'] ) && ! empty( $kata_options['site-phone'] ) ? $kata_options['site-phone'] : '0';
$importer           = new Kata_Plus_Importer();
$demos              = $importer->demos();
$cats               = '';
$cat_terms          = array();
$get_terms          = false;
?>

<style id="fast-mode-filet">
	[website-type] { display: none; }
	[website-type~="fast"] { display: inline-block; }
</style>

<div id="kt-fst-mod-5" class="kt-fst-mod-wrapper kata-admin">
	<h1 id="page-title" class="chose-bussiness-type"><?php echo esc_html__( 'Select your favorite design for start the import', 'kata-plus' ); ?></h1>

	<div class="fast-mode-search-demo search">
		<div class="fm-search">
			<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( '', 'font-awesome/search', '', '' ) ); ?>
		</div>
		<input type="text" name="seach" class="site-search" placeholder="<?php echo esc_html__( 'search', 'kata-plus' ); ?>" value="">
	</div>

	<div id="kata-importer-wrapper" class="kata-importer-wrapper" style="display: none;">
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
							$websitetype = isset( $cats ) && ! empty( $cats ) ? strtolower( $cats ) : '';
							if ( 'pro' == $is_pro ) {
								if ( Kata_Plus_Helpers::string_is_contain( $websitetype, 'fast' ) ) {
									$is_pro = 'fast';
								}
							}
							$image         = $value->images[0] ? $value->images[0] : get_template_directory_uri() . '/screenshot.png';
							$kata_options  = get_option( 'kata_options' );
							$imported_demo = isset( $kata_options['imported_demos'] ) && in_array( $key, $kata_options['imported_demos'] ) ? esc_html__( 'This demo was already imported', 'kata-plus' ) : '';
							?>
							<div class="kata-col-sm-4 kata-importer" data-wishlist="false" website-type="all <?php echo esc_attr( $websitetype ); ?>" demo-name="<?php echo esc_attr( strtolower( $value->name ) ); ?>" import-status="<?php echo esc_attr( $imported_demo ); ?>" demo-screenshot="<?php echo esc_attr( $image ); ?>" data-key="<?php echo esc_attr( $key ); ?>" demo-type="<?php echo esc_attr( $is_pro ); ?>">
								<div class="kata-demp-wrapper">
									<div class="kata-demo-addto-wishlist"> <i class="kata-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.419c-2.826-5.695-11.999-4.064-11.999 3.27 0 7.27 9.903 10.938 11.999 15.311 2.096-4.373 12-8.041 12-15.311 0-7.327-9.17-8.972-12-3.27z"/></svg></i> </div>
									<img class="kata-importer-screenshot lozad" data-src="<?php echo esc_url( $image ); ?>">
									<a class="kata-importer-preview" href="<?php echo esc_url( @$value->preview_url ); ?>" target="_blank"><?php echo esc_attr__( 'Preview', 'kata-plus' ); ?></a>
									<h3 class="kata-importer-name">
										<span class="demo-type <?php echo esc_attr( $is_pro ); ?>"><?php echo esc_html( $is_pro ); ?></span>
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
					<p><?php echo esc_html__( 'Oops! There are no demos available', 'kata-plus' ); ?></p>
					<?php
				}
				?>
			</div> <!-- end .row -->
		</div> <!-- end .kata-container -->
	</div>
	<div id="kata-importer-wrapper" class="kata-importer-wrapper fast-mode-preview" style="height: 745px; overflow: hidden;">
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
							$websitetype = isset( $cats ) && ! empty( $cats ) ? strtolower( $cats ) : '';
							if ( 'pro' == $is_pro ) {
								if ( Kata_Plus_Helpers::string_is_contain( $websitetype, 'fast' ) ) {
									$is_pro = 'fast';
								}
							}
							$image         = $value->images[0] ? $value->images[0] : get_template_directory_uri() . '/screenshot.png';
							$kata_options  = get_option( 'kata_options' );
							$imported_demo = isset( $kata_options['imported_demos'] ) && in_array( $key, $kata_options['imported_demos'] ) ? esc_html__( 'This demo was already imported', 'kata-plus' ) : '';
							?>
							<div class="kata-col-sm-4 kata-importer" data-wishlist="false" website-type="all <?php echo esc_attr( $websitetype ); ?>" demo-name="<?php echo esc_attr( strtolower( $value->name ) ); ?>" import-status="<?php echo esc_attr( $imported_demo ); ?>" demo-screenshot="<?php echo esc_attr( $image ); ?>" data-key="<?php echo esc_attr( $key ); ?>" demo-type="<?php echo esc_attr( $is_pro ); ?>">
								<div class="kata-demp-wrapper">
									<img class="kata-importer-screenshot lozad" data-src="<?php echo esc_url( $image ); ?>">
									<a class="kata-importer-preview" href="<?php echo esc_url( @$value->preview_url ); ?>" target="_blank"><?php echo esc_attr__( 'Preview', 'kata-plus' ); ?></a>
									<h3 class="kata-importer-name">
										<?php echo esc_html( $value->name ); ?>
									</h3>
									<div class="kata-lightbox-wrapper wp-clearfix"></div>
								</div>
							</div>
							<?php
							$cats = '';
						}
					}
				} else {
					?>
					<p><?php echo esc_html__( 'Oops! There are no demos available', 'kata-plus' ); ?></p>
					<?php
				}
				?>
			</div> <!-- end .row -->
		</div> <!-- end .kata-container -->
	</div>
	<a href="#" class="fast-mode-loadmore"><?php echo esc_html__( 'Load more...', 'kata-plus' ); ?></a>
</div>
<div class="kt-fst-mod-footer-area kt-fst-mod-5">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=4' ) ); ?>" class="prev-step">
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_12" data-name="Path 12" d="M.854,5.844a.5.5,0,0,1-.707-.707L5.136.147A.494.494,0,0,1,5.489,0L5.5,0l.01,0a.491.491,0,0,1,.4.212l4.946,4.945a.5.5,0,0,1-.707.707L6.01,1.728V19.5a.5.5,0,0,1-1,0V1.686Z" transform="translate(0 11) rotate(-90)" fill="#adb8b8"/>
			</svg>
		</i>
		<?php echo esc_html__( 'Back', 'kata-plus' ); ?>
	</a>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=6' ) ); ?>" class="next-step">
		<?php echo esc_html__( 'Next', 'kata-plus' ); ?>
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_11" data-name="Path 11" d="M19.333,14.156a.5.5,0,0,0-.707.707l4.989,4.99a.494.494,0,0,0,.353.147l.01,0,.01,0a.491.491,0,0,0,.4-.212l4.946-4.945a.5.5,0,0,0-.707-.707L24.49,18.272V.5a.5.5,0,0,0-1,0V18.314Z" transform="translate(0 29.479) rotate(-90)" fill="#00d6f9"/>
			</svg>
		</i>
	</a>
</div>
