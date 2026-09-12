<?php

/**
 * The [locator] directory must ask the database for a bounded number of stores.
 *
 * The shortcode called $repository->all() with no argument, and all() defaults
 * to -1, so one public page built every published store into itself: a row, a
 * meta cache entry, a hydrated value object and a card each. The shortcode also
 * dropped its own attributes on the floor, so a merchant who could see the
 * problem had no way to ask for fewer.
 *
 * This harness runs the real shortcode against a WP_Query stub and reads back
 * the posts_per_page it asked for.
 *
 * Run: php tests/directory-bound-check.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    /** @var array<int, array<string, mixed>> $locator_test_queries */
    $locator_test_queries = [];

    /** @var int|null $locator_test_filtered_limit Value the default_limit filter returns. */
    $locator_test_filtered_limit = null;

    // phpcs:disable
    class WP_Query
    {
        /** @var array<int, mixed> */
        public array $posts = [];

        public int $found_posts = 0;

        /** @param array<string, mixed> $args */
        public function __construct(public array $args = [])
        {
            global $locator_test_queries;
            $locator_test_queries[] = $args;
        }
    }

    function shortcode_atts(array $pairs, $atts, string $shortcode = ''): array
    {
        $atts = is_array($atts) ? $atts : [];
        $out  = [];
        foreach ($pairs as $name => $default) {
            $out[$name] = array_key_exists($name, $atts) ? $atts[$name] : $default;
        }
        return $out;
    }

    function apply_filters(string $hook, $value, ...$args)
    {
        global $locator_test_filtered_limit;

        if ('locator/default_limit' === $hook && null !== $locator_test_filtered_limit) {
            return $locator_test_filtered_limit;
        }

        return $value;
    }

    function get_option(string $name, $default = false) { return $default; }
    function update_post_thumbnail_cache($query = null): void {}
    function wp_unique_id(string $prefix = ''): string { static $i = 0; return $prefix . ++$i; }
    function esc_html($text) { return (string) $text; }
    function esc_attr($text) { return (string) $text; }
    function esc_html_e(string $text, string $domain = ''): void { echo $text; }
    function esc_attr_e(string $text, string $domain = ''): void { echo $text; }
    function __(string $text, string $domain = ''): string { return $text; }
    function _n(string $single, string $plural, int $number, string $domain = ''): string
    {
        return 1 === $number ? $single : $plural;
    }
    // phpcs:enable
}

namespace Locator {
    const PLUGIN_DIR = __DIR__ . '/..';
    const VERSION    = 'test';
}

namespace {
    require __DIR__ . '/../src/Contract/HasHooks.php';
    require __DIR__ . '/../src/Model/Store.php';
    require __DIR__ . '/../src/PostType/StoreLocation.php';
    require __DIR__ . '/../src/Repository/StoreRepository.php';
    require __DIR__ . '/../src/Util/TemplateLoader.php';
    require __DIR__ . '/../src/Admin/Settings.php';
    require __DIR__ . '/../src/Service/Locator.php';

    $service = new \Locator\Service\Locator(
        new \Locator\Repository\StoreRepository(),
        new \Locator\Util\TemplateLoader(),
        new \Locator\Admin\Settings(),
    );

    /**
     * Render the shortcode and report the posts_per_page it asked the store
     * query for.
     *
     * @param array<string, mixed>|string $atts
     * @return int|string
     */
    function locator_test_limit_for(\Locator\Service\Locator $service, array|string $atts)
    {
        global $locator_test_queries;
        $locator_test_queries = [];

        $service->renderShortcode($atts);

        foreach ($locator_test_queries as $args) {
            if (\Locator\PostType\StoreLocation::POST_TYPE === ($args['post_type'] ?? '')) {
                return $args['posts_per_page'] ?? 'no posts_per_page';
            }
        }

        return 'no store query';
    }

    $failures = [];

    $cases = [
        'no attributes stays bounded'   => [[], 200],
        'limit attribute is honoured'   => [['limit' => '25'], 25],
        'limit="-1" still renders all'  => [['limit' => '-1'], -1],
        'limit="0" is a typo, not none' => [['limit' => '0'], 200],
        'empty shortcode (string atts)' => ['', 200],
    ];

    foreach ($cases as $label => [$atts, $expected]) {
        $actual = locator_test_limit_for($service, $atts);
        if ($actual !== $expected) {
            $failures[] = sprintf('%s: asked for %s, expected %s', $label, var_export($actual, true), var_export($expected, true));
        }
    }

    // The filter is the documented way to move the default without an option.
    $locator_test_filtered_limit = 50;
    $actual = locator_test_limit_for($service, []);
    if (50 !== $actual) {
        $failures[] = sprintf('locator/default_limit filter: asked for %s, expected 50', var_export($actual, true));
    }
    $locator_test_filtered_limit = null;

    if ([] !== $failures) {
        fwrite(STDERR, "directory-bound-check: FAIL\n");
        foreach ($failures as $failure) {
            fwrite(STDERR, '  ' . $failure . "\n");
        }
        exit(1);
    }

    echo 'directory-bound-check: OK (' . (count($cases) + 1) . " cases, the directory asks for a bounded set)\n";
}
