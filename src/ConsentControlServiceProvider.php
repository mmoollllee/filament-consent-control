<?php

namespace Mmoollllee\FilamentConsentControl;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Blade;
use Mmoollllee\FilamentConsentControl\View\Components\Banner;
use Mmoollllee\FilamentConsentControl\View\Components\Gate;
use Mmoollllee\FilamentConsentControl\View\Components\Message;
use Mmoollllee\FilamentConsentControl\View\Components\Scripts;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConsentControlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'consent-control';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasAssets();
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton(ConsentControlManager::class);
    }

    public function packageBooted(): void
    {
        // Register JS with Filament's asset system
        FilamentAsset::register([
            Js::make('consent-control', __DIR__.'/../resources/dist/js/consent-control.js'),
            Js::make('rich-content-plugins/consent-iframe', __DIR__.'/../resources/dist/js/rich-content-plugins/consent-iframe.js')
                ->loadedOnRequest(),
        ], package: 'mmoollllee/filament-consent-control');

        // Blade Components (for frontend usage)
        $this->loadViewComponentsAs('consent-control', [
            Banner::class,
            Message::class,
            Gate::class,
            Scripts::class,
        ]);

        // Blade directive shortcut
        Blade::directive('consentScripts', function () {
            return "<?php echo view('consent-control::components.scripts')->render(); ?>";
        });
    }
}
