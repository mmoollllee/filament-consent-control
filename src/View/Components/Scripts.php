<?php

namespace Mmoollllee\FilamentConsentControl\View\Components;

use Illuminate\View\Component;

class Scripts extends Component
{
    public bool $standaloneCss;

    public function __construct(bool $standaloneCss = false)
    {
        $this->standaloneCss = $standaloneCss;
    }

    public function render()
    {
        return view('consent-control::components.scripts');
    }
}
