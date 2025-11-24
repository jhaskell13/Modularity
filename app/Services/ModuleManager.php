<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    protected $activeModules;
    protected $availableModules;
    protected $tenant;

    public function __construct()
    {
        $this->availableModules = collect(config('modules.available'));
        $this->loadActiveModules();
    }

    protected function loadActiveModules(): void
    {
        $tenant = $this->getCurrentTenant();

        if (!$tenant) {
            $this->activeModules = collect();
            return;
        }

        $cacheKey = "tenant.{$tenant->id}.modules";

        $this->activeModules = Cache::remember(
            $cacheKey,
            config('modules.cache_ttl', 3600),
            fn() => TenantModule::where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->get()
                ->keyBy('module_key')
        );
    }

    public function getCurrentTenant(): ?Tenant
    {
        if ($this->tenant) {
            return $this->tenant;
        }

        $user = Auth::user();

        if (!$user || !$user->tenant_id) {
            return null;
        }

        $this->tenant = Tenant::find($user->tenant_id);

        return $this->tenant;
    }

    public function isActive(string $moduleKey): bool
    {
        return $this->activeModules->has($moduleKey);
    }

    public function getConfiguration(string $moduleKey): array
    {
        return $this->activeModules->get($moduleKey)?->configuration ?? [];
    }

    public function getActiveModules(): Collection
    {
        return $this->activeModules;
    }

    public function getAvailableModules(): Collection
    {
        return $this->availableModules;
    }

    public function getInactiveModules(): Collection
    {
        return $this->availableModules
            ->filter(fn($config, $key) => !$this->isActive($key));
    }

    public function canActivateModule(string $moduleKey): bool
    {
        $module = $this->availableModules->get($moduleKey);

        if (!$module) {
            return false;
        }

        // Check dependencies
        foreach ($module['dependencies'] ?? [] as $dependency) {
            if (!$this->isActive($dependency)) {
                return false;
            }
        }

        return true;
    }

    public function clearCache(?int $tenantId = null): void
    {
        $tenantId = $tenantId ?? $this->getCurrentTenant()?->id;

        if ($tenantId) {
            Cache::forget("tenant.{$tenantId}.modules");
        }
    }
}
