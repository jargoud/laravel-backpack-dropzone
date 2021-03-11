<?php

namespace Jargoud\LaravelBackpackDropzone\Tests;

use Orchestra\Testbench\TestCase;
use Jargoud\LaravelBackpackDropzone\LaravelBackpackDropzoneServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelBackpackDropzoneServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
