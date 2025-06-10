<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface IsTranslatableCommon
{
    public function getTitle(): string;

    public function details(): HasMany;
}
