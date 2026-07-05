<?php
/**
 * Elementor widget: Store Locator.
 *
 * A thin wrapper around the [locator] shortcode so the store locator can be
 * placed with the Elementor editor. Kept deliberately minimal (it renders the
 * shortcode) so a future migration to Elementor v4 atomic widgets stays
 * localized to this class. Loaded only from the `elementor/widgets/register`
 * hook, so the `\Elementor\Widget_Base` base class is guaranteed to exist here.
 *
 * @package Locator
 */

declare(strict_types=1);

namespace Locator\Elementor;

defined('ABSPATH') || exit;

use Elementor\Widget_Base;

/**
 * Store Locator Elementor widget.
 */
final class StoreLocatorWidget extends Widget_Base
{
    /**
     * Widget machine name (matches the shortcode tag).
     */
    public function get_name(): string
    {
        return 'locator';
    }

    /**
     * Widget label shown in the editor.
     */
    public function get_title(): string
    {
        return esc_html__('Store Locator', 'plogins-locator');
    }

    /**
     * Editor panel icon.
     */
    public function get_icon(): string
    {
        return 'eicon-google-maps';
    }

    /**
     * Editor panel categories.
     *
     * @return string[]
     */
    public function get_categories(): array
    {
        return ['woocommerce-elements', 'general'];
    }

    /**
     * Search keywords in the editor.
     *
     * @return string[]
     */
    public function get_keywords(): array
    {
        return ['locator', 'store', 'stores', 'location', 'directory', 'map', 'woocommerce'];
    }

    /**
     * Register the editor controls.
     *
     * The [locator] shortcode takes no required attributes, so a single empty
     * content section keeps the panel tidy without exposing needless controls.
     */
    protected function register_controls(): void
    {
        $this->start_controls_section(
            'content',
            ['label' => esc_html__('Store Locator', 'plogins-locator')]
        );

        $this->add_control(
            'notice',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('This block lists your store locations. Configure the visible fields on the Locator settings page.', 'plogins-locator'),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget on the front end and in the editor preview.
     */
    protected function render(): void
    {
        echo do_shortcode('[locator]');
    }
}
