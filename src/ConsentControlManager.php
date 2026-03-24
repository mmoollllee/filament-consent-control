<?php

namespace Mmoollllee\FilamentConsentControl;

use Mmoollllee\FilamentConsentControl\Contracts\ConfigDriverInterface;
use Mmoollllee\FilamentConsentControl\Drivers\ConfigFileDriver;
use Mmoollllee\FilamentConsentControl\Drivers\EloquentDriver;

class ConsentControlManager
{
    protected ConfigDriverInterface $driver;

    public function __construct()
    {
        $this->driver = $this->resolveDriver();
    }

    public function getCategories(): array
    {
        return $this->driver->getCategories();
    }

    public function getCookieConfig(): array
    {
        return $this->driver->getCookieConfig();
    }

    public function getBannerConfig(): array
    {
        return $this->driver->getBannerConfig();
    }

    public function getLinks(): array
    {
        return $this->driver->getLinks();
    }

    public function getAllConfig(): array
    {
        return $this->driver->getAllConfig();
    }

    public function save(array $data): void
    {
        $this->driver->save($data);
    }

    public function getDriver(): ConfigDriverInterface
    {
        return $this->driver;
    }

    protected function resolveDriver(): ConfigDriverInterface
    {
        return match (config('consent-control.driver', 'config')) {
            'eloquent' => new EloquentDriver,
            default => new ConfigFileDriver,
        };
    }
}
