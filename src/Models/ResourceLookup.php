<?php

namespace Enrisezwolle\FilamentCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ResourceLookup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    public static function getModelForPath(string $path): ?Model
    {
        return ResourceLookup::query()->firstWhere('path', $path)?->model;
    }
}
