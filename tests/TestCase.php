<?php

namespace Tests;

use Laragear\ArtisanUi\ArtisanUiServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ArtisanUiServiceProvider::class];
    }
}
