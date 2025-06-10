<?php

namespace Enrisezwolle\FilamentCms\Observers;

use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Enrisezwolle\FilamentCms\Contracts\IsTranslatable;
use Enrisezwolle\FilamentCms\Models\ResourceLookup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SluggableObserver
{
    public function saved(Model|IsSluggable $model): void
    {
        $locale = $this->getLocale($model);

        $this->ensurePathIsUnique($model, $locale);

        $model->resourceLookup()->updateOrCreate([], [
            'locale' => $locale,
            'slug' => $model->getSluggableSlug(),
            'path' => $model->getSluggablePath(),
        ]);

        if ($childrenAttribute = $model->sluggableChildren()) {
            foreach ($model->{$childrenAttribute} as $child) {
                $child->touch();
            }
        }
    }

    protected function getLocale(Model|IsSluggable $model): ?string
    {
        if ($model instanceof IsTranslatable) {
            return $model->getTranslatableLocale();
        }

        return null;
    }

    protected function ensurePathIsUnique(Model|IsSluggable &$model, ?string $locale): void
    {
        $pathExists = ResourceLookup::query()
            ->whereNotMorphedTo('model', $model)
            ->where('path', $model->getSluggablePath())
            ->when(is_null($locale), fn (Builder $builder) => $builder->whereNull('locale'))
            ->when(! is_null($locale), fn (Builder $builder) => $builder->where('locale', $locale))
            ->exists();

        if ($pathExists) {
            /** @var ResourceLookup $latestIncrement */
            $latestIncrement = ResourceLookup::query()
                ->whereRaw("path REGEXP '^{$model->getSluggablePath()}-[0-9]+$'")
                ->orderByDesc('path')
                ->when(is_null($locale), fn (Builder $builder) => $builder->whereNull('locale'))
                ->when(! is_null($locale), fn (Builder $builder) => $builder->where('locale', $locale))
                ->first();

            $newSlug = $model->getSluggableSlug().'-1';

            if (filled($latestIncrement)) {
                $integer = Str::of($latestIncrement->path)
                    ->after($model->getSluggablePath())
                    ->after('-')
                    ->toInteger();

                $newSlug = $model->getSluggableSlug().'-'.$integer + 1;
            }

            $model->update([$model->sluggableAttribute() => $newSlug]);
        }
    }

    public function deleted(Model|IsSluggable $model)
    {
        $model->resourceLookup()->delete();
    }
}
