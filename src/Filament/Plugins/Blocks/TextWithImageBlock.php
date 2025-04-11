<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Filament\Forms;

class TextWithImageBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text-with-image';
    }

    public static function getLabel(): string
    {
        return __('filament-cms::blocks.text_with_image.title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\RichEditor::make('text')
                ->label(__('filament-cms::blocks.text_with_image.text'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->string()
                ->maxLength(65535)
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label(__('filament-cms::blocks.text_with_image.image'))
                ->image()
                ->required(),

            Forms\Components\Radio::make('position')
                ->label(__('filament-cms::blocks.text_with_image.image_position'))
                ->options([
                    'left' => __('filament-cms::blocks.text_with_image.left'),
                    'right' => __('filament-cms::blocks.text_with_image.right'),
                ])
                ->required()
                ->default('left'),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
            'image' => fake()->image(),
            'position' => fake()->randomElement(['left', 'right']),
        ];
    }
}
