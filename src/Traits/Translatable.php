<?php

namespace Enrisezwolle\FilamentCms\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @used-by Model
 */
trait Translatable
{
    public function translatableColumn(): string
    {
        return 'locale';
    }

    public function getTranslatableLocale(): string
    {
        return $this->getAttribute($this->translatableColumn());
    }

    public function getTranslatedUrl(): string
    {
        return url($this->getTranslatableLocale() . '/' . $this->getFullPath());
    }

    abstract public function common(): BelongsTo;
}
