<?php

namespace Jargoud\LaravelBackpackDropzone;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jargoud\LaravelBackpackDropzone\Skeleton\SkeletonClass
 */
class LaravelBackpackDropzoneFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-backpack-dropzone';
    }
}
