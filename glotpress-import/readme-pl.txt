=== Plogins Locator - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
Wymaga wtyczek: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pokaż lokalizacje swoich sklepów stacjonarnych za pomocą łatwej do przeszukiwania listy, którą klienci mogą filtrować według miasta, kodu pocztowego lub nazwy.

== Description ==

Locator wyświetla listę Twoich sklepów fizycznych w witrynie sklepowej. Do każdej lokalizacji wchodzisz raz
w wp-admin (nazwa, adres, miasto, kod pocztowy, kraj, telefon i godziny otwarcia), następnie
dodaj krótki kod `[locator]` do dowolnej strony, aby wydrukować katalog z możliwością przeszukiwania.

Nie ma mapy ani usługi zewnętrznej. Żadnego klucza Google Maps, żadnego wywołania API, nie
skrypt śledzący. Każda lokalizacja jest drukowana na stronie HTML, więc katalog też
widoczne nawet po wyłączeniu JavaScript. Po uruchomieniu JavaScript pole wyszukiwania zostaje ukryte
i pokazuje karty w zależności od typu odwiedzającego, filtrując według miasta, kodu pocztowego lub nazwy sklepu
całkowicie w przeglądarce.

Źródło i wydania: https://github.com/wppoland/plogins-locator

<strong>Funkcje</strong>

* Lokalizacje sklepów działają jako własny typ postu w menu WooCommerce.
* Każda lokalizacja zachowuje swój adres, miasto, kod pocztowy, kraj, telefon i godziny otwarcia.
* Krótki kod `[locator]` renderuje katalog; możesz pozostawić pole wyszukiwania wyłączone, jeśli masz tylko kilka sklepów.
* Wyszukiwanie po stronie klienta odbywa się według nazwy, adresu, miasta, kodu pocztowego i kraju. Podczas pisania nie jest wysyłane żadne żądanie.
* Wyświetlanie poszczególnych kart umożliwia przełączanie adresu, godzin otwarcia i telefonu (zawsze wyświetlana jest nazwa sklepu).
* Liczba wyników jest ogłaszana w aktywnym regionie ARIA, pole wyszukiwania można obsługiwać za pomocą klawiatury, a karty korzystają z widocznych konturów.
* Arkusz stylów i skrypty ładują się tylko na stronach, na których faktycznie wyrenderowano krótki kod, a znaczniki pozwalają uniknąć zmiany układu.
* Style witryn sklepowych są zgodne z preferencjami gościa dotyczącymi jasności/ciemności i honorują preferencje ograniczonego ruchu.

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/locator` lub zainstaluj poprzez Wtyczki → Dodaj nową.
2. Aktywuj. WooCommerce musi być aktywny.
3. Przejdź do WooCommerce → Lokalizacje sklepów i dodaj swoje sklepy.
4. Skonfiguruj WooCommerce → Lokalizator sklepów (pole wyszukiwania i widoczne pola).
5. Dodaj krótki kod `[locator]` do dowolnej strony.

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

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Katalog sklepu z możliwością przeszukiwania.
2. Strona ustawień Lokalizatora sklepów.

== External Services ==

Lokalizator nie łączy się z żadną usługą zewnętrzną. Nie rejestruje żadnego zdalnego interfejsu API, nie wysyła żądań HTTP i nie ładuje żadnych skryptów, czcionek, map ani kafelków innych firm. Dane Twojego sklepu nigdy nie opuszczają Twojej witryny.

Każda lokalizacja jest przechowywana na Twoim własnym serwerze jako post w „lokalizatorze_sklepu” z jej adresem, miastem, kodem pocztowym, krajem, telefonem, godzinami otwarcia, adresem e-mail i dowolną szerokością/długością geograficzną przechowywaną w meta tego wpisu. Ustawienia znajdują się w opcji `locator_settings`. Współrzędne są wpisywane ręcznie na ekranie lokalizacji – nic nie jest geokodowane względem zewnętrznego dostawcy. Wyszukiwanie w witrynie sklepowej filtruje karty w przeglądarce odwiedzającego, więc podczas pisania nie jest wysyłane żadne żądanie, a wtyczka nie wysyła wiadomości e-mail.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.4 =
* Zmieniono nazwę na Plogins Locator dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.3 =
* Poprawka: lokalizacje sklepów nie przejmują już możliwości „manage_woocommerce”. Zarejestrowanie typu postu lokalizacji zmapowało meta caps na `manage_woocommerce`, co spowodowało niepowodzenie każdej kontroli `manage_woocommerce`, gdy wtyczka była aktywna, ukrywając całe menu administratora WooCommerce (Ustawienia, Status, Zamówienia) i własną stronę ustawień wtyczki.

= 0.1.2 =
* Programowy import sklepu poprzez `StoreWriter` z filtrem `locator/import_store_fields` i akcją `locator/store_imported`.

= 0.1.1 =
* Dodaj filtr „lokator/grupy_sklepów”, aby dodatki mogły grupować katalog witryny sklepowej według regionu lub kraju.

= 0.1.0 =
* Wersja pierwsza: typ wpisu o lokalizacji sklepu, krótki kod [locator], lista z możliwością przeszukiwania, ustawienia.
