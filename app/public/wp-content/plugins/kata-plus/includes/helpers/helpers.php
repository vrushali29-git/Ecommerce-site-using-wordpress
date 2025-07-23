<?php

/**
 * Helpers Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;

if ( ! class_exists( 'Kata_Plus_Helpers' ) ) {

	class Kata_Plus_Helpers {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Helpers
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
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->actions();
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			// add the filter
			add_filter( 'wp_kses_allowed_html', array( $this, 'filter_wp_kses_allowed_html' ), 10, 1 );
			add_filter( 'upload_mimes', array( $this, 'mime_types' ), 10, 1 );
			add_action( 'kata_single_before_loop', array( $this, 'post_view_counter' ) );
			add_action( 'show_user_profile', array( $this, 'author_social_networks' ) );
			add_action( 'edit_user_profile', array( $this, 'author_social_networks' ) );
			add_action( 'personal_options_update', array( $this, 'save_author_social_networks' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_author_social_networks' ) );
			add_action( 'admin_init', array( $this, 'disable_redirection' ), 1 );
		}

		/**
		 * Disable Redirection.
		 *
		 * @since   1.0.0
		 */
		public function disable_redirection() {
			/**
			 * Disable Elementor Redirection
			 */
			if ( did_action( 'elementor/loaded' ) ) {
				remove_action( 'admin_init', array( \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ) );
			}
		}

		/**
		 * Whitelist HTML tags.
		 *
		 * @param string $tag Tag name.
		 * @return string
		 * @since   1.4.8
		 */
		public static function whitelist_html_tags( $tag, $default = 'div' ) {
			$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'figure', 'caption', 'div', 'article' );
			return in_array( $tag, $allowed_tags ) ? $tag : $default;
		}

		/**
		 * Add Author Social Networks.
		 *
		 * @param string $user Required. user object.
		 * @since   1.0.0
		 */
		public function author_social_networks( $user ) {
			?>
			<h2><?php echo __( 'User Social Networks', 'kata-plus' ); ?></h2>
			<table class="form-table" role="presentation">
				<!-- Facebook -->
				<tr>
					<th><label for="kata_author_facebook"><?php echo __( 'Facebook', 'kata-plus' ); ?></label></th>
					<td><input type="url" name="kata_author_facebook" id="kata_author_facebook" value="<?php echo esc_attr( get_the_author_meta( 'kata_author_facebook', $user->ID ) ); ?>" class="regular-text"></td>
				</tr>
				<!-- Twitter -->
				<tr>
					<th><label for="kata_author_twitter"><?php echo __( 'Twitter', 'kata-plus' ); ?></label></th>
					<td><input type="url" name="kata_author_twitter" id="kata_author_twitter" value="<?php echo esc_attr( get_the_author_meta( 'kata_author_twitter', $user->ID ) ); ?>" class="regular-text"></td>
				</tr>
				<!-- Linkedin -->
				<tr>
					<th><label for="kata_author_linkedin"><?php echo __( 'Linkedin', 'kata-plus' ); ?></label></th>
					<td><input type="url" name="kata_author_linkedin" id="kata_author_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'kata_author_linkedin', $user->ID ) ); ?>" class="regular-text"></td>
				</tr>
				<!-- Instagram -->
				<tr>
					<th><label for="kata_author_instagram"><?php echo __( 'Instagram', 'kata-plus' ); ?></label></th>
					<td><input type="url" name="kata_author_instagram" id="kata_author_instagram" value="<?php echo esc_attr( get_the_author_meta( 'kata_author_instagram', $user->ID ) ); ?>" class="regular-text"></td>
				</tr>
			</table>
			<?php
		}

		/**
		 * Save Author Social Networks.
		 *
		 * @param string $user_id Required. user object.
		 * @since   1.0.0
		 */
		public function save_author_social_networks( $user_id ) {
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}
			update_user_meta( $user_id, 'kata_author_facebook', sanitize_text_field( $_POST['kata_author_facebook'] ) );
			update_user_meta( $user_id, 'kata_author_twitter', sanitize_text_field( $_POST['kata_author_twitter'] ) );
			update_user_meta( $user_id, 'kata_author_linkedin', sanitize_text_field( $_POST['kata_author_linkedin'] ) );
			update_user_meta( $user_id, 'kata_author_instagram', sanitize_text_field( $_POST['kata_author_instagram'] ) );
		}

		/**
		 * Get The Author Social Networks.
		 *
		 * @since   1.0.0
		 */
		public static function get_the_author_social_networks() {
			$user_id = ! Plugin::$instance->editor->is_edit_mode() ? get_the_author_meta( 'ID' ) : get_current_user_id();
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}
			// font-awesome/facebook
			$facebook = get_user_meta( $user_id, 'kata_author_facebook', true ) ? get_user_meta( $user_id, 'kata_author_facebook', true ) : '';
			// font-awesome/twitter
			$twitter = get_user_meta( $user_id, 'kata_author_twitter', true ) ? get_user_meta( $user_id, 'kata_author_twitter', true ) : '';
			// font-awesome/linkedin
			$linkedin = get_user_meta( $user_id, 'kata_author_linkedin', true ) ? get_user_meta( $user_id, 'kata_author_linkedin', true ) : '';
			// font-awesome/instagram
			$instagram = get_user_meta( $user_id, 'kata_author_instagram', true ) ? get_user_meta( $user_id, 'kata_author_instagram', true ) : '';
			if ( $facebook || $twitter || $linkedin || $instagram ) {
				echo '<ul class="kt-author-social-network">';
				if ( $facebook ) {
					?>
					<li> <a href="<?php echo esc_url( $facebook ); ?>"><?php echo self::get_icon( '', 'font-awesome/facebook', '', '' ); ?></a> </li>
					<?php
				}
				if ( $twitter ) {
					?>
					<li> <a href="<?php echo esc_url( $twitter ); ?>"><?php echo self::get_icon( '', 'font-awesome/twitter', '', '' ); ?></a> </li>
					<?php
				}
				if ( $linkedin ) {
					?>
					<li> <a href="<?php echo esc_url( $linkedin ); ?>"><?php echo self::get_icon( '', 'font-awesome/linkedin', '', '' ); ?></a> </li>
					<?php
				}
				if ( $instagram ) {
					?>
					<li> <a href="<?php echo esc_url( $instagram ); ?>"><?php echo self::get_icon( '', 'font-awesome/instagram', '', '' ); ?></a> </li>
					<?php
				}
				echo '</ul>';
			}
		}

		/**
		 * Get MetaBox.
		 *
		 * @param string  $key Required. Post meta key.
		 * @param string  $id Optional. Post ID.
		 * @param boolean $single Optional. Add class to heading.
		 * @since   1.0.0
		 */
		public static function get_meta_box( $key, $id = '', $single = true ) {
			if ( function_exists( 'rwmb_meta' ) && '' === $id ) {
				return ! empty( rwmb_meta( $key ) ) || rwmb_meta( $key ) === '0' ? rwmb_meta( $key ) : '';
			} else {
				$id = $id ? $id : get_the_ID();
				return get_post_meta( $id, $key, $single );
			}
		}

		/**
		 * Post Counter Container.
		 *
		 * @since   1.0.0
		 */
		public function post_view_counter() {
			if ( ! get_post_meta( get_the_ID(), 'kata_post_view', true ) ) {
				add_post_meta( get_the_ID(), 'kata_post_view', 1 );
			} else {
				$i = get_post_meta( get_the_ID(), 'kata_post_view', true ) + 1;
				update_post_meta( get_the_ID(), 'kata_post_view', $i );
			}
		}

		/**
		 * Add Svg to Kses
		 *
		 * @since   1.0.0
		 */
		public function filter_wp_kses_allowed_html( $allowed_tags ) {
			$allowed_tags['i']     = array(
				'class' => true,
			);
			$allowed_tags['svg']   = array(
				'version' => true,
				'xmlns'   => true,
				'width'   => true,
				'height'  => true,
				'viewbox' => true,
			);
			$allowed_tags['title'] = array();
			$allowed_tags['path']  = array(
				'data-name' => true,
				'transform' => true,
				'fill' => true,
				'id' => true,
				'd'    => true,
			);
			$allowed_tags['time']  = array(
				'class'    => true,
				'datetime' => true,
			);
			return $allowed_tags;
		}

		/**
		 * Add Svg to Kses
		 *
		 * @since   1.0.0
		 */
		public function mime_types( $mimes = array() ) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}

		/**
		 * Insert attachment
		 *
		 * @since   1.0.0
		 */
		public static function insert_attachment( $file_url ) {
			$file        = $file_url;
			$filename    = basename( $file );
			$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file ) );

			if ( ! $upload_file['error'] ) {
				$wp_filetype   = wp_check_filetype( $filename, null );
				$attachment    = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_parent'    => null,
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], null );
				$attach_url    = wp_get_attachment_url( $attachment_id );

				if ( ! is_wp_error( $attachment_id ) ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );
				}

				return array(
					'id'  => $attachment_id,
					'url' => $attach_url,
				);
			}
		}

		/**
		 * Get current theme info.
		 *
		 * @since   1.0.0
		 */
		public static function get_theme() {
			$get_theme = wp_get_theme();
			if ( $get_theme->parent_theme ) {
				$get_theme = wp_get_theme( basename( get_template_directory() ) );
			}
			return $get_theme;
		}

		/**
		 * Get theme options.
		 *
		 * @since   1.0.0
		 */
		public static function get_theme_option( $opts, $key, $default = '' ) {
			return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
		}

		/**
		 * SSL URL.
		 *
		 * @since   1.0.0
		 */
		public static function ssl_url() {
			return ( is_ssl() ) ? 'https://' : 'http://';
		}

		/**
		 * Avilabel Post types with archive.
		 *
		 * @since   1.0.0
		 */
		public static function get_post_types_with_archive() {
			$Aposttype = array( 'post' => 'post' );
			$PostTypes = get_post_types(
				array(
					'public'      => true,
					'_builtin'    => false,
					'has_archive' => true,
				)
			);
			return array_merge( $Aposttype, $PostTypes );
		}

		/**
		 * Used to overcome core bug when taxonomy is in more then one post type
		 *
		 * @param array  $args
		 * @param string $output
		 * @param string $operator
		 *
		 * @return array
		 */
		public static function get_taxonomies( $args = array(), $output = 'names', $operator = 'and' ) {
			global $wp_taxonomies;

			$field = ( 'names' === $output ) ? 'name' : false;

			// Handle 'object_type' separately.
			if ( isset( $args['object_type'] ) ) {
				$object_type = (array) $args['object_type'];
				unset( $args['object_type'] );
			}

			$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

			if ( isset( $object_type ) ) {
				foreach ( $taxonomies as $tax => $tax_data ) {
					if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
						unset( $taxonomies[ $tax ] );
					}
				}
			}

			if ( $field ) {
				$taxonomies = wp_list_pluck( $taxonomies, $field );
			}

			return $taxonomies;
		}

		/**
		 * Check URL.
		 *
		 * @since   1.0.0
		 */
		public static function check_url( $url ) {
			$headers = @get_headers( $url );
			$headers = ( is_array( $headers ) ) ? implode( "\n ", $headers ) : $headers;
			return (bool) preg_match( '#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers );
		}

		/**
		 * Get Image srcset.
		 *
		 * @since   1.0.0
		 */
		public static function get_attachment_image_html( $settings, $image_size_key = 'image', $retina = '', $class = '' ) {
			$dim = '';
			$image = Group_Control_Image_Size::get_attachment_image_html( $settings, $image_size_key );
			$lazyload = get_theme_mod( 'kata_plus_pro_lazyload', false );
			$image2x = isset( $settings['retina_image']['url'] ) ? $settings['retina_image']['url'] : $retina;

			if ( ! empty( $image2x ) ) $image2x = $image2x . ' 2x';

			if ( 'custom' === $settings[ $image_size_key . '_size' ] && $lazyload === true && ! isset( $_GET['action'] ) ) {
				if ( isset( $settings[ $image_size_key . '_custom_dimension' ]['width'] ) && ! empty( $settings[ $image_size_key . '_custom_dimension' ]['width'] ) ) {
					$dim .= 'width="' . $settings[ $image_size_key . '_custom_dimension' ]['width'] . '"';
				}
				if ( isset( $settings[ $image_size_key . '_custom_dimension' ]['height'] ) && ! empty( $settings[ $image_size_key . '_custom_dimension' ]['height'] ) ) {
					$dim .= ' height="' . $settings[ $image_size_key . '_custom_dimension' ]['height'] . '"';
				}
				if ( $dim ) {
					$image = str_replace( 'src="', $dim . ' src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="', $image );
					$image = str_replace( 'srcset="', ' srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-srcset="', $image );
				}
			} else if ( 'custom' !== $settings[ $image_size_key . '_size' ] && $lazyload === true && ! isset( $_GET['action'] ) ) {
				$image = str_replace( 'src="', ' src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="', $image );
				$image = str_replace( 'srcset="', ' srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-srcset="' . $image2x . ', ', $image );
			} else if ( self::string_is_contain($image, 'svg') ) {
				if ( isset( $settings[ $image_size_key . '_custom_dimension' ]['width'] ) && ! empty( $settings[ $image_size_key . '_custom_dimension' ]['width'] ) ) {
					$dim .= 'width="' . $settings[ $image_size_key . '_custom_dimension' ]['width'] . '"';
				}
				if ( isset( $settings[ $image_size_key . '_custom_dimension' ]['height'] ) && ! empty( $settings[ $image_size_key . '_custom_dimension' ]['height'] ) ) {
					$dim .= ' height="' . $settings[ $image_size_key . '_custom_dimension' ]['height'] . '"';
				}
				if ( $dim ) {
					$image = str_replace( 'src="', $dim . ' src="', $image );
					$image = str_replace( 'srcset="', $dim . ' srcset="', $image );
				}
			}

			if ( strpos( $image, 'data-srcset="') && ! empty( $retina ) ) {
				$image = str_replace( 'data-srcset="', 'data-srcset="' . $image2x . ', ', $image);
			}

			if ( strpos( $image, 'srcset="') && ! strpos( $image, 'data-srcset="') && ! empty( $retina ) ) {
				$image = str_replace( 'srcset="', 'srcset="' . $image2x . ', ', $image);
			}

			if ( ! strpos( $image, 'srcset="') && ! strpos( $image, 'data-srcset="') && ! empty( $retina ) ) {
				$image = str_replace( 'src="', 'srcset="' . $image2x . '" src="', $image);
			}

			if ( ! empty( $class ) ) {
				$image = str_replace( 'src="', 'class="' . $class . '" src="', $image );
				$image = str_replace( 'srcset="', 'class="' . $class . '" srcset="', $image );
			}

			return $image;
		}

		/**
		 * Get Image srcset.
		 *
		 * @since   1.0.0
		 */
		public static function get_image_srcset( $attachment_id = '', $size = 'full', $null = '', $image_meta = null ) {
			if ( ! empty( $attachment_id ) && is_numeric( $attachment_id ) ) {
				$image_meta     = ! empty( trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) ) ? '' : $image_meta;
				$attachmet_meta = wp_get_attachment_metadata( $attachment_id );
				$dim            = '';
				if ( 'full' !== $size && isset( $size[0] ) && ! empty( $size[0] ) ) {
					$dim .= 'width="' . $size[0] . '"';
				} elseif ( 'full' !== $size && isset( $metadata['width'] ) && ! empty( $metadata['width'] ) ) {
					$dim .= 'width="' . $metadata['width'] . '"';
				}
				if ( 'full' !== $size && isset( $size[1] ) && ! empty( $size[1] ) ) {
					$dim .= ' height="' . $size[1] . '"';
				} elseif ( 'full' !== $size && isset( $metadata['height'] ) && ! empty( $metadata['height'] ) ) {
					$dim .= ' height="' . $metadata['height'] . '"';
				}

				$lazyload = get_theme_mod( 'kata_plus_pro_lazyload', false );
				$image    = wp_get_attachment_image( $attachment_id, $size, '', $image_meta );
				$image2x  = wp_get_attachment_url( $attachment_id ) . ' 2x ';

				if ( $lazyload === true && ! isset( $_GET['action'] ) ) {
					$image = str_replace( 'src="', $dim . 'src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="', $image );
					$image = str_replace( 'srcset="', 'srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-srcset="', $image );
					return $image;
				} else {
					return $image;
				}
			}
		}

		/**
		 * Get Attachment SVG path.
		 *
		 * @since   1.0.0
		 */
		public static function get_attachment_svg_path( $id, $url, $attr = '', $class = '', $icon_attr = '' ) {
			if ( ! file_exists( get_attached_file( $id ) ) ) {
				if ( ! empty( $url ) ) {
					?>
					<i class="kata-svg-icon">
						<img src="<?php echo esc_url( $url ); ?>" />
					</i>
					<?php
				}
			} else {
				if ( self::string_is_contain( $url, 'svg' ) ) {
						if ( $attr == 'medium' ) {
							$attr = 'width="300" height="300"';
						}
						switch ( $attr ) {
							case 'thumbnail':
								$attr = 'width="150" height="150"';
								break;
							case 'medium':
								$attr = 'width="300" height="300"';
								break;
							case 'medium_large':
								$attr = 'width="760"';
								break;
							case 'large':
								$attr = 'width="1024" height="1024"';
								break;
							case '1536x1536':
								$attr = 'width="1536" height="1536"';
								break;
							case '2048x2048':
								$attr = 'width="2048" height="2048"';
								break;
							case 'full':
								$attr = '';
								break;
						}

						$default = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M16 0.672c-8.448 0-15.328 6.88-15.328 15.328s6.88 15.328 15.328 15.328 15.328-6.88 15.328-15.328-6.88-15.328-15.328-15.328zM16 30.016c-7.712 0-13.984-6.304-13.984-14.016s6.272-14.016 13.984-14.016 14.016 6.304 14.016 14.016-6.304 14.016-14.016 14.016zM16 12.672c-1.088 0-1.984 0.896-1.984 1.984v8c0 1.12 0.896 2.016 1.984 2.016s2.016-0.896 2.016-2.016v-8c0-1.088-0.896-1.984-2.016-1.984zM16.672 22.656c0 0.384-0.288 0.672-0.672 0.672s-0.672-0.288-0.672-0.672v-8c0-0.352 0.32-0.672 0.672-0.672s0.672 0.32 0.672 0.672v8zM16 7.328c-1.088 0-1.984 0.896-1.984 2.016s0.896 1.984 1.984 1.984 2.016-0.896 2.016-1.984-0.896-2.016-2.016-2.016zM16 9.984c-0.352 0-0.672-0.288-0.672-0.64s0.32-0.672 0.672-0.672 0.672 0.288 0.672 0.672-0.288 0.64-0.672 0.64z"></path></svg>';
						$svg = file_exists( get_attached_file( $id ) ) ? file_get_contents( get_attached_file( $id ) ) : $default;

						if ( $attr != '' ) {
							$svg = str_replace( '<svg ', '<svg ' . $attr, $svg );
						}
						?>
						<i class="kata-svg-icon<?php echo ' ' . esc_attr( $class ); ?>"<?php echo ' ' . $icon_attr; ?>>
							<?php echo $svg; ?>
						</i>
						<?php
					}
			}

		}

		/**
		 * Is Blog Pages
		 *
		 * @since   1.0.0
		 */
		public static function is_blog_pages() {
			return ( ( ( ( is_search() ) || is_archive() ) || ( is_author() ) || ( is_category() ) || ( is_home() ) || ( is_tag() ) ) ) ? true : false;
		}

		/**
		 * Is Blog Page
		 *
		 * @since   1.0.0
		 */
		public static function is_blog() {
			return ( is_home() ) ? true : false;
		}

		/**
		 * SVG size
		 *
		 * @since   1.0.0
		 */
		public static function svg_resize( $size_type, $width = '', $height = '' ) {
			$thumbnail_w       = get_option( 'thumbnail_size_w' ) != '0' ? 'width="' . get_option( 'thumbnail_size_w' ) . '"' : '';
			$thumbnail_h       = get_option( 'thumbnail_size_h' ) != '0' ? ' height="' . get_option( 'thumbnail_size_h' ) . '"' : '';
			$medium_w          = get_option( 'medium_size_w' ) != '0' ? 'width="' . get_option( 'medium_size_w' ) . '"' : '';
			$medium_h          = get_option( 'medium_size_h' ) != '0' ? ' height="' . get_option( 'medium_size_h' ) . '"' : '';
			$large_w           = get_option( 'large_size_w' ) != '0' ? 'width="' . get_option( 'large_size_w' ) . '"' : '';
			$large_h           = get_option( 'large_size_h' ) != '0' ? ' height="' . get_option( 'large_size_h' ) . '"' : '';
			$medium_large_w    = get_option( 'medium_large_size_w' ) != '0' ? 'width="' . get_option( 'medium_large_size_w' ) . '"' : '';
			$medium_large_h    = get_option( 'medium_large_size_h' ) != '0' ? ' height="' . get_option( 'medium_large_size_h' ) . '"' : '';
			$thumbnail_size    = $thumbnail_w . $thumbnail_h;
			$medium_size       = $medium_w . $medium_h;
			$medium_large_size = $medium_large_w . $medium_large_h;
			$large_size        = $large_w . $large_h;

			if ( $size_type != 'custom' ) {
				switch ( $size_type ) {
					case 'thumbnail':
						$svg_size = $thumbnail_size;
						break;
					case 'medium':
						$svg_size = $medium_size;
						break;
					case 'medium_large':
						$svg_size = $medium_large_size;
						break;
					case 'large':
						$svg_size = $large_size;
						break;
					case 'full':
						$svg_size = '';
						break;
				}
			} else {
				$custom_w = $width ? 'width="' . esc_attr( $width ) . '"' : '';
				$custom_h = $height ? ' height="' . esc_attr( $height ) . '" ' : '';
				$svg_size = $custom_w . $custom_h;
			}
			return $svg_size;
		}

		/**
		 * Get Image srcset.
		 *
		 * @since   1.0.0
		 */
		public static function get_link_attr( $data ) {
			$link_src         = new stdClass();
			$link_src->src    = isset( $data['url'] ) && $data['url'] != '' ? 'href="' . esc_url( $data['url'], self::ssl_url() ) . '"' : '';
			$link_src->rel    = isset( $data['nofollow'] ) && $data['nofollow'] != '' ? ' rel="nofollow"' : '';
			$link_src->target = isset( $data['is_external'] ) && $data['is_external'] != '' ? ' target="_blank"' : '';
			$link_src->attr   = isset( $data['custom_attributes'] ) && $data['custom_attributes'] != '' ? explode( ',', $data['custom_attributes'] ) : '';
			if ( $link_src->src ) {
				if ( $data['custom_attributes'] ) {
					foreach ( $link_src->attr as $value ) {
						$link_src->src .= ' ' . str_replace( '|', '="', $value ) . '"';
					}
				}
			}
			return $link_src;
		}

		/**
		 * Minimum capability.
		 *
		 * @since   1.0.0
		 */
		public static function capability() {
			return 'manage_options';
		}

		/**
		 * CSS Minifier.
		 *
		 * @since   1.0.0
		 */
		public static function cssminifier( $css ) {
			$css = str_replace(
				array( "\r\n", "\r", "\n", "\t", '    ' ),
				'',
				preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', trim( $css ) )
			);
			return str_replace(
				array( '  ', '{ ', ' }', ' {', '} ', ' screen and ', '; ', ', ', ': ' ),
				array( '', '{', '}', '{', '}', '', ';', ',', ':' ),
				$css
			);
		}

		/**
		 * JS Minifier.
		 *
		 * @since   1.0.0
		 */
		public static function jsminifier( $js ) {
			$js = str_replace(
				array( "\r\n", "\r", "\n", "\t", '    ' ),
				'',
				preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', trim( $js ) )
			);
			return str_replace(
				array( '  ', '{ ', ' }', ' {', '} ', '; ', ', ', ': ', ' (' ),
				array( '', '{', '}', '{', '}', ';', ',', ':', '(' ),
				$js
			);
		}

		/**
		 * Post Excerpt.
		 *
		 * @since   1.0.0
		 */
		public static function post_excerpt( $n ) {
			return substr( get_the_content(), 0, $n );
		}

		/**
		 * Post Formats.
		 *
		 * @since   1.0.0
		 */
		public static function post_format_icon( $icon = array() ) {
			$icon = array(
				'gallery'  => $icon['gallery'],
				'link'     => $icon['link'],
				'image'    => $icon['image'],
				'quote'    => $icon['quote'],
				'status'   => $icon['status'],
				'video'    => $icon['video'],
				'aside'    => $icon['aside'],
				'standard' => $icon['standard'],
			);

			$post_format = get_post_format( get_the_ID() ) ? get_post_format( get_the_ID() ) : 'standard';

			if ( $post_format == 'gallery' && ! empty( $icon['gallery'] ) ) {
					echo '<div class="kata-post-format">';
					echo self::get_icon( '', $icon['gallery'], '', '' );
					echo '</div>';
			} elseif ( $post_format == 'link' && ! empty( $icon['link'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['link'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'image' && ! empty( $icon['image'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['image'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'quote' && ! empty( $icon['quote'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['quote'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'status' && ! empty( $icon['status'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['status'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'video' && ! empty( $icon['video'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['video'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'aside' && ! empty( $icon['aside'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['aside'], '', '' );
				echo '</div>';
			} elseif ( $post_format == 'standard' && ! empty( $icon['standard'] ) ) {
				echo '<div class="kata-post-format">';
				echo self::get_icon( '', $icon['standard'], '', '' );
				echo '</div>';
			}
		}

		/**
		 * Sanatize CSS value.
		 *
		 * @since   1.0.0
		 */
		public static function validate_unit_of_number( $value ) {
			if ( is_numeric( $value ) ) :
				return $value . 'px';
			endif;

			return $value;
		}

		/**
		 * Make File.
		 *
		 * @since   1.0.0
		 */
		public static function mkfile( $path, $name ) {
			if ( ! file_exists( $path . '/' . $name ) ) {
				global $wp_filesystem;
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}
				$wp_filesystem->put_contents(
					$path . '/' . $name,
					'',
					FS_CHMOD_FILE
				);
			}
		}

		/**
		 * Make and write File.
		 *
		 * @since   1.0.0
		 */
		public static function wrfile( $path, $content ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			$wp_filesystem->put_contents( $path, $content, 0644 );
		}

		/**
		 * Read File.
		 *
		 * @since   1.0.0
		 */
		public static function rfile( $path ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			return $wp_filesystem->get_contents( $path );
		}

		/**
		 * Remove Directory.
		 *
		 * @since   1.0.0
		 */
		public static function rmdir( $path ) {
			if ( file_exists( $path ) ) {
				if ( ! class_exists( 'WP_Filesystem_Base' ) ) {
					global $wp_filesystem;
					if ( empty( $wp_filesystem ) ) {
						require_once ABSPATH . '/wp-admin/includes/file.php';
						WP_Filesystem();
					}
				}
				global $wp_filesystem;
				$wp_filesystem->rmdir( $path, true );
			}
		}

		/**
		 * Make Directory.
		 *
		 * @since   1.0.0
		 */
		public static function mkdir( $path ) {
			if ( ! file_exists( $path ) ) {
				if ( ! class_exists( 'WP_Filesystem_Base' ) ) {
					global $wp_filesystem;
					if ( empty( $wp_filesystem ) ) {
						require_once ABSPATH . '/wp-admin/includes/file.php';
						WP_Filesystem();
					}
				}
				// global $wp_filesystem;
				// $wp_filesystem->mkdir($path, false);
				mkdir( $path, 0777 );
			}
		}

		/**
		 * Get icon url.
		 *
		 * @since   1.0.0
		 */
		public static function get_icon_dir( $icon_name, $font_family = '' ) {
			$font_family = $font_family ? $font_family . '/' : '';
			$assets_dir  = self::string_is_contain( $font_family, '7-stroke' ) || self::string_is_contain( $icon_name, '7-stroke' ) && class_exists( 'Kata_Plus_Pro' ) ? Kata_Plus_Pro::$assets_dir : Kata_Plus::$assets_dir;
			return apply_filters( 'kata-get-icon-dir', $assets_dir . 'fonts/svg-icons/' . $font_family . $icon_name . '.svg' );
		}

		/**
		 * Get icon url.
		 *
		 * @since   1.0.0
		 */
		public static function get_icon_url( $icon_name, $font_family = '' ) {
			return self::abs_path_to_url( static::get_icon_dir( $icon_name, $font_family ) );
		}

		/**
		 * path to url.
		 *
		 * @since   1.0.0
		 */
		public static function abs_path_to_url( $path = '' ) {
			$url = str_replace(
				wp_normalize_path( untrailingslashit( ABSPATH ) ),
				site_url(),
				wp_normalize_path( $path )
			);
			return esc_url_raw( $url );
		}

		/**
		 * Get SVG icon.
		 *
		 * @since   1.0.0
		 */
		public static function get_icon( $font_family = '', $icon_name = '', $custom_class = '', $extra_attr = '' ) {
			if ( ! empty( $icon_name ) ) {
				$custom_class = ! empty( $custom_class ) ? ' ' . $custom_class : '';
				$extra_attr   = ! empty( $extra_attr ) ? ' ' . $extra_attr : '';
				$attach_id    = basename( $icon_name) ;
				if( $_icon_path = get_attached_file( $attach_id ) ) {
					$icon = file_get_contents( $_icon_path );
				} else {
					$icon = file_get_contents( self::get_icon_dir( $icon_name, $font_family ) );
				}
				return '<i class="kata-icon' . $custom_class . '"' . $extra_attr . '>' . $icon . '</i>';
			}
			return '';
		}

		/**
		 * String is Contain
		 *
		 * @since   1.0.0
		 */
		public static function string_is_contain( $string, $search ) {
			if ( strpos( $string, $search ) !== false ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get Latest Post ID.
		 *
		 * @since   1.0.0
		 */
		public static function get_latest_post_id() {
			if ( ! Plugin::$instance->editor->is_edit_mode() ) {
				return;
			}

			$latest_post = get_posts( 'post_type=post&numberposts=1' );
			return $latest_post[0]->ID;
		}

		/**
		 * Get Latest Course ID.
		 *
		 * @since   1.0.0
		 */
		public static function get_latest_course_id() {
			if ( ! Plugin::$instance->editor->is_edit_mode() ) {
				return get_the_ID();
			} else {
				$latest_post = get_posts( 'post_type=lp_course&numberposts=1' );
				return $latest_post[0]->ID;
			}
		}

		/**
		 * Image Resizer
		 *
		 * @since   1.0.0
		 */
		public static function image_resize( $id, $size = array() ) {
			if ( ! empty( $id ) && $size[1] && is_array( $size ) ) {
				$file        = get_attached_file( $id, true );
				$img_path    = realpath( $file );
				$file_exists = str_replace(
					array( '.jpg', '.png' ),
					array(
						'-' . $size[0] . 'x' . $size[1] . '.jpg',
						'-' . $size[0] . 'x' . $size[1] . '.png',
					),
					$img_path
				);
				if ( ! file_exists( $file_exists ) ) {
					$image    = wp_get_image_editor( $img_path );
					$filename = wp_basename( $img_path );
					$src      = str_replace( $filename, '', $img_path );
					if ( ! is_wp_error( $image ) ) {
						$image->resize( $size['0'], $size['1'], true );
						$save_name = $image->generate_filename( $size[0] . 'x' . $size[1], $src, null );
						$save      = $image->save( $save_name );
						return str_replace( $filename, $save['file'], wp_get_attachment_url( $id ) );
					} else {
						return wp_get_attachment_url( $id );
					}
				} else {
					return str_replace(
						array( '.jpg', '.png' ),
						array(
							'-' . $size[0] . 'x' . $size[1] . '.jpg',
							'-' . $size[0] . 'x' . $size[1] . '.png',
						),
						wp_get_attachment_url( $id )
					);
				}
			} else {
				return wp_get_attachment_url( $id );
			}
		}

		/**
		 * Image Resize Output
		 *
		 * @since   1.0.0
		 */
		public static function image_resize_output( $id = '', $size = array(), $custom_attr = '', $classes = '' ) {
			$id       = $id ? $id : get_post_thumbnail_id();
			$alt      = get_post_meta( $id, '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( $id, '_wp_attachment_image_alt', true ) . ' ' : ' alt ';
			$classes  = $classes ? 'kata-single-post-featured-image ' . $classes : 'kata-single-post-featured-image';
			$dim      = '';
			$metadata = wp_get_attachment_metadata( $id );
			if ( ( $metadata ) || ( is_array( $size ) && isset( $size[0] ) && ! empty( $size[0] ) ) ) {
				$dim .= is_array( $size ) && isset( $size[0] ) && ! empty( $size[0] ) ? 'width="' . $size[0] . '"' : 'width="' . $metadata['width'] . '"';
				$dim .= is_array( $size ) && isset( $size[1] ) && ! empty( $size[1] ) ? ' height="' . $size[1] . '"' : ' height="' . $metadata['height'] . '"';
			}
			$lazyload = get_theme_mod( 'kata_plus_pro_lazyload', false );
			if ( $lazyload === true && ! isset( $_GET['action'] ) ) {
				$src = ' src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="' . self::image_resize( $id, $size ) . '"';
			} else {
				$src = ' src="' . self::image_resize( $id, $size ) . '"';
			}
			if ( $src != ' src=""' ) {
				echo '<img ' . $dim . $src . '  class="' . esc_attr( $classes ) . '" ' . esc_attr( $alt ) . $custom_attr . '>';
			}
		}

		/**
		 * get string between
		 * https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php
		 *
		 * @since   1.0.0
		 */
		public static function get_string_between( $string, $start, $end ) {
			$string = ' ' . $string;
			$ini    = strpos( $string, $start );
			if ( $ini == 0 ) {
				return '';
			}
			$ini += strlen( $start );
			$len  = strpos( $string, $end, $ini ) - $ini;
			return substr( $string, $ini, $len );
		}

		/**
		 * generate video
		 *
		 * @since   1.0.0
		 */
		public static function video_player( $url ) {
			if ( self::string_is_contain( $url, 'https://www.youtube.com/watch?v=' ) ) {
				$url = str_replace( 'https://www.youtube.com/watch?v=', '', $url );
				$url = ' data-video="' . esc_url( 'https://www.youtube.com/embed/' . $url ) . '" data-videotype="' . esc_attr( 'youtube' ) . '"';
			} elseif ( self::string_is_contain( $url, 'https://youtu.be/' ) ) {
				$url = str_replace( 'https://youtu.be/', '', $url );
				$url = ' data-video="' . esc_url( 'https://www.youtube.com/embed/' . $url ) . '" data-videotype="' . esc_attr( 'youtube' ) . '"';
			} elseif ( self::string_is_contain( $url, 'https://www.youtube.com/embed/' ) ) {
				$url = ' data-video="' . esc_url( $url ) . '" data-videotype="youtube"';
			} elseif ( self::string_is_contain( $url, 'https://vimeo.com/' ) && ! self::string_is_contain( $url, 'player.vimeo.com' ) ) {
				$url = str_replace( 'https://vimeo.com/', '', $url );
				$url = ' data-video="' . esc_url( 'https://player.vimeo.com/video/' . $url ) . '" data-videotype="' . esc_attr( 'vimeo' ) . '"';
			} elseif ( self::string_is_contain( $url, 'https://player.vimeo.com/video/' ) ) {
				$url = ' data-video="' . esc_url( $url ) . '" data-videotype="' . esc_attr( 'vimeo' ) . '"';
			} elseif ( self::string_is_contain( $url, site_url() ) ) {
				$url = ' data-video="' . esc_url( $url ) . '" data-videotype="' . esc_attr( 'hosted' ) . '"';
			}
			if ( ! $url ) {
				return false;
			}
			return $url;
		}

		/**
		 * Comments Template for Single Builder
		 *
		 * @since   1.0.0
		 */
		public static function comments_template() {
			?>
			<div id="kata-comments" class="kata-comments-area">
				<ul class="kata-comment-list">
					<!-- #comment-## -->
					<li id="comment-8" class="comment odd alt thread-odd thread-alt depth-1">
						<article id="div-comment-8" class="comment-body">
							<footer class="comment-meta">
								<div class="comment-author vcard"> <img alt=""
										src="http://1.gravatar.com/avatar/75e48a7020624657e5da6033590030ee?s=81&amp;d=mm&amp;r=g"
										srcset="http://1.gravatar.com/avatar/75e48a7020624657e5da6033590030ee?s=162&amp;d=mm&amp;r=g 2x"
										class="avatar avatar-81 photo" height="81" width="81" loading="lazy"> <b class="fn">Anonymous
										User</b> <span class="says">says:</span> </div><!-- .comment-author -->
								<div class="comment-metadata"> <a href="#"><time
											datetime="2013-03-11T23:45:54+00:00">March 11, 2013 at 11:45 pm</time></a> <span
										class="edit-link"><a class="comment-edit-link"
											href="#">Edit</a></span>
								</div><!-- .comment-metadata -->
							</footer><!-- .comment-meta -->
							<div class="comment-content">
								<p>This user it trying to be anonymous.</p>
								<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui excepturi atque velit mollitia quaerat,
									dolore iusto molestiae numquam laboriosam fugiat, ipsa quod ad rerum saepe, quas ex nobis ratione
									sunt!</p>
							</div><!-- .comment-content -->
							<div class="reply"><a rel="nofollow" class="comment-reply-link"
									href="#" data-commentid="8"
									data-postid="1148" data-belowelement="div-comment-8" data-respondelement="respond"
									data-replyto="Reply to Anonymous User" aria-label="Reply to Anonymous User">Reply</a></div>
						</article><!-- .comment-body -->
					</li>
					<li id="comment-12" class="comment odd alt thread-odd thread-alt depth-1 parent">
						<article id="div-comment-12" class="comment-body">
							<footer class="comment-meta">
								<div class="comment-author vcard"> <img alt=""
										src="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=81&amp;d=mm&amp;r=g"
										srcset="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=162&amp;d=mm&amp;r=g 2x"
										class="avatar avatar-81 photo" height="81" width="81" loading="lazy"> <b class="fn"><a
											href="http://example.org/" rel="external nofollow ugc" class="url">John Κώστας Doe
											Τάδε</a></b> <span class="says">says:</span> </div><!-- .comment-author -->
								<div class="comment-metadata"> <a href="#"><time
											datetime="2013-03-14T07:57:01+00:00">March 14, 2013 at 7:57 am</time></a> <span
										class="edit-link"><a class="comment-edit-link"
											href="#">Edit</a></span>
								</div><!-- .comment-metadata -->
							</footer><!-- .comment-meta -->
							<div class="comment-content">
								<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor voluptas autem minima et modi,
									possimus perferendis tempore sit esse rerum!</p>
							</div><!-- .comment-content -->
							<div class="reply"><a rel="nofollow" class="comment-reply-link"
									href="#" data-commentid="12"
									data-postid="1148" data-belowelement="div-comment-12" data-respondelement="respond"
									data-replyto="Reply to John Κώστας Doe Τάδε" aria-label="Reply to John Κώστας Doe Τάδε">Reply</a>
							</div>
						</article><!-- .comment-body -->
						<ul class="children">
							<li id="comment-13" class="comment even depth-2 parent">
								<article id="div-comment-13" class="comment-body">
									<footer class="comment-meta">
										<div class="comment-author vcard"> <img alt=""
												src="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=81&amp;d=mm&amp;r=g"
												srcset="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=162&amp;d=mm&amp;r=g 2x"
												class="avatar avatar-81 photo" height="81" width="81" loading="lazy"> <b class="fn"><a
													href="http://example.org/" rel="external nofollow ugc" class="url">Jane
													Bloggs</a></b> <span class="says">says:</span> </div><!-- .comment-author -->
										<div class="comment-metadata"> <a
												href="#"><time
													datetime="2013-03-14T08:01:21+00:00">March 14, 2013 at 8:01 am</time></a> <span
												class="edit-link"><a class="comment-edit-link"
													href="#">Edit</a></span>
										</div><!-- .comment-metadata -->
									</footer><!-- .comment-meta -->
									<div class="comment-content">
										<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui excepturi atque velit mollitia
											quaerat, dolore iusto molestiae numquam laboriosam fugiat, ipsa quod ad rerum saepe, quas ex
											nobis ratione sunt!</p>
									</div><!-- .comment-content -->
									<div class="reply"><a rel="nofollow" class="comment-reply-link"
											href="#" data-commentid="13"
											data-postid="1148" data-belowelement="div-comment-13" data-respondelement="respond"
											data-replyto="Reply to Jane Bloggs" aria-label="Reply to Jane Bloggs">Reply</a></div>
								</article><!-- .comment-body -->
								<ul class="children">
									<li id="comment-14" class="comment odd alt depth-3 parent">
										<article id="div-comment-14" class="comment-body">
											<footer class="comment-meta">
												<div class="comment-author vcard"> <img alt=""
														src="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=81&amp;d=mm&amp;r=g"
														srcset="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=162&amp;d=mm&amp;r=g 2x"
														class="avatar avatar-81 photo" height="81" width="81" loading="lazy"> <b
														class="fn"><a href="http://example.org/" rel="external nofollow ugc"
															class="url">Fred Bloggs</a></b> <span class="says">says:</span> </div>
												<!-- .comment-author -->
												<div class="comment-metadata"> <a
														href="#"><time
															datetime="2013-03-14T08:02:06+00:00">March 14, 2013 at 8:02 am</time></a>
													<span class="edit-link"><a class="comment-edit-link"
															href="#">Edit</a></span>
												</div><!-- .comment-metadata -->
											</footer><!-- .comment-meta -->
											<div class="comment-content">
												<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui excepturi atque velit
													mollitia quaerat, dolore iusto molestiae numquam laboriosam fugiat, ipsa quod ad
													rerum saepe, quas ex nobis ratione sunt!</p>
											</div><!-- .comment-content -->
											<div class="reply"><a rel="nofollow" class="comment-reply-link"
													href="#"
													data-commentid="14" data-postid="1148" data-belowelement="div-comment-14"
													data-respondelement="respond" data-replyto="Reply to Fred Bloggs"
													aria-label="Reply to Fred Bloggs">Reply</a></div>
										</article><!-- .comment-body -->
										<ul class="children">
											<li id="comment-15" class="comment even depth-4 parent">
												<article id="div-comment-15" class="comment-body">
													<footer class="comment-meta">
														<div class="comment-author vcard"> <img alt=""
																src="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=81&amp;d=mm&amp;r=g"
																srcset="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=162&amp;d=mm&amp;r=g 2x"
																class="avatar avatar-81 photo" height="81" width="81" loading="lazy"> <b
																class="fn"><a href="http://example.org/" rel="external nofollow ugc"
																	class="url">Fred Bloggs</a></b> <span class="says">says:</span>
														</div><!-- .comment-author -->
														<div class="comment-metadata"> <a
																href="#"><time
																	datetime="2013-03-14T08:03:22+00:00">March 14, 2013 at 8:03
																	am</time></a> <span class="edit-link"><a class="comment-edit-link"
																	href="#">Edit</a></span>
														</div><!-- .comment-metadata -->
													</footer><!-- .comment-meta -->
													<div class="comment-content">
														<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui excepturi atque
															velit mollitia quaerat, dolore iusto molestiae numquam laboriosam fugiat,
															ipsa quod ad rerum saepe, quas ex nobis ratione sunt!</p>
													</div><!-- .comment-content -->
													<div class="reply"><a rel="nofollow" class="comment-reply-link"
															href="#"
															data-commentid="15" data-postid="1148" data-belowelement="div-comment-15"
															data-respondelement="respond" data-replyto="Reply to Fred Bloggs"
															aria-label="Reply to Fred Bloggs">Reply</a></div>
												</article><!-- .comment-body -->
												<ul class="children">
													<li id="comment-16" class="comment odd alt depth-5 parent">
														<article id="div-comment-16" class="comment-body">
															<footer class="comment-meta">
																<div class="comment-author vcard"> <img alt=""
																		src="http://1.gravatar.com/avatar/4fdb3b572ac7dd8d7a58ba70317efa14?s=81&amp;d=mm&amp;r=g"
																		srcset="http://1.gravatar.com/avatar/4fdb3b572ac7dd8d7a58ba70317efa14?s=162&amp;d=mm&amp;r=g 2x"
																		class="avatar avatar-81 photo" height="81" width="81"
																		loading="lazy"> <b class="fn"><a
																			href="https://wpthemetestdata.wordpress.com/"
																			rel="external nofollow ugc" class="url">themedemos</a></b>
																	<span class="says">says:</span> </div><!-- .comment-author -->
																<div class="comment-metadata">
																	<a href="#"><time datetime="2013-03-14T08:10:29+00:00">March 14, 2013 at 8:10 am</time></a> <span class="edit-link"><a class="comment-edit-link" href="#">Edit</a></span>
																</div><!-- .comment-metadata -->
															</footer><!-- .comment-meta -->
															<div class="comment-content">
																<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui excepturi atque velit mollitia quaerat, dolore iusto molestiae numquam laboriosam fugiat, ipsa quod ad rerum saepe, quas ex nobis ratione sunt!</p>
															</div><!-- .comment-content -->
														</article><!-- .comment-body -->
													</li><!-- #comment-## -->
													<li id="comment-17" class="comment even depth-5 parent">
														<article id="div-comment-17" class="comment-body">
															<footer class="comment-meta">
																<div class="comment-author vcard"> <img alt=""
																		src="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=81&amp;d=mm&amp;r=g"
																		srcset="http://0.gravatar.com/avatar/f72c502e0d657f363b5f2dc79dd8ceea?s=162&amp;d=mm&amp;r=g 2x"
																		class="avatar avatar-81 photo" height="81" width="81"
																		loading="lazy"> <b class="fn"><a href="http://example.org/"
																			rel="external nofollow ugc" class="url">Jane Bloggs</a></b>
																	<span class="says">says:</span> </div><!-- .comment-author -->
																<div class="comment-metadata">
																	<a href="#"><time datetime="2013-03-14T08:12:16+00:00">March 14, 2013 at 8:12 am</time></a> <span class="edit-link"><a class="comment-edit-link" href="#">Edit</a></span>
																</div><!-- .comment-metadata -->
															</footer><!-- .comment-meta -->
															<div class="comment-content">
																<p>Comment Depth 06 has some more text than some of the other comments on this post.</p>
															</div><!-- .comment-content -->
														</article><!-- .comment-body -->
													</li><!-- #comment-## -->
													<!-- #comment-## -->
												</ul><!-- .children -->
											</li><!-- #comment-## -->
										</ul><!-- .children -->
									</li><!-- #comment-## -->
								</ul><!-- .children -->
							</li><!-- #comment-## -->
						</ul><!-- .children -->
					</li>
				</ul>
			</div>
			<?php
		}

		/**
		 * Get Post by title
		 *
		 * @since   1.0.0
		 */
		public static function get_post_by_title( $post_type, $post_name ) {
			$args      = array(
				'post_type'   => $post_type,
				'name'        => $post_name,
				'post_status' => 'all',
				'numberposts' => 1,
				'compare'     => 'BINARY'
			);

			$the_query = new WP_Query( $args );

			if ( isset( $the_query->posts[0]->ID ) ) {
				return $the_query->posts[0]->ID;
			} else if ( empty( $the_query->posts ) ) {
				$args      = array(
					'post_type'   => $post_type,
					'title'       => $post_name,
					'post_status' => 'all',
					'numberposts' => 1,
				);

				$the_query = new WP_Query( $args );

				if ( isset( $the_query->posts[0]->ID ) ) {
					return $the_query->posts[0]->ID;
				}
			}
			return false;
		}
	} // class

	Kata_Plus_Helpers::get_instance();
}
