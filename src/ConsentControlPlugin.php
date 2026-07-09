<?php

namespace Mmoollllee\FilamentConsentControl;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Mmoollllee\FilamentConsentControl\Filament\ConsentSettingsPage;

/**
 * Filament panel plugin.
 *
 * By default it does nothing beyond making the package available — a single
 * config file (config/consent-control.php) is enough to configure everything.
 *
 * Opt in to the database-backed settings editor with:
 *
 *     ConsentControlPlugin::make()->settingsPage()
 *
 * which registers a ready-made page that reads/writes the `eloquent` driver
 * (set CONSENT_CONTROL_DRIVER=eloquent and configure consent-control.eloquent).
 */
class ConsentControlPlugin implements Plugin
{
    protected bool $registersSettingsPage = false;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'consent-control';
    }

    /**
     * Opt in to the ready-made consent settings page in the panel navigation.
     */
    public function settingsPage(bool $condition = true): static
    {
        $this->registersSettingsPage = $condition;

        return $this;
    }

    public function hasSettingsPage(): bool
    {
        return $this->registersSettingsPage;
    }

    public function register(Panel $panel): void
    {
        if ($this->registersSettingsPage) {
            $panel->pages([
                ConsentSettingsPage::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
