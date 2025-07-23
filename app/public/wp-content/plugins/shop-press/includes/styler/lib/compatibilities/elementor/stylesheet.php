<?php
namespace Styler\Compatibilities\Elementor;

ob_start();
/**
 * Styler StyleSheet Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CssCrush\Functions;
use Styler\Compatibilities\Elementor;
use Styler\StyleSheet as StyleSheetManager;
use Elementor\Plugin;
use Styler\Init;

class StyleSheet {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;
	private static $data = array();
	private $styles_data = array();


	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Define the functionality .
	 *
	 * Load the dependencies.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		$this->actions();
	}

	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function actions() {
		add_action( 'elementor/css-file/post/parse', array( $this, 'parse_styles' ) );
		add_action( 'styler/parse/elementor/preview', array( $this, 'parse_preview_styles' ) );
		// add_action( 'elementor/css-file/post/enqueue', [$this, 'enqueue_style'] );
		add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'enqueue_style' ) );
		add_action( 'templates/manager/enqueue', array( $this, 'enqueue_template_manager_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_current_page_style' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_inline_styles' ), 100 );
		add_filter( 'styler/breakPoints/breakpoint/size', array( $this, 'modify_styler_breakpoints_size' ), 10, 2 ) ;
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_template_manager_style( $id ) {
		if ( ! is_preview() && ! Plugin::$instance->preview->is_preview_mode() ) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$id}.css" ) ) ) ) {
				wp_enqueue_style( 'styler-post-' . $id, implode( '/', array( get_styler_upload_url(), 'elementor', 'css', "post-{$id}.css" ) ), array( 'elementor-frontend' ) );
			}
		}
	}

	/**
	 * Modify Styler Breakpoints Size
	 *
	 * @since     1.0.0
	 */
	public function modify_styler_breakpoints_size( $size, $breakpoint ) {

		// Create an array of breakpoint names
		$breakpoint_names = array();
		$breakpoint_names['mobileLandscape'] = 'viewport_mobile_extra';
		$breakpoint_names['mobile'] = 'viewport_mobile';
		$breakpoint_names['tablet'] = 'viewport_tablet';
		$breakpoint_names['tabletlandscape'] = 'viewport_tablet_extra';
		$breakpoint_names['laptop'] = 'viewport_laptop';

		// Check if the breakpoint name is in the array
		if( isset( $breakpoint_names[ $breakpoint->get_name() ] ) ) {
			// Get the active kit
			$default_kit   = Plugin::$instance->kits_manager->get_active_kit();
			// Get the page settings
			$page_settings = $default_kit->get_settings();
			// Check if the breakpoint size is set in the page settings
			if( isset( $page_settings[ $breakpoint_names[ $breakpoint->get_name() ] ] )
				&& $page_settings[ $breakpoint_names[ $breakpoint->get_name() ] ] ) {
					// Return the breakpoint size if it is set
					return $page_settings[ $breakpoint_names[ $breakpoint->get_name() ] ];
			}
		}

		// Return the size if the breakpoint size is not set
		return $size;
	}

	/**
	 * Enqueue Generated Style
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style( $document = false ) {

		$id            = $document->get_post()->ID;
		$elementor_css = get_post_meta( $id, '_elementor_css' , true );
		$time          = get_post_modified_time( 'ymdHis', false, $document->get_post(), true );

		if( is_array( $elementor_css ) && isset( $elementor_css['time'] ) ) {
			$time = $elementor_css['time'];
		}

		$url = implode( '/', array( get_styler_upload_url(), 'elementor', 'css', "post-{$id}.css?ver=" . $time ) );

		if ( ! is_preview() && ! Plugin::$instance->preview->is_preview_mode() ) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$id}.css" ) ) ) ) {
				wp_enqueue_style( 'styler-post-' . $id, $url, array( 'elementor-frontend' ), null );
			}
		} elseif ( $id !== get_the_ID() && Plugin::$instance->preview->is_preview_mode() ) {
			$this->enqueue_inline_styles( $id, true );
		} elseif ( $id !== get_the_ID() && is_preview() && ! Plugin::$instance->preview->is_preview_mode() ) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$id}.css" ) ) ) ) {
				wp_enqueue_style( 'styler-post-' . $id, $url, array( 'elementor-frontend' ), null );
			}
		} elseif ( $id !== get_the_ID() ) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$id}.css" ) ) ) ) {
				wp_enqueue_style( 'styler-post-' . $id, $url, array( 'elementor-frontend' ), null );
			}
		}
	}

	/**
	 * Enqueue Generated Style
	 *
	 * @since     1.0.0
	 */
	public function enqueue_current_page_style() {
		$id = get_the_ID();
		if ( ! is_preview() && ! Plugin::$instance->preview->is_preview_mode() ) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$id}.css" ) ) ) ) {
				$elementor_css = get_post_meta( $id, '_elementor_css' , true );
				$time          = get_post_modified_time( 'ymdHis', false, $id, true );

				if( is_array( $elementor_css ) && isset( $elementor_css['time'] ) ) {
					$time = $elementor_css['time'];
				}

				$url = implode( '/', array( get_styler_upload_url(), 'elementor', 'css', "post-{$id}.css?ver=" . $time ) );
				wp_enqueue_style( 'styler-post-' . $id, $url, array( 'elementor-frontend' ), null );
			}
		}
	}

	/**
	 * Enqueue Generate Style as Inline
	 *
	 * @since     1.0.0
	 */
	public function enqueue_inline_styles( $id = false, $force = false ) {
		$id = ! $id ? get_the_ID() : $id;
		if ( is_preview() || Plugin::$instance->preview->is_preview_mode() || $force ) {
			$this->parse_preview_styles( $id, true );
			$styler_object = get_post_meta( $id, 'styler_object', true );
			$styler_object = is_array( $styler_object ) ? $styler_object : array();

			foreach ( $styler_object as $key => $value ) {
				$css = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $style ) {
						$css .= $style;
					}
				} else {
					$css .= $value;
				}

				$css = str_replace( '.elementor-' . $id . ' ', '', $css );

				printf( '<style id="%1$s">%2$s</style>', 'styler-' . $key, $css );
			}
		}
	}

	public function parse_styles( $stylesheet ) {

		if ( is_user_logged_in() && isset( $_REQUEST['preview_id'] ) && $_REQUEST['preview_id'] == $stylesheet->get_post_id() ) {
			do_action( 'styler/parse/elementor/preview', $stylesheet );
		}
		$this->styles_data = array();
		$ID                = $stylesheet->get_post_id();
		$data              = get_post_meta( $ID, '_elementor_data', true );

		if ( ! is_array( $data ) ) {
			$data = json_decode( $data, true );
		}

		static::$data      = array();
		$newData           = $this->parseData( $data );
		$this->styles_data = static::$data;

		$this->__toString( $ID, false );
		$this->enqueue_inline_styles( $ID, true );
	}

	public function parse_preview_styles( $stylesheet, $stylesheetIsID = false ) {
		if ( $stylesheetIsID ) {
			$revisions = wp_get_latest_revision_id_and_total_count( $stylesheet );
			$orgID     = $stylesheet;
		} else {
			$revisions = wp_get_latest_revision_id_and_total_count( $stylesheet->get_post_id() );
			$orgID     = $stylesheet->get_post_id();
		}

		if ( is_object( $revisions ) ) {
			return;
		}
			// $this->parse_preview_styles(  );
		$ID = $revisions['latest_id'];

		if ( ! $ID ) {
			return;
		}

		$this->styles_data = array();
		// $ID = $stylesheet->get_post_id();

		$data = get_post_meta( $ID, '_elementor_data', true );

		if ( ! is_array( $data ) ) {
			$data = json_decode( $data, true );
		}

		static::$data = array();
		$newData      = $this->parseData( $data );

		if( ! static::$data ) {
			$data = get_post_meta( $orgID, '_elementor_data', true );

			if ( ! is_array( $data ) ) {
				$data = json_decode( $data, true );
			}

			static::$data = array();
			$newData      = $this->parseData( $data );
		}

		$this->styles_data = static::$data;


		$this->styles_data = \array_reverse( $this->styles_data, true );
		$this->__toString( $ID, $orgID );
		$this->enqueue_inline_styles( $ID, true );
	}

	/**
	 * Pre Parse the given element data and extract specific settings information.
	 *
	 * @param array $elementData
	 * @return array
	 */
	private function preParseData($elementData) {
		foreach ($elementData as $ee => $element) {
			$this->processElement($element);
		}
	}

	private function processElement($element) {
		if (isset($element['elements']) && !empty($element['elements'])) {
			foreach ($element['elements'] as $subElement) {
				$this->processSubElement($subElement);
			}
		}

		if (isset($element['settings']) && is_array($element['settings'])) {
			$this->processSettings($element);
		}
	}

	private function processSubElement($subElement) {
		if (isset($subElement['settings']) && is_array($subElement['settings'])) {
			foreach ($subElement['settings'] as $settingName => $setting) {
				$this->processSetting($subElement, $settingName, $setting);
			}
		}

		if (isset($subElement['elements']) && $subElement['elements']) {
			$this->preParseData(['' => $subElement]);
		}

		if (isset($subElement['inner_items']) && $subElement['inner_items']) {
			$this->preParseData(['' => $subElement]);
		}
	}

	private function processSetting($subElement, $settingName, $setting) {
		if (( is_array($setting) || is_object($setting)) && isset($setting['stdata']) && strpos(json_encode($setting), 'stdata') !== false) {
			$this->updateStaticData($subElement, $settingName, $setting);
		} elseif (( is_array($setting) || is_object($setting))) {
			$this->processNestedSetting($subElement, $setting, $settingName);
		}
	}

	private function processNestedSetting($subElement, $setting, $settingName) {
		foreach ($setting as $index => $item) {
			if ($item && is_array($item) && isset($item['_id']) && strpos(json_encode($item), 'stdata') !== false) {
				foreach ($item as $itemSettingName => $itemSettingValue) {
					$this->processItemSetting($subElement, $settingName, $itemSettingName, $itemSettingValue, $item);
				}
			}
		}
	}

	private function processItemSetting($subElement, $settingName, $itemSettingName, $itemSettingValue, $item) {

		if ((is_array($itemSettingValue) || is_object($itemSettingValue)) && isset($itemSettingValue['stdata']) && strpos(json_encode($itemSettingValue), 'stdata') !== false) {
			$this->updateStaticDataForItem($subElement, $settingName, $itemSettingName, $itemSettingValue, $item);
		}
	}

	private function processSettings($element) {
		foreach ($element['settings'] as $settingName => $setting) {
			if (is_array($setting) && strpos(json_encode($setting), 'stdata') !== false) {
				$this->updateStaticData($element, $settingName, $setting);
			}
		}
	}

	private function updateStaticData($element, $settingName, $setting) {
		static::$data[$element['id']]['widget'] = @$element['widgetType'] ?: $element['elType'];
		static::$data[$element['id']]['id'] = @$element['id'];
		static::$data[$element['id']]['settings'][$settingName] = [
			'el' => @$settingName,
			'cid' => @$element['cid'],
			'data' => @$setting,
			'elType' => @$element['elType'],
		];
	}

	private function updateStaticDataForItem($subElement, $settingName, $itemSettingName, $itemSettingValue, $item) {
		static::$data[$subElement['id']]['widget'] = @$subElement['widgetType'] ?: $subElement['elType'];
		static::$data[$subElement['id']]['elType'] = @$subElement['elType'];
		static::$data[$subElement['id']]['is_repeater'] = true;
		static::$data[$subElement['id']]['inner_items'][$settingName][$itemSettingName][] = [
			'id' => @$item['_id'],
			'el' => @$itemSettingName,
			'control_parent' => @$settingName,
			'cid' => @$itemSettingValue['cid'],
			'data' => @$itemSettingValue,
		];
	}

	private static function buildData( $data, $parent_key = false ) {
		foreach ( $data as $key => $element ) {
			if( isset( $element['settings'] ) ) {
				foreach ( $element['settings'] as $setting_name => $setting ) {
					if( isset( $setting['data'] ) && isset( $setting['data']['cid'] ) ) {
						if( $parent_key ) {
							static::$data[ $parent_key ][ $setting['data']['cid'] ] = $setting['data']['stdata'];
						} else {
							static::$data[ $key ][ $setting['data']['cid'] ] = $setting['data']['stdata'];
						}
					} else if ( is_array( $setting ) ) {
						foreach ( $setting as $s_key => $s_value) {
							if( ! isset( $s_value['data']['cid'] ) ) {
								$s_value['data']['cid'] = self::generateUniqueId();
							}

							if( isset( $s_value['data'] ) ) {
								if( $parent_key ) {
									static::$data[ $parent_key ][ $s_value['data']['cid'] ] = isset( $s_value['data']['stdata'] ) ? $s_value['data']['stdata'] : '';
								} else {
									static::$data[ $key ][ $s_value['data']['cid'] ] = isset( $s_value['data']['stdata'] ) ? $s_value['data']['stdata'] : '';
								}
							}
						}
					}

				}
			}


			if( isset( $element['inner_items'] ) ) {
				self::buildData( $element['inner_items'], $key );
			}
		}
	}

	/**
	 * Parse the given element data and extract specific settings information.
	 *
	 * @param array|string $elementData
	 * @return array
	 */
	private function parseData($elementData) {

		if (empty($elementData)) {
			return [];
		}

		$decodedData = is_array($elementData) ? $elementData : json_decode($elementData, true);

		static::$data = [];
		$this->preParseData($decodedData);

		$newData = [];

		foreach (static::$data as $key => $element) {
			if( ! is_array( $element['settings'] ) ) {
				continue;
			}

			foreach ($element['settings'] as $index => $setting) {
				$newData[$key]['id'] = $key;
				$newData[$key]['cid'] = $setting['cid'];
				$newData[$key]['el'] = $setting['el'];
				$newData[$key]['elType'] = $setting['elType'];
				$newData[$key]['widget'] = $element['widget'];
				$newData[$key]['settings'][$index] = $setting['data'];
			}

			if (isset($element['is_repeater']) && $element['is_repeater'] === true) {
				foreach ($element['inner_items'] as $itemIndex => $item) {
					foreach ($item as $itemControlId => $itemControlData) {
						if( isset( $itemControlData['data'] ) ) {
							$newData[$key]['is_repeater'] = true;
							$newData[$key]['inner_items'][$itemIndex][$itemControlId] = [
								'settings' => $itemControlData['data'],
								'id' => $itemControlData['id'],
								'el' => $itemControlData['el'],
								'control_parent' => $itemControlData['control_parent'],
								'cid' => $itemControlData['cid'],
							];
						} else {
							foreach ($itemControlData as $icd_key => $icd_value) {
								if( isset( $icd_value['data'] ) ) {
									$newData[$key]['is_repeater'] = true;
									$newData[$key]['inner_items'][$itemIndex][$itemControlId][$icd_key] = [
										'settings' => $icd_value['data'],
										'id' => $icd_value['id'],
										'el' => $icd_value['el'],
										'control_parent' => $icd_value['control_parent'],
										'cid' => $icd_value['cid'],
									];
								}
							}
						}
					}
				}
			}
		}

		static::$data = [];

		$finalizedData = self::finalize($newData);

		return self::buildData($finalizedData);
	}

	/**
	 * Finalizes the data by processing and formatting it before returning.
	 *
	 * @param array $pre_data The Pre data to be finalized.
	 * @param int   $post_id The ID of the post.
	 * @return array The finalized data.
	 */
	private static function finalize($preData) {
		$finalData = [];

		foreach ($preData as $key => $element) {
			$widget = $element['widget'];
			$widgetsManager = \Elementor\Plugin::$instance->widgets_manager;
			$elementWidget = $widgetsManager->get_widget_types($widget);

			if( ! $elementWidget ) {
				$widgetsManager = \Elementor\Plugin::$instance->elements_manager;
				$elementWidget  = $widgetsManager->get_element_types( $widget );
			}

			foreach ($element['settings'] as $index => $setting) {
				$result = self::handleFinalData($elementWidget, $setting, $element, $index);
				$cid = self::generateUniqueId();
				$finalData[$key]['el'] = $element['el'];
				$finalData[$key]['cid'] = $cid;
				$finalData[$key]['settings'][$index]['data'] = $result;
			}

			if (isset($element['is_repeater']) && $element['is_repeater'] === true) {
				foreach ($element['inner_items'] as $itemIndex => $item) {
					foreach ($item as $itemControlId => $itemControlData) {
						if( isset( $itemControlData['settings'] ) ) {
							$result = self::handleFinalData($elementWidget, $itemControlData['settings'], $element, $itemControlData['control_parent'], $itemControlData['el']);
							$cid = self::generateUniqueId();
							$finalData[$key]['settings']['el'] = $element['el'];
							$finalData[$key]['settings']['cid'] = $cid;
							$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId]['data'] = $result;
							$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId]['id'] = $itemControlData['id'];
							$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId]['el'] = $widget;
						} else {
							foreach ($itemControlData as $icd_key => $icd_value) {
								if( isset( $icd_value['settings'] ) ) {
									$result = self::handleFinalData($elementWidget, $icd_value['settings'], $element, $icd_value['control_parent'], $icd_value['el'], $icd_key );
									$cid = self::generateUniqueId();
									$finalData[$key]['settings']['el'] = $element['el'];
									$finalData[$key]['settings']['cid'] = $cid;

									$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId][$icd_key]['data'] = $result;
									$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId][$icd_key]['id'] = $icd_value['id'];
									$finalData[$key]['inner_items'][$itemIndex]['settings'][$itemControlId][$icd_key]['el'] = $widget;
								}
							}
						}
					}
				}
			}
		}

		return $finalData;
	}

	private static function generateUniqueId() {
		return str_shuffle(md5(microtime(true)));
	}

	private static function handleFinalData( $e_widget, $setting, $element, $el = false, $subEl = false, $index = false ) {

		$data = array();

		$setting['stdata'] = is_array( $setting['stdata'] ) ? json_encode( $setting['stdata'] ) : $setting['stdata'];
		if( preg_match('/"font-family":{"value":"(.*?)"/', $setting['stdata'], $matches ) ) {
			$setting['stdata'] = preg_replace('/"font-family":{"value":"(.*?)"/', '"font-family":{"value":"' . str_replace( '"', "'", $matches[1] ) . '"', $setting['stdata'] );
		}

		$setting['stdata'] = json_decode( $setting['stdata'], true );
		$setting['stdata'] = is_array( $setting['stdata'] ) ? $setting['stdata'] : array();

		foreach ( $setting['stdata'] as $device => $styleObject ) {
			foreach ( $styleObject as $state => $stateObject ) {
				$selector = '';
				if ( $e_widget ) {
					$widgetControls = $e_widget->get_controls();

					if ( isset( $widgetControls[ $el ] ) ) {

						if( $subEl ) {
							$control = $widgetControls[ $el ]['fields'][$subEl];
						} else {
							$control = $widgetControls[ $el ];
						}

						if ( isset( $control['selectors'] ) ) {
							$elData          = $setting['stdata'][ $device ][ $state ];
							$selectorControl = '';
							if( ! is_array( $control['selectors'] ) ) {
								$selectorControl = $control['selectors'];
							} else {
								$selectorControl = array_key_first( $control['selectors'] );
							}
						} else {
							$selectorControl = $control['selector'];
						}

						$elData = $setting['stdata'][ $device ][ $state ];

						$element_id = $element['id'];

						if( $subEl ) {
							$repeaterElementID = $index !== false ? $element['inner_items'][$el][$subEl][$index]['id'] : $element['inner_items'][$el][$subEl]['id'];
							$selectorControl = str_replace('{{CURRENT_ITEM}}', '.elementor-repeater-item-' . $repeaterElementID, $selectorControl );
						}

						if ( isset( $control['wrapper'] ) ) {
							$wrapper = str_replace( '{{WRAPPER}}', '.elementor-element.elementor-element-' . $element_id, $control['wrapper'] );
						} else {
							$wrapper = '.elementor-element.elementor-element-' . $element_id;
						}

						if ( $state === 'parent-hover' ) {
							$wrapper = $wrapper . ':hover';
						}

						if ( strpos( $selectorControl, '{{WRAPPER}}' ) === false ) {
							$selectorControl = $wrapper . ' ' . $selectorControl;
						}

						$selector = str_replace( '{{WRAPPER}}', $wrapper, $selectorControl );
					}
				} else {

					$element_id = ! $subEl ? $element['id'] : $subEl['id'];

					$selector = '.elementor-element.elementor-element-' . $element_id;

					if ( $state === 'parent-hover' ) {
						$selector = $selector . ':hover';
					}
				}

				$stateMapping = array(
					'normal'       => '',
					'parent-hover' => '',
					'hover'        => ':hover',
					'active'       => ':active',
					'before'       => ':before',
					'after'        => ':after',
					'placeholder'  => '::placeholder',
				);

				$selector = trim( $selector ) . $stateMapping[ $state ];

				$selector = str_replace( '::placeholder:hover', ':hover::placeholder', $selector );

				$selector = str_replace( '"', "'", $selector );

				if( trim( $selector ) && isset( $stateObject['data'] ) ) {
					$data[ $device ][ $state ] = array(
						'data'     => $stateObject['data'],
						'selector' => $selector,
					);
				}
			}
		}


		$setting['stdata'] = json_encode( $data );

		return $setting;
	}

	private function searchInArray( $key, $array, $id = '', $results = array() ) {
		if ( is_array( $array ) || is_object( $array ) ) {
			$array = (array) $array;

			if ( isset( $array['id'] ) ) {
				$id = $array['id'];
			}

			if ( isset( $array[ $key ] ) ) {
				$cid                    = $array['cid'];
				$results[ $id ][ $cid ] = $array[ $key ];
			}

			foreach ( $array as $subarray ) {
				$results = $this->searchInArray( $key, $subarray, $id, $results );
			}
		}

		return $results;
	}

	/**
	 * __toString
	 *
	 * @since     1.0.0
	 */
	public function __toString() {
		if ( func_num_args() > 0 ) {
			$postID = func_get_arg( 0 );
			if ( func_get_arg( 1 ) === false ) {
				$orgPostID = func_get_arg( 0 );
			} else {
				$orgPostID = func_get_arg( 1 );
			}
		} else {
			$postID = false;
		}

		$styler_object = array();
		$styler_data   = array();


		foreach ( $this->styles_data as $groupID => $value ) {

			foreach ( \array_unique( \array_reverse( $value, true ) ) as $cid => $style ) {

				if ( ! $style || $style === '[]') {
					continue;
				}

				if ( empty( $cid ) ) {
					$cid = key( $style );
				}

				if ( is_array( $style ) ) {
					$style = current( $style );
				}

				if ( is_object( $style ) || is_array( $style ) ) {
					$style = json_encode( $style );
				}

				if( strpos( $style, '.elementor-repeater-item-' . $groupID ) > -1 ) {
					$groupID = preg_replace( '/.*.elementor-element-(.*?)[:| |\|."].*/s', '$1', $style );
				}

				if ( is_numeric( $groupID ) || $groupID === '[]' ) {
					$groupID = preg_replace( '/.*.elementor-element-(.*?)[:| |\|."].*/s', '$1', $style );
				}

				$style  = \str_replace( '{{WRAPPER}}', '.elementor-element-' . $groupID, $style );
				$prevID = preg_replace( '/.elementor-element-(.*?)[:| |\|."].*/s', '$1', $style );
				$style  = \str_replace( $prevID, $groupID, $style );
				$style  = \str_replace( '  .', ' .', $style );
				$style  = \str_replace( ".elementor-element.elementor-element-{$groupID}.elementor-repeater-item", ".elementor-element.elementor-element-{$groupID} .elementor-repeater-item", $style );

				if( preg_match('/"font-family":{"value":"(.*?)"/', $style, $matches ) ) {
					$style = preg_replace('/"font-family":{"value":"(.*?)"/', '"font-family":{"value":"' . str_replace( '"', "'", $matches[1] ) . '"', $style );
				}

				$style  = json_decode( $style, true );

				if ( ! is_array( $style ) ) {
					continue;
				}

				foreach ( $style as $device => $states ) {

					if( isset( $style[ $device ]['before'] ) ) {
						if( ! isset( $style[ $device ]['before']['data']['content'] ) ) {
							$style[ $device ]['before']['data']['content']['value'] = "''";
						} else {

							$style[ $device ]['before']['data']['content']['value'] = str_replace( "'", "", $style[ $device ]['before']['data']['content']['value'] );
							$style[ $device ]['before']['data']['content']['value'] = "'{$style[ $device ]['before']['data']['content']['value']}'";
						}
					}

					if( isset( $style[ $device ]['after'] ) ) {
						if( ! isset( $style[ $device ]['after']['data']['content'] ) ) {
							$style[ $device ]['after']['data']['content']['value'] = "''";
						} else {
							$style[ $device ]['after']['data']['content']['value'] = str_replace( "'", "", $style[ $device ]['after']['data']['content']['value'] );
							$style[ $device ]['after']['data']['content']['value'] = "'{$style[ $device ]['after']['data']['content']['value']}'";
						}
					}

					foreach ( $states as $state => $value ) {

						if ( strpos( $value['selector'], '.elementor-element.elementor-element-' . $groupID ) === false ) {
							$style[ $device ][ $state ]['selector'] = str_replace( '.elementor-element-' . $groupID, '.elementor-element.elementor-element-' . $groupID, $style[ $device ][ $state ]['selector'] );
							$value['selector']                      = $style[ $device ][ $state ]['selector'];
						}

						if ( strpos( $value['selector'], '.elementor-' . $orgPostID ) !== false ) {
							continue;
						}

						$classes = explode( ',', $style[ $device ][ $state ]['selector'] );
						foreach ( $classes as $classIndex => $class ) {
							$classes[ $classIndex ] = '.elementor-' . $orgPostID . ' ' . trim( $class );
						}

						$style[ $device ][ $state ]['selector'] = implode( ', ', $classes );
					}
				}


				$style = json_encode( $style );

				$parsed_style = StyleSheetManager::get_instance()->prepare( $style );

				if ( is_array( $value ) ) {
					$styler_object[ $groupID ] [ $cid ] = $parsed_style;
					$styler_data[ $groupID ] [ $cid ]   = $style;
				} else {
					$styler_object[] = $parsed_style;
				}
			}
		}

		$this->styles_data = array();

		if ( $postID ) {
			update_post_meta( $postID, 'styler_object', $styler_object );
			update_post_meta( $postID, 'styler_data', $styler_data );
		}

		StyleSheetManager::get_instance()->parse_content( $postID, 'post', 'elementor' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}

} // Class

StyleSheet::get_instance();
