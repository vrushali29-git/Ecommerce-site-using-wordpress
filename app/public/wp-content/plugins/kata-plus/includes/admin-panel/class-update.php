<?php
/**
 * UpdatePanelForNewStyler Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

class UpdatePanelForNewStyler {

	/**
	 * Instance of this class.
	 *
	 * @since   1.3.0
	 * @access  public
	 * @var     UpdatePanelForNewStyler
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.3.0
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since   1.3.0
	 */
	private function __construct() {
		$this->actions();
	}

	/**
	 * Add actions.
	 *
	 * @since   1.3.0
	 */
	public function actions() {
		$kata_options = get_option( 'kata_options' );
		if ( isset( $kata_options['updates']['styler']['updated'] ) ) {
			return false;
		}
		add_action( 'wp_ajax_kata_update_db_initial_ids', array( $this, 'initial_ids' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'kata_plus_add_submenu_page', array( $this, 'add_submenu_page' ) );

		if ( ! isset( $kata_options['updates']['styler']['redirection'] ) ) {
			add_action( 'admin_init', array( $this, 'redirect' ) );
		}
	}

	/**
	 * enqueue scripts.
	 *
	 * @since 1.3.0
	 */
	public function add_submenu_page() {
		add_submenu_page(
			'kata-plus-theme-activation',
			esc_html__( 'Data Update Panel', 'kata-plus' ),
			esc_html__( 'Update Data', 'kata-plus' ),
			Kata_Plus_Helpers::capability(),
			'kata-plus-update',
			array( Kata_Plus_Admin_Panel::get_instance(), 'data_update' )
		);
	}

	/**
	 * enqueue scripts.
	 *
	 * @since 1.3.0
	 */
	public function enqueue_scripts() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'kata-plus-update' ) {
			wp_enqueue_style( 'kata-data-update', Kata_Plus::$assets . 'css/backend/data-update.css', array(), Kata_Plus::$version );
		}
	}

	/**
	 * initial post ids that needs update
	 *
	 * @since     1.3.0
	 */
	public function admin_notice() {
		$kata_options = get_option( 'kata_options' );
		if ( isset( $kata_options['updates']['styler']['redirection'] ) ) {
			if ( ! isset( $kata_options['updates']['styler']['updated'] ) ) {
				echo '
				<div class="kata-notice kt-dashboard-box notice notice-error is-dismissible" style="background-size:0;">
					<h2>' . esc_html__( 'You are using Kata Plus version 1.3.0 without updated database', 'kata-plus' ) . '</h2>
					<h4>' . esc_html__( 'It is essential to update your database, otherwise your website preview will break. Please remember this is a major update so you have to take a full backup from your website.', 'kata-plus' ) . '</h4>
					<p><a href="' . esc_url( admin_url( 'admin.php?page=kata-plus-update' ) ) . '" class="kt-notice-button">
						<i class="dashicons dashicons-update" style="display: none;"></i>
						<span>' . esc_html__( 'Go to update page', 'kata-plus' ) . '</span>
					</a></p>
				</div>';
			}
		}
	}

	/**
	 * initial post ids that needs update
	 *
	 * @since     1.3.0
	 */
	public function redirect() {

		if ( ! isset( $_REQUEST['action'] ) && $_REQUEST['action'] !== 'kata_update_db_initial_ids' ) {
			$kata_options                                     = get_option( 'kata_options' );
			$kata_options['updates']['styler']['redirection'] = true;
			update_option( 'kata_options', $kata_options );
			exit( esc_url( wp_safe_redirect( admin_url( 'admin.php?page=kata-plus-update' ) ) ) );
		}
	}

	/**
	 * initial post ids that needs update
	 *
	 * @since     1.3.0
	 */
	public function initial_ids() {

		$post_types   = get_post_types();
		$kata_options = get_option( 'kata_options' );

		$ids = array();

		$query = new \WP_Query(
			array(
				'meta_key'       => '_elementor_data',
				'post_type'      => $post_types,
				'posts_per_page' => -1,
			)
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				// Get the post ID
				$post_id = get_the_ID();

				// Retrieve the old styler data from the post meta
				$old_data = get_post_meta( $post_id, '_elementor_data', true );

				if ( is_array( $old_data ) || is_object( $old_data ) && $old_data ) {
					$old_data = json_encode( $old_data );
				}

				if ( ! empty( $old_data ) ) {
					// Check if the data contains styler_wrapper
					if ( strpos( $old_data, 'styler' ) !== false ) {
						if ( ! get_post_meta( $post_id, '_elementor_data_updated_1.3', true ) ) {
							$ids[ $post_id ] = get_the_title( $post_id );
						}
					}
				}
			}

			wp_reset_postdata();
		}

		$term_meta_list = get_option( 'kirki_migration_list' );

		if ( ! $term_meta_list && ! isset( $term_meta_list['updated'] ) ) {
			$ids['update_kirki'] = 'Update Kirki';
			update_option( 'kirki_migration_list', $term_meta_list );
		}

		$ids['regenerate_elementor_data'] = 'Regenerate CSS & Data';

		$kata_options['updates']['styler']['have_backup'] = true;
		$kata_options['updates']['styler']['updated']     = true;
		update_option( 'kata_options', $kata_options );

		wp_send_json(
			array(
				'ids' => $ids,
			)
		);
	}
} //Class

UpdatePanelForNewStyler::get_instance();
