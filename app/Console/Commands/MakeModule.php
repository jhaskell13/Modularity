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

        // Create model, migration and factory
        $files->makeDirectory("{$modulePath}/Models", 0755, true, true);
        Artisan::call("make:model {$modelPath}"); // TODO: add migration and factory setup

        // Create Filament resources
        // TODO: Move resources to Filament/Resources dir within the module
        Artisan::call("make:filament-resource {$modelName}Resource --panel=main --model-namespace=App\\\\Modules\\\\{$moduleName}\\\\Models --no-interaction");

        // Create and publish provider
        Artisan::call("make:module-provider {$moduleName}ServiceProvider --module={$moduleName}");

        $this->info("Module '{$moduleName}' created successfully!");
        return 0;
    }
}
