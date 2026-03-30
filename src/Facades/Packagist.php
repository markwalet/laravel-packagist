<?php

namespace MarkWalet\Packagist\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\Packagist\PackagistClient;

/**
 * Class Packagist.
 *
 * @method static array<int|string, mixed>|null getPackagesNames(?string $type = null, ?string $vendor = null)
 * @method static array<int|string, mixed>|null getPackagesNamesByType(string $type)
 * @method static array<int|string, mixed>|null getPackagesNamesByVendor(string $vendor)
 * @method static array<int|string, mixed>|null searchPackages($name = null, array<int|string, mixed> $filters = [], ?int $page = 1, int $perPage = 15)
 * @method static array<int|string, mixed>|null searchPackagesByName(string $name, ?int $page = 1, int $perPage = 15)
 * @method static array<int|string, mixed>|null searchPackagesByTags(string $tags, ?string $name = null, ?int $page = 1, int $perPage = 15)
 * @method static array<int|string, mixed>|null searchPackagesByType(string $type, ?string $name = null, ?int $page = 1, int $perPage = 15)
 * @method static array<int|string, mixed>|null getPackage(string $vendor, ?string $package = null)
 * @method static array<int|string, mixed>|null getPackageMetadata(string $vendor, ?string $package = null)
 * @method static array<int|string, mixed>|null getStatistics()
 * @see PackagistClient
 */
class Packagist extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PackagistClient::class;
    }
}
