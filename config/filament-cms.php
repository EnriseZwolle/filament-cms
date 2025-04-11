<?php

use Enrisezwolle\FilamentCms\Filament\Plugins\Blocks;

return [
    'models' => [
        //
    ],

    'views' => [
        'pages.{resource}.{label}',
        'pages.{resource}.index',
        'pages.{resource}',
        '{resource}.{label}',
        '{resource}.index',
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
