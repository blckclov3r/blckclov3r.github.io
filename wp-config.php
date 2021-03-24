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
define( 'DB_NAME', 'blckclov3r_db' );

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
define( 'AUTH_KEY',         'k@~bV~:&=7Xmk:;8s=w;U[kZ@mIgzHe)J*-Kc f|48C~!y$2{|7Io/>})U:=u_D@' );
define( 'SECURE_AUTH_KEY',  'Fx*)mXACF<)d`!8h4W~%(Ie#hq%T0[4!GRpvj2GE:U`55)h^ePP@nH z~x5Az@p*' );
define( 'LOGGED_IN_KEY',    '[ Ro#P [/u*`Zy&NTAtOg9aNqJFc,C.um1h<43v7I^z2eWRnc8a-&xa:*%`[j8bc' );
define( 'NONCE_KEY',        ',U>IhLvDLIMS-8]U^hHZ>dq?kF$/*WdzoYBN?9&!l;^Gx.ZI^XlpFe|{xplB0zax' );
define( 'AUTH_SALT',        'Mhv&A4EmxVL.^YApm82?qc5z_TiWJlN@gW2flNEPxD$;[4t]7tfV+!Gn7/kRf(tk' );
define( 'SECURE_AUTH_SALT', '`oIgm*MV0sItg1Vs8rmCGaV^mm=*g.[39mmT<O>6pH:zRx4B2p8[TFB[l`eA<jC;' );
define( 'LOGGED_IN_SALT',   'VHB_L<b5+.*[)s~bHc-JOH-XQJ4WLSXD.>_0 c_X33_jh(p?eR=BAs/ijwUBt8j%' );
define( 'NONCE_SALT',       'HAHI.I!<8;2V3ID.=-PObToky9WNh)$l|t=6?0SxRt=WjG`,Jd|6IoH3.oeXc*Wc' );

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
