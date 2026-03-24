# Filament Consent Control

Cookie consent banner and content blocking for Laravel Filament with AlpineJS.

GDPR-compliant consent management with SSR Blade templates, Tailwind styling and a Filament RichEditor plugin for embedding iframes.

## Features

- Consent banner with collapsible UI and Tailwind styling
- Configurable consent categories (Necessary, Analytics, Functional, ...)
- Script loading and inline JS on consent
- Iframe blocking with overlay until consent is granted
- Conditional content via `<x-consent-control-gate>`
- Filament RichEditor plugin for embedding consent-protected iframes
- Embeddable Filament form component for settings
- Flexible driver pattern: config file or Eloquent (JSON field)
- Multi-language (DE/EN, extensible)
- Cookie format backwards-compatible with the [consent-control](https://www.npmjs.com/package/consent-control) NPM package

## Requirements

- PHP 8.2+
- Laravel 11.28+
- Filament 5.0+
- AlpineJS (included in Filament/Livewire)
- Tailwind CSS

## Installation

Since the package is not yet published on Packagist, add the GitHub repository and install:

```bash
composer config repositories.filament-consent-control vcs https://github.com/mmoollllee/filament-consent-control
composer require mmoollllee/filament-consent-control:dev-main
```

Publish config and assets:

```bash
php artisan vendor:publish --tag=consent-control-config
php artisan vendor:publish --tag=consent-control-assets
```

Add the package views as a Tailwind source so utility classes used by the banner are included in your CSS build:

```css
/* resources/css/app.css */
@source '../../vendor/mmoollllee/filament-consent-control/resources/views/components/**/*.blade.php';
```

## Configuration

### Config File (`config/consent-control.php`)

```php
return [
    'driver' => env('CONSENT_CONTROL_DRIVER', 'config'), // 'config' or 'eloquent'

    'cookie' => [
        'name' => 'consentcontrol',
        'days' => 365,
        'domain' => parse_url(config('app.url', ''), PHP_URL_HOST),
    ],

    'banner' => [
        'animated' => true,
        'start_collapsed' => true,
        'position' => 'bottom-right',
    ],

    'categories' => [
        'necessary' => [
            'label' => 'Necessary',
            'description' => 'Ensures the functionality of the website.',
            'checked' => true,
            'disabled' => true,
            'children' => [
                ['label' => 'Site Settings', 'description' => '...'],
            ],
            'scripts' => [],
            'inline_script' => null,
        ],
        'analytics' => [
            'label' => 'Analytics',
            'scripts' => [
                ['src' => 'https://www.googletagmanager.com/gtag/js?id=G-XXXXX', 'async' => true],
            ],
            'inline_script' => "window.dataLayer = window.dataLayer || [];",
        ],
        'functional' => [
            'label' => 'Functional',
        ],
    ],

    'links' => [
        'privacy' => '/privacy/',
    ],
];
```

### Eloquent Driver

Store consent settings as JSON in any of your existing models instead of a separate table.

In `.env`:

```
CONSENT_CONTROL_DRIVER=eloquent
```

In `config/consent-control.php`:

```php
'eloquent' => [
    'model' => App\Models\Setting::class,
    'field' => 'consent_settings',
    'record_id' => 1,
],
```

Your model needs a JSON cast:

```php
protected $casts = ['consent_settings' => 'array'];
```

## Frontend Usage

### Include JS

**Option A: Via Vite (recommended)**

```js
// resources/js/app.js
import '../../vendor/mmoollllee/filament-consent-control/resources/dist/js/consent-control.js';
```

**Option B: As script tag**

```blade
<x-consent-control-scripts />
```

### Banner

```blade
<x-consent-control-banner />
```

### Protect Iframe with Consent

```blade
<x-consent-control-message
    consent="functional"
    src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"
    src-name="YouTube"
    :width="560"
    :height="315"
/>
```

### Custom Content with Consent

```blade
<x-consent-control-message consent="functional" type="custom" src-name="OpenStreetMap">
    <div id="map" style="height: 400px"></div>
</x-consent-control-message>
```

### Conditional Display

```blade
<x-consent-control-gate consent="analytics">
    <p>This content is only shown when analytics consent is granted.</p>
</x-consent-control-gate>
```

### Re-open Banner

```blade
<button @click="$dispatch('consent-control-open')">Cookie Settings</button>
```

## Filament Integration

### Register Plugin

```php
// app/Providers/Filament/AdminPanelProvider.php
->plugins([
    \Mmoollllee\FilamentConsentControl\ConsentControlPlugin::make(),
])
```

### Embed Settings Form

The `ConsentSettingsForm::make()` returns a Filament form group that stores all settings as JSON in a single field on your model.

```php
use Mmoollllee\FilamentConsentControl\Filament\ConsentSettingsForm;

class SiteSettingsResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            ConsentSettingsForm::make('consent_settings'),
            // ... other fields
        ]);
    }
}
```

### RichEditor Iframe Plugin

```php
use Filament\Forms\Components\RichEditor;
use Mmoollllee\FilamentConsentControl\Filament\ConsentIframePlugin;

RichEditor::make('body')
    ->plugins([
        ConsentIframePlugin::make(),
    ])
```

## Blade Components

| Component | Description |
|---|---|
| `<x-consent-control-banner />` | Consent banner |
| `<x-consent-control-message consent="..." src="..." />` | Iframe/content with consent overlay |
| `<x-consent-control-gate consent="...">` | Shows slot only when consent is granted |
| `<x-consent-control-scripts />` | Loads JS |

### Message Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `consent` | string | *required* | Consent category |
| `src` | string | null | Iframe URL |
| `src-name` | string | auto | Display name |
| `type` | string | `iframe` | `iframe` or `custom` |
| `width` | int | null | Iframe width |
| `height` | int | null | Iframe height |

## AlpineJS Events

| Event | Description |
|---|---|
| `consent-control-open` | Open banner: `$dispatch('consent-control-open')` |
| `consent-updated` | Dispatched after consent changes |

## Cookie Format

```
consentcontrol=necessary|analytics|functional
```

## License

MIT
