<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module with starter filament resource and subdirectories.';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $files)
    {
        $moduleName = $this->argument('name');
        $modulePath = base_path('App/Modules/' . $moduleName);

        // Model should be specified, otherwise assume plural convention (Contacts, Forms, etc.) and remove the last `s`
        $modelName = $this->option('model') ?? substr($moduleName, 0, -1);
        $modelPath = "App/Modules/{$moduleName}/Models/{$modelName}";

        // Create the main and subdirectories
        if ($files->isDirectory($modulePath)) {
            $this->error("Module directory '{$moduleName}' already exists!");
            return 1;
        }

        $files->makeDirectory($modulePath, 0755, true, true);

        // Create model and factory
        $files->makeDirectory("{$modulePath}/Models", 0755, true, true);
        $files->makeDirectory("{$modulePath}/Database/Factories", 0755, true, true);
        Artisan::call("make:model {$modelPath} -m -f");
        $files->move(database_path("factories/Modules/{$moduleName}/Models/{$modelName}Factory.php"), "{$modulePath}/Database/Factories/{$modelName}Factory.php");
        $files->deleteDirectory(database_path('factories/Modules'));

        // Create and move migration
        $files->makeDirectory("{$modulePath}/Database/Migrations", 0755, true, true);
        $migration = collect(glob(database_path('migrations/*.php')))->sortByDesc(fn ($file) => filemtime($file))->first();

        if (! $migration) {
            $this->error('No migration found.');
            return 1;
        }

        $baseMigrationName = basename($migration);
        $files->move($migration, "{$modulePath}/Database/Migrations/{$baseMigrationName}");
        $files->delete($migration);

        // Create Filament resources
        Artisan::call("make:filament-resource {$modelName}Resource --panel=main --model-namespace=App\\\\Modules\\\\{$moduleName}\\\\Models --no-interaction");

        // Move resources to Filament/Resources dir within the module
        $files->makeDirectory("{$modulePath}/Filament/Resources", 0755, true, true);
        $files->copyDirectory("{$modulePath}/Pages", "{$modulePath}/Filament/Resources/Pages");
        $files->deleteDirectory("{$modulePath}/Pages");
        $files->copyDirectory("{$modulePath}/Schemas", "{$modulePath}/Filament/Resources/Schemas");
        $files->deleteDirectory("{$modulePath}/Schemas");
        $files->copyDirectory("{$modulePath}/Tables", "{$modulePath}/Filament/Resources/Tables");
        $files->deleteDirectory("{$modulePath}/Tables");
        $files->copy("{$modulePath}/{$modelName}Resource.php", "{$modulePath}/Filament/Resources/{$modelName}Resource.php");
        $files->delete("{$modulePath}/{$modelName}Resource.php");

        // Create and publish provider
        Artisan::call("make:module-provider {$moduleName}ServiceProvider --module={$moduleName}");

        $this->info("Module '{$moduleName}' created successfully!");
        return 0;
    }
}
