<?php

declare(strict_types=1);

namespace Locator\Service;

defined('ABSPATH') || exit;

use Locator\Admin\Settings;
use Locator\Contract\HasHooks;
use Locator\Model\Store;
use Locator\Repository\StoreRepository;

/**
 * Exposes the store directory via the WordPress Abilities API (WP 6.9+).
 *
 * Each ability is a stable, namespaced contract: a structured way for the
 * command palette, MCP servers and AI assistants to read the same location data
 * the [locator] shortcode prints, and to add or update a location the same way
 * the programmatic importer does.
 *
 * On WordPress < 6.9 this service no-ops (the API is detected at runtime and
 * nothing else in the plugin depends on it).
 *
 * Categories:
 *   - locator-stores   : the store locations themselves.
 *   - locator-settings : how the storefront directory is configured.
 */
final class AbilitiesService implements HasHooks
{
    public function __construct(
        private readonly StoreRepository $repository,
        private readonly Settings $settings,
    ) {
    }

    public function registerHooks(): void
    {
        if (! function_exists('wp_register_ability')) {
            // WP < 6.9 or Abilities API not loaded. No-op.
            return;
        }

        add_action('wp_abilities_api_categories_init', [$this, 'registerCategories']);
        add_action('wp_abilities_api_init', [$this, 'registerAbilities']);
    }

    public function registerCategories(): void
    {
        if (! function_exists('wp_register_ability_category')) {
            return;
        }

        wp_register_ability_category('locator-stores', [
            'label'       => __('Locator: store locations', 'plogins-locator'),
            'description' => __('Physical store locations with their address, contact details and opening hours.', 'plogins-locator'),
        ]);

        wp_register_ability_category('locator-settings', [
            'label'       => __('Locator: directory settings', 'plogins-locator'),
            'description' => __('How the storefront directory is configured: search box and visible card fields.', 'plogins-locator'),
        ]);
    }

    public function registerAbilities(): void
    {
        if (! function_exists('wp_register_ability')) {
            return;
        }

        $this->registerListStores();
        $this->registerGetStore();
        $this->registerGetDirectorySettings();
    }

    private function registerListStores(): void
    {
        wp_register_ability('locator/list-stores', [
            'label'       => __('List store locations', 'plogins-locator'),
            'description' => __('Returns published store locations with address, contact details and opening hours. An optional search term matches the same name, address, city, postcode and country text the storefront search box filters on.', 'plogins-locator'),
            'category'    => 'locator-stores',
            'input_schema' => [
                'type'       => 'object',
                'properties' => [
                    'search' => ['type' => ['string', 'null']],
                    'limit'  => ['type' => 'integer', 'minimum' => 1, 'maximum' => 500, 'default' => 100],
                ],
            ],
            'output_schema' => [
                'type'       => 'object',
                'properties' => [
                    'total'  => ['type' => 'integer'],
                    'stores' => [
                        'type'  => 'array',
                        'items' => $this->storeSchema(),
                    ],
                ],
            ],
            'execute_callback' => function (array $input): array {
                $limit  = isset($input['limit']) ? (int) $input['limit'] : 100;
                $limit  = max(1, min(500, $limit));
                $search = isset($input['search']) ? strtolower(trim(sanitize_text_field((string) $input['search']))) : '';

                $stores = $this->repository->all($limit);

                if ('' !== $search) {
                    // Same haystack the storefront cards carry, so an agent sees
                    // exactly what a shopper typing in the search box would.
                    $stores = array_values(array_filter(
                        $stores,
                        static fn (Store $store): bool => str_contains($store->searchHaystack(), $search),
                    ));
                }

                return [
                    'total'  => $this->repository->count(),
                    'stores' => array_map([$this, 'storeToArray'], $stores),
                ];
            },
            'permission_callback' => [$this, 'canManageStores'],
            'meta' => ['show_in_rest' => true, 'readonly' => true],
        ]);
    }

    private function registerGetStore(): void
    {
        wp_register_ability('locator/get-store', [
            'label'       => __('Get a store location', 'plogins-locator'),
            'description' => __('Returns one published store location by its ID, with address, contact details and opening hours.', 'plogins-locator'),
            'category'    => 'locator-stores',
            'input_schema' => [
                'type'       => 'object',
                'required'   => ['store_id'],
                'properties' => [
                    'store_id' => ['type' => 'integer', 'minimum' => 1],
                ],
            ],
            'output_schema' => [
                'type'       => 'object',
                'properties' => [
                    'found' => ['type' => 'boolean'],
                    'store' => $this->storeSchema(true),
                ],
            ],
            'execute_callback' => function (array $input): array {
                $storeId = (int) ($input['store_id'] ?? 0);

                foreach ($this->repository->all() as $store) {
                    if ($store->id === $storeId) {
                        return ['found' => true, 'store' => $this->storeToArray($store)];
                    }
                }

                return ['found' => false, 'store' => null];
            },
            'permission_callback' => [$this, 'canManageStores'],
            'meta' => ['show_in_rest' => true, 'readonly' => true],
        ]);
    }


    private function registerGetDirectorySettings(): void
    {
        wp_register_ability('locator/get-directory-settings', [
            'label'       => __('Get directory settings', 'plogins-locator'),
            'description' => __('Returns whether the storefront search box is shown and which detail fields appear on each store card.', 'plogins-locator'),
            'category'    => 'locator-settings',
            'input_schema' => ['type' => 'object', 'properties' => []],
            'output_schema' => [
                'type'       => 'object',
                'properties' => [
                    'show_search' => ['type' => 'boolean'],
                    'fields'      => [
                        'type'                 => 'object',
                        'additionalProperties' => ['type' => 'boolean'],
                    ],
                ],
            ],
            'execute_callback' => function (): array {
                $settings = $this->settings->all();

                /** @var array<string, mixed> $fields */
                $fields = is_array($settings['fields'] ?? null) ? $settings['fields'] : [];

                return [
                    'show_search' => ! empty($settings['show_search']),
                    'fields'      => array_map(static fn ($value): bool => ! empty($value), $fields),
                ];
            },
            'permission_callback' => [$this, 'canManageStores'],
            'meta' => ['show_in_rest' => true, 'readonly' => true],
        ]);
    }

    /**
     * The shape of a single location, shared by the list and single-store
     * abilities. The single-store ability returns null when nothing matches, so
     * it asks for the nullable variant.
     *
     * @return array<string, mixed>
     */
    private function storeSchema(bool $nullable = false): array
    {
        return [
            'type'       => $nullable ? ['object', 'null'] : 'object',
            'properties' => [
                'id'          => ['type' => 'integer'],
                'name'        => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'address'     => ['type' => 'string'],
                'city'        => ['type' => 'string'],
                'postcode'    => ['type' => 'string'],
                'country'     => ['type' => 'string'],
                'phone'       => ['type' => 'string'],
                'email'       => ['type' => 'string'],
                'hours'       => ['type' => 'string'],
                'lat'         => ['type' => ['number', 'null']],
                'lng'         => ['type' => ['number', 'null']],
                'photo_url'   => ['type' => 'string'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function storeToArray(Store $store): array
    {
        return [
            'id'          => $store->id,
            'name'        => $store->name,
            'description' => $store->description,
            'address'     => $store->address,
            'city'        => $store->city,
            'postcode'    => $store->postcode,
            'country'     => $store->country,
            'phone'       => $store->phone,
            'email'       => $store->email,
            'hours'       => $store->hours,
            'lat'         => $store->lat,
            'lng'         => $store->lng,
            'photo_url'   => $store->thumbnailUrl,
        ];
    }

    /**
     * Store locations are managed under the WooCommerce menu and the post type
     * maps its primitive caps onto manage_woocommerce, so the abilities check
     * exactly the same capability the admin screens do.
     */
    public function canManageStores(): bool
    {
        return current_user_can('manage_woocommerce');
    }
}
