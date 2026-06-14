<?php

/**
 * Locator uninstall routine.
 *
 * Removes plugin options when the user deletes the plugin. Store locations are
 * left intact (they are user content in a custom post type); the merchant can
 * delete them manually before removing the plugin if desired.
 *
 * @package Locator
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

delete_option('locator_settings');
delete_option('locator_db_version');
