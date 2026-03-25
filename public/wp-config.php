<?php

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');

$fw_root = str_replace('/public', '', __DIR__);

/** Location of your WordPress configuration. */
require_once("$fw_root/config/wp-config.php");

if ('%ENABLE_CACHE%' === 'true') {
    define('WP_CACHE', true);
    define('WPCACHEHOME', WP_CONTENT_DIR . '/plugins/wp-super-cache/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/** Disable Pingbacks and XMLRPC attacks */
if (!defined('WP_CLI')) {
    // remove x-pingback HTTP header
    add_filter("wp_headers", function ($headers) {
        unset($headers["X-Pingback"]);
        return $headers;
    });
    // disable pingbacks
    add_filter("xmlrpc_methods", function ($methods) {
        unset($methods["pingback.ping"]);
        return $methods;
    });
}
