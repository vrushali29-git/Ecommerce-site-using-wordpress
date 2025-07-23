<?php
/**
 * Announcement.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin;

defined( 'ABSPATH' ) || exit;

class Announcement {
	/**
	 * Option
	 *
	 * @var array
	 */
	private static $option;

	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! is_admin() ) {
			return;
		}

		self::set_announcement_option();
		self::add_announcement_option();
		self::hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.3
	 */
	private static function hooks() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'shoppress/admin/before_dashboard', array( __CLASS__, 'announcement_content' ) );
		add_action( 'wp_ajax_shoppress_announcement', array( __CLASS__, 'shoppress_announcement' ) );
	}

	/**
	 * Set announcement option.
	 *
	 * @since 1.2.0
	 */
	private static function set_announcement_option() {
		self::$option = get_option( 'sp_announcement' );
	}

	/**
	 * Add shoppress_announcement option.
	 *
	 * @since 1.0.3
	 */
	private static function add_announcement_option() {

		if ( self::check_option() === false ) {
			add_option(
				'sp_announcement',
				array(
					'version' => '1',
					'status'  => true,
					'content' => array(),
				)
			);
		}
	}

	/**
	 * Check shoppress_announcement option.
	 *
	 * @since 1.0.3
	 */
	private static function check_option() {

		if ( self::$option ) {
			return true;
		}

		return false;
	}

	/**
	 * Check announcement status.
	 *
	 * @since 1.0.3
	 */
	private static function check_status() {
		return self::$option['status'] ?? false;
	}

	/**
	 * Check announcement version.
	 *
	 * @since 1.0.3
	 */
	private static function check_version() {
		return self::$option['version'] ?? 1;
	}

	/**
	 * Update announcement content.
	 *
	 * @since 1.0.3
	 *
	 * @param array $body
	 */
	private static function update_display_content( array $body ) {

		if ( ! $body ) {
			return;
		}

		$updated_content = array();
		foreach ( $body as $key => $item ) {

			if ( $key === 'version' || $key === 'display' ) {
				continue;
			}

			$updated_content[ $key ] = sanitize_text_field( $item );
		}
		self::$option['content'] = $updated_content;
		update_option( 'sp_announcement', self::$option );

		$status          = self::check_status();
		$current_version = self::check_version();

		if ( $status === false && $current_version < $body['version'] ) {

			self::$option['version'] = $body['version'];
			self::$option['status']  = true;
			update_option( 'sp_announcement', self::$option );
		}
	}

	/**
	 * Update shoppress_announcement option.
	 *
	 * @since 1.0.3
	 */
	public static function shoppress_announcement() {
		$nonce = sanitize_text_field( $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'sp_announcement' ) ) {
			die( 'Wrong nonce!' );
		}

		self::$option['status'] = false;

		update_option( 'sp_announcement', self::$option );

		wp_die();
	}

	/**
	 * Content.
	 *
	 * @since  1.0.3
	 */
	public static function announcement_content() {
		$body = self::get_data();

		if ( false !== $body ) {
			self::update_display_content( $body );
		}

		$display_content = self::$option['content'] ?? false;

		if ( ! $display_content || false === self::check_status() ) {
			return;
		}

		ob_start();
		?>
		<div id="sp-admin-announcement">
			<div class="sp-ad-left">
				<?php if ( $display_content['title'] ) : ?>
					<div class="sp-an-title"><?php echo esc_html( $display_content['title'] ); ?></div>
				<?php endif; ?>

				<div class="sp-an-content"><?php echo wp_kses_post( $display_content['content'] ); ?></div>

				<?php if ( $display_content['link'] ) : ?>
					<div class="sp-an-link">
						<a href="<?php echo esc_url( $display_content['link'] ); ?>" target="_blank"><?php echo esc_html( $display_content['link_text'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<div class="sp-ad-right">
				<div class="sp-an-close">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z" fill="#000000" /> </svg>
				</div>
				<div class="sp-an-img"><img src="<?php echo esc_url( $display_content['image'] ); ?>" width="200"></div>
			</div>
		</div>
		<?php

		return ob_get_contents();
	}

	/**
	 * Get announcement data.
	 *
	 * @since 1.0.3
	 */
	private static function get_data() {

		if ( false === self::should_get_content() ) {
			return false;
		}

		$request = wp_remote_get( 'https://climaxthemes.org/sp/announcement/api.json' );

		if ( is_wp_error( $request ) ) {
			return false;
		}

		$body = json_decode( $request['body'], true );

		return $body;
	}

	/**
	 * Checks the announcement last execution.
	 *
	 * @since 1.2.0
	 */
	private static function should_get_content() {
		$last_execution = self::$option['last_execution'] ?? false;

		if ( ! $last_execution || ( time() - $last_execution ) >= 24 * 3600 ) {

			self::$option['last_execution'] = time();
			update_option( 'sp_announcement', self::$option );

			return true;
		}

		return false;
	}

	/**
	 * enqueue scripts.
	 *
	 * @since  1.0.3
	 */
	public static function enqueue_scripts() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'shoppress' ) {

			wp_enqueue_style( 'sp-admin-announcement' );
			wp_enqueue_script( 'sp-admin-announcement' );

			wp_localize_script(
				'sp-admin-announcement',
				'shoppressAnnouncement',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'sp_announcement' ),
				)
			);
		}
	}
}
