<?php

declare(strict_types=1);

namespace Locator;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed.
 */
final class Migrator
{
    private const OPTION = 'locator_db_version';

    public function maybeMigrate(): void
    {
        $current = (string) get_option(self::OPTION, '0');

        if (version_compare($current, VERSION, '>=')) {
            return;
        }

        // Locator stores everything in a custom post type + post meta, so there
        // is no schema to create. This tracks the installed version for future
        // forward-migrations and gives the activation hook a safe no-op to call.

        update_option(self::OPTION, VERSION, false);
    }
}
