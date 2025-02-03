<?php

namespace MarkWalet\Packagist\Tests;

use Illuminate\Config\Repository;
use MarkWalet\Packagist\Facades\Packagist;
use MarkWalet\Packagist\PackagistServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistServiceProviderTest extends LaravelTestCase
{
    #[Test]
    public function it_binds_a_packagist_client_to_the_application(): void
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(PackagistClient::class, $bindings);

        $result = $this->app->make(PackagistClient::class);
        $this->assertInstanceOf(PackagistClient::class, $result);
    }

    #[Test]
    public function it_binds_a_url_generator_to_the_application(): void
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(PackagistUrlGenerator::class, $bindings);

        $result = $this->app->make(PackagistUrlGenerator::class);
        $this->assertInstanceOf(PackagistUrlGenerator::class, $result);
    }

    #[Test]
    public function the_service_provider_only_loads_when_one_of_the_bound_classes_should_be_injected(): void
    {
        $provider = new PackagistServiceProvider($this->app);

        $result = $provider->provides();

        $this->assertSame([
            PackagistClient::class,
            PackagistUrlGenerator::class,
        ], $result);
    }

    #[Test]
    public function it_registers_a_facade(): void
    {
        $this->app->bind(PackagistClient::class, function () {
            $client = $this->getMockBuilder(PackagistClient::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['getPackageMetadata'])
                ->getMock();

            $client->expects($this->once())
                ->method('getPackageMetadata')
                ->with('markwalet/laravel-packagist')
                ->willReturnCallback(function () {
                    return ['result' => 'ok'];
                });

            return $client;
        });

        $result = Packagist::getPackageMetadata('markwalet/laravel-packagist');

        $this->assertEquals(['result' => 'ok'], $result);
    }

    #[Test]
    public function it_uses_the_default_configuration_when_no_url_is_set(): void
    {
        /** @var PackagistUrlGenerator $generator */
        $generator = $this->app->make(PackagistUrlGenerator::class);

        $apiResult = $generator->make('test.json', PackagistUrlGenerator::API_MODE);
        $repoResult = $generator->make('test.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://packagist.org/test.json', $apiResult);
        $this->assertEquals('https://repo.packagist.org/test.json', $repoResult);
    }

    #[Test]
    public function it_can_override_the_url_configuration(): void
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $config->set('services.packagist', [
            'base_url' => 'https://markwalet.me',
            'repo_url' => 'https://github.com',
        ]);
        /** @var PackagistUrlGenerator $generator */
        $generator = $this->app->make(PackagistUrlGenerator::class);

        $apiResult = $generator->make('test.json', PackagistUrlGenerator::API_MODE);
        $repoResult = $generator->make('test.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://markwalet.me/test.json', $apiResult);
        $this->assertEquals('https://github.com/test.json', $repoResult);
    }
}
