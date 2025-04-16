<?php

use Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

return [
    'views' => [
        'pages.{resource}.{label}',
        'pages.{resource}.show',
        'pages.{resource}',
        '{resource}.{label}',
        '{resource}.show',
        '{label}',
        '{resource}',
        'index',
    ],

    'blocks' => [
        'active' => [
            Blocks\TextBlock::class,
            Blocks\TextWithTitleBlock::class,
            Blocks\TextWithImageBlock::class,
            Blocks\ImageBlock::class,
            Blocks\QuoteBlock::class,
            Blocks\ToggleContentBlock::class,
        ],

        'toggle_content' => [
            Blocks\TextBlock::class,
            Blocks\TextWithTitleBlock::class,
            Blocks\TextWithImageBlock::class,
            Blocks\ImageBlock::class,
            Blocks\QuoteBlock::class,
        ],
    ],
];
