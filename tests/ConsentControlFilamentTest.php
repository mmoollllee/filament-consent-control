<?php

use Filament\Schemas\Components\Group;
use Mmoollllee\FilamentConsentControl\ConsentControlPlugin;
use Mmoollllee\FilamentConsentControl\Extensions\ConsentIframe;
use Mmoollllee\FilamentConsentControl\Filament\ConsentIframePlugin;
use Mmoollllee\FilamentConsentControl\Filament\ConsentSettingsForm;

it('keeps the settings page opt-in', function () {
    expect(ConsentControlPlugin::make()->hasSettingsPage())->toBeFalse()
        ->and(ConsentControlPlugin::make()->settingsPage()->hasSettingsPage())->toBeTrue();
});

it('builds the settings form schema', function () {
    expect(ConsentSettingsForm::make())->toBeInstanceOf(Group::class)
        ->and(ConsentSettingsForm::make(null))->toBeInstanceOf(Group::class);
});

it('renders RichEditor iframes as blocked consent-message markup', function () {
    $node = (object) [
        'attrs' => (object) [
            'src' => 'https://www.youtube-nocookie.com/embed/x',
            'data-consent' => 'functional',
            'width' => 640,
            'height' => 480,
        ],
    ];

    $html = app(ConsentIframe::class)->renderHTML($node);

    expect($html[0])->toBe('div')
        ->and($html[1]['class'])->toContain('consent-message--wrapper')
        ->and($html[1]['data-consent'])->toBe('functional')
        ->and($html[2][0])->toBe('iframe')
        ->and($html[2][1]['data-src'])->toBe('https://www.youtube-nocookie.com/embed/x')
        ->and($html[2][1])->not->toHaveKey('src');
});

it('normalizes YouTube and Vimeo URLs to privacy-friendly embeds', function () {
    expect(ConsentIframePlugin::normalizeEmbedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ'))
        ->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
        ->and(ConsentIframePlugin::normalizeEmbedUrl('https://youtu.be/dQw4w9WgXcQ'))
        ->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
        ->and(ConsentIframePlugin::normalizeEmbedUrl('https://vimeo.com/123456789'))
        ->toBe('https://player.vimeo.com/video/123456789')
        // already a nocookie embed (valid 11-char id) → unchanged
        ->and(ConsentIframePlugin::normalizeEmbedUrl('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ'))
        ->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
        // unknown provider (Maps, …) → unchanged
        ->and(ConsentIframePlugin::normalizeEmbedUrl('https://maps.example.com/embed?x=1'))
        ->toBe('https://maps.example.com/embed?x=1');
});
