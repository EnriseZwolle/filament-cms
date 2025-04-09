<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVisibility
{
    public function getVisibleKey(): string
    {
        return 'visible';
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where($this->getVisibleKey(), true);
    }

    public function scopeInvisible(Builder $query): void
    {
        $query->where($this->getVisibleKey(), false);
    }

    public function isVisible(): bool
    {
        return $this->getAttribute($this->getVisibleKey());
    }
}
