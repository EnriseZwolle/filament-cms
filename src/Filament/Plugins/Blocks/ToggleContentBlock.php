<?php

namespace Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

use Enrisezwolle\FilamentCms\Filament\Plugins\BaseBlock;
use Enrisezwolle\FilamentCms\Filament\Plugins\BlockModule;
use Filament\Forms;

class ToggleContentBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'toggle-content';
    }

    public static function getLabel(): string
    {
        return __('filament-cms::blocks.toggle_content.title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label(__('filament-cms::blocks.toggle_content.name'))
                ->required()
                ->string()
                ->maxLength(255),

            BlockModule::make('content', 'toggle_content'),
        ];
    }

    public static function factory(): ?array
    {
        return null;
    }
}
