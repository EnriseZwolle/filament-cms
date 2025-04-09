<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins;

interface BlockContract
{
    public static function getType(): string;

    public static function getLabel(): string;

    public static function getFields(): array;

    public static function factory(): ?array;
}
