<?php

namespace Enrisezwolle\FilamentCms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Enrisezwolle\FilamentCms\Commands\FilamentCmsCommand;

class FilamentCmsServiceProvider extends PackageServiceProvider
{
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
            ->hasMigration('create_filament_cms_table')
            ->hasCommand(FilamentCmsCommand::class);
    }
}
