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

## Frontend assets

The banner, content blocking and JS runtime come from `laravel-consent-control` (pulled in
automatically) — this package only adds the Filament admin + the RichEditor iframe plugin.

Recommended: **bundle the runtime yourself** so JS/CSS ship with your Vite build instead of
extra requests, and render only the boot config on your site layout:

```js
// resources/js/app.js
import '../../vendor/mmoollllee/laravel-consent-control/resources/dist/js/consent-control.js';
```

```css
/* resources/css/app.css — overlay CSS + let Tailwind style the banner Blade */
@import '../../vendor/mmoollllee/laravel-consent-control/resources/dist/css/consent-message.css';
@source '../../vendor/mmoollllee/laravel-consent-control/resources/views/components/**/*.blade.php';
```

```blade
{{-- once per page, e.g. before </body> --}}
<x-consent-control-banner />
<x-consent-control-scripts :assets="false" />
```

All options (published assets, no-Tailwind fallback, view publishing) are documented in the
[`laravel-consent-control` README](https://github.com/mmoollllee/laravel-consent-control#frontend-assets-choose-one).

To let visitors **reopen the banner** (e.g. from the privacy policy page), place a button
with the `consent-control--open` class anywhere — the runtime binds it automatically:

```html
<button type="button" class="consent-control--open">Cookie-Einstellungen ändern</button>
```

### Merge tag for editors (filament-cms)

On a site running [`mmoollllee/filament-cms`](https://github.com/mmoollllee/filament-cms) that
button is available to editors without typing HTML: the package registers itself with the
CMS shortcode registry on boot — nothing to wire up.

- **RichEditor** — pick *"Cookie-Einstellungen (Button)"* from the merge-tag menu.
- **Any rich text** — write the `[consent_settings]` shortcode, optionally with a custom
  label and extra CSS classes (the trigger class is always kept):

  ```text
  [consent_settings]
  [consent_settings label="Cookies verwalten" class="btn btn-primary"]
  ```

The label defaults to `filament-consent-control::consent.reopen_button` — publish the
translations to change it globally:

```bash
php artisan vendor:publish --tag=filament-consent-control-translations
```

Without filament-cms (or on a version older than `Shortcodes::registerMergeTag()`) nothing is
registered; `Support\ConsentSettingsButton::html()` renders the same markup for your own views.

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
ConsentControlPlugin::make()
    ->settingsPage();
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
