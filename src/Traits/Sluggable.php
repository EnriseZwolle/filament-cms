<?php

namespace Enrisezwolle\FilamentCms\Traits;

use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Enrisezwolle\FilamentCms\Models\ResourceLookup;
use Enrisezwolle\FilamentCms\Observers\SluggableObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * @mixin Model|IsSluggable
 */
trait Sluggable
{
    public static function bootSluggable(): void
    {
        self::observe(SluggableObserver::class);
    }

    public function getResourceName(): string
    {
        return Str::of(self::class)->afterLast('\\')->lower()->slug()->toString();
    }

    public function resourceLookup(): MorphOne
    {
        return $this->morphOne(ResourceLookup::class, 'model');
    }

    public function getSluggableSlug(): string
    {
        return $this->getAttribute($this->sluggableAttribute());
    }

    public function sluggableParent(): ?string
    {
        return null;
    }

    public function sluggableChildren(): ?string
    {
        return null;
    }

    public function sluggableAttribute(): string
    {
        return 'slug';
    }

    public function getFullPath(): ?string
    {
        return $this->resourceLookup?->path;
    }

    /**
     * If a prefix is given it will not attempt to get the path from its parent
     * As it assumes this has been generated previously.
     *
     * When a suffix is given, it will assume it has been cascading up through parent
     * relationship and will prefix its own slug.
     */
    public function getSluggablePath(?string $prefix = null, ?string $suffix = null): string
    {
        throw_if(! empty($prefix) && ! empty($suffix), new \Exception('Prefix and suffix cannot be set simultaneously.'));

        $parentRelation = $this->sluggableParent();
        $slug = $this->getSluggableSlug();

        // Contains prefix, simply suffix own slug
        if (! is_null($prefix)) {
            return $prefix.'/'.$slug;
        }

        $path = implode('/', array_filter([$slug, $suffix]));

        // It has no parent set, so the slug itself can simply be returned
        if (empty($parentRelation)) {
            return $path;
        }

        /** @var Model|IsSluggable $parentModel */
        $parentModel = $this->{$parentRelation};

        // It has no parent model, so the slug itself can simply be returned
        if (is_null($parentModel)) {
            return $path;
        }

        $parentClassName = get_class($parentModel);

        // Assert the parent is a valid parent
        throw_if(! is_subclass_of($parentModel, Model::class), new \Exception('Sluggable only supports model classes.'));
        throw_if(! in_array(IsSluggable::class, class_implements($parentModel)), new \Exception("Model '{$parentClassName}' does not implement the Sluggable interface."));

        return $parentModel->getSluggablePath(suffix: $path);
    }
}
