<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Filament\Forms;

class TextWithTitleBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text-with-title';
    }

    public static function getLabel(): string
    {
        return __('filament-cms::blocks.text_with_title.title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label(__('filament-cms::blocks.text_with_title.name'))
                ->required()
                ->string()
                ->maxLength(255),

            Forms\Components\RichEditor::make('text')
                ->label(__('filament-cms::blocks.text_with_title.text'))
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
            'title' => fake()->words(5, true),
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
        ];
    }
}
