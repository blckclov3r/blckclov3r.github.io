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
define( 'DB_NAME', 'blckclov3r' );
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
define( 'AUTH_KEY',         'Xbr0k5s6xeq]2D}#b6-EX%koP>gZ6tM>q6dbXx6F_`B*gf9BPT>lL,6Q}RqkK[qm' );
define( 'SECURE_AUTH_KEY',  '`F$,=v  C:*0^+ou~oEL799=G2FYrw=/$i:|7C7tN&_W]EA)E7:*svs+Xq-~=QN1' );
define( 'LOGGED_IN_KEY',    'dE]f9tpJL6%_zGsfQ<{YE<Z{iY{q_DtNm1a//}Yqtk$>~#:LAQDlNj=|udj%[)3l' );
define( 'NONCE_KEY',        '6eKC!s[_(X:C]&rM*%?6Al-7ofm6q7a4n|-ZQ^xNwVgm5d {Y1A23W.^/3JzvnCE' );
define( 'AUTH_SALT',        'zD- G@P#TE*x&tMFukZVymdbL4)~${8]tU4Iu/}:{:cnTCNF]#/!7DpiF^lv#T/*' );
define( 'SECURE_AUTH_SALT', 'fVa!RM$l`8EGUoq.CU@*Xo*OH5M0{!qwO7<:% ZUF#Og3*Yw^_[Pa^Nk1BQB)I=}' );
define( 'LOGGED_IN_SALT',   'U4du(>e< uh*}p*]uwYiU*v*eGgX5guY/x]tb_R`fT3C6E!2[}j/-P<mW,&lK%hb' );
define( 'NONCE_SALT',       'U7KFm&HABEaHA<cq=Cte%`qHp)o^wO]*K&aNXv1tS}@i/kyBk]>Oog[lHjOB-+hI' );
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