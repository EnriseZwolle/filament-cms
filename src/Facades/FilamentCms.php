<?php

namespace Enrisezwolle\FilamentCms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Enrisezwolle\FilamentCms\FilamentCms
 */
class FilamentCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Enrisezwolle\FilamentCms\FilamentCms::class;
    }
}
