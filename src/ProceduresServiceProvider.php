<?php

namespace Wepa\Procedures;

use Database\Seeders\DatabaseSeeder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wepa\Procedures\Commands\ProceduresDemoCommand;
use Wepa\Procedures\Commands\ProceduresInstallCommand;
use Wepa\Procedures\Commands\ProceduresUninstallCommand;
use Wepa\Procedures\Commands\ProceduresUpdateCommand;
use Wepa\Procedures\Database\seeders\DefaultSeeder;

class ProceduresServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $this->hasSeeders([DefaultSeeder::class]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Pages
        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages/Vendor/Procedures'),
        ], ['procedures', 'procedures-pages']);

        // Components
        $this->publishes([
            __DIR__.'/../resources/js/Components' => resource_path('js/Vendor/Procedures'),
        ], ['procedures', 'procedures-components']);

        $this->publishes([
            __DIR__.'/../tests/Unit' => base_path('tests/Unit/Procedures'),
            __DIR__.'/../tests/Feature' => base_path('tests/Feature/Procedures'),
        ], ['procedures-tests']);
    }
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('procedures')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoutes(['web', 'admin', 'api'])
            ->hasMigration('create_procedures_table')
            ->hasCommands([
                ProceduresInstallCommand::class,
                ProceduresUninstallCommand::class,
                ProceduresDemoCommand::class,
                ProceduresUpdateCommand::class
            ]);
    }

    protected function hasSeeders(array $seeders): void
    {
        $this->callAfterResolving(DatabaseSeeder::class,
            function ($cb) use ($seeders) {
                $cb->call($seeders);
            });
    }
}
