<?php

namespace Mmoollllee\FilamentConsentControl;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Mmoollllee\FilamentConsentControl\Support\CmsMergeTag;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConsentControlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-consent-control';

    public function configurePackage(Package $package): void
    {
        // The frontend runtime, config, its translations and Blade components live
        // in mmoollllee/laravel-consent-control. This package only adds the Filament
        // UI — the strings here are the editorial side (merge-tag label) plus the
        // markup this package generates (the reopen button).
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        // RichEditor plugin JS (loaded on demand by ConsentIframePlugin).
        FilamentAsset::register([
            Js::make(
                'rich-content-plugins/consent-iframe',
                __DIR__.'/../resources/dist/js/rich-content-plugins/consent-iframe.js',
            )->loadedOnRequest(),
        ], package: 'mmoollllee/filament-consent-control');

        // Cookie-settings button as a merge tag / [consent_settings] shortcode —
        // a no-op unless the site runs on filament-cms.
        CmsMergeTag::register();
    }
}
