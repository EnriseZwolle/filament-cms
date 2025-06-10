<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IsTranslatable
{
    public function getTitle(): string;

    public function translatableColumn(): string;

    public function getTranslatableLocale(): string;

    public function getTranslatedUrl(): string;
}
