<?php

namespace App\Providers;

use App\Services\ModuleManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);

        // Register all base module providers
        foreach (config('modules.base_modules') as $moduleKey) {
            $moduleConfig = config("modules.available.{$moduleKey}");
            if ($moduleConfig && isset($moduleConfig['provider'])) {
                $this->app->register($moduleConfig['provider']);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
