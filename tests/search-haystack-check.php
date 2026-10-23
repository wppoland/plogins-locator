<?php

/**
 * Storefront search and the list-stores ability must match non-ASCII names,
 * with or without the accents.
 *
 * Store::searchHaystack() lower-cased with strtolower(), which on PHP 8.2+ only
 * touches A-Z, so "Łódź" never matched. Lower-casing alone still left a visitor
 * typing "lodz" with nothing. The haystack is now folded by Store::fold() and
 * the query by fold() in assets/js/locator.js; the two must agree on every
 * input, or a store silently stops matching. This check runs both.
 *
 * remove_accents() is WordPress core, so the check loads it from a core copy:
 * WP_CORE_DIR=/path/to/wordpress php tests/search-haystack-check.php
 */

declare(strict_types=1);

define('ABSPATH', __DIR__);

$locator_dirs = array_merge([(string) getenv('WP_CORE_DIR')], glob(getenv('HOME') . '/.wp-env/*/WordPress') ?: []);
foreach ($locator_dirs as $locator_dir) {
    if ('' !== $locator_dir && is_file($locator_dir . '/wp-includes/formatting.php')) {
        if (is_file($locator_dir . '/wp-includes/utf8.php')) {
            require $locator_dir . '/wp-includes/utf8.php';
        }
        require $locator_dir . '/wp-includes/formatting.php';
        break;
    }
}

if (! function_exists('remove_accents')) {
    echo "SKIP: no WordPress core found, set WP_CORE_DIR\n";
    exit(0);
}

require __DIR__ . '/../src/Model/Store.php';

use Locator\Model\Store;

$store = new Store(1, 'Łódź Piotrkowska', '', 'ul. Piotrkowska 1', 'Łódź', '90-001', 'PL', '', '', '', null, null, '');
$other = new Store(2, 'Überlingen Store', '', 'Große Straße 1', 'Überlingen', '88662', 'DE', '', '', '', null, null, '');

$failures = 0;
$cases    = [[$store, 'lodz'], [$store, 'Łódź'], [$store, 'ŁÓDŹ'], [$store, '90-001'], [$other, 'uberlingen'], [$other, 'grosse strasse']];
foreach ($cases as [$s, $needle]) {
    if (! str_contains($s->searchHaystack(), Store::fold($needle))) {
        echo "FAIL: '{$needle}' not found in '{$s->searchHaystack()}'\n";
        $failures++;
    }
}

// The browser folds the query with locator.js, PHP folds the haystack: same
// inputs, same output, or the card is hidden for a query that should match it.
$inputs = ['Łódź', 'ŁÓDŹ', 'Kraków', 'Überlingen', 'Straße', 'Ørsted', 'Æbeltoft', 'Œuvre', 'Đakovo', 'Þingvellir', 'Iğdır', 'Ħamrun', 'Malmö', 'Ñandú', 'São Paulo'];
$js     = <<<'JS'
const src = require('fs').readFileSync(process.argv[1], 'utf8');
const map = src.match(/var FOLD_MAP = [^;]+;/)[0];
const fn = src.match(/function fold\(text\) \{[\s\S]*?\n  \}\n/)[0];
const fold = new Function(map + fn + 'return fold;')();
process.stdout.write(JSON.stringify(JSON.parse(process.argv[2]).map(fold)));
JS;
$out    = shell_exec('node -e ' . escapeshellarg($js) . ' ' . escapeshellarg(__DIR__ . '/../assets/js/locator.js') . ' ' . escapeshellarg((string) json_encode($inputs)));
$folded = is_string($out) ? json_decode($out, true) : null;

if (! is_array($folded)) {
    echo "FAIL: could not run the JS fold with node\n";
    $failures++;
} else {
    foreach ($inputs as $i => $input) {
        if (Store::fold($input) !== $folded[$i]) {
            echo "FAIL: '{$input}' folds to '" . Store::fold($input) . "' in PHP and '{$folded[$i]}' in JS\n";
            $failures++;
        }
    }
}

echo 0 === $failures ? 'OK: search folds case and accents, PHP and JS agree on ' . count($inputs) . " inputs\n" : '';
exit($failures > 0 ? 1 : 0);
