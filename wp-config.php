<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'windamniacs-web');

/** MySQL database username */
define('DB_USER', 'windamniacs');

/** MySQL database password */
define('DB_PASSWORD', 'd3vt34mr0ck3');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/Vc4+l(21(@.d%Fz/iX4Du!35c`>iR~D2b%RwH)e~A9=p)Z>,T#jrLzB}z3BVyu/');
define('SECURE_AUTH_KEY',  'EGr+C(CmE6q>oMa%%76U`,G#^$71U!UAkh aNvS ?v`wC/u1Yn!q@01*,Gc/ld,q');
define('LOGGED_IN_KEY',    'U%}2~SrQN,JG]q2hLe3V6T;;#x]II|;xUH-|K1T$yZ4I% &2-ZW_1qV-y-8qpA?j');
define('NONCE_KEY',        '!<`Y?iG}G*;(uo0q>frvg]*h|_U>.lK1MzH+@6h?hp4_C }$N.s2gN@|cof6Afw[');
define('AUTH_SALT',        'hV`a1(=+9~17dkbzJG3~q[RSThxwr6+eA!JLu/mf%M/||k>rv<Q{]~$q~+(B[~rE');
define('SECURE_AUTH_SALT', 'aadKgO/jgU:5u+xjt_.OQDh_yy9B4?+^J|KIQ1Yh;2Z5zJ*{7Gz2t?~IV|8z,kbN');
define('LOGGED_IN_SALT',   'L{h|$A!9#{ntrhyW,U.i8T&Kcj0MCf!Y`R4+Hy-r4E+f*(`{<etrowxfGVrmFhE&');
define('NONCE_SALT',       'm-/,vYv2G{BG-H+.l&:s(C^r%ObT,_r5Gs$F6t,(x.;3}^OrnksZ=dK;4E01^k$^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
