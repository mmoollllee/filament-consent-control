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
use Mmoollllee\FilamentConsentControl\Extensions\ConsentIframe;
use Mmoollllee\LaravelConsentControl\ConsentControlManager;

class ConsentIframePlugin implements HasToolbarButtons, RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Turn a pasted share/watch URL into a privacy-friendly embed URL.
     * YouTube → youtube-nocookie embed, Vimeo → player embed. Anything else
     * (Maps, …) is returned unchanged — paste its embed URL directly.
     */
    public static function normalizeEmbedUrl(string $url): string
    {
        $url = trim($url);

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/|v/)|youtu\.be/)([\w-]{11})~i', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/{$m[1]}";
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return $url;
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
        $categoryOptions = collect($categories)
            ->mapWithKeys(fn ($category, $key) => [$key => __($category['label'] ?? $key)])
            ->all();

        return [
            Action::make('consentIframe')
                ->label(__('Embed iframe'))
                ->modalWidth(Width::Large)
                ->schema([
                    TextInput::make('src')
                        ->label(__('URL'))
                        ->required()
                        ->url()
                        ->placeholder('https://www.youtube-nocookie.com/embed/...'),
                    Select::make('consent')
                        ->label(__('Consent category'))
                        ->options($categoryOptions)
                        ->default('functional')
                        ->required(),
                    TextInput::make('width')
                        ->label(__('Width'))
                        ->numeric()
                        ->default(640),
                    TextInput::make('height')
                        ->label(__('Height'))
                        ->numeric()
                        ->default(480),
                ])
                ->action(function (array $data, RichEditor $component, array $arguments): void {
                    $attributes = [
                        'src' => static::normalizeEmbedUrl($data['src']),
                        'data-consent' => $data['consent'],
                        'width' => (int) ($data['width'] ?? 640),
                        'height' => (int) ($data['height'] ?? 480),
                    ];

                    $component->runCommands(
                        [
                            EditorCommand::make('setConsentIframe', arguments: [$attributes]),
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
