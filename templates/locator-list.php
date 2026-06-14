<?php
/**
 * Storefront template: searchable store-locations directory.
 *
 * Variables (prefixed by the template loader):
 *
 * @var list<\Locator\Model\Store> $locator_stores
 * @var string                     $locator_layout      'list' | 'grid'
 * @var bool                       $locator_show_search
 * @var array<string, bool>        $locator_fields
 * @var string                     $locator_empty_text
 *
 * Override by copying to {your-theme}/locator/locator-list.php.
 *
 * @package Locator
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Locator\Service\Locator;

/** @var list<\Locator\Model\Store> $locator_stores */
$locator_stores = isset($locator_stores) && is_array($locator_stores) ? $locator_stores : [];
$locator_layout = isset($locator_layout) && 'grid' === $locator_layout ? 'grid' : 'list';
$locator_show_search = ! empty($locator_show_search);
$locator_fields = isset($locator_fields) && is_array($locator_fields) ? $locator_fields : [];
$locator_empty_text = isset($locator_empty_text) ? (string) $locator_empty_text : '';

$locator_input_id = wp_unique_id('locator-search-');
$locator_count_id = wp_unique_id('locator-count-');
?>
<div class="locator locator--<?php echo esc_attr($locator_layout); ?>" data-locator>

    <?php if ([] === $locator_stores) : ?>

        <p class="locator__empty"><?php echo esc_html($locator_empty_text); ?></p>

    <?php else : ?>

        <?php if ($locator_show_search) : ?>
            <div class="locator__search">
                <label for="<?php echo esc_attr($locator_input_id); ?>" class="locator__search-label">
                    <?php esc_html_e('Find a store', 'locator'); ?>
                </label>
                <input
                    type="search"
                    id="<?php echo esc_attr($locator_input_id); ?>"
                    class="locator__search-input"
                    data-locator-search
                    autocomplete="off"
                    placeholder="<?php esc_attr_e('Search by city, postcode or name…', 'locator'); ?>"
                    aria-describedby="<?php echo esc_attr($locator_count_id); ?>" />
                <p
                    id="<?php echo esc_attr($locator_count_id); ?>"
                    class="locator__count"
                    data-locator-count
                    role="status"
                    aria-live="polite">
                    <?php
                    printf(
                        /* translators: %d: number of store locations. */
                        esc_html(_n('%d location', '%d locations', count($locator_stores), 'locator')),
                        (int) count($locator_stores),
                    );
                    ?>
                </p>
                <p class="locator__noresults" data-locator-noresults hidden>
                    <?php esc_html_e('No locations match your search.', 'locator'); ?>
                </p>
            </div>
        <?php endif; ?>

        <ul class="locator__list" data-locator-list>
            <?php foreach ($locator_stores as $locator_store) :
                $locator_meta = [
                    'city'     => $locator_store->city,
                    'postcode' => $locator_store->postcode,
                ];
                $locator_directions = ! empty($locator_fields['directions'])
                    ? Locator::directionsUrl($locator_store)
                    : '';
                ?>
                <li class="locator__item" data-locator-item
                    data-locator-haystack="<?php echo esc_attr($locator_store->searchHaystack()); ?>">
                    <article class="locator__card">
                        <?php if ('' !== $locator_store->thumbnailUrl) : ?>
                            <img class="locator__thumb" src="<?php echo esc_url($locator_store->thumbnailUrl); ?>"
                                alt="" loading="lazy" decoding="async" />
                        <?php endif; ?>

                        <div class="locator__body">
                            <h3 class="locator__name"><?php echo esc_html($locator_store->name); ?></h3>

                            <?php if ('' !== trim($locator_store->description)) : ?>
                                <p class="locator__desc"><?php echo esc_html(wp_trim_words($locator_store->description, 28)); ?></p>
                            <?php endif; ?>

                            <?php
                            if (! empty($locator_fields['address'])) :
                                $locator_address_lines = array_filter([
                                    $locator_store->address,
                                    trim($locator_store->postcode . ' ' . $locator_store->city),
                                    $locator_store->country,
                                ], static fn (string $line): bool => '' !== trim($line));
                                ?>
                                <?php if ([] !== $locator_address_lines) : ?>
                                    <address class="locator__address">
                                        <?php echo wp_kses_post(nl2br(esc_html(implode("\n", $locator_address_lines)))); ?>
                                    </address>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (! empty($locator_fields['hours']) && '' !== trim($locator_store->hours)) : ?>
                                <div class="locator__hours">
                                    <span class="locator__hours-label"><?php esc_html_e('Opening hours', 'locator'); ?></span>
                                    <?php echo wp_kses_post(nl2br(esc_html($locator_store->hours))); ?>
                                </div>
                            <?php endif; ?>

                            <ul class="locator__contact">
                                <?php if (! empty($locator_fields['phone']) && '' !== trim($locator_store->phone)) : ?>
                                    <li class="locator__phone">
                                        <a href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $locator_store->phone)); ?>">
                                            <?php echo esc_html($locator_store->phone); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (! empty($locator_fields['email']) && '' !== trim($locator_store->email)) : ?>
                                    <li class="locator__email">
                                        <a href="<?php echo esc_url('mailto:' . $locator_store->email); ?>">
                                            <?php echo esc_html($locator_store->email); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ('' !== $locator_directions) : ?>
                                    <li class="locator__directions">
                                        <a href="<?php echo esc_url($locator_directions); ?>"
                                            target="_blank" rel="noopener noreferrer">
                                            <?php esc_html_e('Get directions', 'locator'); ?>
                                            <span class="screen-reader-text">
                                                <?php
                                                printf(
                                                    /* translators: %s: store name. */
                                                    esc_html__('to %s (opens in a new tab)', 'locator'),
                                                    esc_html($locator_store->name),
                                                );
                                                ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>
</div>
