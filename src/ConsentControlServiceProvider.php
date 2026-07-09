<?php

namespace Mmoollllee\FilamentConsentControl;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConsentControlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-consent-control';

    public function configurePackage(Package $package): void
    {
        // The frontend runtime, config, translations and Blade components live in
        // mmoollllee/laravel-consent-control. This package only adds the Filament UI.
        $package
            ->name(static::$name)
            ->hasViews();
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
    }
}
