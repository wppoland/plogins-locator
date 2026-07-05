<?php
/**
 * Elementor integration service.
 *
 * Registers the Locator Elementor widget(s). The `elementor/widgets/register`
 * action only fires when Elementor is active, so this service is self-guarding:
 * nothing loads unless Elementor is present. Works on Elementor 3.x and 4.0.
 *
 * @package Locator
 */

declare(strict_types=1);

namespace Locator\Service;

defined('ABSPATH') || exit;

use Locator\Contract\HasHooks;
use Locator\Elementor\StoreLocatorWidget;

/**
 * Wires the Locator widgets into the Elementor editor.
 */
final class ElementorWidgets implements HasHooks
{
    /**
     * Register WordPress hooks.
     */
    public function registerHooks(): void
    {
        add_action('elementor/widgets/register', [$this, 'register']);
    }

    /**
     * Register widget instances with Elementor's widgets manager.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register($widgets_manager): void
    {
        // Loaded here (not autoloaded) so \Elementor\Widget_Base always exists.
        require_once __DIR__ . '/../Elementor/StoreLocatorWidget.php';
        $widgets_manager->register(new StoreLocatorWidget());
    }
}
