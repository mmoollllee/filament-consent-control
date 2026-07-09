<?php

namespace Mmoollllee\FilamentConsentControl\Filament;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

/**
 * Reusable Filament schema for editing consent settings.
 *
 * Embed it in your own resource/page (stores all settings as JSON in $field):
 *
 *     ConsentSettingsForm::make('consent_settings')
 *
 * Your model needs a JSON cast: protected $casts = ['consent_settings' => 'array'];
 *
 * Pass no field name when the parent schema already owns the state path
 * (e.g. the bundled ConsentSettingsPage).
 */
class ConsentSettingsForm
{
    public static function make(?string $field = 'consent_settings'): Group
    {
        $group = Group::make([
            Section::make(__('Cookie Settings'))
                ->schema([
                    TextInput::make('cookie.name')
                        ->label(__('Cookie Name'))
                        ->default('consentcontrol')
                        ->maxLength(50),
                    TextInput::make('cookie.days')
                        ->label(__('Expiry (days)'))
                        ->numeric()
                        ->default(365)
                        ->minValue(1),
                    TextInput::make('cookie.domain')
                        ->label(__('Cookie Domain'))
                        ->placeholder(__('auto-detect')),
                    Toggle::make('cookie.secure')
                        ->label(__('Secure Cookie'))
                        ->default(false),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make(__('Banner Settings'))
                ->schema([
                    Toggle::make('banner.reject_button')
                        ->label(__('Show "Reject all" button'))
                        ->helperText(__('Only needed when optional categories are pre-checked. "Reject all" saves only the mandatory (locked) categories.'))
                        ->default(false),
                ])
                ->collapsible(),

            Section::make(__('Consent Categories'))
                ->schema([
                    Repeater::make('categories')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('key')
                                ->label(__('Key'))
                                ->required()
                                ->alphaDash()
                                ->placeholder('e.g. analytics'),
                            TextInput::make('label')
                                ->label(__('Label'))
                                ->required()
                                ->placeholder('e.g. Analytics')
                                ->helperText(__('Plain text or a translation key.')),
                            Textarea::make('description')
                                ->label(__('Description'))
                                ->rows(2),
                            Toggle::make('checked')
                                ->label(__('Checked by default')),
                            Toggle::make('disabled')
                                ->label(__('Cannot be unchecked')),
                            Repeater::make('children')
                                ->label(__('Detail items'))
                                ->schema([
                                    TextInput::make('label')
                                        ->label(__('Label'))
                                        ->required(),
                                    Textarea::make('description')
                                        ->label(__('Description'))
                                        ->rows(2),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => $state['label'] ?? __('New item')),
                            Repeater::make('scripts')
                                ->label(__('Scripts to load on consent'))
                                ->schema([
                                    TextInput::make('src')
                                        ->label(__('Script URL'))
                                        ->required()
                                        ->url(),
                                    Toggle::make('async')
                                        ->label(__('Async'))
                                        ->default(true),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => $state['src'] ?? __('New script')),
                            Textarea::make('inline_script')
                                ->label(__('Inline JavaScript'))
                                ->rows(3)
                                ->placeholder(__('Executed once when consent is granted')),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['key'] ?? __('New category'))
                        ->collapsible()
                        ->reorderable()
                        ->addActionLabel(__('Add category')),
                ]),

            Section::make(__('Links'))
                ->schema([
                    TextInput::make('links.privacy')
                        ->label(__('Privacy Policy URL'))
                        ->default('/datenschutz/'),
                    TextInput::make('links.imprint')
                        ->label(__('Imprint URL'))
                        ->default('/impressum/'),
                ])
                ->columns(2)
                ->collapsible(),
        ]);

        return $field === null ? $group : $group->statePath($field);
    }
}
