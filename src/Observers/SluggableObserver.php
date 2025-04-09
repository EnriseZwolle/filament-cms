<?php

namespace Enrisezwolle\FilamentCms\Observers;

use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Illuminate\Database\Eloquent\Model;

class SluggableObserver
{
    public function saved(Model|IsSluggable $model): void
    {
        $model->resourceLookup()->updateOrCreate([], [
            'slug' => $model->getSluggableSlug(),
            'path' => $model->getSluggablePath(),
        ]);

        if ($childrenAttribute = $model->sluggableChildren()) {
            foreach ($model->{$childrenAttribute} as $child) {
                $child->touch();
            }
        }
    }

    public function deleted(Model|IsSluggable $model) {
        $model->resourceLookup()->delete();
    }
}
