<?php

namespace MarkWalet\Packagist;

use GuzzleHttp\Client;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind manager to application.
        $this->app->bind(PackagistClient::class, function (Application $app) {
            $urlGenerator = $app->make(PackagistUrlGenerator::class);

            return new PackagistClient(new Client(), $urlGenerator);
        });

        $this->app->bind(PackagistUrlGenerator::class, function (Application $app) {
            /** @var Repository $config */
            $config = $app['config'];

            return new PackagistUrlGenerator(
                $config->get('services.packagist.base_url', null),
                $config->get('services.packagist.repo_url', null)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            PackagistClient::class,
            PackagistUrlGenerator::class,
        ];
    }
}
