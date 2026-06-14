<?php

declare(strict_types=1);

namespace Locator\Service;

defined('ABSPATH') || exit;

use Locator\Admin\Settings;
use Locator\Contract\HasHooks;
use Locator\Model\Store;
use Locator\Repository\StoreRepository;
use Locator\Util\TemplateLoader;

use const Locator\VERSION;

/**
 * Front-end service: registers the [locator] shortcode and renders an
 * accessible, searchable directory of published store locations.
 *
 * Filtering is performed client-side (no AJAX, no external API): every store
 * carries a lower-cased search haystack on a data attribute, and a small script
 * shows/hides cards as the visitor types. This keeps the free MVP fast, private
 * and fully functional without JavaScript (all stores are rendered server-side).
 */
final class Locator implements HasHooks
{
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
     * Render the [locator] shortcode.
     *
     * Attributes:
     * - layout: "list" | "grid" (overrides the saved default)
     * - search: "1" | "0"       (force-show or hide the search box)
     *
     * @param array<string, mixed>|string $atts
     */
    public function renderShortcode(array|string $atts = []): string
    {
        $settings = $this->settings->all();

        $atts = shortcode_atts(
            [
                'layout' => (string) ($settings['layout'] ?? 'list'),
                'search' => $settings['show_search'] ? '1' : '0',
            ],
            is_array($atts) ? $atts : [],
            'locator',
        );

        $layout = in_array($atts['layout'], ['list', 'grid'], true) ? (string) $atts['layout'] : 'list';
        $showSearch = '1' === (string) $atts['search'];

        $stores = $this->repository->all();

        // Mark assets for enqueue (search interactivity + styling).
        $this->assetsNeeded = true;

        /** @var array<string, bool> $fields */
        $fields = is_array($settings['fields'] ?? null) ? $settings['fields'] : [];

        return $this->templates->render('locator-list', [
            'stores'      => $stores,
            'layout'      => $layout,
            'show_search' => $showSearch,
            'fields'      => $fields,
            'empty_text'  => __('No store locations have been added yet.', 'locator'),
        ]);
    }

    /**
     * Build a Google Maps directions URL for a store, preferring coordinates and
     * falling back to the postal address.
     */
    public static function directionsUrl(Store $store): string
    {
        if (null !== $store->lat && null !== $store->lng) {
            $query = $store->lat . ',' . $store->lng;
        } else {
            $query = trim(implode(', ', array_filter([
                $store->address,
                $store->postcode,
                $store->city,
                $store->country,
            ])));
        }

        if ('' === $query) {
            return '';
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($query);
    }
}
