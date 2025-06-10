<?php

namespace Enrisezwolle\FilamentCms\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IsTranslatableCommon
{
    public function getTitle(): string;
}
