<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'GPH5^Lt,HcAs|}$!F0|g[Q?)m-Q2Wna3!knLg<V5Tsquv[F#),us4?Ze5sB*(XtI' );
define( 'SECURE_AUTH_KEY',   '$@vCyeEEz1Ct6KD8zf1Tk3&yatDGwvI</5Gf}++dzQ]Mk{>2UGieL-LmxteR_wZ|' );
define( 'LOGGED_IN_KEY',     '0]l.z$F!]YeN<fK#yv@&DF#yg?CX-uPj8 9R,4FxF>OcqgrTfe F}`zX,hT<m Jk' );
define( 'NONCE_KEY',         '`Ca`=ZX_Qx `o-85*@I[s<z:O/hUg[ AUiQ[KIy;fRq_Qo*>ODHJP%GPvj_yf[Nv' );
define( 'AUTH_SALT',         '[yqzy.ufG|wMmx=a RlZ><lDxsQ|DEL(v!=i{N2Y0/}X$_}vL]Q$aV4MhQnD1O~V' );
define( 'SECURE_AUTH_SALT',  'U,Q82aZ~1=;}{TL(k?<#[lBaNd<7N78c&CM5r+xE<?E[?ZSD~vo8]O#uUfDIO`zO' );
define( 'LOGGED_IN_SALT',    '!UGh1DVlo$*cUe#r5^IQh7[w24ppH>y$+lWQ_OiVN>NCX Zn+L.fzk^M2cq#zPh=' );
define( 'NONCE_SALT',        ')ih|E#9hr>w`ob3&VKD:HzD`!9d2Nse19:u<gd7CdP`<yQ=gAdD;.nZa-<fdYA)c' );
define( 'WP_CACHE_KEY_SALT', '%,Rq{ @w/ `}?E|cw8*-@~>jf4xSmI=a$.I+]dPe!OBU7PS+Oh2<?ez.zTy_*c@g' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
