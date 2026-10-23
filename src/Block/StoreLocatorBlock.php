<?php

declare(strict_types=1);

namespace Locator\Block;

defined('ABSPATH') || exit;

use Locator\Contract\HasHooks;
use Locator\Service\Locator;

/**
 * Registers the `lokilo/store-locator` block: a dynamic, server-rendered block
 * that prints the same directory as the [lokilo] shortcode, through the same
 * renderer, so the editor preview, the block and the shortcode always match.
 */
final class StoreLocatorBlock implements HasHooks
{
    public function __construct(private readonly Locator $locator)
    {
    }

    public function registerHooks(): void
    {
        // The plugin boots on init:0, so registering here is still inside init.
        register_block_type(
            \LOCATOR_DIR . 'blocks/store-locator',
            ['render_callback' => [$this, 'render']],
        );
    }

    public function render(): string
    {
        return $this->locator->renderShortcode();
    }
}
