<?php

namespace Mmoollllee\FilamentConsentControl\Facades;

use Illuminate\Support\Facades\Facade;
use Mmoollllee\FilamentConsentControl\ConsentControlManager;

/**
 * @method static array getCategories()
 * @method static array getCookieConfig()
 * @method static array getBannerConfig()
 * @method static array getLinks()
 * @method static array getAllConfig()
 * @method static void save(array $data)
 *
 * @see \Mmoollllee\FilamentConsentControl\ConsentControlManager
 */
class ConsentControl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConsentControlManager::class;
    }
}
