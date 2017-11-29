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
define('DB_NAME', 'bdtravelguide');

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
define('AUTH_KEY',         'H}z@hceeIE8$VpH@Hq5m>,n)qn-`CEMx/qX[(c6o-^#/$Wb{=G0(gY5>fsb0QHK%');
define('SECURE_AUTH_KEY',  'gFKV,Ah5z Iv6BTXq3+=}7@=mt53p-?eKd*E//iK,1s&WIR|Y^lfjicW|TI8w#(;');
define('LOGGED_IN_KEY',    '-Si(4pL/)4+Ka/!4+9K$7hDx,^8i#B;-}:h{nBvEFAZMq8?8;l96|On<|MmCz%uY');
define('NONCE_KEY',        ']o@s#aw3f|rDP8VS2&[S{<r[~[HE;e)Gz:1}A6^?<Ff*Z9+eX${5K:c ]:75XQ+E');
define('AUTH_SALT',        '[-F+qt%Hl,{X0n25uU|Z8>d1+e-=L?k%f+D2POv|@{{L)yQ2dch)Vy*;x-h[~tgL');
define('SECURE_AUTH_SALT', 'm)R@?8ao|Sj/F(]cg^l_)}^k Ia#mfQ1f|<V@8XK+|Ilk./ry+>x|=~N.YMFk5c%');
define('LOGGED_IN_SALT',   'EgZG-8d`j~&U7U/Rq3~GD2$jTiKFX=AOZ4#QLb8@V2>GA&+v(y [#,P|x0O4XY]p');
define('NONCE_SALT',       '/H||CDj:o}=lUU!BduOD6B$-t<O(/]ox/<vq7/+3(6p_W9,ap,V5KWG|A%bqy w=');

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
