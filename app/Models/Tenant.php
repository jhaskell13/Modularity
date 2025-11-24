<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'database',
        'settings',
        'plan',
        'is_active',
        'trial_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    public function modules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function hasModule(string $moduleKey): bool
    {
        return $this->modules()
            ->where('module_key', $moduleKey)
            ->where('is_active', true)
            ->exists();
    }

    public function activateModule(string $moduleKey, array $configuration = []): TenantModule
    {
        return $this->modules()->updateOrCreate(
            ['module_key' => $moduleKey],
            [
                'is_active' => true,
                'configuration' => $configuration,
                'activated_at' => now(),
            ]
        );
    }

    public function deactivateModule(string $moduleKey): bool
    {
        return $this->modules()
            ->where('module_key', $moduleKey)
            ->update(['is_active' => false]);
    }

    public function addUsers(array $users): void
    {
        foreach ($users as $user) {
            $user->tenant_id = $this->id;
        }
    }
}
