<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Filament\Forms;

class QuoteBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'quote';
    }

    public static function getLabel(): string
    {
        return __('Quote');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\Textarea::make('quote')
                ->label(__('Quote'))
                ->string()
                ->maxLength(65535)
                ->required(),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'quote' => fake()->sentence(),
        ];
    }
}
