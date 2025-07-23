<?php

/**
 * ShopPress Thumbnail Generator
 *
 * @since 1.0.2
 * @package Shop Press
 */

namespace ShopPress\Helpers;

if ( ! class_exists( 'ThumbnailGenerator' ) ) {

	class ThumbnailGenerator {

		/**
		 * Instance of this class.
		 *
		 * @since  1.0.3
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.3
		 *
		 * @return  object
		 */
		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		 * Image Resizer
		 *
		 * @since   1.0.3
		 */
		public function image_resize( $id, $size = array() ) {

			if ( ! empty( $id ) && is_array( $size ) && $size[1] ) {

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
					$image    = wp_get_image_editor( wp_get_attachment_url( $id ) );
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
						array( '-' . $size[0] . 'x' . $size[1] . '.jpg', '-' . $size[0] . 'x' . $size[1] . '.png' ),
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
		 * @since   1.0.3
		 */
		public function image_resize_output( $id = '', $size = array(), $custom_attr = '', $classes = '', $return = false ) {

			$id       = $id ? $id : get_post_thumbnail_id();
			$alt      = get_post_meta( $id, '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( $id, '_wp_attachment_image_alt', true ) . ' ' : ' alt ';
			$dim      = '';
			$metadata = wp_get_attachment_metadata( $id );

			if ( is_string( $size ) ) {

				$image_sizes = self::get_all_image_sizes();
				$thumb_size  = $image_sizes[ $size ] ?? false;
				if ( $thumb_size ) {

					$size = array(
						$thumb_size['width'],
						$thumb_size['height'],
						$thumb_size['crop'],
					);
				}
			} elseif ( isset( $size['width'] ) ) {
				$size = array(
					$size['width'],
					$size['height'],
					$size['crop'] ?? false,
				);
			}

			if ( ( $metadata ) || ( is_array( $size ) && isset( $size[0] ) && ! empty( $size[0] ) ) ) {
				$dim .= is_array( $size ) && isset( $size[0] ) && ! empty( $size[0] ) ? 'width="' . $size[0] . '"' : 'width="' . $metadata['width'] . '"';
				$dim .= is_array( $size ) && isset( $size[1] ) && ! empty( $size[1] ) ? ' height="' . $size[1] . '"' : ' height="' . $metadata['height'] . '"';
			}

			$srcset   = wp_get_attachment_image_srcset( $id );
			$full_url = wp_get_attachment_url( $id );
			$url      = self::image_resize( $id, $size );
			if ( $url ) {
				$img_html = '<img ' . $dim . ' data-src="' . esc_url( $full_url ) . '" src="' . esc_url( $url ) . '" srcset="' . esc_attr( $srcset ) . '" class="' . esc_attr( $classes ) . '" ' . esc_attr( $alt ) . $custom_attr . '>';

				if ( $return ) {
					return $img_html;
				}

				echo $img_html;
			}
		}

		/**
		 * Return all image sizes
		 *
		 * @since 1.3.1
		 *
		 * @return array
		 */
		public static function get_all_image_sizes() {
			global $_wp_additional_image_sizes;

			$default_image_sizes = get_intermediate_image_sizes();

			foreach ( $default_image_sizes as $size ) {
				$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
				$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
				$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
			}

			if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
				$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
			}

			return $image_sizes;
		}
	}

}
