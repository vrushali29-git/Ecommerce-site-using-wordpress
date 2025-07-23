<?php
namespace Styler\Compatibilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\Init;
use Styler\BreakPoints\Manager as BreakPointManager;
use Elementor\Plugin;

/**
 *
 * Styler Control for elementor
 */
class ElementorControl extends \Elementor\Control_Base_Multiple {
	/**
	 *
	 * Control type name
	 *
	 * @return string
	 */
	public function get_type() {
		return 'styler';
	}

	/**
	 *
	 * Control enqueue live assets
	 *
	 * @return string
	 */
	public function enqueue() {
		$asset_file = include( STYLER_PATH . 'build/styler-chunk.asset.php');

		wp_enqueue_script( 'styler-elementor-chunk', STYLER_URL . 'build/styler-chunk.js', $asset_file['dependencies'], $asset_file['version'], false );

		$asset_file = include STYLER_PATH . '/build/styler-elementor.asset.php';

		wp_enqueue_script( 'styler-elementor', STYLER_URL . 'build/styler-elementor.js', array_merge( array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), $asset_file['dependencies'] ), $asset_file['version'], false );

		// wp_enqueue_script( 'styler-elementor', STYLER_URL . 'build/styler-elementor.js',[ 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ], '', false );

		add_filter( 'styler-localize-data', array( $this, 'get_localize_data' ), 10, 1 );

		localize_styler_data();

		$ui_theme     = \Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		if( $ui_theme === 'auto' ) {
			wp_enqueue_style(  'styler-style-dark', STYLER_URL . 'build/styler-dark.css', [],  $asset_file['version'], '(prefers-color-scheme: dark)');
		} else if( $ui_theme === 'dark' ) {
			wp_enqueue_style(  'styler-style-dark', STYLER_URL . 'build/styler-dark.css', [],  $asset_file['version'] );
		}

		if ( is_rtl() ) {
			wp_enqueue_style( 'styler-style-rtl', STYLER_URL . 'src/rtl/styler-rtl.css', [],  $asset_file['version'] );
		}

		wp_enqueue_style(  'styler-style-chunk', STYLER_URL . 'build/styler-chunk.css', [],  $asset_file['version'] );
		wp_enqueue_style(  'styler-elementor', STYLER_URL . 'build/styler-elementor.css', [],  $asset_file['version'] );

		wp_set_script_translations(
			'styler-elementor-chunk',
			'styler',
			STYLER_PATH . 'languages'
		);
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public function get_localize_data( $data ) {

		static::modify_elementor_breakpoints();

		$stylerData = get_post_meta( esc_attr( $_REQUEST['post'] ), 'styler_data', true );

		if ( $stylerData ) {
			$stylerData = $this->json_decode( $stylerData );
		}

		if ( ! is_array( $stylerData ) ) {
			$stylerData = array();
		}

		$data['GeneratedStyles'] += $stylerData;
		return $data;
	}

	/**
	 * Overwrite Elementor Breakpoints.
	 *
	 * @since     1.0.0
	 */
	public static function modify_elementor_breakpoints() {

		$default_kit   = Plugin::$instance->kits_manager->get_active_kit();
		$is_valid_kit  = $default_kit && $default_kit->get_main_id();

		if ( ! $is_valid_kit ) {
			return false;
		}

		$kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();
		$page_settings = $kit->get_settings();

		$active_breakpoints = array(
			'viewport_mobile',
			'viewport_mobile_extra',
			'viewport_tablet',
			'viewport_tablet_extra',
			'viewport_laptop',
		);

		$page_settings['active_breakpoints'] = $active_breakpoints;

		$breakpoints = BreakPointManager::get_instance()->get_breakpoints();

		$page_settings['viewport_md'] = isset( $page_settings['viewport_md'] ) && $page_settings['viewport_md'] ? $page_settings['viewport_md'] : $breakpoints[ 'mobile' ]->get_size();
		$page_settings['viewport_lg'] = isset( $page_settings['viewport_lg'] ) && $page_settings['viewport_lg'] ? $page_settings['viewport_lg'] : $breakpoints[ 'tablet' ]->get_size();
		$page_settings['viewport_mobile'] = isset( $page_settings['viewport_mobile'] ) && $page_settings['viewport_mobile'] ? $page_settings['viewport_mobile'] : $breakpoints[ 'mobile' ]->get_size();
		$page_settings['viewport_mobile_extra'] = isset( $page_settings['viewport_mobile_extra'] ) && $page_settings['viewport_mobile_extra'] ? $page_settings['viewport_mobile_extra'] : $breakpoints[ 'mobileLandscape' ]->get_size();
		$page_settings['viewport_tablet'] = isset( $page_settings['viewport_tablet'] ) && $page_settings['viewport_tablet'] ? $page_settings['viewport_tablet'] : $breakpoints[ 'tablet' ]->get_size();
		$page_settings['viewport_tablet_extra'] = isset( $page_settings['viewport_tablet_extra'] ) && $page_settings['viewport_tablet_extra'] ? $page_settings['viewport_tablet_extra'] : $breakpoints[ 'tabletlandscape' ]->get_size();
		$page_settings['viewport_laptop'] = isset( $page_settings['viewport_laptop'] ) && $page_settings['viewport_laptop'] ? $page_settings['viewport_laptop'] : $breakpoints[ 'laptop' ]->get_size();

		$default_kit->update_settings( $page_settings );

		if ( ! get_option( 'elementor_experiment-additional_custom_breakpoints' ) ) {
			update_option( 'elementor_experiment-additional_custom_breakpoints', 'active' );
		}

	}

	/**
	 * Json Decode
	 *
	 * @since     1.0.0
	 */
	private function json_decode( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$data[ $key ] = $this->json_decode( $value );
				} else {
					$data[ $key ] = json_decode( $value, true );
				}
			}
		}

		return $data;
	}


	/**
	 *
	 * Control HTML structure
	 *
	 * @return string
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
			<div class="elementor-control-field">
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper">
					<div class="tmp-styler-dialog-btn <?php echo esc_attr( $this->get_control_uid( 'styler-btn' ) ); ?>"
						data-title="{{ data.label }}"
						data-id="{{view.options.container.id}}"
						data-parent-id="{{view.options.container.parent.type === 'repeater-control' ? view.options.container.parent.parent.id : view.options.container.parent.id }}"
						data-selector="{{ data.selector }}"
						data-wrapper="{{ data.wrapper }}"
						data-type="{{{ view.options.container.type }}}"
						data-is-svg="{{ data.isSVG }}"
						data-is-input="{{ data.isInput }}"
						data-hover-selector="{{ data.hover }}"
					>
						<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'stdata' ) ); ?>" value="" data-setting="stdata"/>
						<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'cid' ) ); ?>" value="" data-value="{{{ data._cid }}}" data-setting="cid"/>
						<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
					</div>
				</div>
			</div>
			<# if( data.description ) { #>
				<div class="elementor-control-field-description">{{ data.description }}</div>
			<# } #>
		<?php
	}
}

add_action(
	'elementor/controls/register',
	function () {

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		\Elementor\Plugin::$instance->controls_manager->register( new ElementorControl() );
	}
);

add_action( 'elementor/core/files/clear_cache', function() {
	if ( current_user_can( 'edit_posts' ) ) {
		$ElementorControl = new \Styler\Compatibilities\ElementorControl();
		$ElementorControl::modify_elementor_breakpoints();
	}
});
