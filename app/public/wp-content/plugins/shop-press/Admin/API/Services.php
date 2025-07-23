<?php
/**
 * Admin services.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

class Services {
	/**
	 * Instance of this class.
	 *
	 * @since  1.0.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since  1.0.0
	 *
	 * @return object
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			'sp-admin',
			'/component-pages',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_pages' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'add_page' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_page' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/import-export',
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'import_export' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/fields',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_fields' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/options',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_option' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_option' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/posts',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_posts' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'add_post' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/posts/custom',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_custom_list' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/post/edit/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_post' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_post' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/terms',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_terms' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'add_term' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/term/edit/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_term' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_term' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_term' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			'sp-admin',
			'/custom-data',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_custom_data' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Add a page.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function add_page( $request ) {
		return Pages::instance()->add_page( $request );
	}

	/**
	 * Delete a page.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function delete_page( $request ) {
		return Pages::instance()->delete_page( $request );
	}

	/**
	 * Get pages.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_pages( $request ) {
		return Pages::instance()->get_pages( $request );
	}

	/**
	 * Get terms.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_terms( $request ) {
		return Posts::instance()->get_terms( $request );
	}

	/**
	 * Get posts.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_posts( $request ) {
		return Posts::instance()->get_posts( $request );
	}

	/**
	 * Add post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function add_post( $request ) {
		return Posts::instance()->add_post( $request );
	}

	/**
	 * Update post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function update_post( $request ) {
		return Posts::instance()->update_post( $request );
	}

	/**
	 * Delete post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function delete_post( $request ) {
		return Posts::instance()->delete_post( $request );
	}

	/**
	 * Get post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_post( $request ) {
		return Posts::instance()->get_post( $request );
	}

	/**
	 * Add a term.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function add_term( $request ) {
		return Posts::instance()->add_term( $request );
	}

	/**
	 * Delete a term.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function delete_term( $request ) {
		return Posts::instance()->delete_term( $request );
	}

	/**
	 * Get term.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function get_term( $request ) {
		return Posts::instance()->get_term( $request );
	}

	/**
	 * Update term.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function update_term( $request ) {
		return Posts::instance()->update_term( $request );
	}

	/**
	 * Update option.
	 *
	 * @since 1.0.0
	 *
	 * @param object $request
	 */
	public function update_option( $request ) {
		return Options::instance()->update( $request );
	}

	/**
	 * Get option.
	 *
	 * @since 1.0.0
	 *
	 * @param object $request
	 */
	public function get_option( $request ) {
		return Options::instance()->get();
	}

	/**
	 * Get fields.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_fields( $request ) {
		return GetFields::instance()->get_fields( $request );
	}

	/**
	 * Get custom list.
	 *
	 * @since 1.4.0
	 *
	 * @param object $request
	 */
	public function get_custom_list( $request ) {
		return Posts::instance()->custom_list_filter( $request );
	}

	/**
	 * Import / Export.
	 *
	 * @since 1.0.0
	 *
	 * @param object $request
	 */
	public static function import_export( $request ) {
		$type = $request->get_param( 'type' );

		switch ( $type ) {
			case 'import': {
				Import::instance()->import( $request );
				break;
			}

			case 'export': {
				Export::instance()->export( $request );
				break;
			}

			default:
				break;
		}
	}

	/**
	 * Search a string in Array.
	 *
	 * @since 1.2.0
	 *
	 * @param array  $source
	 * @param string $search
	 */
	private function search_string( $source, $search ) {

		if ( ! $source || ! $search ) {
			return array();
		}

		$matches            = array();
		$closest_match      = array();
		$highest_similarity = 0;

		foreach ( $source as $key => $label ) {

			similar_text( $label, $search, $similarity );

			if ( $similarity > $highest_similarity && $similarity >= 20 ) {
				$highest_similarity = $similarity;

				$closest_match = array(
					'value' => $key,
					'label' => $label,
				);
			}
		}

		if ( ! empty( $closest_match ) ) {
			$matches[] = $closest_match;
		}

		if ( ! empty( $matches ) ) {
			return $matches;
		}

		return array();
	}

	/**
	 * Get custom data.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_custom_data( $request ) {
		$source = $request->get_param( 'source' );
		$search = $request->get_param( 's' );

		if ( ! $source || ! $search ) {
			return array();
		}

		if ( 'rules' === $source ) {

			$roles = wp_roles()->role_names;

			return $this->search_string( $roles, $search );
		}

		if ( 'country' === $source ) {

			$countries = WC()->countries->get_countries();

			return $this->search_string( $countries, $search );
		}

		return array();
	}

	/**
	 * Check permission.
	 *
	 * @since 1.0.0
	 */
	public function check_permission() {
		return current_user_can( 'manage_options' );
	}
}
