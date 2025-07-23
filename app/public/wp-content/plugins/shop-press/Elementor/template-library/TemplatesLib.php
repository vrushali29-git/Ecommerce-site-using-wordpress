<?php
/**
 * Template Library.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor\TemplateLibrary;

use Elementor\User;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Modules\KitElementsDefaults\Utils\Settings_Sanitizer;
use Elementor\Core\Utils\Collection;

/**
 * Template Library.
 *
 * @since 1.2.0
 */
class TemplatesLib {

	/**
	 * library option key.
	 */
	const LIBRARY_OPTION_KEY = 'shoppress_templates_library';

	/**
	 * Presets option key.
	 */
	const PRESETS_OPTION_KEY = 'shoppress_presets_library';

	/**
	 * Presets option key.
	 */
	const PRESETS_ELEMENTS_OPTION_KEY = 'shoppress_preset_elements';

	/**
	 * API templates URL.
	 *
	 * Holds the URL of the templates API.
	 *
	 * @access public
	 * @static
	 *
	 * @var string API URL.
	 */
	public static $api_url = 'https://katademos.com/api/shoppress-template-manager/v1/';

	/**
	 * templates Path.
	 *
	 * Holds the URL of the templates path.
	 *
	 * @access public
	 * @static
	 *
	 * @var string path.
	 */
	public static $path;

	/**
	 * templates url.
	 *
	 * Holds the URL of the templates url.
	 *
	 * @access public
	 * @static
	 *
	 * @var string url.
	 */
	public static $url;

	/**
	 * Init.
	 *
	 * Initializes the hooks.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function init() {
		self::$path = plugin_dir_path( __FILE__ );
		self::$url  = plugin_dir_url( __FILE__ );

		include_once self::$path . 'TemplateSourceContent.php';

		add_action( 'elementor/init', array( __CLASS__, 'register_source' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( __CLASS__, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/ajax/register_actions', array( __CLASS__, 'register_ajax_actions' ) );
		add_action( 'elementor/editor/footer', array( __CLASS__, 'render_template' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( __CLASS__, 'editor_enqueue_styles' ), 0 );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'preview_enqueue_styles' ) );
		add_action( 'wp_ajax_shoppress_get_preset_element', array( __CLASS__, 'shoppress_get_preset_element' ) );
		add_action( 'wp_ajax_nopriv_shoppress_get_preset_element', array( __CLASS__, 'shoppress_get_preset_element' ) );
	}

	/**
	 * Register source.
	 *
	 * Registers the library source.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function register_source() {
		Plugin::$instance->templates_manager->register_source( '\ShopPress\Elementor\TemplateLibrary\TemplateSourceContent' );
	}

	/**
	 * Enqueue Editor Style.
	 *
	 * Enqueues required scripts in Elementor edit mode.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function editor_enqueue_styles() {
		wp_enqueue_style( 'shoppress-elementor-template-manager-editor', self::$url . 'assets/css/template-manager-editor.css', array(), SHOPPRESS_VERSION );
		wp_enqueue_style( 'shoppress-elementor-template-manager-editor-dark', self::$url . 'assets/css/template-manager-editor-dark.css', array(), SHOPPRESS_VERSION );
	}

	/**
	 * Enqueue Editor Style.
	 *
	 * Enqueues required scripts in Elementor edit mode.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function preview_enqueue_styles() {
		wp_enqueue_style( 'shoppress-elementor-template-manager-preview', self::$url . 'assets/css/template-manager-preview.css', array(), SHOPPRESS_VERSION );
	}

	/**
	 * Enqueue Editor Scripts.
	 *
	 * Enqueues required scripts in Elementor edit mode.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function enqueue_editor_scripts() {

		$post_types = array(
			'shoppress_pages',
			'shoppress_loop',
			'shoppress_myaccount',
		);

		$post_type = get_post_type( $_GET['post'] ?? false );

		if ( ! in_array( $post_type, $post_types ) ) {
			return;
		}

		wp_enqueue_script( 'sp-nicescroll-script' );
		wp_enqueue_script(
			'shoppress-templates-lib',
			self::$url . 'assets/js/templates-lib.js',
			array(
				'jquery',
				'sp-nicescroll-script',
				'backbone-marionette',
				'backbone-radio',
				'elementor-common-modules',
				'elementor-dialog',
			),
			SHOPPRESS_VERSION,
			true
		);

		$post_type       = get_post_type();
		$post_id         = get_the_ID();
		$template_groups = array();
		$post_types      = array(
			'shoppress_pages',
		);
		if ( in_array( $post_type, $post_types ) ) {

			$types = (array) get_post_meta( $post_id, 'custom_type', true );

			if ( in_array( 'archive', $types ) || in_array( 'shop', $types ) ) {

				$template_groups['templates/shop'] = array(
					'title'  => __( 'Shop', 'shop-press' ),
					'filter' => array(
						'source'  => 'shop',
						'type'    => 'shop',
						'subtype' => 'shop',
					),
				);
			} elseif ( in_array( 'single', $types ) ) {

				$template_groups['templates/single_product'] = array(
					'title'  => __( 'Product Single', 'shop-press' ),
					'filter' => array(
						'source'  => 'single_product',
						'type'    => 'single_product',
						'subtype' => 'single_product',
					),
				);
			} elseif ( in_array( 'cart', $types ) ) {
				$template_groups['templates/cart'] = array(
					'title'  => __( 'Cart', 'shop-press' ),
					'filter' => array(
						'source'  => 'cart',
						'type'    => 'cart',
						'subtype' => 'cart',
					),
				);
			} elseif ( in_array( 'empty_cart', $types ) ) {
				$template_groups['templates/empty_cart'] = array(
					'title'  => __( 'Empty Cart', 'shop-press' ),
					'filter' => array(
						'source'  => 'empty_cart',
						'type'    => 'empty_cart',
						'subtype' => 'empty_cart',
					),
				);
			} elseif ( in_array( 'checkout', $types ) ) {

				$template_groups['templates/checkout'] = array(
					'title'  => __( 'Checkout', 'shop-press' ),
					'filter' => array(
						'source'  => 'checkout',
						'type'    => 'checkout',
						'subtype' => 'checkout',
					),
				);
			} elseif ( in_array( 'checkout', $types ) ) {

				$template_groups['templates/checkout'] = array(
					'title'  => __( 'Checkout', 'shop-press' ),
					'filter' => array(
						'source'  => 'checkout',
						'type'    => 'checkout',
						'subtype' => 'checkout',
					),
				);

			} elseif ( in_array( 'thank_you', $types ) ) {

				$template_groups['templates/thank_you'] = array(
					'title'  => __( 'Thank You', 'shop-press' ),
					'filter' => array(
						'source'  => 'thank_you',
						'type'    => 'thank_you',
						'subtype' => 'thank_you',
					),
				);

			} elseif ( in_array( 'compare', $types ) ) {

				$template_groups['templates/compare'] = array(
					'title'  => __( 'Compare', 'shop-press' ),
					'filter' => array(
						'source'  => 'compare',
						'type'    => 'compare',
						'subtype' => 'compare',
					),
				);
			} elseif ( in_array( 'quick_view', $types ) ) {

				$template_groups['templates/quick_view'] = array(
					'title'  => __( 'Quick View', 'shop-press' ),
					'filter' => array(
						'source'  => 'quick_view',
						'type'    => 'quick_view',
						'subtype' => 'quick_view',
					),
				);
			} elseif ( in_array( 'wishlist', $types ) ) {

				$template_groups['templates/wishlist'] = array(
					'title'  => __( 'Wishlist', 'shop-press' ),
					'filter' => array(
						'source'  => 'wishlist',
						'type'    => 'wishlist',
						'subtype' => 'wishlist',
					),
				);
			} elseif ( in_array( 'wishlist', $types ) ) {

				$template_groups['templates/wishlist'] = array(
					'title'  => __( 'Wishlist', 'shop-press' ),
					'filter' => array(
						'source'  => 'wishlist',
						'type'    => 'wishlist',
						'subtype' => 'wishlist',
					),
				);
			}
		}

		if ( 'shoppress_loop' === $post_type ) {

			$template_groups['templates/product_loop'] = array(
				'title'  => __( 'Product Loop', 'shop-press' ),
				'filter' => array(
					'source'  => 'product_loop',
					'type'    => 'product_loop',
					'subtype' => 'product_loop',
				),
			);
		}

		if ( 'shoppress_myaccount' === $post_type ) {

			$template_groups['templates/my_account'] = array(
				'title'  => __( 'My Account', 'shop-press' ),
				'filter' => array(
					'source'  => 'my_account',
					'type'    => 'my_account',
					'subtype' => 'my_account',
				),
			);
		}

		$elementor_data     = get_post_meta( $post_id, '_elementor_data', true );
		$open_templates_lib = ( empty( $elementor_data ) || '[]' === $elementor_data ) ? 'yes' : 'no';
		$is_kata            = false;
		$theme              = wp_get_theme();
		$parent_theme       = $theme->parent();
		if ( false !== strpos( $theme->get( 'TextDomain' ), 'kata' ) || ( $parent_theme && false !== strpos( $parent_theme->get( 'TextDomain' ), 'kata' ) ) ) {
			$is_kata = true;
		}

		wp_localize_script(
			'shoppress-templates-lib',
			'shoppress_templates_lib',
			array(
				'template_groups'    => $template_groups,
				'open_templates_lib' => $open_templates_lib,
				'is_kata'            => $is_kata,
			)
		);
	}

	/**
	 * Init ajax calls.
	 *
	 * Initialize template library ajax calls for allowed ajax requests.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param Ajax $ajax Elementor's Ajax object.
	 * @return void
	 */
	public static function register_ajax_actions( Ajax $ajax ) {
		$library_ajax_requests = array(
			'shoppress_get_library_data',
			'shoppress_get_template_data',
		);

		foreach ( $library_ajax_requests as $ajax_request ) {
			$ajax->register_ajax_action(
				$ajax_request,
				function ( $data ) use ( $ajax_request ) {
					return self::handle_ajax_request( $ajax_request, $data );
				}
			);
		}
	}

	/**
	 * Handle ajax request.
	 *
	 * Fire authenticated ajax actions for any given ajax request.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param string $ajax_request Ajax request.
	 * @param array  $data Elementor data.
	 *
	 * @return mixed
	 * @throws \Exception Throws error message.
	 */
	private static function handle_ajax_request( $ajax_request, array $data ) {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			throw new \Exception( 'Access Denied' );
		}

		if ( ! empty( $data['editor_post_id'] ) ) {
			$editor_post_id = absint( $data['editor_post_id'] );

			if ( ! get_post( $editor_post_id ) ) {
				throw new \Exception( __( 'Post not found.', 'shop-press' ) );
			}

			Plugin::$instance->db->switch_to_post( $editor_post_id );
		}

		$result = call_user_func( array( __CLASS__, $ajax_request ), $data );

		if ( is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message() );
		}

		return $result;
	}

	/**
	 * Get library data.
	 *
	 * Get data for template library.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $args Arguments.
	 *
	 * @return array Collection of templates data.
	 */
	public static function shoppress_get_library_data( array $args ) {
		$library_data = self::get_library_data( ! empty( $args['sync'] ) );

		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();

		return array(
			'templates' => self::get_templates(),
			'config'    => $library_data,
		);
	}

	/**
	 * Get templates.
	 *
	 * Retrieve all the templates from all the registered sources.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Templates array.
	 */
	public static function get_templates() {
		$source = Plugin::$instance->templates_manager->get_source( 'product_loop' );

		return $source->get_items();
	}

	/**
	 * Get templates data.
	 *
	 * This function the templates data.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data retrieval or
	 *                                     not. Default is false.
	 *
	 * @return array|false Templates data, or false.
	 */
	private static function get_templates_data( $force_update = false ) {
		$cache_key = 'shoppress_templates_data_' . SHOPPRESS_VERSION;

		$templates_data = get_transient( $cache_key );

		if ( $force_update || false === $templates_data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get(
				self::$api_url . 'templates.json',
				array(
					'timeout' => $timeout,
				)
			);

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			$templates_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $templates_data ) || ! is_array( $templates_data ) ) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			if ( isset( $templates_data ) ) {
				update_option( self::LIBRARY_OPTION_KEY, $templates_data, 'no' );
			}

			set_transient( $cache_key, $templates_data, 12 * HOUR_IN_SECONDS );
		}

		return $templates_data;
	}

	/**
	 * Get Presets data.
	 *
	 * This function the Presets data.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data retrieval or
	 *                                     not. Default is false.
	 *
	 * @return array|false Templates data, or false.
	 */
	public static function get_presets( $force_update = false ) {
		$cache_key = 'shoppress_presets_data_' . SHOPPRESS_VERSION;

		$presets_data = get_transient( $cache_key );

		if ( $force_update || false === $presets_data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get(
				self::$api_url . 'presets/presets.json',
				array(
					'timeout' => $timeout,
				)
			);

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			$presets_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $presets_data ) || ! is_array( $presets_data ) ) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			if ( isset( $presets_data ) ) {
				update_option( self::PRESETS_OPTION_KEY, $presets_data, 'no' );
			}

			set_transient( $cache_key, $presets_data, 12 * HOUR_IN_SECONDS );
		}

		return $presets_data;
	}

	/**
	 * Get Preset element data.
	 *
	 * This function the Preset element data.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @return array|false Templates data, or false.
	 */
	public static function shoppress_get_preset_element() {

		if ( ! check_ajax_referer( 'shoppress_get_preset_element', 'nonce' ) ) {
			wp_send_json( 'Invalid nonce!', 400 );
			wp_die();
		}

		$element = sanitize_text_field( $_POST['element'] );
		$reset   = sanitize_text_field( $_POST['reset'] );

		$presets = get_option( self::PRESETS_ELEMENTS_OPTION_KEY );

		if ( ! isset( $presets[ $element ] ) || $reset === 'true' ) {

			$response = wp_remote_get(
				self::$api_url . 'presets/elements/' . $element . '.json',
				array( 'timeout' => 30 )
			);

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				wp_send_json( 'Unexpected Error, Code: ', wp_remote_retrieve_response_code( $response ) );
				wp_die();
			}

			$presets_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( isset( $presets_data ) ) {
				$presets[ $element ] = $presets_data;
				update_option( self::PRESETS_ELEMENTS_OPTION_KEY, $presets, 'no' );
			}

			$presets = get_option( self::PRESETS_ELEMENTS_OPTION_KEY );
			wp_send_json( $presets, 200 );

		} else {
			$presets = get_option( self::PRESETS_ELEMENTS_OPTION_KEY );
			wp_send_json( $presets, 200 );
		}

		wp_die();
	}

	/**
	 * Get templates data.
	 *
	 * Retrieve the templates data from a remote server.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data update or
	 *                                     not. Default is false.
	 *
	 * @return array The templates data.
	 */
	public static function get_library_data( $force_update = false ) {
		self::get_templates_data( $force_update );

		$library_data = get_option( self::LIBRARY_OPTION_KEY );

		if ( empty( $library_data ) ) {
			return array();
		}

		return $library_data;
	}

	/**
	 * Test the upload of an image from a given URL.
	 *
	 * @since 1.3.8
	 *
	 * @param string $image_url The URL of the image to test.
	 * @return bool Returns true if the image is valid and can be uploaded, false otherwise.
	 */
	public static function test_image_for_upload( $image_url ) {

		// Check for if user can download images
		if (
			( strpos( 'katademos.com', $image_url ) !== false || strpos( 'climaxthemes.com', $image_url ) !== false ) &&
			! current_user_can( 'upload_files' ) ) {
			return false;
		}

		// Check the URL of the image
		if ( ! filter_var( $image_url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		// Check the file type of the image
		$file_type = wp_check_filetype( $image_url );
		if ( false === $file_type['type'] && false === $file_type['ext'] ) {
			return false;
		}

		// Check the file extension of the image
		$file_extension = pathinfo( $image_url, PATHINFO_EXTENSION );
		if ( ! in_array( $file_extension, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
			return false;
		}

		// Check the upload directory
		$upload_dir = wp_upload_dir();
		if ( ! is_dir( $upload_dir['path'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Downloads an image from a given URL and uploads it to the WordPress media library.
	 *
	 * @since 1.2.0
	 *
	 * @param string $image_url The URL of the image to download and upload.
	 *
	 * @return int|false|string The attachment ID of the uploaded image, false if there was an error saving the image, or a string describing the error.
	 */
	public static function download_and_upload_image( $image_url ) {
		// Make sure WordPress core is loaded
		if ( ! defined( 'ABSPATH' ) ) {
			require_once '../../../../wp-load.php'; // Adjust the path to wp-load.php if necessary
		}

		// Test the upload of an image from a given URL.
		static::test_image_for_upload( $image_url );

		// Get the file extension from the image URL
		$file_extension = pathinfo( $image_url, PATHINFO_EXTENSION );

		// Generate a unique file name for the downloaded image
		$unique_file_name = wp_unique_filename( wp_upload_dir()['path'], 'downloaded-image-' . time() ) . '.' . $file_extension;

		// Download the image
		$downloaded_image = download_url( $image_url );

		if ( ! is_wp_error( $downloaded_image ) ) {
			// Save the downloaded image to the uploads directory
			$upload_dir  = wp_upload_dir();
			$target_path = $upload_dir['path'] . '/' . $unique_file_name;

			if ( copy( strval( $downloaded_image ), $target_path ) ) {

				@unlink( $downloaded_image );

				// Insert the downloaded image as a media attachment
				$attachment = array(
					'post_mime_type' => 'image/' . $file_extension,
					'post_title'     => sanitize_file_name( pathinfo( $unique_file_name, PATHINFO_FILENAME ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);

				$attachment_id = wp_insert_attachment( $attachment, $target_path );

				if ( ! is_wp_error( $attachment_id ) ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $target_path );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );

					return array(
						'url' => wp_get_attachment_url( $attachment_id ),
						'id'  => $attachment_id,
					);
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Updates the images in the input array.
	 *
	 * @since 1.2.0
	 *
	 * @param array $inputArray The input array containing the content and elements.
	 *
	 * @return array The updated input array with the images updated.
	 */
	public static function update_images( $data ) {

		foreach ( $data['content'] as $section_key => $section ) {
			foreach ( $section['elements'] as $elements_key => $elements ) {
				foreach ( $elements as $element_key => $element ) {
					// if it's element/widget
					if ( $element_key === 'elements' ) {
						foreach ( $element as $widgets_key => $widgets ) {
							foreach ( $widgets as $widget_id => $widget ) {
								if ( is_array( $widget ) ) {
									foreach ( $widget as $widget_setting_key => $widget_setting ) {
										if ( is_array( $widget_setting ) && ! empty( $widget_setting ) ) {

											// update images for repeater
											if ( isset( $widget_setting[0] ) ) {
												foreach ( $widget_setting as $repeater_key => $repeater_value ) {
													if ( is_array( $repeater_value ) ) {
														foreach ( $repeater_value as $repeater_item_key => $repeater_item_value ) {
															if ( is_array( $repeater_item_value ) && isset( $repeater_item_value['url'] ) && isset( $repeater_item_value['id'] ) ) {
																$new_image = static::download_and_upload_image( static::update_template_url( $repeater_item_value['url'] ) );
																$data['content'][ $section_key ]['elements'][ $elements_key ][ $element_key ][ $widgets_key ][ $widget_id ][ $widget_setting_key ][ $repeater_key ][ $repeater_item_key ]['url'] = $new_image['url'];
																$data['content'][ $section_key ]['elements'][ $elements_key ][ $element_key ][ $widgets_key ][ $widget_id ][ $widget_setting_key ][ $repeater_key ][ $repeater_item_key ]['id']  = $new_image['id'];
															}
														}
													}
												}
											}

											// update images
											if ( is_array( $widget_setting ) && isset( $widget_setting['url'] ) && isset( $widget_setting['id'] ) ) {
												$new_image = static::download_and_upload_image( static::update_template_url( $widget_setting['url'] ) );
												$data['content'][ $section_key ]['elements'][ $elements_key ][ $element_key ][ $widgets_key ][ $widget_id ][ $widget_setting_key ]['url'] = $new_image['url'];
												$data['content'][ $section_key ]['elements'][ $elements_key ][ $element_key ][ $widgets_key ][ $widget_id ][ $widget_setting_key ]['id']  = $new_image['id'];
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Sanitize Elements
	 *
	 * @param array      $template_data
	 * @param array|null $types
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function sanitize_elements( $template_data, $types = null ) {

		if ( ! is_array( $template_data ) ) {

			return $template_data;
		}

		if ( is_null( $types ) ) {

			$element_types = array_keys( Plugin::$instance->elements_manager->get_element_types() );
			$widget_types  = array_keys( Plugin::$instance->widgets_manager->get_widget_types() );

			$types = array_merge( $element_types, $widget_types );
		}

		foreach ( $template_data as $k => $data ) {

			$elements = $data['elements'] ?? array();
			if ( ! empty( $elements ) ) {

				$template_data[ $k ]['elements'] = array_values(
					static::sanitize_elements( $elements, $types )
				);
			} else {

				$widget_type = $data['widgetType'] ?? false;
				if ( $widget_type && ! in_array( $widget_type, $types ) ) {
					unset( $template_data[ $k ] );
				}
			}
		}

		return $template_data;
	}

	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return object|\WP_Error The template content.
	 */
	public static function shoppress_get_template_data( $template_id ) {
		$url = self::$api_url . 'template/template-' . $template_id['template_id'] . '.json';

		$response = wp_remote_post( $url, array( 'timeout' => 25 ) );

		if ( is_wp_error( $response ) ) {
			// @codingStandardsIgnoreStart WordPress.XSS.EscapeOutput.
			wp_die( $response, [
				'back_link' => true,
			] );
			// @codingStandardsIgnoreEnd WordPress.XSS.EscapeOutput.
		}

		$body          = wp_remote_retrieve_body( $response );
		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( ! $response_code ) {
			return new \WP_Error( 500, 'No Response' );
		}

		// Server sent a success message without content.
		if ( 'null' === $body ) {
			$body = true;
		}

		$as_array = true;
		$body     = json_decode( $body, $as_array );

		if ( false === $body ) {
			return new \WP_Error( 422, 'Wrong Server Response' );
		}

		if ( 200 !== $response_code ) {
			// In case $as_array = true.
			$body = (object) $body;

			$message = isset( $body->message ) ? $body->message : wp_remote_retrieve_response_message( $response );
			$code    = isset( $body->code ) ? $body->code : $response_code;

			return new \WP_Error( $code, $message );
		}

		$body['content'] = static::sanitize_elements( $body['content'] );

		// update images
		$body = static::update_images( $body );

		return $body;
	}

	/**
	 * Render template.
	 *
	 * Library modal template.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function render_template() {
		?>
		<script type="text/template" id="tmpl-elementor-template-library-header-actions-pp">
			<div id="elementor-template-library-header-sync" class="elementor-templates-modal__header__item">
				<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Templates', 'shop-press' ); ?>"></i>
				<span class="elementor-screen-only"><?php echo esc_html__( 'Sync Templates', 'shop-press' ); ?></span>
			</div>
		</script>
		<script type="text/template" id="tmpl-elementor-templates-modal__header__logo_pp">
			<span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
		</script>
		<script type="text/template" id="shoppress-tmpl-elementor-template-library-header-preview-pp">
			<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
				{{{ shoppress_templates_lib.templates.layout.getTemplateActionButton( obj ) }}}
			</div>
		</script>
		<script type="text/template" id="tmpl-elementor-template-library-templates-shoppress">
			<#
				var activeSource = shoppress_templates_lib.templates.getFilter('source');
			#>
			<div id="elementor-template-library-toolbar">
				<# if ( activeSource ) {
					var activeType = shoppress_templates_lib.templates.getFilter('type');
					#>
					<div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">
						<# if ( 'new_page' === activeType ) { #>
							<div id="elementor-template-library-order">
								<input type="radio" id="elementor-template-library-order-new" class="elementor-template-library-order-input" name="elementor-template-library-order" value="date">
								<label for="-new" class="elementor-template-library-order-label"><?php echo esc_html__( 'New', 'shop-press' ); ?></label>
								<input type="radio" id="elementor-template-library-order-trend" class="elementor-template-library-order-input" name="elementor-template-library-order" value="trendIndex">
								<label for="elementor-template-library-order-trend" class="elementor-template-library-order-label"><?php echo esc_html__( 'Trend', 'shop-press' ); ?></label>
								<input type="radio" id="elementor-template-library-order-popular" class="elementor-template-library-order-input" name="elementor-template-library-order" value="popularityIndex">
								<label for="elementor-template-library-order-popular" class="elementor-template-library-order-label"><?php echo esc_html__( 'Popular', 'shop-press' ); ?></label>
							</div>
						<# } else {
							var config = shoppress_templates_lib.templates.getConfig( activeType );
							if ( config.categories ) { #>
								<div id="elementor-template-library-filter">
									<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select" data-elementor-filter="subtype">
										<option></option>
										<# config.categories.forEach( function( category ) {
											var selected = category === shoppress_templates_lib.templates.getFilter( 'subtype' ) ? ' selected' : '';
											#>
											<option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
										<# } ); #>
									</select>
								</div>
							<# }
						} #>
						<div id="elementor-template-library-my-favorites">
							<# var checked = shoppress_templates_lib.templates.getFilter( 'favorite' ) ? ' checked' : ''; #>
							<input id="elementor-template-library-filter-my-favorites" type="checkbox"{{{ checked }}}>
							<label id="elementor-template-library-filter-my-favorites-label" for="elementor-template-library-filter-my-favorites">
								<i class="eicon" aria-hidden="true"></i>
								<?php echo esc_html__( 'My Favorites', 'shop-press' ); ?>
							</label>
						</div>
					</div>
				<# } #>
				<div id="elementor-template-library-filter-text-wrapper">
					<label for="elementor-template-library-filter-text" class="elementor-screen-only"><?php echo esc_html__( 'Search Templates:', 'shop-press' ); ?></label>
					<input id="elementor-template-library-filter-text" placeholder="<?php echo esc_attr__( 'Search', 'shop-press' ); ?>">
					<i class="eicon-search"></i>
				</div>
			</div>
			<#
			var is_kata = shoppress_templates_lib.is_kata ? true : false;
			if ( ! is_kata ) {
			#>
				<div id="shoppress-elementor-template-library-templates-kata-popup-notice">
					<div id="shoppress-elementor-template-library-templates-kata-popup-notice-wrapper">
						<i id="shoppress-elementor-template-library-templates-kata-popup-notice-close" class="eicon-close" aria-hidden="true"></i>
						<div id="shoppress-elementor-template-library-templates-kata-popup-notice-content">
							<h3><?php esc_html_e( 'Please Note!', 'shop-press' ); ?></h3>
							<p><?php esc_html_e( 'This template you\'re seeing in the image has been crafted using the Kata theme.', 'shop-press' ); ?></p>
							<p><?php esc_html_e( 'As you may be aware, every theme possesses its own distinctive style, so it\'s possible that you\'ll encounter a divergent style upon import.', 'shop-press' ); ?></p>
						</div>
						<div id="shoppress-elementor-template-library-templates-kata-popup-notice-actions">
							<a href="#" data-action="import"><?php esc_html_e( 'Import Now', 'shop-press' ); ?></a>
							<a href="#" data-action="cancel"><?php esc_html_e( 'Cancel', 'shop-press' ); ?></a>
							<a href="https://wordpress.org/themes/kata/" data-action="get-kata" target="_blank"><?php esc_html_e( 'Get Kata Theme', 'shop-press' ); ?></a>
						</div>
					</div>
				</div>
			<# } #>

			<div id="elementor-template-library-templates-container"></div>

			<# if ( 'content' === activeSource ) { #>
				<div id="elementor-template-library-footer-banner">
					<img class="elementor-nerd-box-icon" src="<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'images/information.svg' ); ?>" />
					<div class="elementor-excerpt"><?php echo esc_html__( 'Stay tuned! More awesome templates coming real soon.', 'shop-press' ); ?></div>
				</div>
			<# } #>
		</script>
		<script type="text/template" id="shoppress-tmpl-elementor-template-library-template-pp">
			<div class="elementor-template-library-template-body">
				<# if ( 'page' === type ) { #>
					<div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
				<# } else { #>
					<img src="{{ thumbnail }}">
				<# } #>
				<div class="elementor-template-library-template-preview">
					<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
				</div>
			</div>
			<div class="elementor-template-library-template-footer">
				{{{ shoppress_templates_lib.templates.layout.getTemplateActionButton( obj ) }}}
				<div class="elementor-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
				<div class="elementor-template-library-favorite">
					<input id="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
					<label for="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-label">
						<i class="eicon-heart-o" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php echo esc_html__( 'Favorite', 'shop-press' ); ?></span>
					</label>
				</div>
			</div>
		</script>
		<script type="text/template" id="tmpl-elementor-template-library-get-pro-button-pp">
			<a class="elementor-template-library-template-action elementor-button elementor-go-pro" href="https://powerpackelements.com/pricing/?utm_source=panel-library&utm_campaign=gopro&utm_medium=wp-dash" target="_blank">
				<i class="eicon-external-link-square" aria-hidden="true"></i>
				<span class="elementor-button-title"><?php echo __( 'Go Pro', 'shop-press' ); ?></span>
			</a>
		</script>
		<script type="text/template" id="tmpl-elementor-pro-template-library-activate-license-button-pp">
			<a class="elementor-template-library-template-action elementor-button elementor-go-pro" href="#" target="_blank">
				<i class="eicon-external-link-square"></i>
				<span class="elementor-button-title"><?php _e( 'Activate License', 'shop-press' ); ?></span>
			</a>
		</script>
		<?php
	}
}

TemplatesLib::init();
