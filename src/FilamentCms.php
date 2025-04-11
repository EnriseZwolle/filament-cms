<?php

namespace Enrisezwolle\FilamentCms;

use Enrisezwolle\FilamentCms\Traits\HasVisibility;
use Enrisezwolle\FilamentCms\Traits\Publishable;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;

class FilamentCms {
    public static function hashModel(string $className, mixed $id): string
    {
        return hash('sha256', "{$className}_{$id}");
    }

    public static function visibilityFields(
        string $model,
        bool $visibility = true,
        bool $publishable = true,
        bool $publishFromRequired = false,
        bool $publishUntilRequired = false,
    ): Fieldset
    {
        $fields = [];

        $modelInstance = new $model;


        if ($visibility) {
            assert(in_array(HasVisibility::class, class_uses_recursive($modelInstance)));

            $fields[] = Forms\Components\Toggle::make($modelInstance->getVisibleKey())
                ->label(__('Visible'))
                ->default(true)
                ->required();
        }

        if ($publishable) {
            assert(in_array(Publishable::class, class_uses_recursive($modelInstance)));

            if ($publishFromRequired) {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishFromKey())
                    ->label(__('Publish from'))
                    ->required()
                    ->default(today())
                    ->live(onBlur: true);
            } else {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishFromKey())
                    ->label(__('Publish from'))
                    ->nullable()
                    ->live(onBlur: true);
            }

            if ($publishUntilRequired) {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishUntilKey())
                    ->label(__('Publish until'))
                    ->required()
                    ->live(onBlur: true)
                    ->after('publish_from');
            } else {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishUntilKey())
                    ->label(__('Publish until'))
                    ->nullable()
                    ->live(onBlur: true)
                    ->after('publish_from');
            }
        }


        return Fieldset::make()
            ->label(__('Visibility'))
            ->columns(1)
            ->schema($fields);
    }

    public static function seoFields(): Group
    {
        return Group::make([
            Fieldset::make('seo')
                ->label(__('SEO'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->label(__('SEO title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('The recommended length is between :min and :max characters', [
                            'min' => 50,
                            'max' => 60,
                        ])),

                    Forms\Components\Textarea::make('description')
                        ->label(__('SEO description'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('The recommended length is between :min and :max characters', [
                            'min' => 120,
                            'max' => 170,
                        ])),

                    Forms\Components\Toggle::make('noindex')
                        ->label(__('Don\'t allow index'))
                        ->default(false),

                    Forms\Components\Toggle::make('nofollow')
                        ->label(__('Allow follow'))
                        ->default(false)
                        ->helperText(__('Allow search engines to follow links on this resource')),
                ]),

            Forms\Components\Fieldset::make('og')
                ->label(__('Social media'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('og_title')
                        ->label(__('Title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('This title will be used when sharing on social media platforms')),

                    Forms\Components\FileUpload::make('image')
                        ->label(__('Image'))
                        ->image()
                        ->nullable()
                        ->helperText(__('This image will be used when sharing on social media platforms. An image with the dimensions of :width by :height is recommended for the best results.', ['width' => 1200, 'height' => 630])),
                ]),
        ]);
    }
}
