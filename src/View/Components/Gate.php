<?php

namespace Mmoollllee\FilamentConsentControl\View\Components;

use Illuminate\View\Component;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;

class Gate extends Component
{
    public string $consent;

    public string $cookieName;

    public function __construct(string $consent)
    {
        $this->consent = $consent;
        $this->cookieName = app(ConsentControlManager::class)->getCookieConfig()['name'] ?? 'consentcontrol';
    }

    public function render()
    {
        return view('consent-control::components.gate');
    }
}
