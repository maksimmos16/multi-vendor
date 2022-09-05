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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mv' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'X2?$,wWB#Js+Y,-~A !cLht:MC>fiVIWU2&S}BOE_7&J]%jb[Fvz:j`9i=6y% xN' );
define( 'SECURE_AUTH_KEY',  ' -XHf+A_RlSCEJ3Q?S cg~PtiG%z4aumSlA@~)K65OyO%!{*_JN(Rrr!:DWB*6NV' );
define( 'LOGGED_IN_KEY',    'xX70SZ6NgLfzfK 0}w->3<h#{C-pMtJ@H{wgJ@Stf|&P}(~uoJ+l9b*gqZ7N6dJb' );
define( 'NONCE_KEY',        'kWTn>p6{{`vYVZ{gf;4=M!D9er5Lk!XP}v];6[|;zVJWZMleUgZ)P:4Zf?3BgWi0' );
define( 'AUTH_SALT',        'Dou) x9b:w_flo:+*~3S1X<qb2LbPbNh2anCHne16[{Y)!F]P-[%dAMON:9D/hL#' );
define( 'SECURE_AUTH_SALT', 's1^&3@-0]snbd>^]xcVM*dNs*|WsCROK4@:%elwIF,sU*S4DT+:D^]x]FAxKn(4A' );
define( 'LOGGED_IN_SALT',   '!~!Xb{j0G&YqE=Lq{tA?R5(r#ZW3/mqY7_~|yY75*s:;CUmu()+wLQe5lPueUqWm' );
define( 'NONCE_SALT',       '3PzHzw}c~*PW}]?=T`sa<lxXYs )Hb/l@+1@pf%B(63Xm)l#p1268ssu,(MV(?eO' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
