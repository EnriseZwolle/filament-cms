<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IsSluggable
{
    public function getResourceName(): string;

    public function sluggableParent(): ?string;

    public function sluggableChildren(): ?string;

    public function sluggableAttribute(): string;

    public function getSluggableSlug(): string;

    public function getSluggablePath(?string $prefix = null, ?string $suffix = null): string;

    public function resourceLookup(): MorphOne;
}
