<?php

namespace Mmoollllee\FilamentConsentControl\Support;

/**
 * The markup that reopens the consent banner.
 *
 * The frontend runtime (laravel-consent-control) binds every element carrying
 * `consent-control--open` on load, so a plain button is all it takes — no id,
 * no inline handler. Editors get it as a merge tag / shortcode instead of
 * having to type HTML into the source view: {@see CmsMergeTag}.
 */
class ConsentSettingsButton
{
    /** The hook the frontend runtime binds to — never rename without the runtime. */
    public const TRIGGER_CLASS = 'consent-control--open';

    /**
     * @param  string|null  $label  visible text, RAW (escaped here); defaults to the package translation
     * @param  string|null  $class  extra classes appended to the trigger class, e.g. a project button style
     */
    public static function html(?string $label = null, ?string $class = null): string
    {
        $label = filled($label) ? $label : (string) __('filament-consent-control::consent.reopen_button');

        $classes = filled($class)
            ? static::TRIGGER_CLASS.' '.$class
            : static::TRIGGER_CLASS;

        return sprintf(
            '<button type="button" class="%s">%s</button>',
            e($classes),
            e($label),
        );
    }
}
