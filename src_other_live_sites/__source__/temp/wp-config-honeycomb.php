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
define('DB_NAME', 'honeycomb');

/** MySQL database username */
define('DB_USER', 'honeycomb');

/** MySQL database password */
define('DB_PASSWORD', 'i2h9Rz1?');

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
define('AUTH_KEY',         '|42tH`LV6/g$;TZz3UMGskwq W~Wn{-;8h;29OypmRsBN]3<LuwC;?&DamCN>8![');
define('SECURE_AUTH_KEY',  'm_NhCM%y(D.6}3kVF--Tu urI?*fCol+_WsWWEV^a4N }N# t,_:$Y[gd{`pI@/H');
define('LOGGED_IN_KEY',    '5mnDY7VI%1L/nuMiFEz;9LV~X)mrx+/S57&UBFGD>b#bj=N_Y@$x;!|K*-FN!Qd_');
define('NONCE_KEY',        'UClut)D^8S9#(>Mk8)]{OHVJ t6!,hiSmeAOW9i4vvd7/h)-VsEeG<7$<t:Y!XKc');
define('AUTH_SALT',        'hD(lKc4K*[M%46Jyk|Ur-4 nTfCd^.}<33z@uJ@+Kvg >A(#,zF_(rl)T8D.=Og*');
define('SECURE_AUTH_SALT', '7< (W6U*dz=bXq]SP#5O;~6$,74J8n,b1I2a.(%mSp}]:7JP6;0J8K2hlv=y>yU$');
define('LOGGED_IN_SALT',   'I{ m&Gu,[4xNzHN<j,`0zB*^7}Aa?W6Z@]W[$+LlI6jo/Z=tMuxaK0lMo`EOR/*>');
define('NONCE_SALT',       '/mo7<C-M;#5*G8uU[XM*lh$1l%sp+ rEk)BJ67 B!Ti|}^}u*5Y*xoBtU;G1c8#h');

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
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
