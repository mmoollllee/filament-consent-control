<?php

namespace Mmoollllee\FilamentConsentControl\Contracts;

interface ConfigDriverInterface
{
    public function getCategories(): array;

    public function getCookieConfig(): array;

    public function getBannerConfig(): array;

    public function getLinks(): array;

    public function getAllConfig(): array;

    public function save(array $data): void;
}
