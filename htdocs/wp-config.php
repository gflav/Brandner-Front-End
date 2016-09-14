<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

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

if(file_exists(__DIR__.'/wp-config-tbo.php')) {
  require_once(__DIR__.'/wp-config-tbo.php');
} else {
  // production
  if($_SERVER['HTTP_HOST'] == 'dev.brandnerdesign.com') {
    define('DB_NAME', 'brandner_dev');
    define('DB_USER', 'brandner_dev');
    define('DB_PASSWORD', 'as!SDfkl234jlk23erl,smflkajdsf');
    define('DB_HOST', 'localhost');
  }
}

define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('DISALLOW_FILE_EDIT', TRUE); // Sucuri Security: Tue, 31 May 2016 16:04:58 +0000

define('DISABLE_WP_CRON', 'true'); // tbo because we'll call this directly


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XZDk?2Mzf7Vs}aB!k|9QRo7wK_NGcrI&8,@tsKhcE.!g0Y}v@uZ-NMNT:8{5i7y`');
define('SECURE_AUTH_KEY',  '_3~@kpn;d9&ORIC)bXxgT)L.DW>ra/7Tit^s?2Z[3FYD-!NjEU/Sr`THVj.s%vDY');
define('LOGGED_IN_KEY',    'j$E3/7K=1O%G|+VBZVd~!_dg=tt1tjr Q*Q83#L S`Y`g%?}aL@c`~@k~g2o){kL');
define('NONCE_KEY',        '?qZ)QFCI[&k8;Az+=E{I,#od~E%}<Wt/w/i-lfn@3+/o}R$&LjVr!Av<6eX4&pY-');
define('AUTH_SALT',        '-&$S;lyH4mY-/}n$A-tncI21bg|=~*^<z!MO,Pl_<b*-S|qe~NkCC~53 6m%F/:!');
define('SECURE_AUTH_SALT', 'n_B5` kO*VFqnt%$#ctw{KH2k9:cEO3fnPI])&rEqF5ECaKfQE`zH9R{5BRb[&xr');
define('LOGGED_IN_SALT',   '>$`^}l:lL~XgpW^52QeS}*]!/^bm?sg*?}M(hY?~<1B|X+UeC ~+QdG</o[7/lfK');
define('NONCE_SALT',       '<m17,%f!%aZFM:Gbj-&1-GH}ql$,eUFq?pDd(!e<E`i*SyE(7R@rEUt*|&65l@|H');

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
