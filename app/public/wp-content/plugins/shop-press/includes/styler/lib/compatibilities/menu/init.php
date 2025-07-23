<?php
/**
 * /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 *
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Styler_Walker_Nav_Menu_Edit {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;

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
		// $this->dependencies();
		$this->actions();
	}


	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function actions() {
		// Print Styler Wrapper
		add_action( 'kata-plus-menu-edit', array( $this, 'edit_walker' ), 10, 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 10 );
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_menu_styler_field' ) );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_menu_styler_field' ), 10, 3 );
	}

	/**
	 *
	 * Control enqueue live assets
	 *
	 * @return string
	 */
	public function enqueue() {

		$screen = get_current_screen( );
		if ( 'nav-menus' !== $screen->id ) {
			return;
		}

		$asset_file = include( STYLER_PATH . 'build/styler-chunk.asset.php');

		wp_enqueue_script( 'styler-menu-chunk', STYLER_URL . 'build/styler-chunk.js', $asset_file['dependencies'], $asset_file['version'], false );

		$asset_file = include STYLER_PATH . '/build/styler-menu.asset.php';

		wp_enqueue_script( 'styler-menu', STYLER_URL . 'build/styler-menu.js', array_merge( array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), $asset_file['dependencies'] ), $asset_file['version'], false );

		// wp_enqueue_script( 'styler-menu', STYLER_URL . 'build/styler-menu.js',[ 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ], '', false );

		add_filter( 'styler-localize-data', array( $this, 'get_localize_data' ), 10, 1 );

		Styler\LocalizeData::localize();

		wp_enqueue_style(  'styler-style-dark', STYLER_URL . 'build/styler-dark.css', [],  $asset_file['version'], '(prefers-color-scheme: dark)');

		wp_enqueue_style(  'styler-style-chunk', STYLER_URL . 'build/styler-chunk.css', [],  $asset_file['version'] );
		wp_enqueue_style(  'styler-menu', STYLER_URL . 'build/styler-menu.css', [],  $asset_file['version'] );

		echo "<div id=\"tmp-styler-wrap\"></div>";
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public function get_localize_data( $data ) {
		// get wp menus
		$menus = wp_get_nav_menus();
		foreach ($menus as $key => $menu) {

			$menu_items = wp_get_nav_menu_items( $menu );

			foreach ( $menu_items as $menu_item ) {
				$styler_data = get_post_meta( $menu_item->ID, '_menu_item_styler', true );
				if( isset(  $styler_data['cid'] ) && json_decode( $styler_data['stdata'], true ) ) {
					$data['GeneratedStyles'] [ 'menu' . $menu_item->ID] [ $styler_data['cid'] ] = json_decode( $styler_data['stdata'], true );
				}
			}
		}

		return $data;
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public function get_control_uid( $name, $item_id ) {
		return str_replace( array( '_', ' ' ), '-', $item_id . $name );
	}

	/**
	 * Add custom fields to $item nav object in order to be used in custom Walker.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	function add_menu_styler_field( $menu_item ) {
		$menu_item->styler = get_post_meta( $menu_item->ID, '_menu_item_styler', true );
		return $menu_item;
	}

	/**
	 * Save menu custom fields
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	function update_menu_styler_field( $menu_id, $menu_item_db_id, $args ) {
		// Check if element is properly sent
		if ( isset( $_REQUEST['menu-item-styler'] ) && is_array( $_REQUEST['menu-item-styler'] ) ) {
			$subtitle_value = $_REQUEST['menu-item-styler'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_styler', $subtitle_value );
		}
	}

	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	 */
	function edit_walker( $item, $depth, $args, $current_object_id ) {
		$item_id  = esc_attr( $item->ID );
		$title    = $item->title;
		$title    = empty( $item->label ) ? $title : $item->label;
		$stdata   = $item->styler ? $item->styler['stdata'] : '';
		$cid      = $item->styler ? $item->styler['cid'] : '';
		$wrapper = '.kata-menu-wrap .kata-menu-navigation .menu-item[id*="menu-item-' . $item_id . '-"]';
		$selector = ' > a';
		?>
			<p class="kata-menu-styler-field description description-wide">
				<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'Styler', 'kata-plus' ); ?><br />
					<div class="menu-styler-control-field">
						<div class="menu-styler-control-input-wrapper">
							<div class="tmp-styler-dialog-btn <?php echo esc_attr( $this->get_control_uid( 'styler-btn', $item_id ) ); ?>"
								data-title="<?php echo esc_attr( $title ); ?>"
								data-field-id="<?php echo esc_attr( $item_id ); ?>"
								data-id="menu<?php echo esc_attr( $item_id ); ?>"
								data-parent-id=""
								data-selector="<?php echo isset( $selector ) ? esc_attr( $selector ) : ''; ?>"
								data-wrapper="<?php echo isset( $wrapper ) ? esc_attr( $wrapper ) : ''; ?>"
								data-type=""
								data-is-svg=""
								data-is-input=""
								data-hover-selector=""
							>
							<input type="hidden" name="menu-item-styler[<?php echo esc_attr( $item->ID ) ?>][stdata]" id="<?php echo esc_attr( $this->get_control_uid( 'stdata', $item_id ) ); ?>"
									value='<?php echo wp_unslash($stdata); ?>' data-setting="stdata"/>
								<input type="hidden" name="menu-item-styler[<?php echo esc_attr( $item->ID ) ?>][cid]" id="<?php echo esc_attr( $this->get_control_uid( 'cid', $item_id ) ); ?>"
									value="<?php echo $cid; ?>" data-setting="cid"/>
								<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
							</div>
						</div>
					</div>
				</label>
			</p>
		<?php
	}
}

Styler_Walker_Nav_Menu_Edit::get_instance();
