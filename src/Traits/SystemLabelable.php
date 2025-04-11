<?php

namespace Enrisezwolle\FilamentCms\Traits;

use Enrisezwolle\FilamentCms\Models\SystemLabel;
use Illuminate\Database\Eloquent\Model;

trait SystemLabelable
{
    public static function bootSystemLabelable(): void
    {
        static::resolveRelationUsing('systemLabel', function (Model $model) {
            return $model->morphOne(SystemLabel::class, 'labelable');
        });

        static::deleting(function ($model) {
            if ($model->systemLabel()->exists()) {
                throw new \Exception('Cannot delete this '.class_basename($model).' because it has labels attached.');
            }
        });
    }

    public function hasSystemLabel(): bool
    {
        return $this->systemLabel()->exists();
    }
}
