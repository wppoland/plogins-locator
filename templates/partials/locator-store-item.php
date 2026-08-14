<?php
/**
 * Single store card in the locator directory list.
 *
 * @var \Locator\Model\Store $locator_store
 * @var array<string, bool>  $locator_fields
 *
 * @package Locator
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

if (! isset($locator_store) || ! $locator_store instanceof \Locator\Model\Store) {
    return;
}

$locator_fields = isset($locator_fields) && is_array($locator_fields) ? $locator_fields : [];
?>
<li class="locator__item" data-locator-item
    data-locator-haystack="<?php echo esc_attr($locator_store->searchHaystack()); ?>">
    <article class="locator__card">
        <span class="locator__pin" data-locator-pin aria-hidden="true">
            <svg viewBox="0 0 24 24" width="20" height="20" focusable="false">
                <path d="M12 2a7 7 0 0 0-7 7c0 4.8 6.2 12.2 6.5 12.5a.7.7 0 0 0 1 0C12.8 21.2 19 13.8 19 9a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z" />
            </svg>
        </span>
        <div class="locator__body">
            <?php
            // The location screen offers a featured image and the content editor,
            // and both were hydrated into the Store object and then thrown away:
            // a merchant uploaded a shopfront photo and wrote a paragraph about
            // the branch, and the shopper got a pin, a name and three lines of
            // detail. Both are printed here now, each behind its own toggle and
            // only when that location actually has one.
            if (! empty($locator_fields['photo']) && '' !== trim($locator_store->thumbnailUrl)) :
                ?>
                <img class="locator__photo" src="<?php echo esc_url($locator_store->thumbnailUrl); ?>"
                    alt="" loading="lazy" decoding="async" />
            <?php endif; ?>

            <h3 class="locator__name"><?php echo esc_html($locator_store->name); ?></h3>

            <?php if (! empty($locator_fields['description']) && '' !== trim($locator_store->description)) : ?>
                <div class="locator__description">
                    <?php echo wp_kses_post(wpautop($locator_store->description)); ?>
                </div>
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
                    <span class="locator__hours-label"><?php esc_html_e('Opening hours', 'plogins-locator'); ?></span>
                    <?php echo wp_kses_post(nl2br(esc_html($locator_store->hours))); ?>
                </div>
            <?php endif; ?>

            <?php
            // Email was saved as _locator_email, hydrated into the Store object
            // and then never printed: the merchant filled in the branch address
            // expecting it beside the phone number, the shopper only ever saw the
            // number. It now sits in the same contact list, behind its own toggle.
            $locator_show_phone = ! empty($locator_fields['phone']) && '' !== trim($locator_store->phone);
            $locator_show_email = ! empty($locator_fields['email']) && is_email($locator_store->email);
            ?>
            <?php if ($locator_show_phone || $locator_show_email) : ?>
                <ul class="locator__contact">
                    <?php if ($locator_show_phone) : ?>
                        <li class="locator__phone">
                            <a href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $locator_store->phone)); ?>">
                                <?php echo esc_html($locator_store->phone); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($locator_show_email) : ?>
                        <li class="locator__email">
                            <a href="<?php echo esc_url('mailto:' . $locator_store->email); ?>">
                                <?php echo esc_html($locator_store->email); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </article>
</li>
