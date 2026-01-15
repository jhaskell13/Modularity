<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class MakeModuleMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-migration {moduleName} {migrationKey?} {--new-migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and move migration for the module to the module destination.';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $files)
    {
        $moduleName = $this->argument('moduleName');
        $modulePath = base_path("App/Modules/{$moduleName}");
        $makeNewMigration = $this->option('new-migration') ?? false;

        if (! $files->isDirectory("{$modulePath}/Database/Migrations")) {
            $files->makeDirectory("{$modulePath}/Database/Migrations", 0755, true, true);
        }

        $migrationKey = $this->argument('migrationKey') ?? strtolower($moduleName);
        if ($migrationKey && $makeNewMigration) {
            Artisan::call("make:migration create_{$migrationKey}_table");
        }

        $migration = collect(glob(database_path('migrations/*.php')))->sortByDesc(fn ($file) => filemtime($file))->first();

        if (! $migration) {
            $this->error('No migration found.');
            return 1;
        }

        $baseMigrationName = basename($migration);
        $files->move($migration, "{$modulePath}/Database/Migrations/{$baseMigrationName}");
        $files->delete($migration);
    }
}
