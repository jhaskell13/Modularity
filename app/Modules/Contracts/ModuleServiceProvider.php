<?php

namespace App\Modules\Contracts;

use App\Services\ModuleManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected string $moduleKey;
    protected string $moduleName;
    protected string $moduleNamespace;
    protected array $dependencies = [];

    public function register(): void
    {
        // Override in child classes for service registration
    }

    public function boot(): void
    {
        if (!$this->isModuleActive()) {
            return;
        }

        $this->loadMigrations();
        $this->loadViews();
        $this->loadTranslations();
        $this->publishAssets();
        $this->registerPolicies();
    }

    protected function isModuleActive(): bool
    {
        // Allow registration if no user is authenticated (for installation)
        if (! Auth::check()) {
            return true;
        }

        return app(ModuleManager::class)->isActive($this->moduleKey);
    }

    protected function loadMigrations(): void
    {
        $migrationPath = $this->getModulePath('Database/Migrations');

        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }

    protected function loadViews(): void
    {
        $viewPath = $this->getModulePath('Resources/Views');

        if (is_dir($viewPath)) {
            $this->loadViewsFrom($viewPath, $this->moduleKey);
        }
    }

    protected function loadTranslations(): void
    {
        $langPath = $this->getModulePath('Resources/Lang');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleKey);
        }
    }

    protected function publishAssets(): void
    {
        // Override in child classes if needed
    }

    protected function registerPolicies(): void
    {
        // Override in child classes if needed
    }

    protected function getModulePath(string $path = ''): string
    {
        $reflection = new \ReflectionClass($this);
        $dir = dirname($reflection->getFileName());

        return $path ? $dir . '/' . $path : $dir;
    }

    public function getModuleKey(): string
    {
        return $this->moduleKey;
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }
}
