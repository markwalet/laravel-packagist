<?php

namespace MarkWalet\Packagist\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\Packagist\PackagistClient;

/**
 * Class Packagist.
 *
 * @method static array|null getPackagesNames(?string $type = null, ?string $vendor = null)
 * @method static array|null getPackagesNamesByType(string $type)
 * @method static array|null getPackagesNamesByVendor(string $vendor)
 * @method static array|null searchPackages($name = null, array $filters = [], ?int $page = 1, int $perPage = 15)
 * @method static array|null searchPackagesByName(string $name, ?int $page = 1, int $perPage = 15):
 * @method static array|null searchPackagesByTags(string $tags, ?string $name = null, ?int $page = 1, int $perPage = 15)
 * @method static array|null searchPackagesByType(string $type, ?string $name = null, ?int $page = 1, int $perPage = 15)
 * @method static array|null getPackage(string $vendor, ?string $package = null)
 * @method static array|null getPackageMetadata(string $vendor, ?string $package = null)
 * @method static array|null getStatistics()
 * @see PackagistClient
 */
class Packagist extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return PackagistClient::class;
    }
}
