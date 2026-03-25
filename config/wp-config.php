<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bitnami_wordpress');

/** Database username */
define('DB_USER', 'bn_wordpress');

/** Database password */
define('DB_PASSWORD', '%DATABASE_PASSWORD%');

/** Database hostname */
define('DB_HOST', '127.0.0.1:3306');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'y^1ho|u#Mai206tl6q-~$8]=v*QH93|z4egh;R&=FL@jEr6{e0U)k`dxdrs6Co`E');
define('SECURE_AUTH_KEY',  'Mc)_YH~m:[/}}5Kvq|?P+Vn?tr~-|[&mg$C6).=[v#;Msk>X>g9WR~z)Qk.EP4-o');
define('LOGGED_IN_KEY',    '?g<]V<4FAp8EJ/,XDlzF_,vA#c.CVv.K|FrQA4xt`uW8LVov<eT*}Lx6[E^Lb>Ti');
define('NONCE_KEY',        'E&_D+xHWt)G#jEusG`D-%e.ewG|YfQF~Wg}QIUF/|+)[A[/=)~8)3sjE)Ri@taFz');
define('AUTH_SALT',        'Cc84C1^C+Pcyho]&gZ[sjEqv:`Go+[b]|!v.R?4NY]xSAwd09%+u5fvnSY4sDD-.');
define('SECURE_AUTH_SALT', '/;GAE99!,w!vHUQ}YNe8dsQ-4liE@-xy/=XB:pRB-_j=sewR6DxFYeOA`|ME&-&-');
define('LOGGED_IN_SALT',   '4>^pnxlw8tM} PP;_R-Iw^I <KD&j7&2*1gJj]RhNL`$xcIs7LB?! _Oz~%Uk1Z^');
define('NONCE_SALT',       '>sNt0I(m/ k8-`F2nq#S]A+-..m<fdRcAPJ`e=w(?|SrKt|KmW5K65s?~ >0t 6u');

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);
define('ENABLE_CACHE', true);

/* Add any custom values between this line and the "stop editing" line. */

$domain = "%DOMAIN%";
$fw_root = str_replace('/config', '', __DIR__);

define('WP_SITEURL', "https://{$domain}/wp");
define('WP_HOME', "https://{$domain}");
define('WP_CONTENT_DIR', "$fw_root/public/app");
define('WP_CONTENT_URL', 'https://' . $domain . '/app');

define('S3_UPLOADS_BUCKET', "faithworksuploads/$domain");
define('S3_UPLOADS_REGION', 'us-east-2');
define('S3_UPLOADS_KEY', '%AWS_KEY%');
define('S3_UPLOADS_SECRET', '%AWS_SECRET%');
// define('S3_UPLOADS_DISABLE_REPLACE_UPLOAD_URL', true);
define('AWS_ACCESS_KEY_ID', '%AWS_KEY%');
define('AWS_SECRET_ACCESS_KEY', '%AWS_SECRET%');

define('SMTP_USER', AWS_ACCESS_KEY_ID);
define('SMTP_PASS', '%SES_PASSWORD%');
define('SMTP_HOST', 'email-smtp.us-east-2.amazonaws.com');
define('SMTP_EMAIL', '%ADMIN_EMAIL%');
define('SMTP_PORT', 587);
define('SMTP_DEBUG', 0);

define('WP_AUTO_UPDATE_CORE', false);

require_once __DIR__ . '/../vendor/autoload.php';

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', "$fw_root/public/wp");
}
