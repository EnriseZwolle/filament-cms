<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\Filament\Plugins;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;
use stdClass;

abstract class BaseBlock implements BlockContract
{
    public stdClass $data;

    public function __construct(array $data)
    {
        $this->data = (object) $data;
    }

    public static function make(): Block
    {
        return Block::make(static::getType())
            ->schema([
                ...static::getFields(),

                Hidden::make('id')
                    ->default(fn () => Str::uuid()->toString()),
            ])
            ->label(static::getLabel());
    }

    public function __get($name)
    {
        return optional($this->data)->{$name};
    }
}
