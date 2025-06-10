<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IsTranslatable
{
    public function getTitle(): string;

    public function translatableColumn(): string;

    public function getTranslatableLocale(): string;

    public function getTranslatedUrl(): string;

    public function common(): BelongsTo;
}
