# Filament Consent Control

The **Filament admin layer** for [consent-control](https://github.com/mmoollllee/consent-control):
an opt-in settings page/form and a RichEditor plugin for embedding consent-gated iframes.

Part of a three-package stack:

| Package | Role |
|---|---|
| [`consent-control`](https://github.com/mmoollllee/consent-control) (npm) | Framework-agnostic runtime + CSS. |
| [`laravel-consent-control`](https://github.com/mmoollllee/laravel-consent-control) | Blade components, config, drivers, translations, server helpers. |
| **`filament-consent-control`** (this package) | Filament settings UI + RichEditor consent-iframe plugin. |

The frontend banner, blocking and configuration come from `laravel-consent-control`
(installed automatically). **A single config file is enough** — this package only adds
optional admin convenience on top.

## Requirements

- PHP 8.2+ · Laravel 11.28+ / 12 · Filament 5

## Installation

```bash
composer require mmoollllee/filament-consent-control
```

This pulls in `laravel-consent-control`. Publish its config and runtime assets, then set
up the frontend as described in [its README](https://github.com/mmoollllee/laravel-consent-control):

```bash
php artisan vendor:publish --tag=consent-control-config
php artisan vendor:publish --tag=consent-control-assets
```

Register the plugin in your panel (does nothing by itself unless you opt in below):

```php
use Mmoollllee\FilamentConsentControl\ConsentControlPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugins([
        ConsentControlPlugin::make(),
    ]);
}
```

## Editing settings in Filament (opt-in)

By default settings are read from `config/consent-control.php`. To edit them at runtime,
opt in to the **eloquent driver** and one of the two admin options below.

```env
CONSENT_CONTROL_DRIVER=eloquent
```

```php
// config/consent-control.php
'eloquent' => [
    'model' => App\Models\Setting::class,   // needs: $casts = ['consent_settings' => 'array']
    'field' => 'consent_settings',
    'record_id' => 1,
],
```

### Option A — ready-made settings page

```php
ConsentControlPlugin::make()->settingsPage();
```

Adds a "Consent Settings" page to the panel that reads/writes the configured model.

### Option B — embed the form in your own resource/page

```php
use Mmoollllee\FilamentConsentControl\Filament\ConsentSettingsForm;

public function form(Schema $schema): Schema
{
    return $schema->components([
        ConsentSettingsForm::make('consent_settings'),
        // ... your other fields
    ]);
}
```

## RichEditor: embed consent-gated iframes

```php
use Filament\Forms\Components\RichEditor;
use Mmoollllee\FilamentConsentControl\Filament\ConsentIframePlugin;

RichEditor::make('body')
    ->plugins([
        ConsentIframePlugin::make(),
    ]);
```

The toolbar gets an "Embed iframe" action (URL, consent category, width, height). Pasted
YouTube/Vimeo links are auto-converted to privacy-friendly embed URLs (YouTube → nocookie). Stored
iframes are rendered on the frontend as blocked `.consent-message--wrapper` markup
(`data-src`) and only load once the visitor grants the chosen category — handled by the
shared runtime, so make sure `<x-consent-control-scripts />` is on the page.

## License

MIT. See [LICENSE.md](LICENSE.md).
