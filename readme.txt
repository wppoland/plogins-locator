=== Locator - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.1.1
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Show your physical store locations with a searchable, accessible list customers can filter by city, postcode or name.

== Description ==

Locator lists your physical shops on the storefront. You enter each location once
in wp-admin (name, address, city, postcode, country, phone, email, opening hours,
a photo and a description), then
add the `[locator]` shortcode to any page to print a searchable directory.

There is no map and no external service. No Google Maps key, no API call, no
tracking script. Every location is printed in the page HTML, so the directory is
visible even with JavaScript turned off. When JavaScript runs, the search box hides
and shows cards as the visitor types, filtering by city, postcode or store name
entirely in the browser.

Source and issues: [github.com/wppoland/plogins-locator](https://github.com/wppoland/plogins-locator)

**Features**

* Store Locations live as their own post type under the WooCommerce menu.
* Each location keeps its address, city, postcode, country, phone, email and opening hours, plus a photo and a description.
* The `[locator]` shortcode renders the directory; you can leave the search box off if you only have a couple of shops.
* Search runs client-side over name, address, city, postcode and country. No request is sent while typing.
* Per-card display toggles for photo, description, address, opening hours, phone and email (the store name always shows).
* The result count is announced through an ARIA live region, the search field is keyboard-operable, and cards use focus-visible outlines.
* Stylesheet and script load only on pages where the shortcode actually rendered, and the markup avoids layout shift.
* Storefront styles follow the visitor's light/dark preference and honour prefers-reduced-motion.
* On WordPress 6.9 and newer, an AI assistant in wp-admin can list your locations, look one up and read the directory settings, through the WordPress Abilities API. Reading only; it cannot add or change a location.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/locator`, or install via Plugins → Add New.
2. Activate it. WooCommerce must be active.
3. Go to WooCommerce → Store Locations and add your stores.
4. Configure WooCommerce → Store Locator (search box and visible fields).
5. Add the `[locator]` shortcode to any page.

== Frequently Asked Questions ==

= Documentation and links =

* **Documentation**: [plogins.com/plogins-locator/docs/](https://plogins.com/plogins-locator/docs/)
* **Plugin page**: [plogins.com/plogins-locator/](https://plogins.com/plogins-locator/)
* **Source code**: [github.com/wppoland/plogins-locator](https://github.com/wppoland/plogins-locator)
* **Bug reports and feature requests**: [github.com/wppoland/plogins-locator/issues](https://github.com/wppoland/plogins-locator/issues)


= Does it require WooCommerce? =

Yes. Locator registers under the WooCommerce menu and requires WooCommerce to be active.

= Does it show a map? =

No. Locator renders a searchable list of cards, not a map, so it needs no map key or external service. You can still store latitude and longitude per location for use by an add-on.

= Which details can I show on each card? =

The store name is always shown. You can toggle the store photo, the description, the address, opening hours, phone and email in the settings. Each one only appears on a card when that location actually has a value for it.

= How does the search box work? =

The `[locator]` shortcode filters location cards client-side as the shopper types.

= Can I add stores without custom code? =

Yes. Add locations under **WooCommerce → Store Locations** and place `[locator]` on any page.


= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The searchable storefront directory.
2. The Store Locator settings page.

== External Services ==

Locator does not connect to any external service. It registers no remote API, sends no HTTP request, and loads no third-party script, font, map or tile. Your store data never leaves your site.

Every location is stored on your own server as a `locator_store` post, with its address, city, postcode, country, phone, opening hours, email and any latitude/longitude kept in that post's meta. Settings live in the `locator_settings` option. Coordinates are typed in by hand on the location screen - nothing is geocoded against an outside provider. The storefront search filters cards in the visitor's browser, so no request is made while typing, and the plugin sends no email.

== Translations ==

Plogins Locator includes Polish, German and Spanish translations for the plugin interface. The text domain is `plogins-locator`, so WordPress.org language packs can also override or extend these bundled translations.

== Changelog ==

= 1.1.1 =
* Fixed the PRO promo on the settings screen quoting a price in PLN. PRO is priced and charged in EUR, so an admin on a Polish site was shown a zloty amount and then billed in euro, and the zloty figure was a fixed conversion that drifted from the real charge as the rate moved. The promo now shows the euro price that is actually taken.

= 1.1.0 =
* An AI assistant working in your wp-admin can now read your store locations for you, through the WordPress Abilities API (WordPress 6.9 and later). Ask it for the shop in a given city or postcode and it reads back the address, phone, email and opening hours, exactly what the storefront search box would find. It can also tell you how the directory is set up.
* Reading only. Nothing an assistant can call adds, edits or removes a location; that stays in your hands. Only users who can manage WooCommerce can use these, and on WordPress 6.8 and earlier nothing changes.

= 1.0.7 =
* The store email you type on a location now appears on its card, as a click-to-write link beside the phone number. Until now it was saved and never shown to shoppers.
* The featured image you set on a location now appears at the top of its card.
* The text you write in the location editor now appears under the store name.
* Photo, description and email each get their own switch under WooCommerce → Store Locator, alongside the address, opening hours and phone switches.

= 1.0.6 =
* Internal: the store-list search builds its meta-key list through an explicitly typed map, so static analysis can see that only strings reach the query. No change in behaviour.

= 1.0.4 =
* Translations: completed Polish, German and Spanish for the PRO upgrade panel.

= 1.0.3 =
* Fixed low-contrast admin headings under an OS dark-mode preference.

= 1.0.2 =
* Added bundled Polish, German and Spanish translations for the plugin interface.

= 1.0.1 =
* First stable release.

= 0.1.4 =
* Renamed to Plogins Locator for WooCommerce for a more distinctive plugin name.

= 0.1.3 =
* Fix: store locations no longer take over the `manage_woocommerce` capability. Registering the locations post type mapped meta caps onto `manage_woocommerce`, which made every `manage_woocommerce` check fail while the plugin was active, hiding the whole WooCommerce admin menu (Settings, Status, Orders) and the plugin's own settings page.

= 0.1.2 =
* Programmatic store import via `StoreWriter` with `locator/import_store_fields` filter and `locator/store_imported` action.

= 0.1.1 =
* Add `locator/store_groups` filter so add-ons can group the storefront directory by region or country.

= 0.1.0 =
* Initial release: store-location post type, [locator] shortcode, searchable list, settings.
