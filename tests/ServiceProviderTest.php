<?php

namespace Tests;

use Laragear\MetaTesting\InteractsWithServiceProvider;

class ServiceProviderTest extends TestCase
{
    use InteractsWithServiceProvider;

    public function test_registers_command(): void
    {
        $this->assertHasCommand('ui');
    }
}
