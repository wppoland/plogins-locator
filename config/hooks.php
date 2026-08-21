<?php
/**
 * Boot order: services listed here are resolved from the container and have
 * their registerHooks() called during Plugin::boot(). Each must implement
 * Locator\Contract\HasHooks.
 *
 * Admin-only services (the settings page) are registered only in wp-admin.
 *
 * @package Locator
 *
 * @return array<class-string>
 */

declare(strict_types=1);

use Locator\Admin\Settings;
use Locator\Admin\StoreListSearch;
use Locator\PostType\StoreLocation;
use Locator\Service\AbilitiesService;
use Locator\Service\Locator;

defined('ABSPATH') || exit;

// The Abilities API is served over REST as well as in wp-admin, so it boots in
// both contexts. It no-ops on WordPress versions without the API.
return is_admin()
    ? [
        StoreLocation::class,
        Settings::class,
        StoreListSearch::class,
        Locator::class,
        AbilitiesService::class,
    ]
    : [
        StoreLocation::class,
        Locator::class,
        AbilitiesService::class,
    ];
