<?php

namespace Enrisezwolle\FilamentCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    protected $guarded = [];

    protected $table = 'seo';

    protected $casts = [
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
