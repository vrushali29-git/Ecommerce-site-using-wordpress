<?php
/**
 * Import default templates.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class DefaultTemplates {
	/**
	 * Templates URL.
	 *
	 * @var string
	 */
	private static $templates_url = '';

	/**
	 * Local Templates URL.
	 *
	 * @var string
	 */
	private static $local_templates_url = SHOPPRESS_URL . 'public/sample-data/';

	/**
	 * Get template content.
	 *
	 * @since  1.0.0
	 */
	public static function get_template_content( $page_id, $custom_type, $template = '' ) {

		if ( ! $page_id ) {
			return;
		}

		$args = array(
			'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
		);

		$url = $template ? self::$templates_url . $custom_type . '/' . $template . '.json' : self::$local_templates_url . $custom_type . '.json';

		$response = wp_remote_get( $url, $args );

		if ( ! is_wp_error( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( isset( $data['content'] ) ) {
				update_post_meta( $page_id, '_elementor_data', $data['content'] );
			}
		}
	}
}
