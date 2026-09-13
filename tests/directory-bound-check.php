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
 * A cap the shopper is not told about is its own defect. The count above the
 * list printed count($stores), so a shop with 700 locations announced "200
 * locations" into an ARIA live region, and the client-side search, which only
 * ever sees the rendered cards, returned nothing for location 500 without
 * saying why.
 *
 * This harness runs the real shortcode against a WP_Query stub: it reads back
 * the posts_per_page the directory asked for, and then reads the rendered HTML
 * for the total, the truncation notice and the search caveat.
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

    /** @var int $locator_test_published How many published stores the stub database holds. */
    $locator_test_published = 0;

    // phpcs:disable
    class WP_Post
    {
        public string $post_content = '';

        public function __construct(public int $ID)
        {
        }
    }

    class WP_Query
    {
        /** @var array<int, mixed> */
        public array $posts = [];

        public int $found_posts = 0;

        /** @param array<string, mixed> $args */
        public function __construct(public array $args = [])
        {
            global $locator_test_queries, $locator_test_published;
            $locator_test_queries[] = $args;

            $this->found_posts = $locator_test_published;

            if ('ids' === ($args['fields'] ?? '')) {
                // The counting query: only found_posts is read from it.
                return;
            }

            $limit = (int) ($args['posts_per_page'] ?? -1);
            $rows  = $limit < 0 ? $locator_test_published : min($limit, $locator_test_published);

            for ($i = 1; $i <= $rows; $i++) {
                $this->posts[] = new WP_Post($i);
            }
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
    function get_the_title($post): string { return 'Store ' . (is_object($post) ? $post->ID : (int) $post); }
    function get_post_meta(int $postId, string $key, bool $single = false): string { return ''; }
    function get_the_post_thumbnail_url($post = null, $size = 'post-thumbnail'): string { return ''; }
    function esc_html($text) { return (string) $text; }
    function esc_attr($text) { return (string) $text; }
    function esc_url($url) { return (string) $url; }
    function wp_kses_post($text) { return (string) $text; }
    function wpautop(string $text): string { return $text; }
    function is_email($email) { return false; }
    function esc_html_e(string $text, string $domain = ''): void { echo $text; }
    function esc_attr_e(string $text, string $domain = ''): void { echo $text; }
    function __(string $text, string $domain = ''): string { return $text; }
    function esc_html__(string $text, string $domain = ''): string { return $text; }
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

    // ---- What the page tells the shopper about the cap ----------------------

    /**
     * Render the shortcode and return the HTML, plus how many counting queries
     * it took to produce it.
     *
     * @param array<string, mixed>|string $atts
     * @return array{html: string, counts: int}
     */
    function locator_test_render(\Locator\Service\Locator $service, int $published, array|string $atts = []): array
    {
        global $locator_test_queries, $locator_test_published;

        $locator_test_queries   = [];
        $locator_test_published = $published;

        $html = $service->renderShortcode($atts);

        $counts = 0;
        foreach ($locator_test_queries as $args) {
            if ('ids' === ($args['fields'] ?? '')) {
                $counts++;
            }
        }

        $locator_test_published = 0;

        return ['html' => $html, 'counts' => $counts];
    }

    /** @param array{html: string, counts: int} $rendered */
    function locator_test_says(array $rendered, string $needle): bool
    {
        return str_contains($rendered['html'], $needle);
    }

    /**
     * The text of the live region above the list, which is the sentence a
     * screen reader reads out as the number of locations.
     *
     * @param array{html: string, counts: int} $rendered
     */
    function locator_test_count_text(array $rendered): string
    {
        if (1 !== preg_match('#<p[^>]*data-locator-count[^>]*>(.*?)</p>#s', $rendered['html'], $m)) {
            return 'no count element';
        }

        return trim(preg_replace('/\s+/', ' ', $m[1]) ?? '');
    }

    $big = locator_test_render($service, 700);

    $expectedInBig = [
        'the cap is on the page, not only in the readme' => 'This page lists the first 200 of 700 locations.',
        'the shopper is told what search reaches' => 'The search box only looks through the locations listed here.',
        'an empty search result says why' => 'This search covered only the 200 locations listed here, out of 700.',
        'the live region stays readable while filtering' => 'data-locator-filtered-label="%d of 200 listed locations match"',
    ];

    foreach ($expectedInBig as $label => $needle) {
        if (! locator_test_says($big, $needle)) {
            $failures[] = sprintf('700 stores, %s: page never says %s', $label, var_export($needle, true));
        }
    }

    // The live region is the sentence that is read out, so it is asserted whole:
    // "200 locations" there is the number that fitted, not the number there are.
    $bigCount = locator_test_count_text($big);
    if ('Showing 200 of 700 locations' !== $bigCount) {
        $failures[] = sprintf('700 stores: the live region reads %s, expected "Showing 200 of 700 locations"', var_export($bigCount, true));
    }

    if (1 !== $big['counts']) {
        $failures[] = sprintf('700 stores: expected exactly 1 counting query, ran %d', $big['counts']);
    }

    // A shop that fits on the page pays nothing for the total and is told
    // nothing about a cap it never met.
    $small = locator_test_render($service, 12);

    $smallCount = locator_test_count_text($small);
    if ('12 locations' !== $smallCount) {
        $failures[] = sprintf('12 stores: the live region reads %s, expected "12 locations"', var_export($smallCount, true));
    }

    if (locator_test_says($small, 'This page lists the first')) {
        $failures[] = '12 stores: a truncation notice on a page that lists every store';
    }

    if (locator_test_says($small, 'data-locator-filtered-label')) {
        $failures[] = '12 stores: the filtered-count label belongs to a truncated page only';
    }

    if (0 !== $small['counts']) {
        $failures[] = sprintf('12 stores: a page that cannot be truncated ran %d counting queries', $small['counts']);
    }

    // Exactly at the cap: the count query runs (the page cannot know it is the
    // last one), and nothing is truncated.
    $exact = locator_test_render($service, 200);

    if ('200 locations' !== locator_test_count_text($exact) || locator_test_says($exact, 'This page lists the first')) {
        $failures[] = '200 stores at a 200 cap: nothing is missing, so the live region must read "200 locations" with no notice';
    }

    // limit="-1" renders every store, so there is nothing to warn about.
    $all = locator_test_render($service, 700, ['limit' => '-1']);

    if ('700 locations' !== locator_test_count_text($all) || locator_test_says($all, 'This page lists the first')) {
        $failures[] = 'limit="-1" with 700 stores: every store is listed, so the live region must read "700 locations" with no notice';
    }

    if ([] !== $failures) {
        fwrite(STDERR, "directory-bound-check: FAIL\n");
        foreach ($failures as $failure) {
            fwrite(STDERR, '  ' . $failure . "\n");
        }
        exit(1);
    }

    echo 'directory-bound-check: OK (' . (count($cases) + 1) . ' limit cases, '
        . (count($expectedInBig) + 8)
        . " statement cases: the directory asks for a bounded set and says so)\n";
}
