<?php
/**
 * Default settings, merged under the option key `locator_settings`.
 *
 * @package Locator
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    // Results layout: 'list' (single column) or 'grid' (responsive cards).
    'layout' => 'list',

    // Show the client-side search/filter box above the results.
    'show_search' => true,

    // Which detail fields appear on each store card. Name is always shown.
    'fields' => [
        'address'    => true,
        'hours'      => true,
        'phone'      => true,
        'email'      => false,
        'directions' => true,
    ],
];
