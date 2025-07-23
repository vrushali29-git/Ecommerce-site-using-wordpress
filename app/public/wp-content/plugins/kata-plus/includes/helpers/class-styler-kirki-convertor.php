<?php
/**
 * Styler Kirki Convertor Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\StyleSheet as StyleSheetManager;

/**
 * Class StylerKirkiDataConverter
 *
 * This class is responsible for converting data into a specific format for the Styler application.
 * It uses a singleton pattern to ensure only one instance of the class exists.
 */
class StylerKirkiDataConverter {

	private static $instance = null;

	private function __construct() {
		ini_set( 'xdebug.var_display_max_depth', '-1' );
		ini_set( 'xdebug.var_display_max_children', '-1' );
		ini_set( 'xdebug.var_display_max_data', '-1' );
		ini_set( 'display_errors', '1' );
		ini_set( 'display_startup_errors', '1' );
		error_reporting( E_ALL );
		// Private to prevent instantiation
		// add_action( 'wp_enqueue_scripts', array( $this, 'convert' ) );
	}

	/**
	 * Get the instance of the StylerKirkiDataConverter class.
	 *
	 * @return StylerKirkiDataConverter
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new StylerKirkiDataConverter();
		}

		return self::$instance;
	}

	/**
	 * Convert options stored in the WordPress database to a new structure.
	 * Retrieves options from the $wpdb->options table where the option_name is like 'styler_%'.
	 * Updates the structure of each option using the UpdateStructure method.
	 * Prepares and parses the stylesheet data using the StyleSheetManager class.
	 * Clears the styles array in the StyleSheetManager instance.
	 */
	public function convert() {

		global $wpdb;
			$results = $wpdb->get_results( "SELECT * FROM $wpdb->options WHERE `option_name` LIKE 'styler_%'" );
			$data    = array();

		foreach ( $results as $option ) {
			$key              = $option->option_name;
			$value            = maybe_unserialize( $option->option_value );
			$updatedStructure = $this->UpdateStructure( $key, $value );
			$cid              = preg_replace( '/([styler_]){7}/s', '', $key, 1 );
			// delete_option( $key );
			if ( $updatedStructure ) {
				$data[ $cid ] = $updatedStructure;
			}
		}

		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( ! $term ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		$styles_data  = array();
		$updated_list = array();
		foreach ( $data as $cid => $value ) {
			$value               = stripslashes_deep( $value );
			$styles_data[ $cid ] = array(
				'cid'    => $cid,
				'stdata' => $value,
			);

			$update_term = update_term_meta( $term->term_id, $cid, $styles_data[ $cid ] );
			if ( $update_term ) {
				$updated_list[ $cid ] = $cid;
			}
		}

		update_option( 'kirki_migration_list', $updated_list );

		foreach ( $styles_data as $groupID => $value ) {

			$value = \maybe_unserialize( $value );
			if ( ! $value['stdata'] ) {
				continue;
			}

			StyleSheetManager::get_instance()->prepare( $value['stdata'] );
		}

		StyleSheetManager::get_instance()->parse_content( 'styler', 'kirki', 'kirki' );
		StyleSheetManager::get_instance()->styles = array();

		@unlink( realpath( \Kata_Plus::$upload_dir . '/css/customizer-styler.css' ) );
	}

	// Regular expression to match consecutive slashes that are not part of a URL
	private function removeUnwantedSlashes( $input ) {
		// Split the input string by URLs using a regex pattern
		$pattern = '/(https?:\/\/[^\s\/]+)/';
		$parts   = preg_split( $pattern, $input, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		// Remove consecutive slashes from non-URL parts
		foreach ( $parts as &$part ) {
			if ( ! preg_match( $pattern, $part ) ) {
				$part = preg_replace( '/\/+/', '/', $part );
			}
		}

		// Reconstruct the string
		$result = implode( '', $parts );

		// Remove leading and trailing slashes
		$result = trim( $result, '/' );

		return $result;
	}

	/**
	 * Update the structure of the data based on the given option name and data.
	 *
	 * @param string $optionName
	 * @param array  $data
	 * @return array
	 */
	private function UpdateStructure( $optionName, $data ) {
		$breakpoints = array(
			'desktop',
			'laptop',
			'tablet',
			'tabletlandscape',
			'mobile',
			'smallmobile',
		);

		$actions = array(
			'hover',
			'phover',
			'before',
			'after',
			'placeholder',
		);

		$selectors = array(
			'kata_gdpr_agree_style'             => 'body .kata-gdpr-box button',
			'kata_gdpr_pp_style'                => 'body .kata-gdpr-box .gdpr-button-privacy a',
			'kata_gdpr_content_style'           => 'body .kata-gdpr-box .gdpr-content-wrap p',
			'kata_gdpr_box_style'               => 'body .kata-gdpr-box',
			'styler_kata_preloader'             => 'body .kata-preloader-screen, body .kata-arc-scale .kata-loader .kata-arc, body .kata-arc-scale .kata-loader .kata-arc',
			'kata_styler_back_to_top_wrap'      => 'body #scroll-top .scrollup',
			'kata_styler_back_to_top_wrap_icon' => 'body #scroll-top .scrollup i',
			'kata_scrollbar_cursor_color'       => 'body .nicescroll-rails .nicescroll-cursors',
			'kata_scrollbar_rail_background'    => 'body .nicescroll-rails',
			'kata_styler_container'             => 'body .container, body .elementor-section.elementor-section-boxed>.elementor-container',
			'kata_styler_container'             => 'body',
			'styler_kata_page_title_wrapper'    => 'body.page #kata-page-title',
			'styler_kata_page_title'            => 'body.page  #kata-page-title h1',
			'styler_kata_blog_title_wrapper'    => 'body.blog  #kata-page-title',
			'styler_kata_blog_title'            => 'body.blog #kata-page-title h1.kata-archive-page-title',
			'styler_kata_archive_title_wrapper' => 'body.archive #kata-page-title',
			'styler_kata_archive_title'         => 'body.archive #kata-page-title h1.kata-archive-page-title',
			'styler_kata_archive_title_part1'   => 'body.archive #kata-page-title h1.kata-archive-page-title .kt-tax-name',
			'styler_kata_archive_title_part2'   => 'body.archive #kata-page-title h1.kata-archive-page-title .kt-tax-title',
			'styler_kata_author_title_wrapper'  => '.author #kata-page-title',
			'styler_kata_author_title'          => '.author #kata-page-title h1.kata-archive-page-title',
			'styler_kata_author_title_part1'    => '.author #kata-page-title h1.kata-archive-page-title .kt-tax-name',
			'styler_kata_author_title_part2'    => '.author #kata-page-title h1.kata-archive-page-title .vcard',
			'styler_kata_search_title_wrapper'  => '.search #kata-page-title',
			'styler_kata_search_title'          => '.search #kata-page-title h1.kata-archive-page-title',
			'styler_kata_search_title_part1'    => '.search #kata-page-title h1.kata-archive-page-title .kt-tax-name',
			'styler_kata_search_title_part2'    => '.search #kata-page-title h1.kata-archive-page-title .kt-search-title',
			'styler_body_tag'                   => 'body',
			'styler_all_heading'                => 'h1, h2, h3, h4, h5, h6',
			'styler_h1_tag'                     => 'body h1',
			'styler_h2_tag'                     => 'body h2',
			'styler_h3_tag'                     => 'body h3',
			'styler_h4_tag'                     => 'body h4',
			'styler_h5_tag'                     => 'body h5',
			'styler_h6_tag'                     => 'body h6',
			'styler_p_tag'                      => 'body p',
			'styler_blockquote_tag'             => 'body blockquote',
			'styler_a_tag'                      => 'body a',
			'styler_img_tag'                    => 'body .elementor img, img',
			'styler_button_element'             => 'body a.kata-button',
		);

		$result = array();
		foreach ( $breakpoints as $key ) {

			$selectorKey = preg_replace( '/([styler_]){7}/s', '', $optionName, 1 );

			if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ) {
				$result[ $key ] = array(

					'normal' => array(
						'selector' => $selectors[ $selectorKey ],
						'data'     => $this->setupData( $data[ $key ] ),
					),
				);
			}

			foreach ( $actions as $action ) {
				$newAction = $action === 'phover' ? 'parent-hover' : $action;

				$explodedSelectors = explode( ',', @$selectors[ $selectorKey ] );
				$fixedSelector     = '';
				if ( $action !== 'phover' ) {
					foreach ( $explodedSelectors as $index => $explodedSelector ) {
						$explodedSelectors[ $index ] = trim( $explodedSelector ) . ':' . $action;
					}
					$fixedSelector = implode( ', ', $explodedSelectors );
				}

				if ( isset( $data[ $key . $action ] ) && $data[ $key . $action ] ) {

					if( $key === 'mobile' ) {
						$key = 'mobileLandscape';
					} else if ( $key === 'smallmobile' ) {
						$key = 'mobile';
					}

					if ( ! isset( $result[ $key ] ) ) {
						$result[ $key ] = array();
					}

					$result[ $key ] [ $newAction ] = array(
						'data'     => $this->setupData( $data[ $key . $action ] ),
						'selector' => $fixedSelector,
					);
				}
			}
		}

		return $result;
	}

	/**
	 * Setup the data by replacing semicolons with a delimiter and then splitting the properties into an valid new data array.
	 *
	 * @param string $properties
	 * @return array
	 */
	private function setupData( $properties ) {

		$properties = str_replace( array( ';' ), array( '^^^^' ), $properties );
		$properties = explode( '^^^^', $properties );

		$data = array();

		foreach ( $properties as $key => $property ) {

			$property = $this->removeUnwantedSlashes( $property );
			// resole http: and https: in background property
			$property = str_replace( array( 'http:', 'https:' ), '', $property );
			$property = explode( ':', $property, 2 );

			if ( ! isset( $property[1] ) ) {
				continue;
			}

			$property[1] = trim( $property[1] );
			if ( is_array( $property ) && ! empty( $property[0] ) ) {
				if ( $property[0] === 'outline' && trim( $property[1] ) === 'notset' ) {
					continue;
				}
				// fix background image index
				if ( $property[0] == 'background-image' ) {
					if ( $this->string_is_contain( $property[1], 'url(' ) ) {
						$index       = 'background-image';
						$value       = $property[1];
						$replace_val = $this->get_string_between( $value, '//', 'wp-content' );
						// $value       = str_replace( $replace_val, get_home_url() . '/', $value );
						$value = str_replace( '//http', 'http', $value );

						$image_url = ltrim( trim( $value ), 'url(' );
						$image_url = rtrim( trim( $image_url ), ')' );

						if ( attachment_url_to_postid( $image_url ) ) {
							$data['background-id'] = array(
								'value'     => attachment_url_to_postid( $image_url ),
								'important' => false,
							);
						}

						$data['background'] = array(
							'value'     => 'url(' . $image_url . ')',
							'important' => false,
						);

						$data['background-type'] = array(
							'value'     => 'classic',
							'important' => false,
						);
					}
					// fix background gradient
					if ( $this->string_is_contain( $property[1], 'linear-gradient' ) ) {
						$index = $property[0];
						$value = $property[1];

						$gradient = ltrim( $value, 'linear-gradient(' );
						$gradient = rtrim( $gradient, ')' );
						$gradient = explode( ',', $gradient );

						$data['background-image'] = array(
							'value'     => $value,
							'important' => false,
						);

						$data['background-type'] = array(
							'value'     => 'gradient',
							'important' => false,
						);

						$data['gradient-first-color']  = array(
							'value'     => $gradient[1],
							'important' => false,
						);
						$data['gradient-second-color'] = array(
							'value'     => $gradient[2],
							'important' => false,
						);
						$data['gradient-angle']        = array(
							'value'     => $gradient[0],
							'important' => false,
						);
					}
				} elseif ( $property[0] == 'background-position' ) {
					$index                       = $property[0];
					$value                       = $property[1];
					$data['background-position'] = array(
						'value'     => str_replace( array( ' !important', '!important' ), '', $value ),
						'important' => false,
					);

					$data['background-position-type'] = array(
						'value'     => str_replace( array( ' !important', '!important' ), '', $value ),
						'important' => false,
					);
				} elseif ( $property[0] == 'background-size' ) {
					$index                        = $property[0];
					$value                        = $property[1];
					$data['background-size-type'] = array(
						'value'     => str_replace( array( ' !important', '!important' ), '', $value ),
						'important' => false,
					);
					$data['background-size']      = array(
						'value'     => str_replace( array( ' !important', '!important' ), '', $value ),
						'important' => false,
					);
				} elseif ( $property[0] == '-webkit-background-clip' ) {
					$data['use-as-color']            = array(
						'value'     => 'yes',
						'important' => false,
					);
					$data['-webkit-background-clip'] = array(
						'value'     => 'text',
						'important' => false,
					);
				} elseif ( $property[0] == 'font-family' ) {
					$important = $this->string_is_contain( $property[1], '!important' ) ? true : false;
					$value     = str_replace( array( ' !important', '!important' ), '', $property[1] );
					$value     = str_replace( array( '\\' ,'/', '"' ), array( '', '', "'" ), $value );
					if( $value ) {
						$data[ 'font-family' ] = array(
							'value'     => $value,
							'important' => $important,
						);
					}
				} elseif ( $property[0] == 'transform' ) {
					$index  = $property[0];
					$value  = str_replace( ', ', ',', $property[1] );
					$value  = explode( ' ', $value );
					$newVal = array();
					foreach ( $value as $transform_key => $transform_item ) {
						if ( $this->string_is_contain( $transform_item, 'skew' ) ) {
							$transform_item    = ltrim( $transform_item, 'skew(' );
							$transform_item    = rtrim( $transform_item, ',' );
							$transform_item    = rtrim( $transform_item, ')' );
							$transform_item    = explode( ',', $transform_item );
							$transform_item[0] = 'skewX(' . $transform_item[0] . ')';
							if ( $transform_item[1] ) {
								$transform_item[1] = 'skewY(' . $transform_item[1] . ')';
							}
							$transform_item          = implode( ' ', $transform_item );
							$value[ $transform_key ] = $transform_item;
						}
						if ( $this->string_is_contain( $transform_item, 'translate(' ) ) {
							$transform_item    = ltrim( $transform_item, 'translate(' );
							$transform_item    = rtrim( $transform_item, ',' );
							$transform_item    = rtrim( $transform_item, ')' );
							$transform_item    = explode( ',', $transform_item );
							$transform_item[0] = 'translateX(' . $transform_item[0] . ')';
							if ( $transform_item[1] ) {
								$transform_item[1] = 'translateY(' . $transform_item[1] . ')';
							}
							$transform_item          = implode( ' ', $transform_item );
							$value[ $transform_key ] = $transform_item;
						}
					}
					$value             = implode( ' ', $value );
					$data['transform'] = array(
						'value'     => $value,
						'important' => strpos( $property[1], '!important' ) ? true : false,
					);
				} elseif ( $property[0] == 'box-shadow' ) {
					// Box Shadow object_value
					$obj_val     = explode( ',', $property[1] . ',' );
					$obj_val_out = array();

					$i = 0;
					foreach ( $obj_val as $j => $vals ) {
						$obj_vals = explode( ' ', trim($vals) );

						// $i = $j == 0 ? 1 : rand( 1, 999 );

						if ( sizeof( $obj_vals ) == 6 ) {
							$obj_val_out[ $i ] = array(
								'x'      => $obj_vals[0],
								'y'      => $obj_vals[1],
								'blur'   => $obj_vals[2],
								'spread' => $obj_vals[3],
								'color'  => $obj_vals[4],
								'inset'  => $obj_vals[5] === 'inset' ? true : false,
							);
						} elseif ( sizeof( $obj_vals ) == 5 ) {
							$obj_val_out[ $i ] = array(
								'x'      => $obj_vals[0],
								'y'      => $obj_vals[1],
								'blur'   => $obj_vals[2],
								'spread' => $obj_vals[3],
								'color'  => $obj_vals[4],
								'inset'  => false,
							);
						} elseif ( sizeof( $obj_vals ) == 4 ) {
							$obj_val_out[ $i ] = array(
								'x'      => $obj_vals[0],
								'y'      => $obj_vals[1],
								'blur'   => $obj_vals[2],
								'spread' => '',
								'color'  => $obj_vals[3],
								'inset'  => false,
							);
						} elseif ( sizeof( $obj_vals ) == 3 ) {
							$obj_val_out[ $i ] = array(
								'x'      => $obj_vals[0],
								'y'      => $obj_vals[1],
								'blur'   => $obj_vals[2],
								'spread' => '',
								'color'  => '',
								'inset'  => false,
							);
						} elseif ( sizeof( $obj_vals ) == 3 ) {
							$obj_val_out[ $i ] = array(
								'x'      => $obj_vals[0],
								'y'      => $obj_vals[1],
								'blur'   => '',
								'spread' => '',
								'color'  => '',
								'inset'  => false,
							);
						}
						$i++;
					}

					$data['box-shadow']['value']        = $property[1];
					$data['box-shadow']['object_value'] = $obj_val_out;

				} elseif ( $property[0] == 'text-shadow' ) {

					// Box Shadow object_value
					$obj_val     = explode( ',', $property[1] . ',' );
					$obj_val_out = array();

					$i = 0;
					foreach ( $obj_val as $j => $vals ) {
						$obj_vals = explode( ' ', trim($vals) );

						if ( sizeof( $obj_vals ) == 4 ) {
							$obj_val_out[ $i ] = array(
								'x'     => $obj_vals[0],
								'y'     => $obj_vals[1],
								'blur'  => $obj_vals[2],
								'color' => $obj_vals[3],
							);
						} elseif ( sizeof( $obj_vals ) == 3 ) {
							$obj_val_out[ $i ] = array(
								'x'     => $obj_vals[0],
								'y'     => $obj_vals[1],
								'blur'  => $obj_vals[2],
								'color' => '#000000',
							);
						} elseif ( sizeof( $obj_vals ) == 2 ) {
							$obj_val_out[ $i ] = array(
								'x'     => $obj_vals[0],
								'y'     => $obj_vals[1],
								'blur'  => '0',
								'color' => '#000000',
							);
						}
						$i++;
					}

					$data['text-shadow']['value']        = $property[1];
					$data['text-shadow']['object_value'] = $obj_val_out;
				} else {
					$index = $property[0];
					$value = $property[1];

					// Fix before & after
					if ( $index === 'content' ) {
						$value = str_replace( '"', "'", $value );
					}

					if ( $index == 'background-image' && $this->string_is_contain( $value, 'linear-gradient' ) ) {
						$data['background-type'] = array(
							'value'     => 'gradient',
							'important' => false,
						);
					} elseif ( ( $index == 'background' && $this->string_is_contain( $value, 'url(' ) ) || ( $index == 'background-color' ) ) {
						$data['background-type'] = array(
							'value'     => 'classic',
							'important' => false,
						);
					}

					$important = $this->string_is_contain( $value, '!important' ) ? true : false;
					$value     = str_replace( array( ' !important', '!important' ), '', $value );

					$data[ $index ] = array(
						'value'     => $value,
						'important' => $important,
					);

					// Box Shadow object_value
					// if ( $index == 'box-shadow' ) {
					// $data[ $index ]['object_value'] = explode( ',', $value );
					// }

					// filter object_value
					if ( $index == 'filter' ) {

						$filter_obj_val      = explode( ' ', $value );
						$filter_obj_val_data = array();

						foreach ( $filter_obj_val as $j => $val ) {
							$val                            = str_replace( ')', '', $val );
							$val                            = explode( '(', $val );
							$filter_obj_val_data[ $val[0] ] = $val[1];
						}
						$data[ $index ]['object_value'] = $filter_obj_val_data;
					}

					// backdrop-filter object_value
					elseif ( $index == 'backdrop-filter' ) {

						$filter_obj_val      = explode( ' ', $value );
						$filter_obj_val_data = array();

						foreach ( $filter_obj_val as $j => $val ) {
							$val                            = str_replace( ')', '', $val );
							$val                            = explode( '(', $val );
							$filter_obj_val_data[ $val[0] ] = $val[1];
						}
						$data[ $index ]['object_value'] = $filter_obj_val_data;
					}

					// transform object_value
					elseif ( $index == 'font-family' ) {
						$important = $this->string_is_contain( $value, '!important' ) ? true : false;
						$value     = str_replace( array( ' !important', '!important' ), '', $value );
						$value     = str_replace( array( '/', '"' ), array( '', "'" ), $value );

						$data[ $index ] = array(
							'value'     => $value,
							'important' => $important,
						);
					}
					elseif ( $index == 'transform' ) {

						$filter_obj_val      = explode( ' ', $value );
						$filter_obj_val_data = array();

						foreach ( $filter_obj_val as $j => $val ) {
							$val = str_replace( ')', '', $val );
							$val = explode( '(', $val );

							$filter_obj_val_data[ $val[0] ] = $val[1];

							// setup translate obj
							if ( $val[0] == 'translate' ) {
								$translate                         = explode( ',', $filter_obj_val_data['translate'] );
								$filter_obj_val_data['translateX'] = $translate[0];
								$filter_obj_val_data['translateY'] = $translate[1];
								unset( $filter_obj_val_data['translate'] );
							}

							// setup skew obj
							if ( $val[0] == 'skew' ) {
								$skew                         = explode( ',', $filter_obj_val_data['skew'] );
								$filter_obj_val_data['skewX'] = $skew[0];
								$filter_obj_val_data['skewY'] = $skew[1];
								unset( $filter_obj_val_data['skew'] );
							}
						}

						$data[ $index ]['object_value'] = $filter_obj_val_data;
					} else {
						$data[ $property[0] ]['value'] = $value;
					}

					$do_not_add_unit = $index !== 'box-shadow' && $index !== 'transform' && $index !== 'filter' && $index !== 'backdrop-filter' && ! $this->string_is_contain( $value, 'linear-gradient' );

					if ( $do_not_add_unit && $this->string_is_contain( $value, 'px' ) ) {
						$data[ $property[0] ]['unit'] = 'px';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, 'em' ) ) {
						$data[ $property[0] ]['unit'] = 'em';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, '%' ) ) {
						$data[ $property[0] ]['unit'] = '%';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, 'deg' ) ) {
						$data[ $property[0] ]['unit'] = 'deg';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, 'vw' ) ) {
						$data[ $property[0] ]['unit'] = 'vw';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, 'vh' ) ) {
						$data[ $property[0] ]['unit'] = 'vh';
					} elseif ( $do_not_add_unit && $this->string_is_contain( $value, 'ms' ) ) {
						$data[ $property[0] ]['unit'] = 'ms';
					}
				}

				if ( strpos( $property[1], '!important' ) !== false ) {
					$data[ $property[0] ]['important'] = true;
				}
			}
		}

		return $data;
	}

	/**
	 * String is Contain
	 *
	 * @param $string string to search
	 * @param $search search for charachter
	 * @since   1.3.0
	 */
	public function string_is_contain( $string, $search ) {

		if ( strpos( $string, $search ) !== false ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the substring between two given strings in a larger string.
	 *
	 * @param string $string
	 * @param string $start
	 * @param string $end
	 * @return string
	 */
	public function get_string_between( $string, $start, $end ) {
		$string = ' ' . $string;
		$ini    = strpos( $string, $start );
		if ( $ini == 0 ) {
			return '';
		}
		$ini += strlen( $start );
		$len  = strpos( $string, $end, $ini ) - $ini;
		return substr( $string, $ini, $len );
	}
}

// Usage
// StylerKirkiDataConverter::getInstance();
