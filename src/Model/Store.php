<?php

declare(strict_types=1);

namespace Locator\Model;

defined('ABSPATH') || exit;

/**
 * An immutable value object describing a single store location, hydrated from a
 * locator_store post and its meta.
 */
final class Store
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $address,
        public readonly string $city,
        public readonly string $postcode,
        public readonly string $country,
        public readonly string $phone,
        public readonly string $email,
        public readonly string $hours,
        public readonly ?float $lat,
        public readonly ?float $lng,
        public readonly string $thumbnailUrl,
    ) {
    }

    /**
     * A single, folded haystack used for client-side text filtering.
     */
    public function searchHaystack(): string
    {
        $parts = [
            $this->name,
            $this->address,
            $this->city,
            $this->postcode,
            $this->country,
        ];

        return self::fold(trim(implode(' ', array_filter($parts))));
    }

    /**
     * Lower-case UTF-8 text. strtolower() only folds A-Z, so "Łódź" kept its
     * capital and never matched the query the browser lower-cases.
     */
    public static function lower(string $text): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    }

    /**
     * Lower-case and strip accents, so "lodz" finds "Łódź". The locale is pinned:
     * on a de_DE or da_DK site remove_accents() would turn "ü" into "ue" and "ø"
     * into "oe", which the browser-side fold in locator.js cannot know about.
     * "ß" becomes "ss" first, because that is how a visitor types it.
     */
    public static function fold(string $text): string
    {
        return remove_accents(str_replace('ß', 'ss', self::lower($text)), 'en_US');
    }
}
