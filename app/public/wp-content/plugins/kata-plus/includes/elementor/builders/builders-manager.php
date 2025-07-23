<?php

/**
 * Widget Base Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.2.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Post_CSS_File;
use Elementor\Core\Files\CSS\Post;


if ( ! class_exists( 'Kata_Plus_Builders_Manager' ) ) {

	class Kata_Plus_Builders_Manager extends Kata_Plus_Builders_Base {

		/**
		 * Instance of this class.
		 *
		 * @since   1.2.0
		 * @access  public
		 * @var     Kata
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.2.0
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
		 * @since    1.2.0
		 */
		public function __construct() {
			$this->actions();
		}

		/**
		 * Actions.
		 *
		 * @since     1.2.0
		 */
		public function actions() {

			add_filter( 'manage_edit-kata_plus_builder_columns', array( $this, 'builder_type_column' ), 99, 1 );
			add_action( 'manage_kata_plus_builder_posts_custom_column', array( $this, 'manage_builder_type_column' ), 99, 1 );
			add_action( 'admin_init', array( $this, 'redirect' ), 99, 3 );

			if ( $this->is_builders_page() ) {
				add_action( 'restrict_manage_posts', array( $this, 'filter_kata_plus_builder_select_options' ) );
				add_filter( 'parse_query', array( $this, 'builders_filters' ), 99, 1 );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_script' ) );
				add_action( 'save_post_kata_plus_builder', array( $this, 'add_new_builder' ), 99, 3 );
				add_filter( 'views_edit-kata_plus_builder', array( $this, 'manage_views' ), 10, 1 );
				add_action( 'admin_notices', array( $this, 'update_builders_notice' ) );
				add_filter( 'page_row_actions', array( $this, 'filter_post_row_actions' ), 999, 2 );
			}

			add_action( 'wp_ajax_set_primary_builder', array( $this, 'set_primary_builder' ) );
			add_action( 'wp_ajax_update_builders', array( $this, 'update_builders' ) );

		}

		/**
		 * is builder's page.
		 *
		* @since     1.2.0
		 */
		public function filter_post_row_actions( $actions, $post ) {

			$builder         = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : 'kata_header';
			$title           = _draft_or_post_title();

			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				get_edit_post_link( $post->ID ) . '&builder=' . $builder,
				/* translators: %s: Post title. */
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ),
				__( 'Edit' )
			);

			return $actions;
		}

		/**
		 * is builder's page.
		 *
		 * @since     1.2.0
		 */
		public function is_builders_page() {

			global $pagenow;

			$is_builder_page  = isset( $_GET['builder'] ) && ( $pagenow === 'edit.php' || $pagenow === 'post-new.php' || $pagenow === 'post.php' );
			if ( $is_builder_page ) return true;


			// $is_builders_ajax = ( isset( $_POST['action'] ) && $_POST['action'] === 'set_primary_builder' ) && isset( $_POST['builder_type'] ) && isset( $_POST['builder_id'] );
			// if ( $is_builders_ajax ) {

			// 	$builder_id = (int) sanitize_text_field( $_POST['builder_id'] );

			// 	if ( $builder_id ) {

			// 		global $wpdb;
			// 		$builder = $wpdb->get_row(
			// 			$wpdb->prepare(
			// 				"SELECT * FROM $wpdb->posts WHERE ID = %d AND post_type = 'kata_plus_builder'", $builder_id
			// 			)
			// 		);

			// 		return isset( $builder->post_type ) && $builder->post_type === 'kata_plus_builder';
			// 	}
			// }

			return false;
		}

		/**
		 * Builder Type Column.
		 *
		 * @since   1.2.0
		 */
		public function builder_type_column( $columns ) {

			$columns['type']    = esc_html__( 'Type', 'kata-plus' );
			$columns['primary'] = esc_html__( 'Primary Builder', 'kata-plus' );

			return $columns;
		}

		/**
		 * Manage Builder type column.
		 *
		 * @since   1.2.0
		 */
		public function manage_builder_type_column( $columns ) {

			global $post;

			$type = get_post_meta( $post->ID, '_kata_builder_type', true );

			if ( $columns == 'type' ) {
				if ( 'kata_404' === $type ) {
					echo esc_html__( '404', 'kata-plus' );
				}
				if ( 'kata_archive' === $type ) {
					echo esc_html__( 'Archive', 'kata-plus' );
				}
				if ( 'kata_author' === $type ) {
					echo esc_html__( 'Author', 'kata-plus' );
				}
				if ( 'kata_single_course' === $type ) {
					echo esc_html__( 'Single Course', 'kata-plus' );
				}
				if ( 'kata_archive_portfolio' === $type ) {
					echo esc_html__( 'Archive Portfolio', 'kata-plus' );
				}
				if ( 'kata_search' === $type ) {
					echo esc_html__( 'Search', 'kata-plus' );
				}
				if ( 'kata_single_post' === $type ) {
					echo esc_html__( 'Single Post', 'kata-plus' );
				}
				if ( 'kata_single_portfolio' === $type ) {
					echo esc_html__( 'Single Portfolio', 'kata-plus' );
				}
				if ( 'kata_sticky_header' === $type ) {
					echo esc_html__( 'Sticky Header', 'kata-plus' );
				}
				if ( 'kata_blog' === $type ) {
					echo esc_html__( 'Blog', 'kata-plus' );
				}
				if ( 'kata_footer' === $type ) {
					echo esc_html__( 'Footer', 'kata-plus' );
				}
				if ( 'kata_header' === $type ) {
					echo esc_html__( 'Header', 'kata-plus' );
				}
			}

			if ( $columns == 'primary' ) {

				$primery = get_post_meta( $post->ID, '_' . $type . '_primary', true );
				$checked = $primery == 'true' ? 'checked' : '';

				?>
					<input
					id="<?php echo esc_attr( $type . '-' . $post->ID ); ?>"
					class="primary-kata-builder"
					type="radio"
					name="<?php echo esc_attr( $type ); ?>"
					value="<?php echo esc_attr( $post->ID ); ?>"
					<?php echo esc_attr( $checked ); ?>
					>
				<?php
			}
		}

		/**
		 * Builders Filter.
		 *
		 * @since   1.2.0
		 */
		public function builders_filters( $query ) {

			if ( isset( $query->query['ignore_filter'] ) ) {
				if ( $query->query['ignore_filter'] === true ) {
					return $query;
				}
			}

			global $pagenow;
			$current_page = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
			$builder      = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : 'kata_header';

			if ( $builder !== 'all' ) {
				if ( is_admin() && 'kata_plus_builder' == $current_page && 'edit.php' == $pagenow && isset( $_GET['builder'] ) && $_GET['builder'] != '' && $query->query_vars['post_type'] == 'kata_plus_builder' ) {

					$query->set(
						'meta_query',
						array(
							array(
								'key'     => '_kata_builder_type',
								'value'   => $builder,
								'compare' => '=',
							),
						)
					);
				}
			}

		}

		/**
		 * Builders Filter select html.
		 *
		 * @since   1.2.0
		 */
		public function filter_kata_plus_builder_select_options() {

			global $pagenow;
			$type      = 'kata_plus_builder';
			$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
			$builder   = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : '';

			if ( $post_type == $type && is_admin() && $pagenow == 'edit.php' ) {

				?>
				<label for="builder" class="screen-reader-text"><?php _e( 'Filter by builders', 'kata-plus' ); ?></label>
				<select name="builder" id="builder">

					<?php if ( in_array( 'kata-plus-pro/kata-plus-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
						<option value="kata_header" <?php isset( $builder ) ? selected( $builder, 'kata_header' ) : ''; ?>>
							<?php esc_html_e( 'Header', 'kata-plus' ); ?>
						</option>

						<option value="kata_blog" <?php isset( $builder ) ? selected( $builder, 'kata_blog' ) : ''; ?>>
							<?php esc_html_e( 'Blog', 'kata-plus' ); ?>
						</option>

						<option value="kata_footer" <?php isset( $builder ) ? selected( $builder, 'kata_footer' ) : ''; ?>>
							<?php esc_html_e( 'Footer', 'kata-plus' ); ?>
						</option>

						<option value="kata_404" <?php isset( $builder ) ? selected( $builder, 'kata_404' ) : ''; ?>>
							<?php esc_html_e( '404', 'kata-plus' ); ?>
						</option>

						<option value="kata_archive" <?php isset( $builder ) ? selected( $builder, 'kata_archive' ) : ''; ?>>
							<?php esc_html_e( 'Archive', 'kata-plus' ); ?>
						</option>

						<option value="kata_author" <?php isset( $builder ) ? selected( $builder, 'kata_author' ) : ''; ?>>
							<?php esc_html_e( 'Author', 'kata-plus' ); ?>
						</option>

						<option value="kata_single_course" <?php isset( $builder ) ? selected( $builder, 'kata_single_course' ) : ''; ?>>
							<?php esc_html_e( 'Single Course', 'kata-plus' ); ?>
						</option>

						<option value="kata_archive_portfolio" <?php isset( $builder ) ? selected( $builder, 'kata_archive_portfolio' ) : ''; ?>>
							<?php esc_html_e( 'Archive Portfolio', 'kata-plus' ); ?>
						</option>

						<option value="kata_search" <?php isset( $builder ) ? selected( $builder, 'kata_search' ) : ''; ?>>
							<?php esc_html_e( 'Search', 'kata-plus' ); ?>
						</option>

						<option value="kata_single_post" <?php isset( $builder ) ? selected( $builder, 'kata_single_post' ) : ''; ?>>
							<?php esc_html_e( 'Single Post', 'kata-plus' ); ?>
						</option>

						<option value="kata_single_portfolio" <?php isset( $builder ) ? selected( $builder, 'kata_single_portfolio' ) : ''; ?>>
							<?php esc_html_e( 'Single Portfolio', 'kata-plus' ); ?>
						</option>

						<option value="kata_sticky_header" <?php isset( $builder ) ? selected( $builder, 'kata_sticky_header' ) : ''; ?>>
							<?php esc_html_e( 'Sticky Header', 'kata-plus' ); ?>
						</option>
					<?php } else { ?>
						<option value="kata_header" <?php isset( $builder ) ? selected( $builder, 'kata_header' ) : ''; ?>>
							<?php esc_html_e( 'Header', 'kata-plus' ); ?>
						</option>

						<option value="kata_blog" <?php isset( $builder ) ? selected( $builder, 'kata_blog' ) : ''; ?>>
							<?php esc_html_e( 'Blog', 'kata-plus' ); ?>
						</option>

						<option value="kata_footer" <?php isset( $builder ) ? selected( $builder, 'kata_footer' ) : ''; ?>>
							<?php esc_html_e( 'Footer', 'kata-plus' ); ?>
						</option>
					<?php } ?>

				</select>
				<?php
			}
		}

		/**
		 * Regist Builder scripts.
		 *
		 * @since   1.2.0
		 */
		public function admin_script() {



			$builder      = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : 'kata_header';

			wp_enqueue_script( 'kata-builders', Kata_Plus::$assets . 'js/backend/builders.js', array( 'jquery' ), Kata_Plus::$version, true );

			wp_localize_script(
				'kata-builders',
				'kata_builders_localize',
				array(
					'ajax'           => array(
						'url'   => admin_url( 'admin-ajax.php' ),
						'nonce' => wp_create_nonce( 'kata_builder_nonce' ),
					),
					'builder_branch' => $builder,
				)
			);

		}

		/**
		 * Add new builder.
		 *
		 * @since   1.2.0
		 * @param Return $post_ID int
		 * @param Return $post object
		 * @param Return $update boolean
		 */
		public function add_new_builder( $post_ID, $post, $update ) {


			if ( $update ) {
				return;
			}

			$builder = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : 'kata_header';
			$content = '';

			if ( 'kata_404' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_404_Builder' ) ? Kata_Plus_Pro_404_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_archive' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Archive_Builder' ) ? Kata_Plus_Pro_Archive_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_author' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Author_Builder' ) ? Kata_Plus_Pro_Author_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_single_course' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Single_Course_Builder' ) ? Kata_Plus_Pro_Single_Course_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_archive_portfolio' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Portfolio_Archive_Builder' ) ? Kata_Plus_Pro_Portfolio_Archive_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_search' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Search_Builder' ) ? Kata_Plus_Pro_Search_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_single_post' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Single_Builder' ) ? Kata_Plus_Pro_Single_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_single_portfolio' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Single_Portfolio_Builder' ) ? Kata_Plus_Pro_Single_Portfolio_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_sticky_header' === $builder ) {
				$content = class_exists( 'Kata_Plus_Pro_Sticky_Header_Builder' ) ? Kata_Plus_Pro_Sticky_Header_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_blog' === $builder ) {
				$content = class_exists( 'Kata_Plus_Blog_Builder' ) ? Kata_Plus_Blog_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_footer' === $builder ) {
				$content = class_exists( 'Kata_Plus_Footer_Builder' ) ? Kata_Plus_Footer_Builder::get_instance()->default_content : '';
			}
			if ( 'kata_header' === $builder ) {
				$content = class_exists( 'Kata_Plus_Header_Builder' ) ? Kata_Plus_Header_Builder::get_instance()->default_content : '';
			}


			update_post_meta( $post_ID, '_elementor_edit_mode', 'builder' );
			update_post_meta( $post_ID, '_elementor_template_type', 'post' );
			update_post_meta( $post_ID, '_wp_page_template', 'default' );
			update_post_meta( $post_ID, '_edit_lock', time() . ':1' );
			update_post_meta( $post_ID, '_elementor_version', '0.4' );
			update_post_meta( $post_ID, '_kata_builder_type', $builder );
			update_post_meta( $post_ID, '_elementor_data', $content );
		}

		/**
		 * Redirect builder.
		 *
		 * @since   1.2.0
		 */
		public function redirect() {

			if ( ! is_admin() ) {
				return;
			}

			global $pagenow;
			$the_page = ( 'edit.php' == $pagenow || 'post-new.php' == $pagenow || 'post.php' == $pagenow );

			if ( ! $the_page ) {
				return;
			}

			$is_builder = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';

			if ( 'kata_plus_builder' !== $is_builder ) {
				return;
			}

			$builder      = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : false;
			$http_query   = http_build_query( $_GET );
			$redirect_url = false;

			if ( ! $builder ) {
				$redirect_url = admin_url( $pagenow . '?' . $http_query . '&builder=kata_header' );
			}

			if ( $redirect_url ) {
				exit( esc_url( wp_redirect( $redirect_url, 301 ) ) );
			}
		}

		/**
		 * Add new builder.
		 *
		 * @since   1.2.0
		 * @param Return $views array.
		 */
		public function manage_views( $views ) {

			global $pagenow;

			unset( $views['mine'] );
			unset( $views['all'] );

			$builder       = isset( $_GET['builder'] ) ? sanitize_text_field( $_GET['builder'] ) : 'kata_header';

			$args = array(
				'post_type'   => 'kata_plus_builder',
				'post_status' => 'trash',
				'meta_query'  => array(
					array(
						'key'     => '_kata_builder_type',
						'value'   => $builder,
						'compare' => '=',
					),
				),
				'posts_per_page' => -1
			);
			// Create a new instance of WP_Query
			$trash_query = new WP_Query($args);
			$trash_url   = 'edit.php?post_status=trash&post_type=kata_plus_builder&builder=' . $builder;

			if ( $trash_query->found_posts && isset( $views['trash'] ) ) {
				$views['trash'] = '<a href="'. admin_url( $trash_url  ) .'">Trash <span class="count">(' . $trash_query->found_posts . ')</span></a>';
			} else {
				unset( $views['trash'] );
			}

			$args = array(
				'post_type'   => 'kata_plus_builder',
				'post_status' => 'draft',
				'meta_query'  => array(
					array(
						'key'     => '_kata_builder_type',
						'value'   => $builder,
						'compare' => '=',
					),
				),
				'posts_per_page' => -1
			);

			wp_reset_query();
			wp_reset_postdata();

			// Create a new instance of WP_Query
			$draft_query = new WP_Query($args);
			$draft_url   = 'edit.php?post_status=draft&post_type=kata_plus_builder&builder=' . $builder;

			if ( $draft_query->found_posts && isset( $views['draft'] ) ) {
				$views['draft'] = '<a href="'. admin_url( $draft_url  ) .'">Draft <span class="count">(' . $draft_query->found_posts . ')</span></a>';
			} else {
				unset( $views['draft'] );
			}

			$args = array(
				'post_type'   => 'kata_plus_builder',
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'     => '_kata_builder_type',
						'value'   => $builder,
						'compare' => '=',
					),
				),
				'posts_per_page' => -1
			);

			wp_reset_query();
			wp_reset_postdata();

			// Create a new instance of WP_Query
			$publish_query = new WP_Query($args);
			$publish_url   = 'edit.php?post_status=publish&post_type=kata_plus_builder&builder=' . $builder;
			$views['publish'] = '<a href="'. admin_url( $publish_url  ) .'">Published <span class="count">(' . $publish_query->found_posts . ')</span></a>';

			wp_reset_query();
			wp_reset_postdata();

			return $views;
		}

		/**
		 * Ajax Set Primary Builder.
		 *
		 * @since   1.2.0
		 * @param Return object.
		 */
		public function set_primary_builder() {

			check_ajax_referer( 'kata_builder_nonce', 'nonce' );

			$builder_id   = isset( $_POST['builder_id'] ) ? sanitize_text_field( $_POST['builder_id'] ) : '';
			$builder_type = isset( $_POST['builder_type'] ) ? sanitize_text_field( $_POST['builder_type'] ) : '';

			if ( $builder_id && $builder_type ) {

				// reset primery builder
				$args = array(
					'post_type'  => 'kata_plus_builder',
					'meta_query' => array(
						array(
							'key'     => '_kata_builder_type',
							'value'   => $builder_type,
							'compare' => '=',
						),
					),
				);

				$builders = get_posts( $args );

				foreach ( $builders as $builder ) {

					update_post_meta( $builder->ID, '_' . $builder_type . '_primary', 'false' );
				}

				// set primery builder
				if ( $builder_id ) {

					$update = update_post_meta( $builder_id, '_' . $builder_type . '_primary', 'true' );

					if ( ! is_wp_error( $update ) ) {

						$primery_builder = get_post( $builder_id, OBJECT );

						wp_send_json( wp_sprintf( '%s, %l', get_the_title( $builder_id ), __( 'selected as the primary builder', 'kata-plus' ) ), 200 );
					} else {

						wp_send_json( __( 'An error occurred while performing the operation', 'kata-plus' ), 400 );
					}
				}
			}

			wp_die();
		}

		/**
		 * Ajax Update Builders.
		 *
		 * @since   1.2.0
		 * @param Return object.
		 */
		public function update_builders() {

			check_ajax_referer( 'kata_builder_nonce', 'nonce' );

			$kata_options    = get_option( 'kata_options' );
			$update_builders = isset( $_POST['update_builders'] ) ? sanitize_text_field( $_POST['update_builders'] ) : false;

			if ( $update_builders && ! isset( $kata_options['updates']['builders']['primary'] ) ) {

				$ids = array(
					'kata_blog'   => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Blog' ),
					'kata_footer' => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Footer' ),
					'kata_header' => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Header' ),
				);

				if ( in_array( 'kata-plus-pro/kata-plus-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

					$ids = array(
						'kata_404'               => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata 404' ),
						'kata_archive'           => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Archive' ),
						'kata_author'            => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Author' ),
						'kata_single_course'     => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Single Course' ),
						'kata_archive_portfolio' => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Portfolio Archive' ),
						'kata_search'            => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Search' ),
						'kata_single_post'       => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Single' ),
						'kata_single_portfolio'  => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Single Portfolio' ),
						'kata_sticky_header'     => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Sticky Header' ),
						'kata_blog'              => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Blog' ),
						'kata_footer'            => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Footer' ),
						'kata_header'            => Kata_Plus_Builders_Base::get_instance()->get_post_by_title( 'Kata Header' ),
					);
				}

				$result = array();

				foreach ( $ids as $key => $id ) {
					$result[ $key ] = update_post_meta( $id, '_' . $key . '_primary', 'true' );
					$result[ $key ] = update_post_meta( $id, '_kata_builder_type', $key );
				}

				$kata_options['updates']['builders']['primary'] = 'updated';

				update_option( 'kata_options', $kata_options );

				return wp_send_json(
					array(
						'message' => __( 'Updated', 'kata-plus' ),
						'results' => $result,
					),
					200
				);

			}

			wp_die();
		}

		/**
		 * Builders update notification.
		 *
		 * @since   1.2.0
		 * @param Return HTML.
		 */
		public function update_builders_notice() {

			$kata_options = get_option( 'kata_options' );

			if ( ! isset( $kata_options['updates']['builders']['primary'] ) && version_compare( Kata_Plus::$version, '1.2.0', '>=' ) ) {
				global $pagenow;
				?>
				<div class="notice notice-success is-dismissible update-kata-builders">
					<h2><?php esc_html_e( 'Update the kata builders', 'kata-plus' ); ?></h2>

					<?php if ( 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'kata_plus_builder' ) { ?>
						<p style="max-width: 70%;"><?php esc_html_e( 'Please click on the update button.', 'kata-plus' ); ?></p>
					<?php } else { ?>
						<p style="max-width: 70%;"><?php esc_html_e( 'From version 1.2.0 onwards, a new feature has been added to the kata theme. Some values need to be updated in the "Kata Builders", this update is required and if you do not do it the header, sticker header, footer, blog and other "Kata Builders" will face problems. and will not display on your site front, to update them.', 'kata-plus' ); ?></p>
					<?php } ?>

					<?php if ( 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'kata_plus_builder' ) { ?>
						<p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=kata_plus_builder&builder=kata_header' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Update', 'kata-plus' ); ?></a></p>
					<?php } else { ?>
						<p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=kata_plus_builder&builder=kata_header' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to update page', 'kata-plus' ); ?></a></p>
					<?php } ?>
				</div>
				<?php

			}
		}
	} // class

	Kata_Plus_Builders_Manager::get_instance();
}
