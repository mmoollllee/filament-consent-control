<?php

namespace Mmoollllee\FilamentConsentControl\View\Components;

use Illuminate\View\Component;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;

class Banner extends Component
{
    public array $categories;

    public array $cookie;

    public array $banner;

    public array $links;

    public function __construct(
        ?array $categories = null,
        ?array $cookie = null,
    ) {
        $manager = app(ConsentControlManager::class);
        $this->categories = $categories ?? $manager->getCategories();
        $this->cookie = $cookie ?? $manager->getCookieConfig();
        $this->banner = $manager->getBannerConfig();
        $this->links = $manager->getLinks();
    }

    public function render()
    {
        return view('consent-control::components.banner');
    }
}
