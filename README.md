# Locator — Store Locator for WooCommerce

Show your physical store locations with a searchable, accessible list customers
can filter by city, postcode or name. Self-contained, privacy-friendly, no
external API or map key required.

## What it does (free)

- Manage store locations as a dedicated post type under **WooCommerce → Store Locations**
  (name, address, city, postcode, country, phone, email, opening hours, optional lat/lng).
- `[locator]` shortcode renders a searchable directory.
- Instant client-side filtering (city / postcode / name) — no data leaves the page,
  and the list works with JavaScript disabled (all stores are server-rendered).
- List or responsive grid layout; pick which fields appear per card.
- "Get directions" links built from coordinates or the postal address.
- Accessible (ARIA live region, keyboard, focus-visible, SR text), Core Web Vitals
  friendly (no CLS, assets enqueued only when the shortcode is present),
  dark-mode aware and `prefers-reduced-motion` respecting.
- Theme-overridable template: `your-theme/locator/locator-list.php`.

## Architecture

- `locator.php` — bootstrap: guards (PHP/WC), HPOS declaration, boots on `init:0`.
- `src/Plugin.php` — DI container + boot; fires `do_action('locator/booted', $plugin)`.
- `src/PostType/StoreLocation.php` — the `locator_store` CPT, meta box, admin columns.
- `src/Repository/StoreRepository.php` — hydrates published stores into value objects.
- `src/Service/Locator.php` — `[locator]` shortcode + asset enqueue.
- `src/Admin/Settings.php` — WooCommerce submenu settings (layout, visible fields).
- `config/{services,hooks,defaults}.php` — wiring, boot order, defaults.

## Development

```bash
composer install
composer cs        # phpcs
composer analyse   # phpstan level 6
```

The Pro add-on lives in a separate repository, `wppoland/locator-pro`, and boots
via `add_action('locator/booted', …)`.
