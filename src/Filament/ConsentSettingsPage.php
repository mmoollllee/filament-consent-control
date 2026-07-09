<?php

namespace Mmoollllee\FilamentConsentControl\Filament;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Mmoollllee\LaravelConsentControl\ConsentControlManager;

/**
 * Ready-made settings page. Registered only when the plugin opts in via
 * ConsentControlPlugin::make()->settingsPage(). Reads/writes through the
 * configured driver (use the "eloquent" driver to persist changes).
 */
class ConsentSettingsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected string $view = 'filament-consent-control::consent-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $config = app(ConsentControlManager::class)->getAllConfig();

        // The repeater expects a list with an explicit `key` per category.
        $config['categories'] = collect($config['categories'] ?? [])
            ->map(fn ($category, $key) => array_merge(['key' => $key], (array) $category))
            ->values()
            ->all();

        $this->form->fill($config);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ConsentSettingsForm::make(null),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            app(ConsentControlManager::class)->save($data);

            Notification::make()
                ->title(__('Consent settings saved'))
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title(__('Could not save consent settings'))
                ->body($e->getMessage().' '.__('Set CONSENT_CONTROL_DRIVER=eloquent to enable saving.'))
                ->danger()
                ->send();
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('Consent');
    }

    public function getTitle(): string
    {
        return __('Consent Settings');
    }
}
