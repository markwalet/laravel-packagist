<?php

namespace MarkWalet\Packagist\Tests;

use Illuminate\Foundation\Application;
use MarkWalet\Packagist\PackagistServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            PackagistServiceProvider::class,
        ];
    }
}
