<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Locator
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
    if (! defined('WP_UNINSTALL_PLUGIN')) {
        define('WP_UNINSTALL_PLUGIN', true);
    }
    if (! defined('LOCATOR_URL')) {
        define('LOCATOR_URL', 'http://example.test/wp-content/plugins/locator/');
    }
    if (! defined('LOCATOR_DIR')) {
        define('LOCATOR_DIR', '/tmp/locator/');
    }
}

namespace Locator {
    if (! defined('Locator\\VERSION')) {
        define('Locator\\VERSION', '0.1.0');
    }
    if (! defined('Locator\\PLUGIN_FILE')) {
        define('Locator\\PLUGIN_FILE', '/tmp/locator/locator.php');
    }
    if (! defined('Locator\\PLUGIN_DIR')) {
        define('Locator\\PLUGIN_DIR', '/tmp/locator');
    }
}
