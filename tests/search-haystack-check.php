<?php

/**
 * Storefront search and the list-stores ability must match non-ASCII names.
 *
 * Store::searchHaystack() lower-cased with strtolower(), which on PHP 8.2+ only
 * touches A-Z. A store in "Łódź" or "Überlingen" kept its capital in the
 * haystack while the browser lower-cased the query, so neither "łódź" nor
 * "Łódź" ever matched.
 *
 * Run: php tests/search-haystack-check.php
 */

declare(strict_types=1);

define('ABSPATH', __DIR__);

require __DIR__ . '/../src/Model/Store.php';

use Locator\Model\Store;

$store = new Store(1, 'Łódź Piotrkowska', '', 'ul. Piotrkowska 1', 'Łódź', '90-001', 'PL', '', '', '', null, null, '');
$other = new Store(2, 'Überlingen Store', '', 'Seestr 1', 'Überlingen', '88662', 'DE', '', '', '', null, null, '');

$failures = 0;
foreach ([[$store, 'łódź'], [$other, 'überlingen'], [$store, '90-001']] as [$s, $needle]) {
    if (! str_contains($s->searchHaystack(), $needle)) {
        echo "FAIL: '{$needle}' not found in '{$s->searchHaystack()}'\n";
        $failures++;
    }
}

echo 0 === $failures ? "OK: search haystack lower-cases non-ASCII letters\n" : '';
exit($failures > 0 ? 1 : 0);
