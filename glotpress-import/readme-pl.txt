=== Plogins Locator - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pokaż lokalizacje swoich sklepów stacjonarnych za pomocą łatwej do przeszukiwania listy, którą klienci mogą filtrować według miasta, kodu pocztowego lub nazwy.

== Description ==

Locator wyświetla listę Twoich sklepów stacjonarnych w sklepie. Każdą lokalizację wprowadzasz raz
w wp-admin (nazwa, adres, miasto, kod pocztowy, kraj, telefon i godziny otwarcia), a następnie
dodajesz shortcode `[locator]` do dowolnej strony, aby wyświetlić katalog z możliwością przeszukiwania.

Nie ma mapy ani usługi zewnętrznej. Żadnego klucza Google Maps, żadnego wywołania API, żadnego
skryptu śledzącego. Każda lokalizacja jest umieszczana w kodzie HTML strony, więc katalog jest
widoczny nawet po wyłączeniu JavaScript. Gdy JavaScript działa, pole wyszukiwania ukrywa
i pokazuje karty w miarę pisania przez odwiedzającego, filtrując według miasta, kodu pocztowego lub nazwy sklepu
całkowicie w przeglądarce.

Kod źródłowy i zgłoszenia: https://github.com/wppoland/plogins-locator

<strong>Funkcje</strong>

* Lokalizacje sklepów działają jako własny typ postu w menu WooCommerce.
* Każda lokalizacja zachowuje swój adres, miasto, kod pocztowy, kraj, telefon i godziny otwarcia.
* Shortcode `[locator]` renderuje katalog; możesz pozostawić pole wyszukiwania wyłączone, jeśli masz tylko kilka sklepów.
* Wyszukiwanie po stronie klienta odbywa się według nazwy, adresu, miasta, kodu pocztowego i kraju. Podczas pisania nie jest wysyłane żadne żądanie.
* Wyświetlanie poszczególnych kart umożliwia przełączanie adresu, godzin otwarcia i telefonu (zawsze wyświetlana jest nazwa sklepu).
* Liczba wyników jest ogłaszana w aktywnym regionie ARIA, pole wyszukiwania można obsługiwać za pomocą klawiatury, a karty korzystają z widocznych konturów.
* Arkusz stylów i skrypt ładują się tylko na stronach, na których faktycznie wyrenderowano shortcode, a znaczniki pozwalają uniknąć przesunięć układu.
* Style sklepu są zgodne z preferencją jasnego/ciemnego trybu odwiedzającego i respektują preferencję ograniczonego ruchu (prefers-reduced-motion).

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/locator` lub zainstaluj poprzez Wtyczki → Dodaj nową.
2. Aktywuj. WooCommerce musi być aktywny.
3. Przejdź do WooCommerce → Lokalizacje sklepów i dodaj swoje sklepy.
4. Skonfiguruj WooCommerce → Lokalizator sklepów (pole wyszukiwania i widoczne pola).
5. Dodaj shortcode `[locator]` do dowolnej strony.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-locator/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-locator/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-locator
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-locator/issues


= Does it require WooCommerce? =

Tak. Locator rejestruje się w menu WooCommerce i wymaga, aby WooCommerce był aktywny.

= Does it show a map? =

Nie. Locator renderuje listę kart z możliwością przeszukiwania, a nie mapę, więc nie potrzebuje klucza mapy ani usługi zewnętrznej. Nadal możesz zapisywać szerokość i długość geograficzną dla poszczególnych lokalizacji do wykorzystania przez dodatek.

= Which details can I show on each card? =

Nazwa sklepu jest zawsze wyświetlana. Możesz zmienić adres, godziny otwarcia i telefon w ustawieniach.

= How does the search box work? =

Krótki kod `[locator]` filtruje karty lokalizacyjne po stronie klienta w miarę wpisywania hasła przez kupującego.

= Can I add stores without custom code? =

Tak. Dodaj lokalizacje w <strong>WooCommerce → Lokalizacje sklepów</strong> i umieść `[locator]` na dowolnej stronie.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Włącz ją dla całej sieci lub na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Katalog sklepu z możliwością przeszukiwania.
2. Strona ustawień Lokalizatora sklepów.

== External Services ==

Locator nie łączy się z żadną usługą zewnętrzną. Nie rejestruje żadnego zdalnego interfejsu API, nie wysyła żądań HTTP i nie ładuje żadnych skryptów, czcionek, map ani kafelków innych firm. Dane Twojego sklepu nigdy nie opuszczają Twojej witryny.

Każda lokalizacja jest przechowywana na Twoim własnym serwerze jako wpis typu `locator_store` – wraz z adresem, miastem, kodem pocztowym, krajem, telefonem, godzinami otwarcia, adresem e-mail i ewentualną szerokością/długością geograficzną przechowywaną w metadanych tego wpisu. Ustawienia znajdują się w opcji `locator_settings`. Współrzędne są wpisywane ręcznie na ekranie lokalizacji – nic nie jest geokodowane względem zewnętrznego dostawcy. Wyszukiwanie w sklepie filtruje karty w przeglądarce odwiedzającego, więc podczas pisania nie jest wysyłane żadne żądanie, a wtyczka nie wysyła wiadomości e-mail.

== Translations ==

Plogins Locator zawiera polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki. Domeną tekstową jest `plogins-locator`, więc paczki językowe z WordPress.org mogą też nadpisywać lub uzupełniać te dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.4 =
* Zmieniono nazwę na Plogins Locator for WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.3 =
* Poprawka: lokalizacje sklepów nie przejmują już uprawnienia `manage_woocommerce`. Zarejestrowanie typu wpisu lokalizacji mapowało meta capabilities na `manage_woocommerce`, co powodowało niepowodzenie każdego sprawdzenia `manage_woocommerce`, gdy wtyczka była aktywna, ukrywając całe menu WooCommerce w kokpicie (Ustawienia, Status, Zamówienia) oraz własną stronę ustawień wtyczki.

= 0.1.2 =
* Programowy import sklepu poprzez `StoreWriter` z filtrem `locator/import_store_fields` i akcją `locator/store_imported`.

= 0.1.1 =
* Dodano filtr `locator/store_groups`, aby dodatki mogły grupować katalog sklepu według regionu lub kraju.

= 0.1.0 =
* Wersja pierwsza: typ wpisu lokalizacji sklepu, shortcode [locator], lista z możliwością przeszukiwania, ustawienia.
