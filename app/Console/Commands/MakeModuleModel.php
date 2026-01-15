<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class MakeModuleModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-model {moduleName} {--model=} {--migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model for specified module and corresponding migration';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $files)
    {
        $moduleName = $this->argument('moduleName');
        $modulePath = base_path('App/Modules/' . $moduleName);

        // Model should be specified, otherwise assume plural convention (Contacts, Forms, etc.) and remove the last `s`
        $modelName = $this->option('model') ?? substr($moduleName, 0, -1);
        $modelPath = "App/Modules/{$moduleName}/Models/{$modelName}";

        // Create the main and subdirectories
        if ($files->exists($modelPath)) {
            $this->error("Model '{$modelName}' already exists!");
            return 1;
        }

        if (! $files->isDirectory($modulePath)) {
            $files->makeDirectory($modulePath, 0755, true, true);
        }

        // Create model and factory
        if (! $files->isDirectory("{$modulePath}/Models")) {
            $files->makeDirectory("{$modulePath}/Models", 0755, true, true);
        }

        if (! $files->isDirectory("{$modulePath}/Database/Factories")) {
            $files->makeDirectory("{$modulePath}/Database/Factories", 0755, true, true);
        }

        Artisan::call("make:model {$modelPath} -m -f");
        $files->move(database_path("factories/Modules/{$moduleName}/Models/{$modelName}Factory.php"), "{$modulePath}/Database/Factories/{$modelName}Factory.php");
        $files->deleteDirectory(database_path('factories/Modules'));

        Artisan::call("make:module-migration {$moduleName}");
    }
}
