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
define('DB_NAME', 'chris_site');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Cl0setW1ne');

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
define('AUTH_KEY',         'Mmc,2SeNK;%2pk+FiqiCAO5sO8fpw3r,!2|UZN;{sgCIP7Q0sU/q` 2FH4VGpt` ');
define('SECURE_AUTH_KEY',  '&jfJj6b.al?EC!^n%.[S-wlqJHZKdRMx`b+}vUfr}a1:nZ8JC|C#&<QN0LyV)UAD');
define('LOGGED_IN_KEY',    'co{WXA0 #6-)0KG?}/P)5JQd{1Ft(@?zJ{~[UQrh^[0R &bi.JVodSO:MR^38+L.');
define('NONCE_KEY',        'y*yPrz&0SYcg7C>JIE5;|?-9_#`{3_b9gD?:OpsC=GrDoG+Ld`,&B<3I@=6wVQbI');
define('AUTH_SALT',        '%?(MmdH{_5ljq^pO[Mwc)H=NNxBs-65p/,}#R2#ynDC`En?mBFg#JcGiPM1Z}b}8');
define('SECURE_AUTH_SALT', 'y(uPzliU9S%N62;>xBsoYF_oe+S0g8,5&S6./qtR?P-D`zfwo!sRdJYeL6mm+Ik3');
define('LOGGED_IN_SALT',   'FYpd_q2`ikQ5)z;h>IuKOfVx7d%JB h!ws]GgZM}zeAp#ds,24QE76:ioxg[j6J*');
define('NONCE_SALT',       'LCkJkg8 c-To;A5#BV<C<>q8=,Rvf6l61+D`#F-L!%:=,KSi@p3:w}?j3EQ)(|j{');

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



/*FUTURE CHRIS, IF THERE IS A PROBLEM WITH UPDATING THEN IT 
PROBABLY HAS SOMETHING TO DO WITH THIS */

/* Force direct file updating
- http://www.charleshooper.net/blog/wordpress-auto-upgrade-and-dumb-permissions/
*/
define('FS_METHOD', 'direct');


