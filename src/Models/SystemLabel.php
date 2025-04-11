<?php

namespace Enrisezwolle\FilamentCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SystemLabel extends Model
{
    protected $guarded = [];

    public static function getModel(string $label): ?Model
    {
        return static::query()->firstWhere('label', $label)?->model;
    }

    public function model(): MorphTo
    {
        return $this->morphTo('labelable');
    }
}
