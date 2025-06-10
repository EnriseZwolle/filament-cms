<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface IsTranslatable
{
    public function getTitle(): string;

    public function translatableColumn(): string;

    public function getTranslatableLocale(): string;

    public function getTranslatedUrl(): string;

    public function common(): BelongsTo;
}
