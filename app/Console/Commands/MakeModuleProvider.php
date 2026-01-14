<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeModuleProvider extends GeneratorCommand
{
    protected $signature = 'make:module-provider {name} {--module=}';

    protected $description = 'Create a new module service provider';

    protected $type = 'Module Provider';

    protected function getStub()
    {
        return __DIR__ . '/stubs/module-provider.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $module = $this->option('module');

        if ($module) {
            return "App\\Modules\\{$module}";
        }

        return $rootNamespace . '\\Providers';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $module = $this->option('module');

        if ($module) {
            return base_path('App/Modules/' . $module . '/' . str_replace('\\', '/', class_basename($name)) . '.php');
        }

        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $module = $this->option('module');

        if ($module) {
            $stub = str_replace('{{ moduleName }}', $module, $stub);
            $stub = str_replace('{{ moduleKey }}', Str::lower($module), $stub);
        }

        return $stub;
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $stub;
    }

    public function handle()
    {
        $module = $this->option('module');

        if ($module) {
            // Create the module directory structure if it doesn't exist
            $modulePath = base_path('App/Modules/' . $module);

            if (!is_dir($modulePath)) {
                mkdir($modulePath, 0755, true);
            }

            $providersPath = $modulePath;

            if (!is_dir($providersPath)) {
                mkdir($providersPath, 0755, true);
            }
        }

        // Generate the provider
        $result = parent::handle();

        if ($result === false) {
            return false;
        }

        // Automatically register the provider
        if ($module) {
            $this->registerProvider($module);
        }

        return $result;
    }

    protected function registerProvider($module)
    {
        $providerClass = "App\\Modules\\{$module}\\" . class_basename($this->argument('name'));

        $providersPath = base_path('bootstrap/providers.php');

        // Check if the file exists
        if (!file_exists($providersPath)) {
            $this->warn('bootstrap/providers.php not found. Please register the provider manually.');
            $this->line("Add this to your providers: {$providerClass}::class");
            return;
        }

        $providersContent = file_get_contents($providersPath);

        // Check if provider is already registered
        if (strpos($providersContent, $providerClass) !== false) {
            $this->warn('Provider already registered in bootstrap/providers.php');
            return;
        }

        // Pattern to match the return array
        // Handles both empty arrays and arrays with existing providers
        $pattern = '/(return\s*\[\s*)(.*?)(\s*\];)/s';

        if (preg_match($pattern, $providersContent, $matches)) {
            $existingProviders = trim($matches[2]);

            // Build the new provider entry
            $newProvider = "    {$providerClass}::class,\n";

            // If array is empty, add without extra newline
            if (empty($existingProviders)) {
                $replacement = $matches[1] . "\n" . $newProvider . $matches[3];
            } else {
                // Add to the end of existing providers
                $replacement = $matches[1] . $existingProviders . "\n" . $newProvider . $matches[3];
            }

            $providersContent = preg_replace($pattern, $replacement, $providersContent, 1);

            file_put_contents($providersPath, $providersContent);

            $this->info('Provider registered in bootstrap/providers.php');
            $this->line("Registered: <fg=green>{$providerClass}::class</>");
        } else {
            $this->warn('Could not automatically register provider. Please add it manually to bootstrap/providers.php');
            $this->line("Add this line: {$providerClass}::class,");
        }
    }
}
