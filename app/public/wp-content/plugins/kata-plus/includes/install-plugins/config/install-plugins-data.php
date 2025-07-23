<?php

/**
 * Install Plugins Data.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugins = array(
	array(
		'name'             => esc_html__( 'Elementor', 'kata-plus' ),
		'slug'             => 'elementor',
		'author'           => '<a href="' . esc_url( 'https://elementor.com/' ) . '">' . esc_html__( 'Elementor.com', 'kata-plus' ) . '</a>',
		'images_url'       => $this->images_url . 'kata-plugin-icon-2.png',
		'version'          => '3.6.5',
		'required'         => true,
		'force_activation' => false,
		'fast-mode'        => false,
	),
	array(
		'name'             => esc_html__( 'Kata Plus', 'kata-plus' ),
		'slug'             => 'kata-plus',
		'author'           => '<a href="' . esc_url( 'https://climaxthemes.com/' ) . '">' . esc_html__( 'Climaxthemes.com', 'kata-plus' ) . '</a>',
		'images_url'       => $this->images_url . 'kata-plugin-icon-2.png',
		'required'         => true,
		'force_activation' => false,
		'fast-mode'        => false,
	),
	array(
		'name'        => esc_html__( 'Swift Performance Lite', 'kata-plus' ),
		'slug'        => 'swift-performance-lite',
		'description' => __( 'Cache and Speed', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://swteplugins.com' ) . '">' . esc_html__( 'SWTE', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-3.png',
		'required'    => false,
		'fast-mode'   => false,
	),
	array(
		'name'        => esc_html__( 'LiteSpeed Cache', 'kata-plus' ),
		'slug'        => 'litespeed-cache',
		'description' => __( 'Cache and Speed', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://www.litespeedtech.com/' ) . '">' . esc_html__( 'LiteSpeed Technologies', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-35.png',
		'required'    => false,
		'fast-mode'   => true,
	),
	array(
		'name'       => esc_html__( 'Polylang', 'kata-plus' ),
		'slug'       => 'polylang',
		'author'     => '<a href="' . esc_url( 'https://polylang.pro/' ) . '">' . esc_html__( 'WP SYNTEX', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-14.png',
		'fast-mode'  => false,
	),
	array(
		'name'        => esc_html__( 'Contact Form 7', 'kata-plus' ),
		'slug'        => 'contact-form-7',
		'description' => __( 'For create form', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://ideasilo.wordpress.com/' ) . '">' . esc_html__( 'Takayuki Miyoshi', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-15.png',
		'fast-mode'   => false,
	),
	array(
		'name'       => esc_html__( 'WP24 Domain Check', 'kata-plus' ),
		'slug'       => 'wp24-domain-check',
		'author'     => '<a href="' . esc_url( 'https://wordpress.org/plugins/wp24-domain-check/' ) . '">' . esc_html__( 'WP24', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-22.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'Gift Vouchers', 'kata-plus' ),
		'slug'       => 'gift-voucher',
		'author'     => '<a href="' . esc_url( 'https://www.codemenschen.at/' ) . '">' . esc_html__( 'codemenschen', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-24.png',
		'fast-mode'  => false,
	),
	array(
		'name'        => esc_html__( 'Woocommerce', 'kata-plus' ),
		'slug'        => 'woocommerce',
		'description' => __( 'eCommerce', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://woocommerce.com/' ) . '">' . esc_html__( 'Automattic', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-16.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'Rank Math SEO', 'kata-plus' ),
		'slug'        => 'seo-by-rank-math',
		'description' => __( 'SEO Optimization', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://s.rankmath.com/home/' ) . '">' . esc_html__( 'Rank Math', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-25.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'Wordfence Security', 'kata-plus' ),
		'slug'        => 'wordfence',
		'description' => __( 'Firewall & Malware Scan', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'http://www.wordfence.com/' ) . '">' . esc_html__( 'Wordfence', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-26.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'All-in-One WP Migration', 'kata-plus' ),
		'slug'        => 'all-in-one-wp-migration',
		'description' => __( 'For Site Migration', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://servmask.com/' ) . '">' . esc_html__( 'ServMask', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-27.png',
		'fast-mode'   => false,
	),
	array(
		'name'        => esc_html__( 'UpdraftPlus', 'kata-plus' ),
		'slug'        => 'updraftplus',
		'description' => __( 'Site Migration & Backup', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://updraftplus.com/' ) . '">' . esc_html__( 'UpdraftPlus.Com, DavidAnderson', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-36.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'Sendinblue', 'kata-plus' ),
		'slug'        => 'mailin',
		'description' => __( 'Email Marketing', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://www.sendinblue.com/?r=wporg' ) . '">' . esc_html__( 'Sendinblue', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-37.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'Site Kit by Google', 'kata-plus' ),
		'slug'        => 'google-site-kit',
		'description' => __( 'For site analytics', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://opensource.google.com/' ) . '">' . esc_html__( 'Google', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-28.png',
		'fast-mode'   => true,
	),
	array(
		'name'        => esc_html__( 'WPForms', 'kata-plus' ),
		'slug'        => 'wpforms-lite',
		'description' => __( 'Form Builder', 'kata-plus' ),
		'author'      => '<a href="' . esc_url( 'https://wpforms.com/' ) . '">' . esc_html__( 'WPForms', 'kata-plus' ) . '</a>',
		'images_url'  => $this->images_url . 'kata-plugin-icon-38.png',
		'fast-mode'   => true,
	),
	array(
		'name'       => esc_html__( 'Amelia', 'kata-plus' ),
		'slug'       => 'ameliabooking',
		'author'     => '<a href="' . esc_url( 'https://ideasilo.wordpress.com/' ) . '">' . esc_html__( 'TMS', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-28.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'Hotel Booking Lite', 'kata-plus' ),
		'slug'       => 'motopress-hotel-booking-lite',
		'author'     => '<a href="' . esc_url( 'https://motopress.com/' ) . '">' . esc_html__( 'MotoPress', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-30.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'Weather Atlas Widget', 'kata-plus' ),
		'slug'       => 'weather-atlas',
		'author'     => '<a href="' . esc_url( 'https://www.weather-atlas.com/' ) . '">' . esc_html__( 'Yu Media Group d.o.o.', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-31.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'Wp Social', 'kata-plus' ),
		'slug'       => 'wp-social',
		'author'     => '<a href="' . esc_url( 'https://wpmet.com/' ) . '">' . esc_html__( 'Wpmet', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-32.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'Cryptocurrency Widgets', 'kata-plus' ),
		'slug'       => 'cryptocurrency-price-ticker-widget',
		'author'     => '<a href="' . esc_url( 'https://coolplugins.net/' ) . '">' . esc_html__( 'Cool Plugins', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-33.png',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'ShopPress', 'kata-plus' ),
		'slug'       => 'shop-press',
		'author'     => '<a href="' . esc_url( 'https://climaxthemes.com/' ) . '">' . esc_html__( 'ClimaxThemes', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-39.svg',
		'fast-mode'  => false,
	),
	array(
		'name'       => esc_html__( 'LearnPress', 'kata-plus' ),
		'slug'       => 'learnpress',
		'author'     => '<a href="' . esc_url( 'http://thimpress.com/' ) . '">' . esc_html__( 'ThimPress', 'kata-plus' ) . '</a>',
		'images_url' => $this->images_url . 'kata-plugin-icon-40.png',
		'fast-mode'  => false,
	),
);

return $plugins;
