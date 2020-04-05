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
define( 'DB_NAME', 'elisabettapinciaroli' );

/** MySQL database username */
define( 'DB_USER', 'fedpindreamhoste' );

/** MySQL database password */
define( 'DB_PASSWORD', '2evJvprR' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql.fedpin.dreamhosters.com' );

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
define( 'AUTH_KEY',         'j;jBB~# abH^KOBe7xaFZdxs<.[wjT]Olv^QS63XaYDlkUoYU:xPp?+3#<GO9nIC' );
define( 'SECURE_AUTH_KEY',  '4@J!DT:3&Q[OoM]U+Zul2+fyqM!:c;}r`IN3%/v0Q`za>SYaC<PhM`1O>+nVO:a$' );
define( 'LOGGED_IN_KEY',    'efr9BRUHq^T#GMihr@AR805Ro+w~sP;~Ab?O-MFll6gSQ1:>26?F`SxAwg))Soa;' );
define( 'NONCE_KEY',        '1xP?aI0@ZAm.xLE&5 u&djcR^WmRO:7isG0+Wot93EoFqL.Z elAYFl#.YW>EuOY' );
define( 'AUTH_SALT',        'keg&#mg}ei@t!C(STbM1_E~mn8r;W,n18FNdGV74<VG@9lz db[f{XMRSA0lT;ze' );
define( 'SECURE_AUTH_SALT', 'c6#XfeyGfA;!2QsR<1>dEqsLV.ACV7A^h%k)2up?Dtu>Z<R<.-.;6oQE3]>) sak' );
define( 'LOGGED_IN_SALT',   '57rG`L[VCs7YY|vwN5f&z|eWt^Clk^F5seTQD{f?VaJ~wC%ZF6u#(tYDp?#W`++X' );
define( 'NONCE_SALT',       '9>NJm@km_Y`2L_NZZeb.qu%98`mq}RSZ)&@Eqm6-,}#o3V4==wI(NOIbNV7!A_J+' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
