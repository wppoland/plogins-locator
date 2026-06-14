<?php

declare(strict_types=1);

namespace Locator\Util;

defined('ABSPATH') || exit;

use const Locator\PLUGIN_DIR;

/**
 * Loads templates with theme override support.
 *
 * Templates are looked up in this order:
 * 1. {theme}/locator/{template}.php
 * 2. {plugin}/templates/{template}.php
 */
final class TemplateLoader
{
    private const THEME_DIR = 'locator';

    /**
     * Render a template and return the HTML.
     *
     * @param string               $template Template name (e.g. 'locator-list').
     * @param array<string, mixed> $args     Variables to extract into the template scope.
     */
    public function render(string $template, array $args = []): string
    {
        ob_start();
        $this->include($template, $args);

        return (string) ob_get_clean();
    }

    /**
     * Include a template directly (outputs to buffer).
     *
     * @param string               $template Template name.
     * @param array<string, mixed> $args     Variables to extract into the template scope.
     */
    public function include(string $template, array $args = []): void
    {
        $path = $this->locate($template);

        if (null === $path) {
            return;
        }

        /**
         * Filter template arguments before rendering.
         *
         * @param array<string, mixed> $args     Template arguments.
         * @param string               $template Template name.
         */
        $args = apply_filters('locator/template/args', $args, $template);

        // Prefix every template variable with `locator_` to keep templates within
        // the plugin's variable namespace (per WordPress.org coding standards).
        $locator_args = [];
        foreach ($args as $locator_args_key => $locator_args_value) {
            if (! is_string($locator_args_key) || '' === $locator_args_key) {
                continue;
            }
            $locator_key = str_starts_with($locator_args_key, 'locator_') ? $locator_args_key : 'locator_' . $locator_args_key;
            $locator_args[$locator_key] = $locator_args_value;
        }

        unset($args, $locator_args_key, $locator_args_value, $locator_key);

        extract($locator_args, EXTR_SKIP); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

        include $path;
    }

    /**
     * Locate a template file. Returns null if not found.
     */
    public function locate(string $template): ?string
    {
        $template = ltrim($template, '/');

        if (! str_ends_with($template, '.php')) {
            $template .= '.php';
        }

        $themePath = locate_template(self::THEME_DIR . '/' . $template);

        if ('' !== $themePath) {
            /** @var string */
            return apply_filters('locator/template/path', $themePath, $template);
        }

        $pluginPath = PLUGIN_DIR . '/templates/' . $template;

        if (file_exists($pluginPath)) {
            /** @var string */
            return apply_filters('locator/template/path', $pluginPath, $template);
        }

        return null;
    }
}
