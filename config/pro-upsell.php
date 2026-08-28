<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-locator-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Locator Pro',
    'url'        => 'https://plogins.com/plogins-locator-pro/pricing/',
    'sellable'   => true,
    'price_from' => 29,
    'currency'   => 'EUR',
    'lead'       => [
        'en' => 'Interactive map, region grouping and bulk CSV import. Feature-complete PRO.',
        'pl' => 'Interaktywna mapa, grupowanie po regionach i import zbiorczy z CSV. Kompletna wersja PRO.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Interactive map', 'desc' => 'An embedded map with pins for every geocoded location via [locator_map], built on OpenStreetMap tiles.'],
            'pl' => ['title' => 'Interaktywna mapa', 'desc' => 'Osadzona mapa z pinezkami dla każdej lokalizacji, oparta na współrzędnych obok przeszukiwalnej listy.'],
        ],
        [
            'en' => ['title' => 'Region grouping', 'desc' => 'Group [locator] list entries by a Region (Pro) field, with country fallback for unlabelled stores.'],
            'pl' => ['title' => 'Grupowanie po regionach', 'desc' => 'Grupuj lokalizacje po regionie lub kraju na liście [locator], aby duże katalogi pozostały czytelne.'],
        ],
        [
            'en' => ['title' => 'Bulk import', 'desc' => 'Import or update locations from CSV on WooCommerce → Store Locator Import instead of adding each store by hand.'],
            'pl' => ['title' => 'Import zbiorczy', 'desc' => 'Importuj lub aktualizuj lokalizacje z pliku CSV na ekranie WooCommerce → Store Locator Import.'],
        ],
    ],
];
