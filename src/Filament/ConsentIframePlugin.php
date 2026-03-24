<?php

namespace Mmoollllee\FilamentConsentControl\Filament;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\HasToolbarButtons;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;
use Mmoollllee\FilamentConsentControl\Extensions\ConsentIframe;

class ConsentIframePlugin implements RichContentPlugin, HasToolbarButtons
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(ConsentIframe::class),
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc(
                'rich-content-plugins/consent-iframe',
                package: 'mmoollllee/filament-consent-control',
            ),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('consentIframe')
                ->action()
                ->icon('heroicon-o-code-bracket'),
        ];
    }

    public function getEditorActions(): array
    {
        $categories = app(ConsentControlManager::class)->getCategories();
        $categoryOptions = collect($categories)->mapWithKeys(fn ($cat, $key) => [
            $key => __($cat['label'] ?? $key),
        ])->toArray();

        return [
            Action::make('consentIframe')
                ->label(__('Iframe einbetten'))
                ->modalWidth(Width::Large)
                ->schema([
                    TextInput::make('src')
                        ->label('URL')
                        ->required()
                        ->url()
                        ->placeholder('https://www.youtube-nocookie.com/embed/...'),
                    Select::make('consent')
                        ->label('Consent-Kategorie')
                        ->options($categoryOptions)
                        ->default('functional')
                        ->required(),
                    TextInput::make('width')
                        ->label('Breite')
                        ->numeric()
                        ->default(640),
                    TextInput::make('height')
                        ->label('Höhe')
                        ->numeric()
                        ->default(480),
                ])
                ->action(function (array $data, RichEditor $component, array $arguments): void {
                    $attrs = [
                        'src' => $data['src'],
                        'data-consent' => $data['consent'],
                        'width' => (int) ($data['width'] ?? 640),
                        'height' => (int) ($data['height'] ?? 480),
                    ];

                    $component->runCommands(
                        [
                            EditorCommand::make('setConsentIframe', arguments: [$attrs]),
                        ],
                        editorSelection: $arguments['editorSelection'] ?? null,
                    );
                }),
        ];
    }

    public function getEnabledToolbarButtons(): array
    {
        return ['consentIframe'];
    }

    public function getDisabledToolbarButtons(): array
    {
        return [];
    }
}
