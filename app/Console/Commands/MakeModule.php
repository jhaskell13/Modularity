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

        // Create model, factory, and migration
        Artisan::call("make:module-model {$moduleName} --model={$modelName} --migration");

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
