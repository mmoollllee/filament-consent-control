<?php

namespace Mmoollllee\FilamentConsentControl\Filament;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ConsentSettingsForm
{
    /**
     * Returns a Filament Group component that stores all consent settings
     * as JSON in the given field name.
     *
     * Usage:
     *   // In your Resource or Page form:
     *   ConsentSettingsForm::make('consent_settings')
     *
     *   // Your model needs a JSON cast:
     *   protected $casts = ['consent_settings' => 'array'];
     */
    public static function make(string $field = 'consent_settings'): Group
    {
        return Group::make([
            Section::make('Cookie Settings')
                ->schema([
                    TextInput::make('cookie.name')
                        ->label('Cookie Name')
                        ->default('consentcontrol')
                        ->maxLength(50),
                    TextInput::make('cookie.days')
                        ->label('Expiry (days)')
                        ->numeric()
                        ->default(365)
                        ->minValue(1),
                    TextInput::make('cookie.domain')
                        ->label('Cookie Domain')
                        ->placeholder('auto-detect'),
                    Toggle::make('cookie.secure')
                        ->label('Secure Cookie')
                        ->default(false),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Banner Settings')
                ->schema([
                    Toggle::make('banner.animated')
                        ->label('Animated')
                        ->default(true),
                    Toggle::make('banner.start_collapsed')
                        ->label('Start Collapsed')
                        ->default(true),
                    Select::make('banner.position')
                        ->label('Position')
                        ->options([
                            'bottom-right' => 'Bottom Right',
                            'bottom-left' => 'Bottom Left',
                            'bottom-full' => 'Bottom Full Width',
                        ])
                        ->default('bottom-right'),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Consent Categories')
                ->schema([
                    Repeater::make('categories')
                        ->schema([
                            TextInput::make('key')
                                ->label('Key')
                                ->required()
                                ->alphaDash()
                                ->placeholder('e.g. analytics'),
                            TextInput::make('label')
                                ->label('Label')
                                ->required()
                                ->placeholder('e.g. Analytics'),
                            Textarea::make('description')
                                ->label('Description')
                                ->rows(2),
                            Toggle::make('checked')
                                ->label('Checked by Default'),
                            Toggle::make('disabled')
                                ->label('Cannot be Unchecked'),
                            Repeater::make('children')
                                ->label('Detail Items')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Label')
                                        ->required(),
                                    Textarea::make('description')
                                        ->label('Description')
                                        ->rows(2),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state) => $state['label'] ?? 'New Item'),
                            Repeater::make('scripts')
                                ->label('Scripts to Load')
                                ->schema([
                                    TextInput::make('src')
                                        ->label('Script URL')
                                        ->required()
                                        ->url(),
                                    Toggle::make('async')
                                        ->label('Async')
                                        ->default(true),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state) => $state['src'] ?? 'New Script'),
                            Textarea::make('inline_script')
                                ->label('Inline JavaScript')
                                ->rows(3)
                                ->placeholder('JavaScript to execute when consent is granted'),
                        ])
                        ->itemLabel(fn (array $state) => $state['label'] ?? $state['key'] ?? 'New Category')
                        ->collapsible()
                        ->reorderable()
                        ->addActionLabel('Add Category'),
                ]),

            Section::make('Links')
                ->schema([
                    TextInput::make('links.privacy')
                        ->label('Privacy Policy URL')
                        ->default('/datenschutz/'),
                    TextInput::make('links.imprint')
                        ->label('Imprint URL')
                        ->default('/impressum/'),
                ])
                ->columns(2)
                ->collapsible(),
        ])->statePath($field);
    }
}
