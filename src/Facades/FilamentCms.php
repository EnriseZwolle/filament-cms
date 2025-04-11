<?php

namespace Enrisezwolle\FilamentCms\Facades;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string hashModel(string $className, mixed $id)
 * @method static Fieldset visibilityFields(bool $visibility = true, bool $publishable = true, bool $publishFromRequired = false, bool $publishUntilRequired = false)
 * @method static Group seoFields()
 *
 * @see \Enrisezwolle\FilamentCms\FilamentCms
 */
class FilamentCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Enrisezwolle\FilamentCms\FilamentCms::class;
    }
}
