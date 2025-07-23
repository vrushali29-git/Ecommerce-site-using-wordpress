<?php
/**
 * Add "like" functionality to your plugin
 *
 * @author  ThemeZilla
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Zilla_Likes' ) ) {
	class Kata_Plus_Zilla_Likes {
		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		function __construct() {
			add_action( 'publish_post', array( &$this, 'setup_likes' ) );
			add_action( 'wp_ajax_zilla-likes', array( &$this, 'ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_zilla-likes', array( &$this, 'ajax_callback' ) );
		}

		/**
		 * Setup likes.
		 *
		 * @since   1.0.0
		 */
		function setup_likes( $post_id ) {
			if ( ! is_numeric( $post_id ) ) {
				return;
			}

			add_post_meta( $post_id, '_zilla_likes', '0', true );
		}

		/**
		 * Run ajax.
		 *
		 * @since   1.0.0
		 */
		function ajax_callback( $post_id ) {
			$likes_id = sanitize_text_field( $_POST['likes_id'] );
			if ( isset( $likes_id ) ) {
				// Click event. Get and Update Count
				$post_id = str_replace( 'zilla-likes-', '', $likes_id );
				echo $this->like_this( $post_id, 'update' );
			} else {
				// AJAXing data in. Get Count
				$post_id = sanitize_text_field( $_POST['post_id'] );
				$post_id = str_replace( 'zilla-likes-', '', $post_id );
				echo $this->like_this( $post_id, 'get' );
			}

			exit;
		}

		/**
		 * Like this.
		 *
		 * @since   1.0.0
		 */
		function like_this( $post_id, $action = 'get' ) {
			if ( ! is_numeric( $post_id ) ) {
				return;
			}

			switch ( $action ) {
				case 'get':
					$likes = get_post_meta( $post_id, '_zilla_likes', true );
					if ( ! $likes ) {
						$likes = 0;
						add_post_meta( $post_id, '_zilla_likes', $likes, true );
					}

					return Kata_Plus_Helpers::get_icon( 'font-awesome', 'heart' ) . '<span class="zilla-likes-count">' . $likes . '</span>';
					break;
				case 'update':
					$likes = get_post_meta( $post_id, '_zilla_likes', true );
					if ( isset( $_COOKIE[ 'zilla_likes_' . $post_id ] ) ) {
						--$likes;
						unset( $_COOKIE[ 'zilla_likes_' . $post_id ] );
						setcookie( 'zilla_likes_' . $post_id, $post_id, time() - ( 10 * 365 * 24 * 60 * 60 ), '/' );
					} else {
						++$likes;
						setcookie( 'zilla_likes_' . $post_id, $post_id, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
					}

					update_post_meta( $post_id, '_zilla_likes', $likes );

					return Kata_Plus_Helpers::get_icon( 'font-awesome', 'heart' ) . '<span class="zilla-likes-count">' . $likes . '</span>';
					break;
			}
		}

		/**
		 * Do likes.
		 *
		 * @since   1.0.0
		 */
		function do_likes() {
			global $post;

			$output = $this->like_this( $post->ID );

			$class = 'zilla-likes';
			$title = __( 'Like this', 'kata-plus' );
			if ( isset( $_COOKIE[ 'zilla_likes_' . $post->ID ] ) ) {
				$class = 'zilla-likes active';
				$title = __( 'You already like this', 'kata-plus' );
			}

			return '<a href="#" class="' . $class . '" id="zilla-likes-' . $post->ID . '" data-id="' . $post->ID . '" title="' . $title . '">' . $output . '</a>';
		}
	}

	global $zilla_likes;
	$zilla_likes = new Kata_Plus_Zilla_Likes();

	function zilla_likes() {
		global $zilla_likes;
		echo $zilla_likes->do_likes();
	}
}
