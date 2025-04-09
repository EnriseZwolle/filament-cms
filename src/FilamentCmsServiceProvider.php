<?php

namespace Enrisezwolle\FilamentCms;

use Enrisezwolle\FilamentCms\Commands\FilamentCmsCommand;
use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Enrisezwolle\FilamentCms\Exceptions\SluggableInterfaceNotImplemented;
use Enrisezwolle\FilamentCms\Http\Controllers\ViewResourceController;
use Enrisezwolle\FilamentCms\Observers\SluggableObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
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
            Route::fallback(ViewResourceController::class);
        });

        foreach (config('filament-cms.models') as $model) {
            throw_if(! is_subclass_of($model, Model::class), new \Exception('Sluggable only supports model classes.'));
            throw_if(! in_array(IsSluggable::class, class_implements($model)), new SluggableInterfaceNotImplemented($model));

            $model::observe(SluggableObserver::class);
        }
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
            ->hasCommand(FilamentCmsCommand::class);
    }
}
