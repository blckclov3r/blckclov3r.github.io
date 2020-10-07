<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dbwp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ':&0WU_+c]lP9<.*aO>w=N*S^6K1q/[:tI1UTF~R4B~H_~:Cr?otiTG,KHYWlOBMs' );
define( 'SECURE_AUTH_KEY',  ';c~n;8zwlJ2|}Jr#Rff<<Wlm2*FJ5V/1`,uOcsJ{<JEsY5]OmSE*g7@]b>UOwn#m' );
define( 'LOGGED_IN_KEY',    '%d_@QL*JM9}K3@B|v|P|iE^r+*#}g)_@23Mzg!iU0@ v%)uQ9Y96C&kL=4^If4PG' );
define( 'NONCE_KEY',        '~BH3@(X@dBf;hwX!K?xZwl/8My``KF3ca$mZ9V?).:M!xvt<hp%4~M,pgl^#{J19' );
define( 'AUTH_SALT',        ')-Ya+atN]AN)z;Q@Es*/_YBHF$q}W;a(]A,xB%~9~EXF8ry$.o;+(]k*aKK 0V<P' );
define( 'SECURE_AUTH_SALT', 'WZ[*O,VMFJDUz%Yap?9Y8n9K-+w4|Y 0SIP*m-7z^%C;LV2@3.S3VafyF_m3_mcd' );
define( 'LOGGED_IN_SALT',   'tdOG3s.{h]IW9r|:u5JK-R@>X1.7U]b~-bM}BPv9+yy=QGQ}%2aB6O5?{C{%ZCx/' );
define( 'NONCE_SALT',       '[~%W)y}XY_>R%3DNJA/yFzMViM4*|5J,T^%0&?w;{zpuok%/m#2Uo;RjuS{go8q%' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
