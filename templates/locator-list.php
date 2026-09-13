<?php
/**
 * Storefront template: searchable store-locations directory.
 *
 * Variables (prefixed by the template loader):
 *
 * @var list<\Locator\Model\Store>                                                         $locator_stores
 * @var int                                                                                  $locator_total
 * @var list<array{label: string, stores: list<\Locator\Model\Store>}>|null               $locator_store_groups
 * @var bool                                                                             $locator_show_search
 * @var array<string, bool>                                                              $locator_fields
 * @var string                                                                           $locator_empty_text
 *
 * @package Locator
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/** @var list<\Locator\Model\Store> $locator_stores */
$locator_stores = isset($locator_stores) && is_array($locator_stores) ? $locator_stores : [];
/** @var list<array{label: string, stores: list<\Locator\Model\Store>}>|null $locator_store_groups */
$locator_store_groups = isset($locator_store_groups) && is_array($locator_store_groups) ? $locator_store_groups : null;
$locator_show_search = ! empty($locator_show_search);
$locator_fields = isset($locator_fields) && is_array($locator_fields) ? $locator_fields : [];
$locator_empty_text = isset($locator_empty_text) ? (string) $locator_empty_text : '';

// How many locations this page prints, and how many exist. The directory is
// bounded, so the two differ on a big shop and the shopper is told so: the count
// used to read count($locator_stores), which announced "200 locations" to a
// screen reader on a shop that has 700.
$locator_listed    = count($locator_stores);
$locator_total     = isset($locator_total) ? max((int) $locator_total, $locator_listed) : $locator_listed;
$locator_truncated = $locator_total > $locator_listed;

$locator_input_id = wp_unique_id('locator-search-');
$locator_count_id = wp_unique_id('locator-count-');
$locator_item_partial = __DIR__ . '/partials/locator-store-item.php';
?>
<div class="locator" data-locator>

    <?php if ([] === $locator_stores) : ?>

        <p class="locator__empty"><?php echo esc_html($locator_empty_text); ?></p>

    <?php else : ?>

        <?php if ($locator_show_search) : ?>
            <div class="locator__search">
                <label for="<?php echo esc_attr($locator_input_id); ?>" class="locator__search-label">
                    <?php esc_html_e('Find a store', 'plogins-locator'); ?>
                </label>
                <input
                    type="search"
                    id="<?php echo esc_attr($locator_input_id); ?>"
                    class="locator__search-input"
                    data-locator-search
                    autocomplete="off"
                    placeholder="<?php esc_attr_e('Search by city, postcode or name…', 'plogins-locator'); ?>"
                    aria-describedby="<?php echo esc_attr($locator_count_id); ?>" />
                <p
                    id="<?php echo esc_attr($locator_count_id); ?>"
                    class="locator__count"
                    data-locator-count
                    <?php if ($locator_truncated) : ?>
                    data-locator-filtered-label="<?php
                    echo esc_attr(
                        sprintf(
                            /* translators: 1: a %d placeholder the browser replaces with the number of matches, 2: number of locations listed on this page. */
                            __('%1$s of %2$d listed locations match', 'plogins-locator'),
                            '%d',
                            $locator_listed,
                        )
                    );
                    ?>"
                    <?php endif; ?>
                    role="status"
                    aria-live="polite">
                    <?php
                    if ($locator_truncated) {
                        printf(
                            /* translators: 1: number of locations listed on this page, 2: total number of locations. */
                            esc_html__('Showing %1$d of %2$d locations', 'plogins-locator'),
                            (int) $locator_listed,
                            (int) $locator_total,
                        );
                    } else {
                        printf(
                            /* translators: %d: number of store locations. */
                            esc_html(_n('%d location', '%d locations', $locator_listed, 'plogins-locator')),
                            (int) $locator_listed,
                        );
                    }
                    ?>
                </p>
                <p class="locator__noresults" data-locator-noresults hidden>
                    <?php esc_html_e('No locations match your search.', 'plogins-locator'); ?>
                    <?php
                    if ($locator_truncated) {
                        printf(
                            /* translators: 1: number of locations listed on this page, 2: total number of locations. */
                            esc_html__('This search covered only the %1$d locations listed here, out of %2$d.', 'plogins-locator'),
                            (int) $locator_listed,
                            (int) $locator_total,
                        );
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($locator_truncated) : ?>
            <p class="locator__truncated" data-locator-truncated>
                <?php
                printf(
                    /* translators: 1: number of locations listed on this page, 2: total number of locations. */
                    esc_html__('This page lists the first %1$d of %2$d locations.', 'plogins-locator'),
                    (int) $locator_listed,
                    (int) $locator_total,
                );

                if ($locator_show_search) {
                    echo ' ';
                    esc_html_e('The search box only looks through the locations listed here.', 'plogins-locator');
                }
                ?>
            </p>
        <?php endif; ?>

        <?php if (is_array($locator_store_groups) && $locator_store_groups !== []) : ?>
            <?php foreach ($locator_store_groups as $locator_group) :
                $locator_group_label = (string) ($locator_group['label'] ?? '');
                $locator_group_stores = is_array($locator_group['stores'] ?? null) ? $locator_group['stores'] : [];
                ?>
                <section class="locator__group" data-locator-group>
                    <?php if ('' !== $locator_group_label) : ?>
                        <h2 class="locator__group-title"><?php echo esc_html($locator_group_label); ?></h2>
                    <?php endif; ?>
                    <ul class="locator__list" data-locator-list>
                        <?php foreach ($locator_group_stores as $locator_store) :
                            if (! $locator_store instanceof \Locator\Model\Store) {
                                continue;
                            }
                            require $locator_item_partial;
                        endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        <?php else : ?>
            <ul class="locator__list" data-locator-list>
                <?php foreach ($locator_stores as $locator_store) :
                    require $locator_item_partial;
                endforeach; ?>
            </ul>
        <?php endif; ?>

    <?php endif; ?>
</div>
