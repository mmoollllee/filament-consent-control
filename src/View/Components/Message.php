<?php

namespace Mmoollllee\FilamentConsentControl\View\Components;

use Illuminate\View\Component;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;

class Message extends Component
{
    public string $consent;

    public ?string $src;

    public ?string $srcName;

    public string $type;

    public ?int $width;

    public ?int $height;

    public array $cookie;

    public function __construct(
        string $consent,
        ?string $src = null,
        ?string $srcName = null,
        string $type = 'iframe',
        ?int $width = null,
        ?int $height = null,
    ) {
        $this->consent = $consent;
        $this->src = $src;
        $this->srcName = $srcName ?: ($src ? parse_url($src, PHP_URL_HOST) : null);
        $this->type = $type;
        $this->width = $width;
        $this->height = $height;
        $this->cookie = app(ConsentControlManager::class)->getCookieConfig();
    }

    public function render()
    {
        return view('consent-control::components.message');
    }
}
