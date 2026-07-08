=== Plogins Locator - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
Erfordert Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Zeige die Standorte deiner physischen Filialen mit einer durchsuchbaren, zugänglichen Liste an, die Kunden nach Stadt, Postleitzahl oder Name filtern können.

== Description ==

Locator listet deine physischen Geschäfte im Schaufenster auf. Sie geben jeden Standort einmal ein
in wp-admin (Name, Adresse, Stadt, Postleitzahl, Land, Telefon und Öffnungszeiten), dann
Füge den Shortcode „[locator]“ zu einer beliebigen Seite hinzu, um ein durchsuchbares Verzeichnis zu drucken.

Es gibt keine Karte und keinen externen Service. Kein Google Maps-Schlüssel, kein API-Aufruf, nein
Tracking-Skript. Jeder Standort wird im HTML der Seite gedruckt, ebenso das Verzeichnis
auch bei deaktiviertem JavaScript sichtbar. Wenn JavaScript ausgeführt wird, wird das Suchfeld ausgeblendet
und zeigt Karten je nach Besuchertyp an, gefiltert nach Stadt, Postleitzahl oder Geschäftsname
komplett im Browser.

Quelle und Probleme: https://github.com/wppoland/plogins-locator

<strong>Funktionen</strong>

* Store-Standorte sind als eigener Beitragstyp im WooCommerce-Menü verfügbar.
* Jeder Standort behält seine Adresse, Stadt, Postleitzahl, Land, Telefonnummer und Öffnungszeiten.
* Der Shortcode „[locator]“ rendert das Verzeichnis; Du kannst das Suchfeld auslassen, wenn du nur wenige Geschäfte haben.
* Die Suche erfolgt clientseitig über Name, Adresse, Stadt, Postleitzahl und Land. Während der Eingabe wird keine Anfrage gesendet.
* Die Anzeige pro Karte schaltet zwischen Adresse, Öffnungszeiten und Telefonnummer um (der Geschäftsname wird immer angezeigt).
* Die Ergebnisanzahl wird über eine ARIA-Live-Region bekannt gegeben, das Suchfeld ist über die Tastatur bedienbar und die Karten verwenden im Fokus sichtbare Umrisse.
* Stylesheet und Skript werden nur auf Seiten geladen, auf denen der Shortcode tatsächlich gerendert wurde, und das Markup vermeidet Layoutverschiebungen.
* Die Schaufensterstile orientieren sich an der Hell/Dunkel-Vorliebe des Besuchers und berücksichtigen die bevorzugte Bewegungsfreiheit.

== Installation ==

1. Lade das Plugin nach „/wp-content/plugins/locator“ hoch oder installiere es über Plugins → Neu hinzufügen.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Gehe zu WooCommerce → Store-Standorte und füge deine Shops hinzu.
4. Konfiguriere WooCommerce → Store Locator (Suchfeld und sichtbare Felder).
5. Füge den Shortcode „[locator]“ zu einer beliebigen Seite hinzu.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-locator/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-locator/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-locator
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-locator/issues


= Does it require WooCommerce? =

Ja. Locator registriert sich im WooCommerce-Menü und erfordert, dass WooCommerce aktiv ist.

= Does it show a map? =

Nein. Locator stellt eine durchsuchbare Liste von Karten dar, keine Karte, sodass kein Kartenschlüssel oder externer Dienst erforderlich ist. Du kannst weiterhin Breiten- und Längengrade pro Standort zur Verwendung durch ein Add-on speichern.

= Which details can I show on each card? =

Der Shopname wird immer angezeigt. In den Einstellungen kannst du Adresse, Öffnungszeiten und Telefon umschalten.

= How does the search box work? =

Der Shortcode „[locator]“ filtert Standortkarten clientseitig, während der Käufer sie eingibt.

= Can I add stores without custom code? =

Ja. Füge Standorte unter <strong>WooCommerce → Store-Standorte</strong> hinzu und platziere „[locator]“ auf einer beliebigen Seite.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Das durchsuchbare Storefront-Verzeichnis.
2. Die Seite mit den Store Locator-Einstellungen.

== External Services ==

Der Locator stellt keine Verbindung zu einem externen Dienst her. Es registriert keine Remote-API, sendet keine HTTP-Anfrage und lädt keine Skripte, Schriftarten, Karten oder Kacheln von Drittanbietern. deine Shop-Daten verlassen niemals deine Website.

Jeder Standort wird auf deinem eigenen Server als „locator_store“-Beitrag gespeichert, wobei Adresse, Stadt, Postleitzahl, Land, Telefonnummer, Öffnungszeiten, E-Mail-Adresse und etwaige Breiten-/Längengrade im Meta dieses Beitrags enthalten sind. Die Einstellungen befinden sich in der Option „locator_settings“. Die Koordinaten werden manuell auf dem Standortbildschirm eingegeben – es erfolgt keine Geokodierung gegenüber einem externen Anbieter. Die Storefront-Suche filtert Karten im Browser des Besuchers, sodass beim Tippen keine Anfrage erfolgt und das Plugin keine E-Mail sendet.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.1.4 =
* Für einen eindeutigeren Plugin-Namen in Plogins Locator für WooCommerce umbenannt.

= 0.1.3 =
* Fix: Filialstandorte übernehmen nicht mehr die Funktion „manage_woocommerce“. Durch die Registrierung des Standortbeitrags wurden Meta-Caps auf „manage_woocommerce“ zugeordnet, was dazu führte, dass jede „manage_woocommerce“-Prüfung fehlschlug, während das Plugin aktiv war, wodurch das gesamte WooCommerce-Administratormenü (Einstellungen, Status, Bestellungen) und die eigene Einstellungsseite des Plugins ausgeblendet wurden.

= 0.1.2 =
* Programmatischer Store-Import über „StoreWriter“ mit „locator/import_store_fields“-Filter und „locator/store_imported“-Aktion.

= 0.1.1 =
* Füge den Filter „locator/store_groups“ hinzu, damit Add-ons das Storefront-Verzeichnis nach Region oder Land gruppieren können.

= 0.1.0 =
* Erstveröffentlichung: Beitragstyp „Store-Location“, [locator]-Shortcode, durchsuchbare Liste, Einstellungen.
