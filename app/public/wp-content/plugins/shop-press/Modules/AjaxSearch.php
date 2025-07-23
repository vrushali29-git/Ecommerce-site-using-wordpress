<?php
/**
 * Ajax search.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class AjaxSearch {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'wp_ajax_nopriv_sp_ajax_search', array( __CLASS__, 'sp_ajax_search' ) );
		add_action( 'wp_ajax_sp_ajax_search', array( __CLASS__, 'sp_ajax_search' ) );
	}

	/**
	 * Ajax function.
	 *
	 * @since 1.1.0
	 */
	public static function sp_ajax_search() {
		check_ajax_referer( 'shoppress_nonce', 'nonce' );

		$s     = sanitize_text_field( $_POST['s'] ?? '' );
		$cat   = sanitize_text_field( $_POST['cat'] ?? '' );
		$limit = sanitize_text_field( $_POST['limit'] ?? '' );
		$res   = sanitize_text_field( $_POST['res'] ?? '' );

		$content = static::search( $s, $cat, $limit, $res );

		wp_send_json(
			array(
				'content' => $content,
			)
		);

		wp_die();
	}

	/**
	 * Search dropdown.
	 *
	 * @since 1.1.0
	 */
	public static function search_dropdown( $loop ) {
		?>
			<ul class="sp-products">
				<?php
				if ( $loop->have_posts() ) {

					while ( $loop->have_posts() ) :
						$loop->the_post();

						$product = wc_get_product( get_the_ID() );
						$price   = $product->get_price_html();
						$image   = $product->get_image( array( 60, 60 ) );
						$title   = $product->get_title();
						$link    = get_permalink( get_the_ID() );

						?>
								<li>
									<div class="sp-ajax-s-pr">
										<span class="sp-ajax-s-img">
										<?php echo wp_kses_post( $image ); ?>
										</span>
										<div class="sp-ajax-meta">
											<span class="sp-ajax-s-pr-title">
												<a href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( $title ); ?></a>
											</span>
											<div class="sp-ajax-s-p-wrap">
												<span class="sp-ajax-s-price">
												<?php echo wp_kses_post( $price ); ?>
												</span>
											</div>
										</div>
									</div>
								</li>
							<?php

						endwhile;
				} else {
					echo __( 'No products found', 'shop-press' );
				}
				?>
			</ul>

		<?php
	}


	/**
	 * Search content.
	 *
	 * @since 1.1.0
	 */
	public static function search_content( $loop ) {

		if ( $loop->have_posts() ) {

			while ( $loop->have_posts() ) :
				$loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found', 'shop-press' );
		}
	}

	/**
	 * Search.
	 *
	 * @since 1.1.0
	 */
	public static function search( $s, $cat, $limit, $res ) {
		ob_start();

		$limit = $limit ? $limit : 9;

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $limit,
			's'              => $s,
		);

		if ( ! empty( $cat ) ) {

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => 'IN',
				),
			);
		}

		$loop = new \WP_Query( $args );

		if ( ! $res ) {
			static::search_dropdown( $loop );
		}

		if ( $res ) {
			static::search_content( $loop );
		}

		wp_reset_postdata();

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}
}
