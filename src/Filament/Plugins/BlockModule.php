<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins;

use Filament\Forms;

abstract class BlockModule
{
    protected static function blocks(string $group): array
    {
        return collect(config('filament-cms.blocks.'.$group, []))
            ->map(fn (string|BaseBlock $block) => $block::make())
            ->toArray();
    }

    public static function make(string $column, string $group = 'active'): Forms\Components\Fieldset
    {
        return Forms\Components\Fieldset::make()
            ->label(__('filament-cms::blocks.title'))
            ->schema([
                Forms\Components\Builder::make($column)
                    ->label('')
                    ->addActionLabel(__('filament-cms::blocks.add_block'))
                    ->collapsible()
                    ->blocks(
                        self::blocks($group)
                    ),

            ])
            ->columns(1);
    }

    public static function reconstructBlock(string $type, array $data): BaseBlock
    {
        $blocks = collect(config('filament-cms.blocks', []))->flatten(1)
            ->filter(fn (string $class) => is_subclass_of($class, BaseBlock::class));

        $class = collect($blocks)
            ->firstWhere(fn (string|BaseBlock $block) => $block::getType() === $type);

        return new $class($data);
    }
}
