<?php

use Mmoollllee\FilamentConsentControl\Support\CmsMergeTag;
use Mmoollllee\FilamentConsentControl\Support\ConsentSettingsButton;

it('renders the trigger class the frontend runtime binds to', function () {
    // The runtime binds by class alone — lose it and the button is dead markup.
    expect(ConsentSettingsButton::html())
        ->toContain('class="consent-control--open"')
        ->toContain('type="button"')
        ->toContain('Change cookie settings');
});

it('translates the default label', function () {
    app()->setLocale('de');

    expect(ConsentSettingsButton::html())->toContain('Cookie-Einstellungen ändern');
});

it('takes a custom label and appends project classes to the trigger class', function () {
    $html = ConsentSettingsButton::html('Cookies verwalten', 'btn btn-primary');

    expect($html)->toContain('class="consent-control--open btn btn-primary"')
        ->toContain('>Cookies verwalten<');
});

it('escapes what an editor typed', function () {
    $html = ConsentSettingsButton::html('<script>alert(1)</script>', '" onclick="alert(1)');

    // The quotes are escaped, so the injected handler stays INSIDE the class
    // value instead of becoming an attribute of its own.
    expect($html)->not->toContain('<script>')
        ->and($html)->toContain('&lt;script&gt;')
        ->and($html)->toContain('&quot; onclick=&quot;')
        ->and($html)->toStartWith('<button type="button" class="consent-control--open');
});

it('skips the CMS registration when filament-cms is not installed', function () {
    // This package must stay usable without the CMS: it is the CMS that opts
    // into consent control, not the other way round.
    expect(CmsMergeTag::isSupported())->toBeFalse();

    CmsMergeTag::register(); // must not throw
})->skip(fn (): bool => CmsMergeTag::isSupported(), 'filament-cms is installed here.');
