<?php

namespace Mmoollllee\FilamentConsentControl;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ConsentControlPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'consent-control';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
