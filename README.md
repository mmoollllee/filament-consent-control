# Filament Consent Control

Cookie-Consent-Banner und Content-Blocking für Laravel Filament mit AlpineJS.

GDPR-konforme Consent-Verwaltung mit SSR Blade-Templates, Tailwind-Styling und einem Filament RichEditor Plugin zum Einbetten von Iframes.

## Features

- Consent-Banner mit klappbarer UI und Tailwind-Styling
- Konfigurierbare Consent-Kategorien (Notwendige, Analytics, Funktionell, ...)
- Script-Loading und Inline-JS bei Consent-Erteilung
- Iframe-Blocking mit Overlay bis Consent erteilt
- Conditional Content via `<x-consent-control-gate>`
- Filament RichEditor Plugin zum Einbetten von consent-geschützten Iframes
- Einbettbare Filament-Formular-Komponente für Settings
- Flexibles Driver-Pattern: Config-File oder Database
- Mehrsprachig (DE/EN, erweiterbar)
- Cookie-Format abwärtskompatibel zum [consent-control](https://www.npmjs.com/package/consent-control) NPM-Package

## Voraussetzungen

- PHP 8.2+
- Laravel 11.28+
- Filament 5.0+
- AlpineJS (in Filament/Livewire bereits enthalten)
- Tailwind CSS (empfohlen, Standalone-CSS als Fallback verfügbar)

## Installation

```bash
composer require mmoollllee/filament-consent-control
```

Config und Assets publishen:

```bash
php artisan vendor:publish --tag=consent-control-config
php artisan vendor:publish --tag=consent-control-assets
php artisan filament:assets
```

Optional - Database-Driver:

```bash
php artisan vendor:publish --tag=consent-control-migrations
php artisan migrate
```

## Konfiguration

### Config-File (`config/consent-control.php`)

```php
return [
    'driver' => env('CONSENT_CONTROL_DRIVER', 'config'), // 'config' oder 'database'

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
            'label' => 'Notwendige',
            'description' => 'Stellt die Funktionalität der Website sicher.',
            'checked' => true,
            'disabled' => true,
            'children' => [
                ['label' => 'Seiten-Einstellungen', 'description' => '...'],
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
            'label' => 'Funktionell',
        ],
    ],

    'links' => [
        'privacy' => '/datenschutz/',
        'imprint' => '/impressum/',
    ],
];
```

### Database-Driver

In `.env`:

```
CONSENT_CONTROL_DRIVER=database
```

## Frontend-Nutzung

### JS einbinden

**Option A: Über Vite (empfohlen)**

```js
// resources/js/app.js
import '../../vendor/mmoollllee/filament-consent-control/resources/dist/js/consent-control.js';
```

**Option B: Als Script-Tag**

```blade
<x-consent-control-scripts />
```

### Banner

```blade
<x-consent-control-banner />
```

### Iframe mit Consent schützen

```blade
<x-consent-control-message
    consent="functional"
    src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"
    src-name="YouTube"
    :width="560"
    :height="315"
/>
```

### Custom Content mit Consent

```blade
<x-consent-control-message consent="functional" type="custom" src-name="OpenStreetMap">
    <div id="map" style="height: 400px"></div>
</x-consent-control-message>
```

### Bedingte Anzeige

```blade
<x-consent-control-gate consent="analytics">
    <p>Dieser Inhalt wird nur angezeigt wenn Analytics erlaubt ist.</p>
</x-consent-control-gate>
```

### Banner wieder öffnen

```blade
<button @click="$dispatch('consent-control-open')">Cookie-Einstellungen</button>
```

## Filament-Integration

### Plugin registrieren

```php
// app/Providers/Filament/AdminPanelProvider.php
->plugins([
    \Mmoollllee\FilamentConsentControl\ConsentControlPlugin::make(),
])
```

### Settings-Formular einbetten

```php
use Mmoollllee\FilamentConsentControl\Filament\ConsentSettingsForm;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;

class SiteSettings extends Page
{
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            app(ConsentControlManager::class)->getAllConfig()
        );
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            ...ConsentSettingsForm::make(),
        ])->statePath('data');
    }

    public function save(): void
    {
        app(ConsentControlManager::class)->save($this->form->getState());
    }
}
```

### RichEditor Iframe-Plugin

```php
use Filament\Forms\Components\RichEditor;
use Mmoollllee\FilamentConsentControl\Filament\ConsentIframePlugin;

RichEditor::make('body')
    ->plugins([
        ConsentIframePlugin::make(),
    ])
```

## Blade Components

| Component | Beschreibung |
|---|---|
| `<x-consent-control-banner />` | Consent-Banner |
| `<x-consent-control-message consent="..." src="..." />` | Iframe/Content mit Consent-Overlay |
| `<x-consent-control-gate consent="...">` | Zeigt Slot nur bei erteiltem Consent |
| `<x-consent-control-scripts />` | Lädt JS (+ optional CSS) |

### Message-Props

| Prop | Typ | Default | Beschreibung |
|---|---|---|---|
| `consent` | string | *required* | Consent-Kategorie |
| `src` | string | null | Iframe-URL |
| `src-name` | string | auto | Anzeigename |
| `type` | string | `iframe` | `iframe` oder `custom` |
| `width` | int | null | Iframe-Breite |
| `height` | int | null | Iframe-Höhe |

## AlpineJS Events

| Event | Beschreibung |
|---|---|
| `consent-control-open` | Banner öffnen: `$dispatch('consent-control-open')` |
| `consent-updated` | Wird nach Consent-Änderung dispatcht |

## Cookie-Format

```
consentcontrol=necessary|analytics|functional
```

## Lizenz

MIT
