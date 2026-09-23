=== Lokilo - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.2.1
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Show your physical store locations with a searchable, accessible list customers can filter by city, postcode or name.

== Description ==

Lokilo lists your physical shops on the storefront. You enter each location once in wp-admin, name, address, city, postcode, country, phone, email, opening hours, a photo and a description, then drop the `[locator]` shortcode on any page and the directory prints.

= No map, and that is the point =

There is no Google Maps key to get, no API to pay for, no third-party script and nothing sent anywhere. A store locator that needs a billing account and a key with a quota is a store locator that breaks quietly the month someone forgets to pay it.

Every location is printed into the page HTML, so the directory is there with JavaScript turned off, and it is there for a search engine reading the page. When JavaScript does run, the search box filters the cards as the visitor types, over name, address, city, postcode and country, without a single request.

= What you get =

* **Locations as their own admin screen.** Store Locations sits under the WooCommerce menu, with a photo, a description and the full contact block per shop.
* **One shortcode.** `[locator]` renders the directory. `[locator limit="500"]` raises the 200-store default and `[locator limit="-1"]` prints every one.
* **An honest count.** When the page shows fewer locations than you have, it says so above the list, and says the search box only reaches the ones shown, rather than letting a visitor conclude you have no shop in their city.
* **Per-card control.** Photo, description, address, opening hours, phone and email each have a toggle. The store name always shows.
* **Accessible by construction.** The result count goes through an ARIA live region, the search field is keyboard-operable, cards carry focus-visible outlines, and the storefront styles follow the visitor's light or dark preference and honour prefers-reduced-motion.
* **Loads nothing it does not need.** The stylesheet and script are enqueued only on a page where the shortcode actually rendered, and the markup is written to avoid layout shift.
* **Readable by an assistant.** On WordPress 6.9 and newer an AI assistant in wp-admin can list your locations, look one up and read the directory settings, through the WordPress Abilities API. Reading only: it cannot add or change anything.

= What it does not do =

There is no map view, no driving directions and no geolocation of the visitor, because all three need a paid map provider. If you need a pin on a map, this is not the plugin.

It does not import in bulk. Locations are added one at a time in wp-admin.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/lokilo`, or install via Plugins > Add New.
2. Activate it. WooCommerce must be active.
3. Go to WooCommerce > Store Locations and add your stores.
4. Configure WooCommerce > Store Locator (search box and visible fields).
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

Yes. Add locations under **WooCommerce > Store Locations** and place `[locator]` on any page.


= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The storefront directory: every location in the page, filtered as the visitor types.
2. The settings screen: the search box, and which fields each card shows.

== External Services ==

Locator does not connect to any external service. It registers no remote API, sends no HTTP request, and loads no third-party script, font, map or tile. Your store data never leaves your site.

Every location is stored on your own server as a `locator_store` post, with its address, city, postcode, country, phone, opening hours, email and any latitude/longitude kept in that post's meta. Settings live in the `locator_settings` option. Coordinates are typed in by hand on the location screen - nothing is geocoded against an outside provider. The storefront search filters cards in the visitor's browser, so no request is made while typing, and the plugin sends no email.

== Translations ==

Plogins Locator is fully translatable and ships the `plogins-locator.pot` template. Translations are delivered by WordPress.org language packs from translate.wordpress.org, which is where Polish, German and Spanish are being contributed; the package itself carries no compiled translation files.

== Changelog ==

= 1.2.1 =
* The sidebar upgrade promo now follows the same dismissal as the banner. Dismissing the banner used to leave a full-height advert on the settings screen for good, which is not what the WordPress.org guideline on upgrade prompts means by used with moderation.

= 1.2.0 =
* Renamed to Lokilo. The WordPress.org review team asks a plugin name to lead with a distinctive, coined identifier rather than a generic descriptive word. Lokilo is Esperanto for a locating tool. The text domain follows the name; the stored locations, the settings, the [locator] shortcode and every hook are unchanged.

= 1.1.9 =
* Fixed: the directory announced how many locations it had printed, not how many the shop has. Since 1.1.8 the page renders 200 by default, so a shop with 700 locations told every visitor, and read out to every screen reader, "200 locations". The count now reads "Showing 200 of 700 locations", and a shop whose locations all fit on the page reads exactly as before.
* Fixed: the search box only ever looked through the locations printed on the page, and nothing said so. A visitor searching for the 500th location was told "No locations match your search" and given no reason. The page now states above the list that it is showing the first 200 of 700 and that search covers only those, and the empty result says it again.

= 1.1.8 =
* Fixed: the directory rendered every published store on the page. The shortcode ignored its attributes entirely, so there was no way to ask for fewer, and a shop with a long franchise list built every store, and a separate photo lookup per store, into one public page. `[locator]` now renders 200 stores and honours `limit`, including `limit="-1"` for all of them, and the store photos are primed in one pass for the whole page instead of a lookup per store.

= 1.1.7 =
* Fixed: the PRO upgrade promo kept selling to people who had already bought the paid edition. Only the banner could be dismissed, so the sidebar promo and the locked feature cards followed a paying customer around for good. The promo now checks whether the paid edition is active and steps aside when it is.
* Fixed: arrow glyphs in the admin menu paths, and in the strings handed to translators. An arrow inside a translatable string makes the glyph every translator's problem and changes the layout in any locale that drops it.

= 1.1.6 =
* Fixed: deleting the plugin left the per-user "dismiss" flag from the PRO notice in the database. Uninstall now removes it for every user, not just the one who dismissed it.

= 1.1.5 =
* The translation template was regenerated. It still named an older version of the plugin and pointed at source lines that had since moved, which is what translation tools read to show a string in context.

= 1.1.4 =
* Renamed to Plogins Locator - Store Locator for WooCommerce so the name leads with the brand rather than a generic word, which is what the WordPress.org plugin review team asks for. The plugin slug is unchanged.

= 1.1.3 =
* Removed the "Tested up to" header from the main PHP file. It belongs in readme.txt only, where it is already declared; present in both, the header can override the readme and show a compatibility version that was never intended.

= 1.1.2 =
* Tested against WordPress 7.1. Verified by activating this build on a clean 7.1 install with WooCommerce 11.1, not by editing the header.

= 1.1.1 =
* Fixed the PRO promo on the settings screen quoting a price in PLN. PRO is priced and charged in EUR, so an admin on a Polish site was shown a zloty amount and then billed in euro, and the zloty figure was a fixed conversion that drifted from the real charge as the rate moved. The promo now shows the euro price that is actually taken.

= 1.1.0 =
* An AI assistant working in your wp-admin can now read your store locations for you, through the WordPress Abilities API (WordPress 6.9 and later). Ask it for the shop in a given city or postcode and it reads back the address, phone, email and opening hours, exactly what the storefront search box would find. It can also tell you how the directory is set up.
* Reading only. Nothing an assistant can call adds, edits or removes a location; that stays in your hands. Only users who can manage WooCommerce can use these, and on WordPress 6.8 and earlier nothing changes.

= 1.0.7 =
* The store email you type on a location now appears on its card, as a click-to-write link beside the phone number. Until now it was saved and never shown to shoppers.
* The featured image you set on a location now appears at the top of its card.
* The text you write in the location editor now appears under the store name.
* Photo, description and email each get their own switch under WooCommerce > Store Locator, alongside the address, opening hours and phone switches.

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
