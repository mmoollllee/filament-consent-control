<?php

namespace Mmoollllee\FilamentConsentControl\Drivers;

use Mmoollllee\FilamentConsentControl\Contracts\ConfigDriverInterface;

class ConfigFileDriver implements ConfigDriverInterface
{
    public function getCategories(): array
    {
        return config('consent-control.categories', []);
    }

    public function getCookieConfig(): array
    {
        return config('consent-control.cookie', [
            'name' => 'consentcontrol',
            'days' => 365,
        ]);
    }

    public function getBannerConfig(): array
    {
        return config('consent-control.banner', []);
    }

    public function getLinks(): array
    {
        return config('consent-control.links', []);
    }

    public function getAllConfig(): array
    {
        return [
            'cookie' => $this->getCookieConfig(),
            'banner' => $this->getBannerConfig(),
            'categories' => $this->getCategories(),
            'links' => $this->getLinks(),
        ];
    }

    public function save(array $data): void
    {
        throw new \RuntimeException(
            'Cannot save settings with the config file driver. Use the database driver or edit config/consent-control.php directly.'
        );
    }
}
