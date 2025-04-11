<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Filament\Forms;

class TextBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text';
    }

    public static function getLabel(): string
    {
        return __('filament-cms::blocks.text.title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\RichEditor::make('text')
                ->label(__('filament-cms::blocks.text.text'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->string()
                ->maxLength(65535)
                ->required(),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
        ];
    }
}
