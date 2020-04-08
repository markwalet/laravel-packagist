<?php

namespace MarkWalet\Packagist\Tests;

use Illuminate\Config\Repository;
use MarkWalet\Packagist\Facades\Packagist;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistServiceProviderTest extends LaravelTestCase
{
    /** @test */
    public function it_binds_a_packagist_manager_to_the_application()
    {
        $bindings = $this->app->getBindings();

        $this->assertArrayHasKey(PackagistClient::class, $bindings);
    }

    /** @test */
    public function it_registers_a_facade()
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

    /** @test */
    public function it_uses_the_default_configuration_when_no_url_is_set()
    {
        /** @var PackagistUrlGenerator $generator */
        $generator = $this->app->make(PackagistUrlGenerator::class);

        $apiResult = $generator->make('test.json', PackagistUrlGenerator::API_MODE);
        $repoResult = $generator->make('test.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://packagist.org/test.json', $apiResult);
        $this->assertEquals('https://repo.packagist.org/test.json', $repoResult);
    }

    /** @test */
    public function it_can_override_the_url_configuration()
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $config->set('services.packagist', [
            'base_url' => 'https://markwalet.me',
            'repo_url' => 'https://github.com'
        ]);
        /** @var PackagistUrlGenerator $generator */
        $generator = $this->app->make(PackagistUrlGenerator::class);

        $apiResult = $generator->make('test.json', PackagistUrlGenerator::API_MODE);
        $repoResult = $generator->make('test.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://markwalet.me/test.json', $apiResult);
        $this->assertEquals('https://github.com/test.json', $repoResult);
    }
}
