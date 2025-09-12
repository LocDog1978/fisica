<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fisica' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'm`<XINdYHMex Ns$*DeGC<HKd>7g3mNsSIvSRR}cQFT4L@zt8 MF@>3D@%8Gl4J1' );
define( 'SECURE_AUTH_KEY',  'a%69GZVEFs8a6$&O#TIGgt}<v~w<EX484Zie(0+<nze^_.@-=teR9a+A{0<@42uN' );
define( 'LOGGED_IN_KEY',    'oJff AdB^V}UbxWp!7nca!/L:zaf4U9i(Dj,ceV~&o~1sn*uadrK3ZnOzoDi[_K5' );
define( 'NONCE_KEY',        '5<?^f-4[vM})}>*GZlo65^!{li/C|U*^yY@OZeF,WK.]ake1)A[.fXA,3MJ^ft(N' );
define( 'AUTH_SALT',        '#v`-v[V></Rt=A+f~3O/S(^!f[k[K4T#c1h@dFQg/]J7}FVc0D82vX*9V|}nOszk' );
define( 'SECURE_AUTH_SALT', 'T:obW!ZB7!QqH49UKfKD<ek=TdWix!zXOh/m[os %loI,Q-8Cr~[6eEG:FQhW%{e' );
define( 'LOGGED_IN_SALT',   '&?{nw{41^_;AgdB#~tGq2{_|U[ DZezQIS-|L`im/ZqzDI&~K9f:.&esn? _zJL9' );
define( 'NONCE_SALT',       'JrI2hF.$2-Il(O@f?+BBMTzAU87Nh) BJ5.k)QDEx`iUU9gkWk SUo2+U.{4A=NW' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
