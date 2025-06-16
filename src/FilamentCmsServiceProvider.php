<?php

namespace Enrisezwolle\FilamentCms;

use Enrisezwolle\FilamentCms\Commands\FilamentCmsCommand;
use Enrisezwolle\FilamentCms\Http\Controllers\ViewResourceController;
use Enrisezwolle\FilamentCms\Http\Middleware\TranslatableMiddleware;
use Enrisezwolle\FilamentCms\Livewire\LanguageSwitcher;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCmsServiceProvider extends PackageServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Route::macro('filamentCms', function () {
            Route::fallback(ViewResourceController::class)
                ->middleware(TranslatableMiddleware::class);
        });

        Blade::componentNamespace('Enrisezwolle\\FilamentCms\\View\\Components', 'filament-cms');

        Livewire::component('language-switcher', LanguageSwitcher::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-cms')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('create_resource_lookups_table')
            ->hasMigration('create_seo_table')
            ->hasMigration('create_system_labels_table')
            ->hasCommand(FilamentCmsCommand::class);
    }
}
