<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Filament\Forms;

class ImageBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'image';
    }

    public static function getLabel(): string
    {
        return __('filament-cms::blocks.image.title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\FileUpload::make('image')
                ->image()
                ->label(__('filament-cms::blocks.image.image'))
                ->required(),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'image' => fake()->image(),
        ];
    }
}
