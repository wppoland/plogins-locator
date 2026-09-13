<?php

declare(strict_types=1);

namespace Locator\Service;

defined('ABSPATH') || exit;

use Locator\Admin\Settings;
use Locator\Contract\HasHooks;
use Locator\Repository\StoreRepository;
use Locator\Util\TemplateLoader;

use const Locator\VERSION;

/**
 * Front-end service: registers the [locator] shortcode and renders an
 * accessible, searchable directory of published store locations.
 *
 * Filtering is performed client-side (no AJAX, no external API): every store
 * carries a lower-cased search haystack on a data attribute, and a small script
 * shows/hides cards as the visitor types. This keeps the directory fast, private
 * and fully functional without JavaScript (every rendered store is in the HTML).
 * The page is bounded, so the template states how many of the total it lists and
 * that the search box only reaches the listed ones.
 */
final class Locator implements HasHooks
{
    /** Stores rendered by [locator] when the shortcode names no limit. */
    private const DEFAULT_LIMIT = 200;

    private bool $assetsNeeded = false;

    public function __construct(
        private readonly StoreRepository $repository,
        private readonly TemplateLoader $templates,
        private readonly Settings $settings,
    ) {
    }

    public function registerHooks(): void
    {
        add_shortcode('locator', [$this, 'renderShortcode']);
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        add_action('wp_footer', [$this, 'enqueueIfNeeded']);
    }

    public function registerAssets(): void
    {
        wp_register_style(
            'locator',
            \LOCATOR_URL . 'assets/css/locator.css',
            [],
            VERSION,
        );

        wp_register_script(
            'locator',
            \LOCATOR_URL . 'assets/js/locator.js',
            [],
            VERSION,
            true,
        );
    }

    /**
     * Enqueue assets only when the shortcode actually rendered on the page.
     */
    public function enqueueIfNeeded(): void
    {
        if (! $this->assetsNeeded) {
            return;
        }

        wp_enqueue_style('locator');
        wp_enqueue_script('locator');
    }

    /**
     * @param list<array<string, mixed>> $raw
     * @return list<array{label: string, stores: list<\Locator\Model\Store>}>
     */
    private function normalizeStoreGroups(array $raw): array
    {
        $groups = [];

        foreach ($raw as $group) {
            if (! is_array($group)) {
                continue;
            }

            $stores = $group['stores'] ?? [];
            if (! is_array($stores) || $stores === []) {
                continue;
            }

            $resolved = [];
            foreach ($stores as $store) {
                if ($store instanceof \Locator\Model\Store) {
                    $resolved[] = $store;
                }
            }

            if ($resolved === []) {
                continue;
            }

            $groups[] = [
                'label'  => isset($group['label']) ? (string) $group['label'] : '',
                'stores' => $resolved,
            ];
        }

        return $groups;
    }

    /**
     * Render the [locator] shortcode.
     *
     * @param array<string, mixed>|string $atts
     */
    public function renderShortcode(array|string $atts = []): string
    {
        $settings = $this->settings->all();

        $atts = shortcode_atts(
            ['limit' => ''],
            is_array($atts) ? $atts : [],
            'locator',
        );

        /**
         * Filter how many stores [locator] renders when the shortcode names no
         * limit. The directory is rendered server-side and filtered in the
         * browser, so every store on the page is a row read, a meta cache entry
         * and a card in the HTML. A shop with a handful of stores never reaches
         * this; one importing a franchise list would otherwise print all of
         * them into one public page.
         *
         * `[locator limit="-1"]` still renders every store.
         *
         * @param int $limit Default number of stores rendered.
         */
        $default = (int) apply_filters('locator/default_limit', self::DEFAULT_LIMIT);
        $limit   = '' === trim((string) $atts['limit']) ? $default : (int) $atts['limit'];

        // `limit="0"` is a typo, not a request for an empty directory, and
        // WP_Query reads 0 as "use the site default" anyway.
        if (0 === $limit) {
            $limit = $default;
        }

        $stores = $this->repository->all($limit);

        // The page prints a bounded set, but the shopper is told how many
        // locations exist, not how many fitted: a shop with 700 stores used to
        // announce "200 locations". Only a page filled to its limit can be
        // truncated, so the counting query runs only in that case.
        $listed = count($stores);
        $total  = $listed;

        if ($limit > 0 && $listed === $limit) {
            $total = max($listed, $this->repository->count());
        }

        // Mark assets for enqueue (search interactivity + styling).
        $this->assetsNeeded = true;

        /** @var array<string, bool> $fields */
        $fields = is_array($settings['fields'] ?? null) ? $settings['fields'] : [];

        /** @var list<array{label: string, stores: list<\Locator\Model\Store>}>|null $storeGroups */
        $storeGroups = apply_filters('locator/store_groups', null, $stores);

        if (! is_array($storeGroups) || $storeGroups === []) {
            $storeGroups = null;
        } else {
            $storeGroups = $this->normalizeStoreGroups($storeGroups);
            if ($storeGroups === []) {
                $storeGroups = null;
            }
        }

        return $this->templates->render('locator-list', [
            'stores'       => $stores,
            'total'        => $total,
            'store_groups' => $storeGroups,
            'show_search'  => ! empty($settings['show_search']),
            'fields'       => $fields,
            'empty_text'   => __('No store locations have been added yet.', 'plogins-locator'),
        ]);
    }
}
