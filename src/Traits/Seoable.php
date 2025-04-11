<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Traits;

use Enrisezwolle\FilamentCms\Models\Seo;
use Illuminate\Database\Eloquent\Model;

trait Seoable
{
    public static function bootSeoable(): void
    {
        static::resolveRelationUsing('seo', function (Model $model) {
            return $model->morphOne(Seo::class, 'seoable');
        });
    }
}
