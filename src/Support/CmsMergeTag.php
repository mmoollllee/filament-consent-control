<?php

namespace Mmoollllee\FilamentConsentControl\Support;

use Illuminate\Support\HtmlString;
use Mmoollllee\Cms\Support\Shortcodes;

/**
 * Offers the cookie-settings button as a RichEditor merge tag when the site
 * runs on mmoollllee/filament-cms.
 *
 * Editors pick "Cookie-Einstellungen (Button)" from the merge-tag menu; the
 * same key also works as a `[consent_settings]` shortcode in any rich text,
 * with optional `label` and `class` attributes:
 *
 *     [consent_settings label="Cookies verwalten" class="btn btn-primary"]
 *
 * filament-cms is NOT a dependency of this package — it is the CMS that opts
 * into consent control, not the other way round — so registration is guarded
 * and silently does nothing without it.
 */
class CmsMergeTag
{
    /** Merge-tag key and shortcode name. */
    public const TAG = 'consent_settings';

    /**
     * The method check is not paranoia: `registerMergeTag()` arrived later than
     * the CMS itself, and an app that updates this package first would otherwise
     * pass a class_exists() guard and then fatal on the first rendered page.
     */
    public static function isSupported(): bool
    {
        return class_exists(Shortcodes::class)
            && method_exists(Shortcodes::class, 'registerMergeTag');
    }

    /**
     * Register the tag with the CMS. Called from the service provider's boot;
     * safe to call when filament-cms is absent.
     */
    public static function register(): void
    {
        if (! static::isSupported()) {
            return;
        }

        // Inside extendDefaultsUsing(), so the registration survives the
        // Shortcodes::reset() that CMS tests run between cases.
        Shortcodes::extendDefaultsUsing(function (): void {
            Shortcodes::register(static::TAG, static::shortcode(...));

            Shortcodes::registerMergeTag(
                static::TAG,
                (string) __('filament-consent-control::consent.merge_tag_label'),
                fn (): HtmlString => new HtmlString(ConsentSettingsButton::html()),
            );
        });
    }

    /**
     * `[consent_settings label="…" class="…"]`.
     *
     * Shortcodes::parseAttributes() hands attribute values over HTML-ESCAPED,
     * while the button escapes what it renders — so decode first, or an
     * ampersand in the label would end up as `&amp;amp;`.
     *
     * @param  array<string, string>  $attributes
     */
    protected static function shortcode(array $attributes): string
    {
        $decode = fn (?string $value): ?string => filled($value)
            ? html_entity_decode($value, ENT_QUOTES, 'UTF-8')
            : null;

        return ConsentSettingsButton::html(
            label: $decode($attributes['label'] ?? null),
            class: $decode($attributes['class'] ?? null),
        );
    }
}
