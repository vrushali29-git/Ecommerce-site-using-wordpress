<?php
/**
 * Meta Box Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Meta_Box' ) ) {
	class Kata_Plus_Meta_Box {
		/**
		 * The directory of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $dir;

		/**
		 * The url of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $url;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Meta_Box
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
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->definitions();
			$this->dependencies();
			$this->actions();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/meta-box/';
			self::$url = Kata_Plus::$url . 'includes/meta-box/';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			if ( is_plugin_active( 'meta-box/meta-box.php' ) ) {
				return;
			}
			add_filter( 'rwmb_normalize_field', array( $this, 'add_kata_class' ) );
			add_action( 'rwmb_before', array( $this, 'add_kata_tabs' ) );
			add_action( 'rwmb_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'rwmb_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action(
				'admin_menu',
				function () {
					remove_submenu_page( 'plugins.php', 'meta-box' );
					if ( ! class_exists( 'RWMB_Loader' ) && is_plugin_active( 'meta-box-aio/meta-box-aio.php' ) ) {
						remove_submenu_page( 'plugins.php', 'mb-aio-install-plugins' );
						add_action( 'admin_notices', array( $this, 'metaboxaio_notice' ) );
					}
					remove_menu_page( 'meta-box' );
				},
				99999
			);

			/**
			* Remove Metabox.
			*/
			add_action(
				'add_meta_boxes',
				function ( $post_type, $id ) {
					if ( class_exists( '\WP_Social\Helper\Share_Style_Settings' ) ) {
						remove_meta_box( 'wp-social-plugin', $post_type, 'normal', 'high' );
					}
				},
				99,
				2
			);
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Meta Box Plugin
			if ( ! class_exists( 'RWMB_Loader' ) && ! is_plugin_active( 'meta-box-aio/meta-box-aio.php' ) ) {
				Kata_Plus_Autoloader::load( self::$dir . 'meta-box-plugin/', 'meta-box' );
			}
			if ( ! function_exists( 'mb_conditional_logic_load' ) ) {
				Kata_Plus_Autoloader::load( self::$dir . 'addons/meta-box-conditional-logic/', 'meta-box-conditional-logic' );
			}
			Kata_Plus_Autoloader::load(
				self::$dir . 'options',
				array(
					'post',
					'testimonial',
					'team-member',
					'mega-menu',
				),
				'',
				'options'
			);
		}


		/**
		 * MetaboxAIO
		 *
		 * @since   1.0.0
		 */
		public function metaboxaio_notice() {
			$url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'install-plugin',
						'plugin' => 'meta-box',
					),
					admin_url( 'update.php' )
				),
				'install-plugin_meta-box'
			);
			?>
			<div class="notice notice-error">
				<p><?php echo __( 'Kata and Meta Box AIO plugins need the Meta Box plugin to work. Please install the Meta Box plugin first.', 'kata-plus' ); ?> <a href="<?php echo esc_url( $url ); ?>"><?php echo __( 'Install Meta Box', 'kata-plus' ); ?></a></p>
			</div>
			<?php
		}

		/**
		 * Add 'kata-class' property to field
		 *
		 * @since   1.0.0
		 */
		public function add_kata_class( $field ) {
			if ( empty( $field['kata-class'] ) ) {
				return $field;
			}

			$field['class']  = $field['class'] ? $field['class'] : '';
			$field['class'] .= ' ' . $field['kata-class'];
			$field['class']  = trim( $field['class'] );

			return $field;
		}

		/**
		 * Add 'kata-tabs' to Meta Box plugin - part 1
		 *
		 * @since   1.0.0
		 */
		public function add_kata_tabs( RW_Meta_Box $obj ) {
			if ( empty( $obj->meta_box['kata-tabs'] ) ) {
				return;
			}

			$li   = '';
			$tabs = $obj->meta_box['kata-tabs'];

			foreach ( $tabs as $tab_key => $tab_detail ) {
				if ( is_array( $tab_detail ) ) {
					$li .= '
					<li class="kata-nav-item" data-target="kata-tab-content-' . $tab_key . '">
						<i class="' . esc_attr( $tab_detail['icon'] ) . '"></i>
						<a href="#">' . esc_html( $tab_detail['title'] ) . '</a>
					</li>';
				}
			}

			// Output
			echo '
			<div class="kata-tabs-wrap kata-clearfix">
				<ul class="kata-nav-tabs">
					' . $li . '
				</ul>';

			add_filter( 'rwmb_outer_html', array( $this, 'create_kata_tabs' ), 10, 2 );
			add_action( 'rwmb_after', array( $this, 'rwmb_after' ) );
		}

		/**
		 * Create kata tabs.
		 *
		 * @since   1.0.0
		 */
		public function create_kata_tabs( $output, $field ) {
			if ( isset( $field['dependecy'] ) ) {
				$dom = new DOMDocument();
				$dom->loadHTML( $output );
				$node = $dom->getElementsByTagName( 'div' )->item( 0 );
				$node->setAttribute( 'data-dependency', key( $field['dependecy'] ) );
				$node->setAttribute( 'data-dependency-value', $field['dependecy'][ key( $field['dependecy'] ) ] );
				$output = $dom->saveHTML( $node );
			}

			if ( ! isset( $field['kata-tab'] ) ) {
				return $output;
			}

			$tab = $field['kata-tab'];
			if ( ! isset( $this->tab_fields_output[ $tab ] ) ) {
				$this->tab_fields_output[ $tab ] = array();
			}
			$this->tab_fields_output[ $tab ][] = $output;

			return '';
		}

		/**
		 * Add 'kata-tabs' to Meta Box plugin - part 2
		 *
		 * @since   1.0.0
		 */
		public function rwmb_after() {
			$tabs = '';

			foreach ( $this->tab_fields_output as $tab => $fields ) :
				$tabs .= '<div class="kata-tab-content kata-tab-content-' . $tab . '">' . implode( '', $fields ) . '</div>';
			endforeach;

			// Output
			echo '
				<div class="kata-tab-contents">
					' . $tabs . '
				</div>
			</div>'; // end kata-tabs-wrap
		}

		/**
		 * Enqueue Styles.
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'kata-plus-meta-box', Kata_Plus::$assets . 'css/backend/meta-box.css' );
		}

		/**
		 * Enqueue Javascripts.
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'kata-plus-meta-box', Kata_Plus::$assets . 'js/backend/meta-box.js', array( 'jquery' ), Kata_plus::$version, true );
		}
	} // class

	Kata_Plus_Meta_Box::get_instance();
} // if
