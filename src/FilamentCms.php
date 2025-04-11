<?php

namespace Enrisezwolle\FilamentCms;

use Enrisezwolle\FilamentCms\Traits\HasVisibility;
use Enrisezwolle\FilamentCms\Traits\Publishable;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;

class FilamentCms
{
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
    ): Fieldset {
        $fields = [];

        $modelInstance = new $model;

        if ($visibility) {
            assert(in_array(HasVisibility::class, class_uses_recursive($modelInstance)));

            $fields[] = Forms\Components\Toggle::make($modelInstance->getVisibleKey())
                ->label(__('filament-cms::visibility.visible'))
                ->default(true)
                ->required();
        }

        if ($publishable) {
            assert(in_array(Publishable::class, class_uses_recursive($modelInstance)));

            if ($publishFromRequired) {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishFromKey())
                    ->label(__('filament-cms::visibility.publish_from'))
                    ->required()
                    ->default(today())
                    ->live(onBlur: true);
            } else {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishFromKey())
                    ->label(__('filament-cms::visibility.publish_from'))
                    ->nullable()
                    ->live(onBlur: true);
            }

            if ($publishUntilRequired) {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishUntilKey())
                    ->label(__('filament-cms::visibility.publish_until'))
                    ->required()
                    ->live(onBlur: true)
                    ->after('publish_from');
            } else {
                $fields[] = Forms\Components\DatePicker::make($modelInstance->getPublishUntilKey())
                    ->label(__('filament-cms::visibility.publish_until'))
                    ->nullable()
                    ->live(onBlur: true)
                    ->after('publish_from');
            }
        }

        return Fieldset::make()
            ->label(__('filament-cms::visibility.title'))
            ->columns(1)
            ->schema($fields);
    }

    public static function seoFields(): Group
    {
        return Group::make([
            Fieldset::make('seo')
                ->label(__('filament-cms::seo.seo'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->label(__('filament-cms::seo.seo_title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('filament-cms::seo.seo_title_help', [
                            'min' => 50,
                            'max' => 60,
                        ])),

                    Forms\Components\Textarea::make('description')
                        ->label(__('filament-cms::seo.seo_description'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('filament-cms::seo.seo_description_help', [
                            'min' => 120,
                            'max' => 170,
                        ])),

                    Forms\Components\Toggle::make('noindex')
                        ->label(__('filament-cms::seo.noindex'))
                        ->helperText(__('filament-cms::seo.noindex_help'))
                        ->default(false),

                    Forms\Components\Toggle::make('nofollow')
                        ->label(__('filament-cms::seo.nofollow'))
                        ->default(false)
                        ->helperText(__('filament-cms::seo.nofollow_help')),
                ]),

            Forms\Components\Fieldset::make('og')
                ->label(__('filament-cms::seo.social_media'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('og_title')
                        ->label(__('filament-cms::seo.og_title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('filament-cms::seo.og_title_help')),

                    Forms\Components\FileUpload::make('image')
                        ->label(__('filament-cms::seo.og_image'))
                        ->image()
                        ->nullable()
                        ->helperText(__('filament-cms::seo.og_image_help', ['width' => 1200, 'height' => 630])),
                ]),
        ]);
    }
}
