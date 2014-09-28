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
define('DB_NAME', 'wordpress1');

/** MySQL database username */
define('DB_USER', 'wordpressuser225');

/** MySQL database password */
define('DB_PASSWORD', 'q-s|qfRUHQ^%');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Type. Defaults to mysql */
define('DB_TYPE', 'mysql');

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
define('AUTH_KEY',         'n1!h!WtiMUbR5o*S}+DDdUx<2GyXTL-bHH8-_%+<J y:4*B2w8ssY/5Zy!}(<2)A');
define('SECURE_AUTH_KEY',  'g|I*kPu}]8=Uc&8]Nht9Tm#`>E?VOm#u:^x|q2A~uKr; 5+d[Md|1x|CYi$ROxaR');
define('LOGGED_IN_KEY',    'ti5h.JoY)H>by28UiopWV)WRld5G3E5-J)|f9a:mVY-mNBt=6WUFAex4aED3xZm;');
define('NONCE_KEY',        'k!j4[dvs7Fk#KE=Uq7q-na6B|FAx-X(>1*2-EJ+<br,AXJ@=@$9+!!dEb7D(o -j');
define('AUTH_SALT',        '}dnvUw1QoyWAC?+;l&u8l(&2aH`BAv?jl;A[;2VlNk-I-n&u+/ld@32O,9ln#n_Q');
define('SECURE_AUTH_SALT', 'b!dyyCc{[:hr[bTF&mPC5-Zh`r FE)^BC16cY(JFWUnkB}EK x<~)mqMwjk:!X*3');
define('LOGGED_IN_SALT',   'AG{y&n,:bB}ry5q8/Tfcsl9k?uB-53Twg%o+~b6JdK2!h<EL~<ly&^CeECQf>Xd3');
define('NONCE_SALT',       '?4msqn<TL)T]+b;gfuyL-+~aFV4zknA` eiYlpkV v~(X|(BbKk6sSmV<vs&V(|@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';


/** Query Logging Settings */
define('SAVEQUERIES', FALSE);
define('QUERY_LOG', 'C:\Users\Pc\Documents\My Web Sites\Brandoo WordPress (MS SQL or Azure SQL)/wp-content\queries.log');

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
