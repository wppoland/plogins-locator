<?php
/**
 * Script dependencies for editor.js, read by register_block_type().
 *
 * editor.js is hand-written, so no build step generates this file. Without it
 * WordPress registers the script with no dependencies, prints it before
 * wp-blocks, and the block never registers in the editor.
 *
 * @package Locator
 */

defined('ABSPATH') || exit;

return [
    'dependencies' => ['wp-blocks', 'wp-block-editor', 'wp-server-side-render', 'wp-element'],
    'version'      => \Locator\VERSION,
];
