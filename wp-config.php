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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ninjas_test');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'y9E5am~KwWhA&#c/WQQAK)0R!p>:{U=DOlxjg@O_F[4Xdw`bVF`tx3;)peJ?}&kt');
define('SECURE_AUTH_KEY',  'iMlJo,suGD$ojMNY76)r<h7(ct2MI$_u6OK0N|.ubKYbtvv&Xsg1HHn%PC):YTgm');
define('LOGGED_IN_KEY',    'SkZevvt903w9xbyZ1gYy&/ {Vii!;<}}M %~,-wa}~X%e)%B19G1<rLo,Z9tDb#S');
define('NONCE_KEY',        'SrSzfh>Uo<[V[:/JPT2|pC=Za3g+S>](a)Eeg*e)Dj?qyF^FL[{(q@8 4]$`[O%U');
define('AUTH_SALT',        '{H*V3{Q_#mk|a*=sChuW1+Z@IABrLBGQo AbwJSh2QohhHLKAG{_.WsYnzX+&+,p');
define('SECURE_AUTH_SALT', 'NI]uxAy.MJ-plv4h<{v>5h7Aj+;YJ{O~LWGl_5?YF[|R}rIiloOUE5w=,CCew|SQ');
define('LOGGED_IN_SALT',   'kb-kf,D$I#&zjXlef@LnPzd4)V4v;{4f8`n Z{rt<6YyF/NCe|jg-d`{SR8DhCM}');
define('NONCE_SALT',       'mb:p<YC,?l~@gIdNnE>_ddsU|r1J)`nm&T=`dxV#TIri[Bk$3[iX}U^r7J+-*!IJ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
